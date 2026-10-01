<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;

/**
 * A unit's content with its words treated as reviewed, so the review sheet can
 * show the exercises the words would generate before the review is recorded.
 * It is only ever used to render a preview, never to seed.
 */
final readonly class PreviewContent implements UnitContent
{
    public function __construct(
        private UnitContent $content,
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
        return [];
    }

    public function reviews(): array
    {
        return [new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'preview', now()->toDateString())];
    }
}
