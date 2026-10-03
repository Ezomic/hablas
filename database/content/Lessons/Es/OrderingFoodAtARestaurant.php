<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class OrderingFoodAtARestaurant implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'ordering-food-at-a-restaurant';
    }

    public function words(): array
    {
        return [
            new WordData('el restaurante', cue: 'restaurant'),
            new WordData('el menú', cue: 'menu (the list of dishes)', accepted: ['la carta'], note: 'This is the list of dishes. In Spain la carta means the same and is also accepted.'),
            new WordData('la cuenta', cue: 'bill (to pay at the end)'),
            new WordData('quisiera', cue: 'I would like', accepted: ['me gustaría', 'yo quisiera']),
            new WordData('para beber', cue: 'to drink (as in something to drink)', accepted: ['de beber', 'algo de beber', 'algo para beber']),
            new WordData('para comer', cue: 'to eat (as in something to eat)', accepted: ['de comer', 'algo de comer', 'algo para comer']),
            new WordData('el camarero', cue: 'waiter (or waitress)', accepted: ['la camarera']),
            new WordData('delicioso', cue: 'delicious (masculine)', forms: ['deliciosa']),
            new WordData('la propina', cue: 'tip (money for the waiter)'),
            new WordData('vegetariano', cue: 'vegetarian (masculine)', forms: ['vegetariana']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El camarero trabaja aquí.', 'english' => 'The waiter works here.'],
            ['text' => 'Hablamos español.', 'english' => 'We speak Spanish.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), Wikcionario (menú also means carta, the list of dishes), SpanishDict. Fixed: accepted yo quisiera, algo de beber, algo para beber, algo de comer, algo para comer. el menú kept with la carta accepted, la camarera accepted. Open questions answered and removed.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.pago-cuenta', 'Yo ___ la cuenta.', ['pago', 'paga', 'pagas'], 'pago', Kit::form('pago', true), 'The ending shows who pays: yo goes with -o.', 'choose', glosses: ['pago' => 'I pay', 'paga' => 'he or she pays', 'pagas' => 'you pay']),
            Kit::gap($stage, 'sentences.choose_gap.camarero-habla', 'El camarero ___ con Ana.', ['habla', 'hablas', 'hablamos'], 'habla', Kit::form('habla'), 'El camarero is one person, so the verb ends in -a.', 'choose', glosses: ['habla' => 'he or she speaks', 'hablas' => 'you speak', 'hablamos' => 'we speak']),
            Kit::gap($stage, 'sentences.choose_gap.menu-es', 'El menú ___ delicioso.', ['es', 'habla', 'toma'], 'es', Kit::form('es', true), 'Es says what the menu is like. The verbs habla and toma say what someone does.', 'choose', glosses: ['habla' => 'he or she speaks', 'toma' => 'he or she is having (food or drink)']),
            Kit::gap($stage, 'sentences.choose_gap.trabajamos', 'Nosotros ___ en el restaurante.', ['trabajamos', 'trabajan', 'trabajo'], 'trabajamos', Kit::form('trabajamos'), 'Nosotros goes with the ending -amos.', 'choose', glosses: ['trabajamos' => 'we work', 'trabajan' => 'they work', 'trabajo' => 'I work']),
            Kit::gap($stage, 'sentences.choose_gap.menu-vegetariano', 'Quisiera un menú ___.', ['vegetariano', 'vegetariana'], 'vegetariano', Kit::word('vegetariano'), 'Menú is masculine, so the adjective ends in -o.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.quisiera', '___ la cuenta, por favor.', ['Quisiera', 'Hablo', 'Trabajo'], 'Quisiera', Kit::word('quisiera'), 'Quisiera is the polite way to ask for something. Verbs like hablo and trabajo do not ask for things.', 'choose', glosses: ['hablo' => 'I speak', 'trabajo' => 'I work']),

            Kit::typeGap($stage, 'sentences.type_gap.habla', 'Ana ___ con el camarero.', 'Ana speaks with the waiter.', 'habla', Kit::form('habla'), 'Ana is one person, so the verb ends in -a.'),
            Kit::typeGap($stage, 'sentences.type_gap.pagamos', '___ la cuenta.', 'We pay the bill.', 'Pagamos', Kit::form('pagamos'), 'We pay is pagamos: the ending -amos means we.', glosses: ['pagamos' => 'we pay']),
            Kit::typeGap($stage, 'sentences.type_gap.trabajo', 'Yo ___ en un restaurante.', 'I work in a restaurant.', 'trabajo', Kit::form('trabajo'), 'Yo goes with the ending -o.', glosses: ['trabajo' => 'I work']),
            Kit::typeGap($stage, 'sentences.type_gap.propina-es', 'La propina ___ para el camarero.', 'The tip is for the waiter.', 'es', Kit::form('es', true), 'What something is or is for takes ser, not an -ar verb.'),
            Kit::typeGap($stage, 'sentences.type_gap.tomo', 'Yo ___ algo para beber.', 'I am having something to drink.', 'tomo', Kit::form('tomo'), 'Yo goes with the ending -o.', glosses: ['tomo' => 'I am having (food or drink)']),

            Kit::translate($stage, 'sentences.translate.hablamos', 'We speak with the waiter.', ['Hablamos con el camarero.', 'Nosotros hablamos con el camarero.'], [Kit::word('el camarero'), Kit::form('hablamos')]),
            Kit::translate($stage, 'sentences.translate.pago', 'I pay the bill.', ['Pago la cuenta.', 'Yo pago la cuenta.'], [Kit::word('la cuenta'), Kit::form('pago')]),
            Kit::translate($stage, 'sentences.translate.menu', 'The menu is delicious.', ['El menú es delicioso.', 'El menú está delicioso.'], [Kit::word('el menú', 'menú'), Kit::word('delicioso')]),

            Kit::build($stage, 'sentences.build.paga-propina', 'Ana pays the tip.', 'Ana paga la propina.', ['pago'], [Kit::word('la propina'), Kit::form('paga')]),
            Kit::build($stage, 'sentences.build.trabajamos', 'We work in the restaurant.', 'Trabajamos en el restaurante.', ['trabajan'], [Kit::word('el restaurante', 'restaurante'), Kit::form('trabajamos')]),
            Kit::build($stage, 'sentences.build.menu-vegetariano', 'I would like a vegetarian menu.', 'Quisiera un menú vegetariano.', ['vegetariana'], [Kit::word('quisiera'), Kit::word('el menú', 'menú'), Kit::word('vegetariano')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.camarero-habla', 'El camarero habla con Ana.', ['The waiter speaks with Ana.', 'The waiter pays Ana.', 'The waiter works with Ana.', 'Ana speaks with the waiter.'], 'The waiter speaks with Ana.', [Kit::word('el camarero'), Kit::form('habla')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pagamos', 'Pagamos la cuenta.', ['We pay the bill.', 'I pay the bill.', 'They pay the bill.', 'We pay the tip.'], 'We pay the bill.', [Kit::word('la cuenta'), Kit::form('pagamos')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.beber', 'Quisiera algo para beber.', ['I would like something to eat.', 'I would like something to drink.', 'I would like the menu.', 'I would like the bill.'], 'I would like something to drink.', [Kit::word('quisiera'), Kit::word('para beber')]),
            Kit::listenType($stage, 'sentences.listen_type.propina', 'La propina es para el camarero.', 'The tip is for the waiter.', [Kit::word('la propina'), Kit::word('el camarero'), Kit::form('es', true)]),
            Kit::listenType($stage, 'sentences.listen_type.comer', 'Quisiera algo para comer.', 'I would like something to eat.', [Kit::word('quisiera'), Kit::word('para comer')]),
            Kit::listenType($stage, 'sentences.listen_type.menu', 'El menú vegetariano es delicioso.', 'The vegetarian menu is delicious.', [Kit::word('el menú', 'menú'), Kit::word('vegetariano'), Kit::word('delicioso')]),
            Kit::listenType($stage, 'sentences.listen_type.trabajo', 'Trabajo en un restaurante.', 'I work in a restaurant.', [Kit::word('el restaurante', 'restaurante'), Kit::form('trabajo')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.cuenta', 'Quisiera la cuenta, por favor.', 'I would like the bill, please.', [Kit::word('quisiera'), Kit::word('la cuenta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.propina', 'Pago la cuenta y la propina.', 'I pay the bill and the tip.', [Kit::word('la cuenta'), Kit::word('la propina'), Kit::form('pago')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.restaurante', 'El restaurante es vegetariano.', 'The restaurant is vegetarian.', [Kit::word('el restaurante', 'restaurante'), Kit::word('vegetariano')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.comer-beber', 'Algo para comer y algo para beber.', 'Something to eat and something to drink.', [Kit::word('para comer'), Kit::word('para beber')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.camarero', '¿Hablas con el camarero?', 'Do you speak with the waiter?', [['sí', 'no'], ['hablo', 'hablamos', 'camarero']], 'Sí, hablo con el camarero.', [Kit::word('el camarero'), Kit::form('hablo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.paga', '¿Quién paga la cuenta?', 'Who pays the bill?', [['yo', 'tú', 'usted', 'nosotros', 'nosotras', 'él', 'ella', 'ellos', 'ellas', 'camarero', 'ana', 'pablo', 'marta', 'luis'], ['paga', 'pago', 'pagamos']], 'Ana paga la cuenta.', [Kit::word('la cuenta'), Kit::form('paga')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vegetariano', '¿Hay un menú vegetariano?', 'Is there a vegetarian menu?', [['sí', 'no', 'hay'], ['menú', 'carta', 'vegetariano', 'vegetariana']], 'Sí, hay un menú vegetariano.', [Kit::word('el menú', 'menú'), Kit::word('vegetariano')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.cuenta', 'Read the conversation at the end of the meal.', [
                Kit::line('Pablo', 'Buenas tardes. La cuenta, por favor.'),
                Kit::line('Camarero', 'Aquí tiene la cuenta.'),
                Kit::line('Pablo', 'Pago yo. Y la propina es para usted.'),
                Kit::line('Camarero', 'Muchas gracias.'),
            ], [
                Kit::question('What does Pablo ask for?', ['The menu', 'The bill', 'Something to drink'], 'The bill'),
                Kit::question('Who pays?', ['Pablo', 'The waiter', 'The text does not say.'], 'Pablo'),
                Kit::question('Who is the tip for?', ['The waiter', 'Pablo', 'The restaurant'], 'The waiter'),
            ], [Kit::word('la cuenta'), Kit::word('la propina'), Kit::word('el camarero')], 'read'),
            Kit::gap($stage, 'task.choose_gap.algo-delicioso', 'Quisiera algo ___ para comer.', ['delicioso', 'deliciosa'], 'delicioso', Kit::word('delicioso'), 'Algo is masculine, so the adjective ends in -o.', 'read'),
            Kit::gap($stage, 'task.choose_gap.pagamos', 'Nosotros ___ la cuenta.', ['pagamos', 'pagan', 'pagas'], 'pagamos', Kit::form('pagamos'), 'Nosotros goes with the ending -amos.', 'read', glosses: ['pagamos' => 'we pay', 'pagan' => 'they pay', 'pagas' => 'you pay']),

            Kit::transform($stage, 'task.transform.pagamos', 'Change it to nosotros.', 'Yo pago la cuenta.', ['Pagamos la cuenta.', 'Nosotros pagamos la cuenta.'], [Kit::word('la cuenta'), Kit::form('pagamos')]),
            Kit::transform($stage, 'task.transform.trabajamos', 'Change it to nosotros.', 'Trabajo en el restaurante.', ['Trabajamos en el restaurante.', 'Nosotros trabajamos en el restaurante.'], [Kit::word('el restaurante', 'restaurante'), Kit::form('trabajamos')]),
            Kit::transform($stage, 'task.transform.habla', 'Say it about Ana.', 'Hablo con el camarero.', ['Ana habla con el camarero.'], [Kit::word('el camarero'), Kit::form('habla')]),
            Kit::writeGuided($stage, 'task.write_guided.trabajo', 'Say that you work in a restaurant and that the menu is delicious.', ['trabajo', 'restaurante', 'menú', 'delicioso'], 'Trabajo en un restaurante. El menú es delicioso.', [
                ['forms' => ['trabajo'], 'term' => null],
                ['forms' => ['restaurante'], 'term' => 'el restaurante'],
                ['forms' => ['menú', 'carta'], 'term' => 'el menú'],
                ['forms' => ['delicioso', 'deliciosa'], 'term' => 'delicioso'],
            ], [Kit::word('el restaurante', 'restaurante'), Kit::word('el menú', 'menú'), Kit::word('delicioso')]),
            Kit::writeGuided($stage, 'task.write_guided.beber', 'Say that you would like something to drink and that you pay the bill.', ['quisiera', 'para beber', 'pago', 'cuenta'], 'Quisiera algo para beber. Pago la cuenta.', [
                ['forms' => ['quisiera'], 'term' => 'quisiera'],
                ['forms' => ['beber'], 'term' => 'para beber'],
                ['forms' => ['pago'], 'term' => null],
                ['forms' => ['cuenta'], 'term' => 'la cuenta'],
            ], [Kit::word('quisiera'), Kit::word('para beber'), Kit::word('la cuenta')]),
            Kit::build($stage, 'task.build.camarero-trabaja', 'The waiter works in the restaurant.', 'El camarero trabaja en el restaurante.', ['trabajo', 'trabajan'], [Kit::word('el camarero'), Kit::word('el restaurante', 'restaurante'), Kit::form('trabaja')], 'write'),
            Kit::build($stage, 'task.build.comer', 'I would like something to eat, please.', 'Quisiera algo para comer, por favor.', ['el', 'una'], [Kit::word('quisiera'), Kit::word('para comer')], 'write'),
            new AuthoredExercise(Stage::Task, Format::BuildSentence, 'task.build.cuenta-propina', ['prompt' => 'We pay the bill and the tip.', 'english' => 'We pay the bill and the tip.', 'distractors' => ['pago', 'pagan'], 'glosses' => ['pagan' => 'they pay']], ['Pagamos la cuenta y la propina.', 'Pagamos la propina y la cuenta.'], [Kit::word('la cuenta'), Kit::word('la propina'), Kit::form('pagamos')], block: 'write'),
            Kit::translate($stage, 'task.translate.hablas', 'Pablo, do you speak with the waiter?', ['Pablo, ¿hablas con el camarero?', '¿Hablas con el camarero, Pablo?', '¿Pablo, hablas con el camarero?'], [Kit::word('el camarero'), Kit::form('hablas')], 'write'),
            Kit::translate($stage, 'task.translate.menu', 'I would like the menu, please.', ['Quisiera el menú, por favor.', 'Yo quisiera el menú, por favor.'], [Kit::word('quisiera'), Kit::word('el menú', 'menú')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.vegetariano', [
                Kit::line('Ana', 'Quisiera un menú vegetariano.'),
                Kit::line('Camarero', 'Sí, hay un menú vegetariano. Es delicioso.'),
                Kit::line('Ana', 'Y quisiera algo para beber.'),
                Kit::line('Camarero', 'Muy bien.'),
                Kit::line('Ana', 'Gracias.'),
            ], [
                Kit::question('What kind of menu does Ana want?', ['A vegetarian one', 'A big one', 'A cheap one'], 'A vegetarian one'),
                Kit::question('What does the waiter say about the menu?', ['It is delicious.', 'It is expensive.', 'It is not ready.'], 'It is delicious.'),
                Kit::question('What else does Ana want?', ['Something to eat', 'Something to drink', 'The bill'], 'Something to drink'),
            ], [
                Kit::question('Is there a vegetarian menu?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What does Ana say at the end?', ['Thank you', 'Goodbye', 'The bill, please'], 'Thank you'),
            ], [Kit::word('el menú', 'menú'), Kit::word('vegetariano'), Kit::word('delicioso'), Kit::word('quisiera'), Kit::word('para beber')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.toma', 'Ana toma algo para beber.', 'Ana is having something to drink.', [Kit::word('para beber'), Kit::form('toma')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.camareros', 'Los camareros trabajan aquí.', 'The waiters work here.', [Kit::word('el camarero', 'camareros'), Kit::form('trabajan')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paga', 'Pablo paga la cuenta y la propina.', 'Pablo pays the bill and the tip.', [Kit::word('la cuenta'), Kit::word('la propina'), Kit::form('paga')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.trabaja', '¿Dónde trabaja el camarero?', 'Where does the waiter work?', [['camarero', 'trabaja'], ['restaurante', 'aquí']], 'El camarero trabaja en el restaurante.', [Kit::word('el camarero'), Kit::word('el restaurante', 'restaurante'), Kit::form('trabaja')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.propina', '¿Quién paga la propina?', 'Who pays the tip?', [['yo', 'tú', 'usted', 'nosotros', 'nosotras', 'él', 'ella', 'ellos', 'ellas', 'camarero', 'ana', 'pablo', 'marta', 'luis'], ['paga', 'pago', 'pagamos']], 'Pablo paga la propina.', [Kit::word('la propina'), Kit::form('paga')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.delicioso', '¿El menú es delicioso?', 'Is the menu delicious?', [['sí', 'no', 'es'], ['delicioso', 'deliciosa']], 'Sí, el menú es delicioso.', [Kit::word('el menú', 'menú'), Kit::word('delicioso')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.comer', '¿Quisiera algo para comer?', 'Would you like something to eat?', [['sí', 'no', 'quisiera'], ['comer', 'algo']], 'Sí, quisiera algo para comer.', [Kit::word('quisiera'), Kit::word('para comer')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.menu', 'Buenas tardes, quisiera un menú vegetariano.', 'Good afternoon, I would like a vegetarian menu.', [Kit::word('quisiera'), Kit::word('el menú', 'menú'), Kit::word('vegetariano')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.propina', 'La cuenta y la propina, por favor.', 'The bill and the tip, please.', [Kit::word('la cuenta'), Kit::word('la propina')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.camarero', 'The waiter works in a restaurant.', ['El camarero trabaja en un restaurante.'], [Kit::word('el camarero'), Kit::word('el restaurante', 'restaurante'), Kit::form('trabaja')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.menu', 'I would like the vegetarian menu.', ['Quisiera el menú vegetariano.', 'Yo quisiera el menú vegetariano.'], [Kit::word('quisiera'), Kit::word('el menú', 'menú'), Kit::word('vegetariano')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.propina', 'We pay the tip.', ['Pagamos la propina.', 'Nosotros pagamos la propina.'], [Kit::word('la propina'), Kit::form('pagamos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.cuenta', 'The bill is here.', ['La cuenta está aquí.', 'Aquí está la cuenta.'], [Kit::word('la cuenta'), Kit::form('está', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.paga', 'Marta ___ la cuenta.', 'Marta pays the bill.', 'paga', Kit::form('paga'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vegetariana', 'Ana ___ vegetariana.', 'Ana is vegetarian.', 'es', Kit::form('es', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.beber', 'Quisiera algo para beber, por favor.', 'I would like something to drink, please.', [Kit::word('quisiera'), Kit::word('para beber')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.toma', 'Marta toma algo para comer.', 'Marta is having something to eat.', [Kit::word('para comer'), Kit::form('toma')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.restaurante', 'El restaurante tiene un menú delicioso.', 'The restaurant has a delicious menu.', [Kit::word('el restaurante', 'restaurante'), Kit::word('el menú', 'menú'), Kit::word('delicioso')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.menu', [
                Kit::line('Camarero', 'Buenas tardes. Aquí tiene el menú.'),
                Kit::line('Luis', 'Para comer, quisiera algo vegetariano.'),
                Kit::line('Camarero', 'Muy bien. ¿Y algo para beber?'),
            ], [
                Kit::question('What does the waiter give Luis?', ['The menu', 'The bill', 'The key'], 'The menu'),
                Kit::question('What does Luis want?', ['Something to drink', 'Something to eat', 'The bill'], 'Something to eat'),
                Kit::question('What does the waiter ask about at the end?', ['Something to drink', 'A vegetarian menu', 'The tip'], 'Something to drink'),
            ], [
                Kit::question('Who speaks first?', ['The waiter', 'Luis', 'Nobody'], 'The waiter'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Luis ask for something vegetarian?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('el menú', 'menú'), Kit::word('quisiera'), Kit::word('para comer'), Kit::word('para beber'), Kit::word('vegetariano')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.restaurante', 'Read the conversation.', [
                Kit::line('Marta', 'Hola, Pablo. Trabajo en un restaurante vegetariano.'),
                Kit::line('Pablo', 'Muy bien. ¿Hay menú vegetariano?'),
                Kit::line('Marta', 'Sí, hay. Es delicioso.'),
            ], [
                Kit::question('Where does Marta work?', ['In a vegetarian restaurant', 'In a hotel', 'In a shop'], 'In a vegetarian restaurant'),
                Kit::question('What does Pablo ask about?', ['A vegetarian menu', 'The bill', 'The tip'], 'A vegetarian menu'),
            ], [Kit::word('el restaurante', 'restaurante'), Kit::word('vegetariano'), Kit::word('el menú', 'menú'), Kit::word('delicioso')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.beber', '¿Hay algo para beber?', 'Is there something to drink?', [['sí', 'no', 'hay'], ['algo', 'beber']], 'Sí, hay algo para beber.', [Kit::word('para beber')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.restaurante', '¿Hay un restaurante vegetariano aquí?', 'Is there a vegetarian restaurant here?', [['sí', 'no', 'hay'], ['restaurante', 'vegetariano']], 'Sí, hay un restaurante vegetariano aquí.', [Kit::word('el restaurante', 'restaurante'), Kit::word('vegetariano')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cuenta', '¿Dónde está la cuenta?', 'Where is the bill?', [['cuenta', 'está'], ['aquí', 'allí']], 'La cuenta está aquí.', [Kit::word('la cuenta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.habla', 'The waiter speaks with Marta.', ['El camarero habla con Marta.'], [Kit::word('el camarero'), Kit::form('habla')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.quisiera', 'I would like the bill and the tip.', ['Quisiera la cuenta y la propina.', 'Yo quisiera la cuenta y la propina.'], [Kit::word('quisiera'), Kit::word('la cuenta'), Kit::word('la propina')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tiene', 'The restaurant has a vegetarian menu.', ['El restaurante tiene un menú vegetariano.'], [Kit::word('el restaurante', 'restaurante'), Kit::word('el menú', 'menú'), Kit::word('vegetariano'), Kit::form('tiene', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta', 'Is Marta vegetarian?', ['¿Es Marta vegetariana?', '¿Marta es vegetariana?', '¿Es vegetariana Marta?'], [Kit::word('vegetariano', 'vegetariana'), Kit::form('es', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.trabaja', 'Luis ___ en el restaurante.', 'Luis works in the restaurant.', 'trabaja', Kit::form('trabaja'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estamos', 'Ana y yo ___ en el restaurante.', 'Ana and I are in the restaurant.', 'estamos', Kit::form('estamos', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tomo', 'Tomo algo para comer, gracias.', 'I am having something to eat, thank you.', [Kit::word('para comer'), Kit::form('tomo')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pago', 'Pago la cuenta, gracias.', 'I pay the bill, thank you.', [Kit::word('la cuenta')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.delicioso', 'Algo delicioso para beber.', 'Something delicious to drink.', [Kit::word('delicioso'), Kit::word('para beber')], 'dictation', $set),
        ];
    }
}
