<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlacementTestAttempt>
 */
class PlacementTestAttemptFactory extends Factory
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
            'language_id' => Language::factory(),
            'started_at' => now(),
            'completed_at' => null,
            'resulting_skill_levels' => null,
        ];
    }
}
