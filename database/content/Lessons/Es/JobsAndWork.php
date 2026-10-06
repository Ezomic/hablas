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

final class JobsAndWork implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'jobs-and-work';
    }

    public function words(): array
    {
        return [
            new WordData('trabajar', cue: 'to work', forms: ['trabajo', 'trabajas', 'trabaja', 'trabajamos', 'trabajan']),
            new WordData('el profesor', cue: 'teacher (man)', accepted: ['la profesora'], forms: ['profesora', 'profesores', 'profesoras']),
            new WordData('el ingeniero', cue: 'engineer (man)', accepted: ['la ingeniera'], forms: ['ingeniera', 'ingenieros', 'ingenieras']),
            new WordData('el abogado', cue: 'lawyer (man)', accepted: ['la abogada'], forms: ['abogada', 'abogados', 'abogadas']),
            new WordData('el cocinero', cue: 'cook (man)', accepted: ['la cocinera'], forms: ['cocinera', 'cocineros', 'cocineras']),
            new WordData('el enfermero', cue: 'nurse (man)', accepted: ['la enfermera'], forms: ['enfermera', 'enfermeros', 'enfermeras']),
            new WordData('la oficina', cue: 'office', forms: ['oficinas']),
            new WordData('la empresa', cue: 'company (business)', forms: ['empresas']),
            new WordData('el jefe', cue: 'boss (man)', accepted: ['la jefa'], forms: ['jefa', 'jefes', 'jefas']),
            new WordData('el compañero', cue: 'colleague (man)', accepted: ['la compañera'], forms: ['compañera', 'compañeros', 'compañeras']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Soy cocinero.', 'english' => 'I am a cook.'],
            ['text' => 'Trabajo de cocinero en una empresa.', 'english' => 'I work as a cook in a company.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-06', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<string> */
    private function jobs(): array
    {
        return ['profesor', 'profesora', 'ingeniero', 'ingeniera', 'abogado', 'abogada', 'cocinero', 'cocinera', 'enfermero', 'enfermera'];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.compañera-abogada', 'Mi compañera ___ abogada.', ['es', 'está'], 'es', Kit::form('es', true), 'Your job says what you are, so it goes with ser: es. Está is for how someone feels or where they are.', 'choose', 'My colleague is a lawyer.'),
            Kit::gap($stage, 'sentences.choose_gap.trabajo-cocinero', 'Trabajo ___ cocinero.', ['de', 'en'], 'de', Kit::form('de', true), 'To say the job you do, use de: trabajo de cocinero. En is for the place where you work.', 'choose', 'I work as a cook.'),
            Kit::gap($stage, 'sentences.choose_gap.trabajo-oficina', 'Marta trabaja ___ una oficina.', ['en', 'de'], 'en', Kit::form('en', true), 'An office is a place, so you use en. De is for the job you do, as in trabaja de profesora.', 'choose', 'Marta works in an office.'),
            Kit::gap($stage, 'sentences.choose_gap.compañero-ingeniero', 'Mi compañero es ___.', ['ingeniero', 'oficina', 'empresa'], 'ingeniero', Kit::word('el ingeniero', 'ingeniero'), 'Ingeniero is a job. Oficina and empresa are places, not things a person can be.', 'choose', 'My colleague is an engineer.'),
            Kit::gap($stage, 'sentences.choose_gap.una-empresa', 'Trabajo en una ___.', ['empresa', 'abogado', 'profesor'], 'empresa', Kit::word('la empresa', 'empresa'), 'La empresa is a company, a place to work in. Abogado and profesor are masculine jobs, so they do not fit after una.', 'choose', 'I work in a company.'),
            Kit::gap($stage, 'sentences.choose_gap.ana-enfermera', 'Ana es ___.', ['enfermera', 'enfermero', 'empresa'], 'enfermera', Kit::word('el enfermero', 'enfermera'), 'Ana is a woman, so the job ends in -a: enfermera. Enfermero is for a man, and empresa is a company.', 'choose', 'Ana is a nurse.'),

            Kit::typeGap($stage, 'sentences.type_gap.pablo-ingeniero', 'Pablo es ___.', 'Pablo is an engineer.', 'ingeniero', Kit::word('el ingeniero', 'ingeniero')),
            Kit::typeGap($stage, 'sentences.type_gap.marta-abogada', 'Marta es ___.', 'Marta is a lawyer.', 'abogada', Kit::word('el abogado', 'abogada')),
            Kit::typeGap($stage, 'sentences.type_gap.luis-es', 'Luis ___ cocinero.', 'Luis is a cook.', 'es', Kit::form('es'), 'The job after ser has no article: Luis es cocinero, not Luis es un cocinero.'),
            Kit::typeGap($stage, 'sentences.type_gap.luis-empresa', 'Luis trabaja ___ una empresa.', 'Luis works in a company.', 'en', Kit::form('en'), 'A company is a place where you work, so you use en.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-de', 'Marta trabaja ___ profesora.', 'Marta works as a teacher.', 'de', Kit::form('de'), 'The job you do after trabajar takes de: trabaja de profesora.'),

            Kit::translate($stage, 'sentences.translate.cocinero', 'I am a cook.', ['Soy cocinero.', 'Soy cocinera.', 'Yo soy cocinero.', 'Yo soy cocinera.'], [Kit::word('el cocinero', 'cocinero', ['cocinera']), Kit::form('soy', true)]),
            Kit::translate($stage, 'sentences.translate.trabajas-oficina', 'Do you work in an office? (informal you)', ['¿Trabajas en una oficina?', '¿Tú trabajas en una oficina?'], [Kit::word('trabajar', 'trabajas'), Kit::word('la oficina', 'oficina'), Kit::form('trabajas en')]),
            Kit::translate($stage, 'sentences.translate.jefe-empresa', 'My boss works in a company.', ['Mi jefe trabaja en una empresa.', 'Mi jefa trabaja en una empresa.'], [Kit::word('el jefe', 'jefe', ['jefa']), Kit::word('la empresa', 'empresa'), Kit::word('trabajar', 'trabaja'), Kit::form('trabaja en')]),

            Kit::build($stage, 'sentences.build.marta-profesora', 'Marta is a teacher.', 'Marta es profesora.', ['un'], [Kit::word('el profesor', 'profesora'), Kit::form('es', true)]),
            Kit::build($stage, 'sentences.build.compañero-oficina', 'My colleague (a man) works in an office.', 'Mi compañero trabaja en una oficina.', ['de'], [Kit::word('el compañero', 'compañero'), Kit::word('la oficina', 'oficina'), Kit::word('trabajar', 'trabaja'), Kit::form('trabaja en')]),
            Kit::build($stage, 'sentences.build.pablo-ingeniero', 'Pablo works as an engineer.', 'Pablo trabaja de ingeniero.', ['en'], [Kit::word('el ingeniero', 'ingeniero'), Kit::word('trabajar', 'trabaja'), Kit::form('trabaja de')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.abogado', 'Soy abogado.', ['I am a lawyer.', 'I am a teacher.', 'I am a cook.', 'You are a lawyer.'], 'I am a lawyer.', [Kit::word('el abogado', 'abogado'), Kit::form('soy')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.marta-enfermera', 'Marta es enfermera.', ['Marta is a nurse.', 'Marta is a teacher.', 'Marta is a cook.', 'Ana is a nurse.'], 'Marta is a nurse.', [Kit::word('el enfermero', 'enfermera'), Kit::form('es')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.en-que-trabajas', '¿En qué trabajas?', ['What do you do for work?', 'Where do you work?', 'Do you work here?', 'What do I do for work?'], 'What do you do for work?', [Kit::word('trabajar', 'trabajas')]),
            Kit::listenType($stage, 'sentences.listen_type.empresa', 'Trabajo en una empresa.', 'I work in a company.', [Kit::word('la empresa', 'empresa'), Kit::word('trabajar', 'trabajo'), Kit::form('trabajo en')]),
            Kit::listenType($stage, 'sentences.listen_type.compañero-ingeniero', 'Mi compañero es ingeniero.', 'My colleague (a man) is an engineer.', [Kit::word('el compañero', 'compañero'), Kit::word('el ingeniero', 'ingeniero'), Kit::form('es')]),
            Kit::listenType($stage, 'sentences.listen_type.profesor-cocinero', '¿Eres profesor o cocinero?', 'Are you a teacher or a cook? (informal you)', [Kit::word('el profesor', 'profesor'), Kit::word('el cocinero', 'cocinero'), Kit::form('eres')]),
            Kit::listenType($stage, 'sentences.listen_type.jefa-oficina', 'Mi jefa trabaja en la oficina.', 'My boss (a woman) works in the office.', [Kit::word('el jefe', 'jefa'), Kit::word('la oficina', 'oficina'), Kit::form('trabaja en')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ingeniero', 'Soy ingeniero.', 'I am an engineer.', [Kit::word('el ingeniero', 'ingeniero'), Kit::form('soy')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.oficina', 'Trabajo en la oficina.', 'I work in the office.', [Kit::word('la oficina', 'oficina'), Kit::form('trabajo en')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.jefe-abogado', 'Mi jefe es abogado.', 'My boss is a lawyer.', [Kit::word('el jefe', 'jefe'), Kit::word('el abogado', 'abogado'), Kit::form('es')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.compañero-cocinero', 'Mi compañero trabaja de cocinero.', 'My colleague works as a cook.', [Kit::word('el compañero', 'compañero'), Kit::word('el cocinero', 'cocinero'), Kit::form('trabaja de')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.en-que-trabajas', '¿En qué trabajas?', 'What do you do for work?', [['soy', 'trabajo'], $this->jobs()], 'Soy profesor.', [Kit::word('el profesor', 'profesor'), Kit::form('soy')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.donde-trabajas', '¿Dónde trabajas?', 'Where do you work?', [['trabajo', 'en'], ['oficina', 'empresa']], 'Trabajo en una oficina.', [Kit::word('trabajar', 'trabajo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.eres-abogado', '¿Eres abogado?', 'Are you a lawyer?', [['sí', 'no'], ['soy', 'abogado', 'abogada']], 'Sí, soy abogado.', [Kit::word('el abogado', 'abogado')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.trabajo', 'Read the conversation about work.', [
                Kit::line('Ana', '¿En qué trabajas, Pablo?'),
                Kit::line('Pablo', 'Soy ingeniero. Trabajo en una empresa con mi jefe.'),
                Kit::line('Pablo', '¿Y tú, Ana?'),
                Kit::line('Ana', 'Soy abogada. Trabajo en una oficina con mi compañero.'),
            ], [
                Kit::question('What is Pablo\'s job?', ['Engineer', 'Lawyer', 'Teacher'], 'Engineer'),
                Kit::question('Where does Ana work?', ['In an office', 'In a company', 'At home'], 'In an office'),
                Kit::question('Who does Pablo work with?', ['His boss', 'His colleague', 'Ana'], 'His boss'),
            ], [Kit::word('trabajar', 'trabajo'), Kit::word('el ingeniero', 'ingeniero'), Kit::word('la empresa', 'empresa'), Kit::word('el jefe', 'jefe'), Kit::word('el abogado', 'abogada'), Kit::word('la oficina', 'oficina'), Kit::word('el compañero', 'compañero')], 'read'),
            Kit::gap($stage, 'task.choose_gap.pablo-ingeniero', 'Pablo ___ ingeniero.', ['es', 'trabaja'], 'es', Kit::form('es', true), 'With a job after it, the verb is ser: Pablo es ingeniero, with no un. Trabaja needs de or en, as in trabaja de ingeniero.', 'read', 'Pablo is an engineer.'),
            Kit::gap($stage, 'task.choose_gap.jefe-oficina', 'Mi jefe trabaja ___ la oficina.', ['en', 'de'], 'en', Kit::form('en', true), 'La oficina is a place, so you use en. De goes with a job, as in trabaja de abogado.', 'read', 'My boss works in the office.'),

            Kit::transform($stage, 'task.transform.marta', 'Now say it about Marta.', 'Soy profesor.', ['Marta es profesora.'], [Kit::word('el profesor', 'profesora'), Kit::form('es')]),
            Kit::transform($stage, 'task.transform.pregunta', 'Now ask a colleague (informal you).', 'Trabajo en la oficina.', ['¿Trabajas en la oficina?', '¿Tú trabajas en la oficina?'], [Kit::word('la oficina', 'oficina'), Kit::word('trabajar', 'trabajas'), Kit::form('trabajas en')]),
            Kit::transform($stage, 'task.transform.trabajo-de', 'Say that you work as an engineer.', 'Soy ingeniero.', ['Trabajo de ingeniero.', 'Yo trabajo de ingeniero.'], [Kit::word('el ingeniero', 'ingeniero'), Kit::word('trabajar', 'trabajo'), Kit::form('trabajo de')]),
            Kit::writeGuided($stage, 'task.write_guided.cocinero', 'Say that you are a cook and that you work in a company.', ['soy', 'cocinero', 'trabajo en', 'empresa'], 'Soy cocinero. Trabajo en una empresa.', [
                ['forms' => ['soy'], 'term' => null],
                ['forms' => ['cocinero', 'cocinera'], 'term' => 'el cocinero'],
                ['forms' => ['trabajo'], 'term' => 'trabajar'],
                ['forms' => ['empresa'], 'term' => 'la empresa'],
            ], [Kit::word('el cocinero', 'cocinero'), Kit::word('trabajar', 'trabajo'), Kit::word('la empresa', 'empresa')]),
            Kit::writeGuided($stage, 'task.write_guided.jefe-compañera', 'Say that your boss is a lawyer and that your colleague (a woman) is a nurse.', ['mi jefe', 'abogado', 'mi compañera', 'enfermera'], 'Mi jefe es abogado. Mi compañera es enfermera.', [
                ['forms' => ['jefe', 'jefa'], 'term' => 'el jefe'],
                ['forms' => ['abogado', 'abogada'], 'term' => 'el abogado'],
                ['forms' => ['compañera', 'compañero'], 'term' => 'el compañero'],
                ['forms' => ['enfermera', 'enfermero'], 'term' => 'el enfermero'],
            ], [Kit::word('el jefe', 'jefe'), Kit::word('el abogado', 'abogado'), Kit::word('el compañero', 'compañera'), Kit::word('el enfermero', 'enfermera')]),
            Kit::build($stage, 'task.build.compañero-profesor', 'My colleague (a man) is a teacher.', 'Mi compañero es profesor.', ['en', 'trabaja'], [Kit::word('el compañero', 'compañero'), Kit::word('el profesor', 'profesor'), Kit::form('es', true)], 'write'),
            Kit::build($stage, 'task.build.trabajo-empresa', 'I work in a company.', 'Trabajo en una empresa.', ['de', 'es'], [Kit::word('la empresa', 'empresa'), Kit::word('trabajar', 'trabajo'), Kit::form('trabajo en')], 'write'),
            Kit::build($stage, 'task.build.jefe-cocinero', 'My boss (a man) works as a cook.', 'Mi jefe trabaja de cocinero.', ['en', 'con'], [Kit::word('el jefe', 'jefe'), Kit::word('el cocinero', 'cocinero'), Kit::word('trabajar', 'trabaja'), Kit::form('trabaja de')], 'write'),
            Kit::translate($stage, 'task.translate.marta-luis', 'Marta is a nurse and Luis is a cook.', ['Marta es enfermera y Luis es cocinero.'], [Kit::word('el enfermero', 'enfermera'), Kit::word('el cocinero', 'cocinero'), Kit::form('es')], 'write'),
            Kit::translate($stage, 'task.translate.empresa-oficina', 'Do you work in a company? No, I work in the office. (informal you)', ['¿Trabajas en una empresa? No, trabajo en la oficina.', '¿Trabajas en una empresa? No, yo trabajo en la oficina.'], [Kit::word('trabajar', 'trabajas'), Kit::word('la empresa', 'empresa'), Kit::word('la oficina', 'oficina'), Kit::form('trabajas en')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.trabajo', [
                Kit::line('Marta', '¿En qué trabajas, Luis?'),
                Kit::line('Luis', 'Soy cocinero. Trabajo con mi jefe.'),
                Kit::line('Luis', '¿Y tú, Marta?'),
                Kit::line('Marta', 'Soy enfermera. Trabajo con mi compañera.'),
            ], [
                Kit::question('What is Luis\'s job?', ['Cook', 'Nurse', 'Engineer'], 'Cook'),
                Kit::question('Who does Luis work with?', ['His boss', 'His colleague', 'Marta'], 'His boss'),
                Kit::question('What is Marta\'s job?', ['Nurse', 'Cook', 'Lawyer'], 'Nurse'),
            ], [
                Kit::question('Who asks about the job first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is Marta a nurse?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('trabajar', 'trabajo'), Kit::word('el cocinero', 'cocinero'), Kit::word('el jefe', 'jefe'), Kit::word('el enfermero', 'enfermera'), Kit::word('el compañero', 'compañera')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.profesora-compañero', 'Soy profesora y trabajo con mi compañero.', 'I am a teacher (woman) and I work with my colleague (a man).', [Kit::word('el profesor', 'profesora'), Kit::word('el compañero', 'compañero'), Kit::word('trabajar', 'trabajo'), Kit::form('soy')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.jefe-abogado', 'Mi jefe es abogado y trabaja en una oficina.', 'My boss (a man) is a lawyer and works in an office.', [Kit::word('el jefe', 'jefe'), Kit::word('el abogado', 'abogado'), Kit::word('la oficina', 'oficina'), Kit::word('trabajar', 'trabaja'), Kit::form('es')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.empresa-oficina', '¿Trabajas en una empresa o en una oficina?', 'Do you work in a company or in an office? (informal you)', [Kit::word('la empresa', 'empresa'), Kit::word('la oficina', 'oficina'), Kit::word('trabajar', 'trabajas'), Kit::form('trabajas en')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.oficina', '¿Trabajas en una oficina?', 'Do you work in an office?', [['sí', 'no'], ['trabajo', 'oficina', 'empresa']], 'Sí, trabajo en una oficina.', [Kit::word('la oficina', 'oficina'), Kit::word('trabajar', 'trabajo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.jefe', '¿En qué trabaja tu jefe?', 'What does your boss do for work?', [['es', 'trabaja', 'mi'], $this->jobs()], 'Mi jefe es abogado.', [Kit::word('el jefe', 'jefe')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.enfermero-cocinero', '¿Eres enfermero o cocinero?', 'Are you a nurse or a cook?', [['soy'], ['enfermero', 'enfermera', 'cocinero', 'cocinera']], 'Soy cocinero.', [Kit::word('el enfermero', 'enfermero'), Kit::word('el cocinero', 'cocinero')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.con-quien', '¿Con quién trabajas?', 'Who do you work with?', [['trabajo', 'con'], ['compañero', 'compañera', 'jefe', 'jefa']], 'Trabajo con mi compañero.', [Kit::word('el compañero', 'compañero'), Kit::word('el jefe', 'jefe')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.compañera-enfermera', 'Mi compañera es enfermera y trabaja aquí.', 'My colleague (a woman) is a nurse and works here.', [Kit::word('el compañero', 'compañera'), Kit::word('el enfermero', 'enfermera'), Kit::form('es')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cocinero-jefe', 'Soy cocinero y trabajo con mi jefe.', 'I am a cook and I work with my boss.', [Kit::word('el cocinero', 'cocinero'), Kit::word('el jefe', 'jefe'), Kit::word('trabajar', 'trabajo'), Kit::form('soy')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.compañero-ingeniero', 'Your colleague is an engineer. (informal you)', ['Tu compañero es ingeniero.', 'Tu compañera es ingeniera.'], [Kit::word('el compañero', 'compañero', ['compañera']), Kit::word('el ingeniero', 'ingeniero', ['ingeniera']), Kit::form('es', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-profesora', 'Ana works as a teacher.', ['Ana trabaja de profesora.'], [Kit::word('trabajar', 'trabaja'), Kit::word('el profesor', 'profesora'), Kit::form('trabaja de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.donde-trabajas', 'Where do you work? I work in an office. (informal you)', ['¿Dónde trabajas? Trabajo en una oficina.', '¿Dónde trabajas? Yo trabajo en una oficina.'], [Kit::word('trabajar', 'trabajas'), Kit::word('la oficina', 'oficina'), Kit::form('trabajo en')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.tres-trabajos', 'Luis is a cook, Marta is a nurse and Ana is a lawyer.', ['Luis es cocinero, Marta es enfermera y Ana es abogada.'], [Kit::word('el cocinero', 'cocinero'), Kit::word('el enfermero', 'enfermera'), Kit::word('el abogado', 'abogada'), Kit::form('es')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pablo-empresa', 'Pablo trabaja en una ___.', 'Pablo works in a company.', 'empresa', Kit::word('la empresa', 'empresa'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.compañera-enfermera', 'Mi compañera es ___.', 'My colleague (a woman) is a nurse.', 'enfermera', Kit::word('el enfermero', 'enfermera'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.jefa-abogada', 'Mi jefa es abogada y trabaja en una oficina.', 'My boss (a woman) is a lawyer and works in an office.', [Kit::word('el jefe', 'jefa'), Kit::word('el abogado', 'abogada'), Kit::word('la oficina', 'oficina'), Kit::word('trabajar', 'trabaja'), Kit::form('es')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.eres', '¿Eres profesor, ingeniero o cocinero?', 'Are you a teacher, an engineer or a cook? (informal you)', [Kit::word('el profesor', 'profesor'), Kit::word('el ingeniero', 'ingeniero'), Kit::word('el cocinero', 'cocinero'), Kit::form('eres')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.jefe-compañero', 'Trabajo en una empresa con mi jefe y mi compañero.', 'I work in a company with my boss and my colleague (both men).', [Kit::word('trabajar', 'trabajo'), Kit::word('la empresa', 'empresa'), Kit::word('el jefe', 'jefe'), Kit::word('el compañero', 'compañero')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.oficina', [
                Kit::line('Pablo', 'Ana, ¿dónde trabajas?'),
                Kit::line('Ana', 'Trabajo en una oficina. Soy abogada.'),
                Kit::line('Ana', '¿Y tú, Pablo?'),
                Kit::line('Pablo', 'Soy cocinero. Mi compañero y yo trabajamos aquí.'),
            ], [
                Kit::question('Where does Ana work?', ['In an office', 'In a company', 'Here'], 'In an office'),
                Kit::question('What is Pablo\'s job?', ['Cook', 'Lawyer', 'Engineer'], 'Cook'),
                Kit::question('Who works with Pablo?', ['His colleague', 'His boss', 'Ana'], 'His colleague'),
            ], [
                Kit::question('Who asks the first question?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Is Ana a lawyer?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la oficina', 'oficina'), Kit::word('el abogado', 'abogada'), Kit::word('el cocinero', 'cocinero'), Kit::word('el compañero', 'compañero'), Kit::word('trabajar', 'trabajo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ingeniero', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿eres ingeniero?'),
                Kit::line('Luis', 'No, soy profesor. Mi jefe es ingeniero.'),
                Kit::line('Luis', '¿Y tú?'),
                Kit::line('Marta', 'Yo soy enfermera.'),
            ], [
                Kit::question('What is Luis\'s job?', ['Teacher', 'Engineer', 'Nurse'], 'Teacher'),
                Kit::question('Who is the engineer?', ['Luis\'s boss', 'Luis', 'Marta'], 'Luis\'s boss'),
            ], [Kit::word('el ingeniero', 'ingeniero'), Kit::word('el profesor', 'profesor'), Kit::word('el jefe', 'jefe'), Kit::word('el enfermero', 'enfermera')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.profesor-ingeniero', '¿Eres profesor o ingeniero?', 'Are you a teacher or an engineer?', [['soy'], ['profesor', 'profesora', 'ingeniero', 'ingeniera']], 'Soy profesor.', [Kit::word('el profesor', 'profesor'), Kit::word('el ingeniero', 'ingeniero')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.empresa', '¿Trabajas en una empresa?', 'Do you work in a company?', [['sí', 'no'], ['trabajo', 'empresa', 'oficina']], 'Sí, trabajo en una empresa.', [Kit::word('la empresa', 'empresa'), Kit::word('trabajar', 'trabajo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.compañero', '¿En qué trabaja tu compañero?', 'What does your colleague do for work?', [['es', 'mi', 'trabaja'], $this->jobs()], 'Mi compañero es profesor.', [Kit::word('el compañero', 'compañero'), Kit::word('el profesor', 'profesor')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.marta-abogada', 'Marta is a lawyer and works in an office.', ['Marta es abogada y trabaja en una oficina.'], [Kit::word('el abogado', 'abogada'), Kit::word('trabajar', 'trabaja'), Kit::word('la oficina', 'oficina'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pablo-luis', 'Pablo and Luis are engineers and work in a company.', ['Pablo y Luis son ingenieros y trabajan en una empresa.'], [Kit::word('el ingeniero', 'ingenieros'), Kit::word('trabajar', 'trabajan'), Kit::word('la empresa', 'empresa'), Kit::form('son')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-luis', 'Ana is my colleague and Luis is my boss.', ['Ana es mi compañera y Luis es mi jefe.'], [Kit::word('el compañero', 'compañera'), Kit::word('el jefe', 'jefe'), Kit::form('es', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.trabajo-de', 'I work as a cook and Marta works as a nurse.', ['Trabajo de cocinero y Marta trabaja de enfermera.', 'Trabajo de cocinera y Marta trabaja de enfermera.', 'Yo trabajo de cocinero y Marta trabaja de enfermera.', 'Yo trabajo de cocinera y Marta trabaja de enfermera.'], [Kit::word('el cocinero', 'cocinero', ['cocinera']), Kit::word('el enfermero', 'enfermera'), Kit::word('trabajar', 'trabajo'), Kit::form('trabajo de', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.jefa-abogada', 'Mi jefa es ___.', 'My boss (a woman) is a lawyer.', 'abogada', Kit::word('el abogado', 'abogada'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ana-empresa', 'Ana trabaja en una ___.', 'Ana works in a company.', 'empresa', Kit::word('la empresa', 'empresa'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.profesor-jefe', 'Soy profesor y mi jefe es ingeniero.', 'I am a teacher (man) and my boss (a man) is an engineer.', [Kit::word('el profesor', 'profesor'), Kit::word('el jefe', 'jefe'), Kit::word('el ingeniero', 'ingeniero'), Kit::form('soy')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.compañera-oficina', 'Mi compañera trabaja en una oficina.', 'My colleague (a woman) works in an office.', [Kit::word('el compañero', 'compañera'), Kit::word('trabajar', 'trabaja'), Kit::word('la oficina', 'oficina')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.tres-trabajos', 'Ana es profesora, Luis es cocinero y Marta es enfermera.', 'Ana is a teacher, Luis is a cook and Marta is a nurse.', [Kit::word('el profesor', 'profesora'), Kit::word('el cocinero', 'cocinero'), Kit::word('el enfermero', 'enfermera'), Kit::form('es')], 'dictation', $set),
        ];
    }
}
