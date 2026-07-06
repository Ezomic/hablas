<?php

namespace Database\Seeders;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Database\Seeder;

/**
 * Fixed-form Spanish placement test items, per category 1 of the Feature
 * Brainstorm doc: not adaptive, but tagged with an approximate CEFR
 * sub-level difficulty from day one for a future adaptive/IRT upgrade.
 * AI-drafted; needs a human review pass before being authoritative.
 */
class PlacementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->items() as $sortOrder => $item) {
            PlacementTestItem::query()->updateOrCreate(
                ['language_id' => $spanish->id, 'skill' => $item['skill'], 'prompt' => $item['prompt']],
                [
                    'options' => $item['options'],
                    'correct_answer' => $item['correct_answer'],
                    'cefr_sublevel_tag' => $item['cefr_sublevel_tag'],
                    'sort_order' => $sortOrder + 1,
                ],
            );
        }
    }

    /**
     * @return array<int, array{skill: Skill, prompt: string, options: array<int, string>, correct_answer: string, cefr_sublevel_tag: string}>
     */
    private function items(): array
    {
        return [
            // Reading
            [
                'skill' => Skill::Reading,
                'prompt' => "¿Qué significa 'el aeropuerto'?",
                'options' => ['Airport', 'Hotel', 'Restaurant', 'Street'],
                'correct_answer' => 'Airport',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Mi hermano es alto y mi hermana es baja.' ¿Quién es baja?",
                'options' => ['Mi hermano', 'Mi hermana', 'Mi padre', 'Mi madre'],
                'correct_answer' => 'Mi hermana',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "¿Cuál es la traducción correcta de 'la cuenta, por favor'?",
                'options' => ['The check, please', 'The menu, please', 'The key, please', 'The room, please'],
                'correct_answer' => 'The check, please',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee el cartel: 'Habitaciones disponibles. Desayuno incluido.' ¿Qué dice el cartel?",
                'options' => ['Rooms available, breakfast included', 'No rooms available', 'Breakfast not included', 'Restaurant closed'],
                'correct_answer' => 'Rooms available, breakfast included',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Completa: 'Yo ___ estudiante.'",
                'options' => ['soy', 'estoy', 'es', 'está'],
                'correct_answer' => 'soy',
                'cefr_sublevel_tag' => 'A1.3',
            ],

            // Listening
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Buenos días, ¿cómo está usted?' ¿Qué se pregunta?",
                'options' => ['How are you (formal)', 'What is your name', 'Where are you from', 'How old are you'],
                'correct_answer' => 'How are you (formal)',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'La puerta de embarque es la número doce.' ¿Qué número se menciona?",
                'options' => ['Twelve', 'Two', 'Twenty', 'Twenty-two'],
                'correct_answer' => 'Twelve',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Quisiera una habitación para dos noches.' ¿Qué se pide?",
                'options' => ['A room for two nights', 'A table for two people', 'A ticket for two people', 'A discount for two nights'],
                'correct_answer' => 'A room for two nights',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Gire a la izquierda en la esquina.' ¿Qué dirección se da?",
                'options' => ['Turn left at the corner', 'Turn right at the corner', 'Go straight ahead', 'Stop at the corner'],
                'correct_answer' => 'Turn left at the corner',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Normalmente me levanto a las siete y media.' ¿A qué hora se levanta normalmente?",
                'options' => ['7:30', '7:00', '8:30', '6:30'],
                'correct_answer' => '7:30',
                'cefr_sublevel_tag' => 'A1.3',
            ],

            // Speaking
            [
                'skill' => Skill::Speaking,
                'prompt' => "Alguien pregunta '¿Cómo te llamas?' Te llamas Ana. ¿Qué respondes?",
                'options' => ['Me llamo Ana', 'Soy de Ana', 'Tengo Ana', 'Está Ana'],
                'correct_answer' => 'Me llamo Ana',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres pedir agua en un restaurante. ¿Qué dices?',
                'options' => ['Quisiera agua, por favor', 'Quisiera la cuenta, por favor', 'Quisiera una habitación, por favor', 'Quisiera un mapa, por favor'],
                'correct_answer' => 'Quisiera agua, por favor',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres preguntar si hay una habitación de hotel disponible. ¿Qué dices?',
                'options' => ['¿Hay una habitación disponible?', '¿Dónde está el baño?', '¿Cuánto cuesta el desayuno?', '¿A qué hora es la salida?'],
                'correct_answer' => '¿Hay una habitación disponible?',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres decir que tu hermana es mayor que tú. ¿Cuál es correcto?',
                'options' => ['Mi hermana es mayor que yo', 'Mi hermana está mayor que yo', 'Mi hermana es mayor que mí', 'Mi hermana soy mayor'],
                'correct_answer' => 'Mi hermana es mayor que yo',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Quieres decir 'I get up early every day.' ¿Cuál es correcto?",
                'options' => ['Me levanto temprano todos los días', 'Levanto me temprano todos los días', 'Me levanto temprano todo el día', 'Yo levanto temprano todos los días'],
                'correct_answer' => 'Me levanto temprano todos los días',
                'cefr_sublevel_tag' => 'A1.3',
            ],

            // Writing
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ella ___ profesora.'",
                'options' => ['es', 'está', 'soy', 'eres'],
                'correct_answer' => 'es',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'El hotel ___ cerca del aeropuerto.'",
                'options' => ['está', 'es', 'soy', 'son'],
                'correct_answer' => 'está',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Elige la forma correcta: 'la camisa ___' (roja)",
                'options' => ['roja', 'rojo', 'rojos', 'rojas'],
                'correct_answer' => 'roja',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nosotros ___ (comer) a las dos.'",
                'options' => ['comemos', 'comimos', 'come', 'comer'],
                'correct_answer' => 'comemos',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Mis padres se ___ Juan y María.' (llamarse)",
                'options' => ['llaman', 'llama', 'llamamos', 'llamas'],
                'correct_answer' => 'llaman',
                'cefr_sublevel_tag' => 'A1.3',
            ],
        ];
    }
}
