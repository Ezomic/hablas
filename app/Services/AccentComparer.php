<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\TextNormalizer;
use App\Enums\AccentVerdict;

final class AccentComparer
{
    /**
     * Compares one typed word with the word that was expected. Null means a
     * different word, in the sense that more than the vowel accents differ:
     * ñ, ç, ã and õ are never forgiven, so "ano" is not "año".
     */
    public function compare(TextNormalizer $normalizer, string $expected, string $given): ?AccentVerdict
    {
        $expectedWord = $normalizer->exactKey($expected);
        $givenWord = $normalizer->exactKey($given);

        if ($expectedWord === $givenWord) {
            return AccentVerdict::Exact;
        }

        if ($normalizer->answerKey($expectedWord) !== $normalizer->answerKey($givenWord)) {
            return null;
        }

        $withoutAccents = $normalizer->foldAccents($expectedWord);

        if ($givenWord !== $withoutAccents) {
            return AccentVerdict::OtherWord;
        }

        return in_array($expectedWord, $normalizer->accentWords(), true)
            ? AccentVerdict::OtherWord
            : AccentVerdict::Missing;
    }
}
