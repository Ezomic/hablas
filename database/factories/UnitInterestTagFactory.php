<?php

namespace Database\Factories;

use App\Enums\InterestTag;
use App\Models\Unit;
use App\Models\UnitInterestTag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UnitInterestTag>
 */
class UnitInterestTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'interest_tag' => $this->faker->randomElement(InterestTag::cases()),
        ];
    }
}
