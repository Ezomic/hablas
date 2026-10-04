<?php

declare(strict_types=1);

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
     * Accent-folds, strips punctuation (an apostrophe inside a word is kept
     * and unified) without splitting on it, and splits on whitespace into
     * unique words, for word-overlap style matching.
     *
     * @return Collection<int, non-empty-string>
     */
    public function uniqueWords(string $text): Collection;

    /**
     * For grading a whole typed answer: folds what grading folds and treats
     * punctuation as a break between words, except an apostrophe inside a word
     * (l'italiano), which is kept and unified; searchKey and sortKey split on
     * it. Keeps every word in order,
     * so a missing or repeated word still counts ("poco a" is not "poco a
     * poco").
     */
    public function answerKey(string $text): string;

    /**
     * For looking things up, never for grading: folds the marks grading keeps
     * distinct as well (ñ, ç, ã, õ), so a query typed without them still
     * matches, and turns punctuation into spaces. Words keep their order and
     * repeats, so a run of words can be found inside a phrase.
     */
    public function searchKey(string $text): string;

    /**
     * The search key without a leading article, so an alphabetical list files
     * "el aeropuerto" under "a" instead of grouping every noun by its article.
     */
    public function sortKey(string $text): string;

    /**
     * For grading accents word by word: lowercases and splits into words like
     * answerKey, but keeps every accent and special letter, so "avô" and
     * "avó", or "esta" and "está", stay different words.
     */
    public function exactKey(string $text): string;

    /**
     * The accented words that turn into another word when the accent is
     * dropped ("él" and "el", "é" and "e"). Typing one of these without its
     * accent is a different word, not a slip.
     *
     * @return list<string>
     */
    public function accentWords(): array;

    /**
     * The language's articles, lowercased, with their accents.
     *
     * @return list<string>
     */
    public function articles(): array;
}
