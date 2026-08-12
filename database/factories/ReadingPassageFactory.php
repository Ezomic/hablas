<?php

namespace Database\Factories;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\ReadingPassage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReadingPassage>
 */
class ReadingPassageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'language_id' => Language::firstOrCreate(['code' => 'es'], ['name' => 'Spanish']),
            'unit_id' => null,
            'cefr_level' => CefrLevel::A1,
            'title' => $this->faker->sentence(3),
            'body' => $this->faker->paragraph(),
            'questions' => [
                [
                    'prompt' => '¿Qué dice el texto?',
                    'options' => ['uno', 'dos', 'tres'],
                    'correct_answer' => 'uno',
                ],
                [
                    'prompt' => '¿Dónde ocurre?',
                    'options' => ['aquí', 'allí'],
                    'correct_answer' => 'aquí',
                ],
            ],
        ];
    }
}
