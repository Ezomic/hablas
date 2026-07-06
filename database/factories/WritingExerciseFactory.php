<?php

namespace Database\Factories;

use App\Enums\WritingExerciseType;
use App\Models\Language;
use App\Models\WritingExercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WritingExercise>
 */
class WritingExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'language_id' => Language::factory(),
            'unit_id' => null,
            'type' => WritingExerciseType::FillInTemplate,
            'prompt' => $this->faker->sentence(),
            'template' => ['text' => $this->faker->sentence()],
            'correct_answers' => [$this->faker->word()],
        ];
    }
}
