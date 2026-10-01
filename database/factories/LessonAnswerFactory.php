<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonAnswer>
 */
class LessonAnswerFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'lesson_run_id' => LessonRun::factory(),
            'lesson_exercise_id' => LessonExercise::factory(),
            'step' => $this->faker->uuid(),
            'attempt' => 1,
            'hinted' => false,
            'skipped' => false,
            'is_correct' => true,
            'answered_at' => now(),
        ];
    }
}
