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

final class DescribingYourDailyRoutine implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'describing-your-daily-routine';
    }

    public function words(): array
    {
        return [
            new WordData('alzarsi', cue: 'to get up', forms: ['mi alzo']),
            new WordData('svegliarsi', cue: 'to wake up', forms: ['mi sveglio']),
            new WordData('farsi la doccia', cue: 'to shower', accepted: ['fare la doccia'], forms: ['mi faccio la doccia']),
            new WordData('fare colazione', cue: 'to have breakfast', forms: ['faccio colazione']),
            new WordData('lavorare', cue: 'to work', forms: ['lavoro']),
            new WordData('andare a letto', cue: 'to go to bed', accepted: ['andare a dormire'], forms: ['vado a letto']),
            new WordData('presto', cue: 'early'),
            new WordData('tardi', cue: 'late (not early)'),
            new WordData('ogni giorno', cue: 'every day', accepted: ['tutti i giorni']),
            new WordData('di solito', cue: 'normally, usually', accepted: ['normalmente']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mi alzo presto.', 'english' => 'I get up early.'],
            ['text' => 'Lei si sveglia tardi.', 'english' => 'She wakes up late.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-05', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-05', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-05', 'Released on the owner\'s instruction on 2026-10-05, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $a = 'A (to, at) sounds the same as ha (has), which is written with an h. Here it is the preposition a.';
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

        return [
            Kit::gap($stage, 'sentences.choose_gap.io-alzo', 'Io ___ presto.', ['mi alzo', 'si alzo', 'mi alzi'], 'mi alzo', Kit::form('mi alzo'), 'Io goes with mi, and the verb ends in -o: mi alzo.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.anna-alza', 'Anna ___ tardi.', ['si alza', 'si alzano', 'ti alzi'], 'si alza', Kit::form('si alza'), 'Anna (she) goes with si, and the verb ends in -a: si alza.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tu-alzi', 'Tu ___ alle sette.', ['ti alzi', 'ti alzo', 'si alzi'], 'ti alzi', Kit::form('ti alzi'), 'Tu goes with ti, and the verb ends in -i: ti alzi.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.noi-svegliamo', 'Noi ___ presto.', ['ci svegliamo', 'mi svegliamo', 'ci sveglio'], 'ci svegliamo', Kit::form('ci svegliamo'), 'Noi goes with ci, and the verb ends in -iamo: ci svegliamo.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.io-lavoro', 'Io ___ alle nove.', ['lavoro', 'mi lavoro', 'si lavoro'], 'lavoro', Kit::form('lavoro', true), 'Lavorare is not reflexive, so the verb takes no pronoun.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tu-lavori', 'Tu ___ alle dieci.', ['lavori', 'ti lavori', 'si lavori'], 'lavori', Kit::form('lavori', true), 'Lavorare is not reflexive, so the verb takes no pronoun.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.mi-alzo', '___ presto.', 'I get up early.', 'Mi alzo', Kit::form('mi alzo'), 'The subject io goes with mi: mi alzo.'),
            Kit::typeGap($stage, 'sentences.type_gap.si-alza', 'Lei ___ presto.', 'She gets up early.', 'si alza', Kit::form('si alza'), 'Lei (she) goes with si: si alza.'),
            Kit::typeGap($stage, 'sentences.type_gap.mi-sveglio', 'Io ___ alle sette.', 'I wake up at seven.', 'mi sveglio', Kit::form('mi sveglio'), 'Io goes with mi: mi sveglio.'),
            Kit::typeGap($stage, 'sentences.type_gap.si-fa-la-doccia', 'Lei ___ alle otto.', 'She showers at eight.', 'si fa la doccia', Kit::form('si fa la doccia'), 'Lei (she) goes with si: si fa la doccia.'),
            Kit::typeGap($stage, 'sentences.type_gap.ci-alziamo', 'Noi ___ presto.', 'We get up early.', 'ci alziamo', Kit::form('ci alziamo'), 'Noi goes with ci: ci alziamo.'),

            Kit::translate($stage, 'sentences.translate.di-solito-alzo', 'Normally I get up early.', ['Di solito mi alzo presto.', 'Mi alzo presto di solito.', 'Di solito io mi alzo presto.', 'Io di solito mi alzo presto.', 'Normalmente mi alzo presto.', 'Mi alzo presto normalmente.', 'Normalmente io mi alzo presto.', 'Io normalmente mi alzo presto.'], [Kit::word('di solito', null, ['normalmente']), Kit::word('alzarsi', 'mi alzo'), Kit::word('presto'), Kit::form('mi alzo')]),
            Kit::translate($stage, 'sentences.translate.va-a-letto', 'She goes to bed late.', ['Lei va a letto tardi.', 'Va a letto tardi.', 'Lei va a dormire tardi.', 'Va a dormire tardi.'], [Kit::word('andare a letto', 'va a letto', ['va a dormire']), Kit::word('tardi')]),
            Kit::translate($stage, 'sentences.translate.lavoro', 'I work at nine.', ['Lavoro alle nove.', 'Io lavoro alle nove.'], [Kit::word('lavorare', 'lavoro'), Kit::form('lavoro', true)]),

            Kit::build($stage, 'sentences.build.mi-sveglio', 'I wake up at seven.', 'Mi sveglio alle sette.', ['si'], [Kit::word('svegliarsi', 'mi sveglio'), Kit::form('mi sveglio')]),
            Kit::build($stage, 'sentences.build.si-fa-la-doccia', 'She showers every day.', 'Lei si fa la doccia ogni giorno.', ['mi'], [Kit::word('farsi la doccia', 'si fa la doccia'), Kit::word('ogni giorno'), Kit::form('si fa la doccia')]),
            Kit::build($stage, 'sentences.build.ci-alziamo', 'We get up early.', 'Ci alziamo presto.', ['si'], [Kit::word('alzarsi', 'ci alziamo'), Kit::word('presto'), Kit::form('ci alziamo')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.alzo', 'Mi alzo presto ogni giorno.', ['I get up early every day.', 'I go to bed early every day.', 'I get up late every day.', 'I work early every day.'], 'I get up early every day.', [Kit::word('alzarsi', 'mi alzo'), Kit::word('presto'), Kit::word('ogni giorno'), Kit::form('mi alzo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.si-alza', 'Anna si alza tardi.', ['Anna gets up late.', 'Anna goes to bed late.', 'Anna gets up early.', 'Anna works late.'], 'Anna gets up late.', [Kit::word('alzarsi', 'si alza'), Kit::word('tardi'), Kit::form('si alza')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.colazione', 'Di solito faccio colazione alle otto.', ['Normally I have breakfast at eight.', 'Normally I work at eight.', 'Normally I have breakfast at seven.', 'Normally I shower at eight.'], 'Normally I have breakfast at eight.', [Kit::word('di solito'), Kit::word('fare colazione', 'faccio colazione')]),
            Kit::listenType($stage, 'sentences.listen_type.mi-faccio-la-doccia', 'Mi faccio la doccia presto.', 'I shower early.', [Kit::word('farsi la doccia', 'mi faccio la doccia'), Kit::word('presto'), Kit::form('mi faccio la doccia')]),
            Kit::listenType($stage, 'sentences.listen_type.si-sveglia', 'Lei si sveglia tardi ogni giorno.', 'She wakes up late every day.', [Kit::word('svegliarsi', 'si sveglia'), Kit::word('tardi'), Kit::word('ogni giorno'), Kit::form('si sveglia')]),
            Kit::listenType($stage, 'sentences.listen_type.lavoro', 'Di solito lavoro con Marta.', 'Normally I work with Marta.', [Kit::word('di solito'), Kit::word('lavorare', 'lavoro')]),
            Kit::listenType($stage, 'sentences.listen_type.andiamo', 'Andiamo a letto tardi.', 'We go to bed late.', [Kit::word('andare a letto', 'andiamo a letto'), Kit::word('tardi')], homophoneNote: $a),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.di-solito-alzo', 'Di solito mi alzo presto ogni giorno.', 'I normally get up early every day.', [Kit::word('di solito'), Kit::word('alzarsi', 'mi alzo'), Kit::word('presto'), Kit::word('ogni giorno'), Kit::form('mi alzo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.si-fa-la-doccia', 'Anna si fa la doccia alle otto.', 'Anna showers at eight.', [Kit::word('farsi la doccia', 'si fa la doccia'), Kit::form('si fa la doccia')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.lavoro', 'Lavoro con Anna alle nove.', 'I work with Anna at nine.', [Kit::word('lavorare', 'lavoro')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.vado-a-letto', 'Vado a letto tardi.', 'I go to bed late.', [Kit::word('andare a letto', 'vado a letto'), Kit::word('tardi')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.alzi', 'Ti alzi presto?', 'Do you get up early?', [['sì', 'no', 'alzo', 'presto', 'tardi', 'cinque', 'sei', 'sette', 'otto', 'nove']], 'Sì, mi alzo presto.', [Kit::word('alzarsi', 'mi alzo'), Kit::word('presto'), Kit::form('mi alzo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.colazione', 'Fai colazione ogni giorno?', 'Do you have breakfast every day?', [['faccio', 'colazione', 'giorno', 'giorni', 'ogni', 'tutti']], 'Sì, faccio colazione ogni giorno.', [Kit::word('fare colazione', 'faccio colazione'), Kit::word('ogni giorno')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.letto', 'Vai a letto tardi?', 'Do you go to bed late?', [['vado', 'letto', 'tardi', 'presto']], 'Sì, vado a letto tardi.', [Kit::word('andare a letto', 'vado a letto'), Kit::word('tardi')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $a = 'A (to, at) sounds the same as ha (has), which is written with an h. Here it is the preposition a.';
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

        return [
            Kit::readPassage($stage, 'task.read_passage.routine', 'Read the conversation about daily routines.', [
                Kit::line('Anna', 'Ti alzi presto, Paolo?'),
                Kit::line('Paolo', 'Sì, di solito mi alzo alle sei. Mi faccio la doccia e faccio colazione.'),
                Kit::line('Anna', 'Alle sei? Io mi sveglio alle sette.'),
                Kit::line('Paolo', 'Lavoro alle otto. Vado a letto presto ogni giorno.'),
                Kit::line('Anna', 'Io vado a letto tardi.'),
            ], [
                Kit::question('What time does Paolo get up?', ['At five', 'At six', 'At seven'], 'At six'),
                Kit::question('What time does Paolo start work?', ['At seven', 'At eight', 'At nine'], 'At eight'),
                Kit::question('Who goes to bed late?', ['Paolo', 'Anna', 'Both of them'], 'Anna'),
            ], [Kit::word('alzarsi', 'mi alzo'), Kit::word('farsi la doccia', 'mi faccio la doccia'), Kit::word('fare colazione', 'faccio colazione'), Kit::word('svegliarsi', 'mi sveglio'), Kit::word('lavorare', 'lavoro'), Kit::word('andare a letto', 'vado a letto'), Kit::word('di solito'), Kit::word('presto'), Kit::word('tardi'), Kit::word('ogni giorno')]),
            Kit::gap($stage, 'task.choose_gap.presto', 'Mi alzo alle cinque, molto ___.', ['presto', 'tardi'], 'presto', Kit::word('presto'), 'Five in the morning is early, so presto. Late is tardi.', 'read'),
            Kit::gap($stage, 'task.choose_gap.tardi', 'Lei va a letto alle due, molto ___.', ['tardi', 'presto'], 'tardi', Kit::word('tardi'), 'Two at night is late, so tardi. Early is presto.', 'read'),

            Kit::transform($stage, 'task.transform.lei', 'Change the subject to she.', 'Mi alzo presto.', ['Lei si alza presto.', 'Si alza presto.'], [Kit::word('alzarsi', 'si alza'), Kit::word('presto'), Kit::form('si alza')]),
            Kit::transform($stage, 'task.transform.noi', 'Change the subject to we.', 'Mi sveglio alle sette.', ['Ci svegliamo alle sette.', 'Noi ci svegliamo alle sette.'], [Kit::word('svegliarsi', 'ci svegliamo'), Kit::form('ci svegliamo')]),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to you (tu).', 'Mi faccio la doccia tardi.', ['Ti fai la doccia tardi.', 'Tu ti fai la doccia tardi.', 'Fai la doccia tardi.', 'Tu fai la doccia tardi.'], [Kit::word('farsi la doccia', 'ti fai la doccia', ['fai la doccia']), Kit::word('tardi'), Kit::form('ti fai la doccia', false, ['fai la doccia'])]),
            Kit::writeGuided($stage, 'task.write_guided.routine', 'Describe your daily routine. Use the words get up, have breakfast and work.', ['mi alzo', 'faccio colazione', 'lavoro'], 'Mi alzo presto, faccio colazione e lavoro alle nove.', [
                ['forms' => ['alzo', 'alziamo', 'alza', 'alzano'], 'term' => 'alzarsi'],
                ['forms' => ['colazione'], 'term' => 'fare colazione'],
                ['forms' => ['lavoro', 'lavoriamo', 'lavora'], 'term' => 'lavorare'],
            ], [Kit::word('alzarsi', 'mi alzo'), Kit::word('fare colazione', 'faccio colazione'), Kit::word('lavorare', 'lavoro')]),
            Kit::writeGuided($stage, 'task.write_guided.sveglio', 'Say that you wake up early and go to bed late.', ['mi sveglio', 'presto', 'vado a letto', 'tardi'], 'Mi sveglio presto e vado a letto tardi.', [
                ['forms' => ['sveglio', 'svegliamo', 'sveglia', 'svegliano'], 'term' => 'svegliarsi'],
                ['forms' => ['presto'], 'term' => 'presto'],
                ['forms' => ['letto', 'dormire'], 'term' => 'andare a letto'],
                ['forms' => ['tardi'], 'term' => 'tardi'],
            ], [Kit::word('svegliarsi', 'mi sveglio'), Kit::word('presto'), Kit::word('andare a letto', 'vado a letto'), Kit::word('tardi')]),
            Kit::build($stage, 'task.build.si-alzano', 'They get up early.', 'Loro si alzano presto.', ['mi', 'alza'], [Kit::word('alzarsi', 'si alzano'), Kit::word('presto'), Kit::form('si alzano')], 'write'),
            Kit::build($stage, 'task.build.facciamo-colazione', 'We have breakfast at eight.', 'Facciamo colazione alle otto.', ['mi', 'faccio'], [Kit::word('fare colazione', 'facciamo colazione'), Kit::form('facciamo colazione', true)], 'write'),
            Kit::build($stage, 'task.build.sveglio-doccia', 'I wake up at seven and I shower.', 'Mi sveglio alle sette e mi faccio la doccia.', ['si', 'ti'], [Kit::word('svegliarsi', 'mi sveglio'), Kit::word('farsi la doccia', 'mi faccio la doccia'), Kit::form('mi faccio la doccia')], 'write'),
            Kit::translate($stage, 'task.translate.alzo-colazione', 'I get up at six and I have breakfast at seven.', ['Mi alzo alle sei e faccio colazione alle sette.', 'Io mi alzo alle sei e faccio colazione alle sette.'], [Kit::word('alzarsi', 'mi alzo'), Kit::word('fare colazione', 'faccio colazione'), Kit::form('mi alzo')], 'write'),
            Kit::translate($stage, 'task.translate.lavoriamo', 'We normally work at nine.', ['Di solito lavoriamo alle nove.', 'Lavoriamo di solito alle nove.', 'Di solito noi lavoriamo alle nove.', 'Noi di solito lavoriamo alle nove.', 'Normalmente lavoriamo alle nove.', 'Lavoriamo normalmente alle nove.', 'Normalmente noi lavoriamo alle nove.', 'Noi normalmente lavoriamo alle nove.'], [Kit::word('di solito', null, ['normalmente']), Kit::word('lavorare', 'lavoriamo'), Kit::form('lavoriamo', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.routine', [
                Kit::line('Marta', 'Ti alzi presto, Luca?'),
                Kit::line('Luca', 'No, di solito mi alzo alle otto. Lavoro alle dieci.'),
                Kit::line('Marta', 'Io mi alzo alle sei. Mi faccio la doccia e faccio colazione.'),
                Kit::line('Luca', 'E vai a letto presto?'),
                Kit::line('Marta', 'Sì, vado a letto alle dieci ogni giorno.'),
            ], [
                Kit::question('What time does Luca get up?', ['At six', 'At eight', 'At ten'], 'At eight'),
                Kit::question('What time does Luca start work?', ['At eight', 'At nine', 'At ten'], 'At ten'),
                Kit::question('What time does Marta go to bed?', ['At nine', 'At ten', 'At twelve'], 'At ten'),
            ], [
                Kit::question('Who gets up earlier?', ['Marta', 'Luca', 'They get up at the same time'], 'Marta'),
                Kit::question('What does Marta do after she gets up?', ['She showers and has breakfast', 'She goes to work', 'She goes to bed'], 'She showers and has breakfast'),
                Kit::question('What does Marta do every day at ten?', ['She goes to bed', 'She gets up', 'She works'], 'She goes to bed'),
            ], [Kit::word('alzarsi', 'mi alzo'), Kit::word('lavorare', 'lavoro'), Kit::word('farsi la doccia', 'mi faccio la doccia'), Kit::word('fare colazione', 'faccio colazione'), Kit::word('andare a letto', 'vado a letto'), Kit::word('di solito'), Kit::word('presto'), Kit::word('ogni giorno')]),
            Kit::listenType($stage, 'task.listen_type.doccia-colazione', 'Di solito mi faccio la doccia e faccio colazione.', 'Normally I shower and have breakfast.', [Kit::word('di solito'), Kit::word('farsi la doccia', 'mi faccio la doccia'), Kit::word('fare colazione', 'faccio colazione'), Kit::form('mi faccio la doccia')], homophoneNote: $e),
            Kit::listenType($stage, 'task.listen_type.anna-sveglia', 'Anna si sveglia tardi tutti i giorni.', 'Anna wakes up late every day.', [Kit::word('svegliarsi', 'si sveglia'), Kit::word('tardi'), Kit::word('ogni giorno', 'tutti i giorni'), Kit::form('si sveglia')]),
            Kit::listenType($stage, 'task.listen_type.andiamo-alziamo', 'Andiamo a letto presto e ci alziamo presto.', 'We go to bed early and we get up early.', [Kit::word('andare a letto', 'andiamo a letto'), Kit::word('alzarsi', 'ci alziamo'), Kit::word('presto'), Kit::form('ci alziamo')], homophoneNote: $a.' '.$e),

            Kit::speakAnswer($stage, 'task.speak_answer.quando-alzi', 'Quando ti alzi?', 'When do you get up?', [['alzo', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci', 'presto', 'tardi', 'solito']], 'Mi alzo alle sette.', [Kit::word('alzarsi', 'mi alzo'), Kit::form('mi alzo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quando-letto', 'Quando vai a letto?', 'When do you go to bed?', [['vado', 'sette', 'otto', 'nove', 'dieci', 'undici', 'dodici', 'presto', 'tardi', 'solito']], 'Vado a letto alle undici.', [Kit::word('andare a letto', 'vado a letto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.colazione', 'Fai colazione presto o tardi?', 'Do you have breakfast early or late?', [['faccio', 'colazione', 'presto', 'tardi']], 'Faccio colazione presto.', [Kit::word('fare colazione', 'faccio colazione'), Kit::word('presto'), Kit::word('tardi')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.lavori', 'Lavori ogni giorno?', 'Do you work every day?', [['lavoro', 'lavorare', 'ogni', 'tutti', 'giorno', 'giorni']], 'Sì, lavoro ogni giorno.', [Kit::word('lavorare', 'lavoro'), Kit::word('ogni giorno')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ci-svegliamo', 'Ci svegliamo presto ogni giorno.', 'We wake up early every day.', [Kit::word('svegliarsi', 'ci svegliamo'), Kit::word('presto'), Kit::word('ogni giorno'), Kit::form('ci svegliamo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.si-fa-la-doccia', 'Lei si fa la doccia e fa colazione alle sette.', 'She showers and has breakfast at seven.', [Kit::word('farsi la doccia', 'si fa la doccia'), Kit::word('fare colazione', 'fa colazione'), Kit::form('si fa la doccia')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $a = 'A (to, at) sounds the same as ha (has), which is written with an h. Here it is the preposition a.';
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

        return [
            Kit::translate($stage, 'check.a.translate.sveglia', 'She wakes up early and has breakfast.', ['Lei si sveglia presto e fa colazione.', 'Si sveglia presto e fa colazione.'], [Kit::word('svegliarsi', 'si sveglia'), Kit::word('presto'), Kit::word('fare colazione', 'fa colazione'), Kit::form('si sveglia')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.doccia-letto', 'I shower and go to bed at eleven.', ['Mi faccio la doccia e vado a letto alle undici.', 'Faccio la doccia e vado a letto alle undici.', 'Io faccio la doccia e vado a letto alle undici.', 'Mi faccio la doccia e vado a dormire alle undici.', 'Faccio la doccia e vado a dormire alle undici.', 'Io faccio la doccia e vado a dormire alle undici.'], [Kit::word('farsi la doccia', 'mi faccio la doccia', ['faccio la doccia']), Kit::word('andare a letto', 'vado a letto', ['vado a dormire'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.lavoro', 'Normally I work at ten.', ['Di solito lavoro alle dieci.', 'Lavoro di solito alle dieci.', 'Di solito io lavoro alle dieci.', 'Io di solito lavoro alle dieci.', 'Normalmente lavoro alle dieci.', 'Lavoro normalmente alle dieci.', 'Normalmente io lavoro alle dieci.', 'Io normalmente lavoro alle dieci.'], [Kit::word('di solito', null, ['normalmente']), Kit::word('lavorare', 'lavoro'), Kit::form('lavoro', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.alzano', 'They get up late every day.', ['Loro si alzano tardi ogni giorno.', 'Si alzano tardi ogni giorno.', 'Loro si alzano tardi tutti i giorni.', 'Si alzano tardi tutti i giorni.'], [Kit::word('alzarsi', 'si alzano'), Kit::word('tardi'), Kit::word('ogni giorno', null, ['tutti i giorni']), Kit::form('si alzano')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.fai', 'Tu ___ alle otto.', 'You have breakfast at eight.', 'fai colazione', Kit::form('fai colazione', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.alzo', 'Io ___ alle otto.', 'I get up at eight.', 'mi alzo', Kit::form('mi alzo'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.alziamo', 'Ci alziamo tardi ogni giorno.', 'We get up late every day.', [Kit::word('alzarsi', 'ci alziamo'), Kit::word('tardi'), Kit::word('ogni giorno'), Kit::form('ci alziamo')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.sveglio', 'Mi sveglio, faccio colazione e mi faccio la doccia.', 'I wake up, have breakfast and shower.', [Kit::word('svegliarsi', 'mi sveglio'), Kit::word('fare colazione', 'faccio colazione'), Kit::word('farsi la doccia', 'mi faccio la doccia')], 'dictation', $set, homophoneNote: $e),
            Kit::listenType($stage, 'check.a.listen_type.lavoro', 'Di solito lavoro e vado a letto presto.', 'Normally I work and go to bed early.', [Kit::word('di solito'), Kit::word('lavorare', 'lavoro'), Kit::word('andare a letto', 'vado a letto'), Kit::word('presto')], 'dictation', $set, homophoneNote: $a.' '.$e),
            Kit::listenPassage($stage, 'check.a.listen_passage.routine', [
                Kit::line('Marta', 'Ciao, Paolo. E tu, ti alzi presto?'),
                Kit::line('Paolo', 'Sì, mi alzo alle sei e mi faccio la doccia.'),
                Kit::line('Marta', 'Io lavoro alle dieci.'),
            ], [
                Kit::question('What time does Paolo get up?', ['At five', 'At six', 'At seven'], 'At six'),
                Kit::question('What time does Marta work?', ['At eight', 'At nine', 'At ten'], 'At ten'),
                Kit::question('What does Paolo do after he gets up?', ['He showers', 'He has breakfast', 'He goes to bed'], 'He showers'),
            ], [
                Kit::question('Who asks about getting up?', ['Marta', 'Paolo', 'Nobody'], 'Marta'),
                Kit::question('Does Paolo get up early?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('alzarsi', 'mi alzo'), Kit::word('farsi la doccia', 'mi faccio la doccia'), Kit::word('lavorare', 'lavoro'), Kit::word('presto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.letto', 'Read the conversation.', [
                Kit::line('Anna', 'Vai a letto tardi, Luca?'),
                Kit::line('Luca', 'No, vado a letto alle dieci. E tu?'),
                Kit::line('Anna', 'Io vado a letto alle dodici. È molto tardi.'),
            ], [
                Kit::question('What time does Luca go to bed?', ['At nine', 'At ten', 'At eleven'], 'At ten'),
                Kit::question('Who goes to bed later?', ['Luca', 'Anna', 'The text does not say.'], 'Anna'),
            ], [Kit::word('andare a letto', 'vado a letto'), Kit::word('tardi')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.alzi', 'Ti alzi presto o tardi?', 'Do you get up early or late?', [['sì', 'no', 'alzo', 'presto', 'tardi']], 'Mi alzo presto.', [Kit::word('alzarsi', 'mi alzo'), Kit::word('presto')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.colazione', 'Quando fai colazione?', 'When do you have breakfast?', [['faccio', 'colazione', 'sei', 'sette', 'otto', 'nove', 'dieci', 'presto', 'tardi', 'solito']], 'Faccio colazione alle otto.', [Kit::word('fare colazione', 'faccio colazione')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.letto', 'Vai a letto presto?', 'Do you go to bed early?', [['vado', 'letto', 'presto', 'tardi']], 'No, vado a letto tardi.', [Kit::word('andare a letto', 'vado a letto'), Kit::word('presto'), Kit::word('tardi')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $a = 'A (to, at) sounds the same as ha (has), which is written with an h. Here it is the preposition a.';
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

        return [
            Kit::translate($stage, 'check.b.translate.svegliamo', 'We normally wake up early.', ['Di solito ci svegliamo presto.', 'Ci svegliamo presto di solito.', 'Di solito noi ci svegliamo presto.', 'Noi di solito ci svegliamo presto.', 'Normalmente ci svegliamo presto.', 'Ci svegliamo presto normalmente.', 'Normalmente noi ci svegliamo presto.', 'Noi normalmente ci svegliamo presto.'], [Kit::word('di solito', null, ['normalmente']), Kit::word('svegliarsi', 'ci svegliamo'), Kit::word('presto'), Kit::form('ci svegliamo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.lavora', 'Marta works and goes to bed late every day.', ['Marta lavora e va a letto tardi ogni giorno.', 'Marta lavora e va a letto tardi tutti i giorni.', 'Lavora e va a letto tardi ogni giorno.', 'Lavora e va a letto tardi tutti i giorni.', 'Marta lavora e va a dormire tardi ogni giorno.', 'Marta lavora e va a dormire tardi tutti i giorni.', 'Lavora e va a dormire tardi ogni giorno.', 'Lavora e va a dormire tardi tutti i giorni.'], [Kit::word('lavorare', 'lavora'), Kit::word('andare a letto', 'va a letto', ['va a dormire']), Kit::word('tardi'), Kit::word('ogni giorno', null, ['tutti i giorni']), Kit::form('lavora', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.alzo-doccia', 'I get up and I shower.', ['Mi alzo e mi faccio la doccia.', 'Io mi alzo e mi faccio la doccia.', 'Mi alzo e faccio la doccia.', 'Io mi alzo e faccio la doccia.'], [Kit::word('alzarsi', 'mi alzo'), Kit::word('farsi la doccia', 'mi faccio la doccia', ['faccio la doccia']), Kit::form('mi faccio la doccia', false, ['faccio la doccia'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.svegliamo-colazione', 'We wake up and we have breakfast.', ['Ci svegliamo e facciamo colazione.', 'Noi ci svegliamo e facciamo colazione.'], [Kit::word('svegliarsi', 'ci svegliamo'), Kit::word('fare colazione', 'facciamo colazione')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.fa', 'Lui ___ alle otto.', 'He has breakfast at eight.', 'fa colazione', Kit::form('fa colazione', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.svegliano', 'Loro ___ presto.', 'They wake up early.', 'si svegliano', Kit::form('si svegliano'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.alzo', 'Mi alzo tardi ogni giorno.', 'I get up late every day.', [Kit::word('alzarsi', 'mi alzo'), Kit::word('tardi'), Kit::word('ogni giorno'), Kit::form('mi alzo')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.colazione', 'Di solito faccio colazione e lavoro.', 'Normally I have breakfast and work.', [Kit::word('di solito'), Kit::word('fare colazione', 'faccio colazione'), Kit::word('lavorare', 'lavoro')], 'dictation', $set, homophoneNote: $e),
            Kit::listenType($stage, 'check.b.listen_type.doccia-letto', 'Mi faccio la doccia e vado a letto presto.', 'I shower and go to bed early.', [Kit::word('farsi la doccia', 'mi faccio la doccia'), Kit::word('andare a letto', 'vado a letto'), Kit::word('presto')], 'dictation', $set, homophoneNote: $a.' '.$e),
        ];
    }
}
