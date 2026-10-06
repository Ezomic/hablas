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

final class RulesAndObligations implements UnitContent
{
    private const HAY_NOTE = 'The word hay (there is) sounds the same as ay (ouch). Here it is hay, as in hay que esperar.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'rules-and-obligations';
    }

    public function words(): array
    {
        return [
            new WordData('prohibido', cue: 'prohibited (not allowed)'),
            new WordData('abierto', cue: 'open', forms: ['abierta']),
            new WordData('cerrado', cue: 'closed', forms: ['cerrada']),
            new WordData('la entrada', cue: 'entrance (also: entrance ticket)', forms: ['entradas']),
            new WordData('fumar', cue: 'to smoke'),
            new WordData('esperar', cue: 'to wait'),
            new WordData('entrar', cue: 'to enter (to go in)'),
            new WordData('tener que', cue: 'to have to', forms: ['tengo que', 'tienes que', 'tiene que', 'tenemos que'], note: 'Tener que is for a person: tengo que, tienes que, tiene que. Only tener changes, que stays the same.'),
            new WordData('hay que', cue: 'one has to (a rule for everybody)', note: 'Hay que has no person and never changes. It states a general rule: hay que esperar.'),
            new WordData('se puede', cue: 'one can (it is allowed)', note: 'Se puede asks or says what is allowed. No se puede means it is not allowed.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Tengo que entrar. Hay que esperar.', 'english' => 'I have to go in. One has to wait.'],
            ['text' => 'No se puede fumar aquí.', 'english' => 'You cannot smoke here.'],
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
            Kit::gap($stage, 'sentences.choose_gap.marta-esperar', 'Marta ___ esperar.', ['tiene que', 'hay que'], 'tiene que', Kit::form('tiene que', true), 'Marta is a person, so you need tener: tiene que. Hay que has no person and cannot follow Marta.', 'choose', 'Marta has to wait.'),
            Kit::gap($stage, 'sentences.choose_gap.pagar-entrada', '___ esperar aquí.', ['Hay que', 'Hay'], 'Hay que', Kit::form('hay que', true), 'Hay alone means there is. To say what has to be done you need hay que before the infinitive.', 'choose', 'One has to wait here.'),
            Kit::gap($stage, 'sentences.choose_gap.no-fumar', 'No ___ fumar aquí.', ['se puede', 'hay'], 'se puede', Kit::form('se puede', true), 'No se puede says that something is not allowed. Hay alone cannot be followed by an infinitive.', 'choose', 'You cannot smoke here.'),
            Kit::gap($stage, 'sentences.choose_gap.entrada-cerrada', 'La entrada está ___.', ['cerrada', 'cerrado'], 'cerrada', Kit::word('cerrado', 'cerrada'), 'La entrada is feminine, so the adjective ends in a: cerrada.', 'choose', 'The entrance is closed.'),
            Kit::gap($stage, 'sentences.choose_gap.prohibido', 'Está ___ fumar aquí.', ['prohibido', 'cerrado', 'abierto'], 'prohibido', Kit::word('prohibido'), 'Está prohibido fumar means smoking is not allowed. Cerrado is closed and abierto is open.', 'choose', 'Smoking is prohibited here.'),
            Kit::gap($stage, 'sentences.choose_gap.pagar', 'Hay que ___ por aquí.', ['entrar', 'abierta'], 'entrar', Kit::word('entrar'), 'After hay que you need a verb in the infinitive: entrar. Abierta describes something, it is not a verb.', 'choose', 'You have to go in this way.'),

            Kit::typeGap($stage, 'sentences.type_gap.pablo-pagar', 'Pablo ___ entrar.', 'Pablo has to go in.', 'tiene que', Kit::word('tener que', 'tiene que'), 'Tener changes with the person: Pablo tiene que. Hay que never changes and has no person.'),
            Kit::typeGap($stage, 'sentences.type_gap.abierta', 'La entrada está ___.', 'The entrance is open.', 'abierta', Kit::word('abierto', 'abierta')),
            Kit::typeGap($stage, 'sentences.type_gap.fumar', 'No se puede ___ aquí.', 'Smoking is not allowed here.', 'fumar', Kit::word('fumar')),
            Kit::typeGap($stage, 'sentences.type_gap.se-puede-pagar', '¿___ entrar aquí?', 'Is it possible to go in here?', 'Se puede', Kit::form('se puede', true), 'Se puede asks whether something is allowed or possible. Hay que would ask whether you have to go in.'),
            Kit::typeGap($stage, 'sentences.type_gap.tengo-esperar', 'Tengo que ___ aquí.', 'I have to wait here.', 'esperar', Kit::word('esperar')),

            Kit::translate($stage, 'sentences.translate.tengo-pagar', 'I have to go in.', ['Tengo que entrar.', 'Yo tengo que entrar.'], [Kit::word('entrar'), Kit::word('tener que', 'tengo que'), Kit::form('tengo que')]),
            Kit::translate($stage, 'sentences.translate.no-fumar', 'You cannot smoke here. (general rule)', ['No se puede fumar aquí.', 'Aquí no se puede fumar.', 'Está prohibido fumar aquí.', 'Aquí está prohibido fumar.'], [Kit::word('fumar'), Kit::form('no se puede', false, ['está prohibido'])]),
            Kit::translate($stage, 'sentences.translate.hay-pagar', 'One has to go in here. (general rule)', ['Hay que entrar aquí.', 'Aquí hay que entrar.', 'Hay que entrar por aquí.'], [Kit::word('entrar'), Kit::word('hay que'), Kit::form('hay que')]),

            Kit::build($stage, 'sentences.build.marta-esperar', 'Marta has to wait.', 'Marta tiene que esperar.', ['la'], [Kit::word('esperar'), Kit::word('tener que', 'tiene que'), Kit::form('tiene que')]),
            Kit::build($stage, 'sentences.build.entrada-cerrada', 'The entrance is closed.', 'La entrada está cerrada.', ['cerrado'], [Kit::word('la entrada', 'entrada'), Kit::word('cerrado', 'cerrada')]),
            Kit::build($stage, 'sentences.build.esperar-aqui', 'Is it possible to wait here?', '¿Se puede esperar aquí?', ['hay'], [Kit::word('se puede'), Kit::word('esperar'), Kit::form('se puede')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.fumar', 'No se puede fumar aquí.', ['You cannot smoke here.', 'You have to smoke here.', 'Smoking is allowed here.', 'You cannot wait here.'], 'You cannot smoke here.', [Kit::word('fumar'), Kit::word('se puede', 'se puede'), Kit::form('no se puede')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pagar', 'Tengo que entrar aquí.', ['I have to go in here.', 'I have to wait here.', 'The entrance is closed.', 'I do not have to go in.'], 'I have to go in here.', [Kit::word('entrar'), Kit::word('tener que', 'tengo que'), Kit::form('tengo que')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.abierta', 'La entrada está abierta.', ['The entrance is open.', 'The entrance is closed.', 'The entrance is here.', 'The entrance is prohibited.'], 'The entrance is open.', [Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta')]),
            Kit::listenType($stage, 'sentences.listen_type.hay-esperar', 'Hay que esperar aquí.', 'One has to wait here.', [Kit::word('hay que'), Kit::word('esperar'), Kit::form('hay que')], homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.se-puede-pagar', '¿Se puede entrar aquí?', 'Is it possible to go in here?', [Kit::word('se puede'), Kit::word('entrar'), Kit::form('se puede')]),
            Kit::listenType($stage, 'sentences.listen_type.ana-esperar', 'Ana tiene que esperar.', 'Ana has to wait.', [Kit::word('tener que', 'tiene que'), Kit::word('esperar'), Kit::form('tiene que')]),
            Kit::listenType($stage, 'sentences.listen_type.prohibido', 'Está prohibido fumar aquí.', 'Smoking is prohibited here.', [Kit::word('prohibido'), Kit::word('fumar')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.pagar', 'Tengo que esperar en la entrada.', 'I have to wait at the entrance.', [Kit::word('tener que', 'tengo que'), Kit::word('esperar'), Kit::word('la entrada', 'entrada'), Kit::form('tengo que')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fumar', 'No se puede fumar aquí.', 'You cannot smoke here.', [Kit::word('se puede'), Kit::word('fumar'), Kit::form('no se puede')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cerrada', 'La entrada está cerrada.', 'The entrance is closed.', [Kit::word('la entrada', 'entrada'), Kit::word('cerrado', 'cerrada')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.abierto', 'Está abierto y hay que entrar.', 'It is open and you have to go in.', [Kit::word('abierto'), Kit::word('hay que'), Kit::word('entrar'), Kit::form('hay que')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pagar', '¿Se puede entrar aquí?', 'Is it possible to go in here?', [['sí', 'no'], ['puede', 'entrar']], 'Sí, se puede entrar aquí.', [Kit::word('se puede'), Kit::word('entrar')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tienes-pagar', '¿Tienes que entrar aquí?', 'Do you have to go in here?', [['sí', 'no'], ['tengo', 'entrar']], 'Sí, tengo que entrar.', [Kit::word('tener que', 'tengo que'), Kit::word('entrar'), Kit::form('tengo que')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.abierta', '¿Está abierta la entrada?', 'Is the entrance open?', [['sí', 'no'], ['abierta', 'cerrada', 'abierto', 'cerrado']], 'Sí, está abierta.', [Kit::word('abierto', 'abierta'), Kit::word('cerrado')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fumar', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿se puede fumar aquí?'),
                Kit::line('Pablo', 'No, está prohibido fumar.'),
                Kit::line('Ana', '¿La entrada está abierta?'),
                Kit::line('Pablo', 'No, está cerrada. Hay que esperar.'),
                Kit::line('Ana', '¿Y tenemos que entrar por aquí?'),
                Kit::line('Pablo', 'Sí, hay que entrar por aquí.'),
            ], [
                Kit::question('Is smoking allowed here?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What do they have to do?', ['Wait and go in', 'Smoke and wait', 'Nothing'], 'Wait and go in'),
            ], [Kit::word('se puede'), Kit::word('fumar'), Kit::word('prohibido'), Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta'), Kit::word('cerrado', 'cerrada'), Kit::word('hay que'), Kit::word('esperar'), Kit::word('tener que', 'tenemos que'), Kit::word('entrar')], 'read'),
            Kit::gap($stage, 'task.choose_gap.tu-pagar', 'Tú ___ entrar aquí.', ['tienes que', 'hay que'], 'tienes que', Kit::form('tienes que', true), 'Tú is a person, so you need tener: tienes que. Hay que has no person and cannot follow tú.', 'read', 'You have to go in here. (informal you)'),
            Kit::gap($stage, 'task.choose_gap.prohibido-fumar', 'Está prohibido: ___ fumar aquí.', ['no se puede', 'se puede'], 'no se puede', Kit::form('no se puede', true), 'The sign says it is prohibited, so the rule is no se puede. Se puede would say that it is allowed.', 'read', 'It is prohibited: you cannot smoke here.'),

            Kit::transform($stage, 'task.transform.ana-pagar', 'Now say it about Ana.', 'Tengo que entrar.', ['Ana tiene que entrar.'], [Kit::word('entrar'), Kit::word('tener que', 'tiene que'), Kit::form('tiene que', true)]),
            Kit::transform($stage, 'task.transform.no-fumar', 'Make it negative.', 'Se puede fumar aquí.', ['No se puede fumar aquí.', 'Aquí no se puede fumar.'], [Kit::word('fumar'), Kit::word('se puede'), Kit::form('no se puede', true)]),
            Kit::transform($stage, 'task.transform.nosotros', 'Say it about us (we).', 'Tienes que esperar.', ['Tenemos que esperar.'], [Kit::word('esperar'), Kit::word('tener que', 'tenemos que'), Kit::form('tenemos que')]),
            Kit::writeGuided($stage, 'task.write_guided.cerrada', 'You are at the entrance: say that it is closed and that you, yourself, have to wait.', ['la entrada', 'cerrada', 'tengo que', 'esperar'], 'La entrada está cerrada. Tengo que esperar.', [
                ['forms' => ['entrada'], 'term' => 'la entrada'],
                ['forms' => ['cerrada', 'cerrado'], 'term' => 'cerrado'],
                ['forms' => ['tengo'], 'term' => 'tener que'],
                ['forms' => ['esperar'], 'term' => 'esperar'],
            ], [Kit::word('la entrada', 'entrada'), Kit::word('cerrado', 'cerrada'), Kit::word('tener que', 'tengo que'), Kit::word('esperar')]),
            Kit::writeGuided($stage, 'task.write_guided.prohibido', 'Say that smoking is prohibited here and that one has to go in this way (a general rule).', ['prohibido', 'fumar', 'hay que', 'entrar'], 'Está prohibido fumar aquí. Hay que entrar por aquí.', [
                ['forms' => ['prohibido'], 'term' => 'prohibido'],
                ['forms' => ['fumar'], 'term' => 'fumar'],
                ['forms' => ['hay'], 'term' => 'hay que'],
                ['forms' => ['entrar'], 'term' => 'entrar'],
            ], [Kit::word('prohibido'), Kit::word('fumar'), Kit::word('hay que'), Kit::word('entrar')]),
            Kit::build($stage, 'task.build.tenemos-pagar', 'We have to go in here.', 'Tenemos que entrar aquí.', ['puede', 'prohibido'], [Kit::word('tener que', 'tenemos que'), Kit::word('entrar'), Kit::form('tenemos que')], 'write'),
            Kit::build($stage, 'task.build.no-esperar', 'You do not have to wait. (informal you)', 'No tienes que esperar.', ['la', 'y'], [Kit::word('tener que', 'tienes que'), Kit::word('esperar'), Kit::form('tienes que', true)], 'write'),
            Kit::build($stage, 'task.build.no-esperar-aqui', 'You cannot wait here. (general rule)', 'No se puede esperar aquí.', ['la', 'muy'], [Kit::word('se puede'), Kit::word('esperar'), Kit::form('no se puede')], 'write'),
            Kit::translate($stage, 'task.translate.abierta-pagar', 'The entrance is open and I have to go in.', ['La entrada está abierta y tengo que entrar.', 'La entrada está abierta y yo tengo que entrar.'], [Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta'), Kit::word('entrar'), Kit::word('tener que', 'tengo que'), Kit::form('tengo que')], 'write'),
            Kit::translate($stage, 'task.translate.esperar-fumar', 'You can wait here, but you cannot smoke. (general rule)', ['Se puede esperar aquí, pero no se puede fumar.', 'Aquí se puede esperar, pero no se puede fumar.'], [Kit::word('esperar'), Kit::word('fumar'), Kit::word('se puede'), Kit::form('se puede', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.entrada', [
                Kit::line('Marta', 'Luis, ¿está abierta la entrada?'),
                Kit::line('Luis', 'No, está cerrada. Tenemos que esperar.'),
                Kit::line('Marta', '¿Se puede fumar aquí?'),
                Kit::line('Luis', 'No, está prohibido. Y hay que entrar por aquí.'),
            ], [
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What do they have to do?', ['Wait', 'Smoke', 'Leave'], 'Wait'),
                Kit::question('What does Luis say about smoking?', ['It is not allowed.', 'It is allowed.', 'He wants to smoke.'], 'It is not allowed.'),
            ], [
                Kit::question('Who asks about the entrance?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Does Luis say you have to go in this way?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta'), Kit::word('cerrado', 'cerrada'), Kit::word('tener que', 'tenemos que'), Kit::word('esperar'), Kit::word('se puede'), Kit::word('fumar'), Kit::word('prohibido'), Kit::word('hay que'), Kit::word('entrar')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pagar-esperar', 'Tengo que entrar y esperar aquí.', 'I have to go in and wait here.', [Kit::word('tener que', 'tengo que'), Kit::word('entrar'), Kit::word('esperar'), Kit::form('tengo que')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.prohibido-hay', 'Está prohibido fumar y hay que esperar aquí.', 'Smoking is prohibited and you have to wait here.', [Kit::word('prohibido'), Kit::word('fumar'), Kit::word('hay que'), Kit::word('esperar'), Kit::form('hay que')], 'listen', homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'task.listen_type.se-puede-o-hay', '¿Se puede esperar aquí o hay que entrar?', 'Can one wait here, or do you have to go in?', [Kit::word('se puede'), Kit::word('esperar'), Kit::word('hay que'), Kit::word('entrar'), Kit::form('se puede')], 'listen', homophoneNote: self::HAY_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.fumar', '¿Se puede fumar aquí?', 'Is it possible to smoke here?', [['sí', 'no'], ['puede', 'prohibido']], 'No, no se puede.', [Kit::word('se puede'), Kit::word('fumar'), Kit::word('prohibido')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cerrado', '¿Está cerrado?', 'Is it closed?', [['sí', 'no'], ['abierto', 'cerrado', 'abierta', 'cerrada']], 'No, está abierto.', [Kit::word('abierto'), Kit::word('cerrado')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.hay-pagar', '¿Hay que entrar aquí?', 'Do you have to go in here?', [['sí', 'no'], ['hay', 'entrar']], 'Sí, hay que entrar.', [Kit::word('hay que'), Kit::word('entrar'), Kit::form('hay que')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.esperar', '¿Tienes que esperar?', 'Do you have to wait?', [['sí', 'no'], ['tengo', 'hay']], 'Sí, tengo que esperar.', [Kit::word('tener que', 'tengo que'), Kit::word('esperar'), Kit::form('tengo que')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.prohibido', 'Está prohibido fumar y hay que esperar en la entrada.', 'Smoking is prohibited and you have to wait at the entrance.', [Kit::word('prohibido'), Kit::word('fumar'), Kit::word('hay que'), Kit::word('esperar'), Kit::word('la entrada', 'entrada')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.abierta', 'La entrada está abierta y tengo que entrar.', 'The entrance is open and I have to go in.', [Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta'), Kit::word('tener que', 'tengo que'), Kit::word('entrar')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.marta-pagar', 'Marta has to wait at the entrance.', ['Marta tiene que esperar en la entrada.'], [Kit::word('esperar'), Kit::word('la entrada', 'entrada'), Kit::word('tener que', 'tiene que'), Kit::form('tiene que')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fumar-esperar', 'You cannot smoke here, but you can wait. (general rule)', ['No se puede fumar aquí, pero se puede esperar.', 'Aquí no se puede fumar, pero se puede esperar.'], [Kit::word('fumar'), Kit::word('esperar'), Kit::form('no se puede', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.no-abierta', 'The entrance is not open.', ['La entrada no está abierta.'], [Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.hay-pagar', 'You have to go in here, but you do not have to wait. (general rule)', ['Hay que entrar aquí, pero no hay que esperar.', 'Aquí hay que entrar, pero no hay que esperar.'], [Kit::word('entrar'), Kit::word('esperar'), Kit::form('hay que', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luis-esperar', 'Luis ___ esperar aquí.', 'Luis has to wait here.', 'tiene que', Kit::form('tiene que'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.prohibido-fumar', 'Está prohibido ___ en la entrada.', 'Smoking is prohibited at the entrance.', 'fumar', Kit::word('fumar'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.cerrada-hay', 'La entrada está cerrada, hay que esperar.', 'The entrance is closed, you have to wait.', [Kit::word('la entrada', 'entrada'), Kit::word('cerrado', 'cerrada'), Kit::word('esperar'), Kit::word('hay que'), Kit::form('hay que')], 'dictation', $set, homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.prohibido-esperar', 'Está prohibido esperar en la entrada.', 'It is prohibited to wait at the entrance.', [Kit::word('prohibido'), Kit::word('esperar'), Kit::word('la entrada', 'entrada')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.abierto-fumar', 'Aquí está abierto, pero no se puede fumar.', 'It is open here, but you cannot smoke.', [Kit::word('abierto'), Kit::word('fumar'), Kit::word('se puede'), Kit::form('no se puede')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.pagar', [
                Kit::line('Ana', 'Pablo, ¿tienes que entrar aquí?'),
                Kit::line('Pablo', 'Sí, tengo que entrar. Y no se puede fumar aquí.'),
                Kit::line('Ana', 'Muy bien. ¿Está abierto?'),
                Kit::line('Pablo', 'Sí, está abierto.'),
            ], [
                Kit::question('What does Pablo have to do?', ['Go in', 'Wait', 'Smoke'], 'Go in'),
                Kit::question('What does Pablo say about smoking?', ['It is not allowed.', 'It is allowed.', 'He has to smoke.'], 'It is not allowed.'),
                Kit::question('Is it open?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who asks the questions?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('Does Pablo have to go in?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('entrar'), Kit::word('tener que', 'tengo que'), Kit::word('se puede'), Kit::word('fumar'), Kit::word('abierto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.entrada', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿está abierto?'),
                Kit::line('Luis', 'No, está cerrado. Tengo que esperar.'),
                Kit::line('Marta', '¿Está prohibido esperar?'),
                Kit::line('Luis', 'No, se puede esperar. Pero no se puede fumar.'),
            ], [
                Kit::question('Is it open?', ['Yes', 'No'], 'No'),
                Kit::question('Is waiting allowed?', ['Yes', 'No'], 'Yes'),
            ], [Kit::word('abierto'), Kit::word('cerrado'), Kit::word('se puede'), Kit::word('esperar'), Kit::word('prohibido'), Kit::word('fumar'), Kit::word('tener que', 'tengo que')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.esperar', '¿Hay que esperar en la entrada?', 'Do you have to wait at the entrance?', [['sí', 'no'], ['hay', 'esperar']], 'Sí, hay que esperar.', [Kit::word('hay que'), Kit::word('esperar')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fumar', '¿Se puede fumar en la entrada?', 'Can one smoke at the entrance?', [['sí', 'no'], ['puede', 'prohibido']], 'No, está prohibido.', [Kit::word('se puede'), Kit::word('fumar'), Kit::word('prohibido')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cerrada', '¿Está cerrada la entrada?', 'Is the entrance closed?', [['sí', 'no'], ['abierta', 'cerrada', 'abierto', 'cerrado']], 'No, está abierta.', [Kit::word('la entrada', 'entrada'), Kit::word('cerrado', 'cerrada'), Kit::word('abierto', 'abierta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.abierta-pablo', 'The entrance is open and Pablo has to go in.', ['La entrada está abierta y Pablo tiene que entrar.'], [Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta'), Kit::word('entrar'), Kit::word('tener que', 'tiene que'), Kit::form('tiene que')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.prohibido-esperar', 'It is prohibited to smoke, but you can wait here. (general rule)', ['Está prohibido fumar, pero se puede esperar aquí.', 'Aquí está prohibido fumar, pero se puede esperar.'], [Kit::word('prohibido'), Kit::word('fumar'), Kit::word('esperar'), Kit::word('se puede'), Kit::form('se puede', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.cerrada-esperar', 'The entrance is closed and we have to wait.', ['La entrada está cerrada y tenemos que esperar.'], [Kit::word('la entrada', 'entrada'), Kit::word('cerrado', 'cerrada'), Kit::word('esperar'), Kit::word('tener que', 'tenemos que'), Kit::form('tenemos que')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.no-hay-pagar', 'You do not have to wait here. (general rule)', ['No hay que esperar aquí.', 'Aquí no hay que esperar.'], [Kit::word('esperar'), Kit::word('hay que'), Kit::form('hay que', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.se-puede-esperar', '¿___ esperar en la entrada?', 'Can one wait at the entrance?', 'Se puede', Kit::form('se puede'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-pagar', 'Marta, ¿tienes que ___ aquí?', 'Marta, do you have to go in here?', 'entrar', Kit::word('entrar'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.hay-pagar', 'Aquí hay que entrar.', 'You have to go in here.', [Kit::word('hay que'), Kit::word('entrar'), Kit::form('hay que')], 'dictation', $set, homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.no-esperar', 'Aquí no se puede esperar, está cerrado.', 'You cannot wait here, it is closed.', [Kit::word('se puede'), Kit::word('esperar'), Kit::word('cerrado')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.abierta-prohibido', 'La entrada está abierta y está prohibido fumar.', 'The entrance is open and smoking is prohibited.', [Kit::word('la entrada', 'entrada'), Kit::word('abierto', 'abierta'), Kit::word('prohibido'), Kit::word('fumar')], 'dictation', $set),
        ];
    }
}
