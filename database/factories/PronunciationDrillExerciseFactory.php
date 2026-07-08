<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\PronunciationDrillExercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PronunciationDrillExercise>
 */
class PronunciationDrillExerciseFactory extends Factory
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
            'word_a' => 'pão',
            'word_a_translation_en' => 'bread',
            'word_b' => 'pau',
            'word_b_translation_en' => 'stick',
            'target_word' => 'pão',
            'audio_url' => null,
        ];
    }
}
