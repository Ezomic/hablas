<?php

namespace Database\Seeders;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Database\Seeder;

/**
 * Fixed-form Portuguese placement test items, structurally identical to
 * PlacementTestSeeder (Spanish) — not adaptive, but tagged with an
 * approximate CEFR sub-level difficulty for a future adaptive/IRT upgrade.
 * AI-drafted; needs a human review pass before being authoritative.
 */
class PortuguesePlacementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portuguese = Language::query()->where('code', 'pt')->firstOrFail();

        foreach ($this->items() as $sortOrder => $item) {
            PlacementTestItem::query()->updateOrCreate(
                ['language_id' => $portuguese->id, 'skill' => $item['skill'], 'prompt' => $item['prompt']],
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
                'prompt' => "O que significa 'o aeroporto'?",
                'options' => ['Airport', 'Hotel', 'Restaurant', 'Street'],
                'correct_answer' => 'Airport',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'O meu irmão é alto e a minha irmã é baixa.' Quem é baixa?",
                'options' => ['O meu irmão', 'A minha irmã', 'O meu pai', 'A minha mãe'],
                'correct_answer' => 'A minha irmã',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Qual é a tradução correta de 'a conta, por favor'?",
                'options' => ['The check, please', 'The menu, please', 'The key, please', 'The room, please'],
                'correct_answer' => 'The check, please',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê o cartaz: 'Quartos disponíveis. Pequeno-almoço incluído.' O que diz o cartaz?",
                'options' => ['Rooms available, breakfast included', 'No rooms available', 'Breakfast not included', 'Restaurant closed'],
                'correct_answer' => 'Rooms available, breakfast included',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Completa: 'Eu ___ estudante.'",
                'options' => ['sou', 'estou', 'é', 'está'],
                'correct_answer' => 'sou',
                'cefr_sublevel_tag' => 'A1.3',
            ],

            // Listening
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Bom dia, como está?' O que se pergunta?",
                'options' => ['How are you (formal)', 'What is your name', 'Where are you from', 'How old are you'],
                'correct_answer' => 'How are you (formal)',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'A porta de embarque é a número doze.' Que número se menciona?",
                'options' => ['Twelve', 'Two', 'Twenty', 'Twenty-two'],
                'correct_answer' => 'Twelve',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Queria um quarto para duas noites.' O que se pede?",
                'options' => ['A room for two nights', 'A table for two people', 'A ticket for two people', 'A discount for two nights'],
                'correct_answer' => 'A room for two nights',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Vire à esquerda na esquina.' Que direção se dá?",
                'options' => ['Turn left at the corner', 'Turn right at the corner', 'Go straight ahead', 'Stop at the corner'],
                'correct_answer' => 'Turn left at the corner',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Normalmente levanto-me às sete e meia.' A que horas se levanta normalmente?",
                'options' => ['7:30', '7:00', '8:30', '6:30'],
                'correct_answer' => '7:30',
                'cefr_sublevel_tag' => 'A1.3',
            ],

            // Speaking
            [
                'skill' => Skill::Speaking,
                'prompt' => "Alguém pergunta 'Como te chamas?' Chamas-te Ana. O que respondes?",
                'options' => ['Chamo-me Ana', 'Sou de Ana', 'Tenho Ana', 'Está Ana'],
                'correct_answer' => 'Chamo-me Ana',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres pedir água num restaurante. O que dizes?',
                'options' => ['Queria água, por favor', 'Queria a conta, por favor', 'Queria um quarto, por favor', 'Queria um mapa, por favor'],
                'correct_answer' => 'Queria água, por favor',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres perguntar se há um quarto de hotel disponível. O que dizes?',
                'options' => ['Há um quarto disponível?', 'Onde fica a casa de banho?', 'Quanto custa o pequeno-almoço?', 'A que horas é a saída?'],
                'correct_answer' => 'Há um quarto disponível?',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres dizer que a tua irmã é mais velha do que tu. Qual é correto?',
                'options' => ['A minha irmã é mais velha do que eu', 'A minha irmã está mais velha do que eu', 'A minha irmã é mais velha do que mim', 'A minha irmã sou mais velha'],
                'correct_answer' => 'A minha irmã é mais velha do que eu',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Queres dizer 'I get up early every day.' Qual é correto?",
                'options' => ['Levanto-me cedo todos os dias', 'Me levanto cedo todos os dias', 'Levanto-me cedo todo o dia', 'Eu levanto cedo todos os dias'],
                'correct_answer' => 'Levanto-me cedo todos os dias',
                'cefr_sublevel_tag' => 'A1.3',
            ],

            // Writing
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ela ___ professora.'",
                'options' => ['é', 'está', 'sou', 'és'],
                'correct_answer' => 'é',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'O hotel ___ perto do aeroporto.'",
                'options' => ['está', 'é', 'sou', 'são'],
                'correct_answer' => 'está',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Escolhe a forma correta: 'a camisa ___' (vermelha)",
                'options' => ['vermelha', 'vermelho', 'vermelhos', 'vermelhas'],
                'correct_answer' => 'vermelha',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nós ___ (comer) às duas.'",
                'options' => ['comemos', 'comeram', 'come', 'comer'],
                'correct_answer' => 'comemos',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nós ___ (falar) português.'",
                'options' => ['falamos', 'falam', 'fala', 'falar'],
                'correct_answer' => 'falamos',
                'cefr_sublevel_tag' => 'A1.3',
            ],
        ];
    }
}
