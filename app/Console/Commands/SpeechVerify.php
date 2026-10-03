<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Speech\SpeechLibrary;
use App\Speech\SpeechVoices;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('speech:verify {language : The language code}')]
#[Description('List the spoken strings of a language that have no audio')]
class SpeechVerify extends Command
{
    private const SHOWN = 50;

    public function handle(SpeechLibrary $library, SpeechVoices $voices): int
    {
        $language = (string) $this->argument('language');
        $missing = $library->missing($language);

        foreach (array_slice($missing, 0, self::SHOWN) as $clip) {
            $this->line("{$clip->speed->value}: {$clip->text}");
        }

        if (count($missing) > self::SHOWN) {
            $this->line('... and '.(count($missing) - self::SHOWN).' more');
        }

        $this->info(count($missing).' clips without audio.');

        return $missing !== [] && $voices->requiresAudio($language) ? self::FAILURE : self::SUCCESS;
    }
}
