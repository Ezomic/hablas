<?php

namespace Database\Factories;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlacementTestItem>
 */
class PlacementTestItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $options = [$this->faker->unique()->word(), $this->faker->unique()->word(), $this->faker->unique()->word(), $this->faker->unique()->word()];

        return [
            'language_id' => Language::factory(),
            'skill' => $this->faker->randomElement(Skill::cases()),
            'prompt' => $this->faker->sentence().'?',
            'options' => $options,
            'correct_answer' => $options[0],
            'cefr_sublevel_tag' => CefrSubLevel::A1_1,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function tier(CefrSubLevel $tier): self
    {
        return $this->state(fn (array $attributes): array => ['cefr_sublevel_tag' => $tier]);
    }
}
