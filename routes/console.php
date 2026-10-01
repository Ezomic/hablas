<?php

declare(strict_types=1);

use App\Console\Commands\SendDailyDigests;
use App\Console\Commands\SendDueReviewReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendDailyDigests::class)->dailyAt('08:00')->withoutOverlapping();

// The noon-to-evening window is enforced by the action itself, so a manual
// run honours it too. A between() filter here would drop the 21:00 slot, as
// the clock is already a few milliseconds past it when the scheduler asks.
Schedule::command(SendDueReviewReminders::class)
    ->hourly()
    ->withoutOverlapping();
