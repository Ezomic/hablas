<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LessonStage;
use App\Enums\LessonState;
use App\Models\Lesson;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;

/**
 * Up to three stars for a unit: one for starting it, one for finishing every
 * lesson before the check, and one for knowing at least nine in ten of its
 * words from memory.
 */
final class UnitStars
{
    public const MEMORY_SHARE = 90;

    public function __construct(
        private readonly LessonProgress $lessonProgress = new LessonProgress,
    ) {}

    public function handle(User $user, Unit $unit, int $percent): int
    {
        $started = LessonRun::query()->where('user_id', $user->id)->whereHas('lesson', fn ($query) => $query->where('unit_id', $unit->id))->exists();

        if (! $started) {
            return 0;
        }

        $lessons = Lesson::query()->where('unit_id', $unit->id)->playable()->where('stage', '!=', LessonStage::Check)->get();
        $states = $this->lessonProgress->states($user, $unit);
        $finished = $lessons->isNotEmpty() && $lessons->every(fn (Lesson $lesson): bool => ($states[$lesson->id] ?? null) === LessonState::Completed);

        return 1 + ($finished ? 1 : 0) + ($finished && $percent >= self::MEMORY_SHARE ? 1 : 0);
    }
}
