<?php

declare(strict_types=1);

namespace App\Speech;

use App\Models\SpeechClip;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;

final class SpeechLibrary
{
    private const CHUNK = 500;

    public function __construct(
        private readonly SpeechInventory $inventory,
        private readonly SpeechVoices $voices,
        private readonly Repository $config,
        private readonly Factory $filesystem,
    ) {}

    /** @return int the number of clip rows created or corrected from the files on disk */
    public function index(): int
    {
        $disk = $this->disk();
        $onDisk = array_flip($disk->allFiles('speech'));
        $indexed = 0;

        foreach ($this->voices->languages() as $language) {
            foreach ($this->inventory->clips($language) as $clip) {
                if (! isset($onDisk[$clip->path()])) {
                    continue;
                }

                $row = SpeechClip::query()->updateOrCreate(['hash' => $clip->hash], [
                    'language' => $language,
                    'voice_id' => $clip->voice->id,
                    'speed' => $clip->speed,
                    'bytes' => $disk->size($clip->path()),
                ]);

                if ($row->wasRecentlyCreated || $row->wasChanged()) {
                    $indexed++;
                }
            }
        }

        return $indexed;
    }

    /** @return list<SpeechClipSpec> the clips of the language for its primary voice that have no row, so no audio is served for them */
    public function missing(string $language): array
    {
        $voice = $this->voices->primary($language);

        if ($voice === null) {
            return [];
        }

        $clips = $this->inventory->clips($language, [$voice]);
        $existing = [];

        foreach (array_chunk(array_map(fn (SpeechClipSpec $clip): string => $clip->hash, $clips), self::CHUNK) as $hashes) {
            foreach (SpeechClip::query()->whereIn('hash', $hashes)->get(['hash']) as $row) {
                $existing[$row->hash] = true;
            }
        }

        return array_values(array_filter($clips, fn (SpeechClipSpec $clip): bool => ! isset($existing[$clip->hash])));
    }

    /** @return array{files: list<string>, rows: int} the files and rows the current corpus no longer asks for, removed only when forced */
    public function prune(bool $force): array
    {
        $paths = [];
        $hashes = [];

        foreach ($this->voices->languages() as $language) {
            foreach ($this->inventory->clips($language) as $clip) {
                $paths[$clip->path()] = true;
                $hashes[$clip->hash] = true;
            }
        }

        $disk = $this->disk();
        $orphanFiles = array_values(array_filter($disk->allFiles('speech'), fn (string $path): bool => ! isset($paths[$path])));
        $orphanRows = SpeechClip::query()->get(['id', 'hash'])
            ->reject(fn (SpeechClip $row): bool => isset($hashes[$row->hash]))
            ->pluck('id')
            ->all();

        if ($force) {
            $disk->delete($orphanFiles);

            foreach (array_chunk($orphanRows, self::CHUNK) as $ids) {
                SpeechClip::query()->whereIn('id', $ids)->delete();
            }
        }

        return ['files' => $orphanFiles, 'rows' => count($orphanRows)];
    }

    private function disk(): Filesystem
    {
        return $this->filesystem->disk($this->config->string('speech.disk'));
    }
}
