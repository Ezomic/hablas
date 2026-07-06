<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\ShadowingExercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShadowingExercise>
 */
class ShadowingExerciseFactory extends Factory
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
            'target_transcript' => $this->faker->sentence(),
            'audio_url' => null,
        ];
    }
}
