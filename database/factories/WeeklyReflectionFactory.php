<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\User;
use App\Models\WeeklyReflection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeeklyReflection>
 */
class WeeklyReflectionFactory extends Factory
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
            'week_start_date' => now()->startOfWeek(),
            'responses' => ['statement_ids' => [], 'can_do_ids' => []],
            'submitted_at' => null,
        ];
    }
}
