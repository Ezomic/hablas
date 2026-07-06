<?php

namespace Database\Factories;

use App\Enums\InterestTag;
use App\Models\User;
use App\Models\UserInterestPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserInterestPreference>
 */
class UserInterestPreferenceFactory extends Factory
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
            'interest_tag' => $this->faker->randomElement(InterestTag::cases()),
        ];
    }
}
