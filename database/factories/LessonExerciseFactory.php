<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LessonExerciseFormat;
use App\Models\Lesson;
use App\Models\LessonExercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonExercise>
 */
class LessonExerciseFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'key' => 'exercise.'.$this->faker->unique()->slug(2),
            'position' => 1,
            'block' => 'main',
            'format' => LessonExerciseFormat::ChooseMeaning,
            'probe_set' => null,
            'payload' => ['prompt' => 'la llave', 'options' => ['key', 'room'], 'answer' => 'key'],
            'substitute_for_id' => null,
            'content_hash' => hash('sha256', $this->faker->unique()->uuid()),
            'retired_at' => null,
        ];
    }
}
