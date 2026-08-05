<?php

namespace App\Actions\Streaks;

use App\Models\Streak;
use App\Models\User;
use Carbon\CarbonImmutable;

class RecordStreakActivity
{
    public function __construct(
        private readonly ReconcileStreak $reconcileStreak = new ReconcileStreak,
    ) {}

    public function handle(User $user): Streak
    {
        $streak = $this->reconcileStreak->handle($user);
        $today = CarbonImmutable::today();

        if ($streak->last_activity_date !== null && $streak->last_activity_date->isSameDay($today)) {
            return $streak;
        }

        $newLength = $streak->current_length + 1;

        $streak->forceFill([
            'current_length' => $newLength,
            'longest_length' => max($streak->longest_length, $newLength),
            'last_activity_date' => $today,
        ])->save();

        return $streak;
    }
}
