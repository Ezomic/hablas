<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;

/**
 * A unit's content treated as reviewed, so the review sheet can show the
 * exercises it would build before any review is recorded. With the lessons
 * too, it carries the authored exercises as well. It is only ever used to
 * render a preview, never to seed.
 */
final readonly class PreviewContent implements UnitContent
{
    public function __construct(
        private UnitContent $content,
        private bool $withLessons = false,
    ) {}

    public function languageCode(): string
    {
        return $this->content->languageCode();
    }

    public function unitSlug(): string
    {
        return $this->content->unitSlug();
    }

    public function words(): array
    {
        return $this->content->words();
    }

    public function grammarExamples(): array
    {
        return $this->content->grammarExamples();
    }

    public function exercises(): array
    {
        return $this->withLessons ? $this->content->exercises() : [];
    }

    public function reviews(): array
    {
        $today = now()->toDateString();
        $reviews = [new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'preview', $today)];

        if ($this->withLessons) {
            array_push(
                $reviews,
                new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'preview', $today),
                new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'preview', $today),
            );
        }

        return $reviews;
    }
}
