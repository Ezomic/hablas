<?php

namespace Database\Factories;

use App\Models\Streak;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Streak>
 */
class StreakFactory extends Factory
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
            'current_length' => 0,
            'longest_length' => 0,
            'freeze_days_remaining' => 2,
            'last_activity_date' => null,
        ];
    }
}
