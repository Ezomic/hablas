<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonState;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\User;
use App\Services\LessonProgress;

final class DescribeNextLesson
{
    public function __construct(
        private readonly LessonProgress $lessonProgress = new LessonProgress,
    ) {}

    /**
     * The lesson of a unit to start or resume next, numbered among the unit's
     * playable lessons, or null when the unit has none open right now.
     *
     * @return array{lessonId: int, title: string, number: int, count: int, resumes: bool, remediation: string|null, missing: int}|null
     */
    public function handle(User $user, Unit $unit): ?array
    {
        $lessons = Lesson::query()->where('unit_id', $unit->id)->playable()->orderBy('position')->get();
        $states = $this->lessonProgress->states($user, $unit);

        foreach ($lessons->values() as $index => $lesson) {
            $state = $states[$lesson->id] ?? LessonState::Coming;

            if (in_array($state, [LessonState::Available, LessonState::InProgress], true)) {
                return [
                    'lessonId' => $lesson->id,
                    'title' => $lesson->title,
                    'number' => $index + 1,
                    'count' => $lessons->count(),
                    'resumes' => $state === LessonState::InProgress,
                    'remediation' => null,
                    'missing' => 0,
                ];
            }

            if ($state === LessonState::Remediation) {
                $remediation = $this->lessonProgress->remediation($user, $unit);

                if ($remediation !== null) {
                    return [
                        'lessonId' => $lesson->id,
                        'title' => $lesson->title,
                        'number' => $index + 1,
                        'count' => $lessons->count(),
                        'resumes' => false,
                        'remediation' => $remediation['retake'] === 'open' ? 'retake' : 'practice',
                        'missing' => $remediation['missing'],
                    ];
                }
            }
        }

        return null;
    }
}
