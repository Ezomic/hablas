<?php

namespace Database\Seeders;

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\ErrorTagCategory;
use App\Enums\InterestTag;
use App\Enums\Skill;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Unit;
use App\Models\UnitInterestTag;
use App\Models\VocabularyItem;
use Illuminate\Database\Seeder;

/**
 * Seeds Portuguese A1 content mirroring SpanishA1Seeder's 8 units topic-for-topic,
 * so the app's contrastive mode can point at a matching Spanish unit for every
 * Portuguese one. AI-drafted; needs a human review pass — including a
 * native/near-native Portuguese speaker's review of the contrast_note claims
 * specifically (false-friend and gender-flip claims are easy to get subtly
 * wrong) — before being treated as authoritative teaching material, per the
 * content-sourcing pipeline in the Feature Brainstorm doc (category 6).
 */
class PortugueseA1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portuguese = Language::query()->where('code', 'pt')->firstOrFail();

        foreach ($this->units() as $sortOrder => $definition) {
            $unit = Unit::query()->updateOrCreate(
                ['language_id' => $portuguese->id, 'slug' => $definition['slug']],
                [
                    'title' => $definition['title'],
                    'cefr_level' => CefrLevel::A1,
                    'context_tag' => $definition['context_tag'],
                    'primary_skill' => $definition['primary_skill'],
                    'secondary_skill' => $definition['secondary_skill'],
                    'task_description' => $definition['task_description'],
                    'sort_order' => $sortOrder + 1,
                    'contrast_note' => $definition['contrast_note'],
                ],
            );

            foreach ($definition['vocabulary'] as $vocabulary) {
                VocabularyItem::query()->updateOrCreate(
                    ['language_id' => $portuguese->id, 'unit_id' => $unit->id, 'term' => $vocabulary['term']],
                    $vocabulary,
                );
            }

            foreach ($definition['grammar'] as $grammar) {
                GrammarPoint::query()->updateOrCreate(
                    ['language_id' => $portuguese->id, 'unit_id' => $unit->id, 'title' => $grammar['title']],
                    $grammar,
                );
            }

            foreach ($definition['interest_tags'] as $interestTag) {
                UnitInterestTag::query()->updateOrCreate(
                    ['unit_id' => $unit->id, 'interest_tag' => $interestTag],
                );
            }
        }
    }

    /**
     * @return array<int, array{
     *     slug: string,
     *     title: string,
     *     context_tag: ContextTag,
     *     primary_skill: Skill,
     *     secondary_skill: Skill,
     *     task_description: string,
     *     contrast_note: string|null,
     *     vocabulary: array<int, array{term: string, translation_en: string, is_cognate: bool, part_of_speech: string, contrast_note?: string|null}>,
     *     grammar: array<int, array{title: string, explanation: string, error_tag_category: ErrorTagCategory|null}>,
     *     interest_tags: array<int, InterestTag>,
     * }>
     */
    private function units(): array
    {
        return [
            [
                'slug' => 'greetings-and-introductions',
                'title' => 'Greetings and introductions',
                'context_tag' => ContextTag::EverydaySocial,
                'primary_skill' => Skill::Speaking,
                'secondary_skill' => Skill::Listening,
                'task_description' => 'Introduce yourself to someone new and greet people appropriately at different times of day.',
                'contrast_note' => 'Portuguese nasalizes vowels (não, mãe, ...) in a way Spanish never does — listen for the nasal hum on ão/ãe endings, not just the letters.',
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'olá', 'translation_en' => 'hello', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'bom dia', 'translation_en' => 'good morning', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'boa tarde', 'translation_en' => 'good afternoon', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'boa noite', 'translation_en' => 'good evening / good night', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'adeus', 'translation_en' => 'goodbye', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'chamo-me', 'translation_en' => 'my name is', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "The reflexive pronoun comes after the verb here (chamo-me), unlike Spanish's 'me llamo' where it comes first — see the daily-routine unit for more on this word-order difference."],
                    ['term' => 'muito prazer', 'translation_en' => 'nice to meet you', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'como estás?', 'translation_en' => 'how are you?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'bem', 'translation_en' => 'well / fine', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'obrigado', 'translation_en' => 'thank you', 'is_cognate' => false, 'part_of_speech' => 'interjection', 'contrast_note' => "Unlike Spanish's invariant 'gracias', Portuguese 'thank you' agrees with the speaker's gender: a man says 'obrigado', a woman says 'obrigada'."],
                ],
                'grammar' => [
                    [
                        'title' => 'Subject pronouns and ser for identity',
                        'explanation' => 'Portuguese keeps the same ser/estar split as Spanish almost unchanged: ser for identity and origin (sou, és, é, somos, sois, são) versus estar for location and temporary states. If ser/estar already feels natural from Spanish, it transfers directly here — this is one of the easiest parts of the whole language for a Spanish speaker.',
                        'error_tag_category' => null,
                    ],
                ],
            ],
            [
                'slug' => 'at-the-airport',
                'title' => 'At the airport',
                'context_tag' => ContextTag::Travel,
                'primary_skill' => Skill::Listening,
                'secondary_skill' => Skill::Reading,
                'task_description' => 'Understand airport announcements, signs, and basic travel vocabulary.',
                'contrast_note' => 'Grammatical gender mostly matches Spanish word-for-word for shared-root vocabulary (o aeroporto/el aeropuerto, a mala/la maleta) — but don\'t assume it always does; later units flag real exceptions.',
                'interest_tags' => [InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'o aeroporto', 'translation_en' => 'airport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'o voo', 'translation_en' => 'flight', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a mala', 'translation_en' => 'suitcase', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'o passaporte', 'translation_en' => 'passport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'a porta', 'translation_en' => 'gate / door', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a saída', 'translation_en' => 'departure / exit', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Same word family as Spanish 'la salida' — note the accent shift to í, and the gender still matches (feminine)."],
                    ['term' => 'a chegada', 'translation_en' => 'arrival', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'o bilhete', 'translation_en' => 'ticket', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'atrasado', 'translation_en' => 'delayed', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'internacional', 'translation_en' => 'international', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Grammatical gender: o / a',
                        'explanation' => "Portuguese keeps the same masculine/feminine noun system as Spanish, and for most shared-root words the gender carries straight over: el vuelo → o voo (both masculine), la maleta → a mala (both feminine). The article changes shape (el/la → o/a) but the underlying pattern is the same one already learned for Spanish. Watch for the exceptions flagged later, though — the gender doesn't always match.",
                        'error_tag_category' => ErrorTagCategory::WrongGender,
                    ],
                ],
            ],
            [
                'slug' => 'checking-into-a-hotel',
                'title' => 'Checking into a hotel',
                'context_tag' => ContextTag::Travel,
                'primary_skill' => Skill::Speaking,
                'secondary_skill' => Skill::Writing,
                'task_description' => 'Check into a hotel, ask about room availability, and understand what is included.',
                'contrast_note' => null,
                'interest_tags' => [InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'o hotel', 'translation_en' => 'hotel', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'o quarto', 'translation_en' => 'room', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Gender flip from Spanish: 'o quarto' is masculine, but the Spanish equivalent 'la habitación' is feminine — don't carry the Spanish gender over here."],
                    ['term' => 'a reserva', 'translation_en' => 'reservation', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'a chave', 'translation_en' => 'key', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'o recepcionista', 'translation_en' => 'receptionist', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'disponível', 'translation_en' => 'available', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'a noite', 'translation_en' => 'night', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a casa de banho', 'translation_en' => 'bathroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'incluído', 'translation_en' => 'included', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'o pequeno-almoço', 'translation_en' => 'breakfast', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Estar for location and temporary states',
                        'explanation' => "Estar (estou, estás, está, estamos, estais, estão) covers location and temporary states here exactly as it does in Spanish — 'O hotel está perto' works the same way as 'El hotel está cerca'. Like ser in the greetings unit, this is one of the parts of Portuguese that transfers almost unchanged from Spanish.",
                        'error_tag_category' => null,
                    ],
                ],
            ],
            [
                'slug' => 'ordering-food-at-a-restaurant',
                'title' => 'Ordering food at a restaurant',
                'context_tag' => ContextTag::Travel,
                'primary_skill' => Skill::Speaking,
                'secondary_skill' => Skill::Reading,
                'task_description' => 'Order a meal at a restaurant and ask questions about menu items.',
                'contrast_note' => null,
                'interest_tags' => [InterestTag::Food, InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'o restaurante', 'translation_en' => 'restaurant', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'a ementa', 'translation_en' => 'menu', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el menú' — 'ementa' is the native Portuguese term for a restaurant menu, though 'menu' is also understood."],
                    ['term' => 'a conta', 'translation_en' => 'bill / check', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'queria', 'translation_en' => 'I would like', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'o copo', 'translation_en' => 'glass', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "False friend risk: looks like Spanish 'la copa', but 'copa' in Portuguese means 'trophy' (or a stemmed glass) — a plain drinking glass is 'o copo'."],
                    ['term' => 'para comer', 'translation_en' => 'to eat', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'o empregado', 'translation_en' => 'waiter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'delicioso', 'translation_en' => 'delicious', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'a gorjeta', 'translation_en' => 'tip', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'vegetariano', 'translation_en' => 'vegetarian', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Present tense of -ar verbs',
                        'explanation' => 'Regular -ar verbs like falar (to speak) and tomar (to take/have) conjugate falo, falas, fala, falamos, falais, falam — the same pattern shape as Spanish hablar (hablo, hablas, habla...), just with Portuguese\'s own vowel sounds.',
                        'error_tag_category' => null,
                    ],
                ],
            ],
            [
                'slug' => 'asking-for-directions',
                'title' => 'Asking for directions',
                'context_tag' => ContextTag::Travel,
                'primary_skill' => Skill::Listening,
                'secondary_skill' => Skill::Speaking,
                'task_description' => 'Ask for and understand directions around a city.',
                'contrast_note' => 'Several direction words are entirely different roots from Spanish (esquerda vs izquierda, perto vs cerca, longe vs lejos) — this unit carries more false-friend/interference risk than most, since the words are unrelated enough that a Portuñol slip (defaulting to the Spanish word) is common.',
                'interest_tags' => [InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'a rua', 'translation_en' => 'street', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a esquina', 'translation_en' => 'corner', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'à direita', 'translation_en' => 'to the right', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'à esquerda', 'translation_en' => 'to the left', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Completely different root from Spanish 'izquierda' — a classic Portuñol slip is defaulting to the Spanish word here."],
                    ['term' => 'sempre em frente', 'translation_en' => 'straight ahead', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'perto', 'translation_en' => 'near', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'longe', 'translation_en' => 'far', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'o mapa', 'translation_en' => 'map', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'onde fica...?', 'translation_en' => 'where is...?', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Portuguese commonly asks for a location with 'ficar' (to be located) rather than 'estar', unlike Spanish's '¿dónde está...?' — both are understood, but 'fica' sounds more native."],
                    ['term' => 'a praça', 'translation_en' => 'square / plaza', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Present tense of -er and -ir verbs, and ficar for location',
                        'explanation' => "-er verbs like comer (como, comes, come, comemos, comeis, comem) and -ir verbs like partir follow the same pattern shape as their Spanish equivalents. The new wrinkle here is ficar: Portuguese uses it (not estar) as the default verb for 'where is X located', which has no direct Spanish parallel and is worth practicing deliberately rather than assuming estar always works.",
                        'error_tag_category' => ErrorTagCategory::PortunolSlip,
                    ],
                ],
            ],
            [
                'slug' => 'shopping-for-clothes',
                'title' => 'Shopping for clothes',
                'context_tag' => ContextTag::Travel,
                'primary_skill' => Skill::Speaking,
                'secondary_skill' => Skill::Reading,
                'task_description' => 'Buy clothes, ask about size, color, and price.',
                'contrast_note' => null,
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'a roupa', 'translation_en' => 'clothing', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a camisa', 'translation_en' => 'shirt', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'as calças', 'translation_en' => 'pants', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word and gender from Spanish 'los pantalones' (masculine) — Portuguese 'calças' is feminine."],
                    ['term' => 'o preço', 'translation_en' => 'price', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'o tamanho', 'translation_en' => 'size', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a cor', 'translation_en' => 'color', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Gender flip from Spanish: 'a cor' is feminine, but 'el color' is masculine in Spanish."],
                    ['term' => 'caro', 'translation_en' => 'expensive', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'barato', 'translation_en' => 'cheap', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'experimentar', 'translation_en' => 'to try on', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => "Unlike Spanish's reflexive 'probarse', Portuguese 'experimentar' isn't reflexive."],
                    ['term' => 'o desconto', 'translation_en' => 'discount', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Adjective agreement (gender and number)',
                        'explanation' => "The agreement rule itself transfers directly from Spanish — adjectives still match gender and number ('a camisa cara', 'as camisas caras'). What doesn't always transfer is the gender of the noun being described, as 'a cor' above shows, so agreement mistakes in Portuguese are often really gender mistakes carried over from Spanish.",
                        'error_tag_category' => ErrorTagCategory::WrongGender,
                    ],
                ],
            ],
            [
                'slug' => 'talking-about-your-family',
                'title' => 'Talking about your family',
                'context_tag' => ContextTag::EverydaySocial,
                'primary_skill' => Skill::Speaking,
                'secondary_skill' => Skill::Writing,
                'task_description' => 'Describe your family members and their relationships to you.',
                'contrast_note' => null,
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'a família', 'translation_en' => 'family', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'o pai', 'translation_en' => 'father', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a mãe', 'translation_en' => 'mother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'o irmão', 'translation_en' => 'brother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a irmã', 'translation_en' => 'sister', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'o filho', 'translation_en' => 'son', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'os avós', 'translation_en' => 'grandparents', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'casado', 'translation_en' => 'married', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'solteiro', 'translation_en' => 'single', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'mais velho', 'translation_en' => 'older', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Portuguese uses the comparative phrase 'mais velho' (more old) rather than a single adjective like Spanish 'mayor'."],
                ],
                'grammar' => [
                    [
                        'title' => 'Possessive adjectives with an article (o meu, a tua, o seu)',
                        'explanation' => "Portuguese possessives normally keep the article: 'o meu irmão', 'a minha irmã' — unlike Spanish, which drops it ('mi hermano', not 'el mi hermano'). Dropping the article out of habit from Spanish is one of the most common Portuñol slips at this level.",
                        'error_tag_category' => ErrorTagCategory::PortunolSlip,
                    ],
                ],
            ],
            [
                'slug' => 'describing-your-daily-routine',
                'title' => 'Describing your daily routine',
                'context_tag' => ContextTag::EverydaySocial,
                'primary_skill' => Skill::Writing,
                'secondary_skill' => Skill::Speaking,
                'task_description' => 'Describe your daily routine using reflexive verbs and time expressions.',
                'contrast_note' => 'Portuguese often drops the reflexive pronoun where Spanish keeps it (acordar vs. despertarse, tomar duche vs. ducharse), and when it is reflexive, the pronoun usually comes after the verb in the affirmative present tense — see the grammar point below.',
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'levantar-se', 'translation_en' => 'to get up', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => "Reflexive pronoun placement flips from Spanish: 'levanto-me' (pronoun after the verb) versus Spanish's 'me levanto' (pronoun first)."],
                    ['term' => 'acordar', 'translation_en' => 'to wake up', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => "Not reflexive in Portuguese, unlike Spanish's 'despertarse'."],
                    ['term' => 'tomar duche', 'translation_en' => 'to shower', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Portuguese uses a non-reflexive verb phrase here, unlike Spanish's reflexive 'ducharse'."],
                    ['term' => 'tomar o pequeno-almoço', 'translation_en' => 'to have breakfast', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "A verb+noun phrase where Spanish has a single verb, 'desayunar'."],
                    ['term' => 'trabalhar', 'translation_en' => 'to work', 'is_cognate' => true, 'part_of_speech' => 'verb'],
                    ['term' => 'deitar-se', 'translation_en' => 'to go to bed', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'cedo', 'translation_en' => 'early', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'tarde', 'translation_en' => 'late', 'is_cognate' => true, 'part_of_speech' => 'adverb'],
                    ['term' => 'todos os dias', 'translation_en' => 'every day', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'normalmente', 'translation_en' => 'normally', 'is_cognate' => true, 'part_of_speech' => 'adverb'],
                ],
                'grammar' => [
                    [
                        'title' => 'Reflexive verbs and pronoun position',
                        'explanation' => "In the affirmative present tense, Portuguese usually puts the reflexive pronoun after the verb and hyphenates it — 'levanto-me', 'deito-me' — the mirror image of Spanish's 'me levanto', 'me acuesto'. Portuguese also uses non-reflexive phrasing in several places Spanish uses a reflexive verb (acordar vs. despertarse, tomar duche vs. ducharse), so don't assume every Spanish reflexive verb has a reflexive Portuguese equivalent.",
                        'error_tag_category' => ErrorTagCategory::PortunolSlip,
                    ],
                ],
            ],
        ];
    }
}
