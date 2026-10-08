<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\SpeechSpeed;
use App\Speech\SpeechGenerator;
use App\Speech\SpeechVoices;
use App\Speech\VoiceConfig;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('speech:generate {language? : The language code, every configured language by default} {--voice= : The voice id or name, the primary voice by default} {--speed= : normal or slow, both by default} {--dry-run : Count what would be generated without writing} {--limit= : Generate at most this many texts} {--force : Regenerate clips whose file exists} {--characters : Only the story and dialogue lines this voice speaks}')]
#[Description('Generate the speech audio for the spoken strings of a language')]
class SpeechGenerate extends Command
{
    public function handle(SpeechVoices $voices, SpeechGenerator $generator): int
    {
        $speeds = $this->speeds();

        if ($speeds === null) {
            $this->error('The speed must be normal or slow.');

            return self::FAILURE;
        }

        $language = $this->argument('language');
        $languages = is_string($language) ? [$language] : $voices->languages();
        $limit = $this->option('limit');

        if ($limit !== null && preg_match('/^[1-9][0-9]*$/', $limit) !== 1) {
            $this->error('The limit must be a positive integer.');

            return self::FAILURE;
        }

        $failed = false;

        foreach ($languages as $code) {
            $voice = $this->voice($voices, $code);

            if ($voice === null) {
                $this->error("No voice configured for [{$code}].");

                return self::FAILURE;
            }

            $report = $generator->generate(
                language: $code,
                voice: $voice,
                speeds: $speeds,
                dryRun: (bool) $this->option('dry-run'),
                limit: is_string($limit) ? (int) $limit : null,
                force: (bool) $this->option('force'),
                progress: fn (string $line) => $this->line("  {$line}"),
                charactersOnly: (bool) $this->option('characters'),
            );

            $failed = $failed || $report->failed > 0;

            if ($this->option('dry-run')) {
                $this->info("{$code} ({$voice->id}): {$report->planned} clips to generate, {$report->skipped} already present, about ".$this->megabytes($report->bytes).' MB (estimate).');
            } else {
                $this->info("{$code} ({$voice->id}): generated {$report->generated} of {$report->planned} clips, {$report->failed} failed, {$report->skipped} skipped, {$report->bytes} bytes.");
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    /** @return list<SpeechSpeed>|null */
    private function speeds(): ?array
    {
        $option = $this->option('speed');

        if (! is_string($option)) {
            return SpeechSpeed::cases();
        }

        $speed = SpeechSpeed::tryFrom($option);

        return $speed === null ? null : [$speed];
    }

    private function voice(SpeechVoices $voices, string $language): ?VoiceConfig
    {
        $wanted = $this->option('voice');

        if (! is_string($wanted)) {
            return $voices->primary($language);
        }

        foreach ($voices->forLanguage($language) as $voice) {
            if ($voice->id === $wanted || strcasecmp($voice->voice, $wanted) === 0) {
                return $voice;
            }
        }

        return null;
    }

    private function megabytes(int $bytes): string
    {
        return number_format($bytes / 1_000_000, 1);
    }
}
