<?php

declare(strict_types=1);

namespace App\Lessons;

/**
 * The reviewed word data for one vocabulary item of a unit: how a learner is
 * cued for it and what typed answers are right. Everything the ramp needs to
 * generate the word exercises comes from here.
 */
final readonly class WordData
{
    /**
     * @param  string  $term  the vocabulary item's term, which identifies it within the unit
     * @param  string|null  $cue  the English recall cue, where the gloss alone is ambiguous
     * @param  list<string>  $accepted  further accepted typed answers besides the term
     * @param  list<string>  $forms  the forms the ramp uses (plural, feminine, the persons of a verb)
     * @param  list<string>  $portunolSlips  Spanish forms that count as a Portunol slip in Portuguese
     * @param  list<string>  $questions  open questions for the reviewer, which the review sheet prints beside the word
     */
    public function __construct(
        public string $term,
        public ?string $cue = null,
        public array $accepted = [],
        public array $forms = [],
        public bool $commonGender = false,
        public array $portunolSlips = [],
        public array $questions = [],
    ) {}
}
