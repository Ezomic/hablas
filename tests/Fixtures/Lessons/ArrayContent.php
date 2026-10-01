<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lessons;

use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

/**
 * Unit content assembled in a test from plain arrays, for the cases the hotel
 * fixture does not need to cover.
 */
final class ArrayContent implements UnitContent
{
    /**
     * @param  list<WordData>  $words
     * @param  list<AuthoredExercise>  $exercises
     * @param  list<ContentReview>|null  $reviews  fully reviewed when null
     */
    public function __construct(
        private readonly string $slug = 'checking-into-a-hotel',
        private readonly array $words = [],
        private readonly array $exercises = [],
        private readonly ?array $reviews = null,
        private readonly string $language = 'es',
    ) {}

    public function languageCode(): string
    {
        return $this->language;
    }

    public function unitSlug(): string
    {
        return $this->slug;
    }

    public function words(): array
    {
        return $this->words;
    }

    public function grammarExamples(): array
    {
        return [];
    }

    public function exercises(): array
    {
        return $this->exercises;
    }

    public function reviews(): array
    {
        return $this->reviews ?? (new HotelContent)->reviews();
    }
}
