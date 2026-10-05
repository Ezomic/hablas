<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Contracts\TextNormalizer;

/**
 * Finds where each target's words sit inside an accepted answer, so a
 * mistake can be pinned to the word or pattern that was wrong.
 */
final class AnswerSpans
{
    /**
     * @param  array<string, list<string>>  $formsByKey  the wordings of each target, by target key, the first found in the answer is used
     * @return array<string, array{int, int}>|null start and length in words; null when a form is missing
     */
    public function find(TextNormalizer $normalizer, string $answer, array $formsByKey): ?array
    {
        $words = $this->words($normalizer, $answer);
        $spans = [];

        foreach ($formsByKey as $key => $forms) {
            $span = null;

            foreach ($forms as $form) {
                $formWords = $this->words($normalizer, $form);
                $start = $this->indexOf($words, $formWords);

                if ($start !== null) {
                    $span = [$start, count($formWords)];

                    break;
                }
            }

            if ($span === null) {
                return null;
            }

            $spans[$key] = $span;
        }

        return $spans;
    }

    /** @return list<string> */
    public function words(TextNormalizer $normalizer, string $text): array
    {
        $key = $normalizer->exactKey($text);

        return $key === '' ? [] : explode(' ', $key);
    }

    /**
     * @param  list<string>  $haystack
     * @param  list<string>  $needle
     */
    private function indexOf(array $haystack, array $needle): ?int
    {
        if ($needle === []) {
            return null;
        }

        for ($start = 0; $start + count($needle) <= count($haystack); $start++) {
            if (array_slice($haystack, $start, count($needle)) === $needle) {
                return $start;
            }
        }

        return null;
    }
}
