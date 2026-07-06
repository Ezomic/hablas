<?php

namespace Database\Factories;

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\CefrCanDoStatement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CefrCanDoStatement>
 */
class CefrCanDoStatementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cefr_level' => $this->faker->randomElement(CefrLevel::cases()),
            'skill' => $this->faker->randomElement(Skill::cases()),
            'statement_text' => $this->faker->sentence(),
        ];
    }
}
