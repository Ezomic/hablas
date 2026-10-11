<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

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
    private const E_NOTE = 'È (is) has an accent and e (and) does not. They sound close, and the sentence tells you which is which.';

    private const O_NOTE = 'O (or) sounds the same as ho (I have). Here it is o, as in qui o bisogna.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'rules-and-obligations';
    }

    public function words(): array
    {
        return [
            new WordData('vietato', cue: 'prohibited (not allowed)', forms: ['vietata']),
            new WordData('aperto', cue: 'open', forms: ['aperta']),
            new WordData('chiuso', cue: 'closed', forms: ['chiusa']),
            new WordData("l'entrata", cue: 'entrance (the way in)', accepted: ['entrata'], forms: ["all'entrata"], note: "Entrata starts with a vowel, so the article is l': l'entrata. At the entrance is all'entrata."),
            new WordData('fumare', cue: 'to smoke'),
            new WordData('aspettare', cue: 'to wait'),
            new WordData('entrare', cue: 'to enter (to go in)'),
            new WordData('dovere', cue: 'to have to (must)', forms: ['devo', 'devi', 'deve', 'dobbiamo'], note: 'Dovere is for a person: devo, devi, deve, dobbiamo. The next verb stays in the infinitive.'),
            new WordData('bisogna', cue: 'one has to (a rule for everybody)', note: 'Bisogna has no person and never changes. It states a general rule: bisogna aspettare. Non bisogna means one must not.'),
            new WordData('si può', cue: 'one can (it is allowed)', note: 'Si può asks or says what is allowed. Non si può means it is not allowed.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Devo entrare. Bisogna aspettare.', 'english' => 'I have to go in. One has to wait.'],
            ['text' => 'Non si può fumare qui.', 'english' => 'You cannot smoke here.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.marta-aspettare', 'Marta ___ aspettare.', ['deve', 'bisogna'], 'deve', Kit::form('deve', true), 'Marta is a person, so you need dovere: deve. Bisogna has no person and cannot follow Marta.', 'choose', 'Marta has to wait.'),
            Kit::gap($stage, 'sentences.choose_gap.aspettare-qui', '___ aspettare qui.', ['Bisogna', 'Dovere'], 'Bisogna', Kit::form('bisogna', true), 'To say what has to be done in general you need bisogna before the infinitive. Dovere is only the infinitive and cannot start the sentence.', 'choose', 'One has to wait here.'),
            Kit::gap($stage, 'sentences.choose_gap.non-fumare', 'Non ___ fumare qui.', ['si può', 'è'], 'si può', Kit::form('si può', true), 'Non si può says that something is not allowed. Non è cannot be followed by an infinitive here.', 'choose', 'You cannot smoke here.'),
            Kit::gap($stage, 'sentences.choose_gap.entrata-chiusa', "L'entrata è ___.", ['chiusa', 'chiuso'], 'chiusa', Kit::word('chiuso', 'chiusa'), "L'entrata is feminine, so the adjective ends in a: chiusa.", 'choose', 'The entrance is closed.'),
            Kit::gap($stage, 'sentences.choose_gap.vietato', 'È ___ fumare qui.', ['vietato', 'chiuso', 'aperto'], 'vietato', Kit::word('vietato'), 'È vietato fumare means smoking is not allowed. Chiuso is closed and aperto is open.', 'choose', 'Smoking is prohibited here.'),
            Kit::gap($stage, 'sentences.choose_gap.entrare', 'Bisogna ___ da qui.', ['entrare', 'aperta'], 'entrare', Kit::word('entrare'), 'After bisogna you need a verb in the infinitive: entrare. Aperta describes something, it is not a verb.', 'choose', 'You have to go in this way.'),

            Kit::typeGap($stage, 'sentences.type_gap.paolo-entrare', 'Paolo ___ entrare.', 'Paolo has to go in.', 'deve', Kit::word('dovere', 'deve'), 'Dovere changes with the person: Paolo deve. Bisogna never changes and has no person.'),
            Kit::typeGap($stage, 'sentences.type_gap.aperta', "L'entrata è ___.", 'The entrance is open.', 'aperta', Kit::word('aperto', 'aperta')),
            Kit::typeGap($stage, 'sentences.type_gap.fumare', 'Non si può ___ qui.', 'Smoking is not allowed here.', 'fumare', Kit::word('fumare')),
            Kit::typeGap($stage, 'sentences.type_gap.si-puo-entrare', '___ entrare qui?', 'Is it possible to go in here?', 'Si può', Kit::form('si può', true), 'Si può asks whether something is allowed or possible. Bisogna would ask whether you have to go in.'),
            Kit::typeGap($stage, 'sentences.type_gap.devo-aspettare', 'Devo ___ qui.', 'I have to wait here.', 'aspettare', Kit::word('aspettare')),

            Kit::translate($stage, 'sentences.translate.devo-entrare', 'I have to go in.', ['Devo entrare.', 'Io devo entrare.'], [Kit::word('entrare'), Kit::word('dovere', 'devo'), Kit::form('devo')]),
            Kit::translate($stage, 'sentences.translate.non-fumare', 'You cannot smoke here. (general rule)', ['Non si può fumare qui.', 'Qui non si può fumare.', 'È vietato fumare qui.', 'Qui è vietato fumare.'], [Kit::word('fumare'), Kit::form('non si può', false, ['è vietato'])]),
            Kit::translate($stage, 'sentences.translate.bisogna-entrare', 'One has to go in here. (general rule)', ['Bisogna entrare qui.', 'Qui bisogna entrare.', 'Bisogna entrare da qui.'], [Kit::word('entrare'), Kit::word('bisogna'), Kit::form('bisogna')]),

            Kit::build($stage, 'sentences.build.marta-aspettare', 'Marta has to wait.', 'Marta deve aspettare.', ['la'], [Kit::word('aspettare'), Kit::word('dovere', 'deve'), Kit::form('deve')]),
            Kit::build($stage, 'sentences.build.entrata-chiusa', 'The entrance is closed.', "L'entrata è chiusa.", ['chiuso'], [Kit::word("l'entrata"), Kit::word('chiuso', 'chiusa')]),
            Kit::build($stage, 'sentences.build.aspettare-qui', 'Is it possible to wait here?', 'Si può aspettare qui?', ['bisogna'], [Kit::word('si può'), Kit::word('aspettare'), Kit::form('si può')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.fumare', 'Non si può fumare qui.', ['You cannot smoke here.', 'You have to smoke here.', 'Smoking is allowed here.', 'You cannot wait here.'], 'You cannot smoke here.', [Kit::word('fumare'), Kit::word('si può', 'si può'), Kit::form('non si può')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.entrare', 'Devo entrare qui.', ['I have to go in here.', 'I have to wait here.', 'The entrance is closed.', 'I do not have to go in.'], 'I have to go in here.', [Kit::word('entrare'), Kit::word('dovere', 'devo'), Kit::form('devo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.aperta', "L'entrata è aperta.", ['The entrance is open.', 'The entrance is closed.', 'The entrance is here.', 'The entrance is prohibited.'], 'The entrance is open.', [Kit::word("l'entrata"), Kit::word('aperto', 'aperta')]),
            Kit::listenType($stage, 'sentences.listen_type.bisogna-aspettare', 'Bisogna aspettare qui.', 'One has to wait here.', [Kit::word('bisogna'), Kit::word('aspettare'), Kit::form('bisogna')]),
            Kit::listenType($stage, 'sentences.listen_type.si-puo-entrare', 'Si può entrare qui?', 'Is it possible to go in here?', [Kit::word('si può'), Kit::word('entrare'), Kit::form('si può')]),
            Kit::listenType($stage, 'sentences.listen_type.anna-aspettare', 'Anna deve aspettare.', 'Anna has to wait.', [Kit::word('dovere', 'deve'), Kit::word('aspettare'), Kit::form('deve')]),
            Kit::listenType($stage, 'sentences.listen_type.vietato', 'È vietato fumare qui.', 'Smoking is prohibited here.', [Kit::word('vietato'), Kit::word('fumare')], homophoneNote: self::E_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.entrare', "Devo aspettare all'entrata.", 'I have to wait at the entrance.', [Kit::word('dovere', 'devo'), Kit::word('aspettare'), Kit::word("l'entrata", "all'entrata"), Kit::form('devo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fumare', 'Non si può fumare qui.', 'You cannot smoke here.', [Kit::word('si può'), Kit::word('fumare'), Kit::form('non si può')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.chiusa', "L'entrata è chiusa.", 'The entrance is closed.', [Kit::word("l'entrata"), Kit::word('chiuso', 'chiusa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.aperto', 'È aperto e bisogna entrare.', 'It is open and you have to go in.', [Kit::word('aperto'), Kit::word('bisogna'), Kit::word('entrare'), Kit::form('bisogna')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.entrare', 'Si può entrare qui?', 'Is it possible to go in here?', [['sì', 'no'], ['può', 'entrare']], 'Sì, si può entrare qui.', [Kit::word('si può'), Kit::word('entrare')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.devi-entrare', 'Devi entrare qui?', 'Do you have to go in here?', [['sì', 'no'], ['devo', 'entrare']], 'Sì, devo entrare.', [Kit::word('dovere', 'devo'), Kit::word('entrare'), Kit::form('devo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.aperta', "L'entrata è aperta?", 'Is the entrance open?', [['sì', 'no'], ['aperta', 'chiusa', 'aperto', 'chiuso']], 'Sì, è aperta.', [Kit::word('aperto', 'aperta'), Kit::word('chiuso')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fumare', 'Read the conversation.', [
                Kit::line('Anna', 'Paolo, si può fumare qui?'),
                Kit::line('Paolo', 'No, è vietato fumare.'),
                Kit::line('Anna', "L'entrata è aperta?"),
                Kit::line('Paolo', 'No, è chiusa. Bisogna aspettare.'),
                Kit::line('Anna', 'E dobbiamo entrare da qui?'),
                Kit::line('Paolo', 'Sì, bisogna entrare da qui.'),
            ], [
                Kit::question('Is smoking allowed here?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What do they have to do?', ['Wait and go in', 'Smoke and wait', 'Nothing'], 'Wait and go in'),
            ], [Kit::word('si può'), Kit::word('fumare'), Kit::word('vietato'), Kit::word("l'entrata"), Kit::word('aperto', 'aperta'), Kit::word('chiuso', 'chiusa'), Kit::word('bisogna'), Kit::word('aspettare'), Kit::word('dovere', 'dobbiamo'), Kit::word('entrare')], 'read'),
            Kit::gap($stage, 'task.choose_gap.tu-entrare', 'Tu ___ entrare qui.', ['devi', 'bisogna'], 'devi', Kit::form('devi', true), 'Tu is a person, so you need dovere: devi. Bisogna has no person and cannot follow tu.', 'read', 'You have to go in here. (informal you)'),
            Kit::gap($stage, 'task.choose_gap.vietato-fumare', 'È vietato: ___ fumare qui.', ['non si può', 'si può'], 'non si può', Kit::form('non si può', true), 'The sign says it is prohibited, so the rule is non si può. Si può would say that it is allowed.', 'read', 'It is prohibited: you cannot smoke here.'),

            Kit::transform($stage, 'task.transform.anna-entrare', 'Now say it about Anna.', 'Devo entrare.', ['Anna deve entrare.'], [Kit::word('entrare'), Kit::word('dovere', 'deve'), Kit::form('deve', true)]),
            Kit::transform($stage, 'task.transform.non-fumare', 'Make it negative.', 'Si può fumare qui.', ['Non si può fumare qui.', 'Qui non si può fumare.'], [Kit::word('fumare'), Kit::word('si può'), Kit::form('non si può', true)]),
            Kit::transform($stage, 'task.transform.noi', 'Say it about us (we).', 'Devi aspettare.', ['Dobbiamo aspettare.', 'Noi dobbiamo aspettare.'], [Kit::word('aspettare'), Kit::word('dovere', 'dobbiamo'), Kit::form('dobbiamo')]),
            Kit::writeGuided($stage, 'task.write_guided.chiusa', 'You are at the entrance: say that it is closed and that you, yourself, have to wait.', ["l'entrata", 'chiusa', 'devo', 'aspettare'], "L'entrata è chiusa. Devo aspettare.", [
                ['forms' => ["l'entrata", 'entrata'], 'term' => "l'entrata"],
                ['forms' => ['chiusa'], 'term' => 'chiuso'],
                ['forms' => ['devo'], 'term' => 'dovere'],
                ['forms' => ['aspettare'], 'term' => 'aspettare'],
            ], [Kit::word("l'entrata"), Kit::word('chiuso', 'chiusa'), Kit::word('dovere', 'devo'), Kit::word('aspettare')]),
            Kit::writeGuided($stage, 'task.write_guided.vietato', 'Say that smoking is prohibited here and that one has to go in this way (a general rule).', ['vietato', 'fumare', 'bisogna', 'entrare'], 'È vietato fumare qui. Bisogna entrare da qui.', [
                ['forms' => ['vietato'], 'term' => 'vietato'],
                ['forms' => ['fumare'], 'term' => 'fumare'],
                ['forms' => ['bisogna'], 'term' => 'bisogna'],
                ['forms' => ['entrare'], 'term' => 'entrare'],
            ], [Kit::word('vietato'), Kit::word('fumare'), Kit::word('bisogna'), Kit::word('entrare')]),
            Kit::build($stage, 'task.build.dobbiamo-entrare', 'We have to go in here.', 'Dobbiamo entrare qui.', ['può', 'vietato'], [Kit::word('dovere', 'dobbiamo'), Kit::word('entrare'), Kit::form('dobbiamo')], 'write'),
            Kit::build($stage, 'task.build.non-aspettare', 'You do not have to wait. (informal you)', 'Non devi aspettare.', ['la', 'e'], [Kit::word('dovere', 'devi'), Kit::word('aspettare'), Kit::form('devi', true)], 'write'),
            Kit::build($stage, 'task.build.non-aspettare-qui', 'You cannot wait here. (general rule)', 'Non si può aspettare qui.', ['la', 'molto'], [Kit::word('si può'), Kit::word('aspettare'), Kit::form('non si può')], 'write'),
            Kit::translate($stage, 'task.translate.aperta-entrare', 'The entrance is open and I have to go in.', ["L'entrata è aperta e devo entrare.", "L'entrata è aperta e io devo entrare."], [Kit::word("l'entrata"), Kit::word('aperto', 'aperta'), Kit::word('entrare'), Kit::word('dovere', 'devo'), Kit::form('devo')], 'write'),
            Kit::translate($stage, 'task.translate.aspettare-fumare', 'You can wait here, but you cannot smoke. (general rule)', ['Si può aspettare qui, ma non si può fumare.', 'Qui si può aspettare, ma non si può fumare.'], [Kit::word('aspettare'), Kit::word('fumare'), Kit::word('si può'), Kit::form('si può', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.entrata', [
                Kit::line('Marta', "Luca, l'entrata è aperta?"),
                Kit::line('Luca', 'No, è chiusa. Dobbiamo aspettare.'),
                Kit::line('Marta', 'Si può fumare qui?'),
                Kit::line('Luca', 'No, è vietato. E bisogna entrare da qui.'),
            ], [
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What do they have to do first?', ['Wait', 'Smoke', 'Leave'], 'Wait'),
                Kit::question('What does Luca say about smoking?', ['It is not allowed.', 'It is allowed.', 'He wants to smoke.'], 'It is not allowed.'),
            ], [
                Kit::question('Who asks about the entrance?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('Does Luca say you have to go in this way?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word("l'entrata"), Kit::word('aperto', 'aperta'), Kit::word('chiuso', 'chiusa'), Kit::word('dovere', 'dobbiamo'), Kit::word('aspettare'), Kit::word('si può'), Kit::word('fumare'), Kit::word('vietato'), Kit::word('bisogna'), Kit::word('entrare')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.entrare-aspettare', 'Devo entrare e aspettare qui.', 'I have to go in and wait here.', [Kit::word('dovere', 'devo'), Kit::word('entrare'), Kit::word('aspettare'), Kit::form('devo')], 'listen', homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'task.listen_type.vietato-bisogna', 'È vietato fumare e bisogna aspettare qui.', 'Smoking is prohibited and you have to wait here.', [Kit::word('vietato'), Kit::word('fumare'), Kit::word('bisogna'), Kit::word('aspettare'), Kit::form('bisogna')], 'listen', homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'task.listen_type.si-puo-o-bisogna', 'Si può aspettare qui o bisogna entrare?', 'Can one wait here, or do you have to go in?', [Kit::word('si può'), Kit::word('aspettare'), Kit::word('bisogna'), Kit::word('entrare'), Kit::form('si può')], 'listen', homophoneNote: self::O_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.fumare', 'Si può fumare qui?', 'Is it possible to smoke here?', [['sì', 'no'], ['può', 'vietato']], 'No, non si può.', [Kit::word('si può'), Kit::word('fumare'), Kit::word('vietato')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chiuso', 'È chiuso?', 'Is it closed?', [['sì', 'no'], ['aperto', 'chiuso', 'aperta', 'chiusa']], 'No, è aperto.', [Kit::word('aperto'), Kit::word('chiuso')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.bisogna-entrare', 'Bisogna entrare qui?', 'Do you have to go in here?', [['sì', 'no'], ['bisogna', 'entrare']], 'Sì, bisogna entrare.', [Kit::word('bisogna'), Kit::word('entrare'), Kit::form('bisogna')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.aspettare', 'Devi aspettare?', 'Do you have to wait?', [['sì', 'no'], ['devo', 'bisogna']], 'Sì, devo aspettare.', [Kit::word('dovere', 'devo'), Kit::word('aspettare'), Kit::form('devo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.vietato', "È vietato fumare e bisogna aspettare all'entrata.", 'Smoking is prohibited and you have to wait at the entrance.', [Kit::word('vietato'), Kit::word('fumare'), Kit::word('bisogna'), Kit::word('aspettare'), Kit::word("l'entrata", "all'entrata")], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.aperta', "L'entrata è aperta e devo entrare.", 'The entrance is open and I have to go in.', [Kit::word("l'entrata"), Kit::word('aperto', 'aperta'), Kit::word('dovere', 'devo'), Kit::word('entrare')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.marta-aspettare', 'Marta has to wait at the entrance.', ["Marta deve aspettare all'entrata."], [Kit::word('aspettare'), Kit::word("l'entrata", "all'entrata"), Kit::word('dovere', 'deve'), Kit::form('deve')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fumare-aspettare', 'You cannot smoke here, but you can wait. (general rule)', ['Non si può fumare qui, ma si può aspettare.', 'Qui non si può fumare, ma si può aspettare.'], [Kit::word('fumare'), Kit::word('aspettare'), Kit::form('non si può', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.non-aperta', 'The entrance is not open.', ["L'entrata non è aperta."], [Kit::word("l'entrata"), Kit::word('aperto', 'aperta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bisogna-entrare', 'You have to go in here, but you cannot wait. (general rule)', ['Bisogna entrare qui, ma non si può aspettare.', 'Qui bisogna entrare, ma non si può aspettare.', 'Bisogna entrare qui, ma non bisogna aspettare.', 'Qui bisogna entrare, ma non bisogna aspettare.'], [Kit::word('entrare'), Kit::word('aspettare'), Kit::form('bisogna', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luca-aspettare', 'Luca ___ aspettare qui.', 'Luca has to wait here.', 'deve', Kit::form('deve'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vietato-fumare', "È vietato ___ all'entrata.", 'Smoking is prohibited at the entrance.', 'fumare', Kit::word('fumare'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.chiusa-bisogna', "Qui l'entrata è chiusa, bisogna aspettare.", 'The entrance is closed here, you have to wait.', [Kit::word("l'entrata"), Kit::word('chiuso', 'chiusa'), Kit::word('aspettare'), Kit::word('bisogna'), Kit::form('bisogna')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.vietato-aspettare', "È vietato aspettare all'entrata.", 'It is prohibited to wait at the entrance.', [Kit::word('vietato'), Kit::word('aspettare'), Kit::word("l'entrata", "all'entrata")], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.aperto-fumare', 'Qui è aperto, ma non si può fumare.', 'It is open here, but you cannot smoke.', [Kit::word('aperto'), Kit::word('fumare'), Kit::word('si può'), Kit::form('non si può')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.entrare', [
                Kit::line('Anna', 'Paolo, devi entrare qui?'),
                Kit::line('Paolo', 'Sì, devo entrare. E non si può fumare qui.'),
                Kit::line('Anna', 'Molto bene. È aperto?'),
                Kit::line('Paolo', 'Sì, è aperto.'),
            ], [
                Kit::question('What does Paolo have to do?', ['Go in', 'Wait', 'Smoke'], 'Go in'),
                Kit::question('What does Paolo say about smoking?', ['It is not allowed.', 'It is allowed.', 'He has to smoke.'], 'It is not allowed.'),
                Kit::question('Is it open?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who asks the questions?', ['Anna', 'Paolo', 'Nobody'], 'Anna'),
                Kit::question('Does Paolo have to go in?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('entrare'), Kit::word('dovere', 'devo'), Kit::word('si può'), Kit::word('fumare'), Kit::word('aperto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.entrata', 'Read the conversation.', [
                Kit::line('Marta', 'Luca, è aperto?'),
                Kit::line('Luca', 'No, è chiuso. Devo aspettare.'),
                Kit::line('Marta', 'È vietato aspettare?'),
                Kit::line('Luca', 'No, si può aspettare. Ma non si può fumare.'),
            ], [
                Kit::question('Is it open?', ['Yes', 'No'], 'No'),
                Kit::question('Is waiting allowed?', ['Yes', 'No'], 'Yes'),
            ], [Kit::word('aperto'), Kit::word('chiuso'), Kit::word('si può'), Kit::word('aspettare'), Kit::word('vietato'), Kit::word('fumare'), Kit::word('dovere', 'devo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aspettare', "Bisogna aspettare all'entrata?", 'Do you have to wait at the entrance?', [['sì', 'no'], ['bisogna', 'aspettare']], 'Sì, bisogna aspettare.', [Kit::word('bisogna'), Kit::word('aspettare')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fumare', "Si può fumare all'entrata?", 'Can one smoke at the entrance?', [['sì', 'no'], ['può', 'vietato']], 'No, è vietato.', [Kit::word('si può'), Kit::word('fumare'), Kit::word('vietato')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.chiusa', "È chiusa l'entrata?", 'Is the entrance closed?', [['sì', 'no'], ['aperta', 'chiusa', 'aperto', 'chiuso']], 'No, è aperta.', [Kit::word("l'entrata"), Kit::word('chiuso', 'chiusa'), Kit::word('aperto', 'aperta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.aperta-paolo', 'The entrance is open and Paolo has to go in.', ["L'entrata è aperta e Paolo deve entrare."], [Kit::word("l'entrata"), Kit::word('aperto', 'aperta'), Kit::word('entrare'), Kit::word('dovere', 'deve'), Kit::form('deve')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vietato-aspettare', 'It is prohibited to smoke, but you can wait here. (general rule)', ['È vietato fumare, ma si può aspettare qui.', 'Qui è vietato fumare, ma si può aspettare.'], [Kit::word('vietato'), Kit::word('fumare'), Kit::word('aspettare'), Kit::word('si può'), Kit::form('si può', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.chiusa-aspettare', 'The entrance is closed and we have to wait.', ["L'entrata è chiusa e dobbiamo aspettare.", "L'entrata è chiusa e noi dobbiamo aspettare.", "L'entrata qui è chiusa e dobbiamo aspettare."], [Kit::word("l'entrata"), Kit::word('chiuso', 'chiusa'), Kit::word('aspettare'), Kit::word('dovere', 'dobbiamo'), Kit::form('dobbiamo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.non-bisogna', 'You do not have to wait here. (informal you)', ['Non devi aspettare qui.', 'Qui non devi aspettare.'], [Kit::word('aspettare'), Kit::word('dovere', 'devi'), Kit::form('devi', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.si-puo-aspettare', "___ aspettare all'entrata?", 'Can one wait at the entrance?', 'Si può', Kit::form('si può'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-entrare', 'Marta, devi ___ qui?', 'Marta, do you have to go in here?', 'entrare', Kit::word('entrare'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.bisogna-entrare', 'Qui bisogna entrare.', 'You have to go in here.', [Kit::word('bisogna'), Kit::word('entrare'), Kit::form('bisogna')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.non-aspettare', 'Qui non si può aspettare, è chiuso.', 'You cannot wait here, it is closed.', [Kit::word('si può'), Kit::word('aspettare'), Kit::word('chiuso')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.aperta-vietato', "L'entrata è aperta, ma è vietato fumare.", 'The entrance is open, but smoking is prohibited.', [Kit::word("l'entrata"), Kit::word('aperto', 'aperta'), Kit::word('vietato'), Kit::word('fumare')], 'dictation', $set, homophoneNote: self::E_NOTE),
        ];
    }
}
