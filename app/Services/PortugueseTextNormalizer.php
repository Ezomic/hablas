<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Shared accent-folding/normalization for Portuguese text, used by graders
 * that fuzzy-match a learner's answer against a reference string.
 */
class PortugueseTextNormalizer
{
    /**
     * Only non-nasal vowel accents are folded. 'ã'/'õ' and 'ç' are
     * deliberately left alone: nasalization is exactly the phonemic
     * distinction the pronunciation-drill grader needs to detect (pão/pau,
     * mão/mau are minimal pairs distinguished only by the nasal mark), so
     * folding ã→a or õ→o here would silently defeat minimal-pair grading.
     * 'ç' is likewise a distinct phoneme from plain 'c', the same reasoning
     * SpanishTextNormalizer uses to preserve 'ñ'.
     *
     * @var array<string, string>
     */
    private const VOWEL_ACCENT_FOLDS = [
        'á' => 'a', 'à' => 'a', 'â' => 'a',
        'é' => 'e', 'ê' => 'e',
        'í' => 'i',
        'ó' => 'o', 'ô' => 'o',
        'ú' => 'u', 'ü' => 'u',
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
