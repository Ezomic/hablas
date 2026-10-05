<?php

declare(strict_types=1);

namespace App\Lessons;

/**
 * What an authored exercise practises. The form is the exact words of the
 * target inside the accepted answers, which is how a mistake is pinned to the
 * word or grammar pattern that was wrong. An answer that is as right with
 * another wording of the target (chaque jour for tous les jours) lists it as
 * an alternate; the first form found in the answer is the one used.
 */
final readonly class TargetSpec
{
    /** @param  list<string>  $alternates */
    private function __construct(
        public ?string $term,
        public string $form,
        public bool $contrast,
        public array $alternates = [],
    ) {}

    /** @param  list<string>  $alternates */
    public static function word(string $term, ?string $form = null, array $alternates = []): self
    {
        return new self($term, $form ?? $term, false, $alternates);
    }

    /** @param  list<string>  $alternates */
    public static function grammar(string $form, bool $contrast = false, array $alternates = []): self
    {
        return new self(null, $form, $contrast, $alternates);
    }

    /** @return list<string> the form first, then the other forms that are as right in an accepted answer */
    public function forms(): array
    {
        return [$this->form, ...$this->alternates];
    }

    public function isGrammar(): bool
    {
        return $this->term === null;
    }
}
