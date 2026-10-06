<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

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
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'food-and-drink';
    }

    public function words(): array
    {
        return [
            new WordData('el pan', cue: 'bread'),
            new WordData('la leche', cue: 'milk'),
            new WordData('el agua', cue: 'water', note: 'Agua is feminine, but the single word takes el: el agua. The quantity word stays feminine: una botella de agua.'),
            new WordData('la fruta', cue: 'fruit'),
            new WordData('la carne', cue: 'meat'),
            new WordData('el pescado', cue: 'fish (as food)'),
            new WordData('el queso', cue: 'cheese'),
            new WordData('el vino', cue: 'wine'),
            new WordData('el kilo', cue: 'kilo (kilogram)', forms: ['kilos']),
            new WordData('la botella', cue: 'bottle', forms: ['botellas']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Un kilo de queso, por favor.', 'english' => 'A kilo of cheese, please.'],
            ['text' => 'Tengo dos botellas de agua.', 'english' => 'I have two bottles of water.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-06', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.kilo-queso', 'Tengo un kilo ___ queso.', ['de', 'el'], 'de', Kit::form('un kilo de', true), 'After a quantity like un kilo, Spanish puts de before the thing: un kilo de queso. El does not fit here.', 'choose', 'I have a kilo of cheese.'),
            Kit::gap($stage, 'sentences.choose_gap.botella-agua', 'Tengo una botella ___ agua.', ['de', 'de la'], 'de', Kit::form('una botella de', true), 'After de there is no article: una botella de agua. De la agua is not correct.', 'choose', 'I have a bottle of water.'),
            Kit::gap($stage, 'sentences.choose_gap.kilos-fruta', 'Tengo dos kilos ___ fruta.', ['de', 'a'], 'de', Kit::form('dos kilos de', true), 'Dos kilos is an amount, so de comes before the fruit: dos kilos de fruta. A does not fit here.', 'choose', 'I have two kilos of fruit.'),
            Kit::gap($stage, 'sentences.choose_gap.agua-pablo', '___ agua es para Pablo.', ['El', 'La'], 'El', Kit::word('el agua', 'agua'), 'Agua is feminine, but the single word takes el: el agua. La agua is not correct.', 'choose', 'The water is for Pablo.'),
            Kit::gap($stage, 'sentences.choose_gap.botella-leche', 'Una botella de ___, por favor.', ['leche', 'pan', 'pescado'], 'leche', Kit::word('la leche', 'leche'), 'Milk comes in a bottle. Bread and fish do not.', 'choose', 'A bottle of milk, please.'),
            Kit::gap($stage, 'sentences.choose_gap.kilo-carne', 'Tengo un kilo de ___.', ['carne', 'botella', 'una'], 'carne', Kit::word('la carne', 'carne'), 'Carne is a thing you can weigh. Botella is a container, and una is an article.', 'choose', 'I have a kilo of meat.'),

            Kit::typeGap($stage, 'sentences.type_gap.botella-leche', 'Tengo una botella ___ leche.', 'I have a bottle of milk.', 'de', Kit::form('de'), 'Between the amount and the thing comes de: una botella de leche.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-kilo', 'Marta tiene un ___ de queso.', 'Marta has a kilo of cheese.', 'kilo', Kit::word('el kilo', 'kilo')),
            Kit::typeGap($stage, 'sentences.type_gap.pablo-botella', 'Pablo tiene una ___ de vino.', 'Pablo has a bottle of wine.', 'botella', Kit::word('la botella', 'botella')),
            Kit::typeGap($stage, 'sentences.type_gap.kilo-fruta', 'Un kilo ___ fruta, por favor.', 'A kilo of fruit, please.', 'de', Kit::form('de'), 'Un kilo is an amount, so de comes next: un kilo de fruta.'),
            Kit::typeGap($stage, 'sentences.type_gap.kilos-pan', 'Dos ___ de pan, por favor.', 'Two kilos of bread, please.', 'kilos', Kit::form('kilos'), 'With two, the amount word is plural: dos kilos de pan.'),

            Kit::translate($stage, 'sentences.translate.kilo-queso', 'A kilo of cheese, please.', ['Un kilo de queso, por favor.', 'Por favor, un kilo de queso.'], [Kit::word('el queso', 'queso'), Kit::form('un kilo de')]),
            Kit::translate($stage, 'sentences.translate.botellas-leche', 'Two bottles of milk, please.', ['Dos botellas de leche, por favor.', 'Por favor, dos botellas de leche.'], [Kit::word('la botella', 'botellas'), Kit::form('dos botellas de')]),
            Kit::translate($stage, 'sentences.translate.tiene-leche', 'Do you have milk? (formal you)', ['¿Tiene leche?', '¿Tiene usted leche?', '¿Usted tiene leche?'], [Kit::word('la leche', 'leche')]),

            Kit::build($stage, 'sentences.build.pablo-carne', 'Pablo has a kilo of meat.', 'Pablo tiene un kilo de carne.', ['botella'], [Kit::word('el kilo', 'kilo'), Kit::word('la carne', 'carne'), Kit::form('un kilo de')]),
            Kit::build($stage, 'sentences.build.pan-marta', 'The bread is for Marta.', 'El pan es para Marta.', ['la'], [Kit::word('el pan', 'pan')]),
            Kit::build($stage, 'sentences.build.botella-vino', 'A bottle of wine, please.', 'Una botella de vino, por favor.', ['kilo'], [Kit::word('la botella', 'botella'), Kit::word('el vino', 'vino'), Kit::form('una botella de')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.kilo-pescado', 'Un kilo de pescado, por favor.', ['A kilo of fish, please.', 'A kilo of cheese, please.', 'Two kilos of fish, please.', 'A bottle of milk, please.'], 'A kilo of fish, please.', [Kit::word('el pescado', 'pescado'), Kit::word('el kilo', 'kilo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.botella-agua', 'Tengo una botella de agua.', ['I have a bottle of water.', 'I have a bottle of wine.', 'I have two bottles of water.', 'You have a bottle of water.'], 'I have a bottle of water.', [Kit::word('el agua', 'agua'), Kit::word('la botella', 'botella')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.carne-pescado', '¿Tiene carne o pescado?', ['Do you have meat or fish?', 'Do you have bread or fish?', 'Do I have meat or fish?', 'Is there meat or fish?'], 'Do you have meat or fish?', [Kit::word('la carne', 'carne'), Kit::word('el pescado', 'pescado')]),
            Kit::listenType($stage, 'sentences.listen_type.kilos-fruta', 'Dos kilos de fruta, por favor.', 'Two kilos of fruit, please.', [Kit::word('la fruta', 'fruta'), Kit::word('el kilo', 'kilos')]),
            Kit::listenType($stage, 'sentences.listen_type.vino-luis', 'El vino es para Luis.', 'The wine is for Luis.', [Kit::word('el vino', 'vino')]),
            Kit::listenType($stage, 'sentences.listen_type.pan-queso-fruta', 'Tengo pan, queso y fruta.', 'I have bread, cheese and fruit.', [Kit::word('el pan', 'pan'), Kit::word('el queso', 'queso'), Kit::word('la fruta', 'fruta')]),
            Kit::listenType($stage, 'sentences.listen_type.no-leche', 'No hay leche aquí.', 'There is no milk here.', [Kit::word('la leche', 'leche')], 'listen', null, [], 'Hay (there is) sounds like ay, but the word you write is hay.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.botella-agua', 'Una botella de agua, por favor.', 'A bottle of water, please.', [Kit::word('el agua', 'agua'), Kit::word('la botella', 'botella')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.kilo-pan', 'Tengo un kilo de pan.', 'I have a kilo of bread.', [Kit::word('el pan', 'pan'), Kit::word('el kilo', 'kilo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.botellas-vino', 'Dos botellas de vino.', 'Two bottles of wine.', [Kit::word('el vino', 'vino'), Kit::word('la botella', 'botellas')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pescado-ana', 'El pescado es para Ana.', 'The fish is for Ana.', [Kit::word('el pescado', 'pescado')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.que-tienes', '¿Qué tienes?', 'What do you have?', [['tengo'], ['pan', 'queso', 'fruta', 'leche', 'carne', 'pescado', 'vino', 'agua']], 'Tengo queso.', [Kit::word('el queso', 'queso')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tienes-leche', '¿Tienes leche?', 'Do you have milk?', [['sí', 'no'], ['tengo', 'hay']], 'Sí, tengo leche.', [Kit::word('la leche', 'leche')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.hay-pescado', '¿Hay pescado?', 'Is there fish?', [['sí', 'no'], ['hay', 'pescado']], 'Sí, hay pescado.', [Kit::word('el pescado', 'pescado')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.mercado', 'Read the conversation at the market.', [
                Kit::line('Luis', 'Buenos días, Marta. ¿Tiene queso?'),
                Kit::line('Marta', 'Sí, tengo queso, pescado y vino.'),
                Kit::line('Luis', 'Un kilo de queso y una botella de vino, por favor.'),
                Kit::line('Marta', 'Aquí tiene. Gracias.'),
                Kit::line('Luis', 'Gracias. Adiós.'),
            ], [
                Kit::question('What does Luis buy?', ['Cheese and wine', 'Fish and milk', 'Bread and fruit'], 'Cheese and wine'),
                Kit::question('How much cheese does Luis buy?', ['A kilo', 'Two kilos', 'A bottle'], 'A kilo'),
                Kit::question('Does Marta have fish?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
            ], [Kit::word('el queso', 'queso'), Kit::word('el pescado', 'pescado'), Kit::word('el kilo', 'kilo'), Kit::word('la botella', 'botella'), Kit::word('el vino', 'vino')], 'read'),
            Kit::gap($stage, 'task.choose_gap.luis-kilo', 'Luis tiene un kilo ___ pescado.', ['de', 'el'], 'de', Kit::form('un kilo de', true), 'Un kilo is an amount, so de comes before the fish: un kilo de pescado. El does not fit here.', 'read', 'Luis has a kilo of fish.'),
            Kit::gap($stage, 'task.choose_gap.botellas-agua', 'Dos botellas de ___, por favor.', ['agua', 'kilo', 'pan'], 'agua', Kit::word('el agua', 'agua'), 'Water comes in a bottle. Kilo is a measure, not a thing, and bread does not come in bottles.', 'read', 'Two bottles of water, please.'),

            Kit::transform($stage, 'task.transform.dos-kilos', 'Say you have two.', 'Tengo un kilo de queso.', ['Tengo dos kilos de queso.', 'Yo tengo dos kilos de queso.'], [Kit::word('el kilo', 'kilos'), Kit::word('el queso', 'queso'), Kit::form('dos kilos de')]),
            Kit::transform($stage, 'task.transform.pregunta', 'Now ask a seller (formal you).', 'Tengo pan y fruta.', ['¿Tiene pan y fruta?', '¿Tiene usted pan y fruta?', '¿Usted tiene pan y fruta?'], [Kit::word('el pan', 'pan'), Kit::word('la fruta', 'fruta')]),
            Kit::transform($stage, 'task.transform.dos-botellas', 'Say you have two.', 'Tengo una botella de vino.', ['Tengo dos botellas de vino.', 'Yo tengo dos botellas de vino.'], [Kit::word('la botella', 'botellas'), Kit::word('el vino', 'vino'), Kit::form('dos botellas de', true)]),
            Kit::writeGuided($stage, 'task.write_guided.pescado-leche', 'Ask for a kilo of fish and a bottle of milk, please.', ['un kilo', 'una botella', 'pescado', 'leche'], 'Un kilo de pescado y una botella de leche, por favor.', [
                ['forms' => ['kilo'], 'term' => 'el kilo'],
                ['forms' => ['pescado'], 'term' => 'el pescado'],
                ['forms' => ['botella'], 'term' => 'la botella'],
                ['forms' => ['leche'], 'term' => 'la leche'],
            ], [Kit::word('el kilo', 'kilo'), Kit::word('el pescado', 'pescado'), Kit::word('la botella', 'botella'), Kit::word('la leche', 'leche'), Kit::form('un kilo de')]),
            Kit::writeGuided($stage, 'task.write_guided.vino-queso', 'Say that the wine and the cheese are for Luis.', ['el vino', 'el queso', 'para'], 'El vino y el queso son para Luis.', [
                ['forms' => ['vino'], 'term' => 'el vino'],
                ['forms' => ['queso'], 'term' => 'el queso'],
                ['forms' => ['para'], 'term' => null],
            ], [Kit::word('el vino', 'vino'), Kit::word('el queso', 'queso')]),
            Kit::build($stage, 'task.build.botellas-agua', 'I have two bottles of water.', 'Tengo dos botellas de agua.', ['una', 'el'], [Kit::word('la botella', 'botellas'), Kit::word('el agua', 'agua'), Kit::form('dos botellas de')], 'write'),
            Kit::build($stage, 'task.build.ana-fruta', 'Ana has a kilo of fruit.', 'Ana tiene un kilo de fruta.', ['tengo', 'botella'], [Kit::word('el kilo', 'kilo'), Kit::word('la fruta', 'fruta'), Kit::form('un kilo de', true)], 'write'),
            Kit::build($stage, 'task.build.no-vino', 'There is no wine, but there is water.', 'No hay vino, pero hay agua.', ['tengo', 'con'], [Kit::word('el vino', 'vino'), Kit::word('el agua', 'agua')], 'write'),
            Kit::translate($stage, 'task.translate.botella-kilo', 'I have a bottle of wine and a kilo of cheese.', ['Tengo una botella de vino y un kilo de queso.', 'Tengo un kilo de queso y una botella de vino.', 'Yo tengo una botella de vino y un kilo de queso.', 'Yo tengo un kilo de queso y una botella de vino.'], [Kit::word('la botella', 'botella'), Kit::word('el vino', 'vino'), Kit::word('el kilo', 'kilo'), Kit::word('el queso', 'queso'), Kit::form('una botella de')], 'write'),
            Kit::translate($stage, 'task.translate.no-pan', 'There is no bread, but I have fruit.', ['No hay pan, pero tengo fruta.', 'No hay pan, pero yo tengo fruta.'], [Kit::word('el pan', 'pan'), Kit::word('la fruta', 'fruta')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.carne-pescado', [
                Kit::line('Ana', 'Buenos días, Pablo. ¿Tienes carne?'),
                Kit::line('Pablo', 'Sí, tengo carne, pescado y agua.'),
                Kit::line('Ana', 'Un kilo de pescado y dos botellas de agua, por favor.'),
                Kit::line('Pablo', 'Aquí tienes.'),
                Kit::line('Ana', 'Gracias.'),
            ], [
                Kit::question('What does Ana buy?', ['Fish and water', 'Meat and wine', 'Cheese and milk'], 'Fish and water'),
                Kit::question('How many bottles of water does Ana buy?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Does Pablo have meat?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who sells the food?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Who asks for a kilo?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la carne', 'carne'), Kit::word('el pescado', 'pescado'), Kit::word('el kilo', 'kilo'), Kit::word('la botella', 'botellas'), Kit::word('el agua', 'agua')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.leche-pan', 'Tengo una botella de leche y un kilo de pan.', 'I have a bottle of milk and a kilo of bread.', [Kit::word('la leche', 'leche'), Kit::word('la botella', 'botella'), Kit::word('el kilo', 'kilo'), Kit::word('el pan', 'pan'), Kit::form('una botella de')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.carne-fruta', 'No hay carne, pero hay pescado y fruta.', 'There is no meat, but there is fish and fruit.', [Kit::word('la carne', 'carne'), Kit::word('el pescado', 'pescado'), Kit::word('la fruta', 'fruta')], 'listen', null, [], 'Hay (there is) sounds like ay, but the word you write is hay.'),
            Kit::listenType($stage, 'task.listen_type.dos-botellas', '¿Tiene dos botellas de vino?', 'Do you have two bottles of wine? (formal you)', [Kit::word('la botella', 'botellas'), Kit::word('el vino', 'vino'), Kit::form('dos botellas de')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.en-la-botella', '¿Qué hay en la botella?', 'What is in the bottle?', [['hay'], ['agua', 'leche', 'vino']], 'Hay agua.', [Kit::word('el agua', 'agua'), Kit::word('la botella', 'botella')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pan-fruta', '¿Tienes pan o fruta?', 'Do you have bread or fruit?', [['tengo'], ['pan', 'fruta']], 'Tengo pan.', [Kit::word('el pan', 'pan'), Kit::word('la fruta', 'fruta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.para-ana', '¿Qué es para Ana?', 'What is for Ana?', [['el', 'la', 'es'], ['queso', 'pan', 'fruta', 'carne', 'pescado', 'vino', 'leche', 'agua']], 'El queso es para Ana.', [Kit::word('el queso', 'queso')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.carne-pescado', '¿Tienes carne o pescado?', 'Do you have meat or fish?', [['tengo'], ['carne', 'pescado']], 'Tengo carne.', [Kit::word('la carne', 'carne'), Kit::word('el pescado', 'pescado')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.carne-vino', 'Un kilo de carne y dos botellas de vino.', 'A kilo of meat and two bottles of wine.', [Kit::word('la carne', 'carne'), Kit::word('el kilo', 'kilo'), Kit::word('la botella', 'botellas'), Kit::word('el vino', 'vino')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.leche-agua', 'La leche y el agua son para Marta.', 'The milk and the water are for Marta.', [Kit::word('la leche', 'leche'), Kit::word('el agua', 'agua')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.carne-leche', 'A kilo of meat and a bottle of milk.', ['Un kilo de carne y una botella de leche.', 'Una botella de leche y un kilo de carne.'], [Kit::word('el kilo', 'kilo'), Kit::word('la carne', 'carne'), Kit::word('la botella', 'botella'), Kit::word('la leche', 'leche'), Kit::form('un kilo de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.kilos-pescado', 'Two kilos of fish, please.', ['Dos kilos de pescado, por favor.', 'Por favor, dos kilos de pescado.'], [Kit::word('el kilo', 'kilos'), Kit::word('el pescado', 'pescado'), Kit::form('dos kilos de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.botellas-vino', 'Three bottles of wine, please.', ['Tres botellas de vino, por favor.', 'Por favor, tres botellas de vino.'], [Kit::word('la botella', 'botellas'), Kit::word('el vino', 'vino'), Kit::form('tres botellas de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.agua-queso', 'A bottle of water and a kilo of cheese.', ['Una botella de agua y un kilo de queso.', 'Un kilo de queso y una botella de agua.'], [Kit::word('la botella', 'botella'), Kit::word('el agua', 'agua'), Kit::word('el kilo', 'kilo'), Kit::word('el queso', 'queso'), Kit::form('una botella de')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ana-pan', 'Ana tiene un kilo ___ pan.', 'Ana has a kilo of bread.', 'de', Kit::form('de', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-fruta', 'Marta tiene dos ___ de fruta.', 'Marta has two kilos of fruit.', 'kilos', Kit::form('kilos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.pan-fruta', 'Tengo pan y fruta para Ana.', 'I have bread and fruit for Ana.', [Kit::word('el pan', 'pan'), Kit::word('la fruta', 'fruta')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.vino-leche', 'El vino y la leche son para Marta.', 'The wine and the milk are for Marta.', [Kit::word('el vino', 'vino'), Kit::word('la leche', 'leche')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.agua-queso-carne', 'El agua, el queso y la carne son para Ana.', 'The water, the cheese and the meat are for Ana.', [Kit::word('el agua', 'agua'), Kit::word('el queso', 'queso'), Kit::word('la carne', 'carne')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.pescado', [
                Kit::line('Luis', 'Hola, Marta. ¿Tiene pescado?'),
                Kit::line('Marta', 'Sí, tengo pescado y carne.'),
                Kit::line('Luis', 'Un kilo de carne, por favor.'),
                Kit::line('Marta', 'Aquí tiene. Gracias.'),
            ], [
                Kit::question('What does Luis buy?', ['A kilo of meat', 'A kilo of fish', 'A bottle of wine'], 'A kilo of meat'),
                Kit::question('Does Marta have fish?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What else does Marta have?', ['Meat', 'Milk', 'Fruit'], 'Meat'),
            ], [
                Kit::question('Who sells the food?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Who asks for a kilo?', ['Luis', 'Marta', 'Nobody'], 'Luis'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el pescado', 'pescado'), Kit::word('la carne', 'carne'), Kit::word('el kilo', 'kilo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.fruta', 'Read the conversation.', [
                Kit::line('Ana', 'Hola, Pablo. ¿Hay fruta?'),
                Kit::line('Pablo', 'No, no hay fruta. Hay pan, queso y vino.'),
                Kit::line('Ana', 'Dos kilos de pan y una botella de vino.'),
                Kit::line('Pablo', 'Aquí tienes.'),
            ], [
                Kit::question('Is there fruit?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What does Ana buy?', ['Bread and wine', 'Cheese and milk', 'Fruit and fish'], 'Bread and wine'),
            ], [Kit::word('la fruta', 'fruta'), Kit::word('el pan', 'pan'), Kit::word('el queso', 'queso'), Kit::word('el vino', 'vino')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vino-agua', '¿Tienes vino o agua?', 'Do you have wine or water?', [['tengo'], ['vino', 'agua']], 'Tengo agua.', [Kit::word('el vino', 'vino'), Kit::word('el agua', 'agua')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.marta', '¿Qué tiene Marta?', 'What does Marta have?', [['tiene'], ['pan', 'queso', 'fruta', 'leche', 'carne', 'pescado', 'vino', 'agua']], 'Marta tiene queso.', [Kit::word('el queso', 'queso')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.kilo-pescado', '¿Tienes un kilo de pescado?', 'Do you have a kilo of fish?', [['sí', 'no'], ['tengo', 'pescado', 'kilo']], 'Sí, tengo un kilo de pescado.', [Kit::word('el pescado', 'pescado'), Kit::word('el kilo', 'kilo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.vino-fruta', 'A bottle of wine and a kilo of fruit.', ['Una botella de vino y un kilo de fruta.', 'Un kilo de fruta y una botella de vino.'], [Kit::word('la botella', 'botella'), Kit::word('el vino', 'vino'), Kit::word('el kilo', 'kilo'), Kit::word('la fruta', 'fruta'), Kit::form('una botella de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pan-leche', 'Two kilos of bread and a bottle of milk.', ['Dos kilos de pan y una botella de leche.', 'Una botella de leche y dos kilos de pan.'], [Kit::word('el kilo', 'kilos'), Kit::word('el pan', 'pan'), Kit::word('la botella', 'botella'), Kit::word('la leche', 'leche'), Kit::form('dos kilos de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.queso-pescado', 'A kilo of cheese and a kilo of fish.', ['Un kilo de queso y un kilo de pescado.', 'Un kilo de pescado y un kilo de queso.'], [Kit::word('el kilo', 'kilo'), Kit::word('el queso', 'queso'), Kit::word('el pescado', 'pescado'), Kit::form('un kilo de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.botellas-agua', 'Two bottles of water and a kilo of meat.', ['Dos botellas de agua y un kilo de carne.', 'Un kilo de carne y dos botellas de agua.'], [Kit::word('la botella', 'botellas'), Kit::word('el agua', 'agua'), Kit::word('el kilo', 'kilo'), Kit::word('la carne', 'carne'), Kit::form('dos botellas de', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-queso', 'Luis tiene un kilo ___ queso.', 'Luis has a kilo of cheese.', 'de', Kit::form('de', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luis-pescado', 'Luis tiene dos ___ de pescado.', 'Luis has two kilos of fish.', 'kilos', Kit::form('kilos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vino-agua', 'El vino y el agua son para Luis.', 'The wine and the water are for Luis.', [Kit::word('el vino', 'vino'), Kit::word('el agua', 'agua')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pan-fruta-queso', 'Hay pan, fruta y queso.', 'There is bread, fruit and cheese.', [Kit::word('el pan', 'pan'), Kit::word('la fruta', 'fruta'), Kit::word('el queso', 'queso')], 'dictation', $set, [], 'Hay (there is) sounds like ay, but the word you write is hay.'),
            Kit::listenType($stage, 'check.b.listen_type.carne-pescado-leche', 'Hay carne, pescado y leche.', 'There is meat, fish and milk.', [Kit::word('la carne', 'carne'), Kit::word('el pescado', 'pescado'), Kit::word('la leche', 'leche')], 'dictation', $set, [], 'Hay (there is) sounds like ay, but the word you write is hay.'),
        ];
    }
}
