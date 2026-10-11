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

final class DoingThings implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'doing-things';
    }

    public function words(): array
    {
        return [
            new WordData('fazer', cue: 'to do / to make', forms: ['faço', 'fazes', 'faz', 'fazemos', 'fazem'], portunolSlips: ['hacer']),
            new WordData('ver', cue: 'to see', forms: ['vejo', 'vês', 'vê', 'vemos', 'veem']),
            new WordData('vir', cue: 'to come', forms: ['venho', 'vens', 'vem', 'vimos', 'vêm'], portunolSlips: ['venir']),
            new WordData('saber', cue: 'to know (a fact)', forms: ['sei', 'sabes', 'sabe', 'sabemos', 'sabem'], portunolSlips: ['sé'], note: 'Saber is to know a fact: Não sei onde está a Ana.'),
            new WordData('sair', cue: 'to leave / to go out', forms: ['saio', 'sais', 'sai', 'saímos', 'saem'], portunolSlips: ['salir']),
            new WordData('pôr', cue: 'to put', forms: ['ponho', 'pões', 'põe', 'pomos', 'põem'], portunolSlips: ['poner'], note: 'Pôr has a circumflex to tell it apart from por (for, by).'),
            new WordData('dizer', cue: 'to say / to tell', forms: ['digo', 'dizes', 'diz', 'dizemos', 'dizem'], portunolSlips: ['decir']),
            new WordData('dar', cue: 'to give', forms: ['dou', 'dás', 'dá', 'damos', 'dão']),
            new WordData('trazer', cue: 'to bring', forms: ['trago', 'trazes', 'traz', 'trazemos', 'trazem'], portunolSlips: ['traer']),
            new WordData('ouvir', cue: 'to hear', forms: ['ouço', 'ouves', 'ouve', 'ouvimos', 'ouvem'], portunolSlips: ['oír', 'oir']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Saio às oito.', 'english' => 'I leave at eight.'],
            ['text' => 'Não sei onde está a Ana.', 'english' => 'I do not know where Ana is.'],
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
            Kit::gap($stage, 'sentences.choose_gap.vejo', 'Eu ___ a Ana.', ['vejo', 'vês', 'vê'], 'vejo', Kit::form('vejo', true), 'Eu goes with vejo, which is irregular. Vês is for tu and vê is for ele or ela.', 'choose', 'I see Ana.'),
            Kit::gap($stage, 'sentences.choose_gap.venho', 'Eu ___ com o João.', ['venho', 'vem'], 'venho', Kit::form('venho', true), 'Eu goes with venho, which is irregular. Vem is for ele or ela.', 'choose', 'I come with João.'),
            Kit::gap($stage, 'sentences.choose_gap.sabes', 'Tu ___ onde está a Ana?', ['sabes', 'sei'], 'sabes', Kit::form('sabes', true), 'Tu goes with sabes, which is regular. Sei is only for eu.', 'choose', 'Do you know where Ana is?'),
            Kit::gap($stage, 'sentences.choose_gap.sai', 'A Ana ___ às sete.', ['sai', 'saio'], 'sai', Kit::word('sair', 'sai'), 'Ana is ela, so the verb is sai. Saio is only for eu.', 'choose', 'Ana leaves at seven.'),
            Kit::gap($stage, 'sentences.choose_gap.dou', 'Eu ___ o livro à Ana.', ['dou', 'dá'], 'dou', Kit::word('dar', 'dou'), 'Eu goes with dou. Dá is for ele or ela.', 'choose', 'I give the book to Ana.', ['livro' => 'book']),
            Kit::gap($stage, 'sentences.choose_gap.ouco', 'Eu não ___ bem.', ['ouço', 'ouve'], 'ouço', Kit::word('ouvir', 'ouço'), 'Eu goes with ouço, the irregular form of ouvir, written with a ç. Ouve is for ele or ela.', 'choose', 'I do not hear well.'),

            Kit::typeGap($stage, 'sentences.type_gap.saio', 'Eu ___ às oito.', 'I leave at eight.', 'saio', Kit::form('saio'), 'Eu goes with saio, which is irregular. Sais is for tu.'),
            Kit::typeGap($stage, 'sentences.type_gap.trago', 'Eu ___ a Marta aqui.', 'I bring Marta here.', 'trago', Kit::word('trazer', 'trago'), 'Eu goes with trago, the irregular form of trazer.'),
            Kit::typeGap($stage, 'sentences.type_gap.ponho', 'Eu ___ dois aqui.', 'I put two here.', 'ponho', Kit::form('ponho'), 'Eu goes with ponho, which is irregular. Pões is for tu.'),
            Kit::typeGap($stage, 'sentences.type_gap.digo', 'Eu ___ olá ao Rui.', 'I say hello to Rui.', 'digo', Kit::word('dizer', 'digo'), 'Eu goes with digo, the irregular form of dizer.'),
            Kit::typeGap($stage, 'sentences.type_gap.faz', 'O que ___ a Ana?', 'What does Ana do?', 'faz', Kit::form('faz'), 'Ana is ela, so the verb is faz. Faço is only for eu.'),

            Kit::translate($stage, 'sentences.translate.nao-sei', 'I do not know.', ['Não sei.', 'Eu não sei.'], [Kit::word('saber', 'sei'), Kit::form('sei')]),
            Kit::translate($stage, 'sentences.translate.vejo-ana', 'I see Ana here.', ['Vejo a Ana aqui.', 'Eu vejo a Ana aqui.', 'Vejo aqui a Ana.', 'Aqui vejo a Ana.'], [Kit::word('ver', 'vejo'), Kit::form('vejo')]),
            Kit::translate($stage, 'sentences.translate.fazes', 'What do you do here? (informal you)', ['O que fazes aqui?', 'O que fazes tu aqui?', 'Que fazes aqui?', 'O que é que fazes aqui?'], [Kit::word('fazer', 'fazes')]),

            Kit::build($stage, 'sentences.build.venho', 'I come with Ana.', 'Venho com a Ana.', ['vens'], [Kit::word('vir', 'venho'), Kit::form('venho')]),
            Kit::build($stage, 'sentences.build.sais', 'Do you leave at seven? (informal you)', 'Sais às sete?', ['saio'], [Kit::word('sair', 'sais')]),
            Kit::build($stage, 'sentences.build.poe', 'She puts two here.', 'Ela põe dois aqui.', ['ponho'], [Kit::word('pôr', 'põe')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vejo', 'Vejo a Ana e o João.', ['I see Ana and João.', 'I hear Ana and João.', 'You see Ana and João.', 'I bring Ana and João.'], 'I see Ana and João.', [Kit::word('ver', 'vejo'), Kit::form('vejo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.sabes', 'Sabes onde está o Rui?', ['Do you know where Rui is?', 'Do I know where Rui is?', 'Do you see Rui here?', 'Is Rui here?'], 'Do you know where Rui is?', [Kit::word('saber', 'sabes')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vem', 'A Ana vem com o João.', ['Ana comes with João.', 'Ana leaves with João.', 'Ana brings João.', 'I come with João.'], 'Ana comes with João.', [Kit::word('vir', 'vem')]),
            Kit::listenType($stage, 'sentences.listen_type.nao-sei-onde', 'Não sei onde está a Marta.', 'I do not know where Marta is.', [Kit::word('saber', 'sei'), Kit::form('sei')], homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.saio', 'Saio às oito com o Rui.', 'I leave at eight with Rui.', [Kit::word('sair', 'saio'), Kit::form('saio')]),
            Kit::listenType($stage, 'sentences.listen_type.faco', 'Eu faço um e tu fazes dois.', 'I do one and you do two. (informal you)', [Kit::word('fazer', 'faço'), Kit::form('faço')]),
            Kit::listenType($stage, 'sentences.listen_type.digo', 'Digo olá e adeus.', 'I say hello and goodbye.', [Kit::word('dizer', 'digo')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.fazes', 'O que fazes aqui?', 'What are you doing here? (informal you)', [Kit::word('fazer', 'fazes')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.trago', 'Trago a Marta aqui.', 'I bring Marta here.', [Kit::word('trazer', 'trago'), Kit::form('trago')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ponho', 'Ponho um aqui e dois ali.', 'I put one here and two there.', [Kit::word('pôr', 'ponho'), Kit::form('ponho')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.dou', 'Dou um à Ana e ao Rui.', 'I give one to Ana and Rui.', [Kit::word('dar', 'dou')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ves', 'Vês a Ana?', 'Do you see Ana? (informal you)', [['sim', 'não'], ['vejo']], 'Sim, vejo a Ana.', [Kit::word('ver', 'vejo'), Kit::form('vejo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vens', 'Vens com o João?', 'Are you coming with João? (informal you)', [['sim', 'não'], ['venho']], 'Sim, venho com o João.', [Kit::word('vir', 'venho')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ouves', 'Ouves bem?', 'Do you hear well? (informal you)', [['sim', 'não'], ['ouço']], 'Sim, ouço bem.', [Kit::word('ouvir', 'ouço')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.jantar', 'Read the conversation.', [
                Kit::line('Ana', 'João, o que fazes aqui?'),
                Kit::line('João', 'Venho com o Rui e trago o jantar.'),
                Kit::line('Ana', 'Muito bem. Eu ponho a mesa. Sabes onde está a Marta?'),
                Kit::line('João', 'Não sei. Aqui não vejo a Marta.'),
                Kit::line('Ana', 'Eu ouço a Marta. Está ali.'),
            ], [
                Kit::question('What does João bring?', ['The dinner', 'The table', 'Marta'], 'The dinner'),
                Kit::question('What does Ana do for the dinner?', ['She sets the table.', 'She brings the dinner.', 'She sees Marta.'], 'She sets the table.'),
                Kit::question('Where is Marta?', ['There', 'Here', 'The text does not say.'], 'There'),
            ], [Kit::word('fazer', 'fazes'), Kit::word('vir', 'venho'), Kit::word('trazer', 'trago'), Kit::word('pôr', 'ponho'), Kit::word('saber', 'sabes'), Kit::word('ver', 'vejo'), Kit::word('ouvir', 'ouço')], 'read', glosses: ['jantar' => 'dinner', 'mesa' => 'table']),
            Kit::gap($stage, 'task.choose_gap.vejo-marta', 'Eu não ___ a Marta aqui.', ['vejo', 'vês'], 'vejo', Kit::form('vejo', true), 'Eu goes with vejo, which is irregular. Vês is for tu.', 'read', 'I do not see Marta here.'),
            Kit::gap($stage, 'task.choose_gap.da', 'O Rui ___ dois à Ana.', ['dá', 'dou'], 'dá', Kit::word('dar', 'dá'), 'Rui is ele, so the verb is dá, with an accent. Dou is only for eu.', 'read', 'Rui gives two to Ana.'),

            Kit::transform($stage, 'task.transform.ela-ve', 'Change the subject to she.', 'Vejo a Marta.', ['Ela vê a Marta.'], [Kit::word('ver', 'vê'), Kit::form('vê')]),
            Kit::transform($stage, 'task.transform.saimos', 'Change the subject to we.', 'Saio às oito.', ['Saímos às oito.', 'Nós saímos às oito.'], [Kit::word('sair', 'saímos'), Kit::form('saímos')]),
            Kit::transform($stage, 'task.transform.ponho', 'Change the subject to I.', 'A Ana põe dois aqui.', ['Ponho dois aqui.', 'Eu ponho dois aqui.'], [Kit::word('pôr', 'ponho'), Kit::form('ponho', true)]),
            Kit::writeGuided($stage, 'task.write_guided.venho-trago', 'Say that you come with Rui and that you bring Ana.', ['venho com', 'trago a'], 'Venho com o Rui e trago a Ana.', [
                ['forms' => ['venho', 'vimos'], 'term' => 'vir'],
                ['forms' => ['trago', 'trazemos'], 'term' => 'trazer'],
            ], [Kit::word('vir', 'venho'), Kit::word('trazer', 'trago')]),
            Kit::writeGuided($stage, 'task.write_guided.nao-sei-nao-vejo', 'Say that you do not know where Marta is and that you do not see Rui here.', ['não sei', 'não vejo', 'onde'], 'Não sei onde está a Marta e não vejo o Rui aqui.', [
                ['forms' => ['sei', 'sabemos'], 'term' => 'saber'],
                ['forms' => ['vejo', 'vemos'], 'term' => 'ver'],
            ], [Kit::word('saber', 'sei'), Kit::word('ver', 'vejo')]),
            Kit::build($stage, 'task.build.digo', 'I say hello to Ana.', 'Digo olá à Ana.', ['dizes', 'diz'], [Kit::word('dizer', 'digo'), Kit::form('digo')], 'write'),
            Kit::build($stage, 'task.build.vimos', 'We come with Rui.', 'Vimos com o Rui.', ['venho', 'vem'], [Kit::word('vir', 'vimos')], 'write'),
            Kit::build($stage, 'task.build.ouves', 'Do you hear Marta? (informal you)', 'Ouves a Marta?', ['ouço', 'ouve'], [Kit::word('ouvir', 'ouves')], 'write'),
            Kit::translate($stage, 'task.translate.vejo-ouco', 'I see Ana, but I do not hear Marta.', ['Vejo a Ana, mas não ouço a Marta.', 'Eu vejo a Ana, mas não ouço a Marta.'], [Kit::word('ver', 'vejo'), Kit::word('ouvir', 'ouço'), Kit::form('vejo')], 'write'),
            Kit::translate($stage, 'task.translate.fazes-sais', 'What do you do and when do you leave? (informal you)', ['O que fazes e quando sais?', 'Que fazes e quando sais?', 'O que é que fazes e quando é que sais?', 'Que fazes e quando é que sais?'], [Kit::word('fazer', 'fazes'), Kit::word('sair', 'sais'), Kit::form('fazes', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.sair', [
                Kit::line('Marta', 'Quando sais, Rui?'),
                Kit::line('Rui', 'Saio às oito. Digo adeus à Ana e ao João.'),
                Kit::line('Marta', 'Eu também saio às oito. Dou dois à Ana.'),
            ], [
                Kit::question('When does Rui leave?', ['At seven', 'At eight', 'At nine'], 'At eight'),
                Kit::question('Who does Rui say goodbye to?', ['Ana and João', 'Marta', 'Nobody'], 'Ana and João'),
                Kit::question('Who gives two to Ana?', ['Marta', 'Rui', 'João'], 'Marta'),
            ], [
                Kit::question('Who asks when Rui leaves?', ['Marta', 'Rui', 'Ana'], 'Marta'),
                Kit::question('Does Marta leave too?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('sair', 'saio'), Kit::word('dizer', 'digo'), Kit::word('dar', 'dou')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.venho-trago', 'Venho com o Rui e trago a Marta.', 'I come with Rui and I bring Marta.', [Kit::word('vir', 'venho'), Kit::word('trazer', 'trago'), Kit::form('venho')], 'listen', homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
            Kit::listenType($stage, 'task.listen_type.diz-digo', 'A Ana diz olá e eu digo adeus.', 'Ana says hello and I say goodbye.', [Kit::word('dizer', 'diz'), Kit::form('digo')], 'listen', homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
            Kit::listenType($stage, 'task.listen_type.nao-ouco', 'Não ouço bem, mas vejo o João ali.', 'I do not hear well, but I see João there.', [Kit::word('ouvir', 'ouço'), Kit::word('ver', 'vejo')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.sais', 'Sais às oito?', 'Do you leave at eight? (informal you)', [['sim', 'não'], ['saio']], 'Sim, saio às oito.', [Kit::word('sair', 'saio'), Kit::form('saio')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.dizes', 'Dizes olá ao João?', 'Do you say hello to João? (informal you)', [['sim', 'não'], ['digo']], 'Sim, digo olá ao João.', [Kit::word('dizer', 'digo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.trazes', 'Trazes o Rui?', 'Are you bringing Rui? (informal you)', [['sim', 'não'], ['trago']], 'Sim, trago o Rui.', [Kit::word('trazer', 'trago')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.poes', 'Pões dois aqui?', 'Do you put two here? (informal you)', [['sim', 'não'], ['ponho']], 'Sim, ponho dois aqui.', [Kit::word('pôr', 'ponho')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.dou', 'Dou dois ao João e dois à Ana.', 'I give two to João and two to Ana.', [Kit::word('dar', 'dou')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.nao-sei-faco', 'Não sei o que faço aqui.', 'I do not know what I am doing here.', [Kit::word('saber', 'sei'), Kit::word('fazer', 'faço'), Kit::form('faço')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.marta-fazes', 'Marta, what do you do there? (informal you)', ['Marta, o que fazes ali?', 'O que fazes ali, Marta?', 'Marta, que fazes ali?', 'Marta, o que é que fazes ali?'], [Kit::word('fazer', 'fazes'), Kit::form('fazes', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.venho-trago', 'I come with Marta and I bring two.', ['Venho com a Marta e trago dois.', 'Eu venho com a Marta e trago dois.'], [Kit::word('vir', 'venho'), Kit::word('trazer', 'trago'), Kit::form('venho')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.saio-digo', 'I leave at nine and I say goodbye.', ['Saio às nove e digo adeus.', 'Eu saio às nove e digo adeus.'], [Kit::word('sair', 'saio'), Kit::word('dizer', 'digo'), Kit::form('saio', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.nao-sei-vejo', 'I do not know, but I see Rui here.', ['Não sei, mas vejo o Rui aqui.', 'Eu não sei, mas vejo o Rui aqui.', 'Não sei, mas aqui vejo o Rui.'], [Kit::word('saber', 'sei'), Kit::word('ver', 'vejo'), Kit::form('sei')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.dou', 'Eu ___ dois à Marta.', 'I give two to Marta.', 'dou', Kit::form('dou', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.trago', 'Eu ___ o Rui e a Ana.', 'I bring Rui and Ana.', 'trago', Kit::form('trago'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.ouco', 'Ouço o Rui, mas não ouço a Ana.', 'I hear Rui, but I do not hear Ana.', [Kit::word('ouvir', 'ouço')], 'dictation', $set, homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.digo-ponho', 'Digo olá ao Rui e ponho dois aqui.', 'I say hello to Rui and I put two here.', [Kit::word('dizer', 'digo'), Kit::word('pôr', 'ponho')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.da', 'A Marta dá dois ao João e ao Rui.', 'Marta gives two to João and Rui.', [Kit::word('dar', 'dá')], 'dictation', $set, homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
            Kit::listenPassage($stage, 'check.a.listen_passage.trazes', [
                Kit::line('Ana', 'O que trazes, Rui?'),
                Kit::line('Rui', 'Trago dois. Onde ponho os dois?'),
                Kit::line('Ana', 'Ali, por favor.'),
            ], [
                Kit::question('What does Rui bring?', ['Two', 'Three', 'Nothing'], 'Two'),
                Kit::question('Where does Ana say to put them?', ['There', 'Here', 'The conversation does not say.'], 'There'),
                Kit::question('Who asks the first question?', ['Ana', 'Rui', 'Nobody'], 'Ana'),
            ], [
                Kit::question('Who brings something?', ['Rui', 'Ana', 'Nobody'], 'Rui'),
                Kit::question('Does Rui know where to put them?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('trazer', 'trago'), Kit::word('pôr', 'ponho')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sabes', 'Read the conversation.', [
                Kit::line('Marta', 'João, sabes onde está a Ana?'),
                Kit::line('João', 'Não sei. Aqui não vejo a Ana, mas ouço o Rui.'),
                Kit::line('Marta', 'Eu também ouço o Rui. Vem de lá.'),
            ], [
                Kit::question('What does Marta ask João?', ['Where Ana is', 'Where Rui is', 'Who is coming'], 'Where Ana is'),
                Kit::question('Does João see Ana?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('saber', 'sabes'), Kit::word('ver', 'vejo'), Kit::word('ouvir', 'ouço'), Kit::word('vir', 'vem')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fazes', 'O que fazes?', 'What do you do? (informal you)', [['faço', 'venho', 'saio', 'ponho', 'trago', 'vejo', 'digo', 'ouço', 'dou', 'sei'], ['aqui', 'ali', 'lá', 'com', 'um', 'dois', 'três', 'olá', 'adeus', 'oito', 'sete', 'nove']], 'Vejo a Ana aqui.', [Kit::word('fazer', 'fazes')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.das', 'Dás um à Ana?', 'Do you give one to Ana? (informal you)', [['sim', 'não'], ['dou']], 'Sim, dou um à Ana.', [Kit::word('dar', 'dás')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sais', 'Sais às sete ou às oito?', 'Do you leave at seven or at eight? (informal you)', [['saio'], ['sete', 'oito']], 'Saio às oito.', [Kit::word('sair', 'sais')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.venho-trago-digo', 'I come with Rui, I bring Marta and I say hello.', ['Venho com o Rui, trago a Marta e digo olá.'], [Kit::word('vir', 'venho'), Kit::word('trazer', 'trago'), Kit::word('dizer', 'digo'), Kit::form('venho')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.saio-dou', 'I leave with Rui and I give two to Ana.', ['Saio com o Rui e dou dois à Ana.', 'Eu saio com o Rui e dou dois à Ana.'], [Kit::word('sair', 'saio'), Kit::word('dar', 'dou'), Kit::form('saio', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.nao-sei-fazes', 'I do not know what you do and I do not see Ana. (informal you)', ['Não sei o que fazes e não vejo a Ana.'], [Kit::word('saber', 'sei'), Kit::word('fazer', 'fazes'), Kit::word('ver', 'vejo'), Kit::form('sei', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ponho-ouco', 'I put two here and I do not hear Ana.', ['Ponho dois aqui e não ouço a Ana.', 'Eu ponho dois aqui e não ouço a Ana.'], [Kit::word('pôr', 'ponho'), Kit::word('ouvir', 'ouço'), Kit::form('ponho', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.da', 'A Marta ___ dois ao Rui.', 'Marta gives two to Rui.', 'dá', Kit::word('dar', 'dá'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.poe', 'A Ana ___ dois ali.', 'Ana puts two there.', 'põe', Kit::word('pôr', 'põe'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vejo-ouco-digo', 'Vejo a Ana, ouço o João e digo olá.', 'I see Ana, I hear João and I say hello.', [Kit::word('ver', 'vejo'), Kit::word('ouvir', 'ouço'), Kit::word('dizer', 'digo'), Kit::form('vejo')], 'dictation', $set, homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.sabe-faz-sai', 'A Ana sabe o que faz e sai.', 'Ana knows what she does and she leaves.', [Kit::word('saber', 'sabe'), Kit::word('fazer', 'faz'), Kit::word('sair', 'sai'), Kit::form('faz')], 'dictation', $set, homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.vem-traz', 'A Marta vem com o Rui e traz dois.', 'Marta comes with Rui and brings two.', [Kit::word('vir', 'vem'), Kit::word('trazer', 'traz')], 'dictation', $set, homophoneNote: 'This a is the article (the). Do not write à (to the) or há (there is).'),
        ];
    }
}
