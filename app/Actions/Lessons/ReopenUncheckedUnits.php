<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\UnitProgressStatus;
use App\Models\Lesson;
use App\Models\UserUnitProgress;
use Illuminate\Database\Query\Builder;

final class ReopenUncheckedUnits
{
    /**
     * A unit completed through the old button was completed without anything
     * being checked. Once the unit has playable lessons it goes back to In
     * progress, so its check can still be taken (as a test-out). Review
     * cards are separate rows and are kept. A unit completed through its
     * check has a completed check run and is left alone, so this is safe to
     * run on every seeding pass.
     *
     * @return int the number of units reopened
     */
    public function handle(): int
    {
        $unchecked = UserUnitProgress::query()
            ->where('status', UnitProgressStatus::Completed)
            ->whereIn('unit_id', Lesson::query()->playable()->select('unit_id'))
            ->whereNotExists(fn (Builder $query) => $query
                ->selectRaw('1')
                ->from('lesson_runs')
                ->join('lessons', 'lessons.id', '=', 'lesson_runs.lesson_id')
                ->whereColumn('lesson_runs.user_id', 'user_unit_progress.user_id')
                ->whereColumn('lessons.unit_id', 'user_unit_progress.unit_id')
                ->where('lesson_runs.status', LessonRunStatus::Completed->value)
                ->whereIn('lesson_runs.kind', [LessonRunKind::Check->value, LessonRunKind::Retake->value, LessonRunKind::TestOut->value]));

        if (! $unchecked->exists()) {
            return 0;
        }

        return $unchecked->update(['status' => UnitProgressStatus::InProgress, 'completed_at' => null]);
    }
}
