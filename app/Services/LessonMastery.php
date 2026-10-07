<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\User;

/**
 * How much of a lesson a learner has got right first time, over all their
 * completed runs of it: an exercise counts once it was right first time in
 * any of them, so a replay only has to redo what is still missing.
 */
final class LessonMastery
{
    public function __construct(
        private readonly FirstTryRule $firstTryRule = new FirstTryRule,
    ) {}

    public function hasCompletedRun(User $user, Lesson $lesson): bool
    {
        return LessonRun::query()
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('kind', LessonRunKind::Lesson)
            ->where('status', LessonRunStatus::Completed)
            ->exists();
    }

    /**
     * The exercises of the lesson not yet right first time.
     *
     * @return list<int>
     */
    public function missing(User $user, Lesson $lesson): array
    {
        $exercises = LessonExercise::query()
            ->with('substitute')
            ->where('lesson_id', $lesson->id)
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->whereNull('probe_set')
            ->orderBy('position')
            ->get()
            ->filter(fn (LessonExercise $exercise): bool => ! $exercise->format->isTeach());

        $covers = [];

        foreach ($exercises as $exercise) {
            $covers[$exercise->id] = $exercise->id;

            if ($exercise->substitute !== null) {
                $covers[$exercise->substitute->id] = $exercise->id;
            }
        }

        $right = [];

        $answers = LessonAnswer::query()
            ->with('lessonExercise.lesson')
            ->whereIn('lesson_exercise_id', array_keys($covers))
            ->where('attempt', 1)
            ->whereHas('lessonRun', fn ($query) => $query
                ->where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->where('kind', LessonRunKind::Lesson)
                ->where('status', LessonRunStatus::Completed))
            ->get();

        foreach ($answers as $answer) {
            if ($this->firstTryRule->rightFirstTime($answer)) {
                $right[$covers[$answer->lesson_exercise_id]] = true;
            }
        }

        return array_values(array_filter($exercises->pluck('id')->all(), fn (mixed $id): bool => is_int($id) && ! isset($right[$id])));
    }

    public function isMastered(User $user, Lesson $lesson): bool
    {
        return $this->hasCompletedRun($user, $lesson) && $this->missing($user, $lesson) === [];
    }

    /** The share of the lesson's exercises right first time, 0 to 1. */
    public function share(User $user, Lesson $lesson): float
    {
        $total = LessonExercise::query()->where('lesson_id', $lesson->id)->whereNull('retired_at')->whereNull('substitute_for_id')->whereNull('probe_set')->get()->filter(fn (LessonExercise $exercise): bool => ! $exercise->format->isTeach())->count();

        return $total === 0 ? 1.0 : round(($total - count($this->missing($user, $lesson))) / $total, 4);
    }
}
