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

// Starts after the 08:00 UTC digest (10:00 or 09:00 in Amsterdam), so a
// reminder never lands just before it, and stops before the evening is over.
Schedule::command(SendDueReviewReminders::class)
    ->hourly()
    ->between('12:00', '21:00')
    ->timezone('Europe/Amsterdam')
    ->withoutOverlapping();
