<?php

namespace App\Actions;

use App\Models\ScriptedPromptExercise;
use App\Services\SpanishTextNormalizer;

class GradeScriptedPromptAttempt
{
    /**
     * Percentage of expected_keywords found (as a substring, tolerating
     * conjugation) in the transcript — keyword-presence scoring per the
     * tier-2 scripted-prompt scope, not full semantic grading.
     */
    public function handle(ScriptedPromptExercise $exercise, string $transcriptGuess): float
    {
        if ($exercise->expected_keywords === []) {
            return 0.0;
        }

        $normalizer = new SpanishTextNormalizer;
        $normalizedGuess = $normalizer->collapseWhitespace($transcriptGuess);

        $matched = collect($exercise->expected_keywords)
            ->filter(fn (string $keyword): bool => str_contains($normalizedGuess, $normalizer->collapseWhitespace($keyword)))
            ->count();

        return round(($matched / count($exercise->expected_keywords)) * 100, 1);
    }
}
