<?php

namespace Database\Factories;

use App\Enums\UnitProgressStatus;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserUnitProgress>
 */
class UserUnitProgressFactory extends Factory
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
            'unit_id' => Unit::factory(),
            'status' => UnitProgressStatus::Completed,
            'completed_at' => now(),
        ];
    }
}
