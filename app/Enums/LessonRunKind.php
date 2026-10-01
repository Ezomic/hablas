<?php

declare(strict_types=1);

namespace App\Enums;

enum LessonRunKind: string
{
    case Lesson = 'lesson';
    case Check = 'check';
    case Practice = 'practice';
    case Retake = 'retake';
    case TestOut = 'test_out';

    /**
     * Check-kind runs measure mastery, so they give no hints and no feedback
     * and nothing in them comes back.
     */
    public function isCheck(): bool
    {
        return in_array($this, [self::Check, self::Retake, self::TestOut], true);
    }
}
