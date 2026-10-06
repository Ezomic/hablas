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

final class YourPersonalDetails implements UnitContent
{
    private const NUMBERS = ['uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve', 'veinte'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'your-personal-details';
    }

    public function words(): array
    {
        return [
            new WordData('el año', cue: 'year', forms: ['años'], note: 'You give your age in años: tengo veinte años.'),
            new WordData('el país', cue: 'country'),
            new WordData('Holanda', cue: 'Holland (the Netherlands)'),
            new WordData('España', cue: 'Spain'),
            new WordData('holandés', cue: 'Dutch (nationality)', forms: ['holandesa'], note: 'Holandés is for a man, holandesa for a woman.'),
            new WordData('el teléfono', cue: 'telephone, phone'),
            new WordData('el número', cue: 'number'),
            new WordData('la dirección', cue: 'address'),
            new WordData('el apellido', cue: 'surname, last name'),
            new WordData('deletrear', cue: 'to spell', forms: ['deletrea'], note: 'Deletrea is the informal command: deletrea tu apellido, por favor.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Tengo veinte años.', 'english' => 'I am twenty years old.'],
            ['text' => 'Soy de Holanda.', 'english' => 'I am from Holland.'],
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
            Kit::gap($stage, 'sentences.choose_gap.anos', 'Tengo doce ___.', ['años', 'país'], 'años', Kit::word('el año', 'años'), 'You count your age in años (years): tengo doce años. País means country.', 'choose', 'I am twelve years old.'),
            Kit::gap($stage, 'sentences.choose_gap.tengo', '___ diez años.', ['Tengo', 'Soy'], 'Tengo', Kit::form('tengo', true), 'In Spanish you have your age: tengo diez años. Soy diez años would be like saying I am ten years, which Spanish does not say.', 'choose', 'I am ten years old.'),
            Kit::gap($stage, 'sentences.choose_gap.tienes', 'Ana, ¿cuántos años ___?', ['tienes', 'tengo'], 'tienes', Kit::form('tienes', true), 'You are asking Ana, so you use tienes (you have). Tengo is for talking about yourself.', 'choose', 'Ana, how old are you? (informal you)'),
            Kit::gap($stage, 'sentences.choose_gap.soy-de', '___ Holanda.', ['Soy de', 'Estoy de'], 'Soy de', Kit::form('soy de', true), 'To say where you are from, use ser with de: soy de Holanda. Estoy is for where you are now or how you feel, so estoy de Holanda is wrong.', 'choose', 'I am from Holland.'),
            Kit::gap($stage, 'sentences.choose_gap.espana', 'Pablo es de ___.', ['España', 'dirección', 'año'], 'España', Kit::word('España'), 'España is a country, so it fits after es de. Dirección is address and año is year.', 'choose', 'Pablo is from Spain.'),
            Kit::gap($stage, 'sentences.choose_gap.holandesa', 'Ana es ___.', ['holandesa', 'holandés'], 'holandesa', Kit::word('holandés', 'holandesa'), 'Ana is a woman, so the nationality ends in -a: holandesa. Holandés is for a man.', 'choose', 'Ana is Dutch.'),

            Kit::typeGap($stage, 'sentences.type_gap.anos', 'Tengo quince ___.', 'I am fifteen years old.', 'años', Kit::word('el año', 'años')),
            Kit::typeGap($stage, 'sentences.type_gap.pablo-tienes', 'Pablo, ¿cuántos años ___?', 'Pablo, how old are you? (informal you)', 'tienes', Kit::form('tienes'), 'You are talking to Pablo, so you use tienes. Age takes tener in Spanish.'),
            Kit::typeGap($stage, 'sentences.type_gap.luis-eres', 'Luis, ¿de dónde ___?', 'Luis, where are you from? (informal you)', 'eres', Kit::form('eres'), 'Eres is the tú form of ser, the verb you use for where you are from.'),
            Kit::typeGap($stage, 'sentences.type_gap.soy', '___ de España.', 'I am from Spain.', 'Soy', Kit::form('soy'), 'Where you are from takes ser: soy de España. Tengo would mean I have.'),
            Kit::typeGap($stage, 'sentences.type_gap.numero', 'Tu ___ de teléfono, por favor.', 'Your phone number, please. (informal you)', 'número', Kit::word('el número', 'número')),

            Kit::translate($stage, 'sentences.translate.veinte', 'I am twenty years old.', ['Tengo veinte años.', 'Yo tengo veinte años.'], [Kit::word('el año', 'años'), Kit::form('tengo')]),
            Kit::translate($stage, 'sentences.translate.de-donde', 'Where are you from? (informal you)', ['¿De dónde eres?', '¿De dónde eres tú?', '¿Tú de dónde eres?'], [Kit::form('eres')]),
            Kit::translate($stage, 'sentences.translate.apellido', 'Spell your surname, please. (informal you)', ['Deletrea tu apellido, por favor.', 'Por favor, deletrea tu apellido.'], [Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido')]),

            Kit::build($stage, 'sentences.build.soy-de', 'I am from Holland.', 'Soy de Holanda.', ['tengo'], [Kit::word('Holanda'), Kit::form('soy de')]),
            Kit::build($stage, 'sentences.build.cuantos', 'How old are you? (informal you)', '¿Cuántos años tienes?', ['tengo'], [Kit::word('el año', 'años'), Kit::form('tienes')]),
            Kit::build($stage, 'sentences.build.direccion', 'What is your address? (informal you)', '¿Cuál es tu dirección?', ['eres'], [Kit::word('la dirección', 'dirección')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.veinte', 'Tengo veinte años.', ['I am twenty years old.', 'I am twelve years old.', 'You are twenty years old.', 'I am from Spain.'], 'I am twenty years old.', [Kit::word('el año', 'años'), Kit::form('tengo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.de-donde', '¿De dónde eres?', ['Where are you from?', 'Where are you?', 'How old are you?', 'What is your surname?'], 'Where are you from?', [Kit::form('eres')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.telefono', 'Mi teléfono es el cinco, siete, tres.', ['My phone number is five, seven, three.', 'My phone number is five, seven, two.', 'My phone number is six, seven, three.', 'My address is number five.'], 'My phone number is five, seven, three.', [Kit::word('el teléfono', 'teléfono')]),
            Kit::listenType($stage, 'sentences.listen_type.numero', 'Mi número es el cinco, siete, tres.', 'My number is five, seven, three.', [Kit::word('el número', 'número')]),
            Kit::listenType($stage, 'sentences.listen_type.holandesa', 'Ana es holandesa.', 'Ana is Dutch.', [Kit::word('holandés', 'holandesa')]),
            Kit::listenType($stage, 'sentences.listen_type.apellido', 'Por favor, deletrea tu apellido.', 'Please spell your surname. (informal you)', [Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido')]),
            Kit::listenType($stage, 'sentences.listen_type.ana-tiene', 'Ana tiene quince años.', 'Ana is fifteen years old.', [Kit::word('el año', 'años'), Kit::form('tiene')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.tengo', 'Tengo veinte años.', 'I am twenty years old.', [Kit::word('el año', 'años'), Kit::form('tengo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ana', 'Ana es de Holanda.', 'Ana is from Holland.', [Kit::word('Holanda'), Kit::form('es de')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.direccion', 'Mi dirección y mi teléfono, por favor.', 'My address and my phone number, please.', [Kit::word('la dirección', 'dirección'), Kit::word('el teléfono', 'teléfono')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pais', 'Mi país es España.', 'My country is Spain.', [Kit::word('el país', 'país'), Kit::word('España')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.anos', '¿Cuántos años tienes?', 'How old are you?', [['tengo', 'años'], self::NUMBERS], 'Tengo veinte años.', [Kit::word('el año', 'años')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.de-donde', '¿De dónde eres?', 'Where are you from?', [['soy', 'de'], ['holanda', 'españa', 'holandés', 'holandesa']], 'Soy de Holanda.', [Kit::word('Holanda')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pais', '¿Cuál es tu país?', 'What is your country?', [['mi', 'es', 'soy'], ['holanda', 'españa']], 'Mi país es Holanda.', [Kit::word('el país', 'país'), Kit::word('Holanda')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.datos', 'Read the conversation.', [
                Kit::line('Marta', 'Hola, Luis. ¿Cuál es tu país?'),
                Kit::line('Luis', 'Mi país es Holanda. Soy holandés.'),
                Kit::line('Marta', 'Yo soy de España. ¿Cuántos años tienes?'),
                Kit::line('Luis', 'Tengo veinte años. ¿Y tú?'),
                Kit::line('Marta', 'Tengo dieciocho años. ¿Cuál es tu dirección?'),
                Kit::line('Luis', 'Mi dirección es el número doce. ¿Y tu teléfono?'),
                Kit::line('Marta', 'Mi teléfono es el cinco, siete, tres.'),
            ], [
                Kit::question('Where is Luis from?', ['Holland', 'Spain', 'The text does not say.'], 'Holland'),
                Kit::question('How old is Marta?', ['Eighteen', 'Twenty', 'Twelve'], 'Eighteen'),
                Kit::question('What is Marta\'s phone number?', ['Five, seven, three', 'Five, seven, two', 'Twelve'], 'Five, seven, three'),
            ], [Kit::word('el país', 'país'), Kit::word('Holanda'), Kit::word('holandés'), Kit::word('España'), Kit::word('el año', 'años'), Kit::word('la dirección', 'dirección'), Kit::word('el número', 'número'), Kit::word('el teléfono', 'teléfono')], 'read'),
            Kit::gap($stage, 'task.choose_gap.marta-tiene', 'Marta ___ quince años.', ['tiene', 'es'], 'tiene', Kit::form('tiene', true), 'Age takes tener, so Marta tiene quince años. Es quince años would be like saying she is fifteen years, which Spanish does not say.', 'read', 'Marta is fifteen years old.'),
            Kit::gap($stage, 'task.choose_gap.direccion', 'Mi ___ es el número doce.', ['dirección', 'país', 'apellido'], 'dirección', Kit::word('la dirección', 'dirección'), 'An address has a number, so dirección fits. País is country and apellido is surname.', 'read', 'My address is number twelve.'),

            Kit::transform($stage, 'task.transform.pregunta', 'Now ask a friend how old they are (informal you).', 'Tengo veinte años.', ['¿Cuántos años tienes?', '¿Cuántos años tienes tú?', '¿Tú cuántos años tienes?'], [Kit::word('el año', 'años'), Kit::form('tienes')]),
            Kit::transform($stage, 'task.transform.ana', 'Now say it about Ana.', 'Soy de Holanda.', ['Ana es de Holanda.', 'Es de Holanda.', 'Ella es de Holanda.'], [Kit::word('Holanda'), Kit::form('es de', true)]),
            Kit::transform($stage, 'task.transform.pablo', 'Now say it about Pablo.', 'Tengo quince años.', ['Pablo tiene quince años.', 'Tiene quince años.', 'Él tiene quince años.'], [Kit::word('el año', 'años'), Kit::form('tiene', true)]),
            Kit::writeGuided($stage, 'task.write_guided.edad', 'Say that you are twenty years old and that you are from Holland.', ['tengo', 'años', 'soy de', 'Holanda'], 'Tengo veinte años. Soy de Holanda.', [
                ['forms' => ['tengo'], 'term' => null],
                ['forms' => ['años'], 'term' => 'el año'],
                ['forms' => ['holanda'], 'term' => 'Holanda'],
            ], [Kit::word('el año', 'años'), Kit::word('Holanda'), Kit::form('tengo')]),
            Kit::writeGuided($stage, 'task.write_guided.apellido', 'Ask Pablo to spell his surname, please (informal you).', ['deletrea', 'tu apellido', 'por favor'], 'Pablo, deletrea tu apellido, por favor.', [
                ['forms' => ['deletrea'], 'term' => 'deletrear'],
                ['forms' => ['apellido'], 'term' => 'el apellido'],
            ], [Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido')]),
            Kit::build($stage, 'task.build.telefono', 'What is your phone number? (informal you)', '¿Cuál es tu número de teléfono?', ['eres', 'tengo'], [Kit::word('el número', 'número'), Kit::word('el teléfono', 'teléfono')], 'write'),
            Kit::build($stage, 'task.build.ana-holanda', 'Ana is from Holland.', 'Ana es de Holanda.', ['tiene', 'años'], [Kit::word('Holanda'), Kit::form('es de')], 'write'),
            Kit::build($stage, 'task.build.pablo', 'How old is Pablo?', '¿Cuántos años tiene Pablo?', ['soy', 'de'], [Kit::word('el año', 'años'), Kit::form('tiene')], 'write'),
            Kit::translate($stage, 'task.translate.holanda-espana', 'I am from Holland, but Marta is from Spain.', ['Soy de Holanda, pero Marta es de España.', 'Yo soy de Holanda, pero Marta es de España.'], [Kit::word('Holanda'), Kit::word('España'), Kit::form('soy de')], 'write'),
            Kit::translate($stage, 'task.translate.telefono', 'My phone number is eight, nine, two.', ['Mi número de teléfono es el ocho, nueve, dos.', 'Mi teléfono es el ocho, nueve, dos.'], [Kit::word('el teléfono', 'teléfono')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.datos', [
                Kit::line('Pablo', 'Hola, Ana. ¿De dónde eres?'),
                Kit::line('Ana', 'Soy holandesa. Mi país es Holanda.'),
                Kit::line('Pablo', 'Yo soy de España. ¿Cuántos años tienes?'),
                Kit::line('Ana', 'Tengo quince años.'),
                Kit::line('Pablo', '¿Cuál es tu número de teléfono?'),
                Kit::line('Ana', 'Mi teléfono es el seis, dos, cuatro.'),
            ], [
                Kit::question('Where is Ana from?', ['Holland', 'Spain', 'The conversation does not say.'], 'Holland'),
                Kit::question('How old is Ana?', ['Fifteen', 'Twelve', 'Twenty'], 'Fifteen'),
                Kit::question('What does Pablo ask for last?', ['Ana\'s phone number', 'Ana\'s address', 'Ana\'s surname'], 'Ana\'s phone number'),
            ], [
                Kit::question('Where is Pablo from?', ['Spain', 'Holland', 'The conversation does not say.'], 'Spain'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Is Ana Dutch?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('el país', 'país'), Kit::word('Holanda'), Kit::word('holandés', 'holandesa'), Kit::word('España'), Kit::word('el año', 'años'), Kit::word('el número', 'número'), Kit::word('el teléfono', 'teléfono')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.marta', 'Marta tiene diecisiete años.', 'Marta is seventeen years old.', [Kit::word('el año', 'años'), Kit::form('tiene')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.apellido', 'Pablo, ¿cuál es tu apellido? Deletrea, por favor.', 'Pablo, what is your surname? Spell it, please. (informal you)', [Kit::word('el apellido', 'apellido'), Kit::word('deletrear', 'deletrea')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pais-direccion', 'Mi país es España y mi dirección es el número ocho.', 'My country is Spain and my address is number eight.', [Kit::word('el país', 'país'), Kit::word('España'), Kit::word('la dirección', 'dirección'), Kit::word('el número', 'número')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.telefono', '¿Cuál es tu teléfono?', 'What is your phone number?', [['mi', 'es'], self::NUMBERS], 'Mi teléfono es el cinco, siete, tres.', [Kit::word('el teléfono', 'teléfono')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.direccion', '¿Cuál es tu dirección?', 'What is your address?', [['mi', 'es'], ['número', ...self::NUMBERS]], 'Mi dirección es el número ocho.', [Kit::word('la dirección', 'dirección'), Kit::word('el número', 'número')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.holandes', '¿Eres holandés?', 'Are you Dutch?', [['sí', 'no'], ['soy', 'holandés', 'holandesa']], 'Sí, soy holandés.', [Kit::word('holandés')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.espana', '¿Eres de España?', 'Are you from Spain?', [['sí', 'no'], ['soy', 'españa', 'holanda']], 'No, soy de Holanda.', [Kit::word('España'), Kit::word('Holanda')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.apellido', 'Pablo, deletrea tu apellido, por favor.', 'Pablo, spell your surname, please.', [Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.anos-holanda', 'Tengo veinte años y soy de Holanda.', 'I am twenty years old and I am from Holland.', [Kit::word('el año', 'años'), Kit::word('Holanda')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.diecinueve', 'I am nineteen years old.', ['Tengo diecinueve años.', 'Yo tengo diecinueve años.'], [Kit::word('el año', 'años'), Kit::form('tengo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-tienes', 'Marta, how old are you? (informal you)', ['Marta, ¿cuántos años tienes?', '¿Cuántos años tienes, Marta?'], [Kit::word('el año', 'años'), Kit::form('tienes')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo-espana', 'Pablo is from Spain, but I am from Holland.', ['Pablo es de España, pero soy de Holanda.', 'Pablo es de España, pero yo soy de Holanda.'], [Kit::word('España'), Kit::word('Holanda'), Kit::form('es de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.luis-apellido', 'Luis, spell your surname, please. (informal you)', ['Luis, deletrea tu apellido, por favor.', 'Por favor, Luis, deletrea tu apellido.', 'Deletrea tu apellido, por favor, Luis.'], [Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.direccion', 'Mi ___ es el número nueve.', 'My address is number nine.', 'dirección', Kit::word('la dirección', 'dirección'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luis-tiene', 'Luis ___ diecisiete años.', 'Luis is seventeen years old.', 'tiene', Kit::form('tiene', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.holandes', 'Soy holandés y tengo veinte años.', 'I am Dutch and I am twenty years old.', [Kit::word('holandés'), Kit::word('el año', 'años'), Kit::form('tengo')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.pais', 'Mi país es España, no Holanda.', 'My country is Spain, not Holland.', [Kit::word('el país', 'país'), Kit::word('España'), Kit::word('Holanda')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.telefono', 'Soy de España. Mi número de teléfono es el seis.', 'I am from Spain. My phone number is six.', [Kit::word('España'), Kit::word('el número', 'número'), Kit::word('el teléfono', 'teléfono'), Kit::form('soy de')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.holandesa', [
                Kit::line('Luis', 'Hola, Marta. ¿Eres holandesa?'),
                Kit::line('Marta', 'No, soy de España. ¿Y tú?'),
                Kit::line('Luis', 'Yo soy holandés. ¿Y tu teléfono, Marta?'),
                Kit::line('Marta', 'Mi número de teléfono es el nueve, dos, tres.'),
            ], [
                Kit::question('Is Marta Dutch?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('Where is Marta from?', ['Spain', 'Holland', 'The conversation does not say.'], 'Spain'),
                Kit::question('What does Luis ask for?', ['A phone number', 'An address', 'A surname'], 'A phone number'),
            ], [
                Kit::question('Who is Dutch?', ['Luis', 'Marta', 'Both'], 'Luis'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Does Marta say her phone number?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('holandés', 'holandesa'), Kit::word('el teléfono', 'teléfono'), Kit::word('el número', 'número'), Kit::word('España')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.direccion', 'Read the conversation.', [
                Kit::line('Pablo', 'Hola, Ana. ¿Tu dirección, por favor?'),
                Kit::line('Ana', 'Mi dirección es el número ocho.'),
                Kit::line('Pablo', 'Ana, deletrea tu apellido, por favor.'),
                Kit::line('Ana', 'Sí, Pablo.'),
            ], [
                Kit::question('What does Pablo ask first?', ['Her address', 'Her age', 'Her phone number'], 'Her address'),
                Kit::question('What number is Ana\'s address?', ['Eight', 'Six', 'Twelve'], 'Eight'),
            ], [Kit::word('la dirección', 'dirección'), Kit::word('el número', 'número'), Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ana-anos', '¿Cuántos años tienes, Ana?', 'How old are you, Ana?', [['tengo', 'años'], self::NUMBERS], 'Tengo dieciocho años.', [Kit::word('el año', 'años')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pablo-de-donde', 'Pablo, ¿de dónde eres?', 'Pablo, where are you from?', [['soy', 'de'], ['españa', 'holanda', 'holandés', 'holandesa']], 'Soy de España.', [Kit::word('España'), Kit::form('soy de')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.y-pais', '¿Y cuál es tu país?', 'And what is your country?', [['mi', 'es', 'soy'], ['holanda', 'españa']], 'Mi país es Holanda.', [Kit::word('el país', 'país'), Kit::word('Holanda')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.once', 'I am eleven years old and I am from Spain.', ['Tengo once años y soy de España.', 'Yo tengo once años y soy de España.', 'Soy de España y tengo once años.'], [Kit::word('el año', 'años'), Kit::word('España'), Kit::form('tengo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.direccion-telefono', 'My address is number six. My phone number is five.', ['Mi dirección es el número seis. Mi número de teléfono es el cinco.', 'Mi dirección es el número seis. Mi teléfono es el cinco.'], [Kit::word('la dirección', 'dirección'), Kit::word('el número', 'número'), Kit::word('el teléfono', 'teléfono')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-holandesa', 'Ana is Dutch and her country is Holland.', ['Ana es holandesa y su país es Holanda.'], [Kit::word('holandés', 'holandesa'), Kit::word('el país', 'país'), Kit::word('Holanda'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-apellido', 'Marta, spell your surname, please. (informal you)', ['Marta, deletrea tu apellido, por favor.', 'Por favor, Marta, deletrea tu apellido.', 'Deletrea tu apellido, por favor, Marta.'], [Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luis-tienes', 'Luis, ¿cuántos años ___?', 'Luis, how old are you? (informal you)', 'tienes', Kit::form('tienes', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-es', 'Marta ___ de Holanda.', 'Marta is from Holland.', 'es', Kit::form('es'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.holandes', 'Soy holandés y mi país es Holanda. Luis es de España.', 'I am Dutch and my country is Holland. Luis is from Spain.', [Kit::word('holandés'), Kit::word('el país', 'país'), Kit::word('Holanda'), Kit::word('España'), Kit::form('es de')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.apellido', 'Luis, deletrea tu apellido y tu dirección, por favor.', 'Luis, spell your surname and your address, please. (informal you)', [Kit::word('deletrear', 'deletrea'), Kit::word('el apellido', 'apellido'), Kit::word('la dirección', 'dirección')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.doce', 'Tengo doce años. Mi número de teléfono es el cinco.', 'I am twelve years old. My phone number is five.', [Kit::word('el año', 'años'), Kit::word('el número', 'número'), Kit::word('el teléfono', 'teléfono'), Kit::form('tengo')], 'dictation', $set),
        ];
    }
}
