<?php

declare(strict_types=1);

namespace App\Lessons;

/**
 * What an authored exercise practises. The form is the exact words of the
 * target inside the accepted answers, which is how a mistake is pinned to the
 * word or grammar pattern that was wrong.
 */
final readonly class TargetSpec
{
    private function __construct(
        public ?string $term,
        public string $form,
        public bool $contrast,
    ) {}

    public static function word(string $term, ?string $form = null): self
    {
        return new self($term, $form ?? $term, false);
    }

    public static function grammar(string $form, bool $contrast = false): self
    {
        return new self(null, $form, $contrast);
    }

    public function isGrammar(): bool
    {
        return $this->term === null;
    }
}
