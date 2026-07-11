<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $code = $this->faker->unique()->languageCode();
        } while (in_array($code, ['es', 'pt'], true));

        return [
            'code' => $code,
            'name' => $this->faker->unique()->word(),
        ];
    }
}
