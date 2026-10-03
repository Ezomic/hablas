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

final class DescribingYourDailyRoutine implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'describing-your-daily-routine';
    }

    public function words(): array
    {
        return [
            new WordData('levantarse', cue: 'to get up', forms: ['me levanto']),
            new WordData('despertarse', cue: 'to wake up', forms: ['me despierto']),
            new WordData('ducharse', cue: 'to shower', forms: ['me ducho']),
            new WordData('desayunar', cue: 'to have breakfast', forms: ['desayuno']),
            new WordData('trabajar', cue: 'to work', forms: ['trabajo']),
            new WordData('acostarse', cue: 'to go to bed', forms: ['me acuesto']),
            new WordData('temprano', cue: 'early'),
            new WordData('tarde', cue: 'late (not early)'),
            new WordData('todos los días', cue: 'every day', accepted: ['cada día']),
            new WordData('normalmente', cue: 'normally'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Me levanto temprano.', 'english' => 'I get up early.'],
            ['text' => 'Ella se acuesta tarde.', 'english' => 'She goes to bed late.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), WordReference. Fixed: accepted cada día for todos los días. First-person distractors are finite forms, definitely wrong against infinitive cues. Open question answered and removed.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.yo-levanto', 'Yo ___ temprano.', ['me levanto', 'te levanto', 'se levanta'], 'me levanto', Kit::form('me levanto'), 'Yo goes with me: the pronoun matches the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.ella-acuesta', 'Ella ___ tarde.', ['se acuesta', 'me acuesto', 'se acuestan'], 'se acuesta', Kit::form('se acuesta'), 'Ella goes with se: the pronoun matches the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tu-levantas', 'Tú ___ a las siete.', ['te levantas', 'me levantas', 'se levantas'], 'te levantas', Kit::form('te levantas'), 'Tú goes with te: the pronoun matches the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.nosotros-despertamos', 'Nosotros ___ temprano.', ['nos despertamos', 'nos despierto', 'me despertamos'], 'nos despertamos', Kit::form('nos despertamos'), 'Nosotros goes with nos: the pronoun matches the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.yo-desayuno', 'Yo ___ a las ocho.', ['desayuno', 'me desayuno'], 'desayuno', Kit::form('desayuno', true), 'Desayunar is not reflexive, so the verb takes no pronoun.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tu-trabajas', 'Tú ___ a las nueve.', ['trabajas', 'te trabajas'], 'trabajas', Kit::form('trabajas', true), 'Trabajar is not reflexive, so the verb takes no pronoun.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.me-levanto', '___ temprano.', 'I get up early.', 'Me levanto', Kit::form('me levanto'), 'Yo goes with me: me levanto.'),
            Kit::typeGap($stage, 'sentences.type_gap.se-levanta', 'Ella ___ temprano.', 'She gets up early.', 'se levanta', Kit::form('se levanta'), 'Ella goes with se: se levanta.'),
            Kit::typeGap($stage, 'sentences.type_gap.me-despierto', 'Yo ___ a las siete.', 'I wake up at seven.', 'me despierto', Kit::form('me despierto'), 'Yo goes with me: me despierto.'),
            Kit::typeGap($stage, 'sentences.type_gap.se-ducha', 'Ella ___ a las ocho.', 'She showers at eight.', 'se ducha', Kit::form('se ducha'), 'Ella goes with se: se ducha.'),
            Kit::typeGap($stage, 'sentences.type_gap.nos-acostamos', 'Nosotros ___ tarde.', 'We go to bed late.', 'nos acostamos', Kit::form('nos acostamos'), 'Nosotros goes with nos: nos acostamos.'),

            Kit::translate($stage, 'sentences.translate.normalmente-levanto', 'Normally I get up early.', ['Normalmente me levanto temprano.', 'Me levanto temprano normalmente.', 'Normalmente yo me levanto temprano.', 'Yo normalmente me levanto temprano.', 'Yo me levanto temprano normalmente.'], [Kit::word('normalmente'), Kit::word('levantarse', 'me levanto'), Kit::word('temprano'), Kit::form('me levanto')]),
            Kit::translate($stage, 'sentences.translate.ella-acuesta', 'She goes to bed late.', ['Ella se acuesta tarde.'], [Kit::word('acostarse', 'se acuesta'), Kit::word('tarde'), Kit::form('se acuesta')]),
            Kit::translate($stage, 'sentences.translate.trabajo', 'I work at nine.', ['Trabajo a las nueve.', 'Yo trabajo a las nueve.'], [Kit::word('trabajar', 'trabajo'), Kit::form('trabajo', true)]),

            Kit::build($stage, 'sentences.build.me-despierto', 'I wake up at seven.', 'Me despierto a las siete.', ['se'], [Kit::word('despertarse', 'me despierto'), Kit::form('me despierto')]),
            Kit::build($stage, 'sentences.build.se-ducha', 'She showers every day.', 'Ella se ducha todos los días.', ['me'], [Kit::word('ducharse', 'se ducha'), Kit::word('todos los días'), Kit::form('se ducha')]),
            Kit::build($stage, 'sentences.build.nos-levantamos', 'We get up early.', 'Nos levantamos temprano.', ['se'], [Kit::word('levantarse', 'nos levantamos'), Kit::word('temprano'), Kit::form('nos levantamos')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.levanto', 'Me levanto temprano todos los días.', ['I get up early every day.', 'I go to bed early every day.', 'I get up late every day.', 'I work early every day.'], 'I get up early every day.', [Kit::word('levantarse', 'me levanto'), Kit::word('temprano'), Kit::word('todos los días'), Kit::form('me levanto')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.acuesta', 'Ella se acuesta tarde.', ['She goes to bed late.', 'She gets up late.', 'She goes to bed early.', 'I go to bed late.'], 'She goes to bed late.', [Kit::word('acostarse', 'se acuesta'), Kit::word('tarde'), Kit::form('se acuesta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.desayuno', 'Normalmente desayuno a las ocho.', ['Normally I have breakfast at eight.', 'Normally I work at eight.', 'Normally I have breakfast at seven.', 'Normally I shower at eight.'], 'Normally I have breakfast at eight.', [Kit::word('normalmente'), Kit::word('desayunar', 'desayuno')]),
            Kit::listenType($stage, 'sentences.listen_type.me-ducho', 'Me ducho temprano.', 'I shower early.', [Kit::word('ducharse', 'me ducho'), Kit::word('temprano'), Kit::form('me ducho')]),
            Kit::listenType($stage, 'sentences.listen_type.se-despierta', 'Él se despierta tarde todos los días.', 'He wakes up late every day.', [Kit::word('despertarse', 'se despierta'), Kit::word('tarde'), Kit::word('todos los días'), Kit::form('se despierta')]),
            Kit::listenType($stage, 'sentences.listen_type.trabajo', 'Normalmente trabajo con Marta.', 'Normally I work with Marta.', [Kit::word('normalmente'), Kit::word('trabajar', 'trabajo')]),
            Kit::listenType($stage, 'sentences.listen_type.nos-acostamos', 'Nos acostamos tarde.', 'We go to bed late.', [Kit::word('acostarse', 'nos acostamos'), Kit::word('tarde'), Kit::form('nos acostamos')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.normalmente-levanto', 'Normalmente me levanto temprano todos los días.', 'I normally get up early every day.', [Kit::word('normalmente'), Kit::word('levantarse', 'me levanto'), Kit::word('temprano'), Kit::word('todos los días'), Kit::form('me levanto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.se-ducha', 'Ella se ducha todos los días.', 'She showers every day.', [Kit::word('ducharse', 'se ducha'), Kit::word('todos los días'), Kit::form('se ducha')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.trabajo', 'Trabajo con Ana a las nueve.', 'I work with Ana at nine.', [Kit::word('trabajar', 'trabajo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.me-acuesto', 'Me acuesto tarde.', 'I go to bed late.', [Kit::word('acostarse', 'me acuesto'), Kit::word('tarde'), Kit::form('me acuesto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.levantas', '¿Te levantas temprano?', 'Do you get up early?', [['levanto', 'temprano', 'tarde', 'cinco', 'seis', 'siete', 'ocho', 'nueve']], 'Sí, me levanto temprano.', [Kit::word('levantarse', 'me levanto'), Kit::word('temprano'), Kit::form('me levanto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.desayunas', '¿Desayunas todos los días?', 'Do you have breakfast every day?', [['desayuno', 'días', 'todos', 'cada']], 'Sí, desayuno todos los días.', [Kit::word('desayunar', 'desayuno'), Kit::word('todos los días')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.acuestas', '¿Te acuestas tarde?', 'Do you go to bed late?', [['acuesto', 'tarde', 'temprano']], 'Sí, me acuesto tarde.', [Kit::word('acostarse', 'me acuesto'), Kit::word('tarde'), Kit::form('me acuesto')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.rutina', 'Read the conversation about daily routines.', [
                Kit::line('Ana', '¿Te levantas temprano, Pablo?'),
                Kit::line('Pablo', 'Sí, normalmente me levanto a las seis. Me ducho y desayuno.'),
                Kit::line('Ana', '¿A las seis? Yo me despierto a las siete.'),
                Kit::line('Pablo', 'Trabajo a las ocho. Me acuesto temprano todos los días.'),
                Kit::line('Ana', 'Yo me acuesto tarde.'),
            ], [
                Kit::question('What time does Pablo get up?', ['At five', 'At six', 'At seven'], 'At six'),
                Kit::question('What time does Pablo start work?', ['At seven', 'At eight', 'At nine'], 'At eight'),
                Kit::question('Who goes to bed late?', ['Pablo', 'Ana', 'Both of them'], 'Ana'),
            ], [Kit::word('levantarse', 'me levanto'), Kit::word('ducharse', 'me ducho'), Kit::word('desayunar', 'desayuno'), Kit::word('despertarse', 'me despierto'), Kit::word('trabajar', 'trabajo'), Kit::word('acostarse', 'me acuesto'), Kit::word('normalmente'), Kit::word('temprano'), Kit::word('tarde'), Kit::word('todos los días')]),
            Kit::gap($stage, 'task.choose_gap.temprano', 'Me levanto a las cinco, muy ___.', ['temprano', 'tarde'], 'temprano', Kit::word('temprano'), 'Five in the morning is early, so temprano. Late is tarde.', 'read'),
            Kit::gap($stage, 'task.choose_gap.tarde', 'Se acuesta a las doce, muy ___.', ['tarde', 'temprano'], 'tarde', Kit::word('tarde'), 'Midnight is late, so tarde. Early is temprano.', 'read'),

            Kit::transform($stage, 'task.transform.ella', 'Change the subject to she.', 'Me levanto temprano.', ['Ella se levanta temprano.'], [Kit::word('levantarse', 'se levanta'), Kit::word('temprano'), Kit::form('se levanta')]),
            Kit::transform($stage, 'task.transform.nosotros', 'Change the subject to we.', 'Me despierto a las siete.', ['Nos despertamos a las siete.', 'Nosotros nos despertamos a las siete.'], [Kit::word('despertarse', 'nos despertamos'), Kit::form('nos despertamos')]),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to you (tú).', 'Me acuesto tarde.', ['Te acuestas tarde.', 'Tú te acuestas tarde.'], [Kit::word('acostarse', 'te acuestas'), Kit::word('tarde'), Kit::form('te acuestas')]),
            Kit::writeGuided($stage, 'task.write_guided.rutina', 'Describe your daily routine. Use the words get up, have breakfast and work.', ['me levanto', 'desayuno', 'trabajo'], 'Me levanto temprano, desayuno y trabajo a las nueve.', [
                ['forms' => ['levanto', 'levantamos', 'levanta', 'levantan'], 'term' => 'levantarse'],
                ['forms' => ['desayuno', 'desayunamos', 'desayuna'], 'term' => 'desayunar'],
                ['forms' => ['trabajo', 'trabajamos', 'trabaja'], 'term' => 'trabajar'],
            ], [Kit::word('levantarse', 'me levanto'), Kit::word('desayunar', 'desayuno'), Kit::word('trabajar', 'trabajo')]),
            Kit::writeGuided($stage, 'task.write_guided.despierto', 'Say that you wake up early and go to bed late.', ['me despierto', 'temprano', 'me acuesto', 'tarde'], 'Me despierto temprano y me acuesto tarde.', [
                ['forms' => ['despierto', 'despertamos'], 'term' => 'despertarse'],
                ['forms' => ['temprano'], 'term' => 'temprano'],
                ['forms' => ['acuesto', 'acostamos'], 'term' => 'acostarse'],
                ['forms' => ['tarde'], 'term' => 'tarde'],
            ], [Kit::word('despertarse', 'me despierto'), Kit::word('temprano'), Kit::word('acostarse', 'me acuesto'), Kit::word('tarde')]),
            Kit::build($stage, 'task.build.se-levantan', 'They get up early.', 'Ellos se levantan temprano.', ['me', 'levanta'], [Kit::word('levantarse', 'se levantan'), Kit::word('temprano'), Kit::form('se levantan')], 'write'),
            Kit::build($stage, 'task.build.desayunamos', 'We have breakfast at eight.', 'Desayunamos a las ocho.', ['se', 'me'], [Kit::word('desayunar', 'desayunamos'), Kit::form('desayunamos', true)], 'write'),
            Kit::build($stage, 'task.build.despierto-ducho', 'I wake up at seven and I shower.', 'Me despierto a las siete y me ducho.', ['se', 'te'], [Kit::word('despertarse', 'me despierto'), Kit::word('ducharse', 'me ducho'), Kit::form('me ducho')], 'write'),
            Kit::translate($stage, 'task.translate.levanto-desayuno', 'I get up at six and I have breakfast at seven.', ['Me levanto a las seis y desayuno a las siete.', 'Yo me levanto a las seis y desayuno a las siete.'], [Kit::word('levantarse', 'me levanto'), Kit::word('desayunar', 'desayuno'), Kit::form('me levanto')], 'write'),
            Kit::translate($stage, 'task.translate.trabajamos', 'We normally work at nine.', ['Normalmente trabajamos a las nueve.', 'Trabajamos normalmente a las nueve.', 'Normalmente nosotros trabajamos a las nueve.', 'Nosotros normalmente trabajamos a las nueve.'], [Kit::word('normalmente'), Kit::word('trabajar', 'trabajamos'), Kit::form('trabajamos', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.rutina', [
                Kit::line('Marta', '¿Te levantas temprano, Luis?'),
                Kit::line('Luis', 'No, normalmente me levanto a las ocho. Trabajo a las diez.'),
                Kit::line('Marta', 'Yo me levanto a las seis. Me ducho y desayuno.'),
                Kit::line('Luis', '¿Y te acuestas temprano?'),
                Kit::line('Marta', 'Sí, me acuesto a las diez todos los días.'),
            ], [
                Kit::question('What time does Luis get up?', ['At six', 'At eight', 'At ten'], 'At eight'),
                Kit::question('What time does Luis start work?', ['At eight', 'At nine', 'At ten'], 'At ten'),
                Kit::question('What time does Marta go to bed?', ['At nine', 'At ten', 'At twelve'], 'At ten'),
            ], [
                Kit::question('Who gets up earlier?', ['Marta', 'Luis', 'They get up at the same time'], 'Marta'),
                Kit::question('What does Marta do after she gets up?', ['She showers and has breakfast', 'She goes to work', 'She goes to bed'], 'She showers and has breakfast'),
                Kit::question('Does Marta go to bed early?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('levantarse', 'me levanto'), Kit::word('trabajar', 'trabajo'), Kit::word('ducharse', 'me ducho'), Kit::word('desayunar', 'desayuno'), Kit::word('acostarse', 'me acuesto'), Kit::word('normalmente'), Kit::word('temprano'), Kit::word('todos los días')]),
            Kit::listenType($stage, 'task.listen_type.ducho-desayuno', 'Normalmente me ducho y desayuno.', 'Normally I shower and have breakfast.', [Kit::word('normalmente'), Kit::word('ducharse', 'me ducho'), Kit::word('desayunar', 'desayuno'), Kit::form('me ducho')]),
            Kit::listenType($stage, 'task.listen_type.ella-despierta', 'Ella se despierta tarde todos los días.', 'She wakes up late every day.', [Kit::word('despertarse', 'se despierta'), Kit::word('tarde'), Kit::word('todos los días'), Kit::form('se despierta')]),
            Kit::listenType($stage, 'task.listen_type.nos-acostamos', 'Nos acostamos temprano y nos levantamos temprano.', 'We go to bed early and we get up early.', [Kit::word('acostarse', 'nos acostamos'), Kit::word('levantarse', 'nos levantamos'), Kit::word('temprano'), Kit::form('nos levantamos')]),

            Kit::speakAnswer($stage, 'task.speak_answer.levantas', '¿Cuándo te levantas?', 'When do you get up?', [['levanto', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'temprano', 'tarde', 'normalmente']], 'Me levanto a las siete.', [Kit::word('levantarse', 'me levanto'), Kit::form('me levanto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.acuestas', '¿Cuándo te acuestas?', 'When do you go to bed?', [['acuesto', 'ocho', 'nueve', 'diez', 'once', 'doce', 'una', 'temprano', 'tarde', 'normalmente']], 'Me acuesto a las once.', [Kit::word('acostarse', 'me acuesto'), Kit::form('me acuesto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.desayunas', '¿Desayunas temprano o tarde?', 'Do you have breakfast early or late?', [['desayuno', 'temprano', 'tarde']], 'Desayuno temprano.', [Kit::word('desayunar', 'desayuno'), Kit::word('temprano'), Kit::word('tarde')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.trabajas', '¿Trabajas todos los días?', 'Do you work every day?', [['trabajo', 'trabajar', 'todos', 'cada']], 'Sí, trabajo todos los días.', [Kit::word('trabajar', 'trabajo'), Kit::word('todos los días')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.nos-despertamos', 'Nos despertamos temprano todos los días.', 'We wake up early every day.', [Kit::word('despertarse', 'nos despertamos'), Kit::word('temprano'), Kit::word('todos los días'), Kit::form('nos despertamos')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.se-ducha', 'Ella se ducha y desayuna a las siete.', 'She showers and has breakfast at seven.', [Kit::word('ducharse', 'se ducha'), Kit::word('desayunar', 'desayuna'), Kit::form('se ducha')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.despierta', 'She wakes up early and has breakfast.', ['Ella se despierta temprano y desayuna.'], [Kit::word('despertarse', 'se despierta'), Kit::word('temprano'), Kit::word('desayunar', 'desayuna'), Kit::form('se despierta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.acostamos', 'We go to bed at eleven.', ['Nos acostamos a las once.', 'Nosotros nos acostamos a las once.'], [Kit::word('acostarse', 'nos acostamos'), Kit::form('nos acostamos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.trabajo', 'Normally I work at ten.', ['Normalmente trabajo a las diez.', 'Trabajo normalmente a las diez.', 'Normalmente yo trabajo a las diez.', 'Yo normalmente trabajo a las diez.'], [Kit::word('normalmente'), Kit::word('trabajar', 'trabajo'), Kit::form('trabajo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.levantan', 'They get up late.', ['Se levantan tarde.', 'Ellos se levantan tarde.', 'Ellas se levantan tarde.'], [Kit::word('levantarse', 'se levantan'), Kit::word('tarde'), Kit::form('se levantan')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.desayunas', 'Tú ___ a las ocho.', 'You have breakfast at eight.', 'desayunas', Kit::form('desayunas', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.acuesto', 'Yo ___ temprano.', 'I go to bed early.', 'me acuesto', Kit::form('me acuesto'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.ducho', 'Me ducho todos los días.', 'I shower every day.', [Kit::word('ducharse', 'me ducho'), Kit::word('todos los días')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.desayuno', 'Normalmente desayuno tarde.', 'Normally I have breakfast late.', [Kit::word('normalmente'), Kit::word('desayunar', 'desayuno'), Kit::word('tarde')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.despertamos', 'Nos despertamos tarde todos los días.', 'We wake up late every day.', [Kit::word('despertarse', 'nos despertamos'), Kit::word('tarde'), Kit::word('todos los días')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.rutina', [
                Kit::line('Marta', 'Hola, Pablo. ¿Y tú, te levantas temprano?'),
                Kit::line('Pablo', 'Sí, me levanto a las seis y me ducho.'),
                Kit::line('Marta', 'Yo trabajo a las diez.'),
            ], [
                Kit::question('What time does Pablo get up?', ['At five', 'At six', 'At seven'], 'At six'),
                Kit::question('What time does Marta work?', ['At eight', 'At nine', 'At ten'], 'At ten'),
                Kit::question('What does Pablo do after he gets up?', ['He showers', 'He has breakfast', 'He goes to bed'], 'He showers'),
            ], [
                Kit::question('Who asks about getting up?', ['Marta', 'Pablo', 'Nobody'], 'Marta'),
                Kit::question('Does Pablo get up early?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('levantarse', 'me levanto'), Kit::word('ducharse', 'me ducho'), Kit::word('trabajar', 'trabajo'), Kit::word('temprano')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.rutina', 'Read the conversation.', [
                Kit::line('Ana', '¿Te acuestas tarde, Luis?'),
                Kit::line('Luis', 'No, me acuesto a las diez. ¿Y tú?'),
                Kit::line('Ana', 'Yo me acuesto a las doce. Es muy tarde.'),
            ], [
                Kit::question('What time does Luis go to bed?', ['At nine', 'At ten', 'At eleven'], 'At ten'),
                Kit::question('Who goes to bed later?', ['Luis', 'Ana', 'The text does not say.'], 'Ana'),
            ], [Kit::word('acostarse', 'me acuesto'), Kit::word('tarde')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.levantas', '¿Te levantas temprano o tarde?', 'Do you get up early or late?', [['levanto', 'temprano', 'tarde']], 'Me levanto temprano.', [Kit::word('levantarse', 'me levanto'), Kit::word('temprano')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.desayunas', '¿Cuándo desayunas?', 'When do you have breakfast?', [['desayuno', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'temprano', 'tarde', 'normalmente']], 'Desayuno a las ocho.', [Kit::word('desayunar', 'desayuno')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.acuestas', '¿Te acuestas temprano?', 'Do you go to bed early?', [['acuesto', 'temprano', 'tarde']], 'No, me acuesto tarde.', [Kit::word('acostarse', 'me acuesto'), Kit::word('temprano')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.levantamos', 'We normally get up early.', ['Normalmente nos levantamos temprano.', 'Nos levantamos temprano normalmente.', 'Normalmente nosotros nos levantamos temprano.', 'Nosotros normalmente nos levantamos temprano.'], [Kit::word('normalmente'), Kit::word('levantarse', 'nos levantamos'), Kit::word('temprano'), Kit::form('nos levantamos')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.trabaja', 'She works late.', ['Ella trabaja tarde.', 'Trabaja tarde.'], [Kit::word('trabajar', 'trabaja'), Kit::word('tarde'), Kit::form('trabaja', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.despierto', 'I wake up and I shower.', ['Me despierto y me ducho.', 'Yo me despierto y me ducho.'], [Kit::word('despertarse', 'me despierto'), Kit::word('ducharse', 'me ducho'), Kit::form('me ducho')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.desayuna', 'She has breakfast early.', ['Ella desayuna temprano.', 'Desayuna temprano.'], [Kit::word('desayunar', 'desayuna'), Kit::word('temprano')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.desayuna', 'Él ___ a las ocho.', 'He has breakfast at eight.', 'desayuna', Kit::form('desayuna', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.acuestan', 'Ellos ___ temprano.', 'They go to bed early.', 'se acuestan', Kit::form('se acuestan'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.levanto', 'Me levanto tarde todos los días.', 'I get up late every day.', [Kit::word('levantarse', 'me levanto'), Kit::word('tarde'), Kit::word('todos los días')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ducho', 'Normalmente me ducho y me acuesto temprano.', 'Normally I shower and go to bed early.', [Kit::word('normalmente'), Kit::word('ducharse', 'me ducho'), Kit::word('acostarse', 'me acuesto'), Kit::word('temprano'), Kit::form('me acuesto')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.trabajo', 'Me despierto y trabajo todos los días.', 'I wake up and I work every day.', [Kit::word('despertarse', 'me despierto'), Kit::word('trabajar', 'trabajo'), Kit::word('todos los días')], 'dictation', $set),
        ];
    }
}
