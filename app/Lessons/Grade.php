<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\ErrorTagCategory;

/**
 * The server's verdict on one answer: the whole exercise and each target.
 */
final readonly class Grade
{
    /**
     * @param  list<array{type: string, id: int, correct: bool}>  $targets
     * @param  array{found: list<string>, missing: list<string>}|null  $details  which required words a guided text used
     */
    public function __construct(
        public bool $correct,
        public ?string $expected,
        public ?string $note,
        public ?float $score,
        public array $targets,
        public ?ErrorTagCategory $errorTag,
        public ?array $details = null,
    ) {}
}
