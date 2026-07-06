<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\VocabularyItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VocabularyItem>
 */
class VocabularyItemFactory extends Factory
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
            'term' => $this->faker->unique()->word(),
            'translation_en' => $this->faker->unique()->word(),
            'is_cognate' => false,
            'part_of_speech' => $this->faker->randomElement(['noun', 'verb', 'adjective', 'adverb']),
            'audio_url' => null,
        ];
    }
}
