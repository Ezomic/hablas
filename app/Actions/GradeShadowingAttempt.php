<?php

namespace App\Actions;

use App\Models\ShadowingExercise;
use App\Services\SpanishTextNormalizer;

class GradeShadowingAttempt
{
    /**
     * Rough word-overlap match against the target transcript — not
     * phoneme-level pronunciation scoring, per the tier-1 shadowing scope.
     * Returns a percentage from 0 to 100.
     */
    public function handle(ShadowingExercise $exercise, string $transcriptGuess): float
    {
        $normalizer = new SpanishTextNormalizer;
        $targetWords = $normalizer->uniqueWords($exercise->target_transcript);

        if ($targetWords->isEmpty()) {
            return 0.0;
        }

        $guessWords = $normalizer->uniqueWords($transcriptGuess);
        $matched = $targetWords->intersect($guessWords)->count();

        return round(($matched / $targetWords->count()) * 100, 1);
    }
}
