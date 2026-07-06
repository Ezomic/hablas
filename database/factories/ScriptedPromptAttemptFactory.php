<?php

namespace Database\Factories;

use App\Models\ScriptedPromptAttempt;
use App\Models\ScriptedPromptExercise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScriptedPromptAttempt>
 */
class ScriptedPromptAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'scripted_prompt_exercise_id' => ScriptedPromptExercise::factory(),
            'transcript_guess' => $this->faker->sentence(),
            'score' => $this->faker->randomFloat(1, 0, 100),
            'attempted_at' => now(),
        ];
    }
}
