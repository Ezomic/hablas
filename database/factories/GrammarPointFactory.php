<?php

namespace Database\Factories;

use App\Models\GrammarPoint;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GrammarPoint>
 */
class GrammarPointFactory extends Factory
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
            'unit_id' => null,
            'title' => $this->faker->sentence(3),
            'explanation' => $this->faker->paragraph(),
            'error_tag_category' => null,
        ];
    }
}
