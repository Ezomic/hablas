<?php

namespace App\Actions;

use App\Contracts\TextNormalizer;
use App\Models\ShadowingExercise;
use App\Services\TextNormalizerResolver;
use RuntimeException;

class GradeShadowingAttempt
{
    /**
     * Rough word-overlap match against the target transcript — not
     * phoneme-level pronunciation scoring, per the tier-1 shadowing scope.
     * Returns a percentage from 0 to 100.
     */
    public function handle(ShadowingExercise $exercise, string $transcriptGuess): float
    {
        $normalizer = $this->normalizerFor($exercise);
        $targetWords = $normalizer->uniqueWords($exercise->target_transcript);

        if ($targetWords->isEmpty()) {
            return 0.0;
        }

        $guessWords = $normalizer->uniqueWords($transcriptGuess);
        $matched = $targetWords->intersect($guessWords)->count();

        return round(($matched / $targetWords->count()) * 100, 1);
    }

    /**
     * Which accents are foldable and which are phonemic is language-specific,
     * so grading folds against the exercise's own language rather than a
     * hardcoded one.
     */
    private function normalizerFor(ShadowingExercise $exercise): TextNormalizer
    {
        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Shadowing exercise {$exercise->id} has no language.");
        }

        return (new TextNormalizerResolver)->forLanguage($language);
    }
}
