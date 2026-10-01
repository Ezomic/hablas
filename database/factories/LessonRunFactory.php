<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Models\Lesson;
use App\Models\LessonRun;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonRun>
 */
class LessonRunFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
            'open_lesson_id' => null,
            'kind' => LessonRunKind::Lesson,
            'probe_set' => null,
            'status' => LessonRunStatus::InProgress,
            'plan' => [],
            'seed' => 1,
            'counts_as_evidence' => false,
            'started_at' => now(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (): array => [
            'status' => LessonRunStatus::Completed,
            'open_lesson_id' => null,
            'completed_at' => now(),
        ]);
    }
}
