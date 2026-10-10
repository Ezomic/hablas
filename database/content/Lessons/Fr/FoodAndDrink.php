<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class FoodAndDrink implements UnitContent
{
    private const ET_NOTE = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et joins things.';

    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const ET_SONT_NOTE = 'Et (and) sounds like est (is), and sont (are) sounds like son (his). Here et joins things and sont is the verb.';

    private const A_NOTE = 'A (has) has no accent and à (to, for) has one, but they sound the same. Here a is part of il y a (there is).';

    private const A_ET_NOTE = 'A (has) sounds like à (to, for), and et (and) sounds like est (is, the verb). Here a is part of il y a (there is) and et joins things.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'food-and-drink';
    }

    public function words(): array
    {
        return [
            new WordData('le pain', cue: 'bread'),
            new WordData('le lait', cue: 'milk'),
            new WordData('l\'eau', cue: 'water', forms: ['d\'eau'], note: 'Eau is feminine, but la becomes l\' before a vowel: l\'eau. After a quantity it is d\'eau: une bouteille d\'eau.'),
            new WordData('le fruit', cue: 'fruit', forms: ['fruits'], note: 'Fruit in general is usually plural in French: des fruits, un kilo de fruits.'),
            new WordData('la viande', cue: 'meat'),
            new WordData('le poisson', cue: 'fish (as food)'),
            new WordData('le fromage', cue: 'cheese'),
            new WordData('le vin', cue: 'wine'),
            new WordData('le kilo', cue: 'kilo (kilogram)', forms: ['kilos']),
            new WordData('la bouteille', cue: 'bottle', forms: ['bouteilles']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Un kilo de fromage, s\'il vous plaît.', 'english' => 'A kilo of cheese, please.'],
            ['text' => 'J\'ai deux bouteilles d\'eau.', 'english' => 'I have two bottles of water.'],
        ];
    }

    public function exercises(): array
    {
        return [
            ...$this->sentences(),
            ...$this->task(),
            ...$this->checkA(),
            ...$this->checkB(),
        ];
    }

    public function reviews(): array
    {
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.kilo-fromage', 'J\'ai un kilo ___ fromage.', ['de', 'du'], 'de', Kit::form('un kilo de', true), 'After a quantity like un kilo, French puts de before the thing, with no article: un kilo de fromage. Du (de + le) does not fit here.', 'choose', 'I have a kilo of cheese.'),
            Kit::gap($stage, 'sentences.choose_gap.bouteille-eau', 'J\'ai une bouteille ___.', ['d\'eau', 'de l\'eau'], 'd\'eau', Kit::form('d\'eau', true), 'After a quantity there is no article, and de becomes d\' before a vowel: une bouteille d\'eau. De l\'eau is not correct here.', 'choose', 'I have a bottle of water.'),
            Kit::gap($stage, 'sentences.choose_gap.kilos-fruits', 'J\'ai deux kilos ___ fruits.', ['de', 'des'], 'de', Kit::form('deux kilos de', true), 'Deux kilos is an amount, so de comes before the fruit, with no article: deux kilos de fruits. Des would mean \'of the\', which is not what the sentence says.', 'choose', 'I have two kilos of fruit.'),
            Kit::gap($stage, 'sentences.choose_gap.lait-paul', '___ lait est pour Paul.', ['Le', 'La'], 'Le', Kit::word('le lait', 'lait'), 'Lait is masculine, so the article is le: le lait. La lait is not correct.', 'choose', 'The milk is for Paul.'),
            Kit::gap($stage, 'sentences.choose_gap.bouteille-lait', 'Une bouteille de ___, s\'il vous plaît.', ['lait', 'pain', 'poisson'], 'lait', Kit::word('le lait', 'lait'), 'Milk comes in a bottle. Bread and fish do not.', 'choose', 'A bottle of milk, please.'),
            Kit::gap($stage, 'sentences.choose_gap.kilo-viande', 'J\'ai un kilo de ___.', ['viande', 'bouteille', 'une'], 'viande', Kit::word('la viande', 'viande'), 'Viande is a thing you can weigh. Bouteille is a container, and une is an article.', 'choose', 'I have a kilo of meat.'),

            Kit::typeGap($stage, 'sentences.type_gap.bouteille-lait', 'J\'ai une bouteille ___ lait.', 'I have a bottle of milk.', 'de', Kit::form('de'), 'Between the amount and the thing comes de: une bouteille de lait.'),
            Kit::typeGap($stage, 'sentences.type_gap.marie-kilo', 'Marie a un ___ de fromage.', 'Marie has a kilo of cheese.', 'kilo', Kit::word('le kilo', 'kilo')),
            Kit::typeGap($stage, 'sentences.type_gap.paul-bouteille', 'Paul a une ___ de vin.', 'Paul has a bottle of wine.', 'bouteille', Kit::word('la bouteille', 'bouteille')),
            Kit::typeGap($stage, 'sentences.type_gap.kilo-fruits', 'Un kilo ___ fruits, s\'il vous plaît.', 'A kilo of fruit, please.', 'de', Kit::form('de'), 'Un kilo is an amount, so de comes next: un kilo de fruits.'),
            Kit::typeGap($stage, 'sentences.type_gap.kilos-pain', 'Deux ___ de pain, s\'il vous plaît.', 'Two kilos of bread, please.', 'kilos', Kit::form('kilos'), 'With deux, the amount word is plural: deux kilos de pain.'),

            Kit::translate($stage, 'sentences.translate.kilo-fromage', 'A kilo of cheese, please. (to a seller)', ['Un kilo de fromage, s\'il vous plaît.', 'S\'il vous plaît, un kilo de fromage.'], [Kit::word('le fromage', 'fromage'), Kit::form('un kilo de')]),
            Kit::translate($stage, 'sentences.translate.bouteilles-lait', 'Two bottles of milk, please. (to a seller)', ['Deux bouteilles de lait, s\'il vous plaît.', 'S\'il vous plaît, deux bouteilles de lait.'], [Kit::word('la bouteille', 'bouteilles'), Kit::word('le lait', 'lait'), Kit::form('deux bouteilles de')]),
            Kit::translate($stage, 'sentences.translate.avez-lait', 'Do you have milk? (formal you)', ['Vous avez du lait ?', 'Avez-vous du lait ?'], [Kit::word('le lait', 'lait')]),

            Kit::build($stage, 'sentences.build.paul-viande', 'Paul has a kilo of meat.', 'Paul a un kilo de viande.', ['bouteille'], [Kit::word('le kilo', 'kilo'), Kit::word('la viande', 'viande'), Kit::form('un kilo de')]),
            Kit::build($stage, 'sentences.build.pain-marie', 'The bread is for Marie.', 'Le pain est pour Marie.', ['la'], [Kit::word('le pain', 'pain')]),
            Kit::build($stage, 'sentences.build.bouteille-vin', 'A bottle of wine, please. (to a seller)', 'Une bouteille de vin, s\'il vous plaît.', ['kilo'], [Kit::word('la bouteille', 'bouteille'), Kit::word('le vin', 'vin'), Kit::form('une bouteille de')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.kilo-poisson', 'Un kilo de poisson, s\'il vous plaît.', ['A kilo of fish, please.', 'A kilo of cheese, please.', 'Two kilos of fish, please.', 'A bottle of milk, please.'], 'A kilo of fish, please.', [Kit::word('le poisson', 'poisson'), Kit::word('le kilo', 'kilo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.bouteille-eau', 'J\'ai une bouteille d\'eau.', ['I have a bottle of water.', 'I have a bottle of wine.', 'I have two bottles of water.', 'You have a bottle of water.'], 'I have a bottle of water.', [Kit::word('l\'eau', 'd\'eau'), Kit::word('la bouteille', 'bouteille')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.viande', 'Vous avez de la viande ?', ['Do you have meat?', 'Do you have fish?', 'Do I have meat?', 'Is there meat?'], 'Do you have meat?', [Kit::word('la viande', 'viande')]),
            Kit::listenType($stage, 'sentences.listen_type.kilos-fruits', 'Deux kilos de fruits, s\'il vous plaît.', 'Two kilos of fruit, please.', [Kit::word('le fruit', 'fruits'), Kit::word('le kilo', 'kilos')]),
            Kit::listenType($stage, 'sentences.listen_type.vin-luc', 'Le vin est pour Luc.', 'The wine is for Luc.', [Kit::word('le vin', 'vin')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.pain-fruits', 'J\'ai du pain et des fruits.', 'I have bread and fruit.', [Kit::word('le pain', 'pain'), Kit::word('le fruit', 'fruits')], homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.pas-de-lait', 'Il n\'y a pas de lait ici.', 'There is no milk here.', [Kit::word('le lait', 'lait'), Kit::form('il n\'y a pas de', true)], homophoneNote: self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.bouteille-eau', 'Une bouteille d\'eau, s\'il vous plaît.', 'A bottle of water, please.', [Kit::word('l\'eau', 'd\'eau'), Kit::word('la bouteille', 'bouteille')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.kilo-pain', 'J\'ai un kilo de pain.', 'I have a kilo of bread.', [Kit::word('le pain', 'pain'), Kit::word('le kilo', 'kilo'), Kit::form('j\'ai un kilo de')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bouteilles-vin', 'Deux bouteilles de vin.', 'Two bottles of wine.', [Kit::word('le vin', 'vin'), Kit::word('la bouteille', 'bouteilles')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.poisson-anne', 'Le poisson est pour Anne.', 'The fish is for Anne.', [Kit::word('le poisson', 'poisson')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.fromage-lait', 'Tu as du fromage ou du lait ?', 'Do you have cheese or milk?', [['j\'ai'], ['fromage', 'lait']], 'J\'ai du fromage.', [Kit::word('le fromage', 'fromage')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.as-lait', 'Tu as du lait ?', 'Do you have milk?', [['oui', 'non'], ['j\'ai', 'lait']], 'Oui, j\'ai du lait.', [Kit::word('le lait', 'lait')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.poisson', 'Il y a du poisson ?', 'Is there fish?', [['oui', 'non'], ['il', 'poisson']], 'Oui, il y a du poisson.', [Kit::word('le poisson', 'poisson')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.marche', 'Read the conversation at the market.', [
                Kit::line('Luc', 'Bonjour, Marie. Vous avez du fromage ?'),
                Kit::line('Marie', 'Oui, j\'ai du fromage, du poisson et du vin.'),
                Kit::line('Luc', 'Un kilo de fromage et une bouteille de vin, s\'il vous plaît.'),
                Kit::line('Marie', 'Voici. Merci.'),
                Kit::line('Luc', 'Merci. Au revoir.'),
            ], [
                Kit::question('What does Luc buy?', ['Cheese and wine', 'Fish and milk', 'Bread and fruit'], 'Cheese and wine'),
                Kit::question('How much cheese does Luc buy?', ['A kilo', 'Two kilos', 'A bottle'], 'A kilo'),
                Kit::question('Does Marie have fish?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
            ], [Kit::word('le fromage', 'fromage'), Kit::word('le poisson', 'poisson'), Kit::word('le kilo', 'kilo'), Kit::word('la bouteille', 'bouteille'), Kit::word('le vin', 'vin')], 'read'),
            Kit::gap($stage, 'task.choose_gap.luc-kilo', 'Luc a un kilo ___ poisson.', ['de', 'du'], 'de', Kit::form('un kilo de', true), 'Un kilo is an amount, so de comes before the fish, with no article: un kilo de poisson. Du (de + le) does not fit here.', 'read', 'Luc has a kilo of fish.'),
            Kit::gap($stage, 'task.choose_gap.bouteilles-eau', 'Deux bouteilles ___, s\'il vous plaît.', ['d\'eau', 'de pain', 'de viande'], 'd\'eau', Kit::word('l\'eau', 'd\'eau'), 'Water comes in a bottle. Bread and meat do not.', 'read', 'Two bottles of water, please.'),

            Kit::transform($stage, 'task.transform.deux-kilos', 'Say you have two.', 'J\'ai un kilo de fromage.', ['J\'ai deux kilos de fromage.'], [Kit::word('le kilo', 'kilos'), Kit::word('le fromage', 'fromage'), Kit::form('deux kilos de')]),
            Kit::transform($stage, 'task.transform.question', 'Now ask a seller (formal you).', 'J\'ai du pain et des fruits.', ['Vous avez du pain et des fruits ?', 'Avez-vous du pain et des fruits ?'], [Kit::word('le pain', 'pain'), Kit::word('le fruit', 'fruits')]),
            Kit::transform($stage, 'task.transform.deux-bouteilles', 'Say you have two.', 'J\'ai une bouteille de vin.', ['J\'ai deux bouteilles de vin.'], [Kit::word('la bouteille', 'bouteilles'), Kit::word('le vin', 'vin'), Kit::form('deux bouteilles de', true)]),
            Kit::writeGuided($stage, 'task.write_guided.poisson-lait', 'Ask for a kilo of fish and a bottle of milk, please.', ['un kilo', 'une bouteille', 'poisson', 'lait'], 'Un kilo de poisson et une bouteille de lait, s\'il vous plaît.', [
                ['forms' => ['kilo'], 'term' => 'le kilo'],
                ['forms' => ['poisson'], 'term' => 'le poisson'],
                ['forms' => ['bouteille'], 'term' => 'la bouteille'],
                ['forms' => ['lait'], 'term' => 'le lait'],
            ], [Kit::word('le kilo', 'kilo'), Kit::word('le poisson', 'poisson'), Kit::word('la bouteille', 'bouteille'), Kit::word('le lait', 'lait'), Kit::form('un kilo de')]),
            Kit::writeGuided($stage, 'task.write_guided.vin-fromage', 'Say that the wine is for Luc.', ['le vin', 'pour'], 'Le vin est pour Luc.', [
                ['forms' => ['vin'], 'term' => 'le vin'],
                ['forms' => ['pour'], 'term' => null],
            ], [Kit::word('le vin', 'vin')]),
            Kit::build($stage, 'task.build.bouteilles-eau', 'I have two bottles of water.', 'J\'ai deux bouteilles d\'eau.', ['une', 'le'], [Kit::word('la bouteille', 'bouteilles'), Kit::word('l\'eau', 'd\'eau'), Kit::form('d\'eau', true)], 'write'),
            Kit::build($stage, 'task.build.anne-fruits', 'Anne has a kilo of fruit.', 'Anne a un kilo de fruits.', ['j\'ai', 'bouteille'], [Kit::word('le kilo', 'kilo'), Kit::word('le fruit', 'fruits'), Kit::form('un kilo de', true)], 'write'),
            Kit::build($stage, 'task.build.pas-de-vin', 'There is no wine, but there is water.', 'Il n\'y a pas de vin, mais il y a de l\'eau.', ['du', 'avec'], [Kit::word('le vin', 'vin'), Kit::word('l\'eau'), Kit::form('il n\'y a pas de', true)], 'write'),
            Kit::translate($stage, 'task.translate.bouteille-kilo', 'I have a bottle of wine and a kilo of cheese.', ['J\'ai une bouteille de vin et un kilo de fromage.', 'J\'ai un kilo de fromage et une bouteille de vin.'], [Kit::word('la bouteille', 'bouteille'), Kit::word('le vin', 'vin'), Kit::word('le kilo', 'kilo'), Kit::word('le fromage', 'fromage'), Kit::form('une bouteille de', false, ['un kilo de'])], 'write'),
            Kit::translate($stage, 'task.translate.pas-de-pain', 'There is no cheese, but I have bread.', ['Il n\'y a pas de fromage, mais j\'ai du pain.'], [Kit::word('le fromage', 'fromage'), Kit::word('le pain', 'pain'), Kit::form('il n\'y a pas de', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.viande-poisson', [
                Kit::line('Anne', 'Bonjour, Paul. Vous avez de la viande ?'),
                Kit::line('Paul', 'Oui, j\'ai de la viande, du poisson et de l\'eau.'),
                Kit::line('Anne', 'Un kilo de poisson et deux bouteilles d\'eau, s\'il vous plaît.'),
                Kit::line('Paul', 'Voici.'),
                Kit::line('Anne', 'Merci.'),
            ], [
                Kit::question('What does Anne buy?', ['Fish and water', 'Meat and wine', 'Cheese and milk'], 'Fish and water'),
                Kit::question('How many bottles of water does Anne buy?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Does Paul have meat?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who sells the food?', ['Paul', 'Anne', 'Nobody'], 'Paul'),
                Kit::question('Who asks for a kilo?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la viande', 'viande'), Kit::word('le poisson', 'poisson'), Kit::word('le kilo', 'kilo'), Kit::word('la bouteille', 'bouteilles'), Kit::word('l\'eau')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.lait-pain', 'J\'ai une bouteille de lait et un kilo de pain.', 'I have a bottle of milk and a kilo of bread.', [Kit::word('le lait', 'lait'), Kit::word('la bouteille', 'bouteille'), Kit::word('le kilo', 'kilo'), Kit::word('le pain', 'pain'), Kit::form('une bouteille de')], 'listen', homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'task.listen_type.viande-poisson', 'Il n\'y a pas de viande, mais il y a du poisson.', 'There is no meat, but there is fish.', [Kit::word('la viande', 'viande'), Kit::word('le poisson', 'poisson')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.deux-bouteilles', 'Vous avez deux bouteilles de vin ?', 'Do you have two bottles of wine? (formal you)', [Kit::word('la bouteille', 'bouteilles'), Kit::word('le vin', 'vin'), Kit::form('deux bouteilles de')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.dans-la-bouteille', 'Il y a quoi dans la bouteille ?', 'What is in the bottle?', [['il'], ['l\'eau', 'd\'eau', 'lait', 'vin']], 'Il y a de l\'eau.', [Kit::word('l\'eau'), Kit::word('la bouteille', 'bouteille')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pain-fruits', 'Tu as du pain ou des fruits ?', 'Do you have bread or fruit?', [['j\'ai'], ['pain', 'fruit', 'fruits']], 'J\'ai du pain.', [Kit::word('le pain', 'pain'), Kit::word('le fruit', 'fruits')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pour-anne', 'Le fromage est pour Anne ou pour Paul ?', 'Is the cheese for Anne or for Paul?', [['pour'], ['anne', 'paul']], 'Le fromage est pour Anne.', [Kit::word('le fromage', 'fromage')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.viande-poisson', 'Tu as de la viande ou du poisson ?', 'Do you have meat or fish?', [['j\'ai'], ['viande', 'poisson']], 'J\'ai de la viande.', [Kit::word('la viande', 'viande'), Kit::word('le poisson', 'poisson')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.viande-vin', 'Un kilo de viande et deux bouteilles de vin.', 'A kilo of meat and two bottles of wine.', [Kit::word('la viande', 'viande'), Kit::word('le kilo', 'kilo'), Kit::word('la bouteille', 'bouteilles'), Kit::word('le vin', 'vin')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.lait-eau', 'Le lait et l\'eau sont pour Marie.', 'The milk and the water are for Marie.', [Kit::word('le lait', 'lait'), Kit::word('l\'eau')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.viande-lait', 'A kilo of meat and a bottle of milk.', ['Un kilo de viande et une bouteille de lait.', 'Une bouteille de lait et un kilo de viande.'], [Kit::word('le kilo', 'kilo'), Kit::word('la viande', 'viande'), Kit::word('la bouteille', 'bouteille'), Kit::word('le lait', 'lait'), Kit::form('un kilo de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.kilos-poisson', 'Two kilos of fish, please. (to a seller)', ['Deux kilos de poisson, s\'il vous plaît.', 'S\'il vous plaît, deux kilos de poisson.'], [Kit::word('le kilo', 'kilos'), Kit::word('le poisson', 'poisson'), Kit::form('deux kilos de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bouteilles-vin', 'Three bottles of wine, please. (to a seller)', ['Trois bouteilles de vin, s\'il vous plaît.', 'S\'il vous plaît, trois bouteilles de vin.'], [Kit::word('la bouteille', 'bouteilles'), Kit::word('le vin', 'vin'), Kit::form('trois bouteilles de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.eau-fromage', 'A bottle of water and a kilo of cheese.', ['Une bouteille d\'eau et un kilo de fromage.', 'Un kilo de fromage et une bouteille d\'eau.'], [Kit::word('la bouteille', 'bouteille'), Kit::word('l\'eau', 'd\'eau'), Kit::word('le kilo', 'kilo'), Kit::word('le fromage', 'fromage'), Kit::form('un kilo de')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.anne-pain', 'Anne a un kilo ___ pain.', 'Anne has a kilo of bread.', 'de', Kit::form('de', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marie-fruits', 'Marie a deux ___ de fruits.', 'Marie has two kilos of fruit.', 'kilos', Kit::form('kilos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.pain-fruits', 'J\'ai du pain et des fruits pour Anne.', 'I have bread and fruit for Anne.', [Kit::word('le pain', 'pain'), Kit::word('le fruit', 'fruits')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.vin-lait', 'Le vin et le lait sont pour Marie.', 'The wine and the milk are for Marie.', [Kit::word('le vin', 'vin'), Kit::word('le lait', 'lait')], 'dictation', $set, homophoneNote: self::ET_SONT_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.eau-fromage-viande', 'L\'eau, le fromage et la viande sont pour Anne.', 'The water, the cheese and the meat are for Anne.', [Kit::word('l\'eau'), Kit::word('le fromage', 'fromage'), Kit::word('la viande', 'viande')], 'dictation', $set, homophoneNote: self::ET_SONT_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.poisson', [
                Kit::line('Luc', 'Bonjour, Marie. Vous avez du poisson ?'),
                Kit::line('Marie', 'Oui, j\'ai du poisson et de la viande.'),
                Kit::line('Luc', 'Un kilo de viande, s\'il vous plaît.'),
                Kit::line('Marie', 'Voici. Merci.'),
            ], [
                Kit::question('What does Luc buy?', ['A kilo of meat', 'A kilo of fish', 'A bottle of wine'], 'A kilo of meat'),
                Kit::question('Does Marie have fish?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What else does Marie have?', ['Meat', 'Milk', 'Fruit'], 'Meat'),
            ], [
                Kit::question('Who sells the food?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('Who asks for a kilo?', ['Luc', 'Marie', 'Nobody'], 'Luc'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('le poisson', 'poisson'), Kit::word('la viande', 'viande'), Kit::word('le kilo', 'kilo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.fruits', 'Read the conversation.', [
                Kit::line('Anne', 'Bonjour, Paul. Il y a des fruits ?'),
                Kit::line('Paul', 'Non, il n\'y a pas de fruits. Il y a du pain, du fromage et du vin.'),
                Kit::line('Anne', 'Deux kilos de pain et une bouteille de vin.'),
                Kit::line('Paul', 'Voici.'),
            ], [
                Kit::question('Is there fruit?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What does Anne buy?', ['Bread and wine', 'Cheese and milk', 'Fruit and fish'], 'Bread and wine'),
            ], [Kit::word('le fruit', 'fruits'), Kit::word('le pain', 'pain'), Kit::word('le fromage', 'fromage'), Kit::word('le vin', 'vin')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vin-eau', 'Tu as du vin ou de l\'eau ?', 'Do you have wine or water?', [['j\'ai'], ['vin', 'l\'eau', 'd\'eau']], 'J\'ai de l\'eau.', [Kit::word('le vin', 'vin'), Kit::word('l\'eau')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.qui-fromage', 'Qui a du fromage ?', 'Who has cheese?', [['anne', 'paul', 'marie', 'luc', 'j\'ai', 'je', 'tu', 'il', 'elle', 'nous', 'vous', 'ils', 'elles', 'on']], 'Marie a du fromage.', [Kit::word('le fromage', 'fromage')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.kilo-poisson', 'Tu as un kilo de poisson ?', 'Do you have a kilo of fish?', [['oui', 'non'], ['j\'ai', 'poisson', 'kilo']], 'Oui, j\'ai un kilo de poisson.', [Kit::word('le poisson', 'poisson'), Kit::word('le kilo', 'kilo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.vin-fruits', 'A bottle of wine and a kilo of fruit.', ['Une bouteille de vin et un kilo de fruits.', 'Un kilo de fruits et une bouteille de vin.'], [Kit::word('la bouteille', 'bouteille'), Kit::word('le vin', 'vin'), Kit::word('le kilo', 'kilo'), Kit::word('le fruit', 'fruits'), Kit::form('une bouteille de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pain-lait', 'Two kilos of bread and a bottle of milk.', ['Deux kilos de pain et une bouteille de lait.', 'Une bouteille de lait et deux kilos de pain.'], [Kit::word('le kilo', 'kilos'), Kit::word('le pain', 'pain'), Kit::word('la bouteille', 'bouteille'), Kit::word('le lait', 'lait'), Kit::form('deux kilos de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.fromage-poisson', 'A kilo of cheese and a kilo of fish.', ['Un kilo de fromage et un kilo de poisson.', 'Un kilo de poisson et un kilo de fromage.'], [Kit::word('le kilo', 'kilo'), Kit::word('le fromage', 'fromage'), Kit::word('le poisson', 'poisson'), Kit::form('un kilo de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.bouteilles-eau', 'Two bottles of water and a kilo of meat.', ['Deux bouteilles d\'eau et un kilo de viande.', 'Un kilo de viande et deux bouteilles d\'eau.'], [Kit::word('la bouteille', 'bouteilles'), Kit::word('l\'eau', 'd\'eau'), Kit::word('le kilo', 'kilo'), Kit::word('la viande', 'viande'), Kit::form('un kilo de', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luc-fromage', 'Luc a un kilo ___ fromage.', 'Luc has a kilo of cheese.', 'de', Kit::form('de', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luc-poisson', 'Luc a deux ___ de poisson.', 'Luc has two kilos of fish.', 'kilos', Kit::form('kilos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vin-eau', 'Le vin et l\'eau sont pour Luc.', 'The wine and the water are for Luc.', [Kit::word('le vin', 'vin'), Kit::word('l\'eau')], 'dictation', $set, homophoneNote: self::ET_SONT_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.pain-fruits-fromage', 'Il y a du pain, des fruits et du fromage.', 'There is bread, fruit and cheese.', [Kit::word('le pain', 'pain'), Kit::word('le fruit', 'fruits'), Kit::word('le fromage', 'fromage')], 'dictation', $set, homophoneNote: self::A_ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.viande-poisson-lait', 'Il y a de la viande, du poisson, du lait.', 'There is meat, fish, milk.', [Kit::word('la viande', 'viande'), Kit::word('le poisson', 'poisson'), Kit::word('le lait', 'lait')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
