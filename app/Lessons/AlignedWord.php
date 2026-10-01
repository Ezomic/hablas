<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\AccentVerdict;

/**
 * One expected word lined up with what the learner typed. The verdict is
 * null when the learner's word is a different word, or missing.
 */
final readonly class AlignedWord
{
    public function __construct(
        public string $expected,
        public ?string $given,
        public ?AccentVerdict $verdict,
    ) {}
}
