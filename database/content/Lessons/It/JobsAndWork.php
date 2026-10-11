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

final class JobsAndWork implements UnitContent
{
    private const NOTE_VERB = 'È (is, with an accent) sounds close to e (and); here è is the verb is, so it is written with an accent.';

    private const NOTE_AND = 'E (and) has no accent and sounds close to è (is); here e joins two parts of the sentence.';

    private const NOTE_BOTH = 'E (and) has no accent and sounds close to è (is); here e joins two parts and è is the verb is.';

    private const NOTE_OR = 'O (or) sounds like ho (I have), which starts with a silent h; here o means or.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'jobs-and-work';
    }

    public function words(): array
    {
        return [
            new WordData('lavorare', cue: 'to work', forms: ['lavoro', 'lavori', 'lavora', 'lavoriamo', 'lavorate', 'lavorano']),
            new WordData('l\'insegnante', cue: 'teacher (the same for a man and a woman)', forms: ['insegnante', 'insegnanti']),
            new WordData('l\'ingegnere', cue: 'engineer (the same for a man and a woman)', forms: ['ingegnere', 'ingegnera', 'ingegneri']),
            new WordData('l\'avvocato', cue: 'lawyer (man)', accepted: ['l\'avvocata'], forms: ['avvocato', 'avvocata', 'avvocati', 'avvocate']),
            new WordData('il cuoco', cue: 'cook (man)', accepted: ['la cuoca'], forms: ['cuoco', 'cuoca', 'cuochi', 'cuoche']),
            new WordData('l\'infermiere', cue: 'nurse (man)', accepted: ['l\'infermiera'], forms: ['infermiere', 'infermiera', 'infermieri']),
            new WordData('l\'ufficio', cue: 'office', forms: ['ufficio', 'uffici']),
            new WordData('l\'azienda', cue: 'company (business)', forms: ['azienda', 'un\'azienda', 'aziende']),
            new WordData('il capo', cue: 'boss (man)', forms: ['capo', 'capa', 'capi']),
            new WordData('il collega', cue: 'colleague (man)', accepted: ['la collega'], forms: ['collega', 'colleghi', 'colleghe']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Sono cuoco.', 'english' => 'I am a cook.'],
            ['text' => 'Lavoro come cuoco in un\'azienda.', 'english' => 'I work as a cook in a company.'],
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

    /** @return list<string> */
    private function jobs(): array
    {
        return ['insegnante', 'ingegnere', 'avvocato', 'avvocata', 'cuoco', 'cuoca', 'infermiere', 'infermiera'];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.collega-avvocata', 'La mia collega ___ avvocata.', ['è', 'lavora'], 'è', Kit::form('è', true), 'Your job says what you are, so it goes with essere: è. Lavora needs come or in, as in lavora come avvocata.', 'choose', 'My colleague is a lawyer.'),
            Kit::gap($stage, 'sentences.choose_gap.lavoro-cuoco', 'Lavoro ___ cuoco.', ['come', 'in'], 'come', Kit::form('come', true), 'To say the job you do, use come: lavoro come cuoco. In is for the place where you work.', 'choose', 'I work as a cook.'),
            Kit::gap($stage, 'sentences.choose_gap.lavoro-ufficio', 'Marta lavora ___ un ufficio.', ['in', 'come'], 'in', Kit::form('in', true), 'An office is a place, so you use in. Come is for the job you do, as in lavora come insegnante.', 'choose', 'Marta works in an office.'),
            Kit::gap($stage, 'sentences.choose_gap.collega-ingegnere', 'Il mio collega è ___.', ['ingegnere', 'ufficio', 'azienda'], 'ingegnere', Kit::word('l\'ingegnere', 'ingegnere'), 'Ingegnere is a job. Ufficio and azienda are places, not things a person can be.', 'choose', 'My colleague is an engineer.'),
            Kit::gap($stage, 'sentences.choose_gap.una-azienda', 'Lavoro in un\'___.', ['azienda', 'avvocato', 'ufficio'], 'azienda', Kit::word('l\'azienda', 'azienda'), 'Un\' stands before a feminine word that starts with a vowel: un\'azienda. Avvocato and ufficio are masculine (un avvocato, un ufficio), and an avvocato is a person, not a place.', 'choose', 'I work in a company.'),
            Kit::gap($stage, 'sentences.choose_gap.anna-infermiera', 'Anna è ___.', ['infermiera', 'infermiere', 'azienda'], 'infermiera', Kit::word('l\'infermiere', 'infermiera'), 'Anna is a woman, so the job ends in -a: infermiera. Infermiere is for a man, and azienda is a company.', 'choose', 'Anna is a nurse.'),

            Kit::typeGap($stage, 'sentences.type_gap.paolo-ingegnere', 'Paolo è ___.', 'Paolo is an engineer.', 'ingegnere', Kit::word('l\'ingegnere', 'ingegnere')),
            Kit::typeGap($stage, 'sentences.type_gap.marta-avvocata', 'Marta è ___.', 'Marta is a lawyer.', 'avvocata', Kit::word('l\'avvocato', 'avvocata')),
            Kit::typeGap($stage, 'sentences.type_gap.luca-e', 'Luca ___ cuoco.', 'Luca is a cook.', 'è', Kit::form('è'), 'The job after essere usually has no article: Luca è cuoco.'),
            Kit::typeGap($stage, 'sentences.type_gap.luca-azienda', 'Luca lavora ___ un\'azienda.', 'Luca works in a company.', 'in', Kit::form('in'), 'A company is a place where you work, so you use in.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-come', 'Marta lavora ___ insegnante.', 'Marta works as a teacher.', 'come', Kit::form('come'), 'The job you do after lavorare takes come: lavora come insegnante.'),

            Kit::translate($stage, 'sentences.translate.cuoco', 'I am a cook.', ['Sono cuoco.', 'Sono cuoca.', 'Io sono cuoco.', 'Io sono cuoca.', 'Sono un cuoco.', 'Sono una cuoca.', 'Io sono un cuoco.', 'Io sono una cuoca.'], [Kit::word('il cuoco', 'cuoco', ['cuoca']), Kit::form('sono', true)]),
            Kit::translate($stage, 'sentences.translate.lavori-ufficio', 'Do you work in an office? (informal you)', ['Lavori in un ufficio?', 'Tu lavori in un ufficio?', 'Lavori in ufficio?', 'Tu lavori in ufficio?'], [Kit::word('lavorare', 'lavori'), Kit::word('l\'ufficio', 'ufficio'), Kit::form('lavori in')]),
            Kit::translate($stage, 'sentences.translate.capo-azienda', 'My boss (a man) works in a company.', ['Il mio capo lavora in un\'azienda.'], [Kit::word('il capo', 'capo'), Kit::word('l\'azienda', 'un\'azienda'), Kit::word('lavorare', 'lavora'), Kit::form('lavora in')]),

            Kit::build($stage, 'sentences.build.marta-insegnante', 'Marta is a teacher.', 'Marta è insegnante.', ['lavora'], [Kit::word('l\'insegnante', 'insegnante'), Kit::form('è', true)]),
            Kit::build($stage, 'sentences.build.collega-ufficio', 'My colleague (a man) works in an office.', 'Il mio collega lavora in un ufficio.', ['come'], [Kit::word('il collega', 'collega'), Kit::word('l\'ufficio', 'ufficio'), Kit::word('lavorare', 'lavora'), Kit::form('lavora in')]),
            Kit::build($stage, 'sentences.build.paolo-ingegnere', 'Paolo works as an engineer.', 'Paolo lavora come ingegnere.', ['in'], [Kit::word('l\'ingegnere', 'ingegnere'), Kit::word('lavorare', 'lavora'), Kit::form('lavora come')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.avvocato', 'Sono avvocato.', ['I am a lawyer.', 'I am a teacher.', 'I am a cook.', 'You are a lawyer.'], 'I am a lawyer.', [Kit::word('l\'avvocato', 'avvocato'), Kit::form('sono')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.marta-infermiera', 'Marta è infermiera.', ['Marta is a nurse.', 'Marta is a teacher.', 'Marta is a cook.', 'Anna is a nurse.'], 'Marta is a nurse.', [Kit::word('l\'infermiere', 'infermiera'), Kit::form('è')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.dove-lavori', 'Dove lavori?', ['Where do you work?', 'What do you do for work?', 'Do you work here?', 'Where do I work?'], 'Where do you work?', [Kit::word('lavorare', 'lavori')]),
            Kit::listenType($stage, 'sentences.listen_type.azienda', 'Lavoro in un\'azienda.', 'I work in a company.', [Kit::word('l\'azienda', 'un\'azienda'), Kit::word('lavorare', 'lavoro'), Kit::form('lavoro in')]),
            Kit::listenType($stage, 'sentences.listen_type.collega-ingegnere', 'Il mio collega è ingegnere.', 'My colleague (a man) is an engineer.', [Kit::word('il collega', 'collega'), Kit::word('l\'ingegnere', 'ingegnere'), Kit::form('è')], homophoneNote: self::NOTE_VERB),
            Kit::listenType($stage, 'sentences.listen_type.insegnante-cuoco', 'Sei insegnante o cuoco?', 'Are you a teacher or a cook? (informal you)', [Kit::word('l\'insegnante', 'insegnante'), Kit::word('il cuoco', 'cuoco'), Kit::form('sei')], homophoneNote: self::NOTE_OR),
            Kit::listenType($stage, 'sentences.listen_type.capo-ufficio', 'Il mio capo lavora in ufficio.', 'My boss works in an office.', [Kit::word('il capo', 'capo'), Kit::word('l\'ufficio', 'ufficio'), Kit::form('lavora in')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ingegnere', 'Sono ingegnere.', 'I am an engineer.', [Kit::word('l\'ingegnere', 'ingegnere'), Kit::form('sono')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ufficio', 'Lavoro in ufficio.', 'I work in an office.', [Kit::word('l\'ufficio', 'ufficio'), Kit::form('lavoro in')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.capo-avvocato', 'Il mio capo è avvocato.', 'My boss is a lawyer.', [Kit::word('il capo', 'capo'), Kit::word('l\'avvocato', 'avvocato'), Kit::form('è')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.collega-cuoco', 'Il mio collega lavora come cuoco.', 'My colleague works as a cook.', [Kit::word('il collega', 'collega'), Kit::word('il cuoco', 'cuoco'), Kit::form('lavora come')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.che-lavoro', 'Che lavoro fai?', 'What do you do for work?', [['sono', 'lavoro', 'faccio'], $this->jobs()], 'Sono insegnante.', [Kit::word('l\'insegnante', 'insegnante'), Kit::form('fai', false, ['faccio'])]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.dove-lavori', 'Dove lavori?', 'Where do you work?', [['lavoro', 'in'], ['ufficio', 'azienda']], 'Lavoro in un ufficio.', [Kit::word('lavorare', 'lavoro')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.sei-avvocato', 'Sei avvocato?', 'Are you a lawyer?', [['sì', 'no'], ['sono', 'avvocato', 'avvocata']], 'Sì, sono avvocato.', [Kit::word('l\'avvocato', 'avvocato')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.lavoro', 'Read the conversation about work.', [
                Kit::line('Anna', 'Che lavoro fai, Paolo?'),
                Kit::line('Paolo', 'Sono ingegnere. Lavoro in un\'azienda con il mio capo.'),
                Kit::line('Paolo', 'E tu, Anna?'),
                Kit::line('Anna', 'Sono avvocata. Lavoro in un ufficio con il mio collega.'),
            ], [
                Kit::question('What is Paolo\'s job?', ['Engineer', 'Lawyer', 'Teacher'], 'Engineer'),
                Kit::question('Where does Anna work?', ['In an office', 'In a company', 'At home'], 'In an office'),
                Kit::question('Who does Paolo work with?', ['His boss', 'His colleague', 'Anna'], 'His boss'),
            ], [Kit::word('lavorare', 'lavoro'), Kit::word('l\'ingegnere', 'ingegnere'), Kit::word('l\'azienda', 'un\'azienda'), Kit::word('il capo', 'capo'), Kit::word('l\'avvocato', 'avvocata'), Kit::word('l\'ufficio', 'ufficio'), Kit::word('il collega', 'collega'), Kit::form('fai')], 'read'),
            Kit::gap($stage, 'task.choose_gap.paolo-ingegnere', 'Paolo ___ ingegnere.', ['è', 'lavora'], 'è', Kit::form('è', true), 'With a job after it, the verb is essere: Paolo è ingegnere, which usually has no article. Lavora needs come or in, as in lavora come ingegnere.', 'read', 'Paolo is an engineer.'),
            Kit::gap($stage, 'task.choose_gap.capo-ufficio', 'Il mio capo lavora ___ ufficio.', ['in', 'come'], 'in', Kit::form('in', true), 'Ufficio is a place, so you use in. Come goes with a job, as in lavora come avvocato.', 'read', 'My boss works in an office.'),

            Kit::transform($stage, 'task.transform.marta', 'Now say it about Marta.', 'Sono insegnante.', ['Marta è insegnante.', 'Lei è insegnante.'], [Kit::word('l\'insegnante', 'insegnante'), Kit::form('è')]),
            Kit::transform($stage, 'task.transform.domanda', 'Now ask a colleague (informal you).', 'Lavoro in ufficio.', ['Lavori in ufficio?', 'Tu lavori in ufficio?', 'Lavori in un ufficio?', 'Tu lavori in un ufficio?'], [Kit::word('l\'ufficio', 'ufficio'), Kit::word('lavorare', 'lavori'), Kit::form('lavori in')]),
            Kit::transform($stage, 'task.transform.lavoro-come', 'Say that you work as an engineer.', 'Sono ingegnere.', ['Lavoro come ingegnere.', 'Io lavoro come ingegnere.'], [Kit::word('l\'ingegnere', 'ingegnere'), Kit::word('lavorare', 'lavoro'), Kit::form('lavoro come')]),
            Kit::writeGuided($stage, 'task.write_guided.cuoco', 'Say that you are a cook and that you work in a company.', ['sono', 'cuoco', 'lavoro in', 'un\'azienda'], 'Sono cuoco. Lavoro in un\'azienda.', [
                ['forms' => ['sono'], 'term' => null],
                ['forms' => ['cuoco', 'cuoca'], 'term' => 'il cuoco'],
                ['forms' => ['lavoro'], 'term' => 'lavorare'],
                ['forms' => ['azienda', 'un\'azienda'], 'term' => 'l\'azienda'],
            ], [Kit::word('il cuoco', 'cuoco'), Kit::word('lavorare', 'lavoro'), Kit::word('l\'azienda', 'un\'azienda')]),
            Kit::writeGuided($stage, 'task.write_guided.capo-collega', 'Say that your boss is a lawyer and that your colleague (a woman) is a nurse.', ['il mio capo', 'avvocato', 'la mia collega', 'infermiera'], 'Il mio capo è avvocato. La mia collega è infermiera.', [
                ['forms' => ['capo'], 'term' => 'il capo'],
                ['forms' => ['avvocato', 'avvocata'], 'term' => 'l\'avvocato'],
                ['forms' => ['collega'], 'term' => 'il collega'],
                ['forms' => ['infermiera'], 'term' => 'l\'infermiere'],
            ], [Kit::word('il capo', 'capo'), Kit::word('l\'avvocato', 'avvocato'), Kit::word('il collega', 'collega'), Kit::word('l\'infermiere', 'infermiera')]),
            Kit::build($stage, 'task.build.collega-insegnante', 'My colleague (a man) is a teacher.', 'Il mio collega è insegnante.', ['in', 'lavora'], [Kit::word('il collega', 'collega'), Kit::word('l\'insegnante', 'insegnante'), Kit::form('è', true)], 'write'),
            Kit::build($stage, 'task.build.lavoro-azienda', 'I work in a company.', 'Lavoro in un\'azienda.', ['come', 'è'], [Kit::word('l\'azienda', 'un\'azienda'), Kit::word('lavorare', 'lavoro'), Kit::form('lavoro in')], 'write'),
            Kit::build($stage, 'task.build.capo-cuoco', 'My boss (a man) works as a cook.', 'Il mio capo lavora come cuoco.', ['in', 'con'], [Kit::word('il capo', 'capo'), Kit::word('il cuoco', 'cuoco'), Kit::word('lavorare', 'lavora'), Kit::form('lavora come')], 'write'),
            Kit::translate($stage, 'task.translate.marta-luca', 'Marta is a nurse and Luca is a cook.', ['Marta è infermiera e Luca è cuoco.'], [Kit::word('l\'infermiere', 'infermiera'), Kit::word('il cuoco', 'cuoco'), Kit::form('è')], 'write'),
            Kit::translate($stage, 'task.translate.azienda-ufficio', 'Do you work in a company? No, I work in an office. (informal you)', ['Lavori in un\'azienda? No, lavoro in un ufficio.', 'Lavori in un\'azienda? No, io lavoro in un ufficio.', 'Lavori in un\'azienda? No, lavoro in ufficio.', 'Lavori in un\'azienda? No, io lavoro in ufficio.'], [Kit::word('lavorare', 'lavori'), Kit::word('l\'azienda', 'un\'azienda'), Kit::word('l\'ufficio', 'ufficio'), Kit::form('lavori in')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.lavoro', [
                Kit::line('Marta', 'Che lavoro fai, Luca?'),
                Kit::line('Luca', 'Sono cuoco. Lavoro con il mio capo.'),
                Kit::line('Luca', 'E tu, Marta?'),
                Kit::line('Marta', 'Sono infermiera. Lavoro con la mia collega.'),
            ], [
                Kit::question('What is Luca\'s job?', ['Cook', 'Nurse', 'Engineer'], 'Cook'),
                Kit::question('Who does Luca work with?', ['His boss', 'His colleague', 'Marta'], 'His boss'),
                Kit::question('What is Marta\'s job?', ['Nurse', 'Cook', 'Lawyer'], 'Nurse'),
            ], [
                Kit::question('Who asks about the job first?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('Is Marta a nurse?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('lavorare', 'lavoro'), Kit::word('il cuoco', 'cuoco'), Kit::word('il capo', 'capo'), Kit::word('l\'infermiere', 'infermiera'), Kit::word('il collega', 'collega'), Kit::form('fai')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.insegnante-collega', 'Sono insegnante e lavoro con il mio collega.', 'I am a teacher and I work with my colleague (a man).', [Kit::word('l\'insegnante', 'insegnante'), Kit::word('il collega', 'collega'), Kit::word('lavorare', 'lavoro'), Kit::form('sono')], 'listen', homophoneNote: self::NOTE_AND),
            Kit::listenType($stage, 'task.listen_type.capo-avvocato', 'Il mio capo è avvocato e lavora in un ufficio.', 'My boss (a man) is a lawyer and works in an office.', [Kit::word('il capo', 'capo'), Kit::word('l\'avvocato', 'avvocato'), Kit::word('l\'ufficio', 'ufficio'), Kit::word('lavorare', 'lavora'), Kit::form('è')], 'listen', homophoneNote: self::NOTE_BOTH),
            Kit::listenType($stage, 'task.listen_type.azienda-ufficio', 'Lavori in un\'azienda o in un ufficio?', 'Do you work in a company or in an office? (informal you)', [Kit::word('l\'azienda', 'un\'azienda'), Kit::word('l\'ufficio', 'ufficio'), Kit::word('lavorare', 'lavori'), Kit::form('lavori in')], 'listen', homophoneNote: self::NOTE_OR),

            Kit::speakAnswer($stage, 'task.speak_answer.ufficio', 'Lavori in un ufficio?', 'Do you work in an office?', [['sì', 'no'], ['lavoro', 'ufficio', 'azienda']], 'Sì, lavoro in un ufficio.', [Kit::word('l\'ufficio', 'ufficio'), Kit::word('lavorare', 'lavoro')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.capo', 'Che lavoro fa il tuo capo?', 'What does your boss do for work?', [['è', 'lavora', 'mio'], $this->jobs()], 'Il mio capo è avvocato.', [Kit::word('il capo', 'capo'), Kit::form('fa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.infermiere-cuoco', 'Sei infermiere o cuoco?', 'Are you a nurse or a cook?', [['sono'], ['infermiere', 'infermiera', 'cuoco', 'cuoca']], 'Sono cuoco.', [Kit::word('l\'infermiere', 'infermiere'), Kit::word('il cuoco', 'cuoco')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.con-chi', 'Con chi lavori?', 'Who do you work with?', [['lavoro', 'con'], ['collega', 'capo']], 'Lavoro con il mio collega.', [Kit::word('il collega', 'collega'), Kit::word('il capo', 'capo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.collega-infermiera', 'La mia collega è infermiera e lavora qui.', 'My colleague (a woman) is a nurse and works here.', [Kit::word('il collega', 'collega'), Kit::word('l\'infermiere', 'infermiera'), Kit::form('è')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cuoco-capo', 'Sono cuoco e lavoro con il mio capo.', 'I am a cook and I work with my boss.', [Kit::word('il cuoco', 'cuoco'), Kit::word('il capo', 'capo'), Kit::word('lavorare', 'lavoro'), Kit::form('sono')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.collega-ingegnere', 'Your colleague is an engineer. (informal you)', ['Il tuo collega è ingegnere.', 'La tua collega è ingegnere.'], [Kit::word('il collega', 'collega'), Kit::word('l\'ingegnere', 'ingegnere'), Kit::form('è', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.anna-insegnante', 'Anna works as a teacher.', ['Anna lavora come insegnante.'], [Kit::word('lavorare', 'lavora'), Kit::word('l\'insegnante', 'insegnante'), Kit::form('lavora come', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.dove-lavori', 'Where do you work? I work in an office. (informal you)', ['Dove lavori? Lavoro in un ufficio.', 'Dove lavori? Io lavoro in un ufficio.', 'Dove lavori? Lavoro in ufficio.'], [Kit::word('lavorare', 'lavori'), Kit::word('l\'ufficio', 'ufficio'), Kit::form('lavoro in')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.tre-lavori', 'Luca is a cook, Marta is a nurse and Anna is a lawyer.', ['Luca è cuoco, Marta è infermiera e Anna è avvocata.'], [Kit::word('il cuoco', 'cuoco'), Kit::word('l\'infermiere', 'infermiera'), Kit::word('l\'avvocato', 'avvocata'), Kit::form('è')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.paolo-azienda', 'Paolo lavora in un\'___.', 'Paolo works in a company.', 'azienda', Kit::word('l\'azienda', 'azienda'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.collega-infermiera', 'La mia collega è ___.', 'My colleague (a woman) is a nurse.', 'infermiera', Kit::word('l\'infermiere', 'infermiera'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.collega-avvocata', 'La mia collega è avvocata e lavora in ufficio.', 'My colleague (a woman) is a lawyer and works in an office.', [Kit::word('il collega', 'collega'), Kit::word('l\'avvocato', 'avvocata'), Kit::word('l\'ufficio', 'ufficio'), Kit::word('lavorare', 'lavora'), Kit::form('è')], 'dictation', $set, homophoneNote: self::NOTE_BOTH),
            Kit::listenType($stage, 'check.a.listen_type.sei', 'Sei insegnante, ingegnere o cuoco?', 'Are you a teacher, an engineer or a cook? (informal you)', [Kit::word('l\'insegnante', 'insegnante'), Kit::word('l\'ingegnere', 'ingegnere'), Kit::word('il cuoco', 'cuoco'), Kit::form('sei')], 'dictation', $set, homophoneNote: self::NOTE_OR),
            Kit::listenType($stage, 'check.a.listen_type.capo-collega', 'Lavoro con il mio capo e il mio collega.', 'I work with my boss and my colleague (both men).', [Kit::word('lavorare', 'lavoro'), Kit::word('il capo', 'capo'), Kit::word('il collega', 'collega')], 'dictation', $set, homophoneNote: self::NOTE_AND),
            Kit::listenPassage($stage, 'check.a.listen_passage.ufficio', [
                Kit::line('Paolo', 'Anna, dove lavori?'),
                Kit::line('Anna', 'Lavoro in un ufficio. Sono avvocata.'),
                Kit::line('Anna', 'E tu, Paolo?'),
                Kit::line('Paolo', 'Sono cuoco. Io e il mio collega lavoriamo qui.'),
            ], [
                Kit::question('Where does Anna work?', ['In an office', 'In a company', 'Here'], 'In an office'),
                Kit::question('What is Paolo\'s job?', ['Cook', 'Lawyer', 'Engineer'], 'Cook'),
                Kit::question('Who works with Paolo?', ['His colleague', 'His boss', 'Anna'], 'His colleague'),
            ], [
                Kit::question('Who asks the first question?', ['Paolo', 'Anna', 'Nobody'], 'Paolo'),
                Kit::question('Is Anna a lawyer?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('l\'ufficio', 'ufficio'), Kit::word('l\'avvocato', 'avvocata'), Kit::word('il cuoco', 'cuoco'), Kit::word('il collega', 'collega'), Kit::word('lavorare', 'lavoro')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ingegnere', 'Read the conversation.', [
                Kit::line('Marta', 'Luca, sei ingegnere?'),
                Kit::line('Luca', 'No, sono insegnante. Il mio capo è ingegnere.'),
                Kit::line('Luca', 'E tu?'),
                Kit::line('Marta', 'Io sono infermiera.'),
            ], [
                Kit::question('What is Luca\'s job?', ['Teacher', 'Engineer', 'Nurse'], 'Teacher'),
                Kit::question('Who is the engineer?', ['Luca\'s boss', 'Luca', 'Marta'], 'Luca\'s boss'),
            ], [Kit::word('l\'ingegnere', 'ingegnere'), Kit::word('l\'insegnante', 'insegnante'), Kit::word('il capo', 'capo'), Kit::word('l\'infermiere', 'infermiera')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.insegnante-ingegnere', 'Sei insegnante o ingegnere?', 'Are you a teacher or an engineer?', [['sono'], ['insegnante', 'ingegnere']], 'Sono insegnante.', [Kit::word('l\'insegnante', 'insegnante'), Kit::word('l\'ingegnere', 'ingegnere')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.azienda', 'Lavori in un\'azienda?', 'Do you work in a company?', [['sì', 'no'], ['lavoro', 'azienda', 'ufficio']], 'Sì, lavoro in un\'azienda.', [Kit::word('l\'azienda', 'azienda'), Kit::word('lavorare', 'lavoro')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.collega', 'Che lavoro fa il tuo collega?', 'What does your colleague do for work?', [['è', 'mio', 'lavora'], $this->jobs()], 'Il mio collega è insegnante.', [Kit::word('il collega', 'collega'), Kit::word('l\'insegnante', 'insegnante'), Kit::form('fa')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.marta-avvocata', 'Marta is a lawyer and works in an office.', ['Marta è avvocata e lavora in un ufficio.', 'Marta è avvocata e lavora in ufficio.'], [Kit::word('l\'avvocato', 'avvocata'), Kit::word('lavorare', 'lavora'), Kit::word('l\'ufficio', 'ufficio'), Kit::form('è')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.paolo-luca', 'Paolo and Luca are engineers and work in a company.', ['Paolo e Luca sono ingegneri e lavorano in un\'azienda.'], [Kit::word('l\'ingegnere', 'ingegneri'), Kit::word('lavorare', 'lavorano'), Kit::word('l\'azienda', 'un\'azienda'), Kit::form('sono')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.anna-luca', 'Anna is my colleague and Luca is the boss.', ['Anna è la mia collega e Luca è il capo.'], [Kit::word('il collega', 'collega'), Kit::word('il capo', 'capo'), Kit::form('è', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.lavoro-come', 'I work as a cook and Marta works as a nurse.', ['Lavoro come cuoco e Marta lavora come infermiera.', 'Lavoro come cuoca e Marta lavora come infermiera.', 'Io lavoro come cuoco e Marta lavora come infermiera.', 'Io lavoro come cuoca e Marta lavora come infermiera.'], [Kit::word('il cuoco', 'cuoco', ['cuoca']), Kit::word('l\'infermiere', 'infermiera'), Kit::word('lavorare', 'lavoro'), Kit::form('lavoro come', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.collega-avvocato', 'Il mio collega è ___.', 'My colleague (a man) is a lawyer.', 'avvocato', Kit::word('l\'avvocato', 'avvocato'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.anna-azienda', 'Il mio collega lavora in un\'___.', 'My colleague (a man) works in a company.', 'azienda', Kit::word('l\'azienda', 'azienda'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.insegnante-capo', 'Sono insegnante e il mio capo è ingegnere.', 'I am a teacher and my boss (a man) is an engineer.', [Kit::word('l\'insegnante', 'insegnante'), Kit::word('il capo', 'capo'), Kit::word('l\'ingegnere', 'ingegnere'), Kit::form('sono')], 'dictation', $set, homophoneNote: self::NOTE_BOTH),
            Kit::listenType($stage, 'check.b.listen_type.collega-ufficio', 'La mia collega lavora in un ufficio.', 'My colleague (a woman) works in an office.', [Kit::word('il collega', 'collega'), Kit::word('lavorare', 'lavora'), Kit::word('l\'ufficio', 'ufficio')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.tre-lavori', 'Anna è insegnante, Luca è cuoco e Marta è infermiera.', 'Anna is a teacher, Luca is a cook and Marta is a nurse.', [Kit::word('l\'insegnante', 'insegnante'), Kit::word('il cuoco', 'cuoco'), Kit::word('l\'infermiere', 'infermiera'), Kit::form('è')], 'dictation', $set, homophoneNote: self::NOTE_BOTH),
        ];
    }
}
