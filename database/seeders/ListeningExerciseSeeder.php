<?php

namespace Database\Seeders;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\ListeningExercise;
use Illuminate\Database\Seeder;

/**
 * Short spoken clips for Spanish with multiple-choice comprehension questions.
 * The transcript is what the browser reads aloud, never shown to the learner,
 * so these are written to be understood by ear: short sentences, everyday
 * vocabulary and no orthography-dependent jokes.
 *
 * AI-drafted, same caveat as the other Spanish content seeders: worth a
 * native-speaker pass before being treated as authoritative.
 */
class ListeningExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->clips() as $clip) {
            ListeningExercise::query()->updateOrCreate(
                [
                    'language_id' => $spanish->id,
                    'title' => $clip['title'],
                ],
                [
                    'unit_id' => null,
                    'cefr_level' => $clip['cefr_level'],
                    'transcript' => $clip['transcript'],
                    'audio_url' => null,
                    'questions' => $clip['questions'],
                ],
            );
        }
    }

    /**
     * @return array<int, array{
     *     title: string,
     *     cefr_level: CefrLevel,
     *     transcript: string,
     *     questions: array<int, array{prompt: string, options: array<int, string>, correct_answer: string}>
     * }>
     */
    private function clips(): array
    {
        return [
            [
                'title' => 'Una llamada corta',
                'cefr_level' => CefrLevel::A1,
                'transcript' => 'Hola, soy Carmen. Llego tarde porque el autobús no viene. Nos vemos a las siete en el restaurante. Hasta luego.',
                'questions' => [
                    [
                        'prompt' => '¿Quién habla?',
                        'options' => ['Carmen', 'Ana', 'Pedro'],
                        'correct_answer' => 'Carmen',
                    ],
                    [
                        'prompt' => '¿Por qué llega tarde?',
                        'options' => ['Está enferma', 'El autobús no viene', 'Trabaja mucho'],
                        'correct_answer' => 'El autobús no viene',
                    ],
                    [
                        'prompt' => '¿A qué hora se ven?',
                        'options' => ['A las seis', 'A las siete', 'A las ocho'],
                        'correct_answer' => 'A las siete',
                    ],
                ],
            ],
            [
                'title' => 'En la cafetería',
                'cefr_level' => CefrLevel::A1,
                'transcript' => 'Buenos días. Un café con leche y un zumo de naranja, por favor. ¿Cuánto es? Son cuatro euros con cincuenta.',
                'questions' => [
                    [
                        'prompt' => '¿Qué pide la persona?',
                        'options' => [
                            'Un café y un zumo',
                            'Un té y agua',
                            'Dos cafés',
                        ],
                        'correct_answer' => 'Un café y un zumo',
                    ],
                    [
                        'prompt' => '¿Cuánto cuesta?',
                        'options' => ['Cuatro euros', 'Cuatro cincuenta', 'Cinco euros'],
                        'correct_answer' => 'Cuatro cincuenta',
                    ],
                ],
            ],
            [
                'title' => 'El fin de semana',
                'cefr_level' => CefrLevel::A2,
                'transcript' => 'El sábado pasado fui al cine con mi hermana. Vimos una película muy larga, casi tres horas, y salimos bastante cansados. El domingo me quedé en casa leyendo, porque llovió todo el día.',
                'questions' => [
                    [
                        'prompt' => '¿Con quién fue al cine?',
                        'options' => ['Con su hermana', 'Con un amigo', 'Solo'],
                        'correct_answer' => 'Con su hermana',
                    ],
                    [
                        'prompt' => '¿Cómo era la película?',
                        'options' => ['Corta', 'Muy larga', 'Aburrida'],
                        'correct_answer' => 'Muy larga',
                    ],
                    [
                        'prompt' => '¿Qué hizo el domingo?',
                        'options' => ['Salió a pasear', 'Se quedó leyendo', 'Fue al cine otra vez'],
                        'correct_answer' => 'Se quedó leyendo',
                    ],
                ],
            ],
            [
                'title' => 'Un mensaje del trabajo',
                'cefr_level' => CefrLevel::A2,
                'transcript' => 'Buenas tardes. La reunión del martes se cambia al jueves a las diez de la mañana. Será en la sala pequeña, no en la grande. Si no puedes venir, avísame antes del miércoles.',
                'questions' => [
                    [
                        'prompt' => '¿Qué día es ahora la reunión?',
                        'options' => ['El martes', 'El jueves', 'El miércoles'],
                        'correct_answer' => 'El jueves',
                    ],
                    [
                        'prompt' => '¿Dónde será?',
                        'options' => ['En la sala grande', 'En la sala pequeña', 'En la oficina'],
                        'correct_answer' => 'En la sala pequeña',
                    ],
                    [
                        'prompt' => '¿Hasta cuándo puede avisar?',
                        'options' => [
                            'Antes del miércoles',
                            'Antes del jueves',
                            'El mismo día',
                        ],
                        'correct_answer' => 'Antes del miércoles',
                    ],
                ],
            ],
        ];
    }
}
