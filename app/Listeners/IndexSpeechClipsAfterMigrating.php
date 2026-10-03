<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Speech\SpeechLibrary;
use Illuminate\Console\Events\CommandFinished;
use Throwable;

class IndexSpeechClipsAfterMigrating
{
    public function __construct(private readonly SpeechLibrary $library) {}

    /**
     * Clips reach the server by rsync, so the speech_clips rows that make a
     * page serve them are refreshed on every release. This only scans the
     * disk and upserts rows, with no engine and no Python, and a failure is
     * reported without failing the migrate, since the pages fall back to the
     * browser voice for any clip without a row. Listener order is not
     * guaranteed, so a transcript seeded in this same release may only be
     * indexed by the next one, or by running speech:index.
     */
    public function handle(CommandFinished $event): void
    {
        if ($event->command !== 'migrate' || $event->exitCode !== 0 || $event->input->getOption('pretend') === true) {
            return;
        }

        try {
            $event->output->writeln("Indexed {$this->library->index()} speech clips.");
        } catch (Throwable $exception) {
            report($exception);
            $event->output->writeln('Speech clips were not indexed: '.$exception->getMessage());
        }
    }
}
