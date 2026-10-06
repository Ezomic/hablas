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

final class AskingForDirections implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'asking-for-directions';
    }

    public function words(): array
    {
        return [
            new WordData('la calle', cue: 'street'),
            new WordData('la esquina', cue: 'corner (of a street)'),
            new WordData('a la derecha', cue: 'to the right'),
            new WordData('a la izquierda', cue: 'to the left'),
            new WordData('todo recto', cue: 'straight ahead', accepted: ['todo derecho']),
            new WordData('cerca', cue: 'near'),
            new WordData('lejos', cue: 'far'),
            new WordData('el mapa', cue: 'map'),
            new WordData('¿dónde está...?', cue: 'where is...? (asking for a place)'),
            new WordData('la plaza', cue: 'square (in a town)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Como en la plaza.', 'english' => 'I eat in the square.'],
            ['text' => 'Vivimos cerca.', 'english' => 'We live nearby.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: WordReference forum, SpanishDict, Kwiziq. todo recto is the Spain form and todo derecho is also valid, both accepted. No data fixes. Open question answered and removed.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.nosotros-plaza', 'Nosotros ___ en la plaza.', ['comemos', 'comes', 'como'], 'comemos', Kit::form('comemos'), 'Nosotros takes -emos in an -er verb.', 'choose', 'We eat in the square.'),
            Kit::gap($stage, 'sentences.choose_gap.vivimos-esquina', 'Nosotros ___ cerca de la esquina.', ['vivimos', 'vivo', 'vivís'], 'vivimos', Kit::form('vivimos', true), 'The verb is vivir, an -ir verb, so nosotros ends in -imos. Vivo is for yo and vivís is for vosotros.', 'choose', 'We live near the corner.'),
            Kit::gap($stage, 'sentences.choose_gap.ana-vive', 'Ana ___ lejos de la plaza.', ['vive', 'vives', 'vivo'], 'vive', Kit::form('vive'), 'Ana is a she, so the -ir verb ends in -e.', 'choose', 'Ana lives far from the square.'),
            Kit::gap($stage, 'sentences.choose_gap.cerca', 'Ana no vive lejos, vive ___.', ['cerca', 'lejos'], 'cerca', Kit::word('cerca'), 'Cerca means near, the opposite of lejos.', 'choose', null, ['vive' => 'lives']),
            Kit::gap($stage, 'sentences.choose_gap.donde-esquina', '¿Dónde ___ la esquina?', ['está', 'vive', 'tengo'], 'está', Kit::form('está', true), 'Where something is takes estar, which is not an -er or -ir verb.', 'choose', 'Where is the corner?'),
            Kit::gap($stage, 'sentences.choose_gap.derecha', 'La plaza está a la ___.', ['derecha', 'cerca', 'lejos'], 'derecha', Kit::word('a la derecha', 'derecha'), 'On the right is the fixed phrase a la derecha.', 'choose', 'The square is on the right.'),

            Kit::typeGap($stage, 'sentences.type_gap.vivimos-lejos', '___ lejos de la plaza.', 'We live far from the square.', 'Vivimos', Kit::form('vivimos'), 'With we and an -ir verb, the ending is -imos.'),
            Kit::typeGap($stage, 'sentences.type_gap.comes', 'Tú ___ en la plaza.', 'You eat in the square.', 'comes', Kit::form('comes'), 'With tú and an -er verb, the ending is -es.'),
            Kit::typeGap($stage, 'sentences.type_gap.viven', 'Ellos ___ cerca de la esquina.', 'They live near the corner.', 'viven', Kit::form('viven'), 'With they and an -ir verb, the ending is -en.'),
            Kit::typeGap($stage, 'sentences.type_gap.plaza-derecha', 'La plaza ___ a la derecha.', 'The square is to the right.', 'está', Kit::form('está', true), 'Where something is takes estar, not an -er or -ir verb.'),
            Kit::typeGap($stage, 'sentences.type_gap.tengo-mapa', '___ un mapa.', 'I have a map.', 'Tengo', Kit::form('tengo', true), 'Tener is irregular in the yo form: tengo, not teno.'),

            Kit::translate($stage, 'sentences.translate.vivimos-plaza', 'We live near the square.', ['Vivimos cerca de la plaza.', 'Nosotros vivimos cerca de la plaza.'], [Kit::word('cerca'), Kit::word('la plaza'), Kit::form('vivimos')]),
            Kit::translate($stage, 'sentences.translate.como-esquina', 'I eat at the corner.', ['Como en la esquina.', 'Yo como en la esquina.'], [Kit::word('la esquina'), Kit::form('como')]),
            Kit::translate($stage, 'sentences.translate.ana-lejos', 'Ana lives far away.', ['Ana vive lejos.'], [Kit::word('lejos'), Kit::form('vive')]),
            Kit::build($stage, 'sentences.build.donde-plaza', 'Where is the square?', '¿Dónde está la plaza?', ['es'], [Kit::word('¿dónde está...?', 'dónde está'), Kit::word('la plaza')]),
            Kit::build($stage, 'sentences.build.plaza-derecha', 'The square is to the right.', 'La plaza está a la derecha.', ['es'], [Kit::word('la plaza'), Kit::word('a la derecha'), Kit::form('está', true)]),
            Kit::build($stage, 'sentences.build.vivimos-lejos', 'We live far from the corner.', 'Vivimos lejos de la esquina.', ['comemos'], [Kit::word('lejos'), Kit::word('la esquina'), Kit::form('vivimos', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vivo-plaza', 'Vivo cerca de la plaza.', ['I live near the square.', 'I live far from the square.', 'We live near the square.', 'I eat in the square.'], 'I live near the square.', [Kit::word('cerca'), Kit::word('la plaza'), Kit::form('vivo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.mapa-aqui', 'El mapa está aquí.', ['The map is here.', 'The street is here.', 'The square is here.', 'I have the map.'], 'The map is here.', [Kit::word('el mapa'), Kit::form('está', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.esquina-izquierda', 'La esquina está a la izquierda.', ['The corner is on the left.', 'The corner is on the right.', 'The street is on the left.', 'The corner is near.'], 'The corner is on the left.', [Kit::word('la esquina'), Kit::word('a la izquierda'), Kit::form('está', true)]),
            Kit::listenType($stage, 'sentences.listen_type.calle-lejos', 'La calle está lejos.', 'The street is far.', [Kit::word('la calle'), Kit::word('lejos'), Kit::form('está', true)]),
            Kit::listenType($stage, 'sentences.listen_type.donde-mapa', '¿Dónde está el mapa?', 'Where is the map?', [Kit::word('el mapa'), Kit::word('¿dónde está...?', 'dónde está')]),
            Kit::listenType($stage, 'sentences.listen_type.coneis-plaza', 'Vosotros coméis en la plaza.', 'You (all) eat in the square.', [Kit::word('la plaza'), Kit::form('coméis')]),
            Kit::listenType($stage, 'sentences.listen_type.plaza-recto', 'La plaza está todo recto.', 'The square is straight ahead.', [Kit::word('la plaza'), Kit::word('todo recto'), Kit::form('está', true)]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.donde-plaza', '¿Dónde está la plaza? Está a la derecha.', 'Where is the square? It is to the right.', [Kit::word('¿dónde está...?', 'dónde está'), Kit::word('la plaza'), Kit::word('a la derecha'), Kit::form('está', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.vivimos-esquina', 'Vivimos cerca de la esquina.', 'We live near the corner.', [Kit::word('cerca'), Kit::word('la esquina'), Kit::form('vivimos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.calle-izquierda', 'La calle está a la izquierda.', 'The street is on the left.', [Kit::word('la calle'), Kit::word('a la izquierda'), Kit::form('está', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.esquina-recto', 'La esquina está todo recto.', 'The corner is straight ahead.', [Kit::word('la esquina'), Kit::word('todo recto'), Kit::form('está', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.plaza', '¿Dónde está la plaza?', 'Where is the square?', [['plaza', 'está'], ['derecha', 'izquierda', 'recto', 'cerca', 'lejos', 'aquí', 'allí', 'esquina']], 'La plaza está a la derecha.', [Kit::word('¿dónde está...?', 'dónde está'), Kit::word('la plaza'), Kit::form('está', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cerca-lejos', '¿Vives cerca o lejos?', 'Do you live near or far?', [['vivo', 'vive', 'vivimos', 'cerca', 'lejos']], 'Vivo cerca.', [Kit::word('cerca'), Kit::word('lejos'), Kit::form('vivo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.mapa', '¿Tienes un mapa?', 'Do you have a map?', [['sí', 'no', 'tengo', 'mapa']], 'Sí, tengo un mapa.', [Kit::word('el mapa', 'mapa'), Kit::form('tengo', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.plaza', 'Read the conversation in the street.', [
                Kit::line('Ana', 'Hola, Pablo. Tengo el mapa. ¿Dónde está la plaza?'),
                Kit::line('Pablo', 'La plaza no está lejos. Todo recto por la calle.'),
                Kit::line('Ana', '¿Y la esquina? ¿A la derecha o a la izquierda?'),
                Kit::line('Pablo', 'En la esquina, a la derecha. Yo vivo cerca de la plaza.'),
                Kit::line('Ana', 'Muy bien, gracias.'),
            ], [
                Kit::question('What does Ana have?', ['A map', 'A square', 'A corner'], 'A map'),
                Kit::question('Is the square far?', ['Yes, it is far.', 'No, it is not far.', 'The text does not say.'], 'No, it is not far.'),
                Kit::question('Which way does Pablo say at the corner?', ['To the left', 'To the right', 'Straight ahead'], 'To the right'),
            ], [Kit::word('el mapa'), Kit::word('¿dónde está...?', 'dónde está'), Kit::word('la plaza'), Kit::word('lejos'), Kit::word('todo recto'), Kit::word('la calle'), Kit::word('la esquina'), Kit::word('a la derecha'), Kit::word('a la izquierda'), Kit::word('cerca')], 'read'),
            Kit::gap($stage, 'task.choose_gap.vosotros', 'Vosotros ___ cerca de la plaza.', ['vivís', 'vivimos', 'vives'], 'vivís', Kit::form('vivís', true), 'The verb is vivir, an -ir verb, so vosotros ends in -ís. Vivimos is for nosotros and vives is for tú.', 'read', 'You (all) live near the square.'),
            Kit::gap($stage, 'task.choose_gap.recto', 'La esquina está todo ___.', ['recto', 'derecha'], 'recto', Kit::word('todo recto', 'recto'), 'Todo recto is a fixed phrase, so recto never changes.', 'read'),

            Kit::transform($stage, 'task.transform.nosotros-come', 'Change to nosotros.', 'Él come a las dos.', ['Nosotros comemos a las dos.', 'Comemos a las dos.'], [Kit::form('comemos')], ['come' => 'eats']),
            Kit::transform($stage, 'task.transform.nosotros-vive', 'Change to nosotros.', 'Pablo vive lejos de la plaza.', ['Nosotros vivimos lejos de la plaza.', 'Vivimos lejos de la plaza.'], [Kit::word('la plaza'), Kit::word('lejos'), Kit::form('vivimos')]),
            Kit::transform($stage, 'task.transform.tu-vives', 'Change to tú.', 'Yo vivo cerca de la esquina.', ['Tú vives cerca de la esquina.', 'Vives cerca de la esquina.'], [Kit::word('cerca'), Kit::word('la esquina'), Kit::form('vives')]),
            Kit::writeGuided($stage, 'task.write_guided.vivo', 'Say that you live far from the square.', ['vivo', 'lejos', 'plaza'], 'Vivo lejos de la plaza.', [
                ['forms' => ['vivo'], 'term' => null],
                ['forms' => ['lejos'], 'term' => 'lejos'],
                ['forms' => ['plaza'], 'term' => 'la plaza'],
            ], [Kit::word('lejos'), Kit::word('la plaza'), Kit::form('vivo')]),
            Kit::writeGuided($stage, 'task.write_guided.donde', 'Ask where the corner is and say that the square is on the left.', ['dónde', 'esquina', 'plaza', 'izquierda'], '¿Dónde está la esquina? La plaza está a la izquierda.', [
                ['forms' => ['dónde'], 'term' => '¿dónde está...?'],
                ['forms' => ['esquina'], 'term' => 'la esquina'],
                ['forms' => ['plaza'], 'term' => 'la plaza'],
                ['forms' => ['izquierda'], 'term' => 'a la izquierda'],
            ], [Kit::word('¿dónde está...?', 'dónde'), Kit::word('la esquina'), Kit::word('la plaza'), Kit::word('a la izquierda')]),
            Kit::build($stage, 'task.build.mapa-derecha', 'The map is on the right.', 'El mapa está a la derecha.', ['es', 'hay'], [Kit::word('el mapa'), Kit::word('a la derecha'), Kit::form('está', true)], 'write'),
            Kit::build($stage, 'task.build.comemos-esquina', 'We eat near the corner.', 'Comemos cerca de la esquina.', ['vivimos', 'comes'], [Kit::word('cerca'), Kit::word('la esquina'), Kit::form('comemos')], 'write'),
            Kit::build($stage, 'task.build.vives-plaza', 'You live far from the square.', 'Vives lejos de la plaza.', ['vivís', 'comes'], [Kit::word('lejos'), Kit::word('la plaza'), Kit::form('vives')], 'write'),
            Kit::translate($stage, 'task.translate.vives-esquina', 'Do you live near the corner?', ['¿Vives cerca de la esquina?', '¿Tú vives cerca de la esquina?', '¿Vive usted cerca de la esquina?'], [Kit::word('cerca'), Kit::word('la esquina')]),
            Kit::translate($stage, 'task.translate.plaza-recto', 'The square is straight ahead, near the corner.', ['La plaza está todo recto, cerca de la esquina.', 'La plaza está cerca de la esquina, todo recto.'], [Kit::word('la plaza'), Kit::word('todo recto'), Kit::word('cerca'), Kit::word('la esquina'), Kit::form('está', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.plaza', [
                Kit::line('Luis', 'Hola, Marta. ¿Dónde está la plaza?'),
                Kit::line('Marta', 'Está cerca. Todo recto y en la esquina, a la izquierda.'),
                Kit::line('Luis', '¿Está lejos?'),
                Kit::line('Marta', 'No, no está lejos. Yo como en la plaza.'),
                Kit::line('Luis', 'Muchas gracias.'),
            ], [
                Kit::question('What does Luis ask about?', ['The square', 'The map', 'The street'], 'The square'),
                Kit::question('Which way does Marta say at the corner?', ['To the right', 'To the left', 'Marta does not say.'], 'To the left'),
                Kit::question('Is it far?', ['Yes', 'No', 'Marta does not say.'], 'No'),
            ], [
                Kit::question('Who asks the first question?', ['Luis', 'Marta', 'Pablo'], 'Luis'),
                Kit::question('What does Marta do in the square?', ['She eats there.', 'She lives there.', 'She works there.'], 'She eats there.'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('¿dónde está...?', 'dónde está'), Kit::word('la plaza'), Kit::word('cerca'), Kit::word('todo recto'), Kit::word('la esquina'), Kit::word('a la izquierda'), Kit::word('lejos')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.mapa-derecha', 'El mapa está a la derecha.', 'The map is on the right.', [Kit::word('el mapa'), Kit::word('a la derecha'), Kit::form('está', true)], 'listen', null, [], 'The a in a la derecha is the preposition a, never ha.'),
            Kit::listenType($stage, 'task.listen_type.vives-esquina', 'Vives lejos de la esquina.', 'You live far from the corner.', [Kit::word('lejos'), Kit::word('la esquina'), Kit::form('vives')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.calle-esquina', 'La calle está cerca de la esquina.', 'The street is near the corner.', [Kit::word('cerca'), Kit::word('la calle'), Kit::word('la esquina'), Kit::form('está', true)], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.plaza-lejos', '¿Vives lejos de la plaza?', 'Do you live far from the square?', [['sí', 'no', 'vivo', 'lejos', 'cerca', 'plaza']], 'No, vivo cerca de la plaza.', [Kit::word('lejos'), Kit::word('cerca'), Kit::word('la plaza'), Kit::form('vivo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.donde-comes', '¿Dónde comes?', 'Where do you eat?', [['como', 'comemos', 'en'], ['plaza', 'calle', 'esquina', 'aquí', 'allí']], 'Como en la plaza.', [Kit::word('la plaza'), Kit::form('como')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.esquina', '¿Dónde está la esquina?', 'Where is the corner?', [['esquina', 'está'], ['derecha', 'izquierda', 'recto', 'cerca', 'lejos', 'aquí', 'allí']], 'La esquina está a la derecha.', [Kit::word('la esquina'), Kit::word('¿dónde está...?', 'dónde está'), Kit::word('a la derecha')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mapa', '¿Dónde tienes el mapa?', 'Where do you have the map?', [['tengo', 'mapa', 'está'], ['aquí', 'allí', 'calle', 'plaza', 'esquina']], 'Tengo el mapa aquí.', [Kit::word('el mapa'), Kit::form('tengo', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.recto-derecha', 'Todo recto y a la derecha, cerca de la plaza.', 'Straight ahead and to the right, near the square.', [Kit::word('todo recto'), Kit::word('a la derecha'), Kit::word('cerca'), Kit::word('la plaza')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.vivimos-comemos', 'Vivimos lejos de la esquina y comemos en la plaza.', 'We live far from the corner and eat in the square.', [Kit::word('lejos'), Kit::word('la esquina'), Kit::word('la plaza'), Kit::form('vivimos')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.calle-lejos', 'The street is far from the square.', ['La calle está lejos de la plaza.'], [Kit::word('la calle'), Kit::word('lejos'), Kit::word('la plaza'), Kit::form('está', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-izquierda', 'Ana lives near the square, on the left.', ['Ana vive cerca de la plaza, a la izquierda.'], [Kit::word('cerca'), Kit::word('la plaza'), Kit::word('a la izquierda'), Kit::form('vive')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.donde-calle', 'Where is the street? It is straight ahead.', ['¿Dónde está la calle? Está todo recto.', '¿Dónde está la calle? La calle está todo recto.'], [Kit::word('¿dónde está...?', 'dónde está'), Kit::word('la calle'), Kit::word('todo recto'), Kit::form('está', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.mapa-lejos', 'I have a map, but I live far away.', ['Tengo un mapa, pero vivo lejos.', 'Yo tengo un mapa, pero vivo lejos.'], [Kit::word('el mapa', 'mapa'), Kit::word('lejos'), Kit::form('tengo', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vosotros', 'Vosotros ___ cerca de la esquina.', 'You (all) live near the corner.', 'vivís', Kit::form('vivís', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ellos', 'Ellos ___ lejos de la plaza.', 'They live far from the square.', 'viven', Kit::form('viven'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-derecha', 'Marta vive a la derecha de la plaza.', 'Marta lives to the right of the square.', [Kit::word('a la derecha'), Kit::word('la plaza')], 'dictation', $set, [], 'The a in a la derecha is the preposition a, never ha.'),
            Kit::listenType($stage, 'check.a.listen_type.esquina-cerca', 'La esquina está cerca.', 'The corner is near.', [Kit::word('la esquina'), Kit::word('cerca')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.plaza-lejos', 'La plaza está lejos de la esquina.', 'The square is far from the corner.', [Kit::word('la plaza'), Kit::word('lejos'), Kit::word('la esquina')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.marta', [
                Kit::line('Pablo', 'Hola, Marta. ¿Vives cerca de la plaza?'),
                Kit::line('Marta', 'No, vivo lejos. Vivo en la calle a la derecha de la plaza.'),
                Kit::line('Pablo', 'Yo vivo cerca. Tengo el mapa aquí.'),
                Kit::line('Marta', 'Muy bien. Adiós, Pablo.'),
            ], [
                Kit::question('Does Marta live near the square?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('On which side of the square does Marta live?', ['On the left', 'On the right', 'The conversation does not say.'], 'On the right'),
                Kit::question('What does Pablo have?', ['A map', 'A square', 'A corner'], 'A map'),
            ], [
                Kit::question('Who asks the first question?', ['Pablo', 'Marta', 'Luis'], 'Pablo'),
                Kit::question('Does Pablo live near the square?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How does the conversation end?', ['Marta says goodbye.', 'Pablo says thank you.', 'Marta asks a question.'], 'Marta says goodbye.'),
            ], [Kit::word('la plaza'), Kit::word('cerca'), Kit::word('lejos'), Kit::word('la calle'), Kit::word('a la derecha'), Kit::word('el mapa')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.luis', 'Read the conversation.', [
                Kit::line('Luis', 'Buenas tardes, Ana. ¿Tienes el mapa?'),
                Kit::line('Ana', 'Sí, tengo el mapa. La plaza está a la izquierda.'),
                Kit::line('Luis', '¿Está lejos?'),
                Kit::line('Ana', 'No, está cerca. Todo recto y a la izquierda.'),
            ], [
                Kit::question('Does Ana have the map?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Is the square far?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('el mapa'), Kit::word('la plaza'), Kit::word('a la izquierda'), Kit::word('lejos'), Kit::word('cerca'), Kit::word('todo recto')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.comes-esquina', '¿Comes lejos de la esquina?', 'Do you eat far from the corner?', [['sí', 'no', 'como', 'lejos', 'cerca', 'esquina']], 'No, como cerca de la esquina.', [Kit::word('lejos'), Kit::word('la esquina')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.comes-cerca', '¿Comes cerca de la plaza?', 'Do you eat near the square?', [['sí', 'no', 'como', 'cerca', 'lejos', 'plaza']], 'Sí, como cerca de la plaza.', [Kit::word('cerca'), Kit::word('la plaza')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.calle-derecha', '¿La calle está a la derecha o a la izquierda?', 'Is the street on the right or on the left?', [['derecha', 'izquierda']], 'La calle está a la izquierda.', [Kit::word('la calle'), Kit::word('a la derecha'), Kit::word('a la izquierda')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.plaza-esquina', 'The square is near the corner.', ['La plaza está cerca de la esquina.'], [Kit::word('la plaza'), Kit::word('cerca'), Kit::word('la esquina'), Kit::form('está', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.comemos-lejos', 'We eat far from the square.', ['Comemos lejos de la plaza.', 'Nosotros comemos lejos de la plaza.'], [Kit::word('lejos'), Kit::word('la plaza'), Kit::form('comemos')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.donde-calle', 'Where is the street? It is to the left.', ['¿Dónde está la calle? Está a la izquierda.'], [Kit::word('¿dónde está...?', 'dónde está'), Kit::word('la calle'), Kit::word('a la izquierda'), Kit::form('está', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pablo-mapa', 'Pablo has a map of the street.', ['Pablo tiene un mapa de la calle.'], [Kit::word('el mapa', 'mapa'), Kit::word('la calle')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.nosotros', 'Nosotros ___ cerca de la plaza.', 'We live near the square.', 'vivimos', Kit::form('vivimos', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.tu', 'Tú ___ lejos de la esquina.', 'You live far from the corner.', 'vives', Kit::form('vives'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.mapa-plaza', 'Tengo un mapa de la plaza.', 'I have a map of the square.', [Kit::word('el mapa', 'mapa'), Kit::word('la plaza'), Kit::form('tengo', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.marta-lejos', 'Marta vive lejos de la esquina.', 'Marta lives far from the corner.', [Kit::word('lejos'), Kit::word('la esquina')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.recto-derecha', 'Todo recto y la plaza está a la derecha.', 'Straight ahead, and the square is to the right.', [Kit::word('todo recto'), Kit::word('la plaza'), Kit::word('a la derecha')], 'dictation', $set, [], 'The a in a la derecha is the preposition a, never ha.'),
        ];
    }
}
