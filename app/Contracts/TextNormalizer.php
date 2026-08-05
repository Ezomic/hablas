<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Accent folding and tokenization for one language, used by every grader that
 * fuzzy-matches a learner's answer against a reference string. Which accents
 * fold and which are phonemic is language-specific, so graders resolve an
 * implementation for the exercise's language rather than picking one.
 */
interface TextNormalizer
{
    public function foldAccents(string $text): string;

    /**
     * Accent-folds, then collapses whitespace, for exact-string comparisons.
     */
    public function collapseWhitespace(string $text): string;

    /**
     * Accent-folds, strips punctuation, and splits into unique words, for
     * word-overlap style matching.
     *
     * @return Collection<int, non-empty-string>
     */
    public function uniqueWords(string $text): Collection;
}
