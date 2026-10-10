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

final class MakingPlans implements UnitContent
{
    private const A_NOTE = 'The word a (to) sounds the same as ha (has). Here it is a, as in vado a or andiamo a.';

    private const HA_NOTE = 'Ha (has) sounds the same as a (to). Here it is ha, as in Anna ha una festa.';

    private const E_NOTE = 'È (is) has an accent and e (and) does not. They sound close, and the sentence tells you which is which.';

    private const O_NOTE = 'O (or) sounds the same as ho (I have). Here it is o, as in al cinema o al bar.';

    private const HO_NOTE = 'Ho (I have) sounds the same as o (or). Here it is ho, as in ho un programma.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'making-plans';
    }

    public function words(): array
    {
        return [
            new WordData('il programma', cue: 'plan (something you arrange to do)', forms: ['programmi']),
            new WordData('la festa', cue: 'party'),
            new WordData('il cinema', cue: 'cinema'),
            new WordData('la cena', cue: 'dinner (the evening meal)', forms: ['cenare']),
            new WordData('il bar', cue: 'café, bar (the place where you meet)'),
            new WordData('invitare', cue: 'to invite', forms: ['invito', 'inviti']),
            new WordData('vedersi', cue: 'to meet up (to see each other)', forms: ['ci', 'vediamo']),
            new WordData('vuoi venire?', cue: 'do you want to come? (informal you)', forms: ['vuoi', 'venire', 'vengo']),
            new WordData('mi dispiace', cue: 'I am sorry'),
            new WordData('certo', cue: 'of course', accepted: ['certo che sì', 'certamente']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Andiamo a cenare al bar.', 'english' => 'We are going to have dinner at the bar.'],
            ['text' => 'Puoi venire? Non posso.', 'english' => 'Can you come? I cannot.'],
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
            Kit::gap($stage, 'sentences.choose_gap.vado-cenare', 'Io ___ a cenare con Marta.', ['vado', 'vai', 'va'], 'vado', Kit::form('vado', true), 'Io goes with vado. Vai is for tu and va is for lui or lei.', 'choose', glosses: ['vado' => 'I go', 'vai' => 'you go', 'va' => 'he or she goes', 'cenare' => 'to have dinner']),
            Kit::gap($stage, 'sentences.choose_gap.mi-dispiace', 'Puoi venire? No, ___, non posso.', ['mi dispiace', 'il programma', 'la festa'], 'mi dispiace', Kit::word('mi dispiace'), 'Mi dispiace says you are sorry. Il programma and la festa are things, so they do not fit here.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.cinema', 'Andiamo al ___ con Marta.', ['cinema', 'cena', 'festa'], 'cinema', Kit::word('il cinema', 'cinema'), 'Al means a + il, so it needs a masculine word: il cinema. Cena and festa are feminine and take alla.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.certo', 'Puoi venire? ___, vengo.', ['Certo', 'La cena', 'Il programma'], 'Certo', Kit::word('certo'), 'Certo means of course, so it fits a yes. La cena and il programma are things, not answers.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.invitare', 'Posso ___ Paolo alla festa?', ['invitare', 'cenare', 'venire'], 'invitare', Kit::word('invitare'), 'You invite a person: invitare Paolo. Cenare means to have dinner and venire means to come.', 'choose', glosses: ['cenare' => 'to have dinner', 'venire' => 'to come']),
            Kit::gap($stage, 'sentences.choose_gap.puoi', 'Paolo, tu ___ venire alla cena?', ['puoi', 'posso', 'possiamo'], 'puoi', Kit::form('puoi', true), 'Tu goes with puoi. Posso is for io and possiamo is for noi.', 'choose', glosses: ['puoi' => 'you can', 'posso' => 'I can', 'possiamo' => 'we can']),

            Kit::typeGap($stage, 'sentences.type_gap.andiamo-cenare', '___ a cenare al bar.', 'We are going to have dinner at the bar.', 'Andiamo', Kit::form('andiamo'), 'We is andiamo. After andiamo come a and the verb: andiamo a cenare.', glosses: ['cenare' => 'to have dinner']),
            Kit::typeGap($stage, 'sentences.type_gap.vado-cenare', 'Io ___ a cenare con Luca.', 'I am going to have dinner with Luca.', 'vado', Kit::form('vado', true), 'I is vado. After vado come a and the verb: vado a cenare.', glosses: ['cenare' => 'to have dinner']),
            Kit::typeGap($stage, 'sentences.type_gap.puoi-cinema', 'Paolo, ___ venire al cinema?', 'Paolo, can you come to the cinema? (informal you)', 'puoi', Kit::form('puoi'), 'Paolo is the person you ask, so use puoi. The verb after it stays venire.', glosses: ['venire' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.posso-festa', 'Io non ___ venire alla festa.', 'I cannot come to the party.', 'posso', Kit::form('posso'), 'I is posso, and non goes before it. There is no a before venire.', glosses: ['venire' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.dove-vediamo', 'Dove ci ___?', 'Where shall we see each other?', 'vediamo', Kit::word('vedersi', 'vediamo'), 'Ci vediamo means we meet up, from vedersi.', glosses: ['vediamo' => 'we meet up']),

            Kit::translate($stage, 'sentences.translate.possiamo-invitare', 'Can we invite Paolo?', ['Possiamo invitare Paolo?', 'Noi possiamo invitare Paolo?'], [Kit::word('invitare'), Kit::form('possiamo')], glosses: ['invitare' => 'to invite']),
            Kit::translate($stage, 'sentences.translate.puoi-festa', 'Can you come to the party? (informal you)', ['Puoi venire alla festa?', 'Tu puoi venire alla festa?'], [Kit::word('la festa', 'festa'), Kit::form('puoi')], glosses: ['venire' => 'to come']),
            Kit::translate($stage, 'sentences.translate.vado-cenare', 'I am going to have dinner with Luca.', ['Vado a cenare con Luca.', 'Io vado a cenare con Luca.'], [Kit::word('la cena', 'cenare'), Kit::form('vado a')], glosses: ['cenare' => 'to have dinner']),

            Kit::build($stage, 'sentences.build.posso-invitare', 'Can I invite Anna?', 'Posso invitare Anna?', ['possiamo'], [Kit::word('invitare'), Kit::form('posso')], glosses: ['invitare' => 'to invite']),
            Kit::build($stage, 'sentences.build.puoi-bar', 'Can you come to the bar? (informal you)', 'Puoi venire al bar?', ['posso'], [Kit::word('il bar', 'bar'), Kit::form('puoi')], glosses: ['venire' => 'to come']),
            Kit::build($stage, 'sentences.build.vediamo-bar', 'We are meeting at the bar.', 'Ci vediamo al bar.', ['vedersi'], [Kit::word('vedersi', 'vediamo'), Kit::word('il bar', 'bar')], glosses: ['vedersi' => 'to meet up', 'vediamo' => 'we meet up']),

            Kit::listenChoose($stage, 'sentences.listen_choose.programma-venire', 'Hai programmi? Vuoi venire?', ['Do you have any plans? Do you want to come?', 'Do you have any plans? Can you come?', 'Do you have a party? Do you want to come?', 'Do I have any plans? Do you want to come?'], 'Do you have any plans? Do you want to come?', [Kit::word('il programma', 'programmi'), Kit::word('vuoi venire?', 'vuoi venire')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.mi-dispiace', 'Mi dispiace, non posso venire.', ['I am sorry, I cannot come.', 'Of course, I can come.', 'I am sorry, we cannot come.', 'I am sorry, you cannot come.'], 'I am sorry, I cannot come.', [Kit::word('mi dispiace'), Kit::form('posso')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.andiamo-cenare', 'Andiamo a cenare con Marta.', ['We are going to have dinner with Marta.', 'I am going to have dinner with Marta.', 'We are going to have dinner with Anna.', 'They are going to have dinner with Marta.'], 'We are going to have dinner with Marta.', [Kit::word('la cena', 'cenare'), Kit::form('andiamo a')]),
            Kit::listenType($stage, 'sentences.listen_type.certo-vado', 'Certo, vado a cenare con Paolo.', 'Of course, I am going to have dinner with Paolo.', [Kit::word('certo'), Kit::form('vado a')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.programma-cenare', 'Il programma è cenare al bar.', 'The plan is to have dinner at the bar.', [Kit::word('il programma', 'programma'), Kit::word('il bar', 'bar'), Kit::word('la cena', 'cenare')], homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.festa', 'Anna ha una festa al bar. Vuoi venire?', 'Anna has a party at the bar. Do you want to come?', [Kit::word('la festa', 'festa'), Kit::word('vuoi venire?', 'vuoi venire')], homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.puoi-cenare', 'Anna, perché non puoi cenare?', 'Anna, why can you not have dinner?', [Kit::form('puoi'), Kit::word('la cena', 'cenare')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.vuoi-cinema', 'Vuoi venire al cinema?', 'Do you want to come to the cinema?', [Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('il cinema', 'cinema')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.perche', 'Perché non puoi venire?', 'Why can you not come? (informal you)', [Kit::form('puoi')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.posso-invitare', 'Posso invitare Paolo?', 'Can I invite Paolo?', [Kit::word('invitare'), Kit::form('posso')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.dove-vediamo', 'Dove ci vediamo? Al bar?', 'Where shall we meet? At the bar?', [Kit::word('vedersi', 'vediamo'), Kit::word('il bar', 'bar')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cinema', 'Vuoi venire al cinema?', 'Do you want to come to the cinema?', [['certo', 'sì', 'no', 'dispiace'], ['vengo', 'posso', 'certo']], 'Certo, vengo.', [Kit::word('certo'), Kit::word('il cinema', 'cinema')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cena', 'Puoi venire alla cena?', 'Can you come to the dinner? (informal you)', [['sì', 'no', 'certo', 'dispiace'], ['posso', 'vengo', 'certo']], 'Sì, posso venire.', [Kit::word('la cena'), Kit::form('posso')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.dove', 'Dove ci vediamo?', 'Where shall we meet?', [['vediamo', 'al'], ['cinema', 'bar']], 'Ci vediamo al bar.', [Kit::word('vedersi', 'vediamo'), Kit::word('il bar', 'bar')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.festa', 'Read the conversation.', [
                Kit::line('Anna', "Ciao, Paolo. C'è una festa al bar. Vuoi venire?"),
                Kit::line('Paolo', 'Mi dispiace, Anna. Non posso venire.'),
                Kit::line('Paolo', 'Vado a cenare con Marta.'),
                Kit::line('Anna', 'Va bene, hai un programma. Ciao!'),
            ], [
                Kit::question('Where is the party?', ['In the bar', 'In the cinema', 'In a restaurant'], 'In the bar'),
                Kit::question('Can Paolo come?', ['No', 'Yes', 'The text does not say.'], 'No'),
                Kit::question('What is Paolo going to do?', ['Have dinner with Marta', 'Invite Anna', 'Go to the cinema'], 'Have dinner with Marta'),
            ], [Kit::word('la festa', 'festa'), Kit::word('il bar', 'bar'), Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('mi dispiace'), Kit::word('la cena', 'cenare')], 'read'),
            Kit::gap($stage, 'task.choose_gap.va-cenare', 'Anna va ___ cenare con Paolo.', ['a', 'di', 'in'], 'a', Kit::form('a', true), 'When you say you are going off to do something, a comes between andare and the verb: va a cenare. Di and in do not link andare to a verb.', 'read', glosses: ['cenare' => 'to have dinner']),
            Kit::gap($stage, 'task.choose_gap.programma', 'Il ___ è cenare con Anna.', ['programma', 'cena', 'festa'], 'programma', Kit::word('il programma', 'programma'), 'Il goes with a masculine word. Cena and festa are feminine, so they need la.', 'read', glosses: ['cenare' => 'to have dinner']),

            Kit::transform($stage, 'task.transform.andiamo-cenare', 'Change it to noi.', 'Vado a cenare al bar.', ['Andiamo a cenare al bar.', 'Noi andiamo a cenare al bar.'], [Kit::word('la cena', 'cenare'), Kit::form('andiamo a')]),
            Kit::transform($stage, 'task.transform.marta-puo', 'Say it about Marta.', 'Posso venire alla festa.', ['Marta può venire alla festa.'], [Kit::word('la festa', 'festa'), Kit::form('può')]),
            Kit::transform($stage, 'task.transform.vai-cenare', 'Change it to tu.', 'Vado a cenare con Anna.', ['Vai a cenare con Anna.', 'Tu vai a cenare con Anna.'], [Kit::word('la cena', 'cenare'), Kit::form('vai a')]),
            Kit::writeGuided($stage, 'task.write_guided.invitare', 'Say that you can invite Paolo to the party.', ['posso', 'invitare', 'festa'], 'Posso invitare Paolo alla festa.', [
                ['forms' => ['posso'], 'term' => null],
                ['forms' => ['invitare'], 'term' => 'invitare'],
                ['forms' => ['festa'], 'term' => 'la festa'],
            ], [Kit::word('invitare'), Kit::word('la festa', 'festa')]),
            Kit::writeGuided($stage, 'task.write_guided.mi-dispiace', 'Say that you are sorry and that you cannot come to the cinema.', ['mi dispiace', 'non posso', 'venire', 'cinema'], 'Mi dispiace, non posso venire al cinema.', [
                ['forms' => ['dispiace'], 'term' => 'mi dispiace'],
                ['forms' => ['posso'], 'term' => null],
                ['forms' => ['cinema'], 'term' => 'il cinema'],
            ], [Kit::word('mi dispiace'), Kit::word('il cinema', 'cinema')], ['venire' => 'to come']),
            Kit::build($stage, 'task.build.andiamo-cenare', 'We are going to have dinner at the bar.', 'Andiamo a cenare al bar.', ['vado', 'con'], [Kit::word('la cena', 'cenare'), Kit::word('il bar', 'bar'), Kit::form('andiamo a')], 'write', ['cenare' => 'to have dinner']),
            Kit::build($stage, 'task.build.puoi-invitare', 'Can you invite Marta to the party? (informal you)', 'Puoi invitare Marta alla festa?', ['posso', 'in'], [Kit::word('invitare'), Kit::word('la festa', 'festa'), Kit::form('puoi')], 'write', ['invitare' => 'to invite']),
            Kit::build($stage, 'task.build.mi-dispiace-cena', 'I am sorry, I cannot come to the dinner.', 'Mi dispiace, non posso venire alla cena.', ['puoi', 'certo'], [Kit::word('mi dispiace'), Kit::word('la cena', 'cena'), Kit::form('posso')], 'write', ['venire' => 'to come']),
            Kit::translate($stage, 'task.translate.vai-bar', 'Are you going to have dinner at the bar? (informal you)', ['Vai a cenare al bar?', 'Tu vai a cenare al bar?'], [Kit::word('il bar', 'bar'), Kit::word('la cena', 'cenare'), Kit::form('vai a')], 'write', glosses: ['cenare' => 'to have dinner']),
            Kit::translate($stage, 'task.translate.certo-vedersi', 'Of course, we will meet at the bar.', ['Certo, ci vediamo al bar.', 'Certo, noi ci vediamo al bar.'], [Kit::word('certo'), Kit::word('vedersi', 'vediamo')], 'write', glosses: ['vediamo' => 'we meet up']),

            Kit::listenPassage($stage, 'task.listen_passage.cinema', [
                Kit::line('Marta', 'Ciao, Luca. Hai programmi?'),
                Kit::line('Luca', 'No, perché?'),
                Kit::line('Marta', 'Anna e io andiamo al cinema. Vuoi venire?'),
                Kit::line('Luca', 'Certo. Dove ci vediamo?'),
                Kit::line('Marta', 'Ci vediamo al cinema.'),
                Kit::line('Luca', 'Va bene. Ciao.'),
            ], [
                Kit::question('What are Marta and Anna going to do?', ['Go to the cinema', 'Go to a party', 'Have dinner'], 'Go to the cinema'),
                Kit::question('What does Luca answer to the invitation?', ['Of course', 'Sorry, no', 'He does not know'], 'Of course'),
                Kit::question('Where do they meet?', ['At the cinema', 'At the bar', 'At the party'], 'At the cinema'),
            ], [
                Kit::question('Does Luca have any plans?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('How does the conversation end?', ['Luca says goodbye', 'Marta says sorry', 'Luca says no'], 'Luca says goodbye'),
            ], [Kit::word('il programma', 'programma'), Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('certo'), Kit::word('vedersi', 'vediamo'), Kit::word('il cinema', 'cinema')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.mi-dispiace-cena', 'Mi dispiace, Anna. Non posso venire alla cena.', 'I am sorry, Anna. I cannot come to the dinner.', [Kit::word('mi dispiace'), Kit::word('la cena', 'cena'), Kit::form('posso')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.puo-invitare', 'Marta può invitare Luca alla festa.', 'Marta can invite Luca to the party.', [Kit::word('invitare'), Kit::word('la festa', 'festa'), Kit::form('può')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.certo-vediamo', 'Certo, ci vediamo al cinema o al bar?', 'Of course, shall we meet at the cinema or at the bar?', [Kit::word('certo'), Kit::word('vedersi', 'vediamo'), Kit::word('il cinema', 'cinema'), Kit::word('il bar', 'bar')], 'listen', homophoneNote: self::O_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.festa', 'Vuoi venire alla festa?', 'Do you want to come to the party?', [['certo', 'sì', 'no', 'dispiace'], ['vengo', 'posso', 'certo']], 'Mi dispiace, non posso venire.', [Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('la festa', 'festa'), Kit::word('mi dispiace')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.invitare', 'Perché inviti Anna?', 'Why are you inviting Anna?', [["c'è", 'è'], ['festa', 'programma', 'cena', 'cinema', 'bar']], "C'è una festa.", [Kit::word('invitare', 'inviti')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.programma', 'Hai programmi?', 'Do you have any plans?', [['sì', 'no'], ['ho', 'vado', 'andiamo']], 'No, non ho programmi.', [Kit::word('il programma', 'programmi')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.vediamo', 'Dove ci vediamo, al cinema o al bar?', 'Where shall we meet, at the cinema or at the bar?', [['vediamo', 'al'], ['cinema', 'bar']], 'Ci vediamo al bar.', [Kit::word('vedersi', 'vediamo'), Kit::word('il cinema', 'cinema'), Kit::word('il bar', 'bar')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.mi-dispiace-cena', 'Mi dispiace, non posso venire alla cena.', 'I am sorry, I cannot come to the dinner.', [Kit::word('mi dispiace'), Kit::word('la cena', 'cena'), Kit::form('posso')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.certo-cinema', 'Certo, ci vediamo al cinema.', 'Of course, we will meet at the cinema.', [Kit::word('certo'), Kit::word('vedersi', 'vediamo'), Kit::word('il cinema', 'cinema')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.vuoi-bar', 'Do you want to come to the bar with Anna? (informal you)', ['Vuoi venire al bar con Anna?', 'Tu vuoi venire al bar con Anna?'], [Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('il bar', 'bar')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.possiamo-cena', 'Can we invite Marta to the dinner?', ['Possiamo invitare Marta alla cena?', 'Noi possiamo invitare Marta alla cena?'], [Kit::word('invitare'), Kit::word('la cena', 'cena'), Kit::form('possiamo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.mi-dispiace', 'I am sorry, I cannot come to the party.', ['Mi dispiace, non posso venire alla festa.', 'Mi dispiace, io non posso venire alla festa.'], [Kit::word('mi dispiace'), Kit::word('la festa', 'festa'), Kit::form('posso')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.puoi-cinema', 'Why can you not come to the cinema? (informal you)', ['Perché non puoi venire al cinema?', 'Perché tu non puoi venire al cinema?'], [Kit::word('il cinema', 'cinema'), Kit::form('puoi', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.va-cenare', 'Anna ___ a cenare al bar.', 'Anna is going to have dinner at the bar.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.puo-cena', 'Marta non ___ venire alla cena.', 'Marta cannot come to the dinner.', 'può', Kit::form('può', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.certo-vado', 'Certo, andiamo a cenare con Luca.', 'Of course, we are going to have dinner with Luca.', [Kit::word('certo'), Kit::word('la cena', 'cenare'), Kit::form('andiamo a')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.programma', 'Ho un programma: cenare al bar.', 'I have a plan: to have dinner at the bar.', [Kit::word('il programma', 'programma'), Kit::word('la cena', 'cenare'), Kit::word('il bar', 'bar')], 'dictation', $set, homophoneNote: self::HO_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.dove', 'Ci vediamo al cinema alle due.', 'We meet at the cinema at two.', [Kit::word('vedersi', 'vediamo'), Kit::word('il cinema', 'cinema')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.festa', [
                Kit::line('Paolo', "Ciao, Anna. C'è una festa. Vuoi venire?"),
                Kit::line('Anna', 'Certo. Dove?'),
                Kit::line('Paolo', 'Ci vediamo qui, al bar.'),
            ], [
                Kit::question('What does Paolo ask Anna?', ['If she wants to come to the party', 'If she can have dinner', 'If she has a plan'], 'If she wants to come to the party'),
                Kit::question('What does Anna answer?', ['Of course', 'Sorry, no', 'The conversation does not say.'], 'Of course'),
                Kit::question('Where do they meet?', ['At the bar', 'At the party', 'At the cinema'], 'At the bar'),
            ], [
                Kit::question('Who speaks first?', ['Paolo', 'Anna', 'Nobody'], 'Paolo'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Anna say yes?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('la festa', 'festa'), Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('certo'), Kit::word('vedersi', 'vediamo'), Kit::word('il bar', 'bar')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.programma', 'Read the conversation.', [
                Kit::line('Luca', 'Ciao, Marta. Che programmi hai?'),
                Kit::line('Marta', 'Invito Anna al cinema. Vuoi venire?'),
                Kit::line('Luca', 'Mi dispiace, Marta. Non posso. Vado a cenare con Paolo.'),
                Kit::line('Marta', 'Va bene, Luca. Ciao.'),
            ], [
                Kit::question('Where does Marta invite Anna?', ['To the cinema', 'To a party', 'To a bar'], 'To the cinema'),
                Kit::question('Can Luca come?', ['No', 'Yes', 'The text does not say.'], 'No'),
            ], [Kit::word('il programma', 'programma'), Kit::word('invitare'), Kit::word('il cinema', 'cinema'), Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('mi dispiace'), Kit::word('la cena', 'cenare')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.festa-anna', 'Vuoi venire alla festa di Anna?', "Do you want to come to Anna's party?", [['certo', 'sì', 'no', 'dispiace'], ['vengo', 'posso', 'certo']], 'Certo, vengo.', [Kit::word('certo'), Kit::word('la festa', 'festa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.programma-festa', 'Hai programmi per la festa?', 'Do you have any plans for the party?', [['sì', 'no'], ['ho', 'vado', 'andiamo']], 'Sì, ho programmi.', [Kit::word('il programma', 'programmi'), Kit::word('la festa', 'festa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vediamo-cinema', 'Ci vediamo al cinema, Paolo?', 'Shall we meet at the cinema, Paolo?', [['sì', 'no', 'certo', 'dispiace'], ['vediamo', 'posso', 'bar']], 'Sì, ci vediamo al cinema.', [Kit::word('vedersi', 'vediamo'), Kit::word('il cinema', 'cinema'), Kit::word('certo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.possiamo-festa', 'Can we invite Anna to the party? Of course.', ['Possiamo invitare Anna alla festa? Certo.', 'Noi possiamo invitare Anna alla festa? Certo.'], [Kit::word('invitare'), Kit::word('la festa', 'festa'), Kit::word('certo'), Kit::form('possiamo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.puoi-bar', 'Why can you not come to the bar? Do you have any plans? (informal you)', ['Perché non puoi venire al bar? Hai programmi?', 'Perché tu non puoi venire al bar? Hai programmi?'], [Kit::word('il bar', 'bar'), Kit::word('il programma', 'programmi'), Kit::form('puoi', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vado-cenare', 'I am sorry, I am going to have dinner with Luca.', ['Mi dispiace, vado a cenare con Luca.', 'Mi dispiace, io vado a cenare con Luca.'], [Kit::word('mi dispiace'), Kit::word('la cena', 'cenare'), Kit::form('vado a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.programma-cinema', 'Do you have any plans? Do you want to come to the cinema? (informal you)', ['Hai programmi? Vuoi venire al cinema?'], [Kit::word('il programma', 'programmi'), Kit::word('vuoi venire?', 'vuoi venire'), Kit::word('il cinema', 'cinema')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.va-cenare', 'Luca non ___ a cenare con Marta.', 'Luca is not going to have dinner with Marta.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vediamo', 'Ci ___ al bar alle due.', 'We see each other at the bar at two.', 'vediamo', Kit::word('vedersi', 'vediamo'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.andiamo-cenare', 'Andiamo a cenare al bar. Vuoi venire?', 'We are going to have dinner at the bar. Do you want to come?', [Kit::word('la cena', 'cenare'), Kit::word('il bar', 'bar'), Kit::word('vuoi venire?', 'vuoi venire'), Kit::form('andiamo a')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.puo-invitare', 'Certo, Marta può invitare Paolo al cinema.', 'Of course, Marta can invite Paolo to the cinema.', [Kit::word('certo'), Kit::word('invitare'), Kit::word('il cinema', 'cinema'), Kit::form('può')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.mi-dispiace', 'Mi dispiace, Anna non può venire. Ci vediamo alla festa.', 'I am sorry, Anna cannot come. We will meet at the party.', [Kit::word('mi dispiace'), Kit::word('vedersi', 'vediamo'), Kit::word('la festa', 'festa')], 'dictation', $set),
        ];
    }
}
