<?php

declare(strict_types=1);

namespace App\Lessons;

final readonly class AnswerAlignment
{
    /**
     * @param  list<AlignedWord>  $words  one per word of the closest accepted answer
     * @param  list<string>  $extra  words the learner typed that line up with nothing
     */
    public function __construct(
        public int $acceptedIndex,
        public array $words,
        public array $extra,
        public float $cost,
    ) {}
}
