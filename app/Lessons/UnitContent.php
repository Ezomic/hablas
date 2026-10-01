<?php

declare(strict_types=1);

namespace App\Lessons;

interface UnitContent
{
    public function languageCode(): string;

    public function unitSlug(): string;

    /** @return list<WordData> */
    public function words(): array;

    /**
     * Example sentences for the grammar card of lesson 2.
     *
     * @return list<array{text: string, english: string}>
     */
    public function grammarExamples(): array;

    /**
     * The authored exercises of lessons 2 to 4 and of both check sets.
     *
     * @return list<AuthoredExercise>
     */
    public function exercises(): array;

    /** @return list<ContentReview> */
    public function reviews(): array;
}
