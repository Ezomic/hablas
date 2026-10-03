<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Speech\SpeechSamples;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('speech:sample')]
#[Description('Speak one test sentence per language at both speeds into storage/app/private/speech-samples for listening')]
class SpeechSample extends Command
{
    public function handle(SpeechSamples $samples): int
    {
        $paths = $samples->write();

        foreach ($paths as $path) {
            $this->line(storage_path("app/private/{$path}"));
        }

        $this->info(count($paths).' samples written.');

        return $paths === [] ? self::FAILURE : self::SUCCESS;
    }
}
