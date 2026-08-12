<?php

namespace Database\Seeders;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\ReadingPassage;
use Illuminate\Database\Seeder;

/**
 * Comprehensible-input passages for Spanish, with multiple-choice
 * comprehension questions. Levelled so a learner reads at or just below where
 * they already are, which is what the reading page's level filter enforces.
 *
 * AI-drafted, same caveat as the other Spanish content seeders: worth a
 * native-speaker pass before being treated as authoritative.
 */
class ReadingPassageSeeder extends Seeder
{
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->passages() as $passage) {
            ReadingPassage::query()->updateOrCreate(
                [
                    'language_id' => $spanish->id,
                    'title' => $passage['title'],
                ],
                [
                    'unit_id' => null,
                    'cefr_level' => $passage['cefr_level'],
                    'body' => $passage['body'],
                    'questions' => $passage['questions'],
                ],
            );
        }
    }

    /**
     * @return array<int, array{
     *     title: string,
     *     cefr_level: CefrLevel,
     *     body: string,
     *     questions: array<int, array{prompt: string, options: array<int, string>, correct_answer: string}>
     * }>
     */
    private function passages(): array
    {
        return [
            [
                'title' => 'Una mañana en casa',
                'cefr_level' => CefrLevel::A1,
                'body' => "Me llamo Ana. Vivo en Madrid con mi hermano.\n\nPor la mañana bebo café y como pan con tomate. Mi hermano bebe té porque no le gusta el café. A las ocho salgo de casa y voy al trabajo en autobús.",
                'questions' => [
                    [
                        'prompt' => '¿Dónde vive Ana?',
                        'options' => ['En Madrid', 'En Barcelona', 'En Sevilla'],
                        'correct_answer' => 'En Madrid',
                    ],
                    [
                        'prompt' => '¿Qué bebe su hermano?',
                        'options' => ['Café', 'Té', 'Agua'],
                        'correct_answer' => 'Té',
                    ],
                    [
                        'prompt' => '¿Cómo va Ana al trabajo?',
                        'options' => ['En coche', 'En autobús', 'A pie'],
                        'correct_answer' => 'En autobús',
                    ],
                ],
            ],
            [
                'title' => 'En el mercado',
                'cefr_level' => CefrLevel::A1,
                'body' => "Los sábados voy al mercado con mi madre. Compramos fruta, verduras y pan.\n\nLa fruta está muy buena y no es cara. Mi madre siempre habla con el señor de las naranjas. Después tomamos un café en la plaza.",
                'questions' => [
                    [
                        'prompt' => '¿Cuándo van al mercado?',
                        'options' => ['Los lunes', 'Los sábados', 'Los domingos'],
                        'correct_answer' => 'Los sábados',
                    ],
                    [
                        'prompt' => '¿Con quién va al mercado?',
                        'options' => ['Con su madre', 'Con su hermano', 'Solo'],
                        'correct_answer' => 'Con su madre',
                    ],
                    [
                        'prompt' => '¿Cómo es la fruta?',
                        'options' => ['Cara', 'Mala', 'Buena y barata'],
                        'correct_answer' => 'Buena y barata',
                    ],
                ],
            ],
            [
                'title' => 'Un viaje en tren',
                'cefr_level' => CefrLevel::A2,
                'body' => "El verano pasado fui a Valencia en tren. El viaje duró casi tres horas, pero el paisaje era precioso.\n\nCuando llegué, hacía mucho calor, así que dejé la maleta en el hotel y fui directamente a la playa. Por la noche cené paella con unos amigos que viven allí desde hace años. Volví el domingo, cansada pero contenta.",
                'questions' => [
                    [
                        'prompt' => '¿Cuánto duró el viaje?',
                        'options' => ['Una hora', 'Casi tres horas', 'Todo el día'],
                        'correct_answer' => 'Casi tres horas',
                    ],
                    [
                        'prompt' => '¿Qué hizo al llegar?',
                        'options' => ['Fue a la playa', 'Fue a cenar', 'Volvió a casa'],
                        'correct_answer' => 'Fue a la playa',
                    ],
                    [
                        'prompt' => '¿Con quién cenó?',
                        'options' => ['Sola', 'Con su familia', 'Con unos amigos'],
                        'correct_answer' => 'Con unos amigos',
                    ],
                    [
                        'prompt' => '¿Cómo volvió?',
                        'options' => ['Cansada pero contenta', 'Enfadada', 'Enferma'],
                        'correct_answer' => 'Cansada pero contenta',
                    ],
                ],
            ],
            [
                'title' => 'Buscando piso',
                'cefr_level' => CefrLevel::A2,
                'body' => "Llevo dos meses buscando piso y todavía no he encontrado nada. Los pisos del centro son muy caros y los baratos están demasiado lejos del trabajo.\n\nAyer vi uno que me gustó bastante: tenía dos habitaciones, mucha luz y un balcón pequeño. El problema es que no admiten animales, y yo tengo un gato. Voy a seguir buscando.",
                'questions' => [
                    [
                        'prompt' => '¿Cuánto tiempo lleva buscando piso?',
                        'options' => ['Dos semanas', 'Dos meses', 'Dos años'],
                        'correct_answer' => 'Dos meses',
                    ],
                    [
                        'prompt' => '¿Por qué no le sirven los pisos baratos?',
                        'options' => ['Son pequeños', 'Están lejos del trabajo', 'Son viejos'],
                        'correct_answer' => 'Están lejos del trabajo',
                    ],
                    [
                        'prompt' => '¿Cuál es el problema del piso que vio ayer?',
                        'options' => ['No admiten animales', 'No tiene luz', 'Es muy caro'],
                        'correct_answer' => 'No admiten animales',
                    ],
                ],
            ],
        ];
    }
}
