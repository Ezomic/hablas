<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Speech\SpeechNotice as Notice;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('speech:notice {--path= : Output file, defaults to NOTICE in the project root}')]
#[Description('Regenerate the NOTICE file from the speech voice config')]
class SpeechNotice extends Command
{
    public function handle(Notice $notice): int
    {
        file_put_contents($this->option('path') ?: base_path('NOTICE'), $notice->handle());

        $this->info('NOTICE written.');

        return self::SUCCESS;
    }
}
