<?php

namespace Database\Factories;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlacementTestResponse>
 */
class PlacementTestResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attempt_id' => PlacementTestAttempt::factory(),
            'item_id' => PlacementTestItem::factory(),
            'skill' => $this->faker->randomElement(Skill::cases()),
            'response' => $this->faker->word(),
            'is_correct' => $this->faker->boolean(),
            'tier_at_time' => CefrSubLevel::A1_3,
            'answered_at' => now(),
        ];
    }
}
