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

final class ReasonsAndPurposes implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a, as in voy a.';

    private const PEOPLE = ['ana', 'pablo', 'marta', 'luis', 'madre', 'padre', 'hermano', 'hermana', 'familia', 'jefe', 'hijo'];

    private const WHEN = ['lunes', 'domingo', 'hoy', 'mañana', 'semana', 'mes'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'reasons-and-purposes';
    }

    public function words(): array
    {
        return [
            new WordData('el examen', cue: 'exam'),
            new WordData('la boda', cue: 'wedding'),
            new WordData('la tarta', cue: 'cake (a large cake or tart)'),
            new WordData('el paquete', cue: 'parcel (package)'),
            new WordData('el ramo', cue: 'bouquet (bunch of flowers)'),
            new WordData('el dinero', cue: 'money', note: 'El dinero is singular: el dinero, never los dineros. Dutch uses het geld.'),
            new WordData('ahorrar', cue: 'to save (money)', forms: ['ahorro', 'ahorra', 'ahorras', 'ahorramos'], note: 'Ahorrar is to save money or time, Dutch sparen.'),
            new WordData('el proyecto', cue: 'project (at work or school)'),
            new WordData('el mes', cue: 'month', forms: ['meses'], note: 'El mes is masculine: un mes, dos meses.'),
            new WordData('la fecha', cue: 'date (day on the calendar)', note: 'La fecha is a day on the calendar. A date with a person is a different word.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El ramo es para Marta.', 'english' => 'The bouquet is for Marta.'],
            ['text' => 'Gracias por el paquete.', 'english' => 'Thanks for the parcel.'],
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
            Kit::gap($stage, 'sentences.choose_gap.regalo-ana', 'El regalo es ___ Ana.', ['para', 'por'], 'para', Kit::form('para', true), 'Ana is the person who gets the gift, so you need para. Por would mean because of Ana.', 'choose', 'The gift is for Ana.'),
            Kit::gap($stage, 'sentences.choose_gap.gracias-regalo', 'Gracias ___ el regalo.', ['por', 'para'], 'por', Kit::form('gracias por', true), 'To thank someone for something you always say gracias por. Gracias para is not Spanish.', 'choose', 'Thanks for the gift.'),
            Kit::gap($stage, 'sentences.choose_gap.boda', 'Hago una tarta para la ___.', ['boda', 'proyecto', 'dinero'], 'boda', Kit::word('la boda', 'boda'), 'A cake is made for a celebration: la boda. El proyecto and el dinero are masculine, so they would need el, not la.', 'choose', 'I am making a cake for the wedding.'),
            Kit::gap($stage, 'sentences.choose_gap.dinero', 'Ahorro ___ para la boda.', ['dinero', 'paquete', 'examen'], 'dinero', Kit::word('el dinero', 'dinero'), 'You save money: ahorro dinero. You do not save a parcel or an exam.', 'choose', 'I am saving money for the wedding.'),
            Kit::gap($stage, 'sentences.choose_gap.examen', 'Tengo un ___ el lunes.', ['examen', 'boda', 'tarta'], 'examen', Kit::word('el examen', 'examen'), 'Un goes with a masculine word, and el examen is masculine. Boda and tarta are feminine, so they would need una.', 'choose', 'I have an exam on Monday.'),
            Kit::gap($stage, 'sentences.choose_gap.fecha', 'La ___ de la boda es el lunes.', ['fecha', 'mes', 'examen'], 'fecha', Kit::word('la fecha', 'fecha'), 'La fecha is the day on the calendar. El mes and el examen are masculine, so they do not go with la.', 'choose', 'The date of the wedding is Monday.'),

            Kit::typeGap($stage, 'sentences.type_gap.proyecto-lunes', 'El proyecto es ___ el lunes.', 'The project is for Monday.', 'para', Kit::form('para', true), 'A deadline is para: para el lunes, like Dutch voor maandag.'),
            Kit::typeGap($stage, 'sentences.type_gap.trabajo-noche', 'Trabajo ___ la noche.', 'I work at night.', 'por', Kit::form('por', true), 'A part of the day uses por: por la noche.'),
            Kit::typeGap($stage, 'sentences.type_gap.tarta', 'Pago veinte euros por la ___.', 'I pay twenty euros for the cake.', 'tarta', Kit::word('la tarta', 'tarta')),
            Kit::typeGap($stage, 'sentences.type_gap.paquete', 'Correos trae un ___ para Marta.', 'The post office brings a parcel for Marta.', 'paquete', Kit::word('el paquete', 'paquete')),
            Kit::typeGap($stage, 'sentences.type_gap.mes', 'Vamos a España por un ___.', 'We are going to Spain for a month.', 'mes', Kit::word('el mes', 'mes')),

            Kit::translate($stage, 'sentences.translate.proyecto-lunes', 'Pablo has a project for Monday.', ['Pablo tiene un proyecto para el lunes.'], [Kit::word('el proyecto', 'proyecto'), Kit::form('para el lunes')]),
            Kit::translate($stage, 'sentences.translate.ahorro-espana', 'I save money to go to Spain.', ['Ahorro dinero para ir a España.', 'Yo ahorro dinero para ir a España.'], [Kit::word('ahorrar', 'ahorro'), Kit::word('el dinero', 'dinero'), Kit::form('para ir')]),
            Kit::translate($stage, 'sentences.translate.espana-mes', 'We are going to Spain for a month.', ['Vamos a España por un mes.', 'Nosotros vamos a España por un mes.', 'Por un mes vamos a España.'], [Kit::word('el mes', 'mes'), Kit::form('por un mes')]),

            Kit::build($stage, 'sentences.build.tarta-boda', 'The cake is for the wedding.', 'La tarta es para la boda.', ['por'], [Kit::word('la tarta', 'tarta'), Kit::word('la boda', 'boda'), Kit::form('para', true)]),
            Kit::build($stage, 'sentences.build.gracias-paquete', 'Thanks for the parcel.', 'Gracias por el paquete.', ['para'], [Kit::word('el paquete', 'paquete'), Kit::form('gracias por', true)]),
            Kit::build($stage, 'sentences.build.ramo-marta', 'Marta has a bouquet for the wedding.', 'Marta tiene un ramo para la boda.', ['por'], [Kit::word('el ramo', 'ramo'), Kit::form('para')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.paquete', 'El paquete es para Marta.', ['The parcel is for Marta.', 'The parcel is from Marta.', 'Marta has no parcel.', 'The parcel is for the wedding.'], 'The parcel is for Marta.', [Kit::word('el paquete', 'paquete'), Kit::form('es para')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.gracias-tarta', 'Gracias por la tarta.', ['Thank you for the cake.', 'Thank you for the wedding.', 'The cake is for you.', 'I am making a cake.'], 'Thank you for the cake.', [Kit::word('la tarta', 'tarta'), Kit::form('gracias por')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ahorro', 'Ahorro dinero para la boda.', ['I am saving money for the wedding.', 'I am paying money at the wedding.', 'Marta is saving money for the wedding.', 'I have no money for the wedding.'], 'I am saving money for the wedding.', [Kit::word('ahorrar', 'ahorro'), Kit::word('el dinero', 'dinero'), Kit::form('para')]),
            Kit::listenType($stage, 'sentences.listen_type.examen-mes', 'El examen es en un mes.', 'The exam is in a month.', [Kit::word('el examen', 'examen'), Kit::word('el mes', 'mes')]),
            Kit::listenType($stage, 'sentences.listen_type.proyecto-noche', 'Trabajo en el proyecto por la noche.', 'I work on the project at night.', [Kit::word('el proyecto', 'proyecto'), Kit::form('por la noche')]),
            Kit::listenType($stage, 'sentences.listen_type.fecha-boda', 'La fecha de la boda es hoy.', 'The date of the wedding is today.', [Kit::word('la fecha', 'fecha'), Kit::word('la boda', 'boda')]),
            Kit::listenType($stage, 'sentences.listen_type.oficina', 'Voy a la oficina para trabajar.', 'I go to the office to work.', [Kit::form('para trabajar')], homophoneNote: self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ramo', 'El ramo es para Marta.', 'The bouquet is for Marta.', [Kit::word('el ramo', 'ramo'), Kit::form('es para')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ahorramos', 'Ahorramos dinero para la boda.', 'We save money for the wedding.', [Kit::word('ahorrar', 'ahorramos'), Kit::word('el dinero', 'dinero'), Kit::word('la boda', 'boda'), Kit::form('para')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fecha-examen', 'La fecha del examen es el lunes.', 'The date of the exam is Monday.', [Kit::word('la fecha', 'fecha'), Kit::word('el examen', 'examen')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.examen-mes', 'Luis tiene un examen en un mes.', 'Luis has an exam in a month.', [Kit::word('el examen', 'examen'), Kit::word('el mes', 'mes')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.regalo', '¿Para quién es el regalo?', 'Who is the gift for?', [['es', 'para'], self::PEOPLE], 'El regalo es para Ana.', [Kit::form('es para')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ahorras', '¿Para qué ahorras dinero?', 'What do you save money for?', [['para'], ['boda', 'proyecto', 'fiesta', 'regalo', 'casa', 'españa', 'coche', 'ir']], 'Ahorro para la boda.', [Kit::word('ahorrar', 'ahorro'), Kit::form('para')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.no-sales', '¿Por qué no sales hoy?', 'Why are you not going out today?', [['examen', 'proyecto', 'trabajo', 'lluvia', 'boda']], 'No salgo por el examen.', [Kit::word('el examen', 'examen'), Kit::form('por')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.ahorro-boda', 'Read the conversation about the wedding.', [
                Kit::line('Ana', 'Pablo, ¿para qué ahorras dinero?'),
                Kit::line('Pablo', 'Para la boda de mi hermano. La fecha es en un mes.'),
                Kit::line('Ana', '¿Y tienes un regalo?'),
                Kit::line('Pablo', 'Sí, un ramo para mi madre. Hoy no salgo por el proyecto.'),
                Kit::line('Ana', 'Yo trabajo en el proyecto por la noche.'),
            ], [
                Kit::question('What is Pablo saving money for?', ['His brother\'s wedding', 'A parcel', 'A project'], 'His brother\'s wedding'),
                Kit::question('Who is the bouquet for?', ['His mother', 'Ana', 'His brother'], 'His mother'),
                Kit::question('Why is Pablo not going out today?', ['Because of a project', 'Because of the wedding', 'Because of an exam'], 'Because of a project'),
            ], [Kit::word('ahorrar', 'ahorras'), Kit::word('el dinero', 'dinero'), Kit::word('la boda', 'boda'), Kit::word('la fecha', 'fecha'), Kit::word('el mes', 'mes'), Kit::word('el ramo', 'ramo'), Kit::word('el proyecto', 'proyecto')], 'read'),
            Kit::gap($stage, 'task.choose_gap.pago-ramo', 'Pago cien euros ___ el ramo.', ['por', 'para'], 'por', Kit::form('por', true), 'What you pay for something uses por: pago cien euros por el ramo. Para would say who or what the ramo is meant for.', 'read', 'I pay a hundred euros for the bouquet.'),
            Kit::gap($stage, 'task.choose_gap.ahorro-espana', 'Marta ahorra dinero ___ ir a España.', ['para', 'por'], 'para', Kit::form('para', true), 'Para with an infinitive gives the purpose: para ir means to go. Por would not work before an infinitive here.', 'read', 'Marta is saving money to go to Spain.'),

            Kit::transform($stage, 'task.transform.ramo', 'Change the person to Luis.', 'El ramo es para Ana.', ['El ramo es para Luis.'], [Kit::word('el ramo', 'ramo'), Kit::form('es para')]),
            Kit::transform($stage, 'task.transform.proyecto', 'Say the deadline is Sunday instead.', 'El proyecto es para el lunes.', ['El proyecto es para el domingo.'], [Kit::word('el proyecto', 'proyecto'), Kit::form('para el domingo')]),
            Kit::transform($stage, 'task.transform.meses', 'Say it is for two months.', 'Ana va a España por un mes.', ['Ana va a España por dos meses.'], [Kit::word('el mes', 'meses'), Kit::form('por dos meses')]),
            Kit::writeGuided($stage, 'task.write_guided.boda-fecha', 'Say that you are saving money for the wedding and that the date is in a month.', ['ahorro', 'dinero', 'para', 'boda', 'fecha', 'en un mes'], 'Ahorro dinero para la boda y la fecha es en un mes.', [
                ['forms' => ['ahorro'], 'term' => 'ahorrar'],
                ['forms' => ['dinero'], 'term' => 'el dinero'],
                ['forms' => ['para'], 'term' => null],
                ['forms' => ['boda'], 'term' => 'la boda'],
                ['forms' => ['fecha'], 'term' => 'la fecha'],
            ], [Kit::word('ahorrar', 'ahorro'), Kit::word('el dinero', 'dinero'), Kit::word('la boda', 'boda'), Kit::word('la fecha', 'fecha'), Kit::form('para')]),
            Kit::writeGuided($stage, 'task.write_guided.proyecto-noche', 'Say that you have a project for Monday and that you work at night.', ['tengo', 'proyecto', 'para', 'lunes', 'trabajo', 'por la noche'], 'Tengo un proyecto para el lunes y trabajo por la noche.', [
                ['forms' => ['proyecto'], 'term' => 'el proyecto'],
                ['forms' => ['para'], 'term' => null],
                ['forms' => ['por'], 'term' => null],
                ['forms' => ['noche'], 'term' => null],
            ], [Kit::word('el proyecto', 'proyecto'), Kit::form('por la noche')]),
            Kit::build($stage, 'task.build.pago-tarta', 'I pay ten euros for the bouquet.', 'Pago diez euros por el ramo.', ['para', 'pagas'], [Kit::word('el ramo', 'ramo'), Kit::form('por')]),
            Kit::build($stage, 'task.build.paquete-ramo', 'I have a parcel for Marta and a bouquet for Ana.', 'Tengo un paquete para Marta y un ramo para Ana.', ['por', 'tiene'], [Kit::word('el paquete', 'paquete'), Kit::word('el ramo', 'ramo'), Kit::form('para')]),
            Kit::build($stage, 'task.build.proyecto-ahorrar', 'I work on the project at night to save money.', 'Trabajo en el proyecto por la noche para ahorrar dinero.', ['hago', 'voy'], [Kit::word('el proyecto', 'proyecto'), Kit::word('ahorrar'), Kit::word('el dinero', 'dinero'), Kit::form('para ahorrar')]),
            Kit::translate($stage, 'task.translate.examen-proyecto', 'I have an exam in a month and a project for Monday.', ['Tengo un examen en un mes y un proyecto para el lunes.'], [Kit::word('el examen', 'examen'), Kit::word('el mes', 'mes'), Kit::word('el proyecto', 'proyecto'), Kit::form('para el lunes')]),
            Kit::translate($stage, 'task.translate.pablo-ahorra', 'Pablo saves money for Ana\'s wedding.', ['Pablo ahorra dinero para la boda de Ana.'], [Kit::word('ahorrar', 'ahorra'), Kit::word('el dinero', 'dinero'), Kit::word('la boda', 'boda'), Kit::form('para')]),

            Kit::listenPassage($stage, 'task.listen_passage.tarta-boda', [
                Kit::line('Marta', 'Luis, ¿para quién es la tarta?'),
                Kit::line('Luis', 'Es para la boda de Ana. La fecha es el domingo.'),
                Kit::line('Marta', '¿Y el ramo?'),
                Kit::line('Luis', 'El ramo es para la madre de Ana. Pago cien euros por todo.'),
                Kit::line('Marta', 'Qué bien. Gracias por todo, Luis.'),
            ], [
                Kit::question('What is the cake for?', ['Ana\'s wedding', 'Ana\'s exam', 'A project'], 'Ana\'s wedding'),
                Kit::question('Who is the bouquet for?', ['Ana\'s mother', 'Marta', 'Luis'], 'Ana\'s mother'),
                Kit::question('How much does Luis pay?', ['A hundred euros', 'Twenty euros', 'Fifty euros'], 'A hundred euros'),
            ], [
                Kit::question('Who asks the first question?', ['Marta', 'Luis', 'Ana'], 'Marta'),
                Kit::question('When is the wedding?', ['Sunday', 'Monday', 'Saturday'], 'Sunday'),
                Kit::question('What does Marta thank Luis for?', ['Everything', 'The cake', 'The money'], 'Everything'),
            ], [Kit::word('la tarta', 'tarta'), Kit::word('la boda', 'boda'), Kit::word('la fecha', 'fecha'), Kit::word('el ramo', 'ramo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paquete-proyecto', 'El paquete es para el proyecto de Pablo.', 'The parcel is for Pablo\'s project.', [Kit::word('el paquete', 'paquete'), Kit::word('el proyecto', 'proyecto'), Kit::form('es para')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pablo-espana', 'Pablo trabaja en España por un mes.', 'Pablo works in Spain for a month.', [Kit::word('el mes', 'mes'), Kit::form('por un mes')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.gracias-ramo', 'Gracias por el ramo, es para mi madre.', 'Thanks for the bouquet, it is for my mother.', [Kit::word('el ramo', 'ramo'), Kit::form('gracias por')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.boda', '¿Cuándo es la boda?', 'When is the wedding?', [['es'], self::WHEN], 'La boda es en un mes.', [Kit::word('la boda', 'boda')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ramo', '¿Para quién es el ramo?', 'Who is the bouquet for?', [['es', 'para'], self::PEOPLE], 'El ramo es para mi madre.', [Kit::word('el ramo', 'ramo'), Kit::form('es para')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.proyecto', '¿Para cuándo es el proyecto?', 'When is the project due?', [['para'], self::WHEN], 'Es para el lunes.', [Kit::word('el proyecto', 'proyecto'), Kit::form('para el lunes')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tarta', '¿Cuánto pagas por la tarta?', 'How much do you pay for the cake?', [['pago'], ['euros']], 'Pago veinte euros por la tarta.', [Kit::word('la tarta', 'tarta'), Kit::form('por')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.fecha', 'La fecha de la boda es en un mes.', 'The date of the wedding is in a month.', [Kit::word('la fecha', 'fecha'), Kit::word('la boda', 'boda'), Kit::word('el mes', 'mes')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.examen-proyecto', 'Luis tiene un examen y un proyecto.', 'Luis has an exam and a project.', [Kit::word('el examen', 'examen'), Kit::word('el proyecto', 'proyecto')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.tarta-boda', 'Marta has a cake for the wedding.', ['Marta tiene una tarta para la boda.'], [Kit::word('la tarta', 'tarta'), Kit::word('la boda', 'boda'), Kit::form('para', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.gracias-paquete-ramo', 'Thanks for the parcel and for the bouquet.', ['Gracias por el paquete y por el ramo.', 'Gracias por el paquete y el ramo.'], [Kit::word('el paquete', 'paquete'), Kit::word('el ramo', 'ramo'), Kit::form('gracias por', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fecha-examen', 'The date of the exam is in a month.', ['La fecha del examen es en un mes.'], [Kit::word('la fecha', 'fecha'), Kit::word('el examen', 'examen'), Kit::word('el mes', 'mes')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-proyecto', 'Ana has a project for Sunday.', ['Ana tiene un proyecto para el domingo.'], [Kit::word('el proyecto', 'proyecto'), Kit::form('para el domingo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.tienda-ramo', 'Tengo dinero ___ pagar el ramo.', 'I have money to pay for the bouquet.', 'para', Kit::form('para'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pablo-dinero', 'Pablo ahorra ___ para la fiesta.', 'Pablo saves money for the party.', 'dinero', Kit::word('el dinero', 'dinero'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.luis-ahorra', 'Luis ahorra dinero para un ramo y una tarta.', 'Luis saves money for a bouquet and a cake.', [Kit::word('ahorrar', 'ahorra'), Kit::word('el dinero', 'dinero'), Kit::word('el ramo', 'ramo'), Kit::word('la tarta', 'tarta'), Kit::form('para')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.no-salgo', 'No salgo hoy por el proyecto de Luis.', 'I am not going out today because of Luis\'s project.', [Kit::word('el proyecto', 'proyecto'), Kit::form('por', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.examen-boda', 'Tengo un examen el lunes y una boda el domingo.', 'I have an exam on Monday and a wedding on Sunday.', [Kit::word('el examen', 'examen'), Kit::word('la boda', 'boda')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.ahorrar', [
                Kit::line('Marta', 'Luis, ¿ahorras dinero?'),
                Kit::line('Luis', 'Sí, para ir a España.'),
                Kit::line('Marta', '¿Por cuánto tiempo?'),
                Kit::line('Luis', 'Por un mes. ¿Y tú?'),
                Kit::line('Marta', 'Yo ahorro para la boda de mi hermana.'),
            ], [
                Kit::question('What is Marta saving money for?', ['Her sister\'s wedding', 'A trip', 'An exam'], 'Her sister\'s wedding'),
                Kit::question('What is Luis saving money for?', ['A trip to Spain', 'A wedding', 'A parcel'], 'A trip to Spain'),
                Kit::question('How long is the trip?', ['A month', 'A week', 'Two months'], 'A month'),
            ], [
                Kit::question('Who speaks first?', ['Luis', 'Marta', 'Nobody'], 'Marta'),
                Kit::question('Who asks about the length of the trip?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Whose wedding is Marta saving for?', ['Her sister\'s', 'Her own', 'Luis\'s'], 'Her sister\'s'),
            ], [Kit::word('ahorrar', 'ahorras'), Kit::word('el dinero', 'dinero'), Kit::word('la boda', 'boda'), Kit::word('el mes', 'mes')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ramo-tarta', 'Read the conversation.', [
                Kit::line('Pablo', 'Ana, ¿el ramo es para tu madre?'),
                Kit::line('Ana', 'Sí, y la tarta es para la fiesta del domingo.'),
                Kit::line('Pablo', '¿Cuánto pagas por todo?'),
                Kit::line('Ana', 'Pago cien euros. Gracias por venir, Pablo.'),
            ], [
                Kit::question('Who is the bouquet for?', ['Ana\'s mother', 'Pablo\'s mother', 'Marta'], 'Ana\'s mother'),
                Kit::question('How much does Ana pay?', ['A hundred euros', 'Twenty euros', 'Fifty euros'], 'A hundred euros'),
            ], [Kit::word('el ramo', 'ramo'), Kit::word('la tarta', 'tarta')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.tarta', '¿Para quién es la tarta?', 'Who is the cake for?', [['es', 'para'], self::PEOPLE], 'La tarta es para Marta.', [Kit::word('la tarta', 'tarta'), Kit::form('es para')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ramo-euros', '¿Cuánto pagas por el ramo?', 'How much do you pay for the bouquet?', [['pago'], ['euros']], 'Pago veinte euros por el ramo.', [Kit::word('el ramo', 'ramo'), Kit::form('por')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.examen-proyecto', '¿Tienes un examen o un proyecto?', 'Do you have an exam or a project?', [['tengo'], ['examen', 'proyecto']], 'Tengo un proyecto.', [Kit::word('el examen', 'examen'), Kit::word('el proyecto', 'proyecto')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.paquete-ramo', 'I have a parcel and a bouquet for Luis.', ['Tengo un paquete y un ramo para Luis.'], [Kit::word('el paquete', 'paquete'), Kit::word('el ramo', 'ramo'), Kit::form('para', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.gracias-tarta-dinero', 'Thanks for the cake and for the money.', ['Gracias por la tarta y por el dinero.', 'Gracias por la tarta y el dinero.'], [Kit::word('la tarta', 'tarta'), Kit::word('el dinero', 'dinero'), Kit::form('gracias por', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ahorro-hermana', 'I am saving money for my sister\'s wedding.', ['Ahorro dinero para la boda de mi hermana.', 'Yo ahorro dinero para la boda de mi hermana.'], [Kit::word('ahorrar', 'ahorro'), Kit::word('el dinero', 'dinero'), Kit::word('la boda', 'boda'), Kit::form('para')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.fecha-proyecto', 'The project is due on Sunday.', ['La fecha del proyecto es el domingo.'], [Kit::word('la fecha', 'fecha'), Kit::word('el proyecto', 'proyecto')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.boda-examen', 'No voy a la boda ___ el examen.', 'I am not going to the wedding because of the exam.', 'por', Kit::form('por', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pablo-mes', 'Pablo va a España por un ___.', 'Pablo is going to Spain for a month.', 'mes', Kit::word('el mes', 'mes'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.ana-noche', 'Ana trabaja por la noche para ahorrar dinero.', 'Ana works at night to save money.', [Kit::word('ahorrar'), Kit::word('el dinero', 'dinero'), Kit::form('por la noche')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.proyecto-luis', 'El proyecto de Luis es para el lunes.', 'Luis\'s project is for Monday.', [Kit::word('el proyecto', 'proyecto'), Kit::form('para el lunes')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.marta-examen', 'Marta tiene un examen en un mes y una boda.', 'Marta has an exam in a month and a wedding.', [Kit::word('el examen', 'examen'), Kit::word('la boda', 'boda'), Kit::word('el mes', 'mes')], 'dictation', $set),
        ];
    }
}
