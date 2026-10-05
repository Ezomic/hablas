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
 * Seeds French A1 content: the same eight units as Spanish and Portuguese, topic
 * for topic. AI-drafted and gated: nothing is released to learners until the
 * independent review and the owner approval are recorded on the unit content.
 */
class FrenchA1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $french = Language::query()->where('code', 'fr')->firstOrFail();

        foreach ($this->units() as $sortOrder => $definition) {
            $unit = Unit::query()->updateOrCreate(
                ['language_id' => $french->id, 'slug' => $definition['slug']],
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
                    ['language_id' => $french->id, 'unit_id' => $unit->id, 'term' => $vocabulary['term']],
                    $vocabulary,
                );
            }

            foreach ($definition['grammar'] as $grammar) {
                GrammarPoint::query()->updateOrCreate(
                    ['language_id' => $french->id, 'unit_id' => $unit->id, 'title' => $grammar['title']],
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
                    ['term' => 'bonjour', 'translation_en' => 'hello / good day', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'bonsoir', 'translation_en' => 'good evening', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'salut', 'translation_en' => 'hi / bye (informal)', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                    ['term' => 'au revoir', 'translation_en' => 'goodbye', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'je m\'appelle', 'translation_en' => 'my name is', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'enchanté', 'translation_en' => 'nice to meet you', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'comment allez-vous ?', 'translation_en' => 'how are you? (formal)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'ça va ?', 'translation_en' => 'how are you? / how is it going?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'bien', 'translation_en' => 'well / fine', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'merci', 'translation_en' => 'thank you', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
                ],
                'grammar' => [
                    [
                        'title' => 'Subject pronouns and être for identity',
                        'explanation' => 'French keeps the subject pronoun in every sentence (je, tu, il, elle, nous, vous, ils, elles), unlike Spanish and Portuguese, where it is usually dropped: \'Je suis Anne\', never \'Suis Anne\'. Être (je suis, tu es, il/elle est, nous sommes, vous êtes, ils/elles sont) gives identity and origin. Unlike Spanish there is no second verb for \'to be\': être also does the work of estar, as in \'Je suis bien\'. Vous is both the formal \'you\' and the plural \'you\'; tu is for friends, family and children.',
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
                    ['term' => 'l\'aéroport', 'translation_en' => 'airport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'le vol', 'translation_en' => 'flight', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la valise', 'translation_en' => 'suitcase', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le passeport', 'translation_en' => 'passport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la porte', 'translation_en' => 'gate / door', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le départ', 'translation_en' => 'departure', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'l\'arrivée', 'translation_en' => 'arrival', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le billet', 'translation_en' => 'ticket', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'en retard', 'translation_en' => 'delayed / late', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'international', 'translation_en' => 'international', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Grammatical gender: le / la and l\'',
                        'explanation' => 'Every French noun is masculine (le) or feminine (la), and the gender has to be learned with the noun: le vol, la valise. Before a vowel or a silent h both articles shrink to l\' and the gender disappears from view: l\'aéroport is masculine, l\'arrivée is feminine. Always learn a noun with its article, and with l\' words note the gender separately. The plural of both is les.',
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
                    ['term' => 'l\'hôtel', 'translation_en' => 'hotel', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la chambre', 'translation_en' => 'room', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la réservation', 'translation_en' => 'reservation', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'la clé', 'translation_en' => 'key', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le réceptionniste', 'translation_en' => 'receptionist', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'disponible', 'translation_en' => 'available', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'la nuit', 'translation_en' => 'night', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la salle de bain', 'translation_en' => 'bathroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'compris', 'translation_en' => 'included', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'le petit-déjeuner', 'translation_en' => 'breakfast', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Avoir for what you have, être for what you are',
                        'explanation' => '\'I have a reservation\' is \'J\'ai une réservation\': avoir (j\'ai, tu as, il/elle a, nous avons, vous avez, ils/elles ont) is the verb for having and for the hotel phrases \'Vous avez une chambre ?\'. Être, not avoir, says who or how someone is. A French speaker also says \'Il y a\' where Spanish says \'hay\': \'Il y a un petit-déjeuner ?\' Do not use être for \'there is\'.',
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
                    ['term' => 'le restaurant', 'translation_en' => 'restaurant', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'le menu', 'translation_en' => 'menu / set menu', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'l\'addition', 'translation_en' => 'bill / check', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'je voudrais', 'translation_en' => 'I would like', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'à boire', 'translation_en' => 'to drink', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'à manger', 'translation_en' => 'to eat', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'le serveur', 'translation_en' => 'waiter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'délicieux', 'translation_en' => 'delicious', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                    ['term' => 'le pourboire', 'translation_en' => 'tip', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'végétarien', 'translation_en' => 'vegetarian', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Present tense of -er verbs',
                        'explanation' => 'Most French verbs end in -er and follow one pattern in the present: drop -er and add -e, -es, -e, -ons, -ez, -ent. \'Je mange, tu manges, il mange, nous mangeons, vous mangez, ils mangent.\' The endings -e, -es and -ent are silent, so je mange, tu manges and ils mangent sound the same. Only the subject pronoun tells you who it is, which is why French keeps it, unlike Spanish.',
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
                    ['term' => 'la rue', 'translation_en' => 'street', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le coin', 'translation_en' => 'corner', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'à droite', 'translation_en' => 'to the right', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'à gauche', 'translation_en' => 'to the left', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'tout droit', 'translation_en' => 'straight ahead', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'près', 'translation_en' => 'near', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'loin', 'translation_en' => 'far', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'le plan', 'translation_en' => 'map (of a town)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'où est… ?', 'translation_en' => 'where is…?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'la place', 'translation_en' => 'square / plaza', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Aller and à with the article: au, à la, à l\', aux',
                        'explanation' => '\'To go\' is aller (je vais, tu vas, il/elle va, nous allons, vous allez, ils/elles vont) and \'to\' or \'at\' is à. À merges with le and les: au (à + le), aux (à + les). With la and l\' it does not: à la gare, à l\'hôtel. \'Je vais au restaurant\' is correct, \'Je vais à le restaurant\' is never said.',
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
                    ['term' => 'les vêtements', 'translation_en' => 'clothes', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la chemise', 'translation_en' => 'shirt', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le pantalon', 'translation_en' => 'trousers', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le prix', 'translation_en' => 'price', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la taille', 'translation_en' => 'size', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la couleur', 'translation_en' => 'color', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'cher', 'translation_en' => 'expensive', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'bon marché', 'translation_en' => 'cheap', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'essayer', 'translation_en' => 'to try on', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'la réduction', 'translation_en' => 'discount', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                ],
                'grammar' => [
                    [
                        'title' => 'Adjective agreement (gender and number)',
                        'explanation' => 'French adjectives agree with the noun in gender and number, and the agreement is audible or visible: cher, chère, chers, chères. Most adjectives take -e for feminine and -s for plural: \'une chemise chère\', \'des pantalons chers\'. Some never change, such as \'bon marché\'. Adjectives usually come after the noun (une chemise bleue), but a few short common ones, such as petit, grand, bon, come before it.',
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
                    ['term' => 'la famille', 'translation_en' => 'family', 'is_cognate' => true, 'part_of_speech' => 'noun'],
                    ['term' => 'le père', 'translation_en' => 'father', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la mère', 'translation_en' => 'mother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le frère', 'translation_en' => 'brother', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'la sœur', 'translation_en' => 'sister', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'le fils', 'translation_en' => 'son', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'les grands-parents', 'translation_en' => 'grandparents', 'is_cognate' => false, 'part_of_speech' => 'noun'],
                    ['term' => 'marié', 'translation_en' => 'married', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'célibataire', 'translation_en' => 'single', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                    ['term' => 'aîné', 'translation_en' => 'older / eldest', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
                ],
                'grammar' => [
                    [
                        'title' => 'Possessive adjectives (mon, ma, mes, ton, son)',
                        'explanation' => 'Possessives agree with the thing owned, not with the owner: mon père, ma mère, mes parents. Son, sa and ses mean \'his\', \'her\' and \'its\' alike, so \'sa mère\' is his mother or her mother. Before a feminine noun that starts with a vowel the feminine ma, ta, sa becomes mon, ton, son: \'mon amie\' (my female friend), never \'ma amie\'. For you there are ton (informal) and votre (formal).',
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
                    ['term' => 'se lever', 'translation_en' => 'to get up', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'se réveiller', 'translation_en' => 'to wake up', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'se doucher', 'translation_en' => 'to shower', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'prendre le petit-déjeuner', 'translation_en' => 'to have breakfast', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'travailler', 'translation_en' => 'to work', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'se coucher', 'translation_en' => 'to go to bed', 'is_cognate' => false, 'part_of_speech' => 'verb'],
                    ['term' => 'tôt', 'translation_en' => 'early', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'tard', 'translation_en' => 'late', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
                    ['term' => 'tous les jours', 'translation_en' => 'every day', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
                    ['term' => 'normalement', 'translation_en' => 'normally', 'is_cognate' => true, 'part_of_speech' => 'adverb'],
                ],
                'grammar' => [
                    [
                        'title' => 'Reflexive verbs for daily routine',
                        'explanation' => 'French reflexive verbs carry a pronoun that agrees with the subject: je me lève, tu te lèves, il se lève, nous nous levons, vous vous levez, ils se lèvent. The infinitive keeps se: se lever, se coucher. Before a vowel me, te, se become m\', t\', s\': \'je m\'habille\'. \'Prendre le petit-déjeuner\' is not reflexive. Do not use déjeuner for breakfast: déjeuner is lunch.',
                        'error_tag_category' => null,
                    ],
                ],
            ],
        ];
    }
}
