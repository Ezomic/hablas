<?php

namespace App\Services;

class PortugueseTextNormalizer extends AccentFoldingTextNormalizer
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
     * @return array<string, string>
     */
    protected function vowelAccentFolds(): array
    {
        return [
            'á' => 'a', 'à' => 'a', 'â' => 'a',
            'é' => 'e', 'ê' => 'e',
            'í' => 'i',
            'ó' => 'o', 'ô' => 'o',
            'ú' => 'u', 'ü' => 'u',
        ];
    }
}
