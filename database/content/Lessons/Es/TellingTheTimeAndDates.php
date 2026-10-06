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

final class TellingTheTimeAndDates implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'telling-the-time-and-dates';
    }

    public function words(): array
    {
        return [
            new WordData('la hora', cue: 'time (on the clock) or hour', note: 'La hora is the time of day on the clock, and also one hour.'),
            new WordData('el reloj', cue: 'clock or watch', note: 'El reloj is both a wall clock and a watch.'),
            new WordData('el día', cue: 'day', forms: ['días'], note: 'Día ends in -a but is masculine: el día.'),
            new WordData('la semana', cue: 'week'),
            new WordData('hoy', cue: 'today'),
            new WordData('mañana', cue: 'tomorrow (not the morning)', note: 'Mañana on its own means tomorrow. With la in front, la mañana means the morning.'),
            new WordData('el lunes', cue: 'Monday', note: 'Days of the week have no capital letter in Spanish. El lunes means on Monday.'),
            new WordData('el domingo', cue: 'Sunday', note: 'Days of the week have no capital letter in Spanish. El domingo means on Sunday.'),
            new WordData('en punto', cue: 'on the dot (exactly, for the hour)'),
            new WordData('y media', cue: 'half past'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Es la una. Son las dos.', 'english' => 'It is one o\'clock. It is two o\'clock.'],
            ['text' => 'Voy a las tres.', 'english' => 'I am going at three.'],
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
            Kit::gap($stage, 'sentences.choose_gap.es-una', '___ la una.', ['Es', 'Son', 'Soy'], 'Es', Kit::form('Es', true), 'Una is just one hour, so the time uses es. Soy means I am.', 'choose', 'It is one o\'clock.', ['Soy' => 'I am']),
            Kit::gap($stage, 'sentences.choose_gap.media', 'Son las tres y ___.', ['media', 'hora', 'día'], 'media', Kit::word('y media', 'media'), 'Y media means half past. Hora and día are not used to add half an hour.', 'choose', 'It is half past three.'),
            Kit::gap($stage, 'sentences.choose_gap.lunes', 'Hoy es ___.', ['lunes', 'semana', 'reloj'], 'lunes', Kit::word('el lunes', 'lunes'), 'Lunes is a day of the week. Semana and reloj are not days.', 'choose', 'Today is Monday.'),
            Kit::gap($stage, 'sentences.choose_gap.son-dos', '___ las dos.', ['Son', 'Es', 'Soy'], 'Son', Kit::form('Son', true), 'Las dos is plural, so the time uses son. Soy means I am.', 'choose', 'It is two o\'clock.', ['Soy' => 'I am']),
            Kit::gap($stage, 'sentences.choose_gap.a-las', 'Voy ___ cinco.', ['a las', 'a la', 'las'], 'a las', Kit::form('a las', true), 'To say at five, use a las with a plural hour. A la is only for one o\'clock.', 'choose', 'I go at five.'),
            Kit::gap($stage, 'sentences.choose_gap.manana', 'Hoy es domingo. ___ es lunes.', ['Mañana', 'Semana', 'Hora'], 'Mañana', Kit::word('mañana'), 'Mañana means tomorrow, the day after today. Semana and hora are not days.', 'choose', 'Today is Sunday. Tomorrow is Monday.'),

            Kit::typeGap($stage, 'sentences.type_gap.punto', 'Son las cuatro en ___.', 'It is four o\'clock on the dot.', 'punto', Kit::word('en punto', 'punto')),
            Kit::typeGap($stage, 'sentences.type_gap.son-seis', '___ las seis.', 'It is six o\'clock.', 'Son', Kit::form('Son', true), 'Las seis is plural, so the time uses son.'),
            Kit::typeGap($stage, 'sentences.type_gap.hora', 'Ana, ¿qué ___ es?', 'Ana, what time is it?', 'hora', Kit::word('la hora', 'hora')),
            Kit::typeGap($stage, 'sentences.type_gap.reloj', 'Mi ___ está aquí.', 'My watch is here.', 'reloj', Kit::word('el reloj', 'reloj')),
            Kit::typeGap($stage, 'sentences.type_gap.dia', '¿Qué ___ es hoy? Es domingo.', 'What day is it today? It is Sunday.', 'día', Kit::word('el día', 'día')),

            Kit::translate($stage, 'sentences.translate.una-en-punto', 'It is exactly one o\'clock.', ['Es la una en punto.'], [Kit::word('en punto'), Kit::form('Es')]),
            Kit::translate($stage, 'sentences.translate.hora', 'What time is it?', ['¿Qué hora es?'], [Kit::word('la hora', 'hora')]),
            Kit::translate($stage, 'sentences.translate.dos-media', 'It is half past two.', ['Son las dos y media.'], [Kit::word('y media'), Kit::form('Son')]),

            Kit::build($stage, 'sentences.build.voy-seis', 'I go at six.', 'Voy a las seis.', ['la'], [Kit::form('a las')]),
            Kit::build($stage, 'sentences.build.manana-lunes', 'Tomorrow is Monday.', 'Mañana es lunes.', ['son'], [Kit::word('mañana'), Kit::word('el lunes', 'lunes'), Kit::form('es')]),
            Kit::build($stage, 'sentences.build.siete-en-punto', 'It is seven o\'clock on the dot.', 'Son las siete en punto.', ['es'], [Kit::word('en punto'), Kit::form('Son')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.una-media', 'Es la una y media.', ['It is half past one.', 'It is one o\'clock.', 'It is half past two.', 'It is two o\'clock.'], 'It is half past one.', [Kit::word('y media'), Kit::form('Es', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.hoy-domingo', 'Hoy es domingo.', ['Today is Sunday.', 'Tomorrow is Sunday.', 'Today is Monday.', 'Tomorrow is Monday.'], 'Today is Sunday.', [Kit::word('hoy'), Kit::word('el domingo', 'domingo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.semana', 'Hay siete días en una semana.', ['There are seven days in a week.', 'There are five days in a week.', 'There are seven weeks in a day.', 'There are seven hours in a day.'], 'There are seven days in a week.', [Kit::word('la semana', 'semana'), Kit::word('el día', 'días')]),
            Kit::listenType($stage, 'sentences.listen_type.reloj', 'Mi reloj está aquí, Ana.', 'My watch is here, Ana.', [Kit::word('el reloj', 'reloj')]),
            Kit::listenType($stage, 'sentences.listen_type.nueve-media', 'Son las nueve y media.', 'It is half past nine.', [Kit::word('y media'), Kit::form('Son')]),
            Kit::listenType($stage, 'sentences.listen_type.dia-hoy', '¿Qué día es hoy, Ana?', 'What day is it today, Ana?', [Kit::word('el día', 'día'), Kit::word('hoy')]),
            Kit::listenType($stage, 'sentences.listen_type.hora-una', '¿Qué hora es? Es la una.', 'What time is it? It is one o\'clock.', [Kit::word('la hora', 'hora'), Kit::form('Es')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.cinco-media', 'Son las cinco y media.', 'It is half past five.', [Kit::word('y media'), Kit::form('Son')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hoy-manana', 'Hoy es domingo. Mañana es lunes.', 'Today is Sunday. Tomorrow is Monday.', [Kit::word('hoy'), Kit::word('mañana'), Kit::word('el domingo', 'domingo'), Kit::word('el lunes', 'lunes')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ocho-en-punto', 'Voy a las ocho en punto.', 'I go at eight on the dot.', [Kit::word('en punto'), Kit::form('a las')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hora-reloj', '¿Qué hora es en tu reloj?', 'What time is it on your watch?', [Kit::word('la hora', 'hora'), Kit::word('el reloj', 'reloj')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.una-media', '¿Es la una y media?', 'Is it half past one?', [['sí', 'no', 'es', 'son'], ['media']], 'Sí, es la una y media.', [Kit::word('y media')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.manana', 'Hoy es domingo. ¿Qué día es mañana?', 'Today is Sunday. What day is tomorrow?', [['mañana', 'es'], ['lunes']], 'Mañana es lunes.', [Kit::word('mañana'), Kit::word('el lunes', 'lunes')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.semana', '¿Tiene la semana siete días?', 'Does the week have seven days?', [['sí', 'no', 'tiene'], ['semana', 'siete', 'días']], 'Sí, la semana tiene siete días.', [Kit::word('la semana', 'semana'), Kit::word('el día', 'días')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.hora', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿qué hora es?'),
                Kit::line('Pablo', 'Son las dos y media.'),
                Kit::line('Ana', 'Hoy voy a las tres. Mañana voy a las cinco.'),
                Kit::line('Pablo', 'Adiós, Ana.'),
            ], [
                Kit::question('What time is it?', ['Half past two', 'Two o\'clock', 'Half past three'], 'Half past two'),
                Kit::question('At what time does Ana go today?', ['At three', 'At five', 'At two'], 'At three'),
                Kit::question('At what time does Ana go tomorrow?', ['At five', 'At three', 'At two'], 'At five'),
            ], [Kit::word('la hora', 'hora'), Kit::word('y media'), Kit::word('hoy'), Kit::word('mañana')], 'read'),
            Kit::gap($stage, 'task.choose_gap.dias', 'Una semana tiene siete ___.', ['días', 'hora', 'reloj'], 'días', Kit::word('el día', 'días'), 'Siete means more than one, so we need the plural: días.', 'read', 'A week has seven days.'),
            Kit::gap($stage, 'task.choose_gap.en-punto', 'Son las cinco ___ punto.', ['en', 'de', 'con'], 'en', Kit::word('en punto', 'en'), 'En punto means on the dot, exactly. The words de and con do not fit here.', 'read', 'It is five o\'clock on the dot.'),

            Kit::transform($stage, 'task.transform.tres', 'Change it to three o\'clock.', 'Son las dos en punto.', ['Son las tres en punto.'], [Kit::word('en punto'), Kit::form('Son')]),
            Kit::transform($stage, 'task.transform.una-media', 'Change it to half past one.', 'Son las tres y media.', ['Es la una y media.'], [Kit::word('y media'), Kit::form('Es')]),
            Kit::transform($stage, 'task.transform.manana', 'Say it for tomorrow.', 'Hoy es lunes.', ['Mañana es lunes.'], [Kit::word('mañana'), Kit::word('el lunes', 'lunes')]),
            Kit::writeGuided($stage, 'task.write_guided.hoy', 'Say that today is Monday and that it is two o\'clock exactly.', ['hoy', 'lunes', 'son', 'en punto'], 'Hoy es lunes. Son las dos en punto.', [
                ['forms' => ['hoy'], 'term' => 'hoy'],
                ['forms' => ['lunes'], 'term' => 'el lunes'],
                ['forms' => ['punto'], 'term' => 'en punto'],
            ], [Kit::word('hoy'), Kit::word('el lunes', 'lunes'), Kit::word('en punto')]),
            Kit::writeGuided($stage, 'task.write_guided.reloj', 'Say that your watch is here and that it is half past four.', ['reloj', 'aquí', 'son', 'y media'], 'Mi reloj está aquí. Son las cuatro y media.', [
                ['forms' => ['reloj'], 'term' => 'el reloj'],
                ['forms' => ['media'], 'term' => 'y media'],
            ], [Kit::word('el reloj', 'reloj'), Kit::word('y media')]),
            Kit::build($stage, 'task.build.manana-media', 'Tomorrow I go at half past nine.', 'Mañana voy a las nueve y media.', ['la', 'es'], [Kit::word('mañana'), Kit::word('y media'), Kit::form('a las')], 'write'),
            Kit::build($stage, 'task.build.siete-dias', 'There are seven days in the week.', 'Hay siete días en la semana.', ['tienen', 'son'], [Kit::word('la semana', 'semana'), Kit::word('el día', 'días')], 'write'),
            Kit::build($stage, 'task.build.hora-marta', 'What time is it, Marta?', '¿Qué hora es, Marta?', ['son', 'la'], [Kit::word('la hora', 'hora'), Kit::form('es')], 'write'),
            Kit::translate($stage, 'task.translate.dias-semana', 'How many days does the week have?', ['¿Cuántos días tiene la semana?'], [Kit::word('la semana', 'semana'), Kit::word('el día', 'días')], 'write'),
            Kit::translate($stage, 'task.translate.a-que-hora', 'At what time are you going, Pablo? (informal you)', ['¿A qué hora vas, Pablo?', 'Pablo, ¿a qué hora vas?'], [Kit::word('la hora', 'hora')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.dia', [
                Kit::line('Pablo', '¿Qué día es hoy, Ana?'),
                Kit::line('Ana', 'Es domingo. Mañana es lunes.'),
                Kit::line('Pablo', '¿Y a qué hora vas?'),
                Kit::line('Ana', 'Voy a las ocho en punto.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What day is tomorrow?', ['Monday', 'Sunday', 'Friday'], 'Monday'),
                Kit::question('At what time does Ana go?', ['At eight on the dot', 'At half past eight', 'At nine'], 'At eight on the dot'),
            ], [
                Kit::question('Who asks the questions?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is tomorrow Monday?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('el día', 'día'), Kit::word('hoy'), Kit::word('mañana'), Kit::word('el domingo', 'domingo'), Kit::word('el lunes', 'lunes'), Kit::word('en punto'), Kit::word('la hora', 'hora')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.reloj', 'El reloj de Pablo está aquí.', 'Pablo\'s watch is here.', [Kit::word('el reloj', 'reloj')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.diez-media', 'Hoy voy a las diez y media.', 'Today I go at half past ten.', [Kit::word('hoy'), Kit::word('y media'), Kit::form('a las')], 'listen', homophoneNote: 'A (at) sounds the same as ha. Here it is the word for at.'),
            Kit::listenType($stage, 'task.listen_type.domingo', 'Hoy es domingo. ¿Qué hora es, Ana?', 'Today is Sunday. What time is it, Ana?', [Kit::word('hoy'), Kit::word('el domingo', 'domingo'), Kit::word('la hora', 'hora')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.una-en-punto', '¿Es la una en punto?', 'Is it one o\'clock exactly?', [['sí', 'no', 'es', 'son'], ['punto']], 'Sí, es la una en punto.', [Kit::word('en punto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.a-las', '¿A qué hora vas hoy?', 'At what time are you going today? (informal you)', [['voy', 'a'], ['las', 'la']], 'Voy a las tres.', [Kit::form('a las')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.hoy-lunes', '¿Es hoy lunes?', 'Is today Monday?', [['sí', 'no', 'es', 'hoy'], ['lunes', 'domingo']], 'No, hoy es domingo.', [Kit::word('hoy'), Kit::word('el lunes', 'lunes')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.reloj', '¿Tienes un reloj?', 'Do you have a watch? (informal you)', [['sí', 'no', 'tengo'], ['reloj']], 'Sí, tengo un reloj.', [Kit::word('el reloj', 'reloj')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.tres-media', 'Hoy voy a las tres y media.', 'Today I go at half past three.', [Kit::word('hoy'), Kit::word('y media'), Kit::form('a las')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.dia', '¿Qué día es hoy? Es domingo.', 'What day is it today? It is Sunday.', [Kit::word('el día', 'día'), Kit::word('el domingo', 'domingo')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.cuatro-media', 'It is half past four.', ['Son las cuatro y media.'], [Kit::word('y media'), Kit::form('Son')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.manana', 'Tomorrow is not Monday.', ['Mañana no es lunes.'], [Kit::word('mañana'), Kit::word('el lunes', 'lunes'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.semana', 'The week has seven days.', ['La semana tiene siete días.'], [Kit::word('la semana', 'semana'), Kit::word('el día', 'días')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.nueve', 'I go at nine on the dot.', ['Voy a las nueve en punto.', 'Yo voy a las nueve en punto.'], [Kit::word('en punto'), Kit::form('a las', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.siete', 'Marta va ___ siete.', 'Marta goes at seven.', 'a las', Kit::form('a las'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.una-veinte', '___ la una y veinte.', 'It is twenty past one.', 'Es', Kit::form('Es', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.ocho-media', 'Son las ocho y media.', 'It is half past eight.', [Kit::word('y media'), Kit::form('Son')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.reloj', 'El reloj está aquí, Marta.', 'The watch is here, Marta.', [Kit::word('el reloj', 'reloj')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.hoy', 'Hoy es domingo. ¿Qué hora es, Luis?', 'Today is Sunday. What time is it, Luis?', [Kit::word('hoy'), Kit::word('el domingo', 'domingo'), Kit::word('la hora', 'hora')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.hora', [
                Kit::line('Ana', 'Luis, ¿qué hora es?'),
                Kit::line('Luis', 'Son las seis y media.'),
                Kit::line('Ana', 'Mañana voy a las nueve en punto.'),
            ], [
                Kit::question('What time is it?', ['Half past six', 'Six o\'clock', 'Half past nine'], 'Half past six'),
                Kit::question('At what time does Ana go tomorrow?', ['At nine on the dot', 'At six', 'At half past nine'], 'At nine on the dot'),
                Kit::question('When does Ana go?', ['Tomorrow', 'Today', 'On Sunday'], 'Tomorrow'),
            ], [
                Kit::question('Who answers the question?', ['Luis', 'Ana', 'Nobody'], 'Luis'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is it six o\'clock on the dot?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
            ], [Kit::word('la hora', 'hora'), Kit::word('y media'), Kit::word('mañana'), Kit::word('en punto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.dia', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿qué día es hoy?'),
                Kit::line('Luis', 'Hoy es domingo y mañana es lunes.'),
                Kit::line('Marta', 'Gracias. ¿Y qué hora es?'),
                Kit::line('Luis', 'Son las cinco.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What does Marta ask at the end?', ['What time it is', 'What day it is', 'Where the watch is'], 'What time it is'),
            ], [Kit::word('el día', 'día'), Kit::word('hoy'), Kit::word('el domingo', 'domingo'), Kit::word('el lunes', 'lunes'), Kit::word('la hora', 'hora')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.reloj', '¿Dónde está tu reloj?', 'Where is your watch?', [['reloj', 'está'], ['aquí', 'allí']], 'Mi reloj está aquí.', [Kit::word('el reloj', 'reloj')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.hoy', 'Mañana es lunes. ¿Qué día es hoy?', 'Tomorrow is Monday. What day is it today?', [['hoy', 'es'], ['domingo']], 'Hoy es domingo.', [Kit::word('hoy'), Kit::word('el domingo', 'domingo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.semana', '¿Cuántos días tiene una semana?', 'How many days does a week have?', [['semana', 'tiene'], ['siete']], 'Una semana tiene siete días.', [Kit::word('la semana', 'semana')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.once', 'It is eleven o\'clock on the dot on my watch.', ['Son las once en punto en mi reloj.', 'En mi reloj son las once en punto.'], [Kit::word('en punto'), Kit::word('el reloj', 'reloj'), Kit::form('Son')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hora', 'What time is it on the watch?', ['¿Qué hora es en el reloj?'], [Kit::word('la hora', 'hora'), Kit::word('el reloj', 'reloj'), Kit::form('es', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.manana', 'Tomorrow is Sunday.', ['Mañana es domingo.'], [Kit::word('mañana'), Kit::word('el domingo', 'domingo'), Kit::form('Es')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.semana', 'The week has seven days and today is Monday.', ['La semana tiene siete días y hoy es lunes.'], [Kit::word('la semana', 'semana'), Kit::word('el día', 'días'), Kit::word('hoy'), Kit::word('el lunes', 'lunes')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.semana', 'Luis, la ___ tiene siete días.', 'Luis, the week has seven days.', 'semana', Kit::word('la semana', 'semana'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.hora', '¿Qué ___ es, Luis?', 'What time is it, Luis?', 'hora', Kit::word('la hora', 'hora'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.doce-media', 'Mañana voy a las doce y media.', 'Tomorrow I go at half past twelve.', [Kit::word('mañana'), Kit::word('y media'), Kit::form('a las', true)], 'dictation', $set, homophoneNote: 'A (at) sounds the same as ha. Here it is the word for at.'),
            Kit::listenType($stage, 'check.b.listen_type.lunes', '¿Qué día es hoy, lunes o domingo?', 'What day is it today, Monday or Sunday?', [Kit::word('el día', 'día'), Kit::word('hoy'), Kit::word('el lunes', 'lunes'), Kit::word('el domingo', 'domingo'), Kit::form('es')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.doce', '¿Son las doce en punto o las doce y media?', 'Is it twelve o\'clock on the dot or half past twelve?', [Kit::word('en punto'), Kit::word('y media'), Kit::form('Son', true)], 'dictation', $set),
        ];
    }
}
