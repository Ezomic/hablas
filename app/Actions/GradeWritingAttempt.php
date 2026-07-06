<?php

namespace App\Actions;

use App\Enums\WritingExerciseType;
use App\Models\WritingExercise;
use App\Services\SpanishTextNormalizer;

class GradeWritingAttempt
{
    public function handle(WritingExercise $exercise, string $response): bool
    {
        return match ($exercise->type) {
            WritingExerciseType::FillInTemplate,
            WritingExerciseType::SentenceTransformation => $this->matchesAnAcceptedAnswer($exercise, $response),
            WritingExerciseType::GuidedParagraph => $this->containsRequiredKeywords($exercise, $response),
        };
    }

    private function matchesAnAcceptedAnswer(WritingExercise $exercise, string $response): bool
    {
        $normalizer = new SpanishTextNormalizer;
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
    private function containsRequiredKeywords(WritingExercise $exercise, string $response): bool
    {
        if ($exercise->correct_answers === []) {
            return false;
        }

        $normalizer = new SpanishTextNormalizer;
        $normalizedResponse = $normalizer->collapseWhitespace($response);

        foreach ($exercise->correct_answers as $requiredStem) {
            if (! str_contains($normalizedResponse, $normalizer->collapseWhitespace($requiredStem))) {
                return false;
            }
        }

        return true;
    }
}
