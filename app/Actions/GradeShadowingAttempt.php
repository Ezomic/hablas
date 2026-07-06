<?php

namespace App\Actions;

use App\Models\ShadowingExercise;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GradeShadowingAttempt
{
    /**
     * Rough word-overlap match against the target transcript — not
     * phoneme-level pronunciation scoring, per the tier-1 shadowing scope.
     * Returns a percentage from 0 to 100.
     */
    public function handle(ShadowingExercise $exercise, string $transcriptGuess): float
    {
        $targetWords = $this->normalizeWords($exercise->target_transcript);

        if ($targetWords->isEmpty()) {
            return 0.0;
        }

        $guessWords = $this->normalizeWords($transcriptGuess);
        $matched = $targetWords->intersect($guessWords)->count();

        return round(($matched / $targetWords->count()) * 100, 1);
    }

    /**
     * Vowels a learner's guess accidentally spells without their written
     * accent (e.g. "esta" for "está"), only mapping the vowel diacritics.
     *
     * @var array<string, string>
     */
    private const VOWEL_ACCENT_FOLDS = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
    ];

    /**
     * Lowercases, folds vowel accents, and strips punctuation before
     * splitting into unique words — speech-recognition output varies in how
     * (or whether) it renders diacritics, so folding vowel accents keeps the
     * match rough rather than penalizing an otherwise-correct answer for a
     * missing accent mark. Deliberately does NOT fold 'ñ' to 'n': unlike a
     * vowel accent, ñ is a distinct letter/phoneme in Spanish (año/ano is a
     * canonical minimal pair), so collapsing it would hide exactly the kind
     * of pronunciation error this exercise exists to catch.
     *
     * @return Collection<int, non-empty-string>
     */
    private function normalizeWords(string $text): Collection
    {
        $normalized = strtr(Str::lower(trim($text)), self::VOWEL_ACCENT_FOLDS);
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normalized) ?? '';

        $words = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return collect($words)->unique();
    }
}
