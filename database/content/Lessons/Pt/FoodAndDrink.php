<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

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
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'food-and-drink';
    }

    public function words(): array
    {
        return [
            new WordData('o pão', cue: 'bread', portunolSlips: ['el pan']),
            new WordData('o leite', cue: 'milk', portunolSlips: ['la leche']),
            new WordData('a água', cue: 'water', portunolSlips: ['el agua'], note: 'Água is feminine and takes the normal article: a água, uma garrafa de água. Spanish needs el agua, but Portuguese does not.'),
            new WordData('a fruta', cue: 'fruit'),
            new WordData('a carne', cue: 'meat'),
            new WordData('o peixe', cue: 'fish (as food)', portunolSlips: ['el pescado']),
            new WordData('o queijo', cue: 'cheese', portunolSlips: ['el queso']),
            new WordData('o vinho', cue: 'wine', portunolSlips: ['el vino']),
            new WordData('o quilo', cue: 'kilo (kilogram)', forms: ['quilos'], portunolSlips: ['el kilo']),
            new WordData('a garrafa', cue: 'bottle', forms: ['garrafas'], portunolSlips: ['la botella']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Um quilo de queijo, por favor.', 'english' => 'A kilo of cheese, please.'],
            ['text' => 'Tenho duas garrafas de água.', 'english' => 'I have two bottles of water.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural European Portuguese (Portugal). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural European Portuguese (Portugal), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $ha = 'Há (there is) sounds like the article a, but the word you write is há, with an accent.';

        return [
            Kit::gap($stage, 'sentences.choose_gap.quilo-queijo', 'Tenho um quilo ___ queijo.', ['de', 'o'], 'de', Kit::form('um quilo de', true), 'After a quantity like um quilo, Portuguese puts de before the thing: um quilo de queijo. O does not fit here.', 'choose', 'I have a kilo of cheese.'),
            Kit::gap($stage, 'sentences.choose_gap.garrafa-agua', 'Tenho uma garrafa ___ água.', ['de', 'em'], 'de', Kit::form('uma garrafa de', true), 'After uma garrafa, Portuguese uses de: uma garrafa de água.', 'choose', 'I have a bottle of water.'),
            Kit::gap($stage, 'sentences.choose_gap.quilos-fruta', 'Tenho dois quilos ___ fruta.', ['de', 'a'], 'de', Kit::form('dois quilos de', true), 'Dois quilos is an amount, so de comes before the fruit: dois quilos de fruta. A does not fit here.', 'choose', 'I have two kilos of fruit.'),
            Kit::gap($stage, 'sentences.choose_gap.agua-joao', '___ água é para o João.', ['A', 'O'], 'A', Kit::word('a água', 'água'), 'Água is feminine and takes a: a água. Unlike Spanish el agua, Portuguese keeps the normal article. O água is not correct.', 'choose', 'The water is for João.'),
            Kit::gap($stage, 'sentences.choose_gap.garrafa-leite', 'Uma garrafa de ___, por favor.', ['leite', 'pão', 'peixe'], 'leite', Kit::word('o leite', 'leite'), 'Milk comes in a bottle. Bread and fish do not.', 'choose', 'A bottle of milk, please.'),
            Kit::gap($stage, 'sentences.choose_gap.quilo-carne', 'Tenho um quilo de ___.', ['carne', 'garrafa', 'uma'], 'carne', Kit::word('a carne', 'carne'), 'Carne is a thing you can weigh. Garrafa is a container, and uma is an article.', 'choose', 'I have a kilo of meat.'),

            Kit::typeGap($stage, 'sentences.type_gap.garrafa-leite', 'Tenho uma garrafa ___ leite.', 'I have a bottle of milk.', 'de', Kit::form('de'), 'Between the amount and the thing comes de: uma garrafa de leite.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-quilo', 'A Marta tem um ___ de queijo.', 'Marta has a kilo of cheese.', 'quilo', Kit::word('o quilo', 'quilo')),
            Kit::typeGap($stage, 'sentences.type_gap.joao-garrafa', 'O João tem uma ___ de vinho.', 'João has a bottle of wine.', 'garrafa', Kit::word('a garrafa', 'garrafa')),
            Kit::typeGap($stage, 'sentences.type_gap.quilo-fruta', 'Um quilo ___ fruta, por favor.', 'A kilo of fruit, please.', 'de', Kit::form('de'), 'Um quilo is an amount, so de comes next: um quilo de fruta.'),
            Kit::typeGap($stage, 'sentences.type_gap.quilos-pao', 'Dois ___ de pão, por favor.', 'Two kilos of bread, please.', 'quilos', Kit::form('quilos'), 'With two, the amount word is plural: dois quilos de pão.'),

            Kit::translate($stage, 'sentences.translate.quilo-queijo', 'A kilo of cheese, please.', ['Um quilo de queijo, por favor.', 'Por favor, um quilo de queijo.'], [Kit::word('o queijo', 'queijo'), Kit::form('um quilo de')]),
            Kit::translate($stage, 'sentences.translate.garrafas-leite', 'Two bottles of milk, please.', ['Duas garrafas de leite, por favor.', 'Por favor, duas garrafas de leite.'], [Kit::word('a garrafa', 'garrafas'), Kit::form('duas garrafas de')]),
            Kit::translate($stage, 'sentences.translate.tem-leite', 'Do you have milk? (formal you)', ['Tem leite?', 'O senhor tem leite?', 'A senhora tem leite?'], [Kit::word('o leite', 'leite')]),

            Kit::build($stage, 'sentences.build.joao-carne', 'João has a kilo of meat.', 'O João tem um quilo de carne.', ['garrafa'], [Kit::word('o quilo', 'quilo'), Kit::word('a carne', 'carne'), Kit::form('um quilo de')]),
            Kit::build($stage, 'sentences.build.pao-marta', 'The bread is for Marta.', 'O pão é para a Marta.', ['uma'], [Kit::word('o pão', 'pão')]),
            Kit::build($stage, 'sentences.build.garrafa-vinho', 'A bottle of wine, please.', 'Uma garrafa de vinho, por favor.', ['quilo'], [Kit::word('a garrafa', 'garrafa'), Kit::word('o vinho', 'vinho'), Kit::form('uma garrafa de')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.quilo-peixe', 'Um quilo de peixe, por favor.', ['A kilo of fish, please.', 'A kilo of cheese, please.', 'Two kilos of fish, please.', 'A bottle of milk, please.'], 'A kilo of fish, please.', [Kit::word('o peixe', 'peixe'), Kit::word('o quilo', 'quilo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.garrafa-agua', 'Tenho uma garrafa de água.', ['I have a bottle of water.', 'I have a bottle of wine.', 'I have two bottles of water.', 'You have a bottle of water.'], 'I have a bottle of water.', [Kit::word('a água', 'água'), Kit::word('a garrafa', 'garrafa')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.carne-peixe', 'Tens carne ou peixe?', ['Do you have meat or fish?', 'Do you have bread or fish?', 'Do I have meat or fish?', 'Does he have meat or fish?'], 'Do you have meat or fish?', [Kit::word('a carne', 'carne'), Kit::word('o peixe', 'peixe')]),
            Kit::listenType($stage, 'sentences.listen_type.quilos-fruta', 'Dois quilos de fruta, por favor.', 'Two kilos of fruit, please.', [Kit::word('a fruta', 'fruta'), Kit::word('o quilo', 'quilos')]),
            Kit::listenType($stage, 'sentences.listen_type.vinho-rui', 'O vinho é para o Rui.', 'The wine is for Rui.', [Kit::word('o vinho', 'vinho')]),
            Kit::listenType($stage, 'sentences.listen_type.pao-queijo-fruta', 'Tenho pão, queijo e fruta.', 'I have bread, cheese and fruit.', [Kit::word('o pão', 'pão'), Kit::word('o queijo', 'queijo'), Kit::word('a fruta', 'fruta')]),
            Kit::listenType($stage, 'sentences.listen_type.sem-leite', 'Não há leite aqui.', 'There is no milk here.', [Kit::word('o leite', 'leite')], 'listen', null, [], $ha),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.garrafa-agua', 'Uma garrafa de água, por favor.', 'A bottle of water, please.', [Kit::word('a água', 'água'), Kit::word('a garrafa', 'garrafa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.quilo-pao', 'Tenho um quilo de pão.', 'I have a kilo of bread.', [Kit::word('o pão', 'pão'), Kit::word('o quilo', 'quilo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.garrafas-vinho', 'Duas garrafas de vinho.', 'Two bottles of wine.', [Kit::word('o vinho', 'vinho'), Kit::word('a garrafa', 'garrafas')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.peixe-ana', 'O peixe é para a Ana.', 'The fish is for Ana.', [Kit::word('o peixe', 'peixe')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.que-tens', 'Tens queijo ou peixe?', 'Do you have cheese or fish?', [['tenho'], ['pão', 'queijo', 'fruta', 'leite', 'carne', 'peixe', 'vinho', 'água']], 'Tenho queijo.', [Kit::word('o queijo', 'queijo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tens-leite', 'Tens leite?', 'Do you have milk?', [['sim', 'não'], ['tenho', 'há']], 'Sim, tenho leite.', [Kit::word('o leite', 'leite')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ha-peixe', 'Há peixe?', 'Is there fish?', [['sim', 'não'], ['há', 'peixe']], 'Sim, há peixe.', [Kit::word('o peixe', 'peixe')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $ha = 'Há (there is) sounds like the article a, but the word you write is há, with an accent.';

        return [
            Kit::readPassage($stage, 'task.read_passage.mercado', 'Read the conversation at the market.', [
                Kit::line('Rui', 'Bom dia, Marta. Tem queijo?'),
                Kit::line('Marta', 'Sim, tenho queijo, peixe e vinho.'),
                Kit::line('Rui', 'Um quilo de queijo e uma garrafa de vinho, por favor.'),
                Kit::line('Marta', 'Aqui tem. Obrigada.'),
                Kit::line('Rui', 'Obrigado. Adeus.'),
            ], [
                Kit::question('What does Rui buy?', ['Cheese and wine', 'Fish and milk', 'Bread and fruit'], 'Cheese and wine'),
                Kit::question('How much cheese does Rui buy?', ['A kilo', 'Two kilos', 'A bottle'], 'A kilo'),
                Kit::question('Does Marta have fish?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
            ], [Kit::word('o queijo', 'queijo'), Kit::word('o peixe', 'peixe'), Kit::word('o quilo', 'quilo'), Kit::word('a garrafa', 'garrafa'), Kit::word('o vinho', 'vinho')], 'read'),
            Kit::gap($stage, 'task.choose_gap.rui-quilo', 'O Rui tem um quilo ___ peixe.', ['de', 'o'], 'de', Kit::form('um quilo de', true), 'Um quilo is an amount, so de comes before the fish: um quilo de peixe. O does not fit here.', 'read', 'Rui has a kilo of fish.'),
            Kit::gap($stage, 'task.choose_gap.garrafas-agua', 'Duas garrafas de ___, por favor.', ['água', 'quilo', 'pão'], 'água', Kit::word('a água', 'água'), 'Water comes in a bottle. Quilo is a measure, not a thing, and bread does not come in bottles.', 'read', 'Two bottles of water, please.'),

            Kit::transform($stage, 'task.transform.dois-quilos', 'Now make it two kilos.', 'Tenho um quilo de queijo.', ['Tenho dois quilos de queijo.', 'Eu tenho dois quilos de queijo.'], [Kit::word('o quilo', 'quilos'), Kit::word('o queijo', 'queijo'), Kit::form('dois quilos de')]),
            Kit::transform($stage, 'task.transform.pergunta', 'Now ask a seller (formal you).', 'Tenho pão e fruta.', ['Tem pão e fruta?', 'O senhor tem pão e fruta?', 'A senhora tem pão e fruta?'], [Kit::word('o pão', 'pão'), Kit::word('a fruta', 'fruta')]),
            Kit::transform($stage, 'task.transform.duas-garrafas', 'Now make it two bottles.', 'Tenho uma garrafa de vinho.', ['Tenho duas garrafas de vinho.', 'Eu tenho duas garrafas de vinho.'], [Kit::word('a garrafa', 'garrafas'), Kit::word('o vinho', 'vinho'), Kit::form('duas garrafas de', true)]),
            Kit::writeGuided($stage, 'task.write_guided.peixe-leite', 'Ask for a kilo of fish and a bottle of milk, please.', ['um quilo', 'uma garrafa', 'peixe', 'leite'], 'Um quilo de peixe e uma garrafa de leite, por favor.', [
                ['forms' => ['quilo'], 'term' => 'o quilo'],
                ['forms' => ['peixe'], 'term' => 'o peixe'],
                ['forms' => ['garrafa'], 'term' => 'a garrafa'],
                ['forms' => ['leite'], 'term' => 'o leite'],
            ], [Kit::word('o quilo', 'quilo'), Kit::word('o peixe', 'peixe'), Kit::word('a garrafa', 'garrafa'), Kit::word('o leite', 'leite'), Kit::form('um quilo de')]),
            Kit::writeGuided($stage, 'task.write_guided.vinho-queijo', 'Say that the wine and the cheese are for Rui.', ['o vinho', 'o queijo', 'para'], 'O vinho e o queijo são para o Rui.', [
                ['forms' => ['vinho'], 'term' => 'o vinho'],
                ['forms' => ['queijo'], 'term' => 'o queijo'],
                ['forms' => ['para'], 'term' => null],
            ], [Kit::word('o vinho', 'vinho'), Kit::word('o queijo', 'queijo')]),
            Kit::build($stage, 'task.build.garrafas-agua', 'I have two bottles of water.', 'Tenho duas garrafas de água.', ['uma', 'a'], [Kit::word('a garrafa', 'garrafas'), Kit::word('a água', 'água'), Kit::form('duas garrafas de')], 'write'),
            Kit::build($stage, 'task.build.ana-fruta', 'Ana has a kilo of fruit.', 'A Ana tem um quilo de fruta.', ['tenho', 'garrafa'], [Kit::word('o quilo', 'quilo'), Kit::word('a fruta', 'fruta'), Kit::form('um quilo de', true)], 'write'),
            Kit::build($stage, 'task.build.nao-ha-vinho', 'There is no wine, but there is water.', 'Não há vinho, mas há água.', ['tenho', 'com'], [Kit::word('o vinho', 'vinho'), Kit::word('a água', 'água')], 'write'),
            Kit::translate($stage, 'task.translate.garrafa-quilo', 'I have a bottle of wine and a kilo of cheese.', ['Tenho uma garrafa de vinho e um quilo de queijo.', 'Tenho um quilo de queijo e uma garrafa de vinho.', 'Eu tenho uma garrafa de vinho e um quilo de queijo.', 'Eu tenho um quilo de queijo e uma garrafa de vinho.'], [Kit::word('a garrafa', 'garrafa'), Kit::word('o vinho', 'vinho'), Kit::word('o quilo', 'quilo'), Kit::word('o queijo', 'queijo'), Kit::form('uma garrafa de')], 'write'),
            Kit::translate($stage, 'task.translate.nao-ha-pao', 'There is no bread, but I have fruit.', ['Não há pão, mas tenho fruta.', 'Não há pão, mas eu tenho fruta.'], [Kit::word('o pão', 'pão'), Kit::word('a fruta', 'fruta')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.carne-peixe', [
                Kit::line('Ana', 'Bom dia, João. Tens carne?'),
                Kit::line('João', 'Sim, tenho carne, peixe e água.'),
                Kit::line('Ana', 'Um quilo de peixe e duas garrafas de água, por favor.'),
                Kit::line('João', 'Aqui tens.'),
                Kit::line('Ana', 'Obrigada.'),
            ], [
                Kit::question('What does Ana buy?', ['Fish and water', 'Meat and wine', 'Cheese and milk'], 'Fish and water'),
                Kit::question('How many bottles of water does Ana buy?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Does João have meat?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who sells the food?', ['João', 'Ana', 'Nobody'], 'João'),
                Kit::question('Who asks for a kilo?', ['Ana', 'João', 'Nobody'], 'Ana'),
                Kit::question('What does Ana say at the end?', ['Obrigada', 'Adeus', 'Bom dia'], 'Obrigada'),
            ], [Kit::word('a carne', 'carne'), Kit::word('o peixe', 'peixe'), Kit::word('o quilo', 'quilo'), Kit::word('a garrafa', 'garrafas'), Kit::word('a água', 'água')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.leite-pao', 'Tenho uma garrafa de leite e um quilo de pão.', 'I have a bottle of milk and a kilo of bread.', [Kit::word('o leite', 'leite'), Kit::word('a garrafa', 'garrafa'), Kit::word('o quilo', 'quilo'), Kit::word('o pão', 'pão'), Kit::form('uma garrafa de')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.carne-fruta', 'Não há carne, mas há peixe e fruta.', 'There is no meat, but there is fish and fruit.', [Kit::word('a carne', 'carne'), Kit::word('o peixe', 'peixe'), Kit::word('a fruta', 'fruta')], 'listen', null, [], $ha),
            Kit::listenType($stage, 'task.listen_type.duas-garrafas', 'Tem duas garrafas de vinho?', 'Do you have two bottles of wine? (formal you)', [Kit::word('a garrafa', 'garrafas'), Kit::word('o vinho', 'vinho'), Kit::form('duas garrafas de')], 'listen', null, [], 'Tem (you, formal / he / she) has no accent; têm (they) has a circumflex.'),

            Kit::speakAnswer($stage, 'task.speak_answer.na-garrafa', 'O que há na garrafa?', 'What is in the bottle?', [['há'], ['água', 'leite', 'vinho']], 'Há água.', [Kit::word('a água', 'água'), Kit::word('a garrafa', 'garrafa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pao-fruta', 'Tens pão ou fruta?', 'Do you have bread or fruit?', [['tenho'], ['pão', 'fruta']], 'Tenho pão.', [Kit::word('o pão', 'pão'), Kit::word('a fruta', 'fruta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.para-ana', 'O que é para a Ana?', 'What is for Ana?', [['o', 'a', 'é'], ['queijo', 'pão', 'fruta', 'carne', 'peixe', 'vinho', 'leite', 'água']], 'O queijo é para a Ana.', [Kit::word('o queijo', 'queijo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.carne-peixe', 'Tens carne ou peixe?', 'Do you have meat or fish?', [['tenho'], ['carne', 'peixe']], 'Tenho carne.', [Kit::word('a carne', 'carne'), Kit::word('o peixe', 'peixe')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.carne-vinho', 'Um quilo de carne e duas garrafas de vinho.', 'A kilo of meat and two bottles of wine.', [Kit::word('a carne', 'carne'), Kit::word('o quilo', 'quilo'), Kit::word('a garrafa', 'garrafas'), Kit::word('o vinho', 'vinho')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.leite-agua', 'O leite e a água são para a Marta.', 'The milk and the water are for Marta.', [Kit::word('o leite', 'leite'), Kit::word('a água', 'água')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.carne-leite', 'A kilo of meat and a bottle of milk.', ['Um quilo de carne e uma garrafa de leite.', 'Uma garrafa de leite e um quilo de carne.'], [Kit::word('o quilo', 'quilo'), Kit::word('a carne', 'carne'), Kit::word('a garrafa', 'garrafa'), Kit::word('o leite', 'leite'), Kit::form('um quilo de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.quilos-peixe', 'Two kilos of fish, please.', ['Dois quilos de peixe, por favor.', 'Por favor, dois quilos de peixe.'], [Kit::word('o quilo', 'quilos'), Kit::word('o peixe', 'peixe'), Kit::form('dois quilos de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.garrafas-vinho', 'Three bottles of wine, please.', ['Três garrafas de vinho, por favor.', 'Por favor, três garrafas de vinho.'], [Kit::word('a garrafa', 'garrafas'), Kit::word('o vinho', 'vinho'), Kit::form('três garrafas de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.agua-queijo', 'A bottle of water and a kilo of cheese.', ['Uma garrafa de água e um quilo de queijo.', 'Um quilo de queijo e uma garrafa de água.'], [Kit::word('a garrafa', 'garrafa'), Kit::word('a água', 'água'), Kit::word('o quilo', 'quilo'), Kit::word('o queijo', 'queijo'), Kit::form('uma garrafa de')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ana-pao', 'A Ana tem um quilo ___ pão.', 'Ana has a kilo of bread.', 'de', Kit::form('de', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-fruta', 'A Marta tem dois ___ de fruta.', 'Marta has two kilos of fruit.', 'quilos', Kit::form('quilos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.pao-fruta', 'Tenho pão e fruta para a Ana.', 'I have bread and fruit for Ana.', [Kit::word('o pão', 'pão'), Kit::word('a fruta', 'fruta')], 'dictation', $set, homophoneNote: 'The a before Ana is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.vinho-leite', 'O vinho e o leite são para a Marta.', 'The wine and the milk are for Marta.', [Kit::word('o vinho', 'vinho'), Kit::word('o leite', 'leite')], 'dictation', $set, homophoneNote: 'The a before Marta is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.agua-queijo-carne', 'A água e a carne são para a Ana.', 'The water and the meat are for Ana.', [Kit::word('a água', 'água'), Kit::word('a carne', 'carne')], 'dictation', $set, homophoneNote: 'Each a here is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenPassage($stage, 'check.a.listen_passage.peixe', [
                Kit::line('Rui', 'Olá, Marta. Tem peixe?'),
                Kit::line('Marta', 'Sim, tenho peixe e carne.'),
                Kit::line('Rui', 'Um quilo de carne, por favor.'),
                Kit::line('Marta', 'Aqui tem. Obrigada.'),
            ], [
                Kit::question('What does Rui buy?', ['A kilo of meat', 'A kilo of fish', 'A bottle of wine'], 'A kilo of meat'),
                Kit::question('Does Marta have fish?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What else does Marta have?', ['Meat', 'Milk', 'Fruit'], 'Meat'),
            ], [
                Kit::question('Who sells the food?', ['Marta', 'Rui', 'Nobody'], 'Marta'),
                Kit::question('Who asks for a kilo?', ['Rui', 'Marta', 'Nobody'], 'Rui'),
                Kit::question('What does Marta say after Rui asks for meat?', ['Aqui tem. Obrigada.', 'Adeus.', 'Bom dia.'], 'Aqui tem. Obrigada.'),
            ], [Kit::word('o peixe', 'peixe'), Kit::word('a carne', 'carne'), Kit::word('o quilo', 'quilo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.fruta', 'Read the conversation.', [
                Kit::line('Ana', 'Olá, João. Há fruta?'),
                Kit::line('João', 'Não, não há fruta. Há pão, queijo e vinho.'),
                Kit::line('Ana', 'Dois quilos de pão e uma garrafa de vinho.'),
                Kit::line('João', 'Aqui tens.'),
            ], [
                Kit::question('Is there fruit?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What does Ana buy?', ['Bread and wine', 'Cheese and milk', 'Fruit and fish'], 'Bread and wine'),
            ], [Kit::word('a fruta', 'fruta'), Kit::word('o pão', 'pão'), Kit::word('o queijo', 'queijo'), Kit::word('o vinho', 'vinho')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vinho-agua', 'Tens vinho ou água?', 'Do you have wine or water?', [['tenho'], ['vinho', 'água']], 'Tenho água.', [Kit::word('o vinho', 'vinho'), Kit::word('a água', 'água')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.marta', 'A Marta tem queijo ou peixe?', 'Does Marta have cheese or fish?', [['sim', 'não'], ['tem', 'queijo', 'peixe']], 'Sim, tem queijo.', [Kit::word('o queijo', 'queijo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quilo-peixe', 'Tens um quilo de peixe?', 'Do you have a kilo of fish?', [['sim', 'não'], ['tenho', 'peixe', 'quilo']], 'Sim, tenho um quilo de peixe.', [Kit::word('o peixe', 'peixe'), Kit::word('o quilo', 'quilo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $ha = 'Há (there is) sounds like the article a, but the word you write is há, with an accent.';

        return [
            Kit::translate($stage, 'check.b.translate.vinho-fruta', 'A bottle of wine and a kilo of fruit.', ['Uma garrafa de vinho e um quilo de fruta.', 'Um quilo de fruta e uma garrafa de vinho.'], [Kit::word('a garrafa', 'garrafa'), Kit::word('o vinho', 'vinho'), Kit::word('o quilo', 'quilo'), Kit::word('a fruta', 'fruta'), Kit::form('uma garrafa de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pao-leite', 'Two kilos of bread and a bottle of milk.', ['Dois quilos de pão e uma garrafa de leite.', 'Uma garrafa de leite e dois quilos de pão.'], [Kit::word('o quilo', 'quilos'), Kit::word('o pão', 'pão'), Kit::word('a garrafa', 'garrafa'), Kit::word('o leite', 'leite'), Kit::form('dois quilos de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.queijo-peixe', 'A kilo of cheese and a kilo of fish.', ['Um quilo de queijo e um quilo de peixe.', 'Um quilo de peixe e um quilo de queijo.'], [Kit::word('o quilo', 'quilo'), Kit::word('o queijo', 'queijo'), Kit::word('o peixe', 'peixe'), Kit::form('um quilo de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.garrafas-agua', 'Two bottles of water and a kilo of meat.', ['Duas garrafas de água e um quilo de carne.', 'Um quilo de carne e duas garrafas de água.'], [Kit::word('a garrafa', 'garrafas'), Kit::word('a água', 'água'), Kit::word('o quilo', 'quilo'), Kit::word('a carne', 'carne'), Kit::form('duas garrafas de', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.rui-queijo', 'O Rui tem um quilo ___ queijo.', 'Rui has a kilo of cheese.', 'de', Kit::form('de', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.rui-peixe', 'O Rui tem dois ___ de peixe.', 'Rui has two kilos of fish.', 'quilos', Kit::form('quilos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vinho-agua', 'O vinho e a água são para o Rui.', 'The wine and the water are for Rui.', [Kit::word('o vinho', 'vinho'), Kit::word('a água', 'água')], 'dictation', $set, homophoneNote: 'The a before água is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.pao-fruta-queijo', 'Há pão, fruta e queijo.', 'There is bread, fruit and cheese.', [Kit::word('o pão', 'pão'), Kit::word('a fruta', 'fruta'), Kit::word('o queijo', 'queijo')], 'dictation', $set, homophoneNote: $ha),
            Kit::listenType($stage, 'check.b.listen_type.carne-peixe-leite', 'Há carne, peixe e leite.', 'There is meat, fish and milk.', [Kit::word('a carne', 'carne'), Kit::word('o peixe', 'peixe'), Kit::word('o leite', 'leite')], 'dictation', $set, homophoneNote: $ha),
        ];
    }
}
