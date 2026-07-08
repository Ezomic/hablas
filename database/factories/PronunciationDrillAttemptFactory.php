<?php

namespace Database\Factories;

use App\Models\PronunciationDrillAttempt;
use App\Models\PronunciationDrillExercise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PronunciationDrillAttempt>
 */
class PronunciationDrillAttemptFactory extends Factory
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
            'pronunciation_drill_exercise_id' => PronunciationDrillExercise::factory(),
            'transcript_guess' => 'pão',
            'is_correct' => true,
            'score' => 100.0,
            'attempted_at' => now(),
        ];
    }
}
