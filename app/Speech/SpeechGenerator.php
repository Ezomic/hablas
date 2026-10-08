<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;
use App\Models\SpeechClip;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Throwable;

final class SpeechGenerator
{
    private const BATCH = 10;

    private const SHOWN_ERRORS = 3;

    private const NORMAL_BYTES_PER_CHARACTER = 550;

    private const SLOW_BYTES_PER_CHARACTER = 690;

    public function __construct(
        private readonly SpeechInventory $inventory,
        private readonly SpeechEngineManager $engines,
        private readonly AudioEncoder $encoder,
        private readonly Repository $config,
        private readonly Factory $filesystem,
        private readonly SpeechFailures $failures,
    ) {}

    /**
     * @param  list<SpeechSpeed>  $speeds
     * @param  Closure(string): void  $progress
     */
    public function generate(string $language, VoiceConfig $voice, array $speeds, bool $dryRun, ?int $limit, bool $force, Closure $progress, bool $charactersOnly = false): SpeechRunReport
    {
        $disk = $this->disk();
        $skipped = 0;
        $pending = [];

        foreach ($this->inventory->clips($language, [$voice], $speeds, $charactersOnly) as $clip) {
            if (! $force && $disk->exists($clip->path())) {
                $skipped++;
            } else {
                $pending[] = $clip;
            }
        }

        $pending = $this->limited($pending, $limit);

        if ($dryRun) {
            return new SpeechRunReport(count($pending), $skipped, 0, 0, $this->estimate($pending));
        }

        $disk->delete(array_values(array_filter(
            $disk->allFiles("speech/{$language}/{$voice->id}"),
            fn (string $path): bool => str_ends_with($path, '.tmp'),
        )));

        $this->failures->drain();
        $shown = 0;
        $generated = 0;
        $failed = 0;
        $bytes = 0;

        foreach ($speeds as $speed) {
            $forSpeed = array_values(array_filter($pending, fn (SpeechClipSpec $clip): bool => $clip->speed === $speed));

            foreach (array_chunk($forSpeed, self::BATCH) as $batch) {
                $stored = $this->generateBatch($voice, $speed, $batch, $disk);

                $generated += count($stored);
                $failed += count($batch) - count($stored);
                $bytes += array_sum($stored);

                $progress("{$speed->value}: ".($generated + $failed).' of '.count($pending).' clips');

                foreach ($this->failures->drain() as $message) {
                    if ($shown++ < self::SHOWN_ERRORS) {
                        $progress("error: {$message}");
                    }
                }
            }
        }

        return new SpeechRunReport(count($pending), $skipped, $generated, $failed, $bytes);
    }

    /**
     * @param  list<SpeechClipSpec>  $batch
     * @return list<int> the stored size of each clip that was generated
     */
    private function generateBatch(VoiceConfig $voice, SpeechSpeed $speed, array $batch, Filesystem $disk): array
    {
        $wavs = $this->engines->forVoice($voice)->synthesizeBatch($voice, $speed, array_map(fn (SpeechClipSpec $clip): string => $clip->text, $batch));

        $clips = [];
        $wavList = [];

        foreach ($batch as $position => $clip) {
            if ($wavs[$position] !== null) {
                $clips[] = $clip;
                $wavList[] = $wavs[$position];
            }
        }

        $sizes = [];

        foreach ($this->encoder->encode($wavList) as $position => $audio) {
            if ($audio === null) {
                continue;
            }

            $clip = $clips[$position];
            $temporary = $clip->path().'.tmp';

            try {
                $disk->put($temporary, $audio->bytes);
                $disk->move($temporary, $clip->path());
            } catch (Throwable $error) {
                $disk->delete($temporary);
                $this->failures->record($error->getMessage());

                continue;
            }

            SpeechClip::query()->updateOrCreate(['hash' => $clip->hash], [
                'language' => $voice->language,
                'voice_id' => $voice->id,
                'speed' => $speed,
                'bytes' => strlen($audio->bytes),
                'duration_ms' => $audio->durationMs,
            ]);

            $sizes[] = strlen($audio->bytes);
        }

        return $sizes;
    }

    /**
     * @param  list<SpeechClipSpec>  $pending
     * @return list<SpeechClipSpec>
     */
    private function limited(array $pending, ?int $limit): array
    {
        if ($limit === null) {
            return $pending;
        }

        $texts = [];

        foreach ($pending as $clip) {
            $texts[$clip->text] = true;
        }

        $kept = array_flip(array_slice(array_keys($texts), 0, max($limit, 0)));

        return array_values(array_filter($pending, fn (SpeechClipSpec $clip): bool => isset($kept[$clip->text])));
    }

    /** @param  list<SpeechClipSpec>  $pending */
    private function estimate(array $pending): int
    {
        $bytes = 0;

        foreach ($pending as $clip) {
            $perCharacter = $clip->speed === SpeechSpeed::Slow ? self::SLOW_BYTES_PER_CHARACTER : self::NORMAL_BYTES_PER_CHARACTER;
            $bytes += mb_strlen($clip->text) * $perCharacter;
        }

        return $bytes;
    }

    private function disk(): Filesystem
    {
        return $this->filesystem->disk($this->config->string('speech.disk'));
    }
}
