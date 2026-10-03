<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Speech\SpeechNotice as Notice;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('speech:notice')]
#[Description('Regenerate the NOTICE file from the speech voice config')]
class SpeechNotice extends Command
{
    public function handle(Notice $notice): int
    {
        file_put_contents(base_path('NOTICE'), $notice->handle());

        $this->info('NOTICE written.');

        return self::SUCCESS;
    }
}
