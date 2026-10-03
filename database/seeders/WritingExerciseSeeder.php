<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\WritingExerciseType;
use App\Models\Language;
use App\Models\Unit;
use App\Models\WritingExercise;
use Illuminate\Database\Seeder;

/**
 * Structured, auto-gradable Spanish A1 writing exercises across all three
 * types. AI-drafted; needs the same human review pass as the other content
 * seeders before being authoritative.
 */
class WritingExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->exercises() as $exercise) {
            WritingExercise::query()->updateOrCreate(
                ['language_id' => $spanish->id, 'type' => $exercise['type'], 'prompt' => $exercise['prompt']],
                [
                    'unit_id' => $exercise['unit'] === null ? null : Unit::query()->where('language_id', $spanish->id)->where('slug', $exercise['unit'])->value('id'),
                    'template' => $exercise['template'],
                    'correct_answers' => $exercise['correct_answers'],
                ],
            );
        }
    }

    /**
     * @return array<int, array{unit: string|null, type: WritingExerciseType, prompt: string, template: array<string, mixed>|null, correct_answers: array<int, string>}>
     */
    private function exercises(): array
    {
        return [
            [
                'unit' => 'greetings-and-introductions',
                'type' => WritingExerciseType::FillInTemplate,
                'prompt' => "Completa: 'Yo ___ estudiante.'",
                'template' => ['text' => 'Yo ___ estudiante.'],
                'correct_answers' => ['soy'],
            ],
            [
                'unit' => null,
                'type' => WritingExerciseType::FillInTemplate,
                'prompt' => "Completa: 'El hotel ___ cerca del aeropuerto.'",
                'template' => ['text' => 'El hotel ___ cerca del aeropuerto.'],
                'correct_answers' => ['está'],
            ],
            [
                'unit' => 'shopping-for-clothes',
                'type' => WritingExerciseType::FillInTemplate,
                'prompt' => "Elige la forma correcta: 'la camisa ___' (roja)",
                'template' => ['text' => 'la camisa ___'],
                'correct_answers' => ['roja'],
            ],
            [
                'unit' => null,
                'type' => WritingExerciseType::SentenceTransformation,
                'prompt' => 'Cambia a la forma negativa:',
                'template' => ['text' => 'Quiero café.'],
                'correct_answers' => ['no quiero café'],
            ],
            [
                'unit' => 'asking-for-directions',
                'type' => WritingExerciseType::SentenceTransformation,
                'prompt' => 'Cambia a la primera persona del plural (nosotros):',
                'template' => ['text' => 'Él come a las dos.'],
                'correct_answers' => ['nosotros comemos a las dos', 'comemos a las dos'],
            ],
            [
                'unit' => 'describing-your-daily-routine',
                'type' => WritingExerciseType::GuidedParagraph,
                'prompt' => 'Describe tu rutina diaria. Usa las palabras: levantarse, desayunar, trabajar.',
                'template' => null,
                'correct_answers' => ['levant', 'desayun', 'trabaj'],
            ],
        ];
    }
}
