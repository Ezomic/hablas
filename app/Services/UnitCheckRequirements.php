<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LessonStage;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\User;

/**
 * What a learner has to do before the unit check opens: every lesson of the
 * unit played through to 100% first time, and the final test of every skill
 * the unit trains passed.
 */
final class UnitCheckRequirements
{
    public function __construct(
        private readonly UnitSkillProgress $unitSkillProgress = new UnitSkillProgress,
        private readonly LessonMastery $lessonMastery = new LessonMastery,
    ) {}

    /** @return array{lessonsMastered: int, lessonsTotal: int, skillsMastered: int, skillsTotal: int, met: bool} */
    public function handle(User $user, Unit $unit): array
    {
        $lessons = Lesson::query()->where('unit_id', $unit->id)->where('stage', '!=', LessonStage::Check)->playable()->get();
        $mastered = 0;

        foreach ($lessons as $lesson) {
            if ($this->lessonMastery->isMastered($user, $lesson)) {
                $mastered++;
            }
        }

        $skills = array_values(array_filter($this->unitSkillProgress->handle($user, $unit), fn (array $row): bool => $row['total'] > 0));
        $skillsMastered = count(array_filter($skills, fn (array $row): bool => $row['mastered']));

        return [
            'lessonsMastered' => $mastered,
            'lessonsTotal' => count($lessons),
            'skillsMastered' => $skillsMastered,
            'skillsTotal' => count($skills),
            'met' => $mastered === count($lessons) && $skillsMastered === count($skills),
        ];
    }
}
