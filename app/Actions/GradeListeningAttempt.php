<?php

namespace App\Actions;

use App\Models\ListeningExercise;

class GradeListeningAttempt
{
    /**
     * Percentage of comprehension questions answered correctly. Multiple
     * choice keeps this objective: comprehension is being measured, not
     * production, and free text here would need the kind of semantic grading
     * the writing exercises deliberately avoid.
     *
     * Answers are positional, one per question. A short list counts the
     * missing answers as wrong rather than shrinking the denominator, so
     * skipping questions can't inflate the score.
     *
     * @param  array<int, string>  $answers
     */
    public function handle(ListeningExercise $exercise, array $answers): float
    {
        $questions = $exercise->questions;

        if ($questions === []) {
            return 0.0;
        }

        $correct = 0;

        foreach ($questions as $index => $question) {
            if (($answers[$index] ?? null) === $question['correct_answer']) {
                $correct++;
            }
        }

        return round(($correct / count($questions)) * 100, 1);
    }
}
