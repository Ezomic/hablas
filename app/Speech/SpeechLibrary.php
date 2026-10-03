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
        $found = [];

        foreach ($this->voices->languages() as $language) {
            foreach ($this->inventory->clips($language) as $clip) {
                $bytes = isset($onDisk[$clip->path()]) ? $disk->size($clip->path()) : 0;

                if ($bytes > 0) {
                    $found[$clip->hash] = ['clip' => $clip, 'bytes' => $bytes];
                }
            }
        }

        $existing = [];

        foreach (array_chunk(array_keys($found), self::CHUNK) as $hashes) {
            foreach (SpeechClip::query()->whereIn('hash', $hashes)->get() as $row) {
                $existing[$row->hash] = $row;
            }
        }

        $indexed = 0;

        foreach ($found as $hash => ['clip' => $clip, 'bytes' => $bytes]) {
            $attributes = ['language' => $clip->voice->language, 'voice_id' => $clip->voice->id, 'speed' => $clip->speed, 'bytes' => $bytes];
            $row = $existing[$hash] ?? null;

            if ($row === null) {
                SpeechClip::query()->create(['hash' => $hash, ...$attributes]);
                $indexed++;
            } elseif ($row->language !== $attributes['language'] || $row->voice_id !== $attributes['voice_id'] || $row->speed !== $clip->speed || $row->bytes !== $bytes) {
                $row->update($attributes);
                $indexed++;
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

    /** @return array{files: list<string>, rows: int, total: int, refusal: string|null} the files and rows the current corpus no longer asks for, removed only when forced and not refused */
    public function prune(bool $force, bool $allowMassDelete = false): array
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
        $onDisk = array_values(array_filter($disk->allFiles('speech'), fn (string $path): bool => ! str_ends_with($path, '.tmp')));
        $orphanFiles = array_values(array_filter($onDisk, fn (string $path): bool => ! isset($paths[$path])));
        $orphanRows = SpeechClip::query()->get(['id', 'hash'])
            ->reject(fn (SpeechClip $row): bool => isset($hashes[$row->hash]))
            ->pluck('id')
            ->all();

        $refusal = null;

        if ($force && $paths === []) {
            $refusal = 'The current corpus has no clips, so every file would be an orphan.';
        } elseif ($force && ! $allowMassDelete && count($orphanFiles) * 2 > count($onDisk)) {
            $refusal = 'More than half of the files on disk are orphans; pass --allow-mass-delete to remove them.';
        }

        if ($force && $refusal === null) {
            $disk->delete($orphanFiles);

            foreach (array_chunk($orphanRows, self::CHUNK) as $ids) {
                SpeechClip::query()->whereIn('id', $ids)->delete();
            }
        }

        return ['files' => $orphanFiles, 'rows' => count($orphanRows), 'total' => count($onDisk), 'refusal' => $refusal];
    }

    private function disk(): Filesystem
    {
        return $this->filesystem->disk($this->config->string('speech.disk'));
    }
}
