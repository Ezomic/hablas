<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\ScriptedPromptExercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScriptedPromptExercise>
 */
class ScriptedPromptExerciseFactory extends Factory
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
            'prompt_text' => $this->faker->sentence(),
            'expected_keywords' => [$this->faker->word()],
        ];
    }
}
