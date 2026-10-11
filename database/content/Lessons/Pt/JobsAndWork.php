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

final class JobsAndWork implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'jobs-and-work';
    }

    public function words(): array
    {
        return [
            new WordData('trabalhar', cue: 'to work', forms: ['trabalho', 'trabalhas', 'trabalha', 'trabalhamos', 'trabalham']),
            new WordData('o professor', cue: 'teacher (man)', accepted: ['a professora'], forms: ['professora', 'professores', 'professoras']),
            new WordData('o engenheiro', cue: 'engineer (man)', accepted: ['a engenheira'], forms: ['engenheira', 'engenheiros', 'engenheiras'], portunolSlips: ['el ingeniero']),
            new WordData('o advogado', cue: 'lawyer (man)', accepted: ['a advogada'], forms: ['advogada', 'advogados', 'advogadas'], portunolSlips: ['el abogado']),
            new WordData('o cozinheiro', cue: 'cook (man)', accepted: ['a cozinheira'], forms: ['cozinheira', 'cozinheiros', 'cozinheiras'], portunolSlips: ['el cocinero']),
            new WordData('o enfermeiro', cue: 'nurse (man)', accepted: ['a enfermeira'], forms: ['enfermeira', 'enfermeiros', 'enfermeiras'], portunolSlips: ['el enfermero']),
            new WordData('o escritório', cue: 'office', forms: ['escritórios'], portunolSlips: ['la oficina']),
            new WordData('a empresa', cue: 'company (business)', forms: ['empresas']),
            new WordData('o chefe', cue: 'boss (man)', accepted: ['a chefe'], forms: ['chefes'], portunolSlips: ['el jefe'], note: 'Chefe has one form for men and women: o chefe is a male boss and a chefe is a female boss.'),
            new WordData('o colega', cue: 'colleague (man)', accepted: ['a colega'], forms: ['colegas'], portunolSlips: ['el compañero'], note: 'Colega has one form for men and women: o colega is a male colleague and a colega is a female colleague. O companheiro is a partner, not a workmate.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Sou cozinheiro.', 'english' => 'I am a cook.'],
            ['text' => 'Trabalho como cozinheiro numa empresa.', 'english' => 'I work as a cook in a company.'],
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

    /** @return list<string> */
    private function jobs(): array
    {
        return ['professor', 'professora', 'engenheiro', 'engenheira', 'advogado', 'advogada', 'cozinheiro', 'cozinheira', 'enfermeiro', 'enfermeira'];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.colega-advogada', 'A minha colega ___ advogada.', ['é', 'está'], 'é', Kit::form('é', true), 'Your job says what you are, so it goes with ser: é. Está is for how someone feels or where they are.', 'choose', 'My colleague is a lawyer.'),
            Kit::gap($stage, 'sentences.choose_gap.trabalho-cozinheiro', 'Trabalho ___ cozinheiro.', ['como', 'em'], 'como', Kit::form('como', true), 'To say the job you do, use como: trabalho como cozinheiro. Em is for the place where you work.', 'choose', 'I work as a cook.'),
            Kit::gap($stage, 'sentences.choose_gap.trabalho-escritorio', 'A Marta trabalha ___ escritório.', ['num', 'como'], 'num', Kit::form('num', true), 'An office is a place, so you use em, which joins with um into num. Como is for the job you do, as in trabalha como professora.', 'choose', 'Marta works in an office.'),
            Kit::gap($stage, 'sentences.choose_gap.colega-engenheiro', 'O meu colega é ___.', ['engenheiro', 'escritório', 'empresa'], 'engenheiro', Kit::word('o engenheiro', 'engenheiro'), 'Engenheiro is a job. Escritório is a place and empresa is a company, neither is a job.', 'choose', 'My colleague is an engineer.'),
            Kit::gap($stage, 'sentences.choose_gap.numa-empresa', 'Trabalho numa ___.', ['empresa', 'advogado', 'professor'], 'empresa', Kit::word('a empresa', 'empresa'), 'A empresa is a company, a place to work in. Advogado and professor are jobs, not places, so they cannot follow numa.', 'choose', 'I work in a company.'),
            Kit::gap($stage, 'sentences.choose_gap.ana-enfermeira', 'A Ana é ___.', ['enfermeira', 'enfermeiro', 'empresa'], 'enfermeira', Kit::word('o enfermeiro', 'enfermeira'), 'Ana is a woman, so the job ends in -a: enfermeira. Enfermeiro is for a man, and empresa is a company.', 'choose', 'Ana is a nurse.'),

            Kit::typeGap($stage, 'sentences.type_gap.joao-engenheiro', 'João é ___.', 'João is an engineer.', 'engenheiro', Kit::word('o engenheiro', 'engenheiro')),
            Kit::typeGap($stage, 'sentences.type_gap.marta-advogada', 'Marta é ___.', 'Marta is a lawyer.', 'advogada', Kit::word('o advogado', 'advogada')),
            Kit::typeGap($stage, 'sentences.type_gap.rui-e', 'Rui ___ cozinheiro.', 'Rui is a cook.', 'é', Kit::form('é'), 'A plain job statement after ser normally has no article: Rui é cozinheiro.'),
            Kit::typeGap($stage, 'sentences.type_gap.rui-empresa', 'Rui trabalha ___ empresa.', 'Rui works in a company.', 'numa', Kit::form('numa'), 'A company is a place where you work, so you use em, which joins with uma into numa.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-como', 'Marta trabalha ___ professora.', 'Marta works as a teacher.', 'como', Kit::form('como'), 'The job you do after trabalhar takes como: trabalha como professora.'),

            Kit::translate($stage, 'sentences.translate.cozinheiro', 'I am a cook.', ['Sou cozinheiro.', 'Sou cozinheira.', 'Eu sou cozinheiro.', 'Eu sou cozinheira.', 'Sou um cozinheiro.', 'Sou uma cozinheira.'], [Kit::word('o cozinheiro', 'cozinheiro', ['cozinheira']), Kit::form('sou', true)]),
            Kit::translate($stage, 'sentences.translate.trabalhas-escritorio', 'Do you work in an office? (informal you)', ['Trabalhas num escritório?', 'Tu trabalhas num escritório?', 'Trabalhas em um escritório?', 'Tu trabalhas em um escritório?'], [Kit::word('trabalhar', 'trabalhas'), Kit::word('o escritório', 'escritório'), Kit::form('trabalhas num', false, ['trabalhas em'])]),
            Kit::translate($stage, 'sentences.translate.chefe-empresa', 'My boss works in a company.', ['O meu chefe trabalha numa empresa.', 'A minha chefe trabalha numa empresa.', 'O meu chefe trabalha em uma empresa.', 'A minha chefe trabalha em uma empresa.'], [Kit::word('o chefe', 'chefe'), Kit::word('a empresa', 'empresa'), Kit::word('trabalhar', 'trabalha'), Kit::form('trabalha numa', false, ['trabalha em'])]),

            Kit::build($stage, 'sentences.build.marta-professora', 'Marta is a teacher.', 'Marta é professora.', ['está'], [Kit::word('o professor', 'professora'), Kit::form('é', true)]),
            Kit::build($stage, 'sentences.build.colega-escritorio', 'My colleague (a man) works in an office.', 'O meu colega trabalha num escritório.', ['como'], [Kit::word('o colega', 'colega'), Kit::word('o escritório', 'escritório'), Kit::word('trabalhar', 'trabalha'), Kit::form('trabalha num', false, ['trabalha em'])]),
            Kit::build($stage, 'sentences.build.joao-engenheiro', 'João works as an engineer.', 'João trabalha como engenheiro.', ['em'], [Kit::word('o engenheiro', 'engenheiro'), Kit::word('trabalhar', 'trabalha'), Kit::form('trabalha como')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.advogado', 'Sou advogado.', ['I am a lawyer.', 'I am a teacher.', 'I am a cook.', 'You are a lawyer.'], 'I am a lawyer.', [Kit::word('o advogado', 'advogado'), Kit::form('sou')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.marta-enfermeira', 'Marta é enfermeira.', ['Marta is a nurse.', 'Marta is a teacher.', 'Marta is a cook.', 'Ana is a nurse.'], 'Marta is a nurse.', [Kit::word('o enfermeiro', 'enfermeira'), Kit::form('é')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.em-que-trabalhas', 'Em que trabalhas?', ['What do you do for work?', 'Where do you work?', 'Do you work here?', 'What do I do for work?'], 'What do you do for work?', [Kit::word('trabalhar', 'trabalhas')]),
            Kit::listenType($stage, 'sentences.listen_type.empresa', 'Trabalho numa empresa.', 'I work in a company.', [Kit::word('a empresa', 'empresa'), Kit::word('trabalhar', 'trabalho'), Kit::form('trabalho numa', false, ['trabalho em'])]),
            Kit::listenType($stage, 'sentences.listen_type.colega-engenheiro', 'O meu colega é engenheiro.', 'My colleague (a man) is an engineer.', [Kit::word('o colega', 'colega'), Kit::word('o engenheiro', 'engenheiro'), Kit::form('é')]),
            Kit::listenType($stage, 'sentences.listen_type.professor-cozinheiro', 'És professor ou cozinheiro?', 'Are you a teacher or a cook? (informal you)', [Kit::word('o professor', 'professor'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::form('és')]),
            Kit::listenType($stage, 'sentences.listen_type.chefe-escritorio', 'A minha chefe trabalha no escritório.', 'My boss (a woman) works in the office.', [Kit::word('o chefe', 'chefe'), Kit::word('o escritório', 'escritório'), Kit::form('trabalha no', false, ['trabalha em'])], homophoneNote: 'The a before minha is the article a (the), not à (to the) and not há (there is).'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.engenheiro', 'Sou engenheiro.', 'I am an engineer.', [Kit::word('o engenheiro', 'engenheiro'), Kit::form('sou')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.escritorio', 'Trabalho no escritório.', 'I work in the office.', [Kit::word('o escritório', 'escritório'), Kit::form('trabalho no', false, ['trabalho em'])]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.chefe-advogado', 'O meu chefe é advogado.', 'My boss is a lawyer.', [Kit::word('o chefe', 'chefe'), Kit::word('o advogado', 'advogado'), Kit::form('é')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.colega-cozinheiro', 'O meu colega trabalha como cozinheiro.', 'My colleague works as a cook.', [Kit::word('o colega', 'colega'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::form('trabalha como')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.em-que-trabalhas', 'Em que trabalhas?', 'What do you do for work?', [['sou', 'trabalho'], $this->jobs()], 'Sou professor.', [Kit::word('o professor', 'professor'), Kit::form('sou')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.onde-trabalhas', 'Onde trabalhas?', 'Where do you work?', [['trabalho', 'num', 'numa', 'no', 'na', 'em'], ['escritório', 'empresa']], 'Trabalho num escritório.', [Kit::word('trabalhar', 'trabalho')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.es-advogado', 'És advogado?', 'Are you a lawyer?', [['sim', 'não'], ['sou', 'advogado', 'advogada']], 'Sim, sou advogado.', [Kit::word('o advogado', 'advogado')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.trabalho', 'Read the conversation about work.', [
                Kit::line('Ana', 'Em que trabalhas, João?'),
                Kit::line('João', 'Sou engenheiro. Trabalho numa empresa com o meu chefe.'),
                Kit::line('João', 'E tu, Ana?'),
                Kit::line('Ana', 'Sou advogada. Trabalho num escritório com o meu colega.'),
            ], [
                Kit::question('What is João\'s job?', ['Engineer', 'Lawyer', 'Teacher'], 'Engineer'),
                Kit::question('Where does Ana work?', ['In an office', 'In a company', 'At home'], 'In an office'),
                Kit::question('Who does João work with?', ['His boss', 'His colleague', 'Ana'], 'His boss'),
            ], [Kit::word('trabalhar', 'trabalho'), Kit::word('o engenheiro', 'engenheiro'), Kit::word('a empresa', 'empresa'), Kit::word('o chefe', 'chefe'), Kit::word('o advogado', 'advogada'), Kit::word('o escritório', 'escritório'), Kit::word('o colega', 'colega')], 'read'),
            Kit::gap($stage, 'task.choose_gap.joao-engenheiro', 'João ___ engenheiro.', ['é', 'trabalha'], 'é', Kit::form('é', true), 'With a job after it, the verb is ser: João é engenheiro, usually with no um. Trabalha needs como or em, as in trabalha como engenheiro.', 'read', 'João is an engineer.'),
            Kit::gap($stage, 'task.choose_gap.chefe-escritorio', 'O meu chefe trabalha ___ escritório.', ['no', 'como'], 'no', Kit::form('no', true), 'O escritório is a place, so you use em, which joins with o into no. Como goes with a job, as in trabalha como advogado.', 'read', 'My boss works in the office.'),

            Kit::transform($stage, 'task.transform.marta', 'Now say it about Marta.', 'Sou professor.', ['Marta é professora.', 'A Marta é professora.'], [Kit::word('o professor', 'professora'), Kit::form('é')]),
            Kit::transform($stage, 'task.transform.pergunta', 'Now ask a colleague (informal you).', 'Trabalho no escritório.', ['Trabalhas no escritório?', 'Tu trabalhas no escritório?'], [Kit::word('o escritório', 'escritório'), Kit::word('trabalhar', 'trabalhas'), Kit::form('trabalhas no', false, ['trabalhas em'])]),
            Kit::transform($stage, 'task.transform.trabalho-como', 'Say that you work as an engineer.', 'Sou engenheiro.', ['Trabalho como engenheiro.', 'Eu trabalho como engenheiro.', 'Trabalho como engenheira.', 'Eu trabalho como engenheira.', 'Trabalho como um engenheiro.', 'Trabalho como uma engenheira.'], [Kit::word('o engenheiro', 'engenheiro', ['engenheira']), Kit::word('trabalhar', 'trabalho'), Kit::form('trabalho como')]),
            Kit::writeGuided($stage, 'task.write_guided.cozinheiro', 'Say that you are a cook and that you work in a company.', ['sou', 'cozinheiro', 'trabalho numa', 'empresa'], 'Sou cozinheiro. Trabalho numa empresa.', [
                ['forms' => ['sou'], 'term' => null],
                ['forms' => ['cozinheiro', 'cozinheira'], 'term' => 'o cozinheiro'],
                ['forms' => ['trabalho'], 'term' => 'trabalhar'],
                ['forms' => ['empresa'], 'term' => 'a empresa'],
            ], [Kit::word('o cozinheiro', 'cozinheiro'), Kit::word('trabalhar', 'trabalho'), Kit::word('a empresa', 'empresa')]),
            Kit::writeGuided($stage, 'task.write_guided.chefe-colega', 'Say that your boss (a man) is a lawyer and that your colleague (a woman) is a nurse.', ['o meu chefe', 'advogado', 'a minha colega', 'enfermeira'], 'O meu chefe é advogado. A minha colega é enfermeira.', [
                ['forms' => ['chefe'], 'term' => 'o chefe'],
                ['forms' => ['advogado'], 'term' => 'o advogado'],
                ['forms' => ['colega'], 'term' => 'o colega'],
                ['forms' => ['enfermeira'], 'term' => 'o enfermeiro'],
            ], [Kit::word('o chefe', 'chefe'), Kit::word('o advogado', 'advogado'), Kit::word('o colega', 'colega'), Kit::word('o enfermeiro', 'enfermeira')]),
            Kit::build($stage, 'task.build.colega-professor', 'My colleague (a man) is a teacher.', 'O meu colega é professor.', ['em', 'trabalha'], [Kit::word('o colega', 'colega'), Kit::word('o professor', 'professor'), Kit::form('é', true)], 'write'),
            Kit::build($stage, 'task.build.trabalho-empresa', 'I work in a company.', 'Trabalho numa empresa.', ['como', 'é'], [Kit::word('a empresa', 'empresa'), Kit::word('trabalhar', 'trabalho'), Kit::form('trabalho numa', false, ['trabalho em'])], 'write'),
            Kit::build($stage, 'task.build.chefe-cozinheiro', 'My boss (a man) works as a cook.', 'O meu chefe trabalha como cozinheiro.', ['em', 'é'], [Kit::word('o chefe', 'chefe'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::word('trabalhar', 'trabalha'), Kit::form('trabalha como')], 'write'),
            Kit::translate($stage, 'task.translate.marta-joao', 'Marta is a nurse and João is a cook.', ['Marta é enfermeira e João é cozinheiro.', 'A Marta é enfermeira e o João é cozinheiro.', 'A Marta é enfermeira e João é cozinheiro.', 'Marta é enfermeira e o João é cozinheiro.'], [Kit::word('o enfermeiro', 'enfermeira'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::form('é')], 'write'),
            Kit::translate($stage, 'task.translate.empresa-escritorio', 'Do you work in a company? No, I work in the office. (informal you)', ['Trabalhas numa empresa? Não, trabalho no escritório.', 'Trabalhas numa empresa? Não, eu trabalho no escritório.', 'Tu trabalhas numa empresa? Não, trabalho no escritório.', 'Tu trabalhas numa empresa? Não, eu trabalho no escritório.', 'Trabalhas em uma empresa? Não, trabalho no escritório.', 'Trabalhas em uma empresa? Não, eu trabalho no escritório.', 'Tu trabalhas em uma empresa? Não, trabalho no escritório.', 'Tu trabalhas em uma empresa? Não, eu trabalho no escritório.'], [Kit::word('trabalhar', 'trabalhas'), Kit::word('a empresa', 'empresa'), Kit::word('o escritório', 'escritório'), Kit::form('trabalhas numa', false, ['trabalhas em'])], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.trabalho', [
                Kit::line('Marta', 'Em que trabalhas, Rui?'),
                Kit::line('Rui', 'Sou cozinheiro. Trabalho com o meu chefe.'),
                Kit::line('Rui', 'E tu, Marta?'),
                Kit::line('Marta', 'Sou enfermeira. Trabalho com a minha colega.'),
            ], [
                Kit::question('What is Rui\'s job?', ['Cook', 'Nurse', 'Engineer'], 'Cook'),
                Kit::question('Who does Rui work with?', ['His boss', 'His colleague', 'Marta'], 'His boss'),
                Kit::question('What is Marta\'s job?', ['Nurse', 'Cook', 'Lawyer'], 'Nurse'),
            ], [
                Kit::question('Who asks about the job first?', ['Marta', 'Rui', 'Nobody'], 'Marta'),
                Kit::question('Is Marta a nurse?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('trabalhar', 'trabalho'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::word('o chefe', 'chefe'), Kit::word('o enfermeiro', 'enfermeira'), Kit::word('o colega', 'colega')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.professora-colega', 'Sou professora e trabalho com o meu colega.', 'I am a teacher (woman) and I work with my colleague (a man).', [Kit::word('o professor', 'professora'), Kit::word('o colega', 'colega'), Kit::word('trabalhar', 'trabalho'), Kit::form('sou')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.chefe-advogado', 'O meu chefe é advogado e trabalha num escritório.', 'My boss (a man) is a lawyer and works in an office.', [Kit::word('o chefe', 'chefe'), Kit::word('o advogado', 'advogado'), Kit::word('o escritório', 'escritório'), Kit::word('trabalhar', 'trabalha'), Kit::form('é')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.empresa-escritorio', 'Trabalhas numa empresa ou num escritório?', 'Do you work in a company or in an office? (informal you)', [Kit::word('a empresa', 'empresa'), Kit::word('o escritório', 'escritório'), Kit::word('trabalhar', 'trabalhas'), Kit::form('trabalhas numa', false, ['trabalhas em'])], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.escritorio', 'Trabalhas num escritório?', 'Do you work in an office?', [['sim', 'não'], ['trabalho', 'escritório', 'empresa']], 'Sim, trabalho num escritório.', [Kit::word('o escritório', 'escritório'), Kit::word('trabalhar', 'trabalho')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chefe', 'Em que trabalha o teu chefe?', 'What does your boss do for work?', [['é', 'trabalha', 'meu'], $this->jobs()], 'O meu chefe é advogado.', [Kit::word('o chefe', 'chefe')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.enfermeiro-cozinheiro', 'És enfermeiro ou cozinheiro?', 'Are you a nurse or a cook?', [['sou'], ['enfermeiro', 'enfermeira', 'cozinheiro', 'cozinheira']], 'Sou cozinheiro.', [Kit::word('o enfermeiro', 'enfermeiro'), Kit::word('o cozinheiro', 'cozinheiro')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.com-quem', 'Com quem trabalhas?', 'Who do you work with?', [['trabalho', 'com'], ['colega', 'chefe']], 'Trabalho com o meu colega.', [Kit::word('o colega', 'colega'), Kit::word('o chefe', 'chefe')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.colega-enfermeira', 'A minha colega é enfermeira e trabalha aqui.', 'My colleague (a woman) is a nurse and works here.', [Kit::word('o colega', 'colega'), Kit::word('o enfermeiro', 'enfermeira'), Kit::form('é')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cozinheiro-chefe', 'Sou cozinheiro e trabalho com o meu chefe.', 'I am a cook and I work with my boss.', [Kit::word('o cozinheiro', 'cozinheiro'), Kit::word('o chefe', 'chefe'), Kit::word('trabalhar', 'trabalho'), Kit::form('sou')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.colega-engenheiro', 'Your colleague is an engineer. (informal you)', ['O teu colega é engenheiro.', 'A tua colega é engenheira.'], [Kit::word('o colega', 'colega'), Kit::word('o engenheiro', 'engenheiro', ['engenheira']), Kit::form('é', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-professora', 'Ana works as a teacher.', ['A Ana trabalha como professora.', 'Ana trabalha como professora.'], [Kit::word('trabalhar', 'trabalha'), Kit::word('o professor', 'professora'), Kit::form('trabalha como', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.onde-trabalhas', 'Where do you work? I work in an office. (informal you)', ['Onde trabalhas? Trabalho num escritório.', 'Onde trabalhas? Eu trabalho num escritório.', 'Onde trabalhas? Trabalho em um escritório.', 'Onde trabalhas? Eu trabalho em um escritório.'], [Kit::word('trabalhar', 'trabalhas'), Kit::word('o escritório', 'escritório'), Kit::form('trabalho num', false, ['trabalho em'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.tres-trabalhos', 'Rui is a cook, Marta is a nurse and Ana is a lawyer.', ['Rui é cozinheiro, Marta é enfermeira e Ana é advogada.'], [Kit::word('o cozinheiro', 'cozinheiro'), Kit::word('o enfermeiro', 'enfermeira'), Kit::word('o advogado', 'advogada'), Kit::form('é')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.joao-empresa', 'João trabalha numa ___.', 'João works in a company.', 'empresa', Kit::word('a empresa', 'empresa'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.colega-enfermeira', 'A minha colega é ___.', 'My colleague (a woman) is a nurse.', 'enfermeira', Kit::word('o enfermeiro', 'enfermeira'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.chefe-advogada', 'A minha chefe é advogada e trabalha num escritório.', 'My boss (a woman) is a lawyer and works in an office.', [Kit::word('o chefe', 'chefe'), Kit::word('o advogado', 'advogada'), Kit::word('o escritório', 'escritório'), Kit::word('trabalhar', 'trabalha'), Kit::form('é')], 'dictation', $set, homophoneNote: 'The a before minha is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.es', 'És professor, engenheiro ou cozinheiro?', 'Are you a teacher, an engineer or a cook? (informal you)', [Kit::word('o professor', 'professor'), Kit::word('o engenheiro', 'engenheiro'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::form('és')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.chefe-colega', 'Trabalho numa empresa com o chefe e o colega.', 'I work in a company with the boss and the colleague (both men).', [Kit::word('trabalhar', 'trabalho'), Kit::word('a empresa', 'empresa'), Kit::word('o chefe', 'chefe'), Kit::word('o colega', 'colega')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.escritorio', [
                Kit::line('João', 'Ana, onde trabalhas?'),
                Kit::line('Ana', 'Trabalho num escritório. Sou advogada.'),
                Kit::line('Ana', 'E tu, João?'),
                Kit::line('João', 'Sou cozinheiro. Trabalho aqui com o meu colega.'),
            ], [
                Kit::question('Where does Ana work?', ['In an office', 'In a company', 'Here'], 'In an office'),
                Kit::question('What is João\'s job?', ['Cook', 'Lawyer', 'Engineer'], 'Cook'),
                Kit::question('Who works with João?', ['His colleague', 'His boss', 'Ana'], 'His colleague'),
            ], [
                Kit::question('Who asks the first question?', ['João', 'Ana', 'Nobody'], 'João'),
                Kit::question('Is Ana a lawyer?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o escritório', 'escritório'), Kit::word('o advogado', 'advogada'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::word('o colega', 'colega'), Kit::word('trabalhar', 'trabalho')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.engenheiro', 'Read the conversation.', [
                Kit::line('Marta', 'Rui, és engenheiro?'),
                Kit::line('Rui', 'Não, sou professor. O meu chefe é engenheiro.'),
                Kit::line('Rui', 'E tu?'),
                Kit::line('Marta', 'Eu sou enfermeira.'),
            ], [
                Kit::question('What is Rui\'s job?', ['Teacher', 'Engineer', 'Nurse'], 'Teacher'),
                Kit::question('Who is the engineer?', ['Rui\'s boss', 'Rui', 'Marta'], 'Rui\'s boss'),
            ], [Kit::word('o engenheiro', 'engenheiro'), Kit::word('o professor', 'professor'), Kit::word('o chefe', 'chefe'), Kit::word('o enfermeiro', 'enfermeira')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.professor-engenheiro', 'És professor ou engenheiro?', 'Are you a teacher or an engineer?', [['sou'], ['professor', 'professora', 'engenheiro', 'engenheira']], 'Sou professor.', [Kit::word('o professor', 'professor'), Kit::word('o engenheiro', 'engenheiro')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.empresa', 'Trabalhas numa empresa?', 'Do you work in a company?', [['sim', 'não'], ['trabalho', 'empresa', 'escritório']], 'Sim, trabalho numa empresa.', [Kit::word('a empresa', 'empresa'), Kit::word('trabalhar', 'trabalho')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.colega', 'Em que trabalha o teu colega?', 'What does your colleague do for work?', [['é', 'meu', 'trabalha'], $this->jobs()], 'O meu colega é professor.', [Kit::word('o colega', 'colega'), Kit::word('o professor', 'professor')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.marta-advogada', 'Marta is a lawyer and works in an office.', ['Marta é advogada e trabalha num escritório.', 'A Marta é advogada e trabalha num escritório.', 'Marta é advogada e trabalha em um escritório.', 'A Marta é advogada e trabalha em um escritório.'], [Kit::word('o advogado', 'advogada'), Kit::word('trabalhar', 'trabalha'), Kit::word('o escritório', 'escritório'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.joao-rui', 'João is an engineer and works in a company. Rui is a cook.', ['João é engenheiro e trabalha numa empresa. Rui é cozinheiro.', 'João é engenheiro e trabalha em uma empresa. Rui é cozinheiro.', 'O João é engenheiro e trabalha numa empresa. O Rui é cozinheiro.', 'O João é engenheiro e trabalha em uma empresa. O Rui é cozinheiro.'], [Kit::word('o engenheiro', 'engenheiro'), Kit::word('trabalhar', 'trabalha'), Kit::word('a empresa', 'empresa'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-rui', 'Ana is my colleague. Rui is my boss.', ['Ana é a minha colega. Rui é o meu chefe.', 'A Ana é a minha colega. O Rui é o meu chefe.', 'Ana é a minha colega. O Rui é o meu chefe.', 'A Ana é a minha colega. Rui é o meu chefe.'], [Kit::word('o colega', 'colega'), Kit::word('o chefe', 'chefe'), Kit::form('é', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.trabalho-como', 'I work as a cook and Marta works as a nurse.', ['Trabalho como cozinheiro e Marta trabalha como enfermeira.', 'Trabalho como cozinheira e Marta trabalha como enfermeira.', 'Eu trabalho como cozinheiro e Marta trabalha como enfermeira.', 'Eu trabalho como cozinheira e Marta trabalha como enfermeira.', 'Trabalho como cozinheiro e a Marta trabalha como enfermeira.', 'Trabalho como cozinheira e a Marta trabalha como enfermeira.', 'Eu trabalho como cozinheiro e a Marta trabalha como enfermeira.', 'Eu trabalho como cozinheira e a Marta trabalha como enfermeira.'], [Kit::word('o cozinheiro', 'cozinheiro', ['cozinheira']), Kit::word('o enfermeiro', 'enfermeira'), Kit::word('trabalhar', 'trabalho'), Kit::form('trabalho como', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.chefe-advogada', 'A minha chefe é ___.', 'My boss (a woman) is a lawyer.', 'advogada', Kit::word('o advogado', 'advogada'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ana-empresa', 'Ana trabalha numa ___.', 'Ana works in a company.', 'empresa', Kit::word('a empresa', 'empresa'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.professor-chefe', 'Sou professor e o meu chefe é engenheiro.', 'I am a teacher (man) and my boss (a man) is an engineer.', [Kit::word('o professor', 'professor'), Kit::word('o chefe', 'chefe'), Kit::word('o engenheiro', 'engenheiro'), Kit::form('sou')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.colega-escritorio', 'A minha colega trabalha num escritório.', 'My colleague (a woman) works in an office.', [Kit::word('o colega', 'colega'), Kit::word('trabalhar', 'trabalha'), Kit::word('o escritório', 'escritório')], 'dictation', $set, homophoneNote: 'The a before minha is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.tres-trabalhos', 'Ana é professora, Rui é cozinheiro e Marta é enfermeira.', 'Ana is a teacher, Rui is a cook and Marta is a nurse.', [Kit::word('o professor', 'professora'), Kit::word('o cozinheiro', 'cozinheiro'), Kit::word('o enfermeiro', 'enfermeira'), Kit::form('é')], 'dictation', $set),
        ];
    }
}
