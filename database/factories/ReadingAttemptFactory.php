<?php

namespace Database\Factories;

use App\Models\ReadingAttempt;
use App\Models\ReadingPassage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReadingAttempt>
 */
class ReadingAttemptFactory extends Factory
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
            'reading_passage_id' => ReadingPassage::factory(),
            'answers' => [],
            'score' => 100.0,
            'attempted_at' => now(),
        ];
    }
}
