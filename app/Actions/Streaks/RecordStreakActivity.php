<?php

declare(strict_types=1);

namespace App\Actions\Streaks;

use App\Models\Streak;
use App\Models\User;
use Carbon\CarbonImmutable;

final class RecordStreakActivity
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
        $earnsFreezeDay = $newLength % Streak::ACTIVE_DAYS_PER_FREEZE_DAY === 0 && ! $streak->hasFullFreezeAllowance();

        $streak->forceFill([
            'current_length' => $newLength,
            'longest_length' => max($streak->longest_length, $newLength),
            'freeze_days_remaining' => $streak->freeze_days_remaining + ($earnsFreezeDay ? 1 : 0),
            'last_activity_date' => $today,
        ])->save();

        return $streak;
    }
}
