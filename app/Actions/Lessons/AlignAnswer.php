<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Contracts\TextNormalizer;
use App\Enums\AccentVerdict;
use App\Lessons\AlignedWord;
use App\Lessons\AnswerAlignment;
use App\Services\AccentComparer;
use InvalidArgumentException;

final class AlignAnswer
{
    private const SAME_WORD_COST = 0.25;

    public function __construct(
        private readonly AccentComparer $accentComparer = new AccentComparer,
    ) {}

    /**
     * Lines the learner's words up with the closest accepted answer by edit
     * distance over words, so a wrong or missing word is pinned to its place.
     *
     * @param  list<string>  $accepted
     */
    public function handle(TextNormalizer $normalizer, string $response, array $accepted): AnswerAlignment
    {
        if ($accepted === []) {
            throw new InvalidArgumentException('An answer needs at least one accepted answer to be aligned with.');
        }

        $given = $this->words($normalizer, $response);
        $best = null;

        foreach ($accepted as $index => $candidate) {
            $alignment = $this->align($normalizer, $index, $this->words($normalizer, $candidate), $given);

            if ($best === null || $alignment->cost < $best->cost) {
                $best = $alignment;
            }
        }

        return $best;
    }

    /**
     * @param  list<string>  $expected
     * @param  list<string>  $given
     */
    private function align(TextNormalizer $normalizer, int $index, array $expected, array $given): AnswerAlignment
    {
        $rows = count($expected);
        $columns = count($given);
        $cost = [];
        $verdicts = [];

        for ($i = 0; $i <= $rows; $i++) {
            $cost[$i][0] = (float) $i;
        }

        for ($j = 0; $j <= $columns; $j++) {
            $cost[0][$j] = (float) $j;
        }

        for ($i = 1; $i <= $rows; $i++) {
            for ($j = 1; $j <= $columns; $j++) {
                $verdict = $this->accentComparer->compare($normalizer, $expected[$i - 1], $given[$j - 1]);
                $verdicts[$i][$j] = $verdict;
                $substitution = match (true) {
                    $verdict === null => 1.0,
                    $verdict === AccentVerdict::Exact => 0.0,
                    default => self::SAME_WORD_COST,
                };

                $cost[$i][$j] = min(
                    $cost[$i - 1][$j - 1] + $substitution,
                    $cost[$i - 1][$j] + 1.0,
                    $cost[$i][$j - 1] + 1.0,
                );
            }
        }

        $words = [];
        $extra = [];
        $i = $rows;
        $j = $columns;

        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0) {
                $verdict = $verdicts[$i][$j];
                $substitution = match (true) {
                    $verdict === null => 1.0,
                    $verdict === AccentVerdict::Exact => 0.0,
                    default => self::SAME_WORD_COST,
                };

                if (abs($cost[$i][$j] - ($cost[$i - 1][$j - 1] + $substitution)) < 1e-9) {
                    $words[] = new AlignedWord($expected[$i - 1], $given[$j - 1], $verdict);
                    $i--;
                    $j--;

                    continue;
                }
            }

            if ($i > 0 && abs($cost[$i][$j] - ($cost[$i - 1][$j] + 1.0)) < 1e-9) {
                $words[] = new AlignedWord($expected[$i - 1], null, null);
                $i--;

                continue;
            }

            $extra[] = $given[$j - 1];
            $j--;
        }

        return new AnswerAlignment($index, array_reverse($words), array_reverse($extra), $cost[$rows][$columns]);
    }

    /** @return list<string> */
    private function words(TextNormalizer $normalizer, string $text): array
    {
        $key = $normalizer->exactKey($text);

        return $key === '' ? [] : explode(' ', $key);
    }
}
