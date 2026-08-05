<?php

namespace App\Actions;

use App\Contracts\TextNormalizer;
use App\Enums\WritingExerciseType;
use App\Models\WritingExercise;
use App\Services\TextNormalizerResolver;
use RuntimeException;

class GradeWritingAttempt
{
    public function handle(WritingExercise $exercise, string $response): bool
    {
        $normalizer = $this->normalizerFor($exercise);

        return match ($exercise->type) {
            WritingExerciseType::FillInTemplate,
            WritingExerciseType::SentenceTransformation => $this->matchesAnAcceptedAnswer($exercise, $response, $normalizer),
            WritingExerciseType::GuidedParagraph => $this->containsRequiredKeywords($exercise, $response, $normalizer),
        };
    }

    private function matchesAnAcceptedAnswer(WritingExercise $exercise, string $response, TextNormalizer $normalizer): bool
    {
        $normalizedResponse = $normalizer->collapseWhitespace($response);

        foreach ($exercise->correct_answers as $acceptedAnswer) {
            if ($normalizer->collapseWhitespace($acceptedAnswer) === $normalizedResponse) {
                return true;
            }
        }

        return false;
    }

    /**
     * Guided paragraphs are checked for structural completeness (does the
     * response actually use each required cue?) rather than full semantic
     * correctness, which real auto-grading can't do — correct_answers here
     * holds word stems (e.g. "levant" for levantarse/levanto/levantas) so a
     * substring check tolerates conjugation without needing to be
     * grammar-aware.
     */
    private function containsRequiredKeywords(WritingExercise $exercise, string $response, TextNormalizer $normalizer): bool
    {
        if ($exercise->correct_answers === []) {
            return false;
        }

        $normalizedResponse = $normalizer->collapseWhitespace($response);

        foreach ($exercise->correct_answers as $requiredStem) {
            if (! str_contains($normalizedResponse, $normalizer->collapseWhitespace($requiredStem))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Which accents are foldable and which are phonemic is language-specific,
     * so grading folds against the exercise's own language rather than a
     * hardcoded one.
     */
    private function normalizerFor(WritingExercise $exercise): TextNormalizer
    {
        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Writing exercise {$exercise->id} has no language.");
        }

        return (new TextNormalizerResolver)->forLanguage($language);
    }
}
