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

final class PlansForNextWeek implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a, as in voy a.';

    private const VERBS = ['descansar', 'pasear', 'ver', 'cenar', 'trabajar', 'bailar', 'cantar', 'jugar', 'leer', 'salir', 'hacer', 'ir', 'invitar', 'quedar'];

    private const DAYS = ['hoy', 'mañana', 'lunes', 'domingo', 'semana'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'plans-for-next-week';
    }

    public function words(): array
    {
        return [
            new WordData('la cita', cue: 'appointment (a fixed time with someone)', note: 'La cita is an appointment, for example with the doctor. It can also be a date with a partner.'),
            new WordData('el concierto', cue: 'concert'),
            new WordData('el cumpleaños', cue: 'birthday', note: 'El cumpleaños is singular: un cumpleaños, dos cumpleaños.'),
            new WordData('el fin de semana', cue: 'weekend'),
            new WordData('la reunión', cue: 'meeting (at work)'),
            new WordData('el partido', cue: 'match (a game of football or tennis)'),
            new WordData('la excursión', cue: 'trip (a short outing)', note: 'La excursión is a short trip or outing, often for a day. A long journey is a viaje.'),
            new WordData('el teatro', cue: 'theatre'),
            new WordData('descansar', cue: 'to rest'),
            new WordData('pasear', cue: 'to go for a walk (to stroll)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mañana voy a descansar.', 'english' => 'Tomorrow I am going to rest.'],
            ['text' => 'La semana que viene vamos al teatro.', 'english' => 'Next week we are going to the theatre.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-08', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-08', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-08', 'Released on the owner\'s instruction on 2026-10-08, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.voy-descansar', 'Yo ___ a descansar mañana.', ['voy', 'vas', 'va'], 'voy', Kit::form('voy', true), 'Yo goes with voy. Vas is for tú and va is for él or ella. After the form of ir come a and the infinitive.', 'choose', 'I am going to rest tomorrow.'),
            Kit::gap($stage, 'sentences.choose_gap.van-partido', 'Ellos ___ a ver el partido.', ['van', 'va', 'vamos'], 'van', Kit::form('van', true), 'Ellos means more than one person, so you need van. Va is for one person and vamos is for nosotros.', 'choose', 'They are going to watch the match.'),
            Kit::gap($stage, 'sentences.choose_gap.partido', 'Voy a ver un ___ de fútbol.', ['partido', 'cita', 'concierto'], 'partido', Kit::word('el partido', 'partido'), 'Un partido de fútbol is a football match. Cita is feminine, so it would be una cita. A concierto is music, not football.', 'choose', 'I am going to watch a football match.'),
            Kit::gap($stage, 'sentences.choose_gap.manana', 'Hoy es domingo y ___ es lunes.', ['mañana', 'pasado mañana', 'el próximo'], 'mañana', Kit::form('mañana', true), 'Mañana is the next day, so after Sunday it is Monday. Pasado mañana is one day further, and el próximo needs a day or a week after it.', 'choose', 'Today is Sunday and tomorrow is Monday.'),
            Kit::gap($stage, 'sentences.choose_gap.proxima', 'La ___ semana voy al teatro.', ['próxima', 'próximo'], 'próxima', Kit::form('próxima', true), 'Semana is feminine, so it takes próxima. Próximo goes with a masculine word, like el próximo lunes.', 'choose', 'Next week I am going to the theatre.'),
            Kit::gap($stage, 'sentences.choose_gap.cita-medico', 'Mañana tengo una ___ con el médico.', ['cita', 'concierto', 'excursión'], 'cita', Kit::word('la cita', 'cita'), 'You have an appointment with a doctor: una cita. Concierto is masculine, so it would be un concierto, and it is not something you have with a doctor.', 'choose', 'Tomorrow I have an appointment with the doctor.'),

            Kit::typeGap($stage, 'sentences.type_gap.vamos-cenar', 'Nosotros ___ a cenar en el café.', 'We are going to have dinner at the café.', 'vamos', Kit::form('vamos'), 'Nosotros goes with vamos: vamos a + infinitive.'),
            Kit::typeGap($stage, 'sentences.type_gap.cumpleanos', 'Mi ___ es el domingo.', 'My birthday is on Sunday.', 'cumpleaños', Kit::word('el cumpleaños', 'cumpleaños')),
            Kit::typeGap($stage, 'sentences.type_gap.teatro', 'Vamos al ___ con Marta.', 'We are going to the theatre with Marta.', 'teatro', Kit::word('el teatro', 'teatro')),
            Kit::typeGap($stage, 'sentences.type_gap.va-descansar', 'Pablo ___ a descansar en casa.', 'Pablo is going to rest at home.', 'va', Kit::form('va'), 'Pablo is one person, so you need va: va a + infinitive.'),
            Kit::typeGap($stage, 'sentences.type_gap.reunion', 'Tengo una ___ el lunes.', 'I have a meeting on Monday.', 'reunión', Kit::word('la reunión', 'reunión')),

            Kit::translate($stage, 'sentences.translate.descansar-casa', 'Tomorrow I am going to rest at home.', ['Mañana voy a descansar en casa.', 'Mañana yo voy a descansar en casa.', 'Voy a descansar en casa mañana.', 'Yo voy a descansar en casa mañana.'], [Kit::word('descansar'), Kit::form('voy a')]),
            Kit::translate($stage, 'sentences.translate.partido-domingo', 'On Sunday we are going to watch the match.', ['El domingo vamos a ver el partido.', 'Vamos a ver el partido el domingo.'], [Kit::word('el partido', 'partido'), Kit::form('vamos a')]),
            Kit::translate($stage, 'sentences.translate.concierto-marta', 'Next week we are going to the concert.', ['La semana que viene vamos al concierto.', 'Vamos al concierto la semana que viene.', 'La próxima semana vamos al concierto.', 'Vamos al concierto la próxima semana.'], [Kit::word('el concierto', 'concierto'), Kit::form('la semana que viene', false, ['la próxima semana'])]),

            Kit::build($stage, 'sentences.build.descansar-fin', 'I am going to rest at the weekend (start with the verb).', 'Voy a descansar el fin de semana.', ['vas'], [Kit::word('descansar'), Kit::word('el fin de semana', 'fin de semana'), Kit::form('voy a')]),
            Kit::build($stage, 'sentences.build.pasear-parque', 'We are going for a walk in the park.', 'Vamos a pasear por el parque.', ['voy'], [Kit::word('pasear'), Kit::form('vamos a')]),
            Kit::build($stage, 'sentences.build.reunion-manana', 'Tomorrow I have a meeting (start with the time).', 'Mañana tengo una reunión.', ['tiene'], [Kit::word('la reunión', 'reunión'), Kit::form('mañana')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.descansar', 'Mañana voy a descansar.', ['Tomorrow I am going to rest.', 'Yesterday I rested.', 'Tomorrow I am going to work.', 'I am resting now.'], 'Tomorrow I am going to rest.', [Kit::word('descansar'), Kit::form('voy a')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.partido', '¿Vas a ver el partido?', ['Are you going to watch the match?', 'Are you going to play in the match?', 'Is Pablo going to watch the match?', 'Am I going to watch the match?'], 'Are you going to watch the match?', [Kit::word('el partido', 'partido'), Kit::form('vas a')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cita', 'Tengo una cita el lunes.', ['I have an appointment on Monday.', 'I have an appointment on Sunday.', 'I have a party on Monday.', 'Marta has an appointment on Monday.'], 'I have an appointment on Monday.', [Kit::word('la cita', 'cita')]),
            Kit::listenType($stage, 'sentences.listen_type.pasear', 'El domingo vamos a pasear.', 'On Sunday we are going for a walk.', [Kit::word('pasear'), Kit::form('vamos a')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.luis-fin', 'Va a descansar el fin de semana.', 'He is going to rest at the weekend.', [Kit::word('el fin de semana', 'fin de semana'), Kit::form('va a')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.cumpleanos', 'El cumpleaños de Luis es mañana.', 'Luis\'s birthday is tomorrow.', [Kit::word('el cumpleaños', 'cumpleaños'), Kit::form('mañana')]),
            Kit::listenType($stage, 'sentences.listen_type.excursion', 'La excursión es pasado mañana.', 'The trip is the day after tomorrow.', [Kit::word('la excursión', 'excursión'), Kit::form('pasado mañana')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.teatro', 'Mañana voy al teatro.', 'Tomorrow I am going to the theatre.', [Kit::word('el teatro', 'teatro'), Kit::form('mañana')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.concierto', 'El próximo domingo vamos al concierto.', 'Next Sunday we are going to the concert.', [Kit::word('el concierto', 'concierto'), Kit::form('el próximo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.partido', 'Vamos a ver un partido.', 'We are going to watch a match.', [Kit::word('el partido', 'partido'), Kit::form('vamos a')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cita-reunion', 'Marta tiene una cita y una reunión.', 'Marta has an appointment and a meeting.', [Kit::word('la cita', 'cita'), Kit::word('la reunión', 'reunión')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.domingo', '¿Qué vas a hacer el domingo?', 'What are you going to do on Sunday?', [['voy', 'vamos'], self::VERBS], 'El domingo voy a pasear.', [Kit::word('pasear'), Kit::form('voy a')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.concierto-teatro', '¿Vas al concierto o al teatro?', 'Are you going to the concert or to the theatre?', [['voy'], ['concierto', 'teatro']], 'Voy al teatro.', [Kit::word('el concierto', 'concierto'), Kit::word('el teatro', 'teatro')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.excursion', '¿Cuándo es la excursión?', 'When is the trip?', [self::DAYS], 'La excursión es mañana.', [Kit::word('la excursión', 'excursión')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fin-de-semana', 'Read the conversation about the weekend.', [
                Kit::line('Ana', 'Pablo, ¿qué vas a hacer el fin de semana?'),
                Kit::line('Pablo', 'El domingo voy a ver un partido. ¿Y tú?'),
                Kit::line('Ana', 'Yo voy a descansar. Pero mañana tengo una cita.'),
                Kit::line('Pablo', 'La semana que viene hay un concierto. ¿Quieres venir?'),
                Kit::line('Ana', 'Claro, vamos.'),
            ], [
                Kit::question('What is Pablo going to do on Sunday?', ['Watch a match', 'Go to a concert', 'Rest'], 'Watch a match'),
                Kit::question('What does Ana have tomorrow?', ['An appointment', 'A meeting', 'A birthday'], 'An appointment'),
                Kit::question('When is the concert?', ['Next week', 'Tomorrow', 'On Sunday'], 'Next week'),
            ], [Kit::word('el fin de semana', 'fin de semana'), Kit::word('el partido', 'partido'), Kit::word('descansar'), Kit::word('la cita', 'cita'), Kit::word('el concierto', 'concierto')], 'read'),
            Kit::gap($stage, 'task.choose_gap.cumpleanos-marta', 'Pasado mañana es mi ___. Voy a invitar a Marta.', ['cumpleaños', 'excursión', 'teatro'], 'cumpleaños', Kit::word('el cumpleaños', 'cumpleaños'), 'Mi cumpleaños is an event you invite people to. Es mi excursión does not sound right, and el teatro is a place, not an event.', 'read', 'The day after tomorrow is my birthday. I am going to invite Marta.'),
            Kit::gap($stage, 'task.choose_gap.pasear-pueblo', 'Ana y yo vamos a ___ por el pueblo.', ['pasear', 'descansar', 'cenar'], 'pasear', Kit::word('pasear'), 'Pasear is to go for a walk, which fits por el pueblo. Descansar is to rest and cenar is to have dinner.', 'read', 'Ana and I are going for a walk through the village.', ['pueblo' => 'village']),

            Kit::transform($stage, 'task.transform.partido', 'Say that you and Ana are going to do it.', 'Voy a ver el partido.', ['Vamos a ver el partido.', 'Nosotros vamos a ver el partido.'], [Kit::word('el partido', 'partido'), Kit::form('vamos a')]),
            Kit::transform($stage, 'task.transform.pasear', 'Ask a friend instead (informal you).', 'Voy a pasear por el parque.', ['¿Vas a pasear por el parque?', '¿Tú vas a pasear por el parque?'], [Kit::word('pasear'), Kit::form('vas a')]),
            Kit::transform($stage, 'task.transform.teatro', 'Say it about the day after tomorrow.', 'Mañana voy al teatro.', ['Pasado mañana voy al teatro.', 'Pasado mañana yo voy al teatro.', 'Voy al teatro pasado mañana.', 'Yo voy al teatro pasado mañana.'], [Kit::word('el teatro', 'teatro'), Kit::form('pasado mañana', true)]),
            Kit::writeGuided($stage, 'task.write_guided.reunion-descansar', 'Say that tomorrow you have a meeting and that the day after tomorrow you are going to rest.', ['mañana tengo', 'reunión', 'pasado mañana', 'voy a', 'descansar'], 'Mañana tengo una reunión y pasado mañana voy a descansar.', [
                ['forms' => ['reunión'], 'term' => 'la reunión'],
                ['forms' => ['pasado'], 'term' => null],
                ['forms' => ['descansar'], 'term' => 'descansar'],
            ], [Kit::word('la reunión', 'reunión'), Kit::word('descansar'), Kit::form('pasado mañana')]),
            Kit::writeGuided($stage, 'task.write_guided.cumpleanos-domingo', 'Say that the birthday of Luis is on Sunday and that you are going to invite Ana.', ['el cumpleaños', 'domingo', 'voy a', 'invitar', 'Ana'], 'El cumpleaños de Luis es el domingo y voy a invitar a Ana.', [
                ['forms' => ['cumpleaños'], 'term' => 'el cumpleaños'],
                ['forms' => ['domingo'], 'term' => null],
                ['forms' => ['voy'], 'term' => null],
                ['forms' => ['invitar'], 'term' => null],
            ], [Kit::word('el cumpleaños', 'cumpleaños'), Kit::form('voy a')], ['invitar' => 'to invite']),
            Kit::build($stage, 'task.build.teatro-domingo', 'Next Sunday we are going to the theatre (start with the time).', 'El próximo domingo vamos al teatro.', ['voy', 'la'], [Kit::word('el teatro', 'teatro'), Kit::form('el próximo')]),
            Kit::build($stage, 'task.build.reunion-luis', 'On Monday Pablo has a meeting with Luis (start with the time).', 'El lunes Pablo tiene una reunión con Luis.', ['va', 'a'], [Kit::word('la reunión', 'reunión')]),
            Kit::build($stage, 'task.build.partido-excursion', 'The match is on Sunday and the trip is the day after tomorrow.', 'El partido es el domingo y la excursión es pasado mañana.', ['hoy', 'vas'], [Kit::word('el partido', 'partido'), Kit::word('la excursión', 'excursión'), Kit::form('pasado mañana')]),
            Kit::translate($stage, 'task.translate.cumpleanos-semana', 'My birthday is next week.', ['Mi cumpleaños es la semana que viene.', 'Mi cumpleaños es la próxima semana.', 'La semana que viene es mi cumpleaños.', 'La próxima semana es mi cumpleaños.'], [Kit::word('el cumpleaños', 'cumpleaños'), Kit::form('la semana que viene', false, ['la próxima semana'])]),
            Kit::translate($stage, 'task.translate.concierto-teatro', 'The day after tomorrow we are going to the concert and the theatre.', ['Pasado mañana vamos al concierto y al teatro.', 'Vamos al concierto y al teatro pasado mañana.', 'Pasado mañana nosotros vamos al concierto y al teatro.', 'Nosotros vamos al concierto y al teatro pasado mañana.'], [Kit::word('el concierto', 'concierto'), Kit::word('el teatro', 'teatro'), Kit::form('pasado mañana')]),

            Kit::listenPassage($stage, 'task.listen_passage.excursion-domingo', [
                Kit::line('Marta', 'Luis, ¿qué vas a hacer mañana?'),
                Kit::line('Luis', 'Mañana voy a trabajar. Pero el domingo tengo una excursión.'),
                Kit::line('Marta', '¿Con quién vas?'),
                Kit::line('Luis', 'Con Pablo. Vamos a pasear por el parque.'),
                Kit::line('Marta', 'Qué bien. Yo voy a ver un partido con Ana.'),
            ], [
                Kit::question('What is Luis going to do tomorrow?', ['Work', 'Rest', 'Go to the theatre'], 'Work'),
                Kit::question('Who is Luis going on the trip with?', ['Pablo', 'Ana', 'Marta'], 'Pablo'),
                Kit::question('What is Marta going to do?', ['Watch a match', 'Go for a walk', 'Rest'], 'Watch a match'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is Luis working tomorrow?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la excursión', 'excursión'), Kit::word('pasear'), Kit::word('el partido', 'partido')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.proximo-cita', 'El próximo lunes tengo una cita con el médico.', 'Next Monday I have an appointment with the doctor.', [Kit::word('la cita', 'cita'), Kit::form('el próximo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.teatro-ana', 'La semana que viene Ana va a cantar en el teatro.', 'Next week Ana is going to sing at the theatre.', [Kit::word('el teatro', 'teatro'), Kit::form('la semana que viene')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.cumpleanos-concierto', 'Pasado mañana es el cumpleaños de Luis y vamos al concierto.', 'The day after tomorrow is the birthday of Luis and we are going to the concert.', [Kit::word('el cumpleaños', 'cumpleaños'), Kit::word('el concierto', 'concierto'), Kit::form('pasado mañana')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.manana', '¿Qué vas a hacer mañana?', 'What are you going to do tomorrow?', [['voy', 'vamos'], self::VERBS], 'Mañana voy a descansar.', [Kit::word('descansar'), Kit::form('voy a')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cita-reunion', '¿Tienes una cita o una reunión?', 'Do you have an appointment or a meeting?', [['tengo'], ['cita', 'reunión']], 'Tengo una reunión.', [Kit::word('la cita', 'cita'), Kit::word('la reunión', 'reunión')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.partido', '¿Cuándo es el partido?', 'When is the match?', [self::DAYS], 'El partido es el domingo.', [Kit::word('el partido', 'partido')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.fin-de-semana', '¿Qué vas a hacer el fin de semana?', 'What are you going to do at the weekend?', [['voy', 'vamos'], self::VERBS], 'El fin de semana voy a descansar.', [Kit::word('el fin de semana', 'fin de semana'), Kit::word('descansar'), Kit::form('voy a')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cita', 'Pasado mañana tengo una cita.', 'The day after tomorrow I have an appointment.', [Kit::word('la cita', 'cita'), Kit::form('pasado mañana')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cumpleanos', 'El próximo domingo es el cumpleaños de Ana.', 'Next Sunday is Ana\'s birthday.', [Kit::word('el cumpleaños', 'cumpleaños'), Kit::form('el próximo')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.descansar-teatro', 'Tomorrow I am going to rest and the day after tomorrow I am going to the theatre.', ['Mañana voy a descansar y pasado mañana voy al teatro.', 'Mañana voy a descansar y pasado mañana al teatro.'], [Kit::word('descansar'), Kit::word('el teatro', 'teatro'), Kit::form('voy a')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.proximo-reunion', 'Next Monday Pablo has a meeting.', ['El próximo lunes Pablo tiene una reunión.', 'Pablo tiene una reunión el próximo lunes.'], [Kit::word('la reunión', 'reunión'), Kit::form('el próximo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-marta-pasear', 'Ana and Marta are going for a walk at the weekend.', ['Ana y Marta van a pasear el fin de semana.', 'El fin de semana Ana y Marta van a pasear.'], [Kit::word('pasear'), Kit::word('el fin de semana', 'fin de semana'), Kit::form('van a')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.cumpleanos-pasado', 'My birthday is the day after tomorrow.', ['Mi cumpleaños es pasado mañana.', 'Pasado mañana es mi cumpleaños.'], [Kit::word('el cumpleaños', 'cumpleaños'), Kit::form('pasado mañana', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.cita-medico', 'La semana que viene tengo una ___ con Luis.', 'Next week I have an appointment with Luis.', 'cita', Kit::word('la cita', 'cita'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ana-descansar', 'Pasado mañana Ana va a ___ en el parque.', 'The day after tomorrow Ana is going to rest in the park.', 'descansar', Kit::word('descansar'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-concierto', 'Mañana Marta va a cantar en el concierto.', 'Tomorrow Marta is going to sing at the concert.', [Kit::word('el concierto', 'concierto'), Kit::form('va a')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.cita-partido', 'Luis tiene una cita y un partido el lunes.', 'Luis has an appointment and a match on Monday.', [Kit::word('la cita', 'cita'), Kit::word('el partido', 'partido')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.excursion-pueblo', 'En la excursión vamos a pasear por el pueblo.', 'On the trip we are going for a walk through the village.', [Kit::word('la excursión', 'excursión'), Kit::word('pasear'), Kit::form('vamos a')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.cumpleanos', [
                Kit::line('Luis', 'Marta, mañana es mi cumpleaños.'),
                Kit::line('Marta', '¿Sí? ¿Qué vas a hacer?'),
                Kit::line('Luis', 'Vamos a cenar en casa. ¿Quieres venir?'),
                Kit::line('Marta', 'Claro, pero tengo una reunión a las cinco.'),
                Kit::line('Luis', 'Es a las ocho.'),
                Kit::line('Marta', 'Sí, voy.'),
            ], [
                Kit::question('When is the birthday of Luis?', ['Tomorrow', 'Today', 'On Sunday'], 'Tomorrow'),
                Kit::question('What are they going to do?', ['Have dinner at home', 'Go to the theatre', 'Go to a concert'], 'Have dinner at home'),
                Kit::question('What does Marta have at five?', ['A meeting', 'An appointment', 'A match'], 'A meeting'),
            ], [
                Kit::question('Whose birthday is it?', ['Luis', 'Marta', 'Nobody'], 'Luis'),
                Kit::question('Does Marta want to come?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el cumpleaños', 'cumpleaños'), Kit::word('la reunión', 'reunión')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.concierto', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿vas al concierto de Marta?'),
                Kit::line('Pablo', 'Sí, voy el domingo. ¿Quieres venir?'),
                Kit::line('Ana', 'Lo siento, el domingo tengo una excursión.'),
                Kit::line('Pablo', '¿Vamos al teatro el lunes?'),
                Kit::line('Ana', 'Sí, claro.'),
            ], [
                Kit::question('When is Pablo going to the concert?', ['On Sunday', 'On Monday', 'Tomorrow'], 'On Sunday'),
                Kit::question('What does Ana have on Sunday?', ['A trip', 'A meeting', 'An appointment'], 'A trip'),
            ], [Kit::word('el concierto', 'concierto'), Kit::word('la excursión', 'excursión'), Kit::word('el teatro', 'teatro')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.partido', '¿Vas a ver el partido el domingo?', 'Are you going to watch the match on Sunday?', [['sí', 'no'], ['voy', 'vamos', 'partido']], 'Sí, voy a ver el partido.', [Kit::word('el partido', 'partido'), Kit::form('voy a')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cumpleanos', '¿Es mañana el cumpleaños de Ana?', 'Is Ana\'s birthday tomorrow?', [['sí', 'no'], ['mañana', 'cumpleaños', 'es']], 'Sí, es mañana.', [Kit::word('el cumpleaños', 'cumpleaños')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fin-de-semana', '¿Vas a descansar el fin de semana?', 'Are you going to rest at the weekend?', [['sí', 'no'], ['voy', 'vamos', 'descansar']], 'Sí, el fin de semana voy a descansar.', [Kit::word('descansar'), Kit::word('el fin de semana', 'fin de semana')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.cita-reunion-partido', 'Tomorrow I have an appointment, a meeting and a match.', ['Mañana tengo una cita, una reunión y un partido.', 'Tengo una cita, una reunión y un partido mañana.'], [Kit::word('la cita', 'cita'), Kit::word('la reunión', 'reunión'), Kit::word('el partido', 'partido'), Kit::form('mañana')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.fin-descansar-pasear', 'At the weekend we are going to rest and go for a walk.', ['El fin de semana vamos a descansar y a pasear.', 'El fin de semana vamos a descansar y pasear.'], [Kit::word('el fin de semana', 'fin de semana'), Kit::word('descansar'), Kit::word('pasear'), Kit::form('vamos a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.teatro-concierto', 'The day after tomorrow we are going to the theatre and to the concert.', ['Pasado mañana vamos al teatro y al concierto.', 'Vamos al teatro y al concierto pasado mañana.'], [Kit::word('el teatro', 'teatro'), Kit::word('el concierto', 'concierto'), Kit::form('pasado mañana', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.cumpleanos-excursion', 'Next Sunday is my birthday and there is a trip.', ['El próximo domingo es mi cumpleaños y hay una excursión.', 'Mi cumpleaños es el próximo domingo y hay una excursión.'], [Kit::word('el cumpleaños', 'cumpleaños'), Kit::word('la excursión', 'excursión'), Kit::form('el próximo', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-luis-van', 'Pablo y Ana ___ a cenar en casa el domingo.', 'Pablo and Ana are going to have dinner at home on Sunday.', 'van', Kit::form('van'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.reunion-luis', 'Hoy tengo una ___ con Ana.', 'Today I have a meeting with Ana.', 'reunión', Kit::word('la reunión', 'reunión'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.pablo-descansar-pasear', 'Pablo va a descansar y pasear el fin de semana.', 'Pablo is going to rest and go for a walk at the weekend.', [Kit::word('descansar'), Kit::word('pasear'), Kit::word('el fin de semana', 'fin de semana'), Kit::form('va a')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.partido-excursion', 'Mi cumpleaños es el día del partido y la excursión.', 'My birthday is the day of the match and the trip.', [Kit::word('el partido', 'partido'), Kit::word('la excursión', 'excursión'), Kit::word('el cumpleaños', 'cumpleaños')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.luis-cita-concierto', 'Luis tiene una cita y un concierto en el teatro.', 'Luis has an appointment and a concert at the theatre.', [Kit::word('la cita', 'cita'), Kit::word('el concierto', 'concierto'), Kit::word('el teatro', 'teatro')], 'dictation', $set),
        ];
    }
}
