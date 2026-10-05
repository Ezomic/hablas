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

final class DescribingYourDailyRoutine implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'describing-your-daily-routine';
    }

    public function words(): array
    {
        return [
            new WordData('se lever', cue: 'to get up (out of bed)', forms: ['je me lève']),
            new WordData('se réveiller', cue: 'to wake up', forms: ['je me réveille']),
            new WordData('se doucher', cue: 'to shower', accepted: ['prendre une douche'], forms: ['je me douche']),
            new WordData('prendre le petit-déjeuner', cue: 'to have breakfast', forms: ['je prends le petit-déjeuner']),
            new WordData('travailler', cue: 'to work', forms: ['je travaille']),
            new WordData('se coucher', cue: 'to go to bed', forms: ['je me couche']),
            new WordData('tôt', cue: 'early'),
            new WordData('tard', cue: 'late (not early)'),
            new WordData('tous les jours', cue: 'every day', accepted: ['chaque jour']),
            new WordData('normalement', cue: 'normally', accepted: ['d\'habitude']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je me lève tôt.', 'english' => 'I get up early.'],
            ['text' => 'Elle se couche tard.', 'english' => 'She goes to bed late.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-05', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-05', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-05', 'Released on the owner\'s instruction on 2026-10-05, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.je-leve', 'Je ___ tôt.', ['me lève', 'se lève', 'me lèves'], 'me lève', Kit::form('me lève'), 'Je goes with me: the pronoun matches the subject.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.elle-couche', 'Elle ___ tard.', ['se couche', 'se couches', 'se couchent'], 'se couche', Kit::form('se couche'), 'Elle goes with se: the pronoun matches the subject.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tu-leves', 'Tu ___ à sept heures.', ['te lèves', 'te lève', 'se lèves'], 'te lèves', Kit::form('te lèves'), 'Tu goes with te: the pronoun matches the subject.', 'choose', null, ['heures' => 'o\'clock, hours']),
            Kit::gap($stage, 'sentences.choose_gap.nous-reveillons', 'Nous ___ tôt.', ['nous réveillons', 'se réveillons', 'réveillons'], 'nous réveillons', Kit::form('nous réveillons', true), 'Nous needs the pronoun nous twice: nous nous réveillons. The verb alone is not enough.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.je-prends', 'Je ___ le petit-déjeuner à sept heures.', ['prends', 'me prends', 'prend'], 'prends', Kit::form('prends', true), 'Prendre le petit-déjeuner is not reflexive, so there is no me. Déjeuner is lunch, not breakfast.', 'choose', null, ['heures' => 'o\'clock, hours']),
            Kit::gap($stage, 'sentences.choose_gap.tu-travailles', 'Tu ___ tous les jours.', ['travailles', 'te travailles'], 'travailles', Kit::form('travailles', true), 'Travailler is not reflexive, so the verb takes no pronoun.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.anne-leve', 'Anne ___ tôt.', 'Anne gets up early.', 'se lève', Kit::form('se lève'), 'Anne is she, and she goes with se: se lève.'),
            Kit::typeGap($stage, 'sentences.type_gap.je-reveille', 'Je ___ à sept heures.', 'I wake up at seven.', 'me réveille', Kit::form('me réveille'), 'Je goes with me: me réveille.', glosses: ['heures' => 'o\'clock, hours']),
            Kit::typeGap($stage, 'sentences.type_gap.marie-douche', 'Marie ___ tous les jours.', 'Marie showers every day.', 'se douche', Kit::form('se douche'), 'Marie is she, and she goes with se: se douche.'),
            Kit::typeGap($stage, 'sentences.type_gap.nous-couchons', 'Nous ___ tard.', 'We go to bed late.', 'nous couchons', Kit::form('nous couchons'), 'Nous needs the pronoun nous twice: nous nous couchons.'),
            Kit::typeGap($stage, 'sentences.type_gap.paul-travaille', 'Paul ___ à neuf heures.', 'Paul works at nine.', 'travaille', Kit::form('travaille', true), 'Travailler is not reflexive, so the verb takes no pronoun.', glosses: ['heures' => 'o\'clock, hours']),

            Kit::translate($stage, 'sentences.translate.normalement-leve', 'Normally I get up early.', ['Normalement, je me lève tôt.', 'Je me lève tôt normalement.', 'Je me lève normalement tôt.', "D'habitude, je me lève tôt.", "Je me lève tôt d'habitude.", "Je me lève d'habitude tôt."], [Kit::word('normalement', null, ["d'habitude"]), Kit::word('se lever', 'me lève'), Kit::word('tôt'), Kit::form('me lève')]),
            Kit::translate($stage, 'sentences.translate.elle-couche', 'She goes to bed late.', ['Elle se couche tard.'], [Kit::word('se coucher', 'se couche'), Kit::word('tard'), Kit::form('se couche')]),
            Kit::translate($stage, 'sentences.translate.travaille', 'I work every day.', ['Je travaille tous les jours.', 'Tous les jours, je travaille.', 'Je travaille chaque jour.', 'Chaque jour, je travaille.'], [Kit::word('travailler', 'travaille'), Kit::word('tous les jours', null, ['chaque jour']), Kit::form('travaille', true)]),

            Kit::build($stage, 'sentences.build.me-reveille', 'I wake up at seven.', 'Je me réveille à sept heures.', ['se'], [Kit::word('se réveiller', 'me réveille'), Kit::form('me réveille')], 'write', ['heures' => 'o\'clock, hours']),
            Kit::build($stage, 'sentences.build.se-douche', 'She showers every day.', 'Elle se douche tous les jours.', ['je'], [Kit::word('se doucher', 'se douche'), Kit::word('tous les jours'), Kit::form('se douche')]),
            Kit::build($stage, 'sentences.build.nous-levons', 'We get up early.', 'Nous nous levons tôt.', ['je'], [Kit::word('se lever', 'nous nous levons'), Kit::word('tôt'), Kit::form('nous nous levons')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.petit-dejeuner', 'Elle prend le petit-déjeuner tôt.', ['She has breakfast early.', 'She has lunch early.', 'She has breakfast late.', 'She gets up early.'], 'She has breakfast early.', [Kit::word('prendre le petit-déjeuner', 'prend le petit-déjeuner'), Kit::word('tôt')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.reveille', 'Il se réveille tard.', ['He wakes up late.', 'He wakes up early.', 'He goes to bed late.', 'I wake up late.'], 'He wakes up late.', [Kit::word('se réveiller', 'se réveille'), Kit::word('tard'), Kit::form('se réveille')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.douche', 'Normalement, je me douche tôt.', ['Normally I shower early.', 'Normally I get up early.', 'Normally I shower late.', 'Normally I work early.'], 'Normally I shower early.', [Kit::word('normalement'), Kit::word('se doucher', 'me douche'), Kit::word('tôt'), Kit::form('me douche')]),
            Kit::listenType($stage, 'sentences.listen_type.anne-leve', 'Anne se lève tôt tous les jours.', 'Anne gets up early every day.', [Kit::word('se lever', 'se lève'), Kit::word('tôt'), Kit::word('tous les jours', null, ['chaque jour']), Kit::form('se lève')], alsoAccepted: ['Anne se lève tôt chaque jour.']),
            Kit::listenType($stage, 'sentences.listen_type.paul-couche', 'Paul se couche tard, Anne aussi.', 'Paul goes to bed late, Anne too.', [Kit::word('se coucher', 'se couche'), Kit::word('tard'), Kit::form('se couche')]),
            Kit::listenType($stage, 'sentences.listen_type.prends', 'Normalement, je prends le petit-déjeuner.', 'Normally I have breakfast.', [Kit::word('normalement'), Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::form('prends', true)]),
            Kit::listenType($stage, 'sentences.listen_type.nous-reveillons', 'Nous nous réveillons très tôt.', 'We wake up very early.', [Kit::word('se réveiller', 'nous nous réveillons'), Kit::word('tôt'), Kit::form('nous nous réveillons')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.leve-tous', 'Je me lève tôt tous les jours.', 'I get up early every day.', [Kit::word('se lever', 'me lève'), Kit::word('tôt'), Kit::word('tous les jours'), Kit::form('me lève')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.petit-dejeuner', 'Je prends le petit-déjeuner tard.', 'I have breakfast late.', [Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::word('tard')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.travaille-tard', 'Je travaille tard tous les jours.', 'I work late every day.', [Kit::word('travailler', 'travaille'), Kit::word('tard'), Kit::word('tous les jours')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.couche', 'Je me couche tard.', 'I go to bed late.', [Kit::word('se coucher', 'me couche'), Kit::word('tard'), Kit::form('me couche')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.leves', 'Tu te lèves tôt ?', 'Do you get up early?', [['lève'], ['tôt', 'tard']], 'Oui, je me lève tôt.', [Kit::word('se lever', 'me lève'), Kit::word('tôt'), Kit::form('me lève')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.prends', 'Tu prends le petit-déjeuner tôt ?', 'Do you have breakfast early?', [['prends'], ['tôt', 'tard']], 'Oui, je prends le petit-déjeuner tôt.', [Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::word('tôt')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.couches', 'Normalement, tu te couches tôt ?', 'Do you normally go to bed early?', [['couche'], ['tôt', 'tard']], 'Non, je me couche tard.', [Kit::word('normalement'), Kit::word('se coucher', 'me couche'), Kit::word('tard')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.routine', 'Read the conversation about daily routines.', [
                Kit::line('Anne', 'Tu te lèves tôt, Paul ?'),
                Kit::line('Paul', 'Oui, normalement je me lève à six heures. Je me douche et je prends le petit-déjeuner.'),
                Kit::line('Anne', 'À six heures ? Moi, je me réveille à sept heures.'),
                Kit::line('Paul', 'Je travaille à huit heures. Je me couche tôt tous les jours.'),
                Kit::line('Anne', 'Moi, je me couche tard.'),
            ], [
                Kit::question('What time does Paul get up?', ['At five', 'At six', 'At seven'], 'At six'),
                Kit::question('At what time does Paul work?', ['At seven', 'At eight', 'At nine'], 'At eight'),
                Kit::question('Who goes to bed late?', ['Paul', 'Anne', 'Both of them'], 'Anne'),
            ], [Kit::word('se lever', 'me lève'), Kit::word('se doucher', 'me douche'), Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::word('se réveiller', 'me réveille'), Kit::word('travailler', 'travaille'), Kit::word('se coucher', 'me couche'), Kit::word('normalement'), Kit::word('tôt'), Kit::word('tard'), Kit::word('tous les jours')], 'read', null, ['heures' => 'o\'clock, hours', 'moi' => 'me (as for me)']),
            Kit::gap($stage, 'task.choose_gap.tot', 'Anne se lève à cinq heures, très ___.', ['tôt', 'tard'], 'tôt', Kit::word('tôt'), 'Getting up at five in the morning is early, so tôt. Late is tard.', 'read', null, ['heures' => 'o\'clock, hours']),
            Kit::gap($stage, 'task.choose_gap.tard', 'Luc se couche à minuit, très ___.', ['tard', 'tôt'], 'tard', Kit::word('tard'), 'Midnight is late, so tard. Early is tôt.', 'read', null, ['minuit' => 'midnight']),

            Kit::transform($stage, 'task.transform.elle', 'Change the subject to she.', 'Je me lève tôt.', ['Elle se lève tôt.'], [Kit::word('se lever', 'se lève'), Kit::word('tôt'), Kit::form('se lève')]),
            Kit::transform($stage, 'task.transform.nous', 'Change the subject to we.', 'Je me réveille à sept heures.', ['Nous nous réveillons à sept heures.'], [Kit::word('se réveiller', 'nous nous réveillons'), Kit::form('nous nous réveillons')], ['heures' => 'o\'clock, hours']),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to you (tu).', 'Je me couche tard.', ['Tu te couches tard.'], [Kit::word('se coucher', 'te couches'), Kit::word('tard'), Kit::form('te couches')]),
            Kit::writeGuided($stage, 'task.write_guided.routine', 'Describe your daily routine. Use the words get up, have breakfast and work.', ['me lève', 'prends le petit-déjeuner', 'travaille'], 'Je me lève tôt, je prends le petit-déjeuner et je travaille.', [
                ['forms' => ['lève', 'lèves', 'levons', 'levez', 'lèvent'], 'term' => 'se lever'],
                ['forms' => ['prends', 'prend', 'prenons', 'prenez', 'prennent'], 'term' => 'prendre le petit-déjeuner'],
                ['forms' => ['travaille', 'travailles', 'travaillons', 'travaillez', 'travaillent'], 'term' => 'travailler'],
            ], [Kit::word('se lever', 'me lève'), Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::word('travailler', 'travaille')], ['levez' => 'vous form of se lever', 'prenez' => 'vous form of prendre']),
            Kit::writeGuided($stage, 'task.write_guided.reveille', 'Say that you wake up early and go to bed late.', ['me réveille', 'tôt', 'me couche', 'tard'], 'Je me réveille tôt et je me couche tard.', [
                ['forms' => ['réveille', 'réveilles', 'réveillons', 'réveillez', 'réveillent'], 'term' => 'se réveiller'],
                ['forms' => ['tôt'], 'term' => 'tôt'],
                ['forms' => ['couche', 'couches', 'couchons', 'couchez', 'couchent'], 'term' => 'se coucher'],
                ['forms' => ['tard'], 'term' => 'tard'],
            ], [Kit::word('se réveiller', 'me réveille'), Kit::word('tôt'), Kit::word('se coucher', 'me couche'), Kit::word('tard')], ['réveilles' => 'tu form of se réveiller', 'couchez' => 'vous form of se coucher']),
            Kit::build($stage, 'task.build.se-levent', 'They get up early.', 'Ils se lèvent tôt.', ['je', 'lève'], [Kit::word('se lever', 'se lèvent'), Kit::word('tôt'), Kit::form('se lèvent')], 'write'),
            Kit::build($stage, 'task.build.travaillons', 'We work every day.', 'Nous travaillons tous les jours.', ['se', 'me'], [Kit::word('travailler', 'travaillons'), Kit::word('tous les jours'), Kit::form('travaillons', true)], 'write'),
            Kit::build($stage, 'task.build.reveille-douche', 'I wake up at seven and I shower.', 'Je me réveille à sept heures et je me douche.', ['se', 'tu'], [Kit::word('se réveiller', 'me réveille'), Kit::word('se doucher', 'me douche'), Kit::form('me douche')], 'write', ['heures' => 'o\'clock, hours']),
            Kit::translate($stage, 'task.translate.leve-petit-dejeuner', 'I get up at six and I have breakfast.', ['Je me lève à six heures et je prends le petit-déjeuner.', 'Je me lève à six heures et prends le petit-déjeuner.', 'Je me lève à six heures et je prends mon petit-déjeuner.', 'Je me lève à six heures et prends mon petit-déjeuner.'], [Kit::word('se lever', 'me lève'), Kit::word('prendre le petit-déjeuner', 'prends'), Kit::form('me lève')], 'write', null, ['heures' => 'o\'clock, hours']),
            Kit::translate($stage, 'task.translate.travaillons', 'We normally work at nine.', ['Normalement, nous travaillons à neuf heures.', 'Nous travaillons normalement à neuf heures.', 'Nous travaillons à neuf heures normalement.', "D'habitude, nous travaillons à neuf heures.", "Nous travaillons d'habitude à neuf heures.", "Nous travaillons à neuf heures d'habitude."], [Kit::word('normalement', null, ["d'habitude"]), Kit::word('travailler', 'travaillons'), Kit::form('travaillons', true)], 'write', null, ['heures' => 'o\'clock, hours']),

            Kit::listenPassage($stage, 'task.listen_passage.routine', [
                Kit::line('Marie', 'Tu te lèves tôt, Luc ?'),
                Kit::line('Luc', 'Non, normalement je me lève tard. Je travaille tard.'),
                Kit::line('Marie', 'Je me lève tôt. Je me douche et je prends le petit-déjeuner.'),
                Kit::line('Luc', 'Et tu te couches tôt, Marie ?'),
                Kit::line('Marie', 'Oui, je me couche tôt tous les jours.'),
            ], [
                Kit::question('Does Luc get up early?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('When does Luc work?', ['Early', 'Late', 'The conversation does not say.'], 'Late'),
                Kit::question('When does Marie go to bed?', ['Early', 'Late', 'The conversation does not say.'], 'Early'),
            ], [
                Kit::question('Who gets up earlier?', ['Marie', 'Luc', 'They get up at the same time'], 'Marie'),
                Kit::question('What does Marie do after she gets up?', ['She showers and has breakfast', 'She goes to work', 'She goes to bed'], 'She showers and has breakfast'),
                Kit::question('How often does Marie go to bed early?', ['Every day', 'Never', 'The conversation does not say.'], 'Every day'),
            ], [Kit::word('se lever', 'me lève'), Kit::word('travailler', 'travaille'), Kit::word('se doucher', 'me douche'), Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::word('se coucher', 'me couche'), Kit::word('normalement'), Kit::word('tôt'), Kit::word('tard'), Kit::word('tous les jours')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paul-anne', 'Paul se lève tard. Anne se lève tôt.', 'Paul gets up late. Anne gets up early.', [Kit::word('se lever', 'se lève'), Kit::word('tard'), Kit::word('tôt'), Kit::form('se lève')]),
            Kit::listenType($stage, 'task.listen_type.prenons', 'Normalement, nous prenons le petit-déjeuner tard.', 'Normally we have breakfast late.', [Kit::word('normalement'), Kit::word('prendre le petit-déjeuner', 'prenons le petit-déjeuner'), Kit::word('tard'), Kit::form('prenons', true)]),
            Kit::listenType($stage, 'task.listen_type.vous', 'Vous vous réveillez tôt et vous travaillez tous les jours.', 'You wake up early and you work every day.', [Kit::word('se réveiller', 'vous vous réveillez'), Kit::word('travailler', 'vous travaillez'), Kit::word('tôt'), Kit::word('tous les jours'), Kit::form('vous vous réveillez')], homophoneNote: 'Et (and) and est (is) sound very close, and context decides: here et joins the two parts of the sentence. Also, réveillez (after vous) and réveiller (the infinitive) sound the same.'),

            Kit::speakAnswer($stage, 'task.speak_answer.leves', 'Tu te lèves tôt ou tard ?', 'Do you get up early or late?', [['lève'], ['tôt', 'tard']], 'Je me lève tôt.', [Kit::word('se lever', 'me lève'), Kit::word('tôt'), Kit::word('tard')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.douches', 'Tu te douches tôt ou tard ?', 'Do you shower early or late?', [['douche'], ['tôt', 'tard']], 'Je me douche tôt.', [Kit::word('se doucher', 'me douche'), Kit::word('tôt')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.petit-dejeuner', 'Tu prends le petit-déjeuner tôt ou tard ?', 'Do you have breakfast early or late?', [['prends'], ['tôt', 'tard']], 'Je prends le petit-déjeuner tôt.', [Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::word('tôt'), Kit::word('tard')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.travailles', 'Tu travailles tous les jours ?', 'Do you work every day?', [['travaille', 'jours', 'tous', 'chaque']], 'Oui, je travaille tous les jours.', [Kit::word('travailler', 'travaille'), Kit::word('tous les jours')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.couchons', 'Nous nous couchons tard tous les jours.', 'We go to bed late every day.', [Kit::word('se coucher', 'nous nous couchons'), Kit::word('tard'), Kit::word('tous les jours'), Kit::form('nous nous couchons')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.douche-prend', 'Elle se douche et elle prend le petit-déjeuner.', 'She showers and she has breakfast.', [Kit::word('se doucher', 'se douche'), Kit::word('prendre le petit-déjeuner', 'prend le petit-déjeuner'), Kit::form('se douche')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.reveille', 'She wakes up early and has breakfast.', ['Elle se réveille tôt et elle prend le petit-déjeuner.', 'Elle se réveille tôt et prend le petit-déjeuner.', 'Elle se réveille tôt et elle prend son petit-déjeuner.', 'Elle se réveille tôt et prend son petit-déjeuner.'], [Kit::word('se réveiller', 'se réveille'), Kit::word('tôt'), Kit::word('prendre le petit-déjeuner', 'prend'), Kit::form('se réveille')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.couchons', 'We normally go to bed late.', ['Normalement, nous nous couchons tard.', 'Nous nous couchons tard normalement.', 'Nous nous couchons normalement tard.', "D'habitude, nous nous couchons tard.", "Nous nous couchons tard d'habitude.", "Nous nous couchons d'habitude tard."], [Kit::word('normalement', null, ["d'habitude"]), Kit::word('se coucher', 'nous nous couchons'), Kit::word('tard'), Kit::form('nous nous couchons')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.travaillent', 'They work early every day.', ['Ils travaillent tôt tous les jours.', 'Elles travaillent tôt tous les jours.', 'Tous les jours, ils travaillent tôt.', 'Tous les jours, elles travaillent tôt.', 'Ils travaillent tôt chaque jour.', 'Elles travaillent tôt chaque jour.', 'Chaque jour, ils travaillent tôt.', 'Chaque jour, elles travaillent tôt.'], [Kit::word('travailler', 'travaillent'), Kit::word('tôt'), Kit::word('tous les jours', null, ['chaque jour']), Kit::form('travaillent', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.leves', 'You (tu) get up late and you shower.', ['Tu te lèves tard et tu te douches.', 'Tu te lèves tard et te douches.', 'Tu te lèves tard et tu prends une douche.', 'Tu te lèves tard et prends une douche.'], [Kit::word('se lever', 'te lèves'), Kit::word('tard'), Kit::word('se doucher', 'te douches', ['prends une douche']), Kit::form('te lèves')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.prend', 'Il ___ le petit-déjeuner tard.', 'He has breakfast late.', 'prend', Kit::form('prend', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.couche', 'Luc ___ tard.', 'Luc goes to bed late.', 'se couche', Kit::form('se couche'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.douche', 'Normalement, je me douche tous les jours.', 'Normally I shower every day.', [Kit::word('normalement', null, ["d'habitude"]), Kit::word('se doucher', 'me douche', ['prends une douche']), Kit::word('tous les jours', null, ['chaque jour'])], 'dictation', $set, alsoAccepted: ['Normalement, je prends une douche tous les jours.', 'Normalement, je me douche chaque jour.', "D'habitude, je me douche tous les jours."]),
            Kit::listenType($stage, 'check.a.listen_type.paul', 'Paul se lève tôt. Il travaille tard.', 'Paul gets up early. He works late.', [Kit::word('se lever', 'se lève'), Kit::word('travailler', 'travaille'), Kit::word('tôt'), Kit::word('tard')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.reveille', 'Je me réveille tôt tous les jours.', 'I wake up early every day.', [Kit::word('se réveiller', 'me réveille'), Kit::word('tôt'), Kit::word('tous les jours', null, ['chaque jour'])], 'dictation', $set, alsoAccepted: ['Je me réveille tôt chaque jour.']),
            Kit::listenPassage($stage, 'check.a.listen_passage.paul', [
                Kit::line('Anne', 'Paul, tu te lèves tôt ?'),
                Kit::line('Paul', 'Oui, je me lève tôt. Je prends le petit-déjeuner et je travaille.'),
                Kit::line('Anne', 'Tu te couches tôt aussi ?'),
                Kit::line('Paul', 'Non, normalement je me couche tard.'),
            ], [
                Kit::question('What does Paul do after he gets up?', ['He has breakfast and works', 'He goes to bed', 'He showers'], 'He has breakfast and works'),
                Kit::question('When does Paul go to bed?', ['Early', 'Late', 'The conversation does not say.'], 'Late'),
                Kit::question('Does Paul get up early?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who asks the questions?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
                Kit::question('Does Paul work?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('se lever', 'me lève'), Kit::word('prendre le petit-déjeuner', 'prends le petit-déjeuner'), Kit::word('travailler', 'travaille'), Kit::word('se coucher', 'me couche'), Kit::word('tôt'), Kit::word('tard'), Kit::word('normalement')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.luc-marie', 'Read the conversation.', [
                Kit::line('Luc', 'Marie, tu te lèves tard ?'),
                Kit::line('Marie', 'Non, je me lève tôt tous les jours.'),
                Kit::line('Luc', 'Je me lève tard. Je me douche et je travaille.'),
            ], [
                Kit::question('Who gets up late?', ['Marie', 'Luc', 'Both of them'], 'Luc'),
                Kit::question('Does Marie get up early?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
            ], [Kit::word('se lever', 'me lève'), Kit::word('se doucher', 'me douche'), Kit::word('travailler', 'travaille'), Kit::word('tôt'), Kit::word('tard'), Kit::word('tous les jours')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.leves', 'Tu te lèves tard ?', 'Do you get up late?', [['lève'], ['tard', 'tôt']], 'Non, je me lève tôt.', [Kit::word('se lever', 'me lève'), Kit::word('tard')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.douches', 'Tu te douches tous les jours ?', 'Do you shower every day?', [['douche', 'jours', 'tous', 'chaque']], 'Oui, je me douche tous les jours.', [Kit::word('se doucher', 'me douche'), Kit::word('tous les jours')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.travailles', 'Normalement, tu travailles tôt ou tard ?', 'Do you normally work early or late?', [['travaille'], ['tôt', 'tard']], 'Je travaille tard.', [Kit::word('normalement'), Kit::word('travailler', 'travaille')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.levons', 'We get up early every day.', ['Nous nous levons tôt tous les jours.', 'Tous les jours, nous nous levons tôt.', 'Nous nous levons tôt chaque jour.', 'Chaque jour, nous nous levons tôt.'], [Kit::word('se lever', 'nous nous levons'), Kit::word('tôt'), Kit::word('tous les jours', null, ['chaque jour']), Kit::form('nous nous levons')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.travaille', 'Normally she works late.', ['Normalement, elle travaille tard.', 'Elle travaille tard normalement.', 'Elle travaille normalement tard.', "D'habitude, elle travaille tard.", "Elle travaille tard d'habitude.", "Elle travaille d'habitude tard."], [Kit::word('normalement', null, ["d'habitude"]), Kit::word('travailler', 'travaille'), Kit::word('tard'), Kit::form('travaille', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.douchent', 'They shower and they have breakfast.', ['Ils se douchent et ils prennent le petit-déjeuner.', 'Ils se douchent et prennent le petit-déjeuner.', 'Elles se douchent et elles prennent le petit-déjeuner.', 'Elles se douchent et prennent le petit-déjeuner.', 'Ils prennent une douche et ils prennent le petit-déjeuner.', 'Ils prennent une douche et prennent le petit-déjeuner.', 'Elles prennent une douche et elles prennent le petit-déjeuner.', 'Elles prennent une douche et prennent le petit-déjeuner.'], [Kit::word('se doucher', 'se douchent', ['prennent une douche']), Kit::word('prendre le petit-déjeuner', 'prennent le petit-déjeuner'), Kit::form('se douchent', false, ['prennent une douche'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.reveillent', 'They wake up early and go to bed late.', ['Ils se réveillent tôt et ils se couchent tard.', 'Ils se réveillent tôt et se couchent tard.', 'Elles se réveillent tôt et elles se couchent tard.', 'Elles se réveillent tôt et se couchent tard.'], [Kit::word('se réveiller', 'se réveillent'), Kit::word('tôt'), Kit::word('se coucher', 'se couchent'), Kit::word('tard'), Kit::form('se réveillent')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.prend', 'Marie ___ le petit-déjeuner tôt.', 'Marie has breakfast early.', 'prend', Kit::form('prend', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.douche', 'Je ___ tous les jours.', 'I shower every day.', 'me douche', Kit::form('me douche'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.leve', 'Normalement, je me lève tard.', 'Normally I get up late.', [Kit::word('normalement', null, ["d'habitude"]), Kit::word('se lever', 'me lève'), Kit::word('tard')], 'dictation', $set, alsoAccepted: ["D'habitude, je me lève tard."]),
            Kit::listenType($stage, 'check.b.listen_type.luc', 'Luc travaille tard, Marie se couche tôt.', 'Luc works late, Marie goes to bed early.', [Kit::word('travailler', 'travaille'), Kit::word('se coucher', 'se couche'), Kit::word('tard'), Kit::word('tôt')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.reveillons', 'Nous nous réveillons tôt tous les jours.', 'We wake up early every day.', [Kit::word('se réveiller', 'nous nous réveillons'), Kit::word('tôt'), Kit::word('tous les jours', null, ['chaque jour'])], 'dictation', $set, alsoAccepted: ['Nous nous réveillons tôt chaque jour.']),
        ];
    }
}
