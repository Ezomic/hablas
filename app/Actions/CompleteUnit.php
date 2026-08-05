<?php

namespace App\Actions;

use App\Actions\Srs\EnrollInSrs;
use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\UnitProgressStatus;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use RuntimeException;

class CompleteUnit
{
    public function handle(User $user, Unit $unit): UserUnitProgress
    {
        $progress = UserUnitProgress::query()->updateOrCreate(
            ['user_id' => $user->id, 'unit_id' => $unit->id],
            ['status' => UnitProgressStatus::Completed, 'completed_at' => now()],
        );

        $this->enrollUnitContent($user, $unit);

        (new RecordStreakActivity)->handle($user);

        return $progress;
    }

    /**
     * Completing a unit is what puts its material into the review deck. Cards
     * are enrolled against the unit's own language rather than the user's
     * current one, and EnrollInSrs is a firstOrCreate, so re-completing a unit
     * never duplicates a card or resets its scheduling.
     */
    private function enrollUnitContent(User $user, Unit $unit): void
    {
        $language = $unit->language;

        if ($language === null) {
            throw new RuntimeException("Unit {$unit->id} has no language.");
        }

        $enroll = new EnrollInSrs;

        foreach ($unit->vocabularyItems()->get() as $vocabularyItem) {
            $enroll->handle($user, $language, $vocabularyItem);
        }

        foreach ($unit->grammarPoints()->get() as $grammarPoint) {
            $enroll->handle($user, $language, $grammarPoint);
        }
    }
}
