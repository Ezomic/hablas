<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

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
    private const A_NOTE = 'Write a entrada with the article a: no accent and no h.';

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'rules-and-obligations';
    }

    public function words(): array
    {
        return [
            new WordData('proibido', cue: 'prohibited (not allowed)', portunolSlips: ['prohibido']),
            new WordData('aberto', cue: 'open', forms: ['aberta'], portunolSlips: ['abierto', 'abierta']),
            new WordData('fechado', cue: 'closed', forms: ['fechada'], portunolSlips: ['cerrado', 'cerrada']),
            new WordData('a entrada', cue: 'entrance (also: entrance ticket)', forms: ['entradas']),
            new WordData('fumar', cue: 'to smoke'),
            new WordData('esperar', cue: 'to wait'),
            new WordData('entrar', cue: 'to enter (to go in)'),
            new WordData('ter de', cue: 'to have to', forms: ['tenho de', 'tens de', 'tem de', 'temos de'], note: 'Ter de is for a person: tenho de, tens de, tem de. Only ter changes, de stays the same. In Portugal ter que is also heard.', portunolSlips: ['tener que', 'tengo que']),
            new WordData('é preciso', cue: 'one has to (a rule for everybody)', note: 'É preciso has no person and never changes. It states a general rule: é preciso esperar.', portunolSlips: ['hay que']),
            new WordData('pode-se', cue: 'one can (it is allowed)', forms: ['pode', 'não se pode'], note: 'Pode-se asks or says what is allowed. After não the se goes before the verb: não se pode means it is not allowed.', portunolSlips: ['se puede'], questions: ['Is "pode-se" (with "não se pode" for the negative) the form to teach for the impersonal can? Plain sentences and questions use the enclitic form, and the negative has the clitic before the verb.']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Tenho de entrar. É preciso esperar.', 'english' => 'I have to go in. One has to wait.'],
            ['text' => 'Não se pode fumar aqui.', 'english' => 'You cannot smoke here.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural European Portuguese (Portugal). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural European Portuguese (Portugal), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.marta-esperar', 'A Marta ___ esperar.', ['tem de', 'é preciso'], 'tem de', Kit::form('tem de', true), 'Marta is a person, so you need ter: tem de. É preciso has no person and cannot follow Marta.', 'choose', 'Marta has to wait.'),
            Kit::gap($stage, 'sentences.choose_gap.preciso-esperar', '___ esperar aqui.', ['É preciso', 'É'], 'É preciso', Kit::form('é preciso', true), 'É alone does not say what has to be done. To say that, you need é preciso before the infinitive.', 'choose', 'One has to wait here.'),
            Kit::gap($stage, 'sentences.choose_gap.no-fumar', 'Aqui ___ fumar.', ['não se pode', 'há'], 'não se pode', Kit::form('não se pode', true), 'Não se pode says that something is not allowed. Há does not mean can.', 'choose', 'You cannot smoke here.'),
            Kit::gap($stage, 'sentences.choose_gap.entrada-fechada', 'A entrada está ___.', ['fechada', 'fechado'], 'fechada', Kit::word('fechado', 'fechada'), 'A entrada is feminine, so the adjective ends in a: fechada.', 'choose', 'The entrance is closed.'),
            Kit::gap($stage, 'sentences.choose_gap.proibido', 'Aqui é ___ fumar.', ['proibido', 'fechado', 'aberto'], 'proibido', Kit::word('proibido'), 'É proibido fumar means smoking is not allowed. Fechado is closed and aberto is open.', 'choose', 'Smoking is prohibited here.'),
            Kit::gap($stage, 'sentences.choose_gap.entrar', 'É preciso ___ por aqui.', ['entrar', 'esperar'], 'entrar', Kit::word('entrar'), 'The sentence says this way, so the verb is entrar. Esperar would mean wait.', 'choose', 'You have to go in this way.'),

            Kit::typeGap($stage, 'sentences.type_gap.rui-entrar', 'O Rui ___ entrar.', 'Rui has to go in.', 'tem de', Kit::word('ter de', 'tem de'), 'Ter changes with the person: o Rui tem de. É preciso never changes and has no person. This exercise wants tem de.'),
            Kit::typeGap($stage, 'sentences.type_gap.aberta', 'A entrada está ___.', 'The entrance is open.', 'aberta', Kit::word('aberto', 'aberta')),
            Kit::typeGap($stage, 'sentences.type_gap.fumar', 'Não se pode ___ aqui.', 'Smoking is not allowed here.', 'fumar', Kit::word('fumar')),
            Kit::typeGap($stage, 'sentences.type_gap.pode-se-entrar', '___-se entrar aqui?', 'Is it possible to go in here?', 'Pode', Kit::form('pode-se', true, ['pode']), 'Pode-se asks whether something is allowed or possible. É preciso would ask whether you have to go in.'),
            Kit::typeGap($stage, 'sentences.type_gap.tenho-esperar', 'Tenho de ___ aqui.', 'I have to wait here.', 'esperar', Kit::word('esperar')),

            Kit::translate($stage, 'sentences.translate.tenho-entrar', 'I have to go in.', ['Tenho de entrar.', 'Eu tenho de entrar.', 'Tenho que entrar.', 'Eu tenho que entrar.'], [Kit::word('entrar'), Kit::word('ter de', 'tenho de', ['tenho que']), Kit::form('tenho de', false, ['tenho que'])]),
            Kit::translate($stage, 'sentences.translate.no-fumar', 'You cannot smoke here. (general rule)', ['Não se pode fumar aqui.', 'Aqui não se pode fumar.', 'É proibido fumar aqui.', 'Aqui é proibido fumar.'], [Kit::word('fumar'), Kit::form('não se pode', false, ['é proibido'])]),
            Kit::translate($stage, 'sentences.translate.preciso-entrar', 'One has to go in here. (general rule)', ['É preciso entrar aqui.', 'Aqui é preciso entrar.', 'É preciso entrar por aqui.'], [Kit::word('entrar'), Kit::word('é preciso'), Kit::form('é preciso')]),

            Kit::build($stage, 'sentences.build.marta-esperar', 'Marta has to wait.', 'Marta tem de esperar.', ['é'], [Kit::word('esperar'), Kit::word('ter de', 'tem de'), Kit::form('tem de')]),
            Kit::build($stage, 'sentences.build.entrada-fechada', 'The entrance is closed.', 'A entrada está fechada.', ['fechado'], [Kit::word('a entrada', 'entrada'), Kit::word('fechado', 'fechada')]),
            Kit::build($stage, 'sentences.build.preciso-esperar', 'One has to wait.', 'É preciso esperar.', ['há'], [Kit::word('é preciso'), Kit::word('esperar'), Kit::form('é preciso')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.fumar', 'Não se pode fumar aqui.', ['You cannot smoke here.', 'You have to smoke here.', 'Smoking is allowed here.', 'You cannot wait here.'], 'You cannot smoke here.', [Kit::word('fumar'), Kit::word('pode-se', 'se pode'), Kit::form('não se pode')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.entrar', 'Tenho de entrar aqui.', ['I have to go in here.', 'I have to wait here.', 'The entrance is closed.', 'I do not have to go in.'], 'I have to go in here.', [Kit::word('entrar'), Kit::word('ter de', 'tenho de'), Kit::form('tenho de')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.aberta', 'A entrada está aberta.', ['The entrance is open.', 'The entrance is closed.', 'The entrance is here.', 'The entrance is on the left.'], 'The entrance is open.', [Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta')]),
            Kit::listenType($stage, 'sentences.listen_type.preciso-esperar', 'É preciso esperar aqui.', 'One has to wait here.', [Kit::word('é preciso'), Kit::word('esperar'), Kit::form('é preciso')]),
            Kit::listenType($stage, 'sentences.listen_type.pode-se-entrar', 'Pode-se entrar aqui?', 'Is it possible to go in here?', [Kit::word('pode-se'), Kit::word('entrar'), Kit::form('pode-se')]),
            Kit::listenType($stage, 'sentences.listen_type.ana-esperar', 'Ana tem de esperar.', 'Ana has to wait.', [Kit::word('ter de', 'tem de'), Kit::word('esperar'), Kit::form('tem de')]),
            Kit::listenType($stage, 'sentences.listen_type.proibido', 'É proibido fumar aqui.', 'Smoking is prohibited here.', [Kit::word('proibido'), Kit::word('fumar')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.entrada', 'Tenho de esperar na entrada.', 'I have to wait at the entrance.', [Kit::word('ter de', 'tenho de'), Kit::word('esperar'), Kit::word('a entrada', 'entrada'), Kit::form('tenho de')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fumar', 'Não se pode fumar aqui.', 'You cannot smoke here.', [Kit::word('pode-se', 'se pode'), Kit::word('fumar'), Kit::form('não se pode')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fechada', 'A entrada está fechada.', 'The entrance is closed.', [Kit::word('a entrada', 'entrada'), Kit::word('fechado', 'fechada')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.aberto', 'Está aberto e é preciso entrar.', 'It is open and you have to go in.', [Kit::word('aberto'), Kit::word('é preciso'), Kit::word('entrar'), Kit::form('é preciso')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.entrar', 'Pode-se entrar aqui?', 'Is it possible to go in here?', [['sim', 'não'], ['pode', 'entrar']], 'Sim, pode-se entrar aqui.', [Kit::word('pode-se'), Kit::word('entrar')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tens-entrar', 'Tens de entrar aqui?', 'Do you have to go in here?', [['sim', 'não'], ['tenho', 'entrar']], 'Sim, tenho de entrar.', [Kit::word('ter de', 'tenho de'), Kit::word('entrar'), Kit::form('tenho de')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.aberta', 'A entrada está aberta?', 'Is the entrance open?', [['sim', 'não'], ['aberta', 'fechada', 'aberto', 'fechado']], 'Sim, está aberta.', [Kit::word('aberto', 'aberta'), Kit::word('fechado')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fumar', 'Read the conversation.', [
                Kit::line('Ana', 'João, pode-se fumar aqui?'),
                Kit::line('João', 'Não, é proibido fumar.'),
                Kit::line('Ana', 'A entrada está aberta?'),
                Kit::line('João', 'Não, está fechada. É preciso esperar.'),
                Kit::line('Ana', 'E temos de entrar por aqui?'),
                Kit::line('João', 'Sim, é preciso entrar por aqui.'),
            ], [
                Kit::question('Is smoking allowed here?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What do they have to do?', ['Wait and go in', 'Smoke and wait', 'Nothing'], 'Wait and go in'),
            ], [Kit::word('pode-se', 'pode-se'), Kit::word('fumar'), Kit::word('proibido'), Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta'), Kit::word('fechado', 'fechada'), Kit::word('é preciso'), Kit::word('esperar'), Kit::word('ter de', 'temos de'), Kit::word('entrar')], 'read'),
            Kit::gap($stage, 'task.choose_gap.tu-entrar', 'Tu ___ entrar aqui.', ['tens de', 'é preciso'], 'tens de', Kit::form('tens de', true), 'Tu is a person, so you need ter: tens de. É preciso has no person and cannot follow tu.', 'read', 'You have to go in here. (informal you)'),
            Kit::gap($stage, 'task.choose_gap.proibido-fumar', 'É proibido: ___ fumar aqui.', ['não se pode', 'pode-se'], 'não se pode', Kit::form('não se pode', true), 'The sign says it is prohibited, so the rule is não se pode. Pode-se would say that it is allowed.', 'read', 'It is prohibited: you cannot smoke here.'),

            Kit::transform($stage, 'task.transform.ana-entrar', 'Now say it about Ana.', 'Tenho de entrar.', ['A Ana tem de entrar.', 'Ana tem de entrar.', 'A Ana tem que entrar.', 'Ana tem que entrar.'], [Kit::word('entrar'), Kit::word('ter de', 'tem de', ['tem que']), Kit::form('tem de', true, ['tem que'])]),
            Kit::transform($stage, 'task.transform.no-fumar', 'Make it negative.', 'Pode-se fumar aqui.', ['Não se pode fumar aqui.', 'Aqui não se pode fumar.'], [Kit::word('fumar'), Kit::word('pode-se', 'se pode'), Kit::form('não se pode', true)]),
            Kit::transform($stage, 'task.transform.nos', 'Say it about us (we).', 'Tens de esperar.', ['Temos de esperar.', 'Nós temos de esperar.', 'Temos que esperar.', 'Nós temos que esperar.'], [Kit::word('esperar'), Kit::word('ter de', 'temos de', ['temos que']), Kit::form('temos de', false, ['temos que'])]),
            Kit::writeGuided($stage, 'task.write_guided.fechada', 'You are at the entrance: say that the entrance is closed and that you, yourself, have to wait.', ['a entrada', 'fechada', 'tenho de', 'esperar'], 'A entrada está fechada. Tenho de esperar.', [
                ['forms' => ['entrada'], 'term' => 'a entrada'],
                ['forms' => ['fechada', 'fechado'], 'term' => 'fechado'],
                ['forms' => ['tenho'], 'term' => 'ter de'],
                ['forms' => ['esperar'], 'term' => 'esperar'],
            ], [Kit::word('a entrada', 'entrada'), Kit::word('fechado', 'fechada'), Kit::word('ter de', 'tenho de'), Kit::word('esperar')]),
            Kit::writeGuided($stage, 'task.write_guided.proibido', 'Say that smoking is prohibited here and that one has to go in this way (a general rule).', ['proibido', 'fumar', 'é preciso', 'entrar'], 'É proibido fumar aqui. É preciso entrar por aqui.', [
                ['forms' => ['proibido'], 'term' => 'proibido'],
                ['forms' => ['fumar'], 'term' => 'fumar'],
                ['forms' => ['preciso'], 'term' => 'é preciso'],
                ['forms' => ['entrar'], 'term' => 'entrar'],
            ], [Kit::word('proibido'), Kit::word('fumar'), Kit::word('é preciso'), Kit::word('entrar')]),
            Kit::build($stage, 'task.build.temos-entrar', 'We have to go in.', 'Temos de entrar.', ['pode', 'proibido'], [Kit::word('ter de', 'temos de'), Kit::word('entrar'), Kit::form('temos de')], 'write'),
            Kit::build($stage, 'task.build.no-esperar', 'You do not have to wait. (informal you)', 'Não tens de esperar.', ['a', 'muito'], [Kit::word('ter de', 'tens de'), Kit::word('esperar'), Kit::form('tens de', true)], 'write'),
            Kit::build($stage, 'task.build.no-esperar-aqui', 'You cannot wait. (general rule)', 'Não se pode esperar.', ['a', 'muito'], [Kit::word('pode-se', 'se pode'), Kit::word('esperar'), Kit::form('não se pode')], 'write'),
            Kit::translate($stage, 'task.translate.aberta-entrar', 'The entrance is open and I have to go in.', ['A entrada está aberta e tenho de entrar.', 'A entrada está aberta e eu tenho de entrar.', 'A entrada está aberta e tenho que entrar.', 'A entrada está aberta e eu tenho que entrar.'], [Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta'), Kit::word('entrar'), Kit::word('ter de', 'tenho de', ['tenho que']), Kit::form('tenho de', false, ['tenho que'])], 'write'),
            Kit::translate($stage, 'task.translate.esperar-fumar', 'You can wait here, but you cannot smoke. (general rule)', ['Pode-se esperar aqui, mas não se pode fumar.', 'Aqui pode-se esperar, mas não se pode fumar.'], [Kit::word('esperar'), Kit::word('fumar'), Kit::word('pode-se'), Kit::form('pode-se', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.entrada', [
                Kit::line('Marta', 'Rui, a entrada está aberta?'),
                Kit::line('Rui', 'Não, está fechada. Temos de esperar.'),
                Kit::line('Marta', 'Pode-se fumar aqui?'),
                Kit::line('Rui', 'Não, é proibido. E é preciso entrar por aqui.'),
            ], [
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What do they have to do first?', ['Wait', 'Smoke', 'Leave'], 'Wait'),
                Kit::question('What does Rui say about smoking?', ['It is not allowed.', 'It is allowed.', 'He wants to smoke.'], 'It is not allowed.'),
            ], [
                Kit::question('Who asks about the entrance?', ['Marta', 'Rui', 'Nobody'], 'Marta'),
                Kit::question('Does Rui say you have to go in this way?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta'), Kit::word('fechado', 'fechada'), Kit::word('ter de', 'temos de'), Kit::word('esperar'), Kit::word('pode-se', 'pode-se'), Kit::word('fumar'), Kit::word('proibido'), Kit::word('é preciso'), Kit::word('entrar')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.tenho-esperar', 'Tenho de entrar e esperar aqui.', 'I have to go in and wait here.', [Kit::word('ter de', 'tenho de'), Kit::word('entrar'), Kit::word('esperar'), Kit::form('tenho de')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.proibido-preciso', 'É proibido fumar e é preciso esperar aqui.', 'Smoking is prohibited and you have to wait here.', [Kit::word('proibido'), Kit::word('fumar'), Kit::word('é preciso'), Kit::word('esperar'), Kit::form('é preciso')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pode-se-ou-preciso', 'Pode-se esperar aqui ou é preciso entrar?', 'Can one wait here, or do you have to go in?', [Kit::word('pode-se'), Kit::word('esperar'), Kit::word('é preciso'), Kit::word('entrar'), Kit::form('pode-se')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.fumar', 'Pode-se fumar aqui?', 'Is it possible to smoke here?', [['sim', 'não'], ['pode', 'proibido']], 'Não, não se pode.', [Kit::word('pode-se', 'se pode'), Kit::word('fumar'), Kit::word('proibido')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.fechado', 'Está fechado?', 'Is it closed?', [['sim', 'não'], ['aberto', 'fechado', 'aberta', 'fechada']], 'Não, está aberto.', [Kit::word('aberto'), Kit::word('fechado')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.preciso-entrar', 'É preciso entrar aqui?', 'Does one have to go in here?', [['sim', 'não'], ['preciso', 'entrar']], 'Sim, é preciso entrar.', [Kit::word('é preciso'), Kit::word('entrar'), Kit::form('é preciso')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.esperar', 'Tens de esperar?', 'Do you have to wait?', [['sim', 'não'], ['tenho', 'preciso']], 'Sim, tenho de esperar.', [Kit::word('ter de', 'tenho de'), Kit::word('esperar'), Kit::form('tenho de')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.proibido', 'É proibido fumar e é preciso esperar na entrada.', 'Smoking is prohibited and you have to wait at the entrance.', [Kit::word('proibido'), Kit::word('fumar'), Kit::word('é preciso'), Kit::word('esperar'), Kit::word('a entrada', 'entrada')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.aberta', 'A entrada está aberta e tenho de entrar.', 'The entrance is open and I have to go in.', [Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta'), Kit::word('ter de', 'tenho de'), Kit::word('entrar')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.marta-esperar', 'Marta has to wait at the entrance.', ['A Marta tem de esperar na entrada.', 'A Marta tem que esperar na entrada.', 'Marta tem de esperar na entrada.', 'Marta tem que esperar na entrada.'], [Kit::word('esperar'), Kit::word('a entrada', 'entrada'), Kit::word('ter de', 'tem de', ['tem que']), Kit::form('tem de', false, ['tem que'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.esperar-fumar', 'You can wait at the entrance, but you cannot smoke. (general rule)', ['Pode-se esperar na entrada, mas não se pode fumar.', 'Pode-se esperar na entrada, mas é proibido fumar.', 'Na entrada pode-se esperar, mas não se pode fumar.'], [Kit::word('fumar'), Kit::word('esperar'), Kit::form('não se pode', true, ['é proibido'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.no-aberta', 'The entrance is not open.', ['A entrada não está aberta.'], [Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.preciso-entrar', 'You have to go in here, but you do not have to wait. (general rule)', ['É preciso entrar aqui, mas não é preciso esperar.', 'Aqui é preciso entrar, mas não é preciso esperar.'], [Kit::word('entrar'), Kit::word('esperar'), Kit::form('é preciso', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.rui-esperar', 'O Rui ___ esperar aqui.', 'Rui has to wait here.', 'tem de', Kit::form('tem de'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.proibido-fumar', 'É proibido ___ na entrada.', 'Smoking is prohibited at the entrance.', 'fumar', Kit::word('fumar'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.fechada-preciso', 'Aqui a entrada está fechada, é preciso esperar.', 'Here the entrance is closed, you have to wait.', [Kit::word('a entrada', 'entrada'), Kit::word('fechado', 'fechada'), Kit::word('esperar'), Kit::word('é preciso'), Kit::form('é preciso')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.proibido-esperar', 'É proibido esperar na entrada.', 'It is prohibited to wait at the entrance.', [Kit::word('proibido'), Kit::word('esperar'), Kit::word('a entrada', 'entrada')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.aberto-fumar', 'Aqui está aberto, mas não se pode fumar.', 'It is open here, but you cannot smoke.', [Kit::word('aberto'), Kit::word('fumar'), Kit::word('pode-se', 'se pode'), Kit::form('não se pode')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.entrar', [
                Kit::line('Ana', 'João, tens de entrar aqui?'),
                Kit::line('João', 'Sim, tenho de entrar. E não se pode fumar aqui.'),
                Kit::line('Ana', 'Muito bem. Está aberto?'),
                Kit::line('João', 'Sim, está aberto.'),
            ], [
                Kit::question('What does João have to do?', ['Go in', 'Wait', 'Smoke'], 'Go in'),
                Kit::question('What does João say about smoking?', ['It is not allowed.', 'It is allowed.', 'He has to smoke.'], 'It is not allowed.'),
                Kit::question('Is it open?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who asks the questions?', ['Ana', 'João', 'Nobody'], 'Ana'),
                Kit::question('Does João have to go in?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('entrar'), Kit::word('ter de', 'tenho de'), Kit::word('pode-se', 'se pode'), Kit::word('fumar'), Kit::word('aberto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.entrada', 'Read the conversation.', [
                Kit::line('Marta', 'Rui, está aberto?'),
                Kit::line('Rui', 'Não, está fechado. Tenho de esperar.'),
                Kit::line('Marta', 'É proibido esperar?'),
                Kit::line('Rui', 'Não. Pode-se esperar. Mas não se pode fumar.'),
            ], [
                Kit::question('Is it open?', ['Yes', 'No'], 'No'),
                Kit::question('Is waiting allowed?', ['Yes', 'No'], 'Yes'),
            ], [Kit::word('aberto'), Kit::word('fechado'), Kit::word('pode-se', 'pode-se'), Kit::word('esperar'), Kit::word('proibido'), Kit::word('fumar'), Kit::word('ter de', 'tenho de')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.esperar', 'É preciso esperar na entrada?', 'Do you have to wait at the entrance?', [['sim', 'não'], ['preciso', 'esperar']], 'Sim, é preciso esperar.', [Kit::word('é preciso'), Kit::word('esperar')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fumar', 'Pode-se fumar na entrada?', 'Can one smoke at the entrance?', [['sim', 'não'], ['pode', 'proibido']], 'Não, é proibido.', [Kit::word('pode-se'), Kit::word('fumar'), Kit::word('proibido')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fechada', 'Está fechada a entrada?', 'Is the entrance closed?', [['sim', 'não'], ['aberta', 'fechada', 'aberto', 'fechado']], 'Não, está aberta.', [Kit::word('a entrada', 'entrada'), Kit::word('fechado', 'fechada'), Kit::word('aberto', 'aberta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.aberta-rui', 'The entrance is open and Rui has to go in.', ['A entrada está aberta e o Rui tem de entrar.', 'A entrada está aberta e o Rui tem que entrar.', 'A entrada está aberta e Rui tem de entrar.', 'A entrada está aberta e Rui tem que entrar.'], [Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta'), Kit::word('entrar'), Kit::word('ter de', 'tem de', ['tem que']), Kit::form('tem de', false, ['tem que'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.proibido-esperar', 'It is prohibited to smoke, but you can wait here. (general rule)', ['É proibido fumar, mas pode-se esperar aqui.', 'Aqui é proibido fumar, mas pode-se esperar.'], [Kit::word('proibido'), Kit::word('fumar'), Kit::word('esperar'), Kit::word('pode-se'), Kit::form('pode-se', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.fechada-esperar', 'The entrance is closed and we have to wait.', ['A entrada está fechada e temos de esperar.', 'A entrada está fechada e temos que esperar.'], [Kit::word('a entrada', 'entrada'), Kit::word('fechado', 'fechada'), Kit::word('esperar'), Kit::word('ter de', 'temos de', ['temos que']), Kit::form('temos de', false, ['temos que'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.no-preciso', 'You do not have to wait here. (general rule)', ['Não é preciso esperar aqui.', 'Aqui não é preciso esperar.'], [Kit::word('esperar'), Kit::word('é preciso'), Kit::form('é preciso', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pode-se-esperar', '___-se esperar na entrada?', 'Can one wait at the entrance?', 'Pode', Kit::form('pode-se', false, ['pode']), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-entrar', 'Marta, tens de ___ aqui?', 'Marta, do you have to go in here?', 'entrar', Kit::word('entrar'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.preciso-entrar', 'Aqui é preciso entrar.', 'You have to go in here.', [Kit::word('é preciso'), Kit::word('entrar'), Kit::form('é preciso')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.no-esperar', 'Aqui não se pode esperar, está fechado.', 'You cannot wait here, it is closed.', [Kit::word('pode-se', 'se pode'), Kit::word('esperar'), Kit::word('fechado')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.aberta-proibido', 'A entrada está aberta e é proibido fumar.', 'The entrance is open and smoking is prohibited.', [Kit::word('a entrada', 'entrada'), Kit::word('aberto', 'aberta'), Kit::word('proibido'), Kit::word('fumar')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
