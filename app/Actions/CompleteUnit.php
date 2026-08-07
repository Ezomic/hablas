<?php

namespace App\Actions;

use App\Actions\Srs\EnrollPendingUnitContent;
use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\UnitProgressStatus;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use RuntimeException;

class CompleteUnit
{
    public function __construct(
        private readonly EnrollPendingUnitContent $enrollPendingUnitContent = new EnrollPendingUnitContent,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    /**
     * Completing a unit is what puts its material into the review deck, up to
     * the user's daily new-item cap. Enrolment runs across every completed
     * unit rather than just this one, so anything an earlier cap held back
     * comes in first and nothing stays stranded.
     *
     * @return array{progress: UserUnitProgress, enrolled: int, deferred: int}
     */
    public function handle(User $user, Unit $unit): array
    {
        $language = $unit->language;

        if ($language === null) {
            throw new RuntimeException("Unit {$unit->id} has no language.");
        }

        $progress = UserUnitProgress::query()->updateOrCreate(
            ['user_id' => $user->id, 'unit_id' => $unit->id],
            ['status' => UnitProgressStatus::Completed, 'completed_at' => now()],
        );

        $enrollment = $this->enrollPendingUnitContent->handle($user, $language);

        $this->recordStreakActivity->handle($user);

        return ['progress' => $progress, ...$enrollment];
    }
}
