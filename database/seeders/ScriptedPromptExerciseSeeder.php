<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\ScriptedPromptExercise;
use App\Models\Unit;
use Illuminate\Database\Seeder;

/**
 * Tier-2 scripted-prompt exercises: a fixed question per seeded Spanish A1
 * unit, keyword-scored rather than exact-match. AI-drafted; needs the same
 * human review pass as the other content seeders before being authoritative.
 */
class ScriptedPromptExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->exercises() as $unitSlug => $exercise) {
            $unit = Unit::query()->where('language_id', $spanish->id)->where('slug', $unitSlug)->first();

            ScriptedPromptExercise::query()->updateOrCreate(
                ['language_id' => $spanish->id, 'unit_id' => $unit?->id, 'prompt_text' => $exercise['prompt_text']],
                ['expected_keywords' => $exercise['expected_keywords']],
            );
        }
    }

    /** @return array<string, array{prompt_text: string, expected_keywords: array<int, string>}> */
    private function exercises(): array
    {
        return [
            'greetings-and-introductions' => [
                'prompt_text' => 'Preséntate en español: di tu nombre y de dónde eres.',
                'expected_keywords' => ['llamo', 'soy'],
            ],
            'at-the-airport' => [
                'prompt_text' => 'Pregunta si tu vuelo está retrasado.',
                'expected_keywords' => ['vuelo', 'retrasado'],
            ],
            'checking-into-a-hotel' => [
                'prompt_text' => 'Pide una habitación para dos noches.',
                'expected_keywords' => ['habitacion', 'noches'],
            ],
            'ordering-food-at-a-restaurant' => [
                'prompt_text' => 'Pide agua y la cuenta en un restaurante.',
                'expected_keywords' => ['agua', 'cuenta'],
            ],
            'asking-for-directions' => [
                'prompt_text' => 'Pregunta dónde está la plaza.',
                'expected_keywords' => ['donde', 'plaza'],
            ],
            'shopping-for-clothes' => [
                'prompt_text' => 'Pregunta cuánto cuesta una camisa roja.',
                'expected_keywords' => ['cuesta', 'camisa'],
            ],
            'talking-about-your-family' => [
                'prompt_text' => 'Describe a un miembro de tu familia.',
                'expected_keywords' => ['herman', 'familia'],
            ],
            'describing-your-daily-routine' => [
                'prompt_text' => 'Describe tu rutina diaria.',
                'expected_keywords' => ['levant', 'desayun', 'trabaj'],
            ],
        ];
    }
}
