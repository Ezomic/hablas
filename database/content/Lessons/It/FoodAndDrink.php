<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

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
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'food-and-drink';
    }

    public function words(): array
    {
        return [
            new WordData('il pane', cue: 'bread'),
            new WordData('il latte', cue: 'milk'),
            new WordData("l'acqua", cue: 'water', accepted: ['acqua'], forms: ["d'acqua"], note: "Acqua starts with a vowel, so the article is l' and di becomes d': l'acqua, una bottiglia d'acqua."),
            new WordData('la frutta', cue: 'fruit'),
            new WordData('la carne', cue: 'meat'),
            new WordData('il pesce', cue: 'fish (as food)'),
            new WordData('il formaggio', cue: 'cheese'),
            new WordData('il vino', cue: 'wine'),
            new WordData('il chilo', cue: 'kilo (kilogram)', forms: ['chili']),
            new WordData('la bottiglia', cue: 'bottle', forms: ['bottiglie']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Un chilo di formaggio, per favore.', 'english' => 'A kilo of cheese, please.'],
            ['text' => "Ho due bottiglie d'acqua.", 'english' => 'I have two bottles of water.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $eAccent = 'È (is) and e (and) sound the same. The accent only shows in writing.';

        return [
            Kit::gap($stage, 'sentences.choose_gap.chilo-formaggio', 'Ho un chilo ___ formaggio.', ['di', 'il'], 'di', Kit::form('un chilo di', true), 'After a quantity like un chilo, Italian puts di before the thing: un chilo di formaggio. Il does not fit here.', 'choose', 'I have a kilo of cheese.'),
            Kit::gap($stage, 'sentences.choose_gap.bottiglia-vino', 'Ho una bottiglia ___ vino.', ['di', 'del'], 'di', Kit::form('una bottiglia di', true), "To say what is in the bottle, use di without an article: una bottiglia di vino. Una bottiglia del vino would mean 'a bottle of the (specific) wine'.", 'choose', 'I have a bottle of wine.'),
            Kit::gap($stage, 'sentences.choose_gap.chili-frutta', 'Ho due chili ___ frutta.', ['di', 'a'], 'di', Kit::form('due chili di', true), 'Due chili is an amount, so di comes before the fruit: due chili di frutta. A does not fit here.', 'choose', 'I have two kilos of fruit.'),
            Kit::gap($stage, 'sentences.choose_gap.bottiglia-acqua', 'Una bottiglia ___, per favore.', ["d'acqua", 'a acqua', "l'acqua"], "d'acqua", Kit::word("l'acqua", "d'acqua"), "Before a vowel di shortens to d' and joins the next word: una bottiglia d'acqua. L'acqua would mean 'the water', and a does not fit after bottiglia.", 'choose', 'A bottle of water, please.'),
            Kit::gap($stage, 'sentences.choose_gap.bottiglia-latte', 'Una bottiglia di ___, per favore.', ['latte', 'pane', 'pesce'], 'latte', Kit::word('il latte', 'latte'), 'Milk comes in a bottle. Bread and fish do not.', 'choose', 'A bottle of milk, please.'),
            Kit::gap($stage, 'sentences.choose_gap.chilo-carne', 'Ho un chilo di ___.', ['carne', 'bottiglia', 'una'], 'carne', Kit::word('la carne', 'carne'), 'Carne is a thing you can weigh. Bottiglia is a container, and una is an article.', 'choose', 'I have a kilo of meat.'),

            Kit::typeGap($stage, 'sentences.type_gap.bottiglia-latte', 'Ho una bottiglia ___ latte.', 'I have a bottle of milk.', 'di', Kit::form('di'), 'Between the amount and the thing comes di: una bottiglia di latte.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-chilo', 'Marta ha un ___ di formaggio.', 'Marta has a kilo of cheese.', 'chilo', Kit::word('il chilo', 'chilo')),
            Kit::typeGap($stage, 'sentences.type_gap.paolo-bottiglia', 'Paolo ha una ___ di vino.', 'Paolo has a bottle of wine.', 'bottiglia', Kit::word('la bottiglia', 'bottiglia')),
            Kit::typeGap($stage, 'sentences.type_gap.chilo-frutta', 'Un chilo ___ frutta, per favore.', 'A kilo of fruit, please.', 'di', Kit::form('di'), 'Un chilo is an amount, so di comes next: un chilo di frutta.'),
            Kit::typeGap($stage, 'sentences.type_gap.chili-pane', 'Due ___ di pane, per favore.', 'Two kilos of bread, please.', 'chili', Kit::form('chili'), 'With two, the amount word is plural and ends in i: due chili di pane.'),

            Kit::translate($stage, 'sentences.translate.chilo-formaggio', 'A kilo of cheese, please.', ['Un chilo di formaggio, per favore.', 'Per favore, un chilo di formaggio.'], [Kit::word('il formaggio', 'formaggio'), Kit::form('un chilo di')]),
            Kit::translate($stage, 'sentences.translate.bottiglie-latte', 'Two bottles of milk, please.', ['Due bottiglie di latte, per favore.', 'Per favore, due bottiglie di latte.'], [Kit::word('la bottiglia', 'bottiglie'), Kit::form('due bottiglie di')]),
            Kit::translate($stage, 'sentences.translate.ha-latte', 'Do you have milk? (formal you)', ['Ha il latte?', 'Ha del latte?', 'Ha latte?', 'Lei ha il latte?', 'Lei ha del latte?', 'Lei ha latte?'], [Kit::word('il latte', 'latte')]),

            Kit::build($stage, 'sentences.build.paolo-carne', 'Paolo has a kilo of meat.', 'Paolo ha un chilo di carne.', ['bottiglia'], [Kit::word('il chilo', 'chilo'), Kit::word('la carne', 'carne'), Kit::form('un chilo di')]),
            Kit::build($stage, 'sentences.build.pane-marta', 'The bread is for Marta.', 'Il pane è per Marta.', ['la'], [Kit::word('il pane', 'pane')]),
            Kit::build($stage, 'sentences.build.bottiglia-vino', 'A bottle of wine, please.', 'Una bottiglia di vino, per favore.', ['chilo'], [Kit::word('la bottiglia', 'bottiglia'), Kit::word('il vino', 'vino'), Kit::form('una bottiglia di')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.chilo-pesce', 'Un chilo di pesce, per favore.', ['A kilo of fish, please.', 'A kilo of cheese, please.', 'Two kilos of fish, please.', 'A bottle of milk, please.'], 'A kilo of fish, please.', [Kit::word('il pesce', 'pesce'), Kit::word('il chilo', 'chilo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.bottiglia-acqua', "Ho una bottiglia d'acqua.", ['I have a bottle of water.', 'I have a bottle of wine.', 'I have two bottles of water.', 'You have a bottle of water.'], 'I have a bottle of water.', [Kit::word("l'acqua", "d'acqua"), Kit::word('la bottiglia', 'bottiglia')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.carne-pesce', 'Ha la carne o il pesce?', ['Do you have meat or fish?', 'Do you have bread or fish?', 'Do I have meat or fish?', 'Is there meat or fish?'], 'Do you have meat or fish?', [Kit::word('la carne', 'carne'), Kit::word('il pesce', 'pesce')]),
            Kit::listenType($stage, 'sentences.listen_type.chili-frutta', 'Due chili di frutta, per favore.', 'Two kilos of fruit, please.', [Kit::word('la frutta', 'frutta'), Kit::word('il chilo', 'chili')]),
            Kit::listenType($stage, 'sentences.listen_type.vino-luca', 'Il vino è per Luca.', 'The wine is for Luca.', [Kit::word('il vino', 'vino')], homophoneNote: $eAccent),
            Kit::listenType($stage, 'sentences.listen_type.pane-formaggio-frutta', 'Ho pane, formaggio e frutta.', 'I have bread, cheese and fruit.', [Kit::word('il pane', 'pane'), Kit::word('il formaggio', 'formaggio'), Kit::word('la frutta', 'frutta')], homophoneNote: $eAccent),
            Kit::listenType($stage, 'sentences.listen_type.no-latte', "Qui non c'è latte.", 'There is no milk here.', [Kit::word('il latte', 'latte')], homophoneNote: "C'è (there is) is one word with an apostrophe and an accent: c'è."),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.bottiglia-acqua', "Una bottiglia d'acqua, per favore.", 'A bottle of water, please.', [Kit::word("l'acqua", "d'acqua"), Kit::word('la bottiglia', 'bottiglia')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.chilo-pane', 'Ho un chilo di pane.', 'I have a kilo of bread.', [Kit::word('il pane', 'pane'), Kit::word('il chilo', 'chilo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bottiglie-vino', 'Due bottiglie di vino.', 'Two bottles of wine.', [Kit::word('il vino', 'vino'), Kit::word('la bottiglia', 'bottiglie')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pesce-anna', 'Il pesce è per Anna.', 'The fish is for Anna.', [Kit::word('il pesce', 'pesce')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.hai-pane', 'Hai il pane?', 'Do you have the bread?', [['sì', 'no'], ['ho', 'pane']], 'Sì, ho il pane.', [Kit::word('il pane', 'pane')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.hai-latte', 'Hai il latte?', 'Do you have milk?', [['sì', 'no'], ['ho', 'latte']], 'Sì, ho il latte.', [Kit::word('il latte', 'latte')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pesce-formaggio', 'Hai il pesce o il formaggio?', 'Do you have fish or cheese?', [['ho'], ['pesce', 'formaggio']], 'Ho il pesce.', [Kit::word('il pesce', 'pesce'), Kit::word('il formaggio', 'formaggio')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $eAccent = 'È (is) and e (and) sound the same. The accent only shows in writing.';

        return [
            Kit::readPassage($stage, 'task.read_passage.mercato', 'Read the conversation at the market.', [
                Kit::line('Luca', 'Buongiorno, Marta. Ha il formaggio?'),
                Kit::line('Marta', 'Sì, ho formaggio, pesce e vino.'),
                Kit::line('Luca', 'Un chilo di formaggio e una bottiglia di vino, per favore.'),
                Kit::line('Marta', 'Ecco. Grazie.'),
                Kit::line('Luca', 'Grazie. Arrivederci.'),
            ], [
                Kit::question('What does Luca buy?', ['Cheese and wine', 'Fish and milk', 'Bread and fruit'], 'Cheese and wine'),
                Kit::question('How much cheese does Luca buy?', ['A kilo', 'Two kilos', 'A bottle'], 'A kilo'),
                Kit::question('Does Marta have fish?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
            ], [Kit::word('il formaggio', 'formaggio'), Kit::word('il pesce', 'pesce'), Kit::word('il chilo', 'chilo'), Kit::word('la bottiglia', 'bottiglia'), Kit::word('il vino', 'vino')], 'read'),
            Kit::gap($stage, 'task.choose_gap.luca-chilo', 'Luca ha un chilo ___ pesce.', ['di', 'il'], 'di', Kit::form('un chilo di', true), 'Un chilo is an amount, so di comes before the fish: un chilo di pesce. Il does not fit here.', 'read', 'Luca has a kilo of fish.'),
            Kit::gap($stage, 'task.choose_gap.bottiglie-acqua', 'Due bottiglie ___, per favore.', ["d'acqua", 'a acqua', "l'acqua"], "d'acqua", Kit::word("l'acqua", "d'acqua"), "Before a vowel di shortens to d' and joins the next word: due bottiglie d'acqua. L'acqua would mean 'the water', and a does not fit after bottiglia.", 'read', 'Two bottles of water, please.'),

            Kit::transform($stage, 'task.transform.due-chili', 'Now say you have two kilos.', 'Ho un chilo di formaggio.', ['Ho due chili di formaggio.', 'Io ho due chili di formaggio.'], [Kit::word('il chilo', 'chili'), Kit::word('il formaggio', 'formaggio'), Kit::form('due chili di')]),
            Kit::transform($stage, 'task.transform.domanda', 'Now ask a seller (formal you).', 'Ho il pane e la frutta.', ['Ha il pane e la frutta?', 'Lei ha il pane e la frutta?', 'Ha pane e frutta?', 'Lei ha pane e frutta?'], [Kit::word('il pane', 'pane'), Kit::word('la frutta', 'frutta')]),
            Kit::transform($stage, 'task.transform.due-bottiglie', 'Now say you have two bottles.', 'Ho una bottiglia di vino.', ['Ho due bottiglie di vino.', 'Io ho due bottiglie di vino.'], [Kit::word('la bottiglia', 'bottiglie'), Kit::word('il vino', 'vino'), Kit::form('due bottiglie di', true)]),
            Kit::writeGuided($stage, 'task.write_guided.pesce-latte', 'Ask for a kilo of fish and a bottle of milk, please.', ['un chilo', 'una bottiglia', 'pesce', 'latte'], 'Un chilo di pesce e una bottiglia di latte, per favore.', [
                ['forms' => ['chilo'], 'term' => 'il chilo'],
                ['forms' => ['pesce'], 'term' => 'il pesce'],
                ['forms' => ['bottiglia'], 'term' => 'la bottiglia'],
                ['forms' => ['latte'], 'term' => 'il latte'],
            ], [Kit::word('il chilo', 'chilo'), Kit::word('il pesce', 'pesce'), Kit::word('la bottiglia', 'bottiglia'), Kit::word('il latte', 'latte'), Kit::form('un chilo di')]),
            Kit::writeGuided($stage, 'task.write_guided.vino-formaggio', 'Say that the wine and the cheese are for Luca.', ['il vino', 'il formaggio', 'per'], 'Il vino e il formaggio sono per Luca.', [
                ['forms' => ['vino'], 'term' => 'il vino'],
                ['forms' => ['formaggio'], 'term' => 'il formaggio'],
                ['forms' => ['per'], 'term' => null],
            ], [Kit::word('il vino', 'vino'), Kit::word('il formaggio', 'formaggio')]),
            Kit::build($stage, 'task.build.bottiglie-latte', 'I have two bottles of milk.', 'Ho due bottiglie di latte.', ['una', 'il'], [Kit::word('la bottiglia', 'bottiglie'), Kit::word('il latte', 'latte'), Kit::form('due bottiglie di')], 'write'),
            Kit::build($stage, 'task.build.anna-frutta', 'Anna has a kilo of fruit.', 'Anna ha un chilo di frutta.', ['ho', 'bottiglia'], [Kit::word('il chilo', 'chilo'), Kit::word('la frutta', 'frutta'), Kit::form('un chilo di', true)], 'write'),
            Kit::build($stage, 'task.build.no-vino', 'There is no wine, but there is water.', "Non c'è vino, ma c'è acqua.", ['ho', 'con'], [Kit::word('il vino', 'vino'), Kit::word("l'acqua", 'acqua')], 'write'),
            Kit::translate($stage, 'task.translate.bottiglia-chilo', 'I have a bottle of wine and a kilo of cheese.', ['Ho una bottiglia di vino e un chilo di formaggio.', 'Ho un chilo di formaggio e una bottiglia di vino.', 'Io ho una bottiglia di vino e un chilo di formaggio.', 'Io ho un chilo di formaggio e una bottiglia di vino.'], [Kit::word('la bottiglia', 'bottiglia'), Kit::word('il vino', 'vino'), Kit::word('il chilo', 'chilo'), Kit::word('il formaggio', 'formaggio'), Kit::form('una bottiglia di')], 'write'),
            Kit::translate($stage, 'task.translate.no-pane', 'There is no bread, but I have fruit.', ["Non c'è pane, ma ho frutta.", "Non c'è il pane, ma ho la frutta.", "Non c'è pane, ma ho la frutta.", "Non c'è il pane, ma ho frutta.", "Non c'è pane, ma io ho frutta."], [Kit::word('il pane', 'pane'), Kit::word('la frutta', 'frutta')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.carne-pesce', [
                Kit::line('Anna', 'Ciao, Paolo. Hai la carne?'),
                Kit::line('Paolo', 'Sì, ho carne, pesce e acqua.'),
                Kit::line('Anna', "Un chilo di pesce e due bottiglie d'acqua, per favore."),
                Kit::line('Paolo', 'Ecco.'),
                Kit::line('Anna', 'Grazie.'),
            ], [
                Kit::question('What does Anna buy?', ['Fish and water', 'Meat and wine', 'Cheese and milk'], 'Fish and water'),
                Kit::question('How many bottles of water does Anna buy?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Does Paolo have meat?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who sells the food?', ['Paolo', 'Anna', 'Nobody'], 'Paolo'),
                Kit::question('Who asks for a kilo?', ['Anna', 'Paolo', 'Nobody'], 'Anna'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la carne', 'carne'), Kit::word('il pesce', 'pesce'), Kit::word('il chilo', 'chilo'), Kit::word('la bottiglia', 'bottiglie'), Kit::word("l'acqua", 'acqua')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.latte-pane', 'Ho una bottiglia di latte e un chilo di pane.', 'I have a bottle of milk and a kilo of bread.', [Kit::word('il latte', 'latte'), Kit::word('la bottiglia', 'bottiglia'), Kit::word('il chilo', 'chilo'), Kit::word('il pane', 'pane'), Kit::form('una bottiglia di')], 'listen', homophoneNote: 'Ho (I have) and o (or) sound the same. The h is silent and only shows in writing.'),
            Kit::listenType($stage, 'task.listen_type.carne-frutta', "Non c'è carne, ma ci sono pesce e frutta.", 'There is no meat, but there are fish and fruit.', [Kit::word('la carne', 'carne'), Kit::word('il pesce', 'pesce'), Kit::word('la frutta', 'frutta')], 'listen', homophoneNote: 'E (and) and è (is) sound the same. The accent only shows in writing.'),
            Kit::listenType($stage, 'task.listen_type.due-bottiglie', 'Ha due bottiglie di vino?', 'Do you have two bottles of wine? (formal you)', [Kit::word('la bottiglia', 'bottiglie'), Kit::word('il vino', 'vino'), Kit::form('due bottiglie di')], 'listen', homophoneNote: 'Ha (has) and a (to) sound the same. The h is silent and only shows in writing.'),

            Kit::speakAnswer($stage, 'task.speak_answer.acqua-latte', 'Hai acqua o latte?', 'Do you have water or milk?', [['ho'], ['acqua', 'latte']], 'Ho acqua.', [Kit::word("l'acqua", 'acqua'), Kit::word('il latte', 'latte')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pane-frutta', 'Hai pane o frutta?', 'Do you have bread or fruit?', [['ho'], ['pane', 'frutta']], 'Ho pane.', [Kit::word('il pane', 'pane'), Kit::word('la frutta', 'frutta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.per-anna', 'Il formaggio è per Anna?', 'Is the cheese for Anna?', [['sì', 'no'], ['formaggio', 'è', 'per']], 'Sì, il formaggio è per Anna.', [Kit::word('il formaggio', 'formaggio')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.carne-pesce', 'Hai la carne o il pesce?', 'Do you have meat or fish?', [['ho'], ['carne', 'pesce']], 'Ho la carne.', [Kit::word('la carne', 'carne'), Kit::word('il pesce', 'pesce')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.carne-vino', 'Un chilo di carne e due bottiglie di vino.', 'A kilo of meat and two bottles of wine.', [Kit::word('la carne', 'carne'), Kit::word('il chilo', 'chilo'), Kit::word('la bottiglia', 'bottiglie'), Kit::word('il vino', 'vino')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.latte-acqua', "Il latte e l'acqua sono per Marta.", 'The milk and the water are for Marta.', [Kit::word('il latte', 'latte'), Kit::word("l'acqua", "l'acqua")], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.carne-latte', 'A kilo of meat and a bottle of milk.', ['Un chilo di carne e una bottiglia di latte.', 'Una bottiglia di latte e un chilo di carne.'], [Kit::word('il chilo', 'chilo'), Kit::word('la carne', 'carne'), Kit::word('la bottiglia', 'bottiglia'), Kit::word('il latte', 'latte'), Kit::form('un chilo di')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.chili-pesce', 'Two kilos of fish, please.', ['Due chili di pesce, per favore.', 'Per favore, due chili di pesce.'], [Kit::word('il chilo', 'chili'), Kit::word('il pesce', 'pesce'), Kit::form('due chili di', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bottiglie-vino', 'Three bottles of wine, please.', ['Tre bottiglie di vino, per favore.', 'Per favore, tre bottiglie di vino.'], [Kit::word('la bottiglia', 'bottiglie'), Kit::word('il vino', 'vino'), Kit::form('tre bottiglie di', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.acqua-formaggio', 'A bottle of water and a kilo of cheese.', ["Una bottiglia d'acqua e un chilo di formaggio.", "Un chilo di formaggio e una bottiglia d'acqua."], [Kit::word('la bottiglia', 'bottiglia'), Kit::word("l'acqua", "d'acqua"), Kit::word('il chilo', 'chilo'), Kit::word('il formaggio', 'formaggio'), Kit::form("d'acqua")], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.anna-pane', 'Anna ha un chilo ___ pane.', 'Anna has a kilo of bread.', 'di', Kit::form('di', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-frutta', 'Marta ha due ___ di frutta.', 'Marta has two kilos of fruit.', 'chili', Kit::form('chili'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.pane-frutta', 'Ho pane e frutta per Anna.', 'I have bread and fruit for Anna.', [Kit::word('il pane', 'pane'), Kit::word('la frutta', 'frutta')], 'dictation', $set, homophoneNote: 'Ho (I have) and o (or) sound the same. The h is silent and only shows in writing.'),
            Kit::listenType($stage, 'check.a.listen_type.vino-latte', 'Il vino e il latte sono per Marta.', 'The wine and the milk are for Marta.', [Kit::word('il vino', 'vino'), Kit::word('il latte', 'latte')], 'dictation', $set, homophoneNote: 'E (and) and è (is) sound the same. The accent only shows in writing.'),
            Kit::listenType($stage, 'check.a.listen_type.acqua-formaggio-carne', "L'acqua, il formaggio e la carne sono per Anna.", 'The water, the cheese and the meat are for Anna.', [Kit::word("l'acqua", "l'acqua"), Kit::word('il formaggio', 'formaggio'), Kit::word('la carne', 'carne')], 'dictation', $set, homophoneNote: 'E (and) and è (is) sound the same. The accent only shows in writing.'),
            Kit::listenPassage($stage, 'check.a.listen_passage.pesce', [
                Kit::line('Luca', 'Ciao, Marta. Hai il pesce?'),
                Kit::line('Marta', 'Sì, ho pesce e carne.'),
                Kit::line('Luca', 'Un chilo di carne, per favore.'),
                Kit::line('Marta', 'Ecco. Grazie.'),
            ], [
                Kit::question('What does Luca buy?', ['A kilo of meat', 'A kilo of fish', 'A bottle of wine'], 'A kilo of meat'),
                Kit::question('Does Marta have fish?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What else does Marta have?', ['Meat', 'Milk', 'Fruit'], 'Meat'),
            ], [
                Kit::question('Who sells the food?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('Who asks for a kilo?', ['Luca', 'Marta', 'Nobody'], 'Luca'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('il pesce', 'pesce'), Kit::word('la carne', 'carne'), Kit::word('il chilo', 'chilo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.frutta', 'Read the conversation.', [
                Kit::line('Anna', 'Ciao, Paolo. Hai la frutta?'),
                Kit::line('Paolo', "No, non c'è frutta. Ci sono pane, formaggio e vino."),
                Kit::line('Anna', 'Due chili di pane e una bottiglia di vino.'),
                Kit::line('Paolo', 'Ecco.'),
            ], [
                Kit::question('Is there fruit?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What does Anna buy?', ['Bread and wine', 'Cheese and milk', 'Fruit and fish'], 'Bread and wine'),
            ], [Kit::word('la frutta', 'frutta'), Kit::word('il pane', 'pane'), Kit::word('il formaggio', 'formaggio'), Kit::word('il vino', 'vino')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vino-acqua', 'Hai vino o acqua?', 'Do you have wine or water?', [['ho'], ['vino', 'acqua']], 'Ho acqua.', [Kit::word('il vino', 'vino'), Kit::word("l'acqua", 'acqua')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.marta', 'Marta ha formaggio o pane?', 'Does Marta have cheese or bread?', [['ha'], ['formaggio', 'pane']], 'Marta ha formaggio.', [Kit::word('il formaggio', 'formaggio'), Kit::word('il pane', 'pane')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.chilo-pesce', 'Hai un chilo di pesce?', 'Do you have a kilo of fish?', [['sì', 'no'], ['ho', 'pesce', 'chilo']], 'Sì, ho un chilo di pesce.', [Kit::word('il pesce', 'pesce'), Kit::word('il chilo', 'chilo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.vino-frutta', 'A bottle of wine and a kilo of fruit.', ['Una bottiglia di vino e un chilo di frutta.', 'Un chilo di frutta e una bottiglia di vino.'], [Kit::word('la bottiglia', 'bottiglia'), Kit::word('il vino', 'vino'), Kit::word('il chilo', 'chilo'), Kit::word('la frutta', 'frutta'), Kit::form('una bottiglia di')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pane-latte', 'Two kilos of bread and a bottle of milk.', ['Due chili di pane e una bottiglia di latte.', 'Una bottiglia di latte e due chili di pane.'], [Kit::word('il chilo', 'chili'), Kit::word('il pane', 'pane'), Kit::word('la bottiglia', 'bottiglia'), Kit::word('il latte', 'latte'), Kit::form('due chili di', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.formaggio-pesce', 'A kilo of cheese and a kilo of fish.', ['Un chilo di formaggio e un chilo di pesce.', 'Un chilo di pesce e un chilo di formaggio.'], [Kit::word('il chilo', 'chilo'), Kit::word('il formaggio', 'formaggio'), Kit::word('il pesce', 'pesce'), Kit::form('un chilo di')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.bottiglie-acqua', 'Two bottles of water and a kilo of meat.', ["Due bottiglie d'acqua e un chilo di carne.", "Un chilo di carne e due bottiglie d'acqua."], [Kit::word('la bottiglia', 'bottiglie'), Kit::word("l'acqua", "d'acqua"), Kit::word('il chilo', 'chilo'), Kit::word('la carne', 'carne'), Kit::form("d'acqua", true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luca-formaggio', 'Luca ha un chilo ___ formaggio.', 'Luca has a kilo of cheese.', 'di', Kit::form('di', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luca-pesce', 'Luca ha due ___ di pesce.', 'Luca has two kilos of fish.', 'chili', Kit::form('chili'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vino-acqua', "Il vino e l'acqua sono per Luca.", 'The wine and the water are for Luca.', [Kit::word('il vino', 'vino'), Kit::word("l'acqua", "l'acqua")], 'dictation', $set, homophoneNote: 'E (and) and è (is) sound the same. The accent only shows in writing.'),
            Kit::listenType($stage, 'check.b.listen_type.pane-frutta-formaggio', 'Ci sono pane, frutta e formaggio.', 'There are bread, fruit and cheese.', [Kit::word('il pane', 'pane'), Kit::word('la frutta', 'frutta'), Kit::word('il formaggio', 'formaggio')], 'dictation', $set, homophoneNote: 'E (and) and è (is) sound the same. The accent only shows in writing.'),
            Kit::listenType($stage, 'check.b.listen_type.carne-pesce-latte', 'Ci sono carne, pesce e latte.', 'There are meat, fish and milk.', [Kit::word('la carne', 'carne'), Kit::word('il pesce', 'pesce'), Kit::word('il latte', 'latte')], 'dictation', $set, homophoneNote: 'E (and) and è (is) sound the same. The accent only shows in writing.'),
        ];
    }
}
