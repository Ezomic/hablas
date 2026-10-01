<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AccentPolicy;
use App\Models\LessonAnswer;
use LogicException;

/**
 * What counts as right first time. Only first tries count for scores:
 * retries finish a lesson but never lift an accuracy.
 */
final class FirstTryRule
{
    public function rightFirstTime(LessonAnswer $answer): bool
    {
        if ($answer->attempt !== 1 || $answer->skipped || $answer->is_correct !== true || $answer->flagged_at !== null) {
            return false;
        }

        $stage = ($answer->lessonExercise->lesson ?? throw new LogicException("Answer {$answer->id} has no exercise lesson."))->stage;

        if ($answer->hinted && ! $stage->hintsAreFree()) {
            return false;
        }

        return ! ($answer->note === 'accent' && $stage->accentPolicy() !== AccentPolicy::Forgive);
    }
}
