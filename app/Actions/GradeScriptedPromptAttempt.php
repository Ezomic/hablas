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
        $normalizer = new SpanishTextNormalizer;

        $keywords = collect($exercise->expected_keywords)
            ->map(fn (string $keyword): string => $normalizer->collapseWhitespace($keyword))
            ->filter(fn (string $keyword): bool => $keyword !== '');

        if ($keywords->isEmpty()) {
            return 0.0;
        }

        $normalizedGuess = $normalizer->collapseWhitespace($transcriptGuess);

        $matched = $keywords
            ->filter(fn (string $keyword): bool => str_contains($normalizedGuess, $keyword))
            ->count();

        return round(($matched / $keywords->count()) * 100, 1);
    }
}
