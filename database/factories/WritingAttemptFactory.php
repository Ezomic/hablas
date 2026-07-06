<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WritingAttempt>
 */
class WritingAttemptFactory extends Factory
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
            'writing_exercise_id' => WritingExercise::factory(),
            'response' => $this->faker->sentence(),
            'is_correct' => false,
            'submitted_at' => now(),
        ];
    }
}
