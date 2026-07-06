<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\UnitProgressStatus;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;

class CompleteUnit
{
    public function handle(User $user, Unit $unit): UserUnitProgress
    {
        $progress = UserUnitProgress::query()->updateOrCreate(
            ['user_id' => $user->id, 'unit_id' => $unit->id],
            ['status' => UnitProgressStatus::Completed, 'completed_at' => now()],
        );

        (new RecordStreakActivity)->handle($user);

        return $progress;
    }
}
