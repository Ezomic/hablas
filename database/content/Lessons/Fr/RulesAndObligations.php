<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

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
    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const ET_NOTE = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et means and.';

    private const EST_ET_NOTE = 'Est (is) and et (and) sound very close, and the sentence tells you which is which: est is the verb, and et joins two parts.';

    private const OU_NOTE = 'Ou (or) and où (where) sound the same. Here it is ou without an accent, because it means or.';

    private const A_NOTE = "À (to, at) and a (has, the verb) sound the same. Here it is à with an accent, as in à l'entrée.";

    private const DOIS_NOTE = 'Dois and doit sound the same. The subject decides: je dois, Anne doit.';

    private const FAUT_NOTE = 'Faut and faux (false) sound the same. Here it is faut, as in il faut.';

    private const MAIS_NOTE = 'Mais (but) and mes (my) sound very close, and the sentence tells you which is which: here it means but.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'rules-and-obligations';
    }

    public function words(): array
    {
        return [
            new WordData('interdit', cue: 'prohibited (not allowed)', forms: ['interdite', 'interdits', 'interdites'], note: 'After il est interdit comes de and an infinitive: il est interdit de fumer. Fumer est interdit also works, with no de.'),
            new WordData('ouvert', cue: 'open', forms: ['ouverte', 'ouverts', 'ouvertes']),
            new WordData('fermé', cue: 'closed', forms: ['fermée', 'fermés', 'fermées']),
            new WordData("l'entrée", cue: 'entrance (the way in)', forms: ['entrée', 'entrées'], note: "L'entrée is feminine. In a restaurant, une entrée is the starter (not the main course as in US English)."),
            new WordData('fumer', cue: 'to smoke'),
            new WordData('attendre', cue: 'to wait', note: 'Attendre means to wait, not to attend.'),
            new WordData('entrer', cue: 'to enter (to go in)'),
            new WordData('devoir', cue: 'to have to (must)', forms: ['dois', 'doit', 'devons'], note: 'Devoir is for a person: je dois, tu dois, il doit, nous devons. Only devoir changes, the infinitive after it stays the same.'),
            new WordData('il faut', cue: 'one has to (a rule for everybody)', note: 'Il faut has no other person and never changes. It states a general rule: il faut attendre. Il ne faut pas means you must not.'),
            new WordData('on peut', cue: 'one can (it is allowed)', note: 'On peut asks or says what is allowed. On ne peut pas means it is not allowed.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je dois entrer. Il faut attendre.', 'english' => 'I have to go in. One has to wait.'],
            ['text' => 'On ne peut pas fumer ici.', 'english' => 'You cannot smoke here.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.marie-attendre', 'Marie ___ attendre.', ['doit', 'il faut'], 'doit', Kit::form('doit', true), 'Marie is a person, so you need devoir: elle doit. Il faut has no other person and cannot follow Marie.', 'choose', 'Marie has to wait.'),
            Kit::gap($stage, 'sentences.choose_gap.attendre-ici', '___ attendre ici.', ['Il faut', 'Il y a'], 'Il faut', Kit::form('il faut', true), 'Il y a means there is. To say what has to be done you need il faut before the infinitive.', 'choose', 'One has to wait here.'),
            Kit::gap($stage, 'sentences.choose_gap.ne-peut-pas', 'On ne ___ pas fumer ici.', ['peut', 'est'], 'peut', Kit::form('peut', true), 'On ne peut pas says that something is not allowed. Est cannot be followed by an infinitive here.', 'choose', 'You cannot smoke here.'),
            Kit::gap($stage, 'sentences.choose_gap.entree-fermee', "L'entrée est ___.", ['fermée', 'fermé'], 'fermée', Kit::word('fermé', 'fermée'), "L'entrée is feminine, so the adjective ends in e: fermée.", 'choose', 'The entrance is closed.'),
            Kit::gap($stage, 'sentences.choose_gap.interdit', 'Il est ___ de fumer ici.', ['interdit', 'fermé', 'ouvert'], 'interdit', Kit::word('interdit'), 'Il est interdit de fumer means smoking is not allowed. Fermé is closed and ouvert is open.', 'choose', 'Smoking is prohibited here.'),
            Kit::gap($stage, 'sentences.choose_gap.entrer', 'Il faut ___ par ici.', ['entrer', 'ouverte'], 'entrer', Kit::word('entrer'), 'After il faut you need a verb in the infinitive: entrer. Ouverte describes something, it is not a verb.', 'choose', 'You have to go in this way.'),

            Kit::typeGap($stage, 'sentences.type_gap.paul-entrer', 'Paul ___ entrer.', 'Paul has to go in.', 'doit', Kit::word('devoir', 'doit'), 'Devoir changes with the person: Paul doit. Il faut never changes and has no other person.'),
            Kit::typeGap($stage, 'sentences.type_gap.ouverte', "L'entrée est ___.", 'The entrance is open.', 'ouverte', Kit::word('ouvert', 'ouverte')),
            Kit::typeGap($stage, 'sentences.type_gap.fumer', 'On ne peut pas ___ ici.', 'Smoking is not allowed here.', 'fumer', Kit::word('fumer')),
            Kit::typeGap($stage, 'sentences.type_gap.on-peut-entrer', '___ entrer ici ?', 'Is it possible to go in here?', 'On peut', Kit::form('on peut', true), 'On peut asks whether something is allowed or possible. Il faut would ask whether you have to go in.'),
            Kit::typeGap($stage, 'sentences.type_gap.dois-attendre', 'Je dois ___ ici.', 'I have to wait here.', 'attendre', Kit::word('attendre')),

            Kit::translate($stage, 'sentences.translate.dois-entrer', 'I have to go in.', ['Je dois entrer.'], [Kit::word('entrer'), Kit::word('devoir', 'dois'), Kit::form('dois')]),
            Kit::translate($stage, 'sentences.translate.pas-fumer', 'You cannot smoke here. (general rule)', ['On ne peut pas fumer ici.', 'Ici, on ne peut pas fumer.', 'Il est interdit de fumer ici.', 'Ici, il est interdit de fumer.', 'Fumer est interdit ici.', 'Il ne faut pas fumer ici.', 'Ici, il ne faut pas fumer.'], [Kit::word('fumer'), Kit::form('on ne peut pas', false, ['il est interdit', 'est interdit', 'il ne faut pas'])]),
            Kit::translate($stage, 'sentences.translate.faut-entrer', 'One has to go in here. (general rule)', ['Il faut entrer ici.', 'Ici, il faut entrer.'], [Kit::word('entrer'), Kit::word('il faut'), Kit::form('il faut')]),

            Kit::build($stage, 'sentences.build.marie-attendre', 'Marie has to wait.', 'Marie doit attendre.', ['la'], [Kit::word('attendre'), Kit::word('devoir', 'doit'), Kit::form('doit')]),
            Kit::build($stage, 'sentences.build.entree-fermee', 'The entrance is closed.', "L'entrée est fermée.", ['fermé'], [Kit::word("l'entrée"), Kit::word('fermé', 'fermée')]),
            Kit::build($stage, 'sentences.build.attendre-ici', 'Is it possible to wait here?', 'On peut attendre ici ?', ['faut'], [Kit::word('on peut'), Kit::word('attendre'), Kit::form('on peut')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.fumer', 'On ne peut pas fumer ici.', ['You cannot smoke here.', 'You have to smoke here.', 'Smoking is allowed here.', 'You cannot wait here.'], 'You cannot smoke here.', [Kit::word('fumer'), Kit::word('on peut', 'peut'), Kit::form('on ne peut pas')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.dois-entrer', 'Je dois entrer ici.', ['I have to go in here.', 'I have to wait here.', 'The entrance is closed.', 'I do not have to go in.'], 'I have to go in here.', [Kit::word('entrer'), Kit::word('devoir', 'dois'), Kit::form('dois')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ouverte', "L'entrée est ouverte.", ['The entrance is open.', 'The entrance is closed.', 'The entrance is here.', 'The entrance is prohibited.'], 'The entrance is open.', [Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte')]),
            Kit::listenType($stage, 'sentences.listen_type.faut-attendre', 'Il faut attendre ici.', 'One has to wait here.', [Kit::word('il faut'), Kit::word('attendre'), Kit::form('il faut')], homophoneNote: self::FAUT_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.on-peut-attendre', 'On peut attendre ici ?', 'Is it possible to wait here?', [Kit::word('on peut'), Kit::word('attendre'), Kit::form('on peut')]),
            Kit::listenType($stage, 'sentences.listen_type.anne-attendre', 'Anne doit attendre.', 'Anne has to wait.', [Kit::word('devoir', 'doit'), Kit::word('attendre'), Kit::form('doit')], homophoneNote: self::DOIS_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.interdit', 'Il est interdit de fumer ici.', 'Smoking is prohibited here.', [Kit::word('interdit'), Kit::word('fumer')], homophoneNote: self::EST_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.dois-attendre', "Je dois attendre à l'entrée.", 'I have to wait at the entrance.', [Kit::word('devoir', 'dois'), Kit::word('attendre'), Kit::word("l'entrée"), Kit::form('dois')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fumer', 'On ne peut pas fumer ici.', 'You cannot smoke here.', [Kit::word('on peut', 'peut'), Kit::word('fumer'), Kit::form('on ne peut pas')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fermee', "L'entrée est fermée.", 'The entrance is closed.', [Kit::word("l'entrée"), Kit::word('fermé', 'fermée')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ouvert', "C'est ouvert et il faut entrer.", 'It is open and you have to go in.', [Kit::word('ouvert'), Kit::word('il faut'), Kit::word('entrer'), Kit::form('il faut')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.on-peut-entrer', 'On peut entrer ici ?', 'Is it possible to go in here?', [['oui', 'non'], ['peut', 'entrer']], 'Oui, on peut entrer ici.', [Kit::word('on peut'), Kit::word('entrer')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tu-dois-entrer', 'Tu dois entrer ici ?', 'Do you have to go in here?', [['oui', 'non'], ['dois', 'entrer']], 'Oui, je dois entrer.', [Kit::word('devoir', 'dois'), Kit::word('entrer'), Kit::form('dois')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ouverte', "L'entrée est ouverte ?", 'Is the entrance open?', [['oui', 'non'], ['ouverte', 'fermée', 'ouvert', 'fermé']], 'Oui, elle est ouverte.', [Kit::word('ouvert', 'ouverte'), Kit::word('fermé')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fumer', 'Read the conversation.', [
                Kit::line('Anne', 'Paul, on peut fumer ici ?'),
                Kit::line('Paul', 'Non, il est interdit de fumer.'),
                Kit::line('Anne', "L'entrée est ouverte ?"),
                Kit::line('Paul', 'Non, elle est fermée. Il faut attendre.'),
                Kit::line('Anne', 'Et nous devons entrer par ici ?'),
                Kit::line('Paul', 'Oui, il faut entrer par ici.'),
            ], [
                Kit::question('Is smoking allowed here?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('What do they have to do?', ['Wait and go in', 'Smoke and wait', 'Nothing'], 'Wait and go in'),
            ], [Kit::word('on peut'), Kit::word('fumer'), Kit::word('interdit'), Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte'), Kit::word('fermé', 'fermée'), Kit::word('il faut'), Kit::word('attendre'), Kit::word('devoir', 'devons'), Kit::word('entrer')], 'read'),
            Kit::gap($stage, 'task.choose_gap.tu-entrer', 'Tu ___ entrer ici.', ['dois', 'il faut'], 'dois', Kit::form('dois', true), 'Tu is a person, so you need devoir: tu dois. Il faut has no other person and cannot follow tu.', 'read', 'You have to go in here. (informal you)'),
            Kit::gap($stage, 'task.choose_gap.interdit-fumer', "C'est interdit : ___ fumer ici.", ['on ne peut pas', 'on peut'], 'on ne peut pas', Kit::form('on ne peut pas', true), 'The sign says it is prohibited, so the rule is on ne peut pas. On peut would say that it is allowed.', 'read', 'It is prohibited: you cannot smoke here.'),

            Kit::transform($stage, 'task.transform.anne-entrer', 'Now say it about Anne.', 'Je dois entrer.', ['Anne doit entrer.'], [Kit::word('entrer'), Kit::word('devoir', 'doit'), Kit::form('doit', true)]),
            Kit::transform($stage, 'task.transform.pas-fumer', 'Make it negative.', 'On peut fumer ici.', ['On ne peut pas fumer ici.', 'Ici, on ne peut pas fumer.'], [Kit::word('fumer'), Kit::word('on peut', 'peut'), Kit::form('on ne peut pas', true)]),
            Kit::transform($stage, 'task.transform.nous', 'Say it with nous.', 'Tu dois attendre.', ['Nous devons attendre.'], [Kit::word('attendre'), Kit::word('devoir', 'devons'), Kit::form('devons')]),
            Kit::writeGuided($stage, 'task.write_guided.fermee', 'You are at the entrance: say that the entrance is closed and that you, yourself, have to wait.', ["l'entrée", 'fermée', 'je dois', 'attendre'], "L'entrée est fermée. Je dois attendre.", [
                ['forms' => ["l'entrée"], 'term' => "l'entrée"],
                ['forms' => ['fermée', 'fermé'], 'term' => 'fermé'],
                ['forms' => ['dois'], 'term' => 'devoir'],
                ['forms' => ['attendre'], 'term' => 'attendre'],
            ], [Kit::word("l'entrée"), Kit::word('fermé', 'fermée'), Kit::word('devoir', 'dois'), Kit::word('attendre')]),
            Kit::writeGuided($stage, 'task.write_guided.interdit', 'Say that smoking is prohibited here and that one has to go in this way (a general rule).', ['interdit', 'fumer', 'il faut', 'entrer'], 'Il est interdit de fumer ici. Il faut entrer par ici.', [
                ['forms' => ['interdit'], 'term' => 'interdit'],
                ['forms' => ['fumer'], 'term' => 'fumer'],
                ['forms' => ['faut'], 'term' => 'il faut'],
                ['forms' => ['entrer'], 'term' => 'entrer'],
            ], [Kit::word('interdit'), Kit::word('fumer'), Kit::word('il faut'), Kit::word('entrer')]),
            Kit::build($stage, 'task.build.devons-entrer', 'We have to go in here.', 'Nous devons entrer ici.', ['peut', 'interdit'], [Kit::word('devoir', 'devons'), Kit::word('entrer'), Kit::form('devons')], 'write'),
            Kit::build($stage, 'task.build.ne-dois-pas', 'You must not wait. (informal you)', 'Tu ne dois pas attendre.', ['la', 'et'], [Kit::word('devoir', 'dois'), Kit::word('attendre'), Kit::form('dois', true)], 'write'),
            Kit::build($stage, 'task.build.pas-attendre-ici', 'You cannot wait here. (general rule)', 'On ne peut pas attendre ici.', ['la', 'très'], [Kit::word('on peut', 'peut'), Kit::word('attendre'), Kit::form('on ne peut pas')], 'write'),
            Kit::translate($stage, 'task.translate.ouverte-entrer', 'The entrance is open and I have to go in.', ["L'entrée est ouverte et je dois entrer."], [Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte'), Kit::word('entrer'), Kit::word('devoir', 'dois'), Kit::form('dois')], 'write'),
            Kit::translate($stage, 'task.translate.attendre-fumer', 'You can wait here, but you cannot smoke. (general rule)', ['On peut attendre ici, mais on ne peut pas fumer.', 'Ici, on peut attendre, mais on ne peut pas fumer.', 'On peut attendre ici, mais il est interdit de fumer.', 'On peut attendre ici, mais il ne faut pas fumer.'], [Kit::word('attendre'), Kit::word('fumer'), Kit::word('on peut'), Kit::form('on peut', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.entree', [
                Kit::line('Marie', "Luc, l'entrée est ouverte ?"),
                Kit::line('Luc', 'Non, elle est fermée. Nous devons attendre.'),
                Kit::line('Marie', 'On peut fumer ici ?'),
                Kit::line('Luc', "Non, c'est interdit. Et il faut entrer par ici."),
            ], [
                Kit::question('Is the entrance open?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What do they have to do?', ['Wait', 'Smoke', 'Leave'], 'Wait'),
                Kit::question('What does Luc say about smoking?', ['It is not allowed.', 'It is allowed.', 'He wants to smoke.'], 'It is not allowed.'),
            ], [
                Kit::question('Who asks about the entrance?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('Does Luc say you have to go in this way?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte'), Kit::word('fermé', 'fermée'), Kit::word('devoir', 'devons'), Kit::word('attendre'), Kit::word('on peut'), Kit::word('fumer'), Kit::word('interdit'), Kit::word('il faut'), Kit::word('entrer')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.dois-entrer-attendre', 'Je dois entrer et attendre ici.', 'I have to go in and wait here.', [Kit::word('devoir', 'dois'), Kit::word('entrer'), Kit::word('attendre'), Kit::form('dois')], 'listen', homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'task.listen_type.interdit-faut', 'Il est interdit de fumer et il faut attendre ici.', 'Smoking is prohibited and you have to wait here.', [Kit::word('interdit'), Kit::word('fumer'), Kit::word('il faut'), Kit::word('attendre'), Kit::form('il faut')], 'listen', homophoneNote: self::EST_ET_NOTE),
            Kit::listenType($stage, 'task.listen_type.on-peut-ou-faut', 'On peut attendre ici ou il faut entrer ?', 'Can one wait here, or do you have to go in?', [Kit::word('on peut'), Kit::word('attendre'), Kit::word('il faut'), Kit::word('entrer'), Kit::form('on peut')], 'listen', homophoneNote: self::OU_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.fumer', 'On peut fumer ici ?', 'Is it possible to smoke here?', [['oui', 'non'], ['peut', 'interdit']], 'Non, on ne peut pas.', [Kit::word('on peut'), Kit::word('fumer'), Kit::word('interdit')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ferme', "C'est fermé ?", 'Is it closed?', [['oui', 'non'], ['ouvert', 'fermé', 'ouverte', 'fermée']], "Non, c'est ouvert.", [Kit::word('ouvert'), Kit::word('fermé')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.faut-entrer', 'Il faut entrer ici ?', 'Do you have to go in here?', [['oui', 'non'], ['faut', 'entrer']], 'Oui, il faut entrer.', [Kit::word('il faut'), Kit::word('entrer'), Kit::form('il faut')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.attendre', 'Tu dois attendre ?', 'Do you have to wait?', [['oui', 'non'], ['dois', 'faut']], 'Oui, je dois attendre.', [Kit::word('devoir', 'dois'), Kit::word('attendre'), Kit::form('dois')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.interdit', "Il est interdit de fumer et il faut attendre à l'entrée.", 'Smoking is prohibited and you have to wait at the entrance.', [Kit::word('interdit'), Kit::word('fumer'), Kit::word('il faut'), Kit::word('attendre'), Kit::word("l'entrée")], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ouverte', "L'entrée est ouverte et je dois entrer.", 'The entrance is open and I have to go in.', [Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte'), Kit::word('devoir', 'dois'), Kit::word('entrer')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.marie-attendre', 'Marie has to wait at the entrance.', ["Marie doit attendre à l'entrée."], [Kit::word('attendre'), Kit::word("l'entrée"), Kit::word('devoir', 'doit'), Kit::form('doit')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fumer-attendre', 'You cannot smoke here, but you can wait. (general rule)', ['On ne peut pas fumer ici, mais on peut attendre.', 'Ici, on ne peut pas fumer, mais on peut attendre.', 'Il est interdit de fumer ici, mais on peut attendre.', 'Il ne faut pas fumer ici, mais on peut attendre.'], [Kit::word('fumer'), Kit::word('attendre'), Kit::form('on ne peut pas', true, ['il est interdit', 'il ne faut pas'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ouverte-ici', 'The entrance is open, but smoking is prohibited.', ["L'entrée est ouverte, mais il est interdit de fumer.", "L'entrée est ouverte, mais on ne peut pas fumer.", "L'entrée est ouverte, mais fumer est interdit."], [Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte'), Kit::word('fumer')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.faut-entrer', 'You have to go in here, but you must not wait. (general rule, with il faut)', ['Il faut entrer ici, mais il ne faut pas attendre.', 'Ici, il faut entrer, mais il ne faut pas attendre.'], [Kit::word('entrer'), Kit::word('attendre'), Kit::form('il faut', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luc-attendre', 'Luc ___ attendre ici.', 'Luc has to wait here.', 'doit', Kit::form('doit'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.interdit-fumer', "Il est interdit de ___ à l'entrée.", 'Smoking is prohibited at the entrance.', 'fumer', Kit::word('fumer'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.fermee-faut', "Ici l'entrée est fermée, il faut attendre.", 'The entrance is closed, you have to wait.', [Kit::word("l'entrée"), Kit::word('fermé', 'fermée'), Kit::word('attendre'), Kit::word('il faut'), Kit::form('il faut')], 'dictation', $set, homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.interdit-attendre', "Attendre à l'entrée, c'est interdit.", 'Waiting at the entrance is prohibited.', [Kit::word('interdit'), Kit::word('attendre'), Kit::word("l'entrée")], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.ouvert-fumer', "Ici, c'est ouvert, mais on ne peut pas fumer.", 'It is open here, but you cannot smoke.', [Kit::word('ouvert'), Kit::word('fumer'), Kit::word('on peut', 'peut'), Kit::form('on ne peut pas')], 'dictation', $set, homophoneNote: self::MAIS_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.entrer', [
                Kit::line('Anne', 'Paul, tu dois entrer ici ?'),
                Kit::line('Paul', 'Oui, je dois entrer. Et on ne peut pas fumer ici.'),
                Kit::line('Anne', "Très bien. C'est ouvert ?"),
                Kit::line('Paul', "Oui, c'est ouvert."),
            ], [
                Kit::question('What does Paul have to do?', ['Go in', 'Wait', 'Smoke'], 'Go in'),
                Kit::question('What does Paul say about smoking?', ['It is not allowed.', 'It is allowed.', 'He has to smoke.'], 'It is not allowed.'),
                Kit::question('Is it open?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who asks the questions?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
                Kit::question('Does Paul have to go in?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('entrer'), Kit::word('devoir', 'dois'), Kit::word('on peut', 'peut'), Kit::word('fumer'), Kit::word('ouvert')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.entree', 'Read the conversation.', [
                Kit::line('Marie', "Luc, c'est ouvert ?"),
                Kit::line('Luc', "Non, c'est fermé. Je dois attendre."),
                Kit::line('Marie', "Attendre, c'est interdit ?"),
                Kit::line('Luc', 'Non, on peut attendre. Mais on ne peut pas fumer.'),
            ], [
                Kit::question('Is it open?', ['Yes', 'No'], 'No'),
                Kit::question('Is waiting allowed?', ['Yes', 'No'], 'Yes'),
            ], [Kit::word('ouvert'), Kit::word('fermé'), Kit::word('on peut'), Kit::word('attendre'), Kit::word('interdit'), Kit::word('fumer'), Kit::word('devoir', 'dois')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.attendre', "Il faut attendre à l'entrée ?", 'Do you have to wait at the entrance?', [['oui', 'non'], ['faut', 'attendre']], 'Oui, il faut attendre.', [Kit::word('il faut'), Kit::word('attendre')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fumer', "On peut fumer à l'entrée ?", 'Can one smoke at the entrance?', [['oui', 'non'], ['peut', 'interdit']], "Non, c'est interdit.", [Kit::word('on peut'), Kit::word('fumer'), Kit::word('interdit')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fermee', "Ici, l'entrée est fermée ?", 'Is the entrance closed here?', [['oui', 'non'], ['ouverte', 'fermée', 'ouvert', 'fermé']], 'Non, elle est ouverte.', [Kit::word("l'entrée"), Kit::word('fermé', 'fermée'), Kit::word('ouvert', 'ouverte')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.ouverte-paul', 'The entrance is open and Paul has to go in.', ["L'entrée est ouverte et Paul doit entrer."], [Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte'), Kit::word('entrer'), Kit::word('devoir', 'doit'), Kit::form('doit')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.interdit-attendre', 'It is prohibited to smoke, but you can wait here. (general rule)', ['Il est interdit de fumer, mais on peut attendre ici.', 'Ici, il est interdit de fumer, mais on peut attendre.'], [Kit::word('interdit'), Kit::word('fumer'), Kit::word('attendre'), Kit::word('on peut'), Kit::form('on peut', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.fermee-attendre', 'The entrance is closed and we have to wait.', ["L'entrée est fermée et nous devons attendre."], [Kit::word("l'entrée"), Kit::word('fermé', 'fermée'), Kit::word('attendre'), Kit::word('devoir', 'devons'), Kit::form('devons')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ne-faut-pas', 'You must not wait here. (general rule, with il faut)', ['Il ne faut pas attendre ici.', 'Ici, il ne faut pas attendre.'], [Kit::word('attendre'), Kit::word('il faut', 'faut'), Kit::form('il ne faut pas', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.on-peut-attendre', "___ attendre à l'entrée ?", 'Can one wait at the entrance?', 'On peut', Kit::form('on peut'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marie-entrer', 'Marie, tu dois ___ ici ?', 'Marie, do you have to go in here?', 'entrer', Kit::word('entrer'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.faut-entrer', 'Ici, il faut entrer.', 'You have to go in here.', [Kit::word('il faut'), Kit::word('entrer'), Kit::form('il faut')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pas-attendre', "Ici on ne peut pas attendre, c'est fermé.", 'You cannot wait here, it is closed.', [Kit::word('on peut', 'peut'), Kit::word('attendre'), Kit::word('fermé')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ouverte-interdit', "L'entrée est ouverte et il est interdit de fumer.", 'The entrance is open and smoking is prohibited.', [Kit::word("l'entrée"), Kit::word('ouvert', 'ouverte'), Kit::word('interdit'), Kit::word('fumer')], 'dictation', $set, homophoneNote: self::EST_ET_NOTE),
        ];
    }
}
