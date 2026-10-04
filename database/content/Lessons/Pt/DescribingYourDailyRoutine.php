<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DescribingYourDailyRoutine implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'describing-your-daily-routine';
    }

    public function words(): array
    {
        return [
            new WordData('levantar-se', cue: 'to get up', forms: ['levanto-me', 'levantas-te', 'levanta-se', 'levantamo-nos', 'levantam-se', 'levantamos'], portunolSlips: ['levantarse'], questions: ['Is "Normalmente levanto-me cedo" (pronoun after the verb, following the sentence-initial adverb normalmente) the form to teach, or do people in Portugal also say "Normalmente me levanto"? The lessons accept only the enclitic form after normalmente, and the proclitic form after não and também.']),
            new WordData('acordar', cue: 'to wake up', forms: ['acordo', 'acordas', 'acorda', 'acordamos', 'acordam'], portunolSlips: ['despertarse'], questions: ['Should "acordar" also accept "despertar" or "despertar-se" as a typed answer for "to wake up", or is "acordar" (not reflexive) the only form to teach at A1?']),
            new WordData('tomar duche', cue: 'to take a shower', accepted: ['tomar um duche', 'tomar banho', 'tomar um banho'], forms: ['tomo duche', 'tomas duche', 'toma duche', 'tomamos duche', 'tomam duche'], portunolSlips: ['ducharse'], questions: ['Is "tomar duche" the natural everyday phrase in Portugal, and are "tomar um duche", "tomar banho" and "tomar um banho" all fair accepted answers for "to take a shower"? Translations of "I shower" accept tomo banho and tomo um duche.']),
            new WordData('tomar o pequeno-almoço', cue: 'to have breakfast', accepted: ['tomar pequeno-almoço'], forms: ['tomo o pequeno-almoço', 'tomas o pequeno-almoço', 'toma o pequeno-almoço', 'tomamos o pequeno-almoço', 'tomam o pequeno-almoço'], portunolSlips: ['desayunar'], questions: ['Is "tomar o pequeno-almoço" the right phrase to teach for "to have breakfast", and is the article-less "tomar pequeno-almoço" acceptable as a typed answer?']),
            new WordData('trabalhar', cue: 'to work', forms: ['trabalho', 'trabalhas', 'trabalha', 'trabalhamos', 'trabalham'], portunolSlips: ['trabajar']),
            new WordData('deitar-se', cue: 'to go to bed', forms: ['deito-me', 'deitas-te', 'deita-se', 'deitamo-nos', 'deitam-se'], portunolSlips: ['acostarse'], questions: ['Is "deitar-se" the right A1 verb for "to go to bed" in Portugal, or would learners hear "ir para a cama" or "ir dormir" more often? Is "Deito-me tarde" natural for "I go to bed late"?']),
            new WordData('cedo', cue: 'early', portunolSlips: ['temprano']),
            new WordData('tarde', cue: 'late (not early)'),
            new WordData('todos os dias', cue: 'every day', accepted: ['cada dia'], portunolSlips: ['todos los días'], questions: ['Is "cada dia" an acceptable answer for "every day", or does it mean something slightly different (each day) that should not be accepted as a typed answer?']),
            new WordData('normalmente', cue: 'normally'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Levanto-me cedo.', 'english' => 'I get up early.'],
            ['text' => 'Não me deito tarde.', 'english' => 'I do not go to bed late.'],
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
        return [];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.eu-levanto', 'Eu ___ cedo.', ['levanto-me', 'levantas-te', 'levanta-se'], 'levanto-me', Kit::form('levanto-me'), 'Eu goes with me, and the pronoun comes after the verb with a hyphen: levanto-me.', 'choose', 'I get up early.'),
            Kit::gap($stage, 'sentences.choose_gap.ela-deita', 'Ela ___ tarde.', ['deita-se', 'deito-me', 'deitam-se'], 'deita-se', Kit::form('deita-se'), 'Ela goes with se, after the verb: deita-se.', 'choose', 'She goes to bed late.'),
            Kit::gap($stage, 'sentences.choose_gap.tu-levantas', 'Tu ___ às sete.', ['levantas-te', 'levanto-me', 'levanta-se'], 'levantas-te', Kit::form('levantas-te'), 'Tu goes with te, after the verb: levantas-te.', 'choose', 'You get up at seven.'),
            Kit::gap($stage, 'sentences.choose_gap.nos-deitamos', 'Nós ___ tarde.', ['deitamo-nos', 'deitam-se', 'deito-me'], 'deitamo-nos', Kit::form('deitamo-nos'), 'Nós goes with nos, after the verb, and the verb loses its s: deitamo-nos.', 'choose', 'We go to bed late.'),
            Kit::gap($stage, 'sentences.choose_gap.eu-duche', 'Eu ___ às oito.', ['tomo duche', 'tomo-me duche', 'tomas duche'], 'tomo duche', Kit::form('tomo duche', true), 'Taking a shower is tomar duche, with no pronoun. Spanish ducharse is reflexive, but Portuguese is not.', 'choose', 'I take a shower at eight.'),
            Kit::gap($stage, 'sentences.choose_gap.eu-nao-levanto', 'Eu não me ___ cedo.', ['levanto', 'levanto-me', 'levantas'], 'levanto', Kit::form('levanto', true), 'After não the pronoun goes before the verb, so the verb stands alone: não me levanto, never não me levanto-me.', 'choose', 'I do not get up early.'),

            Kit::typeGap($stage, 'sentences.type_gap.levanto-sete', '___ às sete.', 'I get up at seven.', 'Levanto-me', Kit::form('levanto-me'), 'In a plain sentence the pronoun follows the verb: levanto-me, not me levanto.'),
            Kit::typeGap($stage, 'sentences.type_gap.ela-levanta', 'Ela ___ cedo.', 'She gets up early.', 'levanta-se', Kit::form('levanta-se'), 'Ela goes with se, after the verb: levanta-se.'),
            Kit::typeGap($stage, 'sentences.type_gap.eu-acordo', 'Eu ___ às sete.', 'I wake up at seven.', 'acordo', Kit::form('acordo', true), 'Acordar is not reflexive in Portugal, so the verb takes no pronoun. Spanish despertarse is reflexive.'),
            Kit::typeGap($stage, 'sentences.type_gap.ela-pequeno-almoco', 'Ela ___ às oito.', 'She has breakfast at eight.', 'toma o pequeno-almoço', Kit::form('toma o pequeno-almoço', true), 'Having breakfast is tomar o pequeno-almoço, a phrase with no pronoun. Spanish desayunar is a single verb, while Portuguese usually says the phrase tomar o pequeno-almoço.'),
            Kit::typeGap($stage, 'sentences.type_gap.eles-deitam', 'Eles ___ tarde.', 'They go to bed late.', 'deitam-se', Kit::form('deitam-se'), 'Eles goes with se, after the verb: deitam-se.'),

            Kit::translate($stage, 'sentences.translate.normalmente-levanto', 'Normally I get up early.', ['Normalmente levanto-me cedo.', 'Normalmente eu levanto-me cedo.', 'Eu normalmente levanto-me cedo.', 'Levanto-me cedo normalmente.', 'Eu levanto-me cedo normalmente.'], [Kit::word('normalmente'), Kit::word('levantar-se', 'levanto-me'), Kit::word('cedo'), Kit::form('levanto-me')]),
            Kit::translate($stage, 'sentences.translate.ela-deita', 'She goes to bed late.', ['Ela deita-se tarde.'], [Kit::word('deitar-se', 'deita-se'), Kit::word('tarde'), Kit::form('deita-se')]),
            Kit::translate($stage, 'sentences.translate.trabalho', 'I work at nine.', ['Trabalho às nove.', 'Eu trabalho às nove.'], [Kit::word('trabalhar', 'trabalho')]),

            Kit::build($stage, 'sentences.build.acordo', 'I wake up at seven.', 'Acordo às sete.', ['acordo-me'], [Kit::word('acordar', 'acordo'), Kit::form('acordo', true)]),
            Kit::build($stage, 'sentences.build.ela-duche', 'She showers every day.', 'Ela toma duche todos os dias.', ['se'], [Kit::word('tomar duche', 'toma duche'), Kit::word('todos os dias'), Kit::form('toma duche', true)]),
            Kit::build($stage, 'sentences.build.nao-nos-levantamos', 'We do not get up early.', 'Nós não nos levantamos cedo.', ['levantamo-nos'], [Kit::word('levantar-se', 'levantamos'), Kit::word('cedo'), Kit::form('não nos levantamos')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.levanto-cedo', 'Levanto-me cedo todos os dias.', ['I get up early every day.', 'I go to bed early every day.', 'I get up late every day.', 'I work early every day.'], 'I get up early every day.', [Kit::word('levantar-se', 'levanto-me'), Kit::word('cedo'), Kit::word('todos os dias'), Kit::form('levanto-me')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ele-deita', 'Ele deita-se tarde.', ['He goes to bed late.', 'He gets up late.', 'He goes to bed early.', 'I go to bed late.'], 'He goes to bed late.', [Kit::word('deitar-se', 'deita-se'), Kit::word('tarde'), Kit::form('deita-se')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pequeno-almoco', 'Normalmente tomo o pequeno-almoço às oito.', ['Normally I have breakfast at eight.', 'Normally I work at eight.', 'Normally I have breakfast at seven.', 'Normally I shower at eight.'], 'Normally I have breakfast at eight.', [Kit::word('normalmente'), Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço')]),
            Kit::listenType($stage, 'sentences.listen_type.tomo-duche', 'Tomo duche cedo.', 'I shower early.', [Kit::word('tomar duche', 'tomo duche'), Kit::word('cedo'), Kit::form('tomo duche', true)]),
            Kit::listenType($stage, 'sentences.listen_type.ele-acorda', 'Ele acorda tarde todos os dias.', 'He wakes up late every day.', [Kit::word('acordar', 'acorda'), Kit::word('tarde'), Kit::word('todos os dias'), Kit::form('acorda', true)]),
            Kit::listenType($stage, 'sentences.listen_type.trabalho', 'Normalmente trabalho com o Rui.', 'Normally I work with Rui.', [Kit::word('normalmente'), Kit::word('trabalhar', 'trabalho')]),
            Kit::listenType($stage, 'sentences.listen_type.deitamo-nos', 'Deitamo-nos tarde.', 'We go to bed late.', [Kit::word('deitar-se', 'deitamo-nos'), Kit::word('tarde'), Kit::form('deitamo-nos')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.normalmente-levanto', 'Normalmente levanto-me cedo todos os dias.', 'I normally get up early every day.', [Kit::word('normalmente'), Kit::word('levantar-se', 'levanto-me'), Kit::word('cedo'), Kit::word('todos os dias'), Kit::form('levanto-me')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tomo-duche', 'Tomo duche todos os dias.', 'I shower every day.', [Kit::word('tomar duche', 'tomo duche'), Kit::word('todos os dias'), Kit::form('tomo duche', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.trabalho', 'Trabalho com a Ana às nove.', 'I work with Ana at nine.', [Kit::word('trabalhar', 'trabalho')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.deito-me', 'Deito-me tarde.', 'I go to bed late.', [Kit::word('deitar-se', 'deito-me'), Kit::word('tarde'), Kit::form('deito-me')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.levantas', 'Levantas-te cedo?', 'Do you get up early?', [['levanto', 'cedo', 'tarde', 'cinco', 'seis', 'sete', 'oito', 'nove']], 'Sim, levanto-me cedo.', [Kit::word('levantar-se', 'levanto-me'), Kit::word('cedo'), Kit::form('levanto-me')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pequeno-almoco', 'Tomas o pequeno-almoço todos os dias?', 'Do you have breakfast every day?', [['tomo', 'dias', 'todos', 'cada']], 'Sim, tomo o pequeno-almoço todos os dias.', [Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::word('todos os dias')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.deitas', 'Deitas-te tarde?', 'Do you go to bed late?', [['deito', 'tarde', 'cedo']], 'Sim, deito-me tarde.', [Kit::word('deitar-se', 'deito-me'), Kit::word('tarde'), Kit::form('deito-me')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.rotina', 'Read the conversation about daily routines.', [
                Kit::line('Ana', 'Levantas-te cedo, Rui?'),
                Kit::line('Rui', 'Sim. Normalmente levanto-me às seis. Tomo duche e tomo o pequeno-almoço.'),
                Kit::line('Ana', 'Às seis? Eu acordo às sete.'),
                Kit::line('Rui', 'Trabalho às oito. Deito-me cedo todos os dias.'),
                Kit::line('Ana', 'Eu deito-me tarde.'),
            ], [
                Kit::question('What time does Rui get up?', ['At five', 'At six', 'At seven'], 'At six'),
                Kit::question('What time does Rui start work?', ['At seven', 'At eight', 'At nine'], 'At eight'),
                Kit::question('Who goes to bed late?', ['Rui', 'Ana', 'Both of them'], 'Ana'),
            ], [Kit::word('levantar-se', 'levanto-me'), Kit::word('tomar duche', 'tomo duche'), Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::word('acordar', 'acordo'), Kit::word('trabalhar', 'trabalho'), Kit::word('deitar-se', 'deito-me'), Kit::word('normalmente'), Kit::word('cedo'), Kit::word('tarde'), Kit::word('todos os dias')]),
            Kit::gap($stage, 'task.choose_gap.cedo', 'Levanto-me às cinco, muito ___.', ['cedo', 'tarde'], 'cedo', Kit::word('cedo'), 'Five in the morning is early, so cedo. Late is tarde.', 'read'),
            Kit::gap($stage, 'task.choose_gap.tarde', 'Deita-se às doze, muito ___.', ['tarde', 'cedo'], 'tarde', Kit::word('tarde'), 'Twelve at night is late, so tarde. Early is cedo.', 'read'),

            Kit::transform($stage, 'task.transform.ela', 'Change the subject to she.', 'Levanto-me cedo.', ['Ela levanta-se cedo.'], [Kit::word('levantar-se', 'levanta-se'), Kit::word('cedo'), Kit::form('levanta-se')]),
            Kit::transform($stage, 'task.transform.nos', 'Change the subject to we.', 'Acordo às sete.', ['Acordamos às sete.', 'Nós acordamos às sete.'], [Kit::word('acordar', 'acordamos'), Kit::form('acordamos', true)]),
            Kit::transform($stage, 'task.transform.negativo', 'Make the sentence negative.', 'Deito-me tarde.', ['Não me deito tarde.', 'Eu não me deito tarde.'], [Kit::word('deitar-se', 'deito'), Kit::word('tarde'), Kit::form('não me deito', true)]),
            Kit::writeGuided($stage, 'task.write_guided.rotina', 'Describe your daily routine. Use the words get up, have breakfast and work.', ['levanto-me', 'tomo o pequeno-almoço', 'trabalho'], 'Levanto-me cedo, tomo o pequeno-almoço e trabalho às nove.', [
                ['forms' => ['levanto', 'levantas', 'levanta', 'levantamo', 'levantamos', 'levantam'], 'term' => 'levantar-se'],
                ['forms' => ['pequeno', 'almoço'], 'term' => 'tomar o pequeno-almoço'],
                ['forms' => ['trabalho', 'trabalhas', 'trabalha', 'trabalhamos', 'trabalham'], 'term' => 'trabalhar'],
            ], [Kit::word('levantar-se', 'levanto-me'), Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::word('trabalhar', 'trabalho')]),
            Kit::writeGuided($stage, 'task.write_guided.acordo', 'Say that you wake up early and go to bed late.', ['acordo', 'cedo', 'deito-me', 'tarde'], 'Acordo cedo e deito-me tarde.', [
                ['forms' => ['acordo', 'acordas', 'acorda', 'acordamos', 'acordam'], 'term' => 'acordar'],
                ['forms' => ['cedo'], 'term' => 'cedo'],
                ['forms' => ['deito', 'deitas', 'deita', 'deitamo', 'deitam'], 'term' => 'deitar-se'],
                ['forms' => ['tarde'], 'term' => 'tarde'],
            ], [Kit::word('acordar', 'acordo'), Kit::word('cedo'), Kit::word('deitar-se', 'deito-me'), Kit::word('tarde')]),
            Kit::build($stage, 'task.build.nao-se-levantam', 'They do not get up early.', 'Eles não se levantam cedo.', ['levantam-se', 'me'], [Kit::word('levantar-se', 'levantam'), Kit::word('cedo'), Kit::form('não se levantam')], 'write'),
            Kit::build($stage, 'task.build.tomamos-duche', 'We take a shower at eight.', 'Tomamos duche às oito.', ['nos', 'tomam'], [Kit::word('tomar duche', 'tomamos duche'), Kit::form('tomamos duche', true)], 'write'),
            Kit::build($stage, 'task.build.acordo-duche', 'I wake up at seven and I take a shower.', 'Acordo às sete e tomo duche.', ['me', 'acordo-me'], [Kit::word('acordar', 'acordo'), Kit::word('tomar duche', 'tomo duche'), Kit::form('acordo', true)], 'write'),
            Kit::translate($stage, 'task.translate.levanto-pequeno-almoco', 'I get up at six and I have breakfast at seven.', ['Levanto-me às seis e tomo o pequeno-almoço às sete.', 'Eu levanto-me às seis e tomo o pequeno-almoço às sete.'], [Kit::word('levantar-se', 'levanto-me'), Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::form('levanto-me')], 'write'),
            Kit::translate($stage, 'task.translate.trabalhamos', 'We normally work at nine.', ['Normalmente trabalhamos às nove.', 'Trabalhamos normalmente às nove.', 'Normalmente nós trabalhamos às nove.', 'Nós normalmente trabalhamos às nove.', 'Nós trabalhamos normalmente às nove.'], [Kit::word('normalmente'), Kit::word('trabalhar', 'trabalhamos')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.rotina', [
                Kit::line('Marta', 'Levantas-te cedo, João?'),
                Kit::line('João', 'Não. Normalmente levanto-me às oito. Trabalho às dez.'),
                Kit::line('Marta', 'Eu levanto-me às seis e tomo duche. Tomo o pequeno-almoço às sete.'),
                Kit::line('João', 'E deitas-te cedo?'),
                Kit::line('Marta', 'Sim, deito-me às dez todos os dias.'),
            ], [
                Kit::question('What time does João get up?', ['At six', 'At eight', 'At ten'], 'At eight'),
                Kit::question('What time does João start work?', ['At eight', 'At nine', 'At ten'], 'At ten'),
                Kit::question('What time does Marta go to bed?', ['At nine', 'At ten', 'At twelve'], 'At ten'),
            ], [
                Kit::question('Who gets up earlier?', ['Marta', 'João', 'They get up at the same time'], 'Marta'),
                Kit::question('What does Marta do at seven?', ['She has breakfast', 'She takes a shower', 'She goes to bed'], 'She has breakfast'),
                Kit::question('Does Marta go to bed early?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('levantar-se', 'levanto-me'), Kit::word('trabalhar', 'trabalho'), Kit::word('tomar duche', 'tomo duche'), Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::word('deitar-se', 'deito-me'), Kit::word('normalmente'), Kit::word('cedo'), Kit::word('todos os dias')]),
            Kit::listenType($stage, 'task.listen_type.duche-pequeno-almoco', 'Normalmente tomo duche e tomo o pequeno-almoço.', 'Normally I take a shower and have breakfast.', [Kit::word('normalmente'), Kit::word('tomar duche', 'tomo duche'), Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::form('tomo duche', true)]),
            Kit::listenType($stage, 'task.listen_type.ela-acorda', 'Ela acorda às oito e deita-se tarde todos os dias.', 'She wakes up at eight and goes to bed late every day.', [Kit::word('acordar', 'acorda'), Kit::word('deitar-se', 'deita-se'), Kit::word('tarde'), Kit::word('todos os dias'), Kit::form('deita-se')], homophoneNote: 'The word before oito is às (at), not as (the).'),
            Kit::listenType($stage, 'task.listen_type.deitamo-nos', 'Nós deitamo-nos cedo e levantamo-nos cedo.', 'We go to bed early and we get up early.', [Kit::word('deitar-se', 'deitamo-nos'), Kit::word('levantar-se', 'levantamo-nos'), Kit::word('cedo'), Kit::form('levantamo-nos')]),

            Kit::speakAnswer($stage, 'task.speak_answer.levantas', 'Quando te levantas?', 'When do you get up?', [['levanto', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove', 'dez', 'cedo', 'tarde', 'normalmente']], 'Levanto-me às sete.', [Kit::word('levantar-se', 'levanto-me'), Kit::form('levanto-me')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.deitas', 'Quando te deitas?', 'When do you go to bed?', [['deito', 'oito', 'nove', 'dez', 'onze', 'doze', 'cedo', 'tarde', 'normalmente']], 'Deito-me às onze.', [Kit::word('deitar-se', 'deito-me'), Kit::form('deito-me')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pequeno-almoco', 'Tomas o pequeno-almoço cedo ou tarde?', 'Do you have breakfast early or late?', [['tomo', 'cedo', 'tarde']], 'Tomo o pequeno-almoço cedo.', [Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::word('cedo'), Kit::word('tarde')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.trabalhas', 'Trabalhas todos os dias?', 'Do you work every day?', [['trabalho', 'trabalhar', 'todos', 'dias', 'cada']], 'Sim, trabalho todos os dias.', [Kit::word('trabalhar', 'trabalho'), Kit::word('todos os dias')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.acordamos', 'Acordamos cedo todos os dias.', 'We wake up early every day.', [Kit::word('acordar', 'acordamos'), Kit::word('cedo'), Kit::word('todos os dias'), Kit::form('acordamos', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ela-duche', 'Ela toma duche e toma o pequeno-almoço às sete.', 'She takes a shower and has breakfast at seven.', [Kit::word('tomar duche', 'toma duche'), Kit::word('tomar o pequeno-almoço', 'toma o pequeno-almoço'), Kit::form('toma duche', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.acorda', 'She wakes up early and has breakfast.', ['Ela acorda cedo e toma o pequeno-almoço.'], [Kit::word('acordar', 'acorda'), Kit::word('cedo'), Kit::word('tomar o pequeno-almoço', 'toma o pequeno-almoço'), Kit::form('acorda', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.deitamos', 'We go to bed at eleven.', ['Deitamo-nos às onze.', 'Nós deitamo-nos às onze.'], [Kit::word('deitar-se', 'deitamo-nos'), Kit::form('deitamo-nos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.trabalho', 'Normally I work at ten.', ['Normalmente trabalho às dez.', 'Trabalho normalmente às dez.', 'Normalmente eu trabalho às dez.', 'Eu normalmente trabalho às dez.', 'Eu trabalho normalmente às dez.'], [Kit::word('normalmente'), Kit::word('trabalhar', 'trabalho')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.levantam', 'They do not get up late.', ['Eles não se levantam tarde.', 'Elas não se levantam tarde.'], [Kit::word('levantar-se', 'levantam'), Kit::word('tarde'), Kit::form('não se levantam', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.tomas', 'Tu ___ às oito.', 'You have breakfast at eight.', 'tomas o pequeno-almoço', Kit::form('tomas o pequeno-almoço', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.deito', 'Eu ___ cedo.', 'I go to bed early.', 'deito-me', Kit::form('deito-me'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.tomamos-duche', 'Nós tomamos duche todos os dias.', 'We take a shower every day.', [Kit::word('tomar duche', 'tomamos duche'), Kit::word('todos os dias'), Kit::form('tomamos duche', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.pequeno-almoco', 'Normalmente tomo o pequeno-almoço tarde.', 'Normally I have breakfast late.', [Kit::word('normalmente'), Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço'), Kit::word('tarde')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.acordamos', 'Acordamos tarde todos os dias.', 'We wake up late every day.', [Kit::word('acordar', 'acordamos'), Kit::word('tarde'), Kit::word('todos os dias')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.rotina', [
                Kit::line('Marta', 'Olá, João. E tu, levantas-te cedo?'),
                Kit::line('João', 'Sim, levanto-me às cinco e tomo duche.'),
                Kit::line('Marta', 'Eu trabalho às dez.'),
            ], [
                Kit::question('What time does João get up?', ['At five', 'At six', 'At seven'], 'At five'),
                Kit::question('What time does Marta work?', ['At eight', 'At nine', 'At ten'], 'At ten'),
                Kit::question('What does João do after he gets up?', ['He takes a shower', 'He has breakfast', 'He goes to bed'], 'He takes a shower'),
            ], [
                Kit::question('Who asks about getting up?', ['Marta', 'João', 'Nobody'], 'Marta'),
                Kit::question('Does João get up early?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('levantar-se', 'levanto-me'), Kit::word('tomar duche', 'tomo duche'), Kit::word('trabalhar', 'trabalho'), Kit::word('cedo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.rotina', 'Read the conversation.', [
                Kit::line('Ana', 'Deitas-te tarde, Rui?'),
                Kit::line('Rui', 'Não. Deito-me às dez. E tu?'),
                Kit::line('Ana', 'Eu deito-me às doze. É muito tarde.'),
            ], [
                Kit::question('What time does Rui go to bed?', ['At nine', 'At ten', 'At eleven'], 'At ten'),
                Kit::question('Who goes to bed later?', ['Rui', 'Ana', 'The text does not say.'], 'Ana'),
            ], [Kit::word('deitar-se', 'deito-me'), Kit::word('tarde')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.levantas', 'Levantas-te cedo ou tarde?', 'Do you get up early or late?', [['levanto', 'cedo', 'tarde']], 'Levanto-me cedo.', [Kit::word('levantar-se', 'levanto-me'), Kit::word('cedo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pequeno-almoco', 'Quando tomas o pequeno-almoço?', 'When do you have breakfast?', [['tomo', 'seis', 'sete', 'oito', 'nove', 'dez', 'cedo', 'tarde', 'normalmente']], 'Tomo o pequeno-almoço às oito.', [Kit::word('tomar o pequeno-almoço', 'tomo o pequeno-almoço')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.deitas', 'Deitas-te cedo?', 'Do you go to bed early?', [['deito', 'cedo', 'tarde']], 'Não. Deito-me tarde.', [Kit::word('deitar-se', 'deito-me'), Kit::word('cedo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.levantamos', 'We normally get up early.', ['Normalmente levantamo-nos cedo.', 'Levantamo-nos cedo normalmente.', 'Normalmente nós levantamo-nos cedo.', 'Nós normalmente levantamo-nos cedo.', 'Nós levantamo-nos cedo normalmente.'], [Kit::word('normalmente'), Kit::word('levantar-se', 'levantamo-nos'), Kit::word('cedo'), Kit::form('levantamo-nos')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.trabalha', 'She works late.', ['Ela trabalha tarde.'], [Kit::word('trabalhar', 'trabalha'), Kit::word('tarde')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.acordo-duche', 'I wake up and I take a shower.', ['Acordo e tomo duche.', 'Acordo e tomo banho.', 'Acordo e tomo um duche.', 'Acordo e tomo um banho.', 'Eu acordo e tomo duche.', 'Eu acordo e tomo banho.', 'Eu acordo e tomo um duche.', 'Eu acordo e tomo um banho.'], [Kit::word('acordar', 'acordo'), Kit::word('tomar duche', 'tomo'), Kit::form('acordo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pequeno-almoco', 'She has breakfast early.', ['Ela toma o pequeno-almoço cedo.'], [Kit::word('tomar o pequeno-almoço', 'toma o pequeno-almoço'), Kit::word('cedo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.toma', 'Ele ___ duche às oito.', 'He takes a shower at eight.', 'toma', Kit::form('toma', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.deitam', 'Eles ___ às onze.', 'They go to bed at eleven.', 'deitam-se', Kit::form('deitam-se'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.levanto-tarde', 'Levanto-me tarde todos os dias.', 'I get up late every day.', [Kit::word('levantar-se', 'levanto-me'), Kit::word('tarde'), Kit::word('todos os dias'), Kit::form('levanto-me')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.tambem-deito', 'Também me deito tarde.', 'I go to bed late too.', [Kit::word('deitar-se', 'deito'), Kit::word('tarde'), Kit::form('também me deito', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.acordo-trabalho', 'Acordo e trabalho todos os dias.', 'I wake up and I work every day.', [Kit::word('acordar', 'acordo'), Kit::word('trabalhar', 'trabalho'), Kit::word('todos os dias')], 'dictation', $set),
        ];
    }
}
