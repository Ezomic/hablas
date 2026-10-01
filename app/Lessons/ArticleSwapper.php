<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Contracts\TextNormalizer;

/**
 * The other-gender article of a noun phrase, which is both the wrong-article
 * distractor of a noun and the second right answer of a common-gender noun.
 */
final class ArticleSwapper
{
    private const SWAPS = [
        'el' => 'la', 'la' => 'el', 'los' => 'las', 'las' => 'los', 'un' => 'una', 'una' => 'un', 'unos' => 'unas', 'unas' => 'unos',
        'o' => 'a', 'a' => 'o', 'os' => 'as', 'as' => 'os', 'um' => 'uma', 'uma' => 'um', 'uns' => 'umas', 'umas' => 'uns',
    ];

    public function swap(TextNormalizer $normalizer, string $term): ?string
    {
        $words = explode(' ', trim($term));
        $first = $normalizer->exactKey($words[0]);

        if (count($words) < 2 || ! isset(self::SWAPS[$first])) {
            return null;
        }

        $words[0] = self::SWAPS[$first];

        return implode(' ', $words);
    }
}
