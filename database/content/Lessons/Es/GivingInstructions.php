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

final class GivingInstructions implements UnitContent
{
    private const A_NOTE = 'A without an h means to or at. It sounds the same as ha, a form of haber, but here it is a, as in ven a la cocina.';

    private const COOKING = ['corta', 'mezcla', 'añade', 'cocina', 'pela', 'pon'];

    private const COMMANDS = ['corta', 'mezcla', 'añade', 'cocina', 'pela', 'pon', 'haz', 've', 'ten', 'di', 'ven'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'giving-instructions';
    }

    public function words(): array
    {
        return [
            new WordData('cortar', cue: 'to cut', forms: ['corta', 'corto'], note: 'Cortar is to cut. The command for one friend is corta: Corta el pan.'),
            new WordData('mezclar', cue: 'to mix', forms: ['mezcla', 'mezclo']),
            new WordData('añadir', cue: 'to add', forms: ['añade']),
            new WordData('cocinar', cue: 'to cook', forms: ['cocina', 'cocino'], note: 'Cocinar is to cook, and la cocina is the kitchen. The command is cocina: Cocina el pescado.'),
            new WordData('pelar', cue: 'to peel', forms: ['pela']),
            new WordData('la olla', cue: 'pot (saucepan)'),
            new WordData('el cuchillo', cue: 'knife'),
            new WordData('el horno', cue: 'oven'),
            new WordData('el huevo', cue: 'egg', forms: ['huevos'], note: 'The h of huevo is silent, so it sounds like uebo.'),
            new WordData('la sartén', cue: 'frying pan', note: 'La sartén is feminine, even though it ends in -n: la sartén, una sartén.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Corta el pan, por favor.', 'english' => 'Cut the bread, please.'],
            ['text' => 'Ana, haz la cena.', 'english' => 'Ana, make dinner.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-09', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-09', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-09', 'Released on the owner\'s instruction on 2026-10-09, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.corta-pan', 'Luis, ___ el pan, por favor.', ['corta', 'corto', 'cortar'], 'corta', Kit::form('corta', true), 'You are telling Luis what to do, so you need the command: corta. Corto means I cut and cortar is the infinitive.', 'choose', 'Luis, cut the bread, please.'),
            Kit::gap($stage, 'sentences.choose_gap.ven-aqui', 'Ana, ___ aquí, por favor.', ['ven', 'vienes', 'venir'], 'ven', Kit::form('ven', true), 'Venir has a short irregular command: ven. Vienes means you come, which is a statement and not an instruction.', 'choose', 'Ana, come here, please.'),
            Kit::gap($stage, 'sentences.choose_gap.sarten', 'Cocina el pescado en la ___.', ['sartén', 'cuchillo', 'huevo'], 'sartén', Kit::word('la sartén', 'sartén'), 'You cook fish in a frying pan: la sartén. A cuchillo is for cutting and it is masculine, so it would be el cuchillo.', 'choose', 'Cook the fish in the frying pan.'),
            Kit::gap($stage, 'sentences.choose_gap.cuchillo', 'Corta la fruta con el ___.', ['cuchillo', 'horno', 'huevo'], 'cuchillo', Kit::word('el cuchillo', 'cuchillo'), 'You cut with a knife: el cuchillo. The oven is for baking and an egg is food, not a tool.', 'choose', 'Cut the fruit with the knife.'),
            Kit::gap($stage, 'sentences.choose_gap.horno', 'Pon el pan en el ___.', ['horno', 'cuchillo', 'olla'], 'horno', Kit::word('el horno', 'horno'), 'You put bread in the oven: en el horno. You do not put bread in a knife, and olla is feminine, so it would be en la olla.', 'choose', 'Put the bread in the oven.'),
            Kit::gap($stage, 'sentences.choose_gap.haz-cena', 'Pablo, ___ la cena, por favor.', ['haz', 'hace', 'hacer'], 'haz', Kit::form('haz', true), 'Hacer has a short irregular command: haz. Hace means he or she makes, and hacer is the infinitive.', 'choose', 'Pablo, make dinner, please.'),

            Kit::typeGap($stage, 'sentences.type_gap.pon-pan', 'Ana, ___ el pan en la mesa.', 'Ana, put the bread on the table.', 'pon', Kit::form('pon'), 'Poner has a short irregular command: pon.'),
            Kit::typeGap($stage, 'sentences.type_gap.ve-cocina', 'Luis, ___ a la cocina, por favor.', 'Luis, go to the kitchen, please.', 've', Kit::form('ve'), 'Ir has the irregular command ve. It looks like the él form of ver, but here it means go.'),
            Kit::typeGap($stage, 'sentences.type_gap.pela-fruta', 'Pablo, ___ la fruta, por favor.', 'Pablo, peel the fruit, please.', 'pela', Kit::word('pelar', 'pela'), 'A regular command is the él form of the present: pela.'),
            Kit::typeGap($stage, 'sentences.type_gap.huevos-olla', 'Pon los ___ en la olla.', 'Put the eggs in the pot.', 'huevos', Kit::word('el huevo', 'huevos')),
            Kit::typeGap($stage, 'sentences.type_gap.anade-agua', 'Marta, ___ agua a la olla.', 'Marta, add water to the pot.', 'añade', Kit::word('añadir', 'añade'), 'A regular command is the él form of the present: añade.'),

            Kit::translate($stage, 'sentences.translate.corta-cuchillo', 'Cut the bread with the knife.', ['Corta el pan con el cuchillo.'], [Kit::word('cortar', 'corta'), Kit::word('el cuchillo', 'cuchillo'), Kit::form('corta')]),
            Kit::translate($stage, 'sentences.translate.mezcla-huevos', 'Mix the eggs and add water.', ['Mezcla los huevos y añade agua.'], [Kit::word('mezclar', 'mezcla'), Kit::word('el huevo', 'huevos'), Kit::form('mezcla')]),
            Kit::translate($stage, 'sentences.translate.ven-mesa', 'Ana, come here and set the table.', ['Ana, ven aquí y pon la mesa.', 'Ven aquí y pon la mesa, Ana.'], [Kit::form('ven')]),

            Kit::build($stage, 'sentences.build.cocina-sarten', 'Cook the fish in the frying pan.', 'Cocina el pescado en la sartén.', ['cocino'], [Kit::word('cocinar', 'cocina'), Kit::word('la sartén', 'sartén'), Kit::form('cocina')]),
            Kit::build($stage, 'sentences.build.pon-olla', 'Put the pot in the oven.', 'Pon la olla en el horno.', ['pone'], [Kit::word('la olla', 'olla'), Kit::word('el horno', 'horno'), Kit::form('pon')]),
            Kit::build($stage, 'sentences.build.di-numero', 'Pablo, say your number.', 'Pablo, di tu número.', ['dices'], [Kit::form('di')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.corta-pan', 'Corta el pan.', ['Cut the bread.', 'Cut the cheese.', 'Buy the bread.', 'I am cutting the bread.'], 'Cut the bread.', [Kit::word('cortar', 'corta'), Kit::form('corta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pon-huevos', 'Pon los huevos en la olla.', ['Put the eggs in the pot.', 'Put the eggs in the pan.', 'Take the eggs out of the pot.', 'I put the eggs in the pot.'], 'Put the eggs in the pot.', [Kit::word('el huevo', 'huevos'), Kit::word('la olla', 'olla'), Kit::form('pon')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ven-cocina', 'Ven a la cocina, Ana.', ['Come to the kitchen, Ana.', 'Go to the kitchen, Ana.', 'Ana comes to the kitchen.', 'Come to the garden, Ana.'], 'Come to the kitchen, Ana.', [Kit::form('ven')]),
            Kit::listenType($stage, 'sentences.listen_type.anade-agua', 'Añade agua a la olla.', 'Add water to the pot.', [Kit::word('añadir', 'añade'), Kit::word('la olla', 'olla'), Kit::form('añade')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.haz-cena', 'Haz la cena, Marta.', 'Make dinner, Marta.', [Kit::form('haz')]),
            Kit::listenType($stage, 'sentences.listen_type.pela-fruta', 'Pela la fruta, Pablo.', 'Peel the fruit, Pablo.', [Kit::word('pelar', 'pela'), Kit::form('pela')]),
            Kit::listenType($stage, 'sentences.listen_type.sal-cocina', 'Sal de la cocina, Luis.', 'Leave the kitchen, Luis.', [Kit::form('sal')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.corta-queso', 'Corta el queso con el cuchillo.', 'Cut the cheese with the knife.', [Kit::word('el cuchillo', 'cuchillo'), Kit::word('cortar', 'corta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pon-sarten', 'Pon la sartén en el horno.', 'Put the frying pan in the oven.', [Kit::word('la sartén', 'sartén'), Kit::word('el horno', 'horno'), Kit::form('pon')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ten-regalo', 'Ten el regalo, Ana.', 'Hold the gift, Ana.', [Kit::form('ten')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mezcla-leche', 'Mezcla los huevos y la leche.', 'Mix the eggs and the milk.', [Kit::word('mezclar', 'mezcla'), Kit::word('el huevo', 'huevos')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.fruta', '¿Qué hago con la fruta?', 'What do I do with the fruit?', [self::COOKING], 'Pela la fruta.', [Kit::word('pelar', 'pela'), Kit::form('pela')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.huevo-sarten', 'Tengo un huevo y una sartén. ¿Qué hago?', 'I have an egg and a frying pan. What do I do?', [self::COOKING], 'Cocina el huevo.', [Kit::word('cocinar', 'cocina'), Kit::word('el huevo', 'huevo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.olla', '¿Dónde pongo la olla?', 'Where do I put the pot?', [['pon'], ['horno', 'mesa', 'cocina']], 'Pon la olla en el horno.', [Kit::word('la olla', 'olla'), Kit::word('el horno', 'horno')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.receta', 'Read the conversation in the kitchen.', [
                Kit::line('Ana', 'Pablo, ven a la cocina. Vamos a hacer la cena.'),
                Kit::line('Pablo', '¿Qué hago?'),
                Kit::line('Ana', 'Pela la fruta y corta el pan.'),
                Kit::line('Pablo', 'Muy bien. ¿Y los huevos?'),
                Kit::line('Ana', 'Mezcla los huevos con el queso.'),
                Kit::line('Pablo', 'Claro. Gracias, Ana.'),
            ], [
                Kit::question('What does Ana tell Pablo to peel?', ['The fruit', 'The eggs', 'The bread'], 'The fruit'),
                Kit::question('What does Ana tell Pablo to cut?', ['The bread', 'The fruit', 'The cheese'], 'The bread'),
                Kit::question('What does Pablo mix the eggs with?', ['Cheese', 'Milk', 'Water'], 'Cheese'),
            ], [Kit::word('pelar', 'pela'), Kit::word('cortar', 'corta'), Kit::word('mezclar', 'mezcla'), Kit::word('el huevo', 'huevos')], 'read'),
            Kit::gap($stage, 'task.choose_gap.se-simpatico', 'Luis, ___ simpático con Ana.', ['sé', 'eres', 'soy'], 'sé', Kit::form('sé', true), 'Ser has the irregular command sé: be nice. Eres is a statement (you are) and soy means I am.', 'read', 'Luis, be nice to Ana.'),
            Kit::gap($stage, 'task.choose_gap.olla-fruta', 'Luis, pon la fruta en la ___.', ['olla', 'horno', 'cuchillo'], 'olla', Kit::word('la olla', 'olla'), 'You put fruit in a pot: en la olla. Horno and cuchillo are masculine, so they would be en el, and you do not put fruit in a knife.', 'read', 'Luis, put the fruit in the pot.'),

            Kit::transform($stage, 'task.transform.corta', 'Tell Luis to do it (command, informal you).', 'Luis corta el pan.', ['Luis, corta el pan.', 'Corta el pan, Luis.', 'Corta el pan.'], [Kit::word('cortar', 'corta'), Kit::form('corta', true)]),
            Kit::transform($stage, 'task.transform.haz', 'Tell Ana to do it (command, informal you).', 'Ana hace la cena.', ['Ana, haz la cena.', 'Haz la cena, Ana.', 'Haz la cena.'], [Kit::form('haz', true)]),
            Kit::transform($stage, 'task.transform.pon', 'Tell Marta to do it (command, informal you).', 'Marta pone los huevos en la olla.', ['Marta, pon los huevos en la olla.', 'Pon los huevos en la olla, Marta.', 'Pon los huevos en la olla.'], [Kit::word('el huevo', 'huevos'), Kit::word('la olla', 'olla'), Kit::form('pon', true)]),
            Kit::writeGuided($stage, 'task.write_guided.pela-corta', 'Tell Pablo to peel the fruit and to cut the bread.', ['Pablo', 'pela', 'la fruta', 'corta', 'el pan'], 'Pablo, pela la fruta y corta el pan.', [
                ['forms' => ['pela'], 'term' => 'pelar'],
                ['forms' => ['corta'], 'term' => 'cortar'],
            ], [Kit::word('pelar', 'pela'), Kit::word('cortar', 'corta'), Kit::form('pela')]),
            Kit::writeGuided($stage, 'task.write_guided.anade-cocina', 'Tell Ana to add water to the pot and to cook the egg.', ['Ana', 'añade', 'agua', 'la olla', 'cocina', 'el huevo'], 'Ana, añade agua a la olla y cocina el huevo.', [
                ['forms' => ['añade'], 'term' => 'añadir'],
                ['forms' => ['olla'], 'term' => 'la olla'],
                ['forms' => ['cocina'], 'term' => 'cocinar'],
            ], [Kit::word('añadir', 'añade'), Kit::word('la olla', 'olla'), Kit::word('cocinar', 'cocina'), Kit::form('añade')]),
            Kit::build($stage, 'task.build.mezcla-queso', 'Marta, mix the eggs with the cheese.', 'Marta, mezcla los huevos con el queso.', ['mezclo', 'mezclar'], [Kit::word('mezclar', 'mezcla'), Kit::word('el huevo', 'huevos'), Kit::form('mezcla')]),
            Kit::build($stage, 'task.build.ven-cuchillo', 'Luis, come here and put the knife on the table.', 'Luis, ven aquí y pon el cuchillo en la mesa.', ['vienes', 'pones'], [Kit::word('el cuchillo', 'cuchillo'), Kit::form('ven')]),
            Kit::build($stage, 'task.build.ve-haz', 'Pablo, go to the kitchen and make dinner.', 'Pablo, ve a la cocina y haz la cena.', ['vas', 'haces'], [Kit::form('ve')]),
            Kit::translate($stage, 'task.translate.sal-cocina', 'Luis, leave the kitchen and say your number.', ['Luis, sal de la cocina y di tu número.', 'Sal de la cocina y di tu número, Luis.'], [Kit::form('sal')]),
            Kit::translate($stage, 'task.translate.ten-cuchillo', 'Hold the knife and cut the fruit.', ['Ten el cuchillo y corta la fruta.'], [Kit::word('el cuchillo', 'cuchillo'), Kit::word('cortar', 'corta'), Kit::form('ten')]),

            Kit::listenPassage($stage, 'task.listen_passage.cena', [
                Kit::line('Luis', 'Marta, ¿qué hago con los huevos?'),
                Kit::line('Marta', 'Mezcla los huevos y añade queso.'),
                Kit::line('Luis', '¿Y el pan?'),
                Kit::line('Marta', 'Corta el pan con el cuchillo. Yo voy a cocinar el pescado.'),
                Kit::line('Luis', 'Muy bien. ¡Vamos!'),
            ], [
                Kit::question('What does Marta tell Luis to mix?', ['The eggs', 'The fruit', 'The fish'], 'The eggs'),
                Kit::question('What does Marta tell Luis to add?', ['Cheese', 'Milk', 'Water'], 'Cheese'),
                Kit::question('What is Marta going to cook?', ['The fish', 'The eggs', 'The bread'], 'The fish'),
            ], [
                Kit::question('Who speaks first?', ['Luis', 'Marta', 'Nobody'], 'Luis'),
                Kit::question('Does Luis ask about the bread?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('mezclar', 'mezcla'), Kit::word('añadir', 'añade'), Kit::word('cortar', 'corta'), Kit::word('el cuchillo', 'cuchillo'), Kit::word('cocinar')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pela-fruta', 'Pablo, ven a la cocina y pela la fruta.', 'Pablo, come to the kitchen and peel the fruit.', [Kit::word('pelar', 'pela'), Kit::form('ven')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.pon-olla', 'Marta, pon la olla en el horno.', 'Marta, put the pot in the oven.', [Kit::word('la olla', 'olla'), Kit::word('el horno', 'horno'), Kit::form('pon')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.cocina-huevos', 'Luis, cocina los huevos en la sartén.', 'Luis, cook the eggs in the frying pan.', [Kit::word('cocinar', 'cocina'), Kit::word('el huevo', 'huevos'), Kit::word('la sartén', 'sartén')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.pan', '¿Qué hago con el pan?', 'What do I do with the bread?', [self::COOKING], 'Corta el pan.', [Kit::word('cortar', 'corta'), Kit::form('corta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.huevos', '¿Qué hago con los huevos?', 'What do I do with the eggs?', [self::COOKING], 'Mezcla los huevos.', [Kit::word('mezclar', 'mezcla'), Kit::word('el huevo', 'huevos')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sarten', '¿Dónde pongo la sartén?', 'Where do I put the frying pan?', [['pon'], ['mesa', 'horno', 'cocina']], 'Pon la sartén en la mesa.', [Kit::word('la sartén', 'sartén'), Kit::form('pon')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cuchillo', '¿Qué hago con el cuchillo y el pan?', 'What do I do with the knife and the bread?', [self::COMMANDS], 'Corta el pan con el cuchillo.', [Kit::word('el cuchillo', 'cuchillo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.anade-olla', 'Añade agua y pon la olla en el horno.', 'Add water and put the pot in the oven.', [Kit::word('añadir', 'añade'), Kit::word('la olla', 'olla'), Kit::word('el horno', 'horno')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ten-sarten', 'Luis, ten la sartén y cocina el huevo.', 'Luis, hold the frying pan and cook the egg.', [Kit::word('la sartén', 'sartén'), Kit::word('cocinar', 'cocina'), Kit::form('ten')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.corta-fruta', 'Luis, cut the fruit and peel the egg.', ['Luis, corta la fruta y pela el huevo.', 'Corta la fruta y pela el huevo, Luis.'], [Kit::word('cortar', 'corta'), Kit::word('pelar', 'pela'), Kit::word('el huevo', 'huevo'), Kit::form('corta', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.mezcla-queso', 'Ana, mix the eggs and add cheese.', ['Ana, mezcla los huevos y añade queso.', 'Mezcla los huevos y añade queso, Ana.'], [Kit::word('mezclar', 'mezcla'), Kit::word('añadir', 'añade'), Kit::word('el huevo', 'huevos'), Kit::form('mezcla')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pon-olla', 'Pablo, put the fish in the oven.', ['Pablo, pon el pescado en el horno.', 'Pon el pescado en el horno, Pablo.'], [Kit::word('el horno', 'horno'), Kit::form('pon', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ven-pela', 'Marta, come here and peel the fruit.', ['Marta, ven aquí y pela la fruta.', 'Ven aquí y pela la fruta, Marta.'], [Kit::word('pelar', 'pela'), Kit::form('ven')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sal-cocina', 'Ana, ___ de la cocina y pon la mesa.', 'Ana, leave the kitchen and set the table.', 'sal', Kit::form('sal'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pon-huevo', 'Pablo, pon el huevo en la ___.', 'Pablo, put the egg in the frying pan.', 'sartén', Kit::word('la sartén', 'sartén'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.cocina-huevos', 'Marta, cocina los huevos en la olla.', 'Marta, cook the eggs in the pot.', [Kit::word('cocinar', 'cocina'), Kit::word('el huevo', 'huevos'), Kit::word('la olla', 'olla')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.ten-cuchillo', 'Ten el cuchillo y corta el queso, Luis.', 'Hold the knife and cut the cheese, Luis.', [Kit::word('el cuchillo', 'cuchillo'), Kit::word('cortar', 'corta'), Kit::form('ten')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.anade-olla', 'Añade queso a los huevos en la sartén, Pablo.', 'Add cheese to the eggs in the frying pan, Pablo.', [Kit::word('añadir', 'añade'), Kit::word('el huevo', 'huevos'), Kit::word('la sartén', 'sartén')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.cena', [
                Kit::line('Pablo', 'Ana, ¿qué hacemos para la cena?'),
                Kit::line('Ana', 'Vamos a cocinar pescado. Pon el pescado en el horno.'),
                Kit::line('Pablo', '¿Y el pan, Ana?'),
                Kit::line('Ana', 'Corta el pan, por favor. Yo voy a pelar la fruta.'),
                Kit::line('Pablo', 'Muy bien. ¿Dónde está el cuchillo?'),
                Kit::line('Ana', 'Está en la mesa.'),
            ], [
                Kit::question('What are they going to cook?', ['Fish', 'Eggs', 'Fruit'], 'Fish'),
                Kit::question('What does Ana tell Pablo to cut?', ['The bread', 'The fruit', 'The fish'], 'The bread'),
                Kit::question('Where is the knife?', ['On the table', 'In the oven', 'In the pot'], 'On the table'),
            ], [
                Kit::question('Who speaks first?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Who asks about the knife?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('cocinar'), Kit::word('el horno', 'horno')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.cena-ana', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿qué haces?'),
                Kit::line('Luis', 'Voy a hacer una cena para Ana.'),
                Kit::line('Marta', 'Muy bien. Mezcla los huevos con la leche.'),
                Kit::line('Luis', '¿Y la fruta?'),
                Kit::line('Marta', 'Pela la fruta con el cuchillo.'),
                Kit::line('Luis', 'Claro. Gracias, Marta.'),
            ], [
                Kit::question('What does Marta tell Luis to mix?', ['Eggs and milk', 'Eggs and cheese', 'Fruit and milk'], 'Eggs and milk'),
                Kit::question('Who is the dinner for?', ['Ana', 'Marta', 'Pablo'], 'Ana'),
            ], [Kit::word('mezclar', 'mezcla'), Kit::word('pelar', 'pela')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.olla', '¿Qué hago con la olla?', 'What do I do with the pot?', [['añade', 'pon', 'cocina']], 'Añade agua a la olla.', [Kit::word('la olla', 'olla'), Kit::word('añadir', 'añade')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.queso', '¿Qué hago con el queso?', 'What do I do with the cheese?', [['corta', 'mezcla', 'añade']], 'Corta el queso.', [Kit::word('cortar', 'corta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fruta-cuchillo', 'Tengo fruta y un cuchillo. ¿Qué hago?', 'I have fruit and a knife. What do I do?', [['corta', 'pela']], 'Corta la fruta.', [Kit::word('cortar', 'corta'), Kit::word('el cuchillo', 'cuchillo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.corta-pan', 'Marta, peel the fruit with the knife.', ['Marta, pela la fruta con el cuchillo.', 'Pela la fruta con el cuchillo, Marta.'], [Kit::word('pelar', 'pela'), Kit::word('el cuchillo', 'cuchillo'), Kit::form('pela', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.mezcla-leche', 'Add the milk to the eggs and mix.', ['Añade la leche a los huevos y mezcla.'], [Kit::word('añadir', 'añade'), Kit::word('mezclar', 'mezcla'), Kit::word('el huevo', 'huevos'), Kit::form('mezcla')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.anade-sarten', 'Ana, add water and put the frying pan in the oven.', ['Ana, añade agua y pon la sartén en el horno.', 'Añade agua y pon la sartén en el horno, Ana.'], [Kit::word('añadir', 'añade'), Kit::word('la sartén', 'sartén'), Kit::word('el horno', 'horno'), Kit::form('pon', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ve-pela', 'Luis, go to the kitchen and hold the knife.', ['Luis, ve a la cocina y ten el cuchillo.', 'Ve a la cocina y ten el cuchillo, Luis.'], [Kit::word('el cuchillo', 'cuchillo'), Kit::form('ve')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.cocina-pescado', 'Ana, ___ el pescado en el horno.', 'Ana, cook the fish in the oven.', 'cocina', Kit::word('cocinar', 'cocina'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.sarten-mesa', 'Pon la ___ en la mesa, Pablo.', 'Put the frying pan on the table, Pablo.', 'sartén', Kit::word('la sartén', 'sartén'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.pela-corta', 'Pela el huevo y corta el pan, Pablo.', 'Peel the egg and cut the bread, Pablo.', [Kit::word('pelar', 'pela'), Kit::word('el huevo', 'huevo'), Kit::word('cortar', 'corta'), Kit::form('pela')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ten-mezcla', 'Ten la olla y mezcla los huevos.', 'Hold the pot and mix the eggs.', [Kit::word('la olla', 'olla'), Kit::word('mezclar', 'mezcla'), Kit::word('el huevo', 'huevos'), Kit::form('ten')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.anade-cocina', 'Pon la olla en el horno y cocina, Ana.', 'Put the pot in the oven and cook, Ana.', [Kit::word('la olla', 'olla'), Kit::word('el horno', 'horno'), Kit::word('cocinar', 'cocina')], 'dictation', $set),
        ];
    }
}
