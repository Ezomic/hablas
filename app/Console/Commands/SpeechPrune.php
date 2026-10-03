<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Speech\SpeechLibrary;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('speech:prune {--force : Delete the orphans, otherwise only list them}')]
#[Description('Remove speech files and rows that the current corpus no longer asks for')]
class SpeechPrune extends Command
{
    public function handle(SpeechLibrary $library): int
    {
        $force = (bool) $this->option('force');
        $orphans = $library->prune($force);

        foreach ($orphans['files'] as $path) {
            $this->line($path);
        }

        $verb = $force ? 'Removed' : 'Would remove';
        $this->info("{$verb} ".count($orphans['files'])." files and {$orphans['rows']} rows.");

        return self::SUCCESS;
    }
}
