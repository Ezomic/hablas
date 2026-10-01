<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;

/**
 * Nothing a learner is graded on is seeded unreviewed. Words need the
 * independent AI review (dictionaries and the pt-PT 1990 agreement spelling);
 * lessons need that review and the owner's approval too.
 */
final class ReviewGate
{
    public static function wordsReleased(UnitContent $content): bool
    {
        return self::has($content, ReviewKind::IndependentAi, ReviewScope::Words);
    }

    public static function lessonsReleased(UnitContent $content): bool
    {
        return self::wordsReleased($content)
            && self::has($content, ReviewKind::IndependentAi, ReviewScope::Lessons)
            && self::has($content, ReviewKind::Owner, ReviewScope::Lessons);
    }

    private static function has(UnitContent $content, ReviewKind $kind, ReviewScope $scope): bool
    {
        foreach ($content->reviews() as $review) {
            if ($review->kind === $kind && $review->scope === $scope) {
                return true;
            }
        }

        return false;
    }
}
