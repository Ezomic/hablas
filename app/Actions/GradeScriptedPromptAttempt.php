<?php

namespace App\Actions;

use App\Contracts\TextNormalizer;
use App\Models\ScriptedPromptExercise;
use App\Services\TextNormalizerResolver;
use RuntimeException;

class GradeScriptedPromptAttempt
{
    public function __construct(
        private readonly TextNormalizerResolver $textNormalizerResolver = new TextNormalizerResolver,
    ) {}

    /**
     * Percentage of expected_keywords found (as a substring, tolerating
     * conjugation) in the transcript — keyword-presence scoring per the
     * tier-2 scripted-prompt scope, not full semantic grading.
     */
    public function handle(ScriptedPromptExercise $exercise, string $transcriptGuess): float
    {
        $normalizer = $this->normalizerFor($exercise);

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

    /**
     * Which accents are foldable and which are phonemic is language-specific,
     * so grading folds against the exercise's own language rather than a
     * hardcoded one.
     */
    private function normalizerFor(ScriptedPromptExercise $exercise): TextNormalizer
    {
        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Scripted prompt exercise {$exercise->id} has no language.");
        }

        return $this->textNormalizerResolver->forLanguage($language);
    }
}
