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

final class MakingPlans implements UnitContent
{
    private const A_NOTE = 'A (has, the verb) and à (to, at) sound the same. Here it is a without an accent, as in il y a (there is).';

    private const PEUX_NOTE = 'Peux (tu, je) and peut (il, elle) sound the same. Here the subject tells you which one.';

    private const PREP_NOTE = 'À (to, at) and a (has, the verb) sound the same. Here it is à with an accent, as in à la fête.';

    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const OU_NOTE = 'Ou (or) and où (where) sound the same. Here it is ou without an accent, because it means or.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'making-plans';
    }

    public function words(): array
    {
        return [
            new WordData('la fête', cue: 'party'),
            new WordData('le cinéma', cue: 'cinema'),
            new WordData('le dîner', cue: 'dinner (the evening meal; dîner is also the verb, to have dinner)', forms: ['dîner']),
            new WordData('le café', cue: 'café (the place where you meet)'),
            new WordData('le rendez-vous', cue: 'meeting, appointment (an arranged time and place to meet)'),
            new WordData('inviter', cue: 'to invite'),
            new WordData('se retrouver', cue: 'to meet up (to arrange to meet)', forms: ['retrouve', 'retrouvons']),
            new WordData('tu veux venir ?', cue: 'do you want to come? (informal you)', forms: ['veux', 'venir']),
            new WordData('désolé', cue: 'sorry (a woman says désolée)', accepted: ['désolée']),
            new WordData('bien sûr', cue: 'of course', accepted: ['bien sûr que oui', 'évidemment']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Nous allons inviter Anne.', 'english' => 'We are going to invite Anne.'],
            ['text' => 'Tu peux venir ? Je ne peux pas.', 'english' => 'Can you come? I cannot.'],
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
            Kit::gap($stage, 'sentences.choose_gap.vais-inviter', 'Je ___ inviter Anne.', ['vais', 'vas', 'va'], 'vais', Kit::form('vais', true), 'Je goes with vais. Vas is for tu and va is for il or elle.', 'choose', glosses: ['vais' => 'I go', 'vas' => 'you go', 'va' => 'he or she goes']),
            Kit::gap($stage, 'sentences.choose_gap.desole', 'Tu peux venir ? Non, je suis ___.', ['désolé', 'la fête', 'le café'], 'désolé', Kit::word('désolé'), 'Désolé says you are sorry. La fête and le café are things, so they do not fit here.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.cinema', 'On va au ___ avec Marie.', ['cinéma', 'fête'], 'cinéma', Kit::word('le cinéma', 'cinéma'), 'Au is à + le, so it needs a masculine word: le cinéma. Fête is feminine, so it would need à la.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.bien-sur', 'Tu veux venir ? ___, je vais venir.', ['Bien sûr', 'Le dîner', 'Le café'], 'Bien sûr', Kit::word('bien sûr'), 'Bien sûr means of course, so it fits a yes. Le dîner and le café are things, not answers.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.inviter', 'Je vais ___ Paul à la fête.', ['inviter', 'dîner', 'venir'], 'inviter', Kit::word('inviter'), 'You invite a person: inviter Paul. Dîner (to have dinner) and venir (to come) cannot be followed straight by a person.', 'choose', glosses: ['dîner' => 'to have dinner', 'venir' => 'to come']),
            Kit::gap($stage, 'sentences.choose_gap.peux', 'Paul, tu ___ venir au dîner ?', ['peux', 'peut', 'pouvons'], 'peux', Kit::form('peux', true), 'Tu goes with peux. Peut is for il or elle and pouvons is for nous.', 'choose', glosses: ['peux' => 'you can', 'peut' => 'he or she can', 'pouvons' => 'we can']),

            Kit::typeGap($stage, 'sentences.type_gap.allons-diner', 'Nous ___ dîner au café.', 'We are going to have dinner at the café.', 'allons', Kit::form('allons'), 'Nous goes with allons. The infinitive follows straight away: allons dîner.', glosses: ['dîner' => 'to have dinner']),
            Kit::typeGap($stage, 'sentences.type_gap.vais-inviter', 'Je ___ inviter Marie.', 'I am going to invite Marie.', 'vais', Kit::form('vais', true), 'Je goes with vais. The infinitive follows straight away: vais inviter.'),
            Kit::typeGap($stage, 'sentences.type_gap.peux-cinema', 'Paul, tu ___ venir au cinéma ?', 'Paul, can you come to the cinema? (informal you)', 'peux', Kit::form('peux'), 'Paul is the person you ask, so use tu peux. The verb after it stays venir.', glosses: ['venir' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.peut-fete', 'Anne ne ___ pas venir à la fête.', 'Anne cannot come to the party.', 'peut', Kit::form('peut'), 'Anne is one person (elle), so use peut. Ne goes before the verb and pas after it.', glosses: ['venir' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.on-se-retrouve', 'On se ___ où ?', 'Where shall we meet?', 'retrouve', Kit::word('se retrouver', 'retrouve'), null, glosses: ['retrouve' => 'we meet up']),

            Kit::translate($stage, 'sentences.translate.allons-inviter', 'We are going to invite Paul.', ['Nous allons inviter Paul.', 'On va inviter Paul.'], [Kit::word('inviter'), Kit::form('allons', false, ['va'])], glosses: ['inviter' => 'to invite']),
            Kit::translate($stage, 'sentences.translate.peux-fete', 'Can you come to the party? (informal you)', ['Tu peux venir à la fête ?', 'Peux-tu venir à la fête ?'], [Kit::word('la fête', 'fête'), Kit::form('peux')], glosses: ['venir' => 'to come']),
            Kit::translate($stage, 'sentences.translate.vais-diner', 'I am going to have dinner with Luc.', ['Je vais dîner avec Luc.'], [Kit::word('le dîner', 'dîner'), Kit::form('vais')], glosses: ['dîner' => 'to have dinner']),

            Kit::build($stage, 'sentences.build.vais-inviter', 'I am going to invite Anne.', 'Je vais inviter Anne.', ['allons'], [Kit::word('inviter'), Kit::form('vais')], glosses: ['inviter' => 'to invite']),
            Kit::build($stage, 'sentences.build.peux-cafe', 'Can you come to the café? (informal you)', 'Tu peux venir au café ?', ['peut'], [Kit::word('le café', 'café'), Kit::form('peux')], glosses: ['venir' => 'to come']),
            Kit::build($stage, 'sentences.build.retrouve-cinema', 'We are meeting at the cinema.', 'On se retrouve au cinéma.', ['retrouver'], [Kit::word('se retrouver', 'retrouve'), Kit::word('le cinéma', 'cinéma')], glosses: ['retrouver' => 'to meet up', 'retrouve' => 'we meet up']),

            Kit::listenChoose($stage, 'sentences.listen_choose.rendez-vous-venir', 'Tu as un rendez-vous ? Tu veux venir ?', ['Do you have an appointment? Do you want to come?', 'Do you have a party? Do you want to come?', 'Do you have an appointment? Can you come?', 'Do I have an appointment? Do you want to come?'], 'Do you have an appointment? Do you want to come?', [Kit::word('le rendez-vous', 'rendez-vous'), Kit::word('tu veux venir ?', 'tu veux venir')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.desole', 'Désolé, je ne peux pas venir.', ['I am sorry, I cannot come.', 'Of course, I can come.', 'I am sorry, we cannot come.', 'I am sorry, you cannot come.'], 'I am sorry, I cannot come.', [Kit::word('désolé'), Kit::form('peux')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.allons-diner', 'Nous allons dîner au café.', ['We are going to have dinner at the café.', 'I am going to have dinner at the café.', 'We are going to have dinner at the cinema.', 'They are going to have dinner at the café.'], 'We are going to have dinner at the café.', [Kit::word('le dîner', 'dîner'), Kit::word('le café', 'café'), Kit::form('allons')]),
            Kit::listenType($stage, 'sentences.listen_type.bien-sur-vais', 'Bien sûr, je vais venir.', 'Of course, I am going to come.', [Kit::word('bien sûr'), Kit::form('vais')]),
            Kit::listenType($stage, 'sentences.listen_type.rendez-vous', 'Le rendez-vous est au cinéma.', 'The meeting is at the cinema.', [Kit::word('le rendez-vous', 'rendez-vous'), Kit::word('le cinéma', 'cinéma')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.fete', 'Il y a une fête au café. Tu veux venir ?', 'There is a party at the café. Do you want to come?', [Kit::word('la fête', 'fête'), Kit::word('tu veux venir ?', 'tu veux venir')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.peux-diner', 'Anne, pourquoi tu ne peux pas dîner ?', 'Anne, why can you not have dinner?', [Kit::form('peux'), Kit::word('le dîner', 'dîner')], homophoneNote: self::PEUX_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.veux-cinema', 'Tu veux venir au cinéma ?', 'Do you want to come to the cinema?', [Kit::word('tu veux venir ?', 'tu veux venir'), Kit::word('le cinéma', 'cinéma')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pourquoi', 'Pourquoi tu ne peux pas venir ?', 'Why can you not come? (informal you)', [Kit::form('peux')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.allons-inviter', 'Nous allons inviter Paul.', 'We are going to invite Paul.', [Kit::word('inviter'), Kit::form('allons')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.on-se-retrouve', 'On se retrouve où ? Au café ?', 'Where shall we meet? At the café?', [Kit::word('se retrouver', 'se retrouve'), Kit::word('le café', 'café')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cinema', 'Tu veux venir au cinéma ?', 'Do you want to come to the cinema?', [['bien', 'oui', 'non', 'désolé', 'désolée'], ['vais', 'peux']], 'Bien sûr, je vais venir.', [Kit::word('bien sûr'), Kit::word('le cinéma', 'cinéma'), Kit::form('vais')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.diner', 'Tu peux venir au dîner ?', 'Can you come to the dinner? (informal you)', [['oui', 'non', 'bien', 'désolé', 'désolée'], ['peux', 'vais']], 'Oui, je peux venir.', [Kit::word('le dîner', 'dîner'), Kit::form('peux')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ou', 'On se retrouve où ?', 'Where shall we meet?', [['retrouve', 'retrouvons', 'au'], ['cinéma', 'café']], 'On se retrouve au cinéma.', [Kit::word('se retrouver', 'se retrouve'), Kit::word('le cinéma', 'cinéma')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fete', 'Read the conversation.', [
                Kit::line('Anne', 'Bonjour, Paul. Il y a une fête au café. Tu veux venir ?'),
                Kit::line('Paul', 'Je suis désolé, Anne. Je ne peux pas venir.'),
                Kit::line('Paul', 'Je vais dîner avec Marie.'),
                Kit::line('Anne', 'Très bien, tu as un rendez-vous. Au revoir !'),
            ], [
                Kit::question('Where is the party?', ['In the café', 'In the cinema', 'In a restaurant'], 'In the café'),
                Kit::question('Can Paul come?', ['No', 'Yes', 'The text does not say.'], 'No'),
                Kit::question('What is Paul going to do?', ['Have dinner with Marie', 'Invite Anne', 'Go to the cinema'], 'Have dinner with Marie'),
            ], [Kit::word('la fête', 'fête'), Kit::word('le café', 'café'), Kit::word('tu veux venir ?', 'tu veux venir'), Kit::word('désolé'), Kit::word('le dîner', 'dîner')], 'read'),
            Kit::gap($stage, 'task.choose_gap.va-inviter', 'Anne ___ inviter Paul à la fête.', ['va', 'vais', 'allons'], 'va', Kit::form('va', true), 'Anne is one person, so use va. Vais is for je and allons is for nous. The infinitive follows straight away: va inviter.', 'read'),
            Kit::gap($stage, 'task.choose_gap.rendez-vous', 'Tu as un ___ au café ?', ['rendez-vous', 'fête'], 'rendez-vous', Kit::word('le rendez-vous', 'rendez-vous'), 'Un goes with a masculine word. Fête is feminine, so it would need une.', 'read'),

            Kit::transform($stage, 'task.transform.nous-diner', 'Change it to nous.', 'Je vais dîner au café.', ['Nous allons dîner au café.', 'On va dîner au café.'], [Kit::word('le dîner', 'dîner'), Kit::form('allons', false, ['va'])]),
            Kit::transform($stage, 'task.transform.anne-peut', 'Say it about Anne.', 'Je peux venir à la fête.', ['Anne peut venir à la fête.'], [Kit::word('la fête', 'fête'), Kit::form('peut')]),
            Kit::transform($stage, 'task.transform.tu-vas', 'Change it to tu.', 'Je vais inviter Marie.', ['Tu vas inviter Marie.'], [Kit::word('inviter'), Kit::form('vas')]),
            Kit::writeGuided($stage, 'task.write_guided.inviter', 'Say that you are going to invite Paul to the party.', ['je vais', 'inviter', 'fête'], 'Je vais inviter Paul à la fête.', [
                ['forms' => ['vais'], 'term' => null],
                ['forms' => ['inviter'], 'term' => 'inviter'],
                ['forms' => ['fête'], 'term' => 'la fête'],
            ], [Kit::word('inviter'), Kit::word('la fête', 'fête')]),
            Kit::writeGuided($stage, 'task.write_guided.desole', 'Say that you are sorry and that you cannot come to the cinema.', ['désolé', 'je ne peux pas', 'venir', 'cinéma'], 'Je suis désolé, je ne peux pas venir au cinéma.', [
                ['forms' => ['désolé', 'désolée'], 'term' => 'désolé'],
                ['forms' => ['peux'], 'term' => null],
                ['forms' => ['cinéma'], 'term' => 'le cinéma'],
            ], [Kit::word('désolé'), Kit::word('le cinéma', 'cinéma')], ['venir' => 'to come']),
            Kit::build($stage, 'task.build.allons-diner', 'We are going to have dinner at the café.', 'Nous allons dîner au café.', ['vais', 'avec'], [Kit::word('le dîner', 'dîner'), Kit::word('le café', 'café'), Kit::form('allons')], 'write', ['dîner' => 'to have dinner']),
            Kit::build($stage, 'task.build.peux-inviter', 'Can you invite Marie to the party? (informal you)', 'Tu peux inviter Marie à la fête ?', ['peut', 'au'], [Kit::word('inviter'), Kit::word('la fête', 'fête'), Kit::form('peux')], 'write', ['inviter' => 'to invite']),
            Kit::build($stage, 'task.build.desole-diner', 'I am sorry, I cannot come to the dinner.', 'Je suis désolé, je ne peux pas venir au dîner.', ['peut', 'bien'], [Kit::word('désolé'), Kit::word('le dîner', 'dîner'), Kit::form('peux')], 'write', ['venir' => 'to come']),
            Kit::translate($stage, 'task.translate.vas-cinema', 'Are you going to come to the cinema? (informal you)', ['Tu vas venir au cinéma ?', 'Vas-tu venir au cinéma ?'], [Kit::word('le cinéma', 'cinéma'), Kit::form('vas')], 'write', glosses: ['venir' => 'to come']),
            Kit::translate($stage, 'task.translate.bien-sur-retrouver', 'Of course, we are going to meet at the café.', ['Bien sûr, nous allons nous retrouver au café.', 'Bien sûr, on va se retrouver au café.'], [Kit::word('bien sûr'), Kit::word('se retrouver', null, ['nous retrouver']), Kit::form('allons', false, ['va'])], 'write', glosses: ['retrouver' => 'to meet up']),

            Kit::listenPassage($stage, 'task.listen_passage.cinema', [
                Kit::line('Marie', 'Bonjour, Luc. Tu as un rendez-vous ?'),
                Kit::line('Luc', 'Non, pourquoi ?'),
                Kit::line('Marie', 'Nous allons au cinéma avec Anne. Tu veux venir ?'),
                Kit::line('Luc', 'Bien sûr. On se retrouve où ?'),
                Kit::line('Marie', 'On se retrouve au cinéma.'),
                Kit::line('Luc', 'Très bien. Au revoir.'),
            ], [
                Kit::question('What are Marie and Anne going to do?', ['Go to the cinema', 'Go to a party', 'Have dinner'], 'Go to the cinema'),
                Kit::question('What does Luc answer to the invitation?', ['Of course', 'Sorry, no', 'He does not know'], 'Of course'),
                Kit::question('Where do they meet?', ['At the cinema', 'At the café', 'At the party'], 'At the cinema'),
            ], [
                Kit::question('Does Luc have an appointment?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('How does the conversation end?', ['Luc says goodbye', 'Marie says sorry', 'Luc says no'], 'Luc says goodbye'),
            ], [Kit::word('le rendez-vous', 'rendez-vous'), Kit::word('tu veux venir ?', 'tu veux venir'), Kit::word('bien sûr'), Kit::word('se retrouver', 'se retrouve'), Kit::word('le cinéma', 'cinéma')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.desole-diner', 'Désolé, Anne. Je ne peux pas venir au dîner.', 'Sorry, Anne. I cannot come to the dinner.', [Kit::word('désolé'), Kit::word('le dîner', 'dîner'), Kit::form('peux')], 'listen', homophoneNote: self::PEUX_NOTE),
            Kit::listenType($stage, 'task.listen_type.va-inviter', 'Marie va inviter Luc à la fête.', 'Marie is going to invite Luc to the party.', [Kit::word('inviter'), Kit::word('la fête', 'fête'), Kit::form('va')], 'listen', homophoneNote: self::PREP_NOTE),
            Kit::listenType($stage, 'task.listen_type.bien-sur-retrouve', 'Bien sûr. On se retrouve au cinéma ou au café ?', 'Of course. Shall we meet at the cinema or at the café?', [Kit::word('bien sûr'), Kit::word('se retrouver', 'se retrouve'), Kit::word('le cinéma', 'cinéma'), Kit::word('le café', 'café')], 'listen', homophoneNote: self::OU_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.fete', 'Tu veux venir à la fête ?', 'Do you want to come to the party?', [['bien', 'oui', 'non', 'désolé', 'désolée'], ['vais', 'peux']], 'Désolé, je ne peux pas venir.', [Kit::word('tu veux venir ?', 'tu veux venir'), Kit::word('la fête', 'fête'), Kit::word('désolé')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.inviter', 'Pourquoi tu vas inviter Anne ?', 'Why are you going to invite Anne?', [['a', 'est', 'avons'], ['fête', 'dîner', 'cinéma', 'café']], 'Il y a une fête.', [Kit::word('inviter'), Kit::form('vas')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.rendez-vous', 'Tu as un rendez-vous ?', 'Do you have an appointment?', [['oui', 'non'], ["j'ai", 'ai', 'avons']], "Oui, j'ai un rendez-vous.", [Kit::word('le rendez-vous', 'rendez-vous'), Kit::form("j'ai")], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.retrouve', 'On se retrouve au cinéma ou au café ?', 'Shall we meet at the cinema or at the café?', [['retrouve', 'retrouvons', 'au'], ['cinéma', 'café']], 'On se retrouve au café.', [Kit::word('se retrouver', 'se retrouve'), Kit::word('le cinéma', 'cinéma'), Kit::word('le café', 'café')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.desole-diner', 'Je suis désolé, je ne peux pas venir au dîner.', 'I am sorry, I cannot come to the dinner.', [Kit::word('désolé'), Kit::word('le dîner', 'dîner'), Kit::form('peux')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bien-sur-retrouver', 'Bien sûr, on va se retrouver au cinéma.', 'Of course, we are going to meet at the cinema.', [Kit::word('bien sûr'), Kit::word('se retrouver'), Kit::word('le cinéma', 'cinéma'), Kit::form('va')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.veux-diner', 'Do you want to come to the dinner? (informal you) We meet at the café.', ['Tu veux venir au dîner ? On se retrouve au café.', 'Veux-tu venir au dîner ? On se retrouve au café.', 'Tu veux venir au dîner ? Nous nous retrouvons au café.', 'Veux-tu venir au dîner ? Nous nous retrouvons au café.'], [Kit::word('tu veux venir ?', 'tu veux venir', ['veux-tu venir']), Kit::word('le dîner', 'dîner'), Kit::word('se retrouver', 'se retrouve', ['retrouvons']), Kit::word('le café', 'café')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bien-sur-inviter', 'Of course, I am going to invite Marie to the cinema.', ['Bien sûr, je vais inviter Marie au cinéma.'], [Kit::word('bien sûr'), Kit::word('inviter'), Kit::word('le cinéma', 'cinéma'), Kit::form('vais')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.desole-fete', 'Sorry, I cannot come to the party.', ['Désolé, je ne peux pas venir à la fête.', 'Désolée, je ne peux pas venir à la fête.'], [Kit::word('désolé', null, ['désolée']), Kit::word('la fête', 'fête'), Kit::form('peux', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.veux-cinema', 'Do you want to come to the cinema with Luc? (informal you) The meeting is here.', ['Tu veux venir au cinéma avec Luc ? Le rendez-vous est ici.', 'Veux-tu venir au cinéma avec Luc ? Le rendez-vous est ici.'], [Kit::word('tu veux venir ?', 'tu veux venir', ['veux-tu venir']), Kit::word('le cinéma', 'cinéma'), Kit::word('le rendez-vous', 'rendez-vous')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.va-diner', 'Anne ___ dîner au café.', 'Anne is going to have dinner at the café.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.peut-diner', 'Marie ne ___ pas venir au dîner.', 'Marie cannot come to the dinner.', 'peut', Kit::form('peut', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.allons-fete', 'Nous allons inviter Anne à la fête.', 'We are going to invite Anne to the party.', [Kit::word('inviter'), Kit::word('la fête', 'fête'), Kit::form('allons')], 'dictation', $set, homophoneNote: self::PREP_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.bien-sur-rendez-vous', 'Bien sûr, le rendez-vous est au café.', 'Of course, the meeting is at the café.', [Kit::word('bien sûr'), Kit::word('le rendez-vous', 'rendez-vous'), Kit::word('le café', 'café')], 'dictation', $set, homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.desole-anne', 'Désolé, Anne ne peut pas dîner. On se retrouve ici ?', 'Sorry, Anne cannot have dinner. Shall we meet here?', [Kit::word('désolé'), Kit::word('le dîner', 'dîner'), Kit::word('se retrouver', 'se retrouve'), Kit::form('peut')], 'dictation', $set, homophoneNote: self::PEUX_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.fete', [
                Kit::line('Paul', 'Bonjour, Anne. Il y a une fête. Tu veux venir avec nous ?'),
                Kit::line('Anne', 'Bien sûr. Nous nous retrouvons où ?'),
                Kit::line('Paul', 'On se retrouve ici, au café.'),
            ], [
                Kit::question('What does Paul ask Anne?', ['If she wants to come to the party', 'If she can have dinner', 'If she has a meeting'], 'If she wants to come to the party'),
                Kit::question('What does Anne answer?', ['Of course', 'Sorry, no', 'The conversation does not say.'], 'Of course'),
                Kit::question('Where do they meet?', ['At the café', 'At the party', 'At the cinema'], 'At the café'),
            ], [
                Kit::question('Who speaks first?', ['Paul', 'Anne', 'Nobody'], 'Paul'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Anne say yes?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('la fête', 'fête'), Kit::word('tu veux venir ?', 'tu veux venir'), Kit::word('bien sûr'), Kit::word('se retrouver', 'se retrouve'), Kit::word('le café', 'café')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.rendez-vous', 'Read the conversation.', [
                Kit::line('Luc', 'Bonjour, Marie. Tu as un rendez-vous avec Anne ?'),
                Kit::line('Marie', 'Non. Je vais inviter Anne au cinéma. Tu veux venir avec nous ?'),
                Kit::line('Luc', 'Je suis désolé, Marie. Je ne peux pas. Je vais dîner avec Paul.'),
                Kit::line('Marie', 'Très bien, Luc. Au revoir !'),
            ], [
                Kit::question('Where is Marie going to invite Anne?', ['To the cinema', 'To a party', 'To a café'], 'To the cinema'),
                Kit::question('Can Luc come?', ['No', 'Yes', 'The text does not say.'], 'No'),
            ], [Kit::word('le rendez-vous', 'rendez-vous'), Kit::word('inviter'), Kit::word('le cinéma', 'cinéma'), Kit::word('tu veux venir ?', 'tu veux venir'), Kit::word('désolé'), Kit::word('le dîner', 'dîner')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fete-anne', 'Tu veux venir à la fête avec Anne ?', 'Do you want to come to the party with Anne?', [['bien', 'oui', 'non', 'désolé', 'désolée'], ['vais', 'peux']], 'Bien sûr, je vais venir.', [Kit::word('bien sûr'), Kit::word('la fête', 'fête')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.rendez-vous-fete', 'Tu as un rendez-vous au cinéma ?', 'Do you have an appointment at the cinema?', [['oui', 'non'], ["j'ai", 'ai', 'vais']], "Oui, j'ai un rendez-vous.", [Kit::word('le rendez-vous', 'rendez-vous')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.retrouve-cinema', 'On se retrouve au cinéma, Paul ?', 'Shall we meet at the cinema, Paul?', [['oui', 'non', 'bien', 'désolé', 'désolée'], ['retrouve', 'retrouvons', 'peux', 'café']], 'Bien sûr, on se retrouve au cinéma.', [Kit::word('se retrouver', 'se retrouve'), Kit::word('le cinéma', 'cinéma'), Kit::word('bien sûr')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.veux-fete', 'Do you want to come to the party with us? (informal you) We meet at the cinema.', ['Tu veux venir à la fête avec nous ? On se retrouve au cinéma.', 'Veux-tu venir à la fête avec nous ? On se retrouve au cinéma.', 'Tu veux venir à la fête avec nous ? Nous nous retrouvons au cinéma.', 'Veux-tu venir à la fête avec nous ? Nous nous retrouvons au cinéma.'], [Kit::word('tu veux venir ?', 'tu veux venir', ['veux-tu venir']), Kit::word('la fête', 'fête'), Kit::word('se retrouver', 'se retrouve', ['retrouvons']), Kit::word('le cinéma', 'cinéma')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.allons-diner', 'Of course, we are going to invite Paul to the dinner.', ['Bien sûr, nous allons inviter Paul au dîner.', 'Bien sûr, on va inviter Paul au dîner.'], [Kit::word('bien sûr'), Kit::word('inviter'), Kit::word('le dîner', 'dîner'), Kit::form('allons', false, ['va'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.desole-rendez-vous', 'Sorry, Anne cannot come to the meeting.', ['Désolé, Anne ne peut pas venir au rendez-vous.', 'Désolée, Anne ne peut pas venir au rendez-vous.'], [Kit::word('désolé', null, ['désolée']), Kit::word('le rendez-vous', 'rendez-vous'), Kit::form('peut')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.peut-cafe', 'Why can we not meet up at the café?', ['Pourquoi on ne peut pas se retrouver au café ?', 'Pourquoi ne peut-on pas se retrouver au café ?', 'Pourquoi nous ne pouvons pas nous retrouver au café ?', 'Pourquoi ne pouvons-nous pas nous retrouver au café ?'], [Kit::word('se retrouver', null, ['nous retrouver']), Kit::word('le café', 'café'), Kit::form('peut', true, ['pouvons'])], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.va-inviter', 'Marie ___ inviter Paul.', 'Marie is going to invite Paul.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.peux-venir', 'Tu ___ venir avec nous ?', 'Can you come with us? (informal you)', 'peux', Kit::form('peux'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.diner-cafe', 'Le dîner est au café. Tu veux venir aussi ?', 'The dinner is at the café. Do you want to come too?', [Kit::word('le dîner', 'dîner'), Kit::word('le café', 'café'), Kit::word('tu veux venir ?', 'tu veux venir')], 'dictation', $set, homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.pouvons-fete', 'Bien sûr, nous pouvons inviter Marie à la fête.', 'Of course, we can invite Marie to the party.', [Kit::word('bien sûr'), Kit::word('inviter'), Kit::word('la fête', 'fête'), Kit::form('pouvons')], 'dictation', $set, homophoneNote: self::PREP_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.desole-cinema', 'Désolé, le rendez-vous est au cinéma.', 'Sorry, the meeting is at the cinema.', [Kit::word('désolé'), Kit::word('le rendez-vous', 'rendez-vous'), Kit::word('le cinéma', 'cinéma')], 'dictation', $set, homophoneNote: self::EST_NOTE),
        ];
    }
}
