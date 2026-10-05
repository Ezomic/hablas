<?php

declare(strict_types=1);

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
 * Seeds Italian A1 content: the same eight units as Spanish and Portuguese, topic
 * for topic. AI-drafted and gated: nothing is released to learners until the
 * independent review and the owner approval are recorded on the unit content.
 */
class ItalianA1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $italian = Language::query()->where('code', 'it')->firstOrFail();

        foreach ($this->units() as $sortOrder => $definition) {
            $unit = Unit::query()->updateOrCreate(
                ['language_id' => $italian->id, 'slug' => $definition['slug']],
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
                    ['language_id' => $italian->id, 'unit_id' => $unit->id, 'term' => $vocabulary['term']],
                    $vocabulary,
                );
            }

            foreach ($definition['grammar'] as $grammar) {
                GrammarPoint::query()->updateOrCreate(
                    ['language_id' => $italian->id, 'unit_id' => $unit->id, 'title' => $grammar['title']],
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
     *     vocabulary: array<int, array{term: string, translation_en: string, is_cognate: bool, part_of_speech: string}>,
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
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'ciao', 'translation_en' => 'hi / bye (informal)', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'buongiorno', 'translation_en' => 'good morning / good day', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'buonasera', 'translation_en' => 'good evening', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'buonanotte', 'translation_en' => 'good night', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'arrivederci', 'translation_en' => 'goodbye', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'mi chiamo', 'translation_en' => 'my name is', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'piacere', 'translation_en' => 'nice to meet you', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'come stai?', 'translation_en' => 'how are you? (informal)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'bene', 'translation_en' => 'well / fine', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'grazie', 'translation_en' => 'thank you', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                ],
                'grammar' => [
                    [
                        'title' => 'Subject pronouns and essere for identity',
                        'explanation' => 'Italian has a verb for \'to be\', essere (sono, sei, è, siamo, siete, sono), used for identity and origin. Italian usually drops the subject pronoun because the verb ending shows who is speaking: \'Sono Anna\' is the normal way to say it, and \'Io sono Anna\' is correct but stresses the I. Note that sono is both \'I am\' and \'they are\', and that è (he/she/it is) has an accent which makes it different from e (and). Lei (capital L) is the formal \'you\', with the he/she form of the verb: \'Come sta?\'. How you are, your health, takes stare, not essere: \'Sto bene\'.',
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
                'interest_tags' => [InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'l\'aeroporto', 'translation_en' => 'airport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'il volo', 'translation_en' => 'flight', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la valigia', 'translation_en' => 'suitcase', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il passaporto', 'translation_en' => 'passport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'l\'uscita', 'translation_en' => 'gate / exit', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la partenza', 'translation_en' => 'departure', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'l\'arrivo', 'translation_en' => 'arrival', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il biglietto', 'translation_en' => 'ticket', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'in ritardo', 'translation_en' => 'delayed / late', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'internazionale', 'translation_en' => 'international', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Grammatical gender and the articles: il, lo, la, l\'',
                        'explanation' => 'Every Italian noun is masculine or feminine, and most end in -o (masculine) or -a (feminine): il volo, la valigia. Nouns in -e can be either, so learn them with the article. The masculine article is il (il volo), but lo before z, s+consonant, gn, ps (lo zaino, lo studente), and l\' before a vowel (l\'aeroporto, l\'arrivo). The feminine is la, and l\' before a vowel (l\'amica). Plurals: il becomes i, lo and l\' become gli, la becomes le: i voli, gli aeroporti, le valigie. A few -o nouns are feminine (la mano) and a few -a nouns masculine (il problema).',
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
                'interest_tags' => [InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'l\'albergo', 'translation_en' => 'hotel', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la camera', 'translation_en' => 'room', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la prenotazione', 'translation_en' => 'reservation', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la chiave', 'translation_en' => 'key', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il receptionist', 'translation_en' => 'receptionist', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'disponibile', 'translation_en' => 'available', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'la notte', 'translation_en' => 'night', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il bagno', 'translation_en' => 'bathroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'incluso', 'translation_en' => 'included', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'la colazione', 'translation_en' => 'breakfast', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Avere for what you have, and c\'è for there is',
                        'explanation' => '\'I have a reservation\' is \'Ho una prenotazione\': avere (ho, hai, ha, abbiamo, avete, hanno) is the verb for having. In writing the h is silent and only keeps ho, hai, ha and hanno apart from o (or), ai (to the), a (to) and anno (year); ha (has) and a (to) sound the same. To ask whether something is there, Italian says c\'è (there is) or ci sono (there are): \'C\'è una camera disponibile?\' Be careful with colazione: it is breakfast, while pranzo is lunch and cena is dinner.',
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
                'interest_tags' => [InterestTag::Food, InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'il ristorante', 'translation_en' => 'restaurant', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'il menù', 'translation_en' => 'menu', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'il conto', 'translation_en' => 'bill / check', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'vorrei', 'translation_en' => 'I would like', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'da bere', 'translation_en' => 'something to drink', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'da mangiare', 'translation_en' => 'something to eat', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'il cameriere', 'translation_en' => 'waiter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'delizioso', 'translation_en' => 'delicious', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'la mancia', 'translation_en' => 'tip', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'vegetariano', 'translation_en' => 'vegetarian', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Present tense of -are verbs',
                        'explanation' => 'Most Italian verbs end in -are and follow one pattern in the present: drop -are and add -o, -i, -a, -iamo, -ate, -ano. \'Parlo, parli, parla, parliamo, parlate, parlano.\' The stress falls on the syllable before the ending in parlo, parli, parla and parlano, and on the ending in parliamo and parlate. Italian usually drops the subject pronoun, because the ending shows who acts. In a restaurant the polite request is \'Vorrei un caffè\', not \'Voglio un caffè\', which sounds like a demand.',
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
                'interest_tags' => [InterestTag::Travel],
                'vocabulary' => [
                    ['term' => 'la strada', 'translation_en' => 'street', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'l\'angolo', 'translation_en' => 'corner', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'a destra', 'translation_en' => 'to the right', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'a sinistra', 'translation_en' => 'to the left', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'sempre dritto', 'translation_en' => 'straight ahead', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'vicino', 'translation_en' => 'near', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'lontano', 'translation_en' => 'far', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'la cartina', 'translation_en' => 'map (of a town)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'dov\'è… ?', 'translation_en' => 'where is…?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'la piazza', 'translation_en' => 'square / plaza', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Present tense of -ere and -ire verbs',
                        'explanation' => 'Verbs in -ere and -ire share most endings: -o, -i, -e, -iamo, -ete (-ere) or -ite (-ire), -ono. \'Prendo, prendi, prende, prendiamo, prendete, prendono.\' \'Parto, parti, parte, partiamo, partite, partono.\' Some -ire verbs, such as finire, capire and preferire, add -isc- in the singular and in the third person plural: finisco, finisci, finisce, finiscono, but finiamo, finite. Others, such as partire, do not. Asking the way: \'Scusi, dov\'è la stazione?\' (Scusi is the formal \'excuse me\').',
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
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'i vestiti', 'translation_en' => 'clothes', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la camicia', 'translation_en' => 'shirt', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'i pantaloni', 'translation_en' => 'trousers', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il prezzo', 'translation_en' => 'price', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la taglia', 'translation_en' => 'size', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il colore', 'translation_en' => 'color', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'caro', 'translation_en' => 'expensive', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'economico', 'translation_en' => 'cheap', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'provare', 'translation_en' => 'to try on', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'lo sconto', 'translation_en' => 'discount', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Adjective agreement (gender and number)',
                        'explanation' => 'Italian adjectives agree with the noun in gender and number. Adjectives in -o have four forms: caro, cara, cari, care. Adjectives in -e have two: \'grande\' is the same for masculine and feminine, with plural \'grandi\'. \'Una camicia cara\', \'dei pantaloni cari\'. Most adjectives follow the noun (una camicia blu), a few short common ones (bello, buono, grande, piccolo) can come before it. Colors such as blu, rosa and viola never change.',
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
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'la famiglia', 'translation_en' => 'family', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'il padre', 'translation_en' => 'father', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la madre', 'translation_en' => 'mother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il fratello', 'translation_en' => 'brother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la sorella', 'translation_en' => 'sister', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'il figlio', 'translation_en' => 'son', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'i nonni', 'translation_en' => 'grandparents', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'sposato', 'translation_en' => 'married', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'single', 'translation_en' => 'single', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'maggiore', 'translation_en' => 'older / elder', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Possessive adjectives (mio, tuo, suo)',
                        'explanation' => 'Possessives agree with the thing owned, not with the owner: mio, mia, miei, mie. They normally take the article: \'il mio libro\', \'la mia casa\'. The exception is a singular family member with no other word: \'mio padre\', \'mia sorella\', but \'i miei fratelli\' (plural) and \'la mia famiglia\' (a collective noun). Loro always keeps the article (\'il loro padre\'), and so do the affectionate \'la mia mamma\' and \'il mio papà\'. Suo means his, her or its alike, so \'suo padre\' is his father or her father. For you there are tuo (informal) and Suo (formal, with a capital).',
                        'error_tag_category' => ErrorTagCategory::WrongGender,
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
                'interest_tags' => [],
                'vocabulary' => [
                    ['term' => 'alzarsi', 'translation_en' => 'to get up', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'svegliarsi', 'translation_en' => 'to wake up', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'farsi la doccia', 'translation_en' => 'to shower', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'fare colazione', 'translation_en' => 'to have breakfast', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'lavorare', 'translation_en' => 'to work', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'andare a letto', 'translation_en' => 'to go to bed', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'presto', 'translation_en' => 'early', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'tardi', 'translation_en' => 'late', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'ogni giorno', 'translation_en' => 'every day', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'di solito', 'translation_en' => 'normally', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                ],
                'grammar' => [
                    [
                        'title' => 'Reflexive verbs for daily routine',
                        'explanation' => 'Italian reflexive verbs carry a pronoun that agrees with the subject: mi alzo, ti alzi, si alza, ci alziamo, vi alzate, si alzano. The infinitive ends in -si: alzarsi, svegliarsi. Not every daily routine verb is reflexive: \'fare colazione\' and \'lavorare\' are not. A time is introduced by \'alle\' for every hour but one: \'Mi alzo alle sette\' (at seven), but \'all\'una\' (at one). Colazione is breakfast, pranzo is lunch, cena is dinner.',
                        'error_tag_category' => null,
                    ],
                ],
            ],
        ];
    }
}
