<?php

namespace App\Actions;

use App\Enums\WritingExerciseType;
use App\Models\WritingExercise;
use Illuminate\Support\Str;

class GradeWritingAttempt
{
    /**
     * Only vowel accents are folded — 'ñ' is deliberately left alone, since
     * it is a distinct Spanish letter/phoneme rather than an accent mark
     * (año/ano is a canonical minimal pair), and this is a written spelling
     * check where that distinction is exactly what's being tested.
     *
     * @var array<string, string>
     */
    private const VOWEL_ACCENT_FOLDS = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
    ];

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
        $normalizedResponse = $this->normalize($response);

        foreach ($exercise->correct_answers as $acceptedAnswer) {
            if ($this->normalize($acceptedAnswer) === $normalizedResponse) {
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

        $normalizedResponse = $this->normalize($response);

        foreach ($exercise->correct_answers as $requiredStem) {
            if (! str_contains($normalizedResponse, $this->normalize($requiredStem))) {
                return false;
            }
        }

        return true;
    }

    private function normalize(string $text): string
    {
        $normalized = strtr(Str::lower(trim($text)), self::VOWEL_ACCENT_FOLDS);

        return preg_replace('/\s+/', ' ', $normalized) ?? '';
    }
}
