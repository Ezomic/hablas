<?php

namespace Database\Factories;

use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShadowingAttempt>
 */
class ShadowingAttemptFactory extends Factory
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
            'shadowing_exercise_id' => ShadowingExercise::factory(),
            'transcript_guess' => $this->faker->sentence(),
            'score' => $this->faker->randomFloat(1, 0, 100),
            'attempted_at' => now(),
        ];
    }
}
