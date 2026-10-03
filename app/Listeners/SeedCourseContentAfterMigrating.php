<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Speech\SpeechLibrary;
use Database\Seeders\ContentSeeder;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class SeedCourseContentAfterMigrating
{
    public function __construct(private readonly SpeechLibrary $speechLibrary) {}

    /**
     * Course content only exists in the seeders, and app-deploy runs
     * `artisan migrate` on every release but never db:seed. Seeding here is
     * how a content change reaches production, including on a release with
     * no pending migrations. ContentSeeder is idempotent and creates no users,
     * and a seeder that throws makes migrate exit non-zero, so app-deploy
     * keeps the old release live.
     *
     * The speech clip rows are refreshed after the seed, so the freshly
     * seeded transcripts are in the corpus. That only scans the disk and
     * upserts rows, and a failure is reported without failing the migrate:
     * the pages fall back to the browser voice for any clip without a row.
     */
    public function handle(CommandFinished $event): void
    {
        if ($event->command !== 'migrate' || $event->exitCode !== 0 || $event->input->getOption('pretend') === true) {
            return;
        }

        Artisan::call('db:seed', ['--class' => ContentSeeder::class, '--force' => true], $event->output);

        try {
            $event->output->writeln("Indexed {$this->speechLibrary->index()} speech clips.");
        } catch (Throwable $exception) {
            report($exception);
            $event->output->writeln('Speech clips were not indexed: '.$exception->getMessage());
        }
    }
}
