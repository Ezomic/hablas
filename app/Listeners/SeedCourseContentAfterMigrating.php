<?php

declare(strict_types=1);

namespace App\Listeners;

use Database\Seeders\ContentSeeder;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;

class SeedCourseContentAfterMigrating
{
    /**
     * Course content only exists in the seeders, and app-deploy runs
     * `artisan migrate` on every release but never db:seed. Seeding here is
     * how a content change reaches production, including on a release with
     * no pending migrations. ContentSeeder is idempotent and creates no users,
     * and a seeder that throws makes migrate exit non-zero, so app-deploy
     * keeps the old release live.
     */
    public function handle(CommandFinished $event): void
    {
        if ($event->command !== 'migrate' || $event->exitCode !== 0 || $event->input->getOption('pretend') === true) {
            return;
        }

        Artisan::call('db:seed', ['--class' => ContentSeeder::class, '--force' => true], $event->output);
    }
}
