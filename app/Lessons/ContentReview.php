<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;

/**
 * One recorded review of a unit's content. Entries are written only after the
 * review has happened, never by the drafting agent on its own.
 */
final readonly class ContentReview
{
    public function __construct(
        public ReviewKind $kind,
        public ReviewScope $scope,
        public string $reviewer,
        public string $reviewedOn,
        public string $notes = '',
    ) {}
}
