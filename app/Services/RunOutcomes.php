<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;

/**
 * The first-try outcome of every exercise in a run's plan, which is what
 * accuracy, skill scores and the summary are all read from. A skipped
 * exercise is represented by its substitute's answer.
 */
final class RunOutcomes
{
    public function __construct(
        private readonly FirstTryRule $firstTryRule = new FirstTryRule,
    ) {}

    /**
     * @return list<array{answer: LessonAnswer, exercise: LessonExercise, origin: string, weight: int, correct: float}>
     */
    public function handle(LessonRun $run): array
    {
        $answers = $run->answers()->with('lessonExercise.lesson')->orderBy('id')->get();
        $first = [];

        foreach ($answers as $answer) {
            $first[$answer->lesson_exercise_id] ??= $answer;
        }

        $substitutes = [];

        foreach (LessonExercise::query()->whereIn('substitute_for_id', $run->planExerciseIds())->get(['id', 'substitute_for_id']) as $substitute) {
            $substitutes[(int) $substitute->substitute_for_id] = $substitute->id;
        }

        $outcomes = [];

        foreach ($run->plan as $entry) {
            $answer = $first[$entry['id']] ?? null;

            if ($answer !== null && $answer->skipped && isset($substitutes[$entry['id']])) {
                $answer = $first[$substitutes[$entry['id']]] ?? null;
            }

            $exercise = $answer?->lessonExercise;

            if ($answer === null || $answer->skipped || $exercise === null || $exercise->format->isTeach()) {
                continue;
            }

            $weight = $exercise->format->isPassage() ? $this->questions($exercise) : 1;

            $outcomes[] = [
                'answer' => $answer,
                'exercise' => $exercise,
                'origin' => $entry['origin'],
                'weight' => $weight,
                'correct' => $this->correct($answer, $exercise, $weight),
            ];
        }

        return $outcomes;
    }

    private function correct(LessonAnswer $answer, LessonExercise $exercise, int $weight): float
    {
        if ($exercise->format->isPassage()) {
            return $answer->flagged_at === null ? ($answer->score ?? 0.0) / 100 * $weight : 0.0;
        }

        return $this->firstTryRule->rightFirstTime($answer) ? 1.0 : 0.0;
    }

    private function questions(LessonExercise $exercise): int
    {
        $questions = $exercise->payload['questions'] ?? [];

        return max(1, is_array($questions) ? count($questions) : 1);
    }
}
