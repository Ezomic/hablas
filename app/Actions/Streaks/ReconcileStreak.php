<?php

namespace App\Actions\Streaks;

use App\Models\Streak;
use App\Models\User;
use Carbon\CarbonImmutable;

class ReconcileStreak
{
    /**
     * Passively rolls a streak forward for elapsed calendar days without any
     * new activity, consuming a freeze day per missed day if enough remain or
     * breaking the streak otherwise. Always advances `last_activity_date` to
     * yesterday when it acts, so repeated calls on the same day (e.g. from
     * multiple dashboard loads) are no-ops rather than double-consuming
     * freeze days.
     */
    public function handle(User $user): Streak
    {
        $streak = Streak::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['current_length' => 0, 'longest_length' => 0, 'freeze_days_remaining' => 2, 'last_activity_date' => null],
        );

        if ($streak->last_activity_date === null) {
            return $streak;
        }

        $today = CarbonImmutable::today();
        $daysSinceLastActivity = (int) $streak->last_activity_date->diffInDays($today);

        if ($daysSinceLastActivity <= 1) {
            return $streak;
        }

        $daysMissed = $daysSinceLastActivity - 1;

        if ($streak->freeze_days_remaining >= $daysMissed) {
            $streak->forceFill([
                'freeze_days_remaining' => $streak->freeze_days_remaining - $daysMissed,
                'last_activity_date' => $today->subDay(),
            ])->save();
        } else {
            $streak->forceFill([
                'current_length' => 0,
                'last_activity_date' => $today->subDay(),
            ])->save();
        }

        return $streak;
    }
}
