<?php

namespace Database\Factories;

use App\Models\ListeningAttempt;
use App\Models\ListeningExercise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ListeningAttempt>
 */
class ListeningAttemptFactory extends Factory
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
            'listening_exercise_id' => ListeningExercise::factory(),
            'answers' => [],
            'score' => 100.0,
            'replays_used' => 0,
            'attempted_at' => now(),
        ];
    }
}
