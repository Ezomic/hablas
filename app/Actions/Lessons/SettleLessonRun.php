<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonRunStatus;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;

final class SettleLessonRun
{
    public function __construct(
        private readonly CompleteLessonRun $completeLessonRun = new CompleteLessonRun,
    ) {}

    /**
     * Completes the run when it is settled and not yet completed. Idempotent,
     * so a replayed answer or a page load finishes a run whose completion
     * once failed.
     *
     * Lessons and practice runs are settled when every exercise has a right
     * answer, a self-checked one, or a flag. Check-kind runs give no verdict
     * and nothing comes back, so they are settled when every probe has an
     * answer. In both, a skipped exercise waits for its substitute.
     */
    public function handle(LessonRun $run): bool
    {
        if ($run->status === LessonRunStatus::Completed) {
            return true;
        }

        if (! $this->isSettled($run)) {
            return false;
        }

        $this->completeLessonRun->handle($run);

        return true;
    }

    private function isSettled(LessonRun $run): bool
    {
        $planIds = $run->planExerciseIds();

        if ($planIds === []) {
            return false;
        }

        $answers = [];

        foreach ($run->answers()->get() as $answer) {
            $answers[$answer->lesson_exercise_id][] = $answer;
        }

        $substitutes = [];

        foreach (LessonExercise::query()->whereIn('substitute_for_id', $planIds)->get(['id', 'substitute_for_id']) as $substitute) {
            $substitutes[(int) $substitute->substitute_for_id] = $substitute->id;
        }

        $isCheck = $run->kind->isCheck();

        foreach ($planIds as $id) {
            $own = $answers[$id] ?? [];

            if ($own === []) {
                return false;
            }

            if ($this->settles($own, $isCheck)) {
                continue;
            }

            $skipped = array_filter($own, fn (LessonAnswer $answer): bool => $answer->skipped);

            if ($skipped === []) {
                return false;
            }

            if (! isset($substitutes[$id])) {
                continue;
            }

            if (! $this->settles($answers[$substitutes[$id]] ?? [], $isCheck)) {
                return false;
            }
        }

        return true;
    }

    /** @param  list<LessonAnswer>  $answers */
    private function settles(array $answers, bool $isCheck): bool
    {
        foreach ($answers as $answer) {
            if ($answer->skipped) {
                continue;
            }

            if ($isCheck || $answer->settlesExercise()) {
                return true;
            }
        }

        return false;
    }
}
