<?php

namespace App\Services;

class SpanishTextNormalizer extends AccentFoldingTextNormalizer
{
    /**
     * Only vowel accents are folded — 'ñ' is deliberately left alone, since
     * it is a distinct Spanish letter/phoneme rather than an accent mark
     * (año/ano is a canonical minimal pair), and every caller of this
     * normalizer is checking a distinction where that difference matters.
     *
     * @return array<string, string>
     */
    protected function vowelAccentFolds(): array
    {
        return [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
        ];
    }
}
