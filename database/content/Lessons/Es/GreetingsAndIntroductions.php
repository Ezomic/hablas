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

final class GreetingsAndIntroductions implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'greetings-and-introductions';
    }

    public function words(): array
    {
        return [
            new WordData('hola', cue: 'hello'),
            new WordData('buenos días', cue: 'good morning'),
            new WordData('buenas tardes', cue: 'good afternoon'),
            new WordData('buenas noches', cue: 'good evening or good night (greeting after dark)'),
            new WordData('adiós', cue: 'goodbye'),
            new WordData('me llamo', cue: 'my name is (introducing yourself)', accepted: ['mi nombre es', 'yo me llamo']),
            new WordData('mucho gusto', cue: 'nice to meet you', accepted: ['encantado', 'encantada', 'encantado de conocerte', 'encantada de conocerte', 'mucho gusto en conocerte']),
            new WordData('¿cómo estás?', cue: 'how are you?', accepted: ['¿cómo está?', '¿qué tal?', '¿qué tal estás?']),
            new WordData('bien', cue: 'well, fine (as in I am fine)', accepted: ['estoy bien']),
            new WordData('gracias', cue: 'thank you', accepted: ['muchas gracias']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Soy Ana.', 'english' => 'I am Ana.'],
            ['text' => 'Ella es mi amiga.', 'english' => 'She is my friend.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), WordReference forum, SpanishDict, hinative. Fixed: cue for hola no longer says informal; accepted yo me llamo, encantado de conocerte (m/f), mucho gusto en conocerte, qué tal, qué tal estás, estoy bien, muchas gracias. mucho gusto is correct but more formal in Spain, encantado/a stays accepted. Open questions answered and removed.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.hola-soy', 'Hola, ___ Ana.', ['soy', 'estoy', 'somos'], 'soy', Kit::form('soy'), 'Saying who you are takes ser: I am is soy.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.como-usted', '¿Cómo ___ usted?', ['está', 'es', 'son'], 'está', Kit::form('está', true), 'How someone is, a state, takes estar. Ser says who someone is.', 'choose', 'How are you? (formal)'),
            Kit::gap($stage, 'sentences.choose_gap.yo-bien', 'Yo ___ bien.', ['estoy', 'soy', 'es'], 'estoy', Kit::form('estoy', true), 'How you are, a state, takes estar, not ser.', 'choose', 'I am fine.'),
            Kit::gap($stage, 'sentences.choose_gap.ella-marta', 'Ella ___ Marta.', ['es', 'soy', 'somos'], 'es', Kit::form('es'), 'He, she and usted take es.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.nosotros', 'Nosotros ___ Pablo y Luis.', ['somos', 'son', 'soy'], 'somos', Kit::form('somos'), 'We takes somos.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.buenas-noches', 'Buenas ___, Marta.', ['noches', 'días', 'gracias'], 'noches', Kit::word('buenas noches', 'noches'), 'Good night is buenas noches. Días is masculine, so it goes with buenos, and gracias is not a time of day.', 'choose', 'Good night, Marta.'),

            Kit::typeGap($stage, 'sentences.type_gap.estudiante', 'Yo ___ estudiante.', 'I am a student.', 'soy', Kit::form('soy'), 'Saying who you are takes ser: I am is soy.', glosses: ['estudiante' => 'student']),
            Kit::typeGap($stage, 'sentences.type_gap.como-estas', 'Hola, Ana. ¿Cómo ___?', 'Hi, Ana. How are you?', 'estás', Kit::form('estás', true), 'How you are, a state, takes estar. Eres says who you are.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-luis', 'Tú ___ Luis.', 'You are Luis.', 'eres', Kit::form('eres'), 'You, to a friend, takes eres.'),
            Kit::typeGap($stage, 'sentences.type_gap.ellos', 'Ellos ___ Ana y Luis.', 'They are Ana and Luis.', 'son', Kit::form('son'), 'More than one person, they, takes son.'),
            Kit::typeGap($stage, 'sentences.type_gap.buenos-dias', 'Buenos ___, Pablo.', 'Good morning, Pablo.', 'días', Kit::word('buenos días', 'días')),
            Kit::translate($stage, 'sentences.translate.hola-soy', 'Hello, I am Ana.', ['Hola, soy Ana.', 'Hola, yo soy Ana.'], [Kit::word('hola'), Kit::form('soy')]),
            Kit::translate($stage, 'sentences.translate.me-llamo', 'My name is Luis. Nice to meet you.', ['Me llamo Luis. Mucho gusto.', 'Me llamo Luis, mucho gusto.', 'Yo me llamo Luis. Mucho gusto.', 'Me llamo Luis. Mucho gusto en conocerte.', 'Me llamo Luis. Encantado.', 'Me llamo Luis, encantado.'], [Kit::word('me llamo'), Kit::word('mucho gusto', null, ['encantado'])]),
            Kit::translate($stage, 'sentences.translate.bien-gracias', 'I am fine, thank you.', ['Estoy bien, gracias.', 'Estoy bien. Gracias.', 'Estoy bien, muchas gracias.', 'Yo estoy bien, gracias.'], [Kit::word('bien'), Kit::word('gracias'), Kit::form('estoy', true)]),
            Kit::build($stage, 'sentences.build.buenas-tardes', 'Good afternoon, I am Ana.', 'Buenas tardes, soy Ana.', ['estoy'], [Kit::word('buenas tardes'), Kit::form('soy')]),
            Kit::build($stage, 'sentences.build.hola-pablo', 'Hello, he is Pablo.', 'Hola, él es Pablo.', ['soy'], [Kit::word('hola'), Kit::form('es')]),
            Kit::build($stage, 'sentences.build.somos', 'We are Pablo and Luis.', 'Somos Pablo y Luis.', ['son'], [Kit::form('somos')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.buenas-noches', 'Buenas noches, soy Ana.', ['Good morning, I am Ana.', 'Good night, I am Ana.', 'Good afternoon, I am Ana.', 'Goodbye, I am Ana.'], 'Good night, I am Ana.', [Kit::word('buenas noches'), Kit::form('soy')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.mucho-gusto', 'Mucho gusto, Pablo.', ['Nice to meet you, Pablo.', 'Thank you, Pablo.', 'Goodbye, Pablo.', 'Hello, Pablo.'], 'Nice to meet you, Pablo.', [Kit::word('mucho gusto')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.adios', 'Adiós, Marta.', ['Hello, Marta.', 'Goodbye, Marta.', 'Thank you, Marta.', 'Nice to meet you, Marta.'], 'Goodbye, Marta.', [Kit::word('adiós')]),
            Kit::listenType($stage, 'sentences.listen_type.buenos-dias', 'Buenos días, me llamo Luis.', 'Good morning, my name is Luis.', [Kit::word('buenos días'), Kit::word('me llamo')]),
            Kit::listenType($stage, 'sentences.listen_type.como-estas', '¿Cómo estás, Ana?', 'How are you, Ana?', [Kit::word('¿cómo estás?', 'cómo estás')]),
            Kit::listenType($stage, 'sentences.listen_type.hola-soy', 'Hola, soy Marta.', 'Hello, I am Marta.', [Kit::word('hola'), Kit::form('soy')], homophoneNote: 'Greeting at the start of a sentence, so hola with a silent h, not ola (wave).'),
            Kit::listenType($stage, 'sentences.listen_type.tardes', 'Buenas tardes, Pablo. ¿Cómo estás?', 'Good afternoon, Pablo. How are you?', [Kit::word('buenas tardes'), Kit::word('¿cómo estás?', 'cómo estás')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.hola-me-llamo', 'Hola, me llamo Ana. Mucho gusto.', 'Hello, my name is Ana. Nice to meet you.', [Kit::word('hola'), Kit::word('me llamo'), Kit::word('mucho gusto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.adios', 'Adiós, Marta. Gracias.', 'Goodbye, Marta. Thank you.', [Kit::word('adiós'), Kit::word('gracias')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.buenos-dias', 'Buenos días, soy Pablo.', 'Good morning, I am Pablo.', [Kit::word('buenos días'), Kit::form('soy')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.como-estas', '¿Cómo estás, Ana? Estoy bien.', 'How are you, Ana? I am fine.', [Kit::word('¿cómo estás?', 'cómo estás'), Kit::word('bien'), Kit::form('estoy', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.como-estas', '¿Cómo estás?', 'How are you?', [['estoy', 'bien'], ['bien', 'gracias', 'muy']], 'Estoy bien, gracias.', [Kit::word('¿cómo estás?'), Kit::word('bien'), Kit::form('estoy', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.eres-luis', 'Hola, ¿eres Luis?', 'Hello, are you Luis?', [['sí', 'no', 'soy'], ['soy', 'luis', 'ana', 'pablo', 'marta']], 'Sí, soy Luis.', [Kit::word('hola'), Kit::form('soy')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quien-ella', '¿Quién es ella?', 'Who is she?', [['es', 'ella'], ['ana', 'marta', 'pablo', 'luis']], 'Ella es Marta.', [Kit::form('es')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.presentaciones', 'Read the conversation.', [
                Kit::line('Pablo', 'Buenos días. Me llamo Pablo. ¿Cómo estás?'),
                Kit::line('Ana', 'Bien, gracias. Soy Ana. Mucho gusto.'),
                Kit::line('Pablo', 'Mucho gusto, Ana. Ella es mi amiga Marta.'),
                Kit::line('Marta', 'Hola, Ana. Yo soy estudiante.'),
            ], [
                Kit::question('Who speaks first?', ['Luis', 'Pablo', 'Ana'], 'Pablo'),
                Kit::question('How is Ana?', ['She is fine.', 'She is not fine.', 'The text does not say.'], 'She is fine.'),
                Kit::question('Who is Marta?', ['Pablo\'s friend', 'Ana\'s sister', 'A teacher'], 'Pablo\'s friend'),
            ], [Kit::word('buenos días'), Kit::word('me llamo'), Kit::word('¿cómo estás?'), Kit::word('bien'), Kit::word('gracias'), Kit::word('mucho gusto'), Kit::word('hola')], 'read', null, ['amiga' => 'friend (female)', 'estudiante' => 'student']),
            Kit::gap($stage, 'task.choose_gap.mucho-gusto', 'Mucho ___, Ana.', ['gusto', 'gracias'], 'gusto', Kit::word('mucho gusto', 'gusto'), 'The set phrase for nice to meet you is mucho gusto. Thank you is gracias or muchas gracias.', 'read', 'Nice to meet you, Ana.'),
            Kit::gap($stage, 'task.choose_gap.bien-gracias', '¿Cómo estás? ___, gracias.', ['Bien', 'Buenos', 'Buenas'], 'Bien', Kit::word('bien'), 'Bien is the usual answer to how are you.', 'read'),

            Kit::transform($stage, 'task.transform.somos', 'Change the subject to we.', 'Soy estudiante.', ['Somos estudiantes.', 'Nosotros somos estudiantes.', 'Nosotras somos estudiantes.'], [Kit::form('somos')], ['estudiante' => 'student', 'estudiantes' => 'students']),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to tú.', 'Ella es Marta.', ['Tú eres Marta.', 'Eres Marta.'], [Kit::form('eres')]),
            Kit::transform($stage, 'task.transform.estamos', 'Change the subject to we.', 'Estoy bien.', ['Estamos bien.', 'Nosotros estamos bien.', 'Nosotras estamos bien.'], [Kit::word('bien'), Kit::form('estamos', true)]),
            Kit::writeGuided($stage, 'task.write_guided.saludo', 'Say good morning, say your name is Ana and say nice to meet you.', ['buenos días', 'me llamo', 'mucho gusto'], 'Buenos días. Me llamo Ana. Mucho gusto.', [
                ['forms' => ['días'], 'term' => 'buenos días'],
                ['forms' => ['llamo', 'soy'], 'term' => 'me llamo'],
                ['forms' => ['gusto', 'encantado', 'encantada'], 'term' => 'mucho gusto'],
            ], [Kit::word('buenos días'), Kit::word('me llamo'), Kit::word('mucho gusto')]),
            Kit::writeGuided($stage, 'task.write_guided.despedida', 'Say good night to Luis, say thank you and say goodbye.', ['buenas noches', 'gracias', 'adiós'], 'Buenas noches, Luis. Gracias. Adiós.', [
                ['forms' => ['noches'], 'term' => 'buenas noches'],
                ['forms' => ['gracias'], 'term' => 'gracias'],
                ['forms' => ['adiós'], 'term' => 'adiós'],
            ], [Kit::word('buenas noches'), Kit::word('gracias'), Kit::word('adiós')]),
            Kit::build($stage, 'task.build.buenos-dias', 'Good morning, I am Ana. How are you?', 'Buenos días, soy Ana. ¿Cómo estás?', ['estoy', 'eres'], [Kit::word('buenos días'), Kit::form('soy'), Kit::word('¿cómo estás?', 'cómo estás')], 'write'),
            Kit::build($stage, 'task.build.tardes', 'Good afternoon, we are Pablo and Luis.', 'Buenas tardes, somos Pablo y Luis.', ['son', 'soy'], [Kit::word('buenas tardes'), Kit::form('somos')], 'write'),
            Kit::build($stage, 'task.build.estoy-bien', 'I am fine, thank you. Goodbye, Ana.', 'Estoy bien, gracias. Adiós, Ana.', ['soy', 'estás'], [Kit::word('bien'), Kit::word('gracias'), Kit::word('adiós'), Kit::form('estoy', true)], 'write'),
            Kit::translate($stage, 'task.translate.estudiante', 'Nice to meet you. I am a student.', ['Mucho gusto. Soy estudiante.', 'Mucho gusto, soy estudiante.', 'Mucho gusto. Yo soy estudiante.', 'Mucho gusto. Soy un estudiante.', 'Encantado. Soy estudiante.', 'Encantada. Soy estudiante.'], [Kit::word('mucho gusto', null, ['encantado', 'encantada']), Kit::form('soy')], 'write', null, ['estudiante' => 'student']),
            Kit::translate($stage, 'task.translate.hola-como', 'Hello, how are you?', ['Hola, ¿cómo estás?', 'Hola, ¿cómo estás tú?', 'Hola, ¿cómo está usted?'], [Kit::word('hola'), Kit::word('¿cómo estás?', 'cómo')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.buenas-noches', [
                Kit::line('Luis', 'Buenas noches, Marta. ¿Cómo estás?'),
                Kit::line('Marta', 'Bien, gracias. Él es Pablo.'),
                Kit::line('Luis', 'Mucho gusto, Pablo. Me llamo Luis.'),
                Kit::line('Pablo', 'Mucho gusto. Adiós, Luis. Buenas noches.'),
            ], [
                Kit::question('What time of day is it?', ['Morning', 'Afternoon', 'Evening or night'], 'Evening or night'),
                Kit::question('How is Marta?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Marta introduce?', ['Luis', 'Pablo', 'Ana'], 'Pablo'),
            ], [
                Kit::question('What does Luis say to Pablo?', ['Nice to meet you', 'Thank you', 'Good morning'], 'Nice to meet you'),
                Kit::question('Who says goodbye?', ['Marta', 'Pablo', 'Luis'], 'Pablo'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Three'),
            ], [Kit::word('buenas noches'), Kit::word('¿cómo estás?'), Kit::word('bien'), Kit::word('gracias'), Kit::word('mucho gusto'), Kit::word('me llamo'), Kit::word('adiós')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.tardes-luis', 'Buenas tardes, Luis. ¿Cómo estás?', 'Good afternoon, Luis. How are you?', [Kit::word('buenas tardes'), Kit::word('¿cómo estás?', 'cómo estás')]),
            Kit::listenType($stage, 'task.listen_type.el-ella', 'Él es Pablo y ella es Ana.', 'He is Pablo and she is Ana.', [Kit::form('es')]),
            Kit::listenType($stage, 'task.listen_type.somos', 'Somos Luis y Marta. Mucho gusto.', 'We are Luis and Marta. Nice to meet you.', [Kit::form('somos'), Kit::word('mucho gusto')]),

            Kit::speakAnswer($stage, 'task.speak_answer.tardes', 'Buenas tardes. ¿Cómo estás?', 'Good afternoon. How are you?', [['estoy', 'bien'], ['bien', 'gracias', 'muy']], 'Estoy bien, gracias.', [Kit::word('buenas tardes'), Kit::word('¿cómo estás?'), Kit::word('bien'), Kit::form('estoy', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quien-eres', 'Buenos días. ¿Quién eres?', 'Good morning. Who are you?', [['soy', 'me', 'llamo'], ['ana', 'pablo', 'marta', 'luis']], 'Buenos días, soy Ana.', [Kit::word('buenos días'), Kit::form('soy')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mucho-gusto', 'Me llamo Pablo. Mucho gusto.', 'My name is Pablo. Nice to meet you.', [['gusto', 'encantado', 'encantada']], 'Mucho gusto, Pablo.', [Kit::word('me llamo'), Kit::word('mucho gusto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quien-el', '¿Quién es él?', 'Who is he?', [['es', 'él'], ['pablo', 'luis']], 'Él es Pablo.', [Kit::form('es')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.buenas-noches', 'Buenas noches, Luis. Adiós.', 'Good night, Luis. Goodbye.', [Kit::word('buenas noches'), Kit::word('adiós')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.estamos', 'Estamos bien, gracias.', 'We are fine, thank you.', [Kit::word('bien'), Kit::word('gracias'), Kit::form('estamos', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.noches', 'Good night, I am Luis.', ['Buenas noches, soy Luis.', 'Buenas noches, yo soy Luis.'], [Kit::word('buenas noches'), Kit::form('soy')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.gracias', 'Thank you, Marta. Goodbye.', ['Gracias, Marta. Adiós.', 'Muchas gracias, Marta. Adiós.'], [Kit::word('gracias'), Kit::word('adiós')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.estoy', 'How are you? I am fine.', ['¿Cómo estás? Estoy bien.', '¿Cómo está usted? Estoy bien.', '¿Cómo estás? Yo estoy bien.'], [Kit::word('¿cómo estás?', 'cómo'), Kit::word('bien'), Kit::form('estoy', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.dias', 'Good morning, my name is Marta.', ['Buenos días, me llamo Marta.', 'Buenos días, yo me llamo Marta.'], [Kit::word('buenos días'), Kit::word('me llamo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ellos', 'Ellos ___ Pablo y Marta.', 'They are Pablo and Marta.', 'son', Kit::form('son'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.estas', 'Buenos días, Luis. ¿Cómo ___?', 'Good morning, Luis. How are you?', 'estás', Kit::form('estás', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.somos', 'Somos Marta y Luis.', 'We are Marta and Luis.', [Kit::form('somos')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.hola-tardes', 'Hola, buenas tardes, soy Pablo.', 'Hello, good afternoon, I am Pablo.', [Kit::word('hola'), Kit::word('buenas tardes'), Kit::form('soy')], 'dictation', $set, homophoneNote: 'Greeting at the start of a sentence, so hola with a silent h, not ola (wave).'),
            Kit::listenType($stage, 'check.a.listen_type.mucho-gusto', 'Mucho gusto, me llamo Pablo.', 'Nice to meet you, my name is Pablo.', [Kit::word('mucho gusto'), Kit::word('me llamo')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.manana', [
                Kit::line('Ana', 'Buenos días, Luis. ¿Cómo estás?'),
                Kit::line('Luis', 'Muy bien, gracias. ¿Y tú?'),
                Kit::line('Ana', 'Bien. Luis, él es Pablo.'),
                Kit::line('Luis', 'Hola, Pablo. Mucho gusto.'),
            ], [
                Kit::question('What time of day is it?', ['Morning', 'Afternoon', 'Night'], 'Morning'),
                Kit::question('How is Luis?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Ana introduce?', ['Pablo', 'Marta', 'Nobody'], 'Pablo'),
            ], [
                Kit::question('Who says hello to Pablo?', ['Ana', 'Luis', 'Marta'], 'Luis'),
                Kit::question('Does Luis say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('buenos días'), Kit::word('¿cómo estás?'), Kit::word('bien'), Kit::word('gracias'), Kit::word('hola'), Kit::word('mucho gusto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.noche', 'Read the conversation.', [
                Kit::line('Marta', 'Buenas noches. Me llamo Marta.'),
                Kit::line('Pablo', 'Hola, Marta. Soy Pablo. Mucho gusto.'),
                Kit::line('Marta', 'Mucho gusto. ¿Cómo estás?'),
                Kit::line('Pablo', 'Bien, gracias.'),
            ], [
                Kit::question('What is the first speaker called?', ['Ana', 'Marta', 'Luis'], 'Marta'),
                Kit::question('How is Pablo?', ['Fine', 'Not fine', 'The text does not say.'], 'Fine'),
            ], [Kit::word('buenas noches'), Kit::word('me llamo'), Kit::word('¿cómo estás?'), Kit::word('bien')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.como-estas', '¿Cómo estás?', 'How are you?', [['estoy', 'bien', 'muy'], ['bien', 'gracias']], 'Estoy bien, gracias.', [Kit::word('¿cómo estás?'), Kit::word('bien'), Kit::word('gracias')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quien-eres', 'Buenas noches. ¿Quién eres?', 'Good night. Who are you?', [['soy', 'me', 'llamo'], ['ana', 'pablo', 'marta', 'luis']], 'Soy Marta.', [Kit::word('buenas noches')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.eres-ana', 'Hola, ¿eres Ana?', 'Hello, are you Ana?', [['sí', 'no', 'soy'], ['soy', 'ana', 'luis', 'marta', 'pablo']], 'Sí, soy Ana.', [Kit::word('hola')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.hola-ella', 'Hello, she is Marta.', ['Hola, ella es Marta.'], [Kit::word('hola'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.adios', 'Goodbye, thank you.', ['Adiós, gracias.', 'Adiós, muchas gracias.', 'Gracias, adiós.', 'Muchas gracias, adiós.'], [Kit::word('adiós'), Kit::word('gracias')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.soy-ana', 'I am Ana. Nice to meet you.', ['Soy Ana. Mucho gusto.', 'Soy Ana, mucho gusto.', 'Yo soy Ana. Mucho gusto.', 'Soy Ana. Encantada.', 'Soy Ana, encantada.'], [Kit::word('mucho gusto', null, ['encantada']), Kit::form('soy')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.como', 'Good morning, how are you?', ['Buenos días, ¿cómo estás?', 'Buenos días, ¿cómo está usted?', 'Buenos días, ¿cómo estás tú?'], [Kit::word('buenos días'), Kit::word('¿cómo estás?', 'cómo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estoy', 'Yo ___ bien, gracias.', 'I am fine, thank you.', 'estoy', Kit::form('estoy', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta', '¿Cómo ___ Marta?', 'How is Marta?', 'está', Kit::form('está', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.noches', 'Buenas noches, soy Marta.', 'Good night, I am Marta.', [Kit::word('buenas noches'), Kit::form('soy')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.tardes-pablo', 'Buenas tardes, Pablo. Mucho gusto.', 'Good afternoon, Pablo. Nice to meet you.', [Kit::word('buenas tardes'), Kit::word('mucho gusto')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.me-llamo', 'Me llamo Pablo y estoy bien.', 'My name is Pablo and I am fine.', [Kit::word('me llamo'), Kit::word('bien'), Kit::form('estoy', true)], 'dictation', $set),
        ];
    }
}
