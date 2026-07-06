<?php

namespace Database\Factories;

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'language_id' => Language::factory(),
            'slug' => $this->faker->unique()->slug(3),
            'cefr_level' => $this->faker->randomElement(CefrLevel::cases()),
            'context_tag' => $this->faker->randomElement(ContextTag::cases()),
            'primary_skill' => $this->faker->randomElement(Skill::cases()),
            'secondary_skill' => null,
            'title' => $this->faker->sentence(3),
            'task_description' => $this->faker->sentence(),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
