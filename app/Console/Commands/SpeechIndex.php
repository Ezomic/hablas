<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Speech\SpeechLibrary;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('speech:index')]
#[Description('Record the speech clips found on disk in the speech_clips table')]
class SpeechIndex extends Command
{
    public function handle(SpeechLibrary $library): int
    {
        $this->info("Indexed {$library->index()} clips.");

        return self::SUCCESS;
    }
}
