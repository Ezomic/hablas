<?php

namespace Database\Seeders;

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\ErrorTagCategory;
use App\Enums\Skill;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Unit;
use App\Models\VocabularyItem;
use Illuminate\Database\Seeder;

/**
 * Seeds a representative slice of Spanish A1 content: travel and everyday-social
 * units first, per the Milestone 1 content-volume decision. AI-drafted; needs a
 * human review pass before being treated as authoritative teaching material,
 * per the content-sourcing pipeline in the Feature Brainstorm doc (category 6).
 */
class SpanishA1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->units() as $sortOrder => $definition) {
            $unit = Unit::query()->updateOrCreate(
                ['language_id' => $spanish->id, 'slug' => $definition['slug']],
                [
                    'title' => $definition['title'],
                    'cefr_level' => CefrLevel::A1,
                    'context_tag' => $definition['context_tag'],
                    'primary_skill' => $definition['primary_skill'],
                    'secondary_skill' => $definition['secondary_skill'],
                    'task_description' => $definition['task_description'],
                    'sort_order' => $sortOrder + 1,
                ],
            );

            foreach ($definition['vocabulary'] as $vocabulary) {
                VocabularyItem::query()->updateOrCreate(
                    ['language_id' => $spanish->id, 'unit_id' => $unit->id, 'term' => $vocabulary['term']],
                    $vocabulary,
                );
            }

            foreach ($definition['grammar'] as $grammar) {
                GrammarPoint::query()->updateOrCreate(
                    ['language_id' => $spanish->id, 'unit_id' => $unit->id, 'title' => $grammar['title']],
                    $grammar,
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
     *     vocabulary: array<int, array{term: string, translation_en: string, is_cognate: bool, part_of_speech: string}>,
     *     grammar: array<int, array{title: string, explanation: string, error_tag_category: ErrorTagCategory|null}>,
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
                'vocabulary' => [
                    ['term' => 'hola', 'translation_en' => 'hello', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'buenos días', 'translation_en' => 'good morning', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'buenas tardes', 'translation_en' => 'good afternoon', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'buenas noches', 'translation_en' => 'good evening / good night', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'adiós', 'translation_en' => 'goodbye', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'me llamo', 'translation_en' => 'my name is', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'mucho gusto', 'translation_en' => 'nice to meet you', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => '¿cómo estás?', 'translation_en' => 'how are you?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'bien', 'translation_en' => 'well / fine', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'gracias', 'translation_en' => 'thank you', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                ],
                'grammar' => [
                    [
                        'title' => 'Subject pronouns and ser for identity',
                        'explanation' => "Spanish has a separate word for 'to be' used for identity and origin: ser (soy, eres, es, somos, sois, son). Subject pronouns (yo, tú, él/ella, nosotros, vosotros, ellos/ellas) are usually dropped because the verb ending already shows who's speaking — 'Soy Ana' is far more natural than 'Yo soy Ana'. This is the first half of a two-verb distinction (ser vs. estar) that doesn't exist in Dutch or English, where 'to be' is a single verb.",
                        'error_tag_category' => ErrorTagCategory::SerEstarConfusion,
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
                'vocabulary' => [
                    ['term' => 'el aeropuerto', 'translation_en' => 'airport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'el vuelo', 'translation_en' => 'flight', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la maleta', 'translation_en' => 'suitcase', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el pasaporte', 'translation_en' => 'passport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la puerta', 'translation_en' => 'gate / door', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la salida', 'translation_en' => 'departure / exit', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la llegada', 'translation_en' => 'arrival', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el billete', 'translation_en' => 'ticket', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'retrasado', 'translation_en' => 'delayed', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'internacional', 'translation_en' => 'international', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Grammatical gender: el / la',
                        'explanation' => "Every Spanish noun is masculine or feminine — a category Dutch's de/het and English don't have in the same way. Most nouns ending in -o are masculine (el vuelo) and most ending in -a are feminine (la maleta), but there are common exceptions (el día, la mano) that just have to be memorized alongside the word itself. The article (el/la, un/una) always has to agree.",
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
                'vocabulary' => [
                    ['term' => 'el hotel', 'translation_en' => 'hotel', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la habitación', 'translation_en' => 'room', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la reserva', 'translation_en' => 'reservation', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la llave', 'translation_en' => 'key', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el recepcionista', 'translation_en' => 'receptionist', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'disponible', 'translation_en' => 'available', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'la noche', 'translation_en' => 'night', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el baño', 'translation_en' => 'bathroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'incluido', 'translation_en' => 'included', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'el desayuno', 'translation_en' => 'breakfast', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Estar for location and temporary states',
                        'explanation' => "This unit introduces the second 'to be' verb: estar (estoy, estás, está, estamos, estáis, están), used for location ('El hotel está cerca') and temporary states ('La habitación está lista'). Contrast with ser from the greetings unit, which covers identity and permanent characteristics. Mixing the two up is one of the most common mistakes for Dutch/English speakers, since neither language makes this split.",
                        'error_tag_category' => ErrorTagCategory::SerEstarConfusion,
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
                'vocabulary' => [
                    ['term' => 'el restaurante', 'translation_en' => 'restaurant', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'el menú', 'translation_en' => 'menu', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la cuenta', 'translation_en' => 'bill / check', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'quisiera', 'translation_en' => 'I would like', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'para beber', 'translation_en' => 'to drink', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'para comer', 'translation_en' => 'to eat', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'el camarero', 'translation_en' => 'waiter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'delicioso', 'translation_en' => 'delicious', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'la propina', 'translation_en' => 'tip', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'vegetariano', 'translation_en' => 'vegetarian', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Present tense of -ar verbs',
                        'explanation' => 'Regular -ar verbs like hablar (to speak) and tomar (to take/have) follow a single predictable pattern in the present tense: hablo, hablas, habla, hablamos, habláis, hablan. Once this pattern is automatic, dozens of common verbs (tomar, comprar, llegar, mirar) become usable immediately.',
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
                'vocabulary' => [
                    ['term' => 'la calle', 'translation_en' => 'street', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la esquina', 'translation_en' => 'corner', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a la derecha', 'translation_en' => 'to the right', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'a la izquierda', 'translation_en' => 'to the left', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'todo recto', 'translation_en' => 'straight ahead', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'cerca', 'translation_en' => 'near', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'lejos', 'translation_en' => 'far', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'el mapa', 'translation_en' => 'map', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => '¿dónde está...?', 'translation_en' => 'where is...?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'la plaza', 'translation_en' => 'square / plaza', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Present tense of -er and -ir verbs',
                        'explanation' => 'The other two regular verb families: -er verbs like comer (to eat) conjugate como, comes, come, comemos, coméis, comen; -ir verbs like vivir (to live) conjugate vivo, vives, vive, vivimos, vivís, viven. Together with -ar verbs from the restaurant unit, this covers the three regular present-tense patterns that most Spanish verbs follow.',
                        'error_tag_category' => null,
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
                'vocabulary' => [
                    ['term' => 'la ropa', 'translation_en' => 'clothing', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la camisa', 'translation_en' => 'shirt', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'los pantalones', 'translation_en' => 'pants', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el precio', 'translation_en' => 'price', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la talla', 'translation_en' => 'size', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el color', 'translation_en' => 'color', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'caro', 'translation_en' => 'expensive', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'barato', 'translation_en' => 'cheap', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'probarse', 'translation_en' => 'to try on', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'el descuento', 'translation_en' => 'discount', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Adjective agreement (gender and number)',
                        'explanation' => "Adjectives must match the noun they describe in both gender and number: 'la camisa roja' (red shirt, feminine) but 'el pantalón rojo' (red pants, masculine), 'las camisas rojas' in the plural. Neither Dutch nor English inflects adjectives this way, so it takes deliberate practice to stop leaving adjectives in a default form.",
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
                'vocabulary' => [
                    ['term' => 'la familia', 'translation_en' => 'family', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'el padre', 'translation_en' => 'father', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la madre', 'translation_en' => 'mother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el hermano', 'translation_en' => 'brother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la hermana', 'translation_en' => 'sister', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'el hijo', 'translation_en' => 'son', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'los abuelos', 'translation_en' => 'grandparents', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'casado', 'translation_en' => 'married', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'soltero', 'translation_en' => 'single', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'mayor', 'translation_en' => 'older', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Possessive adjectives (mi, tu, su)',
                        'explanation' => "Possessives like mi (my), tu (your), su (his/her/their) agree in number with the thing owned, not with the owner: 'mi hermano' but 'mis hermanos'. This is a different agreement rule than Dutch/English possessives, which don't change form based on how many things are owned.",
                        'error_tag_category' => null,
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
                'vocabulary' => [
                    ['term' => 'levantarse', 'translation_en' => 'to get up', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'despertarse', 'translation_en' => 'to wake up', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'ducharse', 'translation_en' => 'to shower', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'desayunar', 'translation_en' => 'to have breakfast', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'trabajar', 'translation_en' => 'to work', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'acostarse', 'translation_en' => 'to go to bed', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'temprano', 'translation_en' => 'early', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'tarde', 'translation_en' => 'late', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'todos los días', 'translation_en' => 'every day', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'normalmente', 'translation_en' => 'normally', 'is_cognate' => true, 'part_of_speech' => 'adverb'],
                ],
                'grammar' => [
                    [
                        'title' => 'Reflexive verbs for daily routine',
                        'explanation' => "Verbs like levantarse (to get (oneself) up) carry a reflexive pronoun (me, te, se, nos, os, se) that agrees with the subject: 'me levanto', 'te levantas', 'se levanta'. This pattern doesn't map cleanly onto English or Dutch phrasing and is one of the more mechanically new structures at A1, even though it isn't a Spanish/Portuguese interference risk specifically.",
                        'error_tag_category' => null,
                    ],
                ],
            ],
        ];
    }
}
