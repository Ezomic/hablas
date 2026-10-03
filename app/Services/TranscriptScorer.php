<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\TextNormalizer;
use App\Enums\AccentVerdict;

final class TranscriptScorer
{
    public const float PASS_SCORE = 80.0;

    public function __construct(
        private readonly AccentComparer $accentComparer = new AccentComparer,
    ) {}

    /**
     * Finds, for each keyword slot, the accepted form the learner said. A form
     * of several words has to come in order as whole words, and a vowel accent
     * the recogniser dropped is forgiven unless it makes another word.
     *
     * @param  list<list<string>>  $slots
     * @return list<string|null> the form heard per slot, null where the slot was missed
     */
    public function keywords(TextNormalizer $normalizer, array $slots, string $transcript): array
    {
        $heard = $this->words($normalizer, $transcript);

        return array_map(function (array $forms) use ($normalizer, $heard): ?string {
            foreach ($forms as $form) {
                if ($this->contains($normalizer, $heard, $this->words($normalizer, $form))) {
                    return $form;
                }
            }

            return null;
        }, $slots);
    }

    public function percentage(int $right, int $total): float
    {
        return $total === 0 ? 0.0 : round($right / $total * 100, 1);
    }

    /**
     * @param  list<string>  $heard
     * @param  list<string>  $form
     */
    private function contains(TextNormalizer $normalizer, array $heard, array $form): bool
    {
        if ($form === []) {
            return false;
        }

        for ($start = 0; $start + count($form) <= count($heard); $start++) {
            if ($this->matchesAt($normalizer, $heard, $form, $start)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<string>  $heard
     * @param  list<string>  $form
     */
    private function matchesAt(TextNormalizer $normalizer, array $heard, array $form, int $start): bool
    {
        foreach ($form as $offset => $word) {
            $verdict = $this->accentComparer->compare($normalizer, $word, $heard[$start + $offset]);

            if (! in_array($verdict, [AccentVerdict::Exact, AccentVerdict::Missing], true)) {
                return false;
            }
        }

        return true;
    }

    /** @return list<string> */
    private function words(TextNormalizer $normalizer, string $text): array
    {
        $key = $normalizer->exactKey($text);

        return $key === '' ? [] : explode(' ', $key);
    }
}
