<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Shared accent-folding/normalization for Spanish text used by every grader
 * that fuzzy-matches a learner's answer (writing, shadowing, scripted
 * prompts) against a reference string.
 */
class SpanishTextNormalizer
{
    /**
     * Only vowel accents are folded — 'ñ' is deliberately left alone, since
     * it is a distinct Spanish letter/phoneme rather than an accent mark
     * (año/ano is a canonical minimal pair), and every caller of this
     * normalizer is checking a distinction where that difference matters.
     *
     * @var array<string, string>
     */
    private const VOWEL_ACCENT_FOLDS = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
    ];

    public function foldAccents(string $text): string
    {
        return strtr(Str::lower(trim($text)), self::VOWEL_ACCENT_FOLDS);
    }

    /**
     * Accent-folds, then collapses whitespace — for exact-string comparisons.
     */
    public function collapseWhitespace(string $text): string
    {
        return preg_replace('/\s+/', ' ', $this->foldAccents($text)) ?? '';
    }

    /**
     * Accent-folds, strips punctuation, and splits into unique words — for
     * word-overlap style matching.
     *
     * @return Collection<int, non-empty-string>
     */
    public function uniqueWords(string $text): Collection
    {
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->foldAccents($text)) ?? '';
        $words = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return collect($words)->unique();
    }
}
