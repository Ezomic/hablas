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

final class AtThePharmacyAndTheDoctor implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'at-the-pharmacy-and-the-doctor';
    }

    public function words(): array
    {
        return [
            new WordData('a farmácia', cue: 'pharmacy', portunolSlips: ['farmacia']),
            new WordData('o médico', cue: 'doctor (man)', accepted: ['a médica', 'o doutor', 'a doutora'], forms: ['médica']),
            new WordData('o medicamento', cue: 'medicine', accepted: ['o remédio']),
            new WordData('a dor', cue: 'pain, ache', portunolSlips: ['el dolor', 'dolor']),
            new WordData('a cabeça', cue: 'head', portunolSlips: ['cabeza']),
            new WordData('a garganta', cue: 'throat'),
            new WordData('a febre', cue: 'fever', portunolSlips: ['fiebre']),
            new WordData('doente', cue: 'sick, ill', portunolSlips: ['enfermo', 'enferma']),
            new WordData('a receita', cue: 'prescription (from the doctor)', note: 'A receita is the paper from the doctor. The same word also means a recipe.', portunolSlips: ['receta']),
            new WordData('dói-me', cue: 'it hurts (me)', forms: ['doem-me', 'pés', 'mãos'], note: 'Dói-me is used with the thing that hurts: dói-me a cabeça. For more than one thing you say doem-me: doem-me os pés (the feet) and doem-me as mãos (the hands).', portunolSlips: ['me duele']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Tenho febre e dói-me a cabeça.', 'english' => 'I have a fever and my head hurts.'],
            ['text' => 'Doem-me os pés.', 'english' => 'My feet hurt.'],
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
        $article = 'The a is the article a (the), not à (to the) and not há (there is).';

        return [
            Kit::gap($stage, 'sentences.choose_gap.tenho-febre', 'Eu ___ febre.', ['tenho', 'tem', 'tens'], 'tenho', Kit::form('tenho', true), 'Eu goes with tenho. Tem is for he, she or o senhor, and tens is for tu.', 'choose', glosses: ['tenho' => 'I have', 'tem' => 'he, she or you (formal) has', 'tens' => 'you (informal) have']),
            Kit::gap($stage, 'sentences.choose_gap.doi-cabeca', '___ a cabeça.', ['dói-me', 'doem-me'], 'dói-me', Kit::form('dói-me', true), 'A cabeça is one thing, so the verb is dói-me. Doem-me is for more than one thing.', 'choose', glosses: ['dói-me' => 'it hurts me', 'doem-me' => 'they hurt me']),
            Kit::gap($stage, 'sentences.choose_gap.doem-pes', '___ os pés.', ['dói-me', 'doem-me'], 'doem-me', Kit::form('doem-me', true), 'Os pés is more than one thing, so the verb is doem-me.', 'choose', glosses: ['dói-me' => 'it hurts me', 'doem-me' => 'they hurt me']),
            Kit::gap($stage, 'sentences.choose_gap.ana-doente', 'A Ana está ___.', ['doente', 'febre', 'cabeça'], 'doente', Kit::word('doente'), 'Está is followed by how Ana is. Doente says how she is, but a febre and a cabeça are things.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.vou-farmacia', 'Vou à ___.', ['farmácia', 'febre', 'cabeça'], 'farmácia', Kit::word('a farmácia', 'farmácia'), 'You can go to a place. A farmácia is a place, but a febre and a cabeça are not.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tem-medicamento', 'Tem ___ para a febre?', ['medicamento', 'cabeça', 'garganta'], 'medicamento', Kit::word('o medicamento', 'medicamento'), 'You ask for medicine for a fever. You do not ask for a head or a throat.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.ana-tem', 'A Ana ___ febre.', 'Ana has a fever.', 'tem', Kit::form('tem'), 'Ana is one person, so the verb is tem.'),
            Kit::typeGap($stage, 'sentences.type_gap.doi-garganta', '___ a garganta.', 'My throat hurts.', 'Dói-me', Kit::form('dói-me'), 'A garganta is one thing, so the verb is dói-me.'),
            Kit::typeGap($stage, 'sentences.type_gap.doem-maos', '___ as mãos.', 'My hands hurt.', 'Doem-me', Kit::form('doem-me', true), 'As mãos is more than one thing, so the verb is doem-me.'),
            Kit::typeGap($stage, 'sentences.type_gap.dor', 'Tenho ___ de cabeça.', 'I have a headache.', 'dor', Kit::word('a dor', 'dor')),
            Kit::typeGap($stage, 'sentences.type_gap.rui-doente', 'O Rui está ___.', 'Rui is sick.', 'doente', Kit::word('doente'), 'Doente is the same for a man and a woman, so it never ends in -o or -a.'),

            Kit::translate($stage, 'sentences.translate.febre', 'I have a fever.', ['Tenho febre.', 'Eu tenho febre.'], [Kit::word('a febre', 'febre'), Kit::form('tenho')]),
            Kit::translate($stage, 'sentences.translate.cabeca', 'My head hurts, Ana.', ['Ana, dói-me a cabeça.', 'Dói-me a cabeça, Ana.'], [Kit::word('a cabeça', 'cabeça'), Kit::form('dói-me')]),
            Kit::translate($stage, 'sentences.translate.medico', 'The doctor has a fever.', ['O médico tem febre.', 'A médica tem febre.', 'O doutor tem febre.', 'A doutora tem febre.'], [Kit::word('o médico', 'médico', ['médica', 'doutor', 'doutora']), Kit::form('tem')]),

            Kit::build($stage, 'sentences.build.dor-garganta', 'I have a sore throat.', 'Tenho dor de garganta.', ['dói-me'], [Kit::word('a dor', 'dor'), Kit::word('a garganta', 'garganta'), Kit::form('tenho')]),
            Kit::build($stage, 'sentences.build.vou-farmacia', 'I go to the pharmacy.', 'Vou à farmácia.', ['vai'], [Kit::word('a farmácia', 'farmácia')]),
            Kit::build($stage, 'sentences.build.medicamento-febre', 'The medicine is for the fever.', 'O medicamento é para a febre.', ['com'], [Kit::word('o medicamento', 'medicamento'), Kit::word('a febre', 'febre')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.febre-garganta', 'Tenho febre e dói-me a garganta.', ['I have a fever and my throat hurts.', 'I have a fever and my head hurts.', 'I have a headache and my throat hurts.', 'I have a fever and my feet hurt.'], 'I have a fever and my throat hurts.', [Kit::word('dói-me'), Kit::word('a garganta', 'garganta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ana-doente', 'A Ana está doente.', ['Ana is sick.', 'Ana is at the doctor.', 'Ana has a fever.', 'Ana is at the pharmacy.'], 'Ana is sick.', [Kit::word('doente')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.receita', 'Vou à farmácia com a receita.', ['I go to the pharmacy with the prescription.', 'I go to the doctor with the prescription.', 'I go to the pharmacy with the medicine.', 'I go to the pharmacy with Ana.'], 'I go to the pharmacy with the prescription.', [Kit::word('a farmácia', 'farmácia'), Kit::word('a receita', 'receita')]),
            Kit::listenType($stage, 'sentences.listen_type.dor-cabeca', 'Tenho dor de cabeça.', 'I have a headache.', [Kit::word('a dor', 'dor'), Kit::word('a cabeça', 'cabeça'), Kit::form('tenho')]),
            Kit::listenType($stage, 'sentences.listen_type.medico-receita', 'O médico tem a receita.', 'The doctor has the prescription.', [Kit::word('o médico', 'médico'), Kit::word('a receita', 'receita'), Kit::form('tem')], homophoneNote: $article),
            Kit::listenType($stage, 'sentences.listen_type.doem-pes', 'Doem-me os pés.', 'My feet hurt.', [Kit::word('dói-me', 'doem-me')]),
            Kit::listenType($stage, 'sentences.listen_type.medicamento-garganta', 'O medicamento é para a garganta.', 'The medicine is for the throat.', [Kit::word('o medicamento', 'medicamento'), Kit::word('a garganta', 'garganta')], homophoneNote: $article),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.doi-cabeca', 'Dói-me a cabeça.', 'My head hurts.', [Kit::word('dói-me'), Kit::word('a cabeça', 'cabeça')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ao-medico', 'Vou ao médico.', 'I am going to the doctor.', [Kit::word('o médico', 'médico')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.farmacia', 'A farmácia tem o medicamento.', 'The pharmacy has the medicine.', [Kit::word('a farmácia', 'farmácia'), Kit::word('o medicamento', 'medicamento'), Kit::form('tem')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.dor-febre', 'Tenho dor de garganta e febre.', 'I have a sore throat and a fever.', [Kit::word('a dor', 'dor'), Kit::word('a garganta', 'garganta'), Kit::word('a febre', 'febre'), Kit::form('tenho')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.que-tens', 'O que tens?', 'What is wrong with you? (informal you)', [['tenho', 'dói', 'doem', 'estou'], ['febre', 'dor', 'doente', 'cabeça', 'garganta']], 'Tenho febre.', [Kit::word('a febre', 'febre'), Kit::form('tenho')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.estas-doente', 'Estás doente?', 'Are you sick? (informal you)', [['sim', 'não', 'estou'], ['doente', 'bem']], 'Sim, estou doente.', [Kit::word('doente')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ha-farmacia', 'Há uma farmácia aqui?', 'Is there a pharmacy here?', [['sim', 'não', 'há'], ['farmácia']], 'Sim, há uma farmácia aqui.', [Kit::word('a farmácia', 'farmácia')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $article = 'The a is the article a (the), not à (to the) and not há (there is).';

        return [
            Kit::readPassage($stage, 'task.read_passage.medico', 'Read the conversation at the doctor.', [
                Kit::line('Médico', 'Bom dia. O que tem?'),
                Kit::line('Rui', 'Tenho febre. Dói-me a garganta.'),
                Kit::line('Médico', 'Está doente. Aqui tem a receita.'),
                Kit::line('Médico', 'O medicamento é para a febre. Tome o medicamento e descanse.'),
                Kit::line('Rui', 'Obrigado. Vou à farmácia.'),
            ], [
                Kit::question('What is wrong with Rui?', ['He has a fever and a sore throat', 'He has a headache', 'He is not sick'], 'He has a fever and a sore throat'),
                Kit::question('What does the doctor tell Rui to do?', ['Take the medicine and rest', 'Go to work', 'Call Ana'], 'Take the medicine and rest'),
                Kit::question('Where does Rui go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [Kit::word('o médico', 'médico'), Kit::word('a receita', 'receita'), Kit::word('o medicamento', 'medicamento'), Kit::word('a farmácia', 'farmácia'), Kit::word('a febre', 'febre')], 'read', glosses: ['tome' => 'take', 'descanse' => 'rest']),
            Kit::gap($stage, 'task.choose_gap.receita', 'Aqui tem a ___ para a farmácia.', ['receita', 'febre', 'garganta'], 'receita', Kit::word('a receita', 'receita'), 'Aqui tem means here you are, so you hand something over. You can hand over a prescription, but not a fever or a throat.', 'read'),
            Kit::gap($stage, 'task.choose_gap.rui-doente', 'O Rui tem febre e está ___.', ['doente', 'farmácia', 'medicamento'], 'doente', Kit::word('doente'), 'Está is followed by how someone is. Doente says how Rui is, but a farmácia and o medicamento are things.', 'read'),

            Kit::transform($stage, 'task.transform.ana-tem', 'Say it about Ana.', 'Tenho febre.', ['A Ana tem febre.', 'Ana tem febre.'], [Kit::word('a febre', 'febre'), Kit::form('tem')]),
            Kit::transform($stage, 'task.transform.doem', 'Change a cabeça to os pés.', 'Dói-me a cabeça.', ['Doem-me os pés.'], [Kit::form('doem-me', true)]),
            Kit::transform($stage, 'task.transform.tens', 'Say it to a friend (informal you).', 'Tenho dor de garganta.', ['Tens dor de garganta.', 'Tu tens dor de garganta.'], [Kit::word('a dor', 'dor'), Kit::form('tens')]),
            Kit::writeGuided($stage, 'task.write_guided.febre', 'Say that you have a fever and that your head hurts.', ['tenho', 'febre', 'dói-me', 'cabeça'], 'Tenho febre e dói-me a cabeça.', [
                ['forms' => ['tenho'], 'term' => null],
                ['forms' => ['febre'], 'term' => 'a febre'],
                ['forms' => ['dói'], 'term' => 'dói-me'],
                ['forms' => ['cabeça'], 'term' => 'a cabeça'],
            ], [Kit::word('a febre', 'febre'), Kit::word('dói-me'), Kit::word('a cabeça', 'cabeça')]),
            Kit::writeGuided($stage, 'task.write_guided.medico', 'Say that you are sick and that you go to the doctor.', ['estou', 'doente', 'vou', 'médico'], 'Estou doente. Vou ao médico.', [
                ['forms' => ['estou'], 'term' => null],
                ['forms' => ['doente'], 'term' => 'doente'],
                ['forms' => ['vou'], 'term' => null],
                ['forms' => ['médico', 'médica', 'doutor', 'doutora'], 'term' => 'o médico'],
            ], [Kit::word('doente'), Kit::word('o médico', 'médico')]),
            Kit::build($stage, 'task.build.receita-medicamento', 'I have the prescription for the medicine.', 'Tenho a receita para o medicamento.', ['tem', 'de'], [Kit::word('a receita', 'receita'), Kit::word('o medicamento', 'medicamento'), Kit::form('tenho')], 'write'),
            Kit::translate($stage, 'task.translate.maos-febre', 'I have a fever and my hands do not hurt.', ['Tenho febre e não me doem as mãos.', 'Tenho febre e as mãos não me doem.', 'Não me doem as mãos e tenho febre.'], [Kit::word('a febre', 'febre'), Kit::form('não me doem', true)], 'write'),
            Kit::build($stage, 'task.build.ana-medico', 'Ana is sick and goes to the doctor.', 'A Ana está doente e vai ao médico.', ['febre', 'vou'], [Kit::word('doente'), Kit::word('o médico', 'médico')], 'write'),
            Kit::translate($stage, 'task.translate.garganta', 'Do you have medicine for a sore throat? (formal you)', ['Tem um medicamento para a garganta?', 'Tem medicamento para a garganta?', 'Tem um medicamento para a dor de garganta?', 'Tem medicamento para a dor de garganta?', 'O senhor tem um medicamento para a garganta?', 'O senhor tem medicamento para a garganta?', 'A senhora tem um medicamento para a garganta?', 'A senhora tem medicamento para a garganta?', 'Tem um remédio para a garganta?', 'Tem remédio para a garganta?'], [Kit::word('o medicamento', 'medicamento', ['remédio']), Kit::word('a garganta', 'garganta'), Kit::form('tem')], 'write'),
            Kit::translate($stage, 'task.translate.garganta-febre', 'My throat hurts and I have a fever.', ['Dói-me a garganta e tenho febre.', 'Tenho febre e dói-me a garganta.'], [Kit::word('a garganta', 'garganta'), Kit::word('a febre', 'febre'), Kit::form('tenho')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.joao-ana', [
                Kit::line('João', 'Olá, Ana. O que tens?'),
                Kit::line('Ana', 'Estou doente. Tenho febre e dói-me a cabeça.'),
                Kit::line('João', 'Vou à farmácia. Há medicamento para a febre.'),
                Kit::line('Ana', 'Obrigada, João.'),
            ], [
                Kit::question('What does Ana have?', ['A fever and a headache', 'A sore throat', 'A prescription'], 'A fever and a headache'),
                Kit::question('Where does João go?', ['To the pharmacy', 'To the doctor', 'To the airport'], 'To the pharmacy'),
                Kit::question('What is the medicine for?', ['The fever', 'The throat', 'The feet'], 'The fever'),
            ], [
                Kit::question('Who is sick?', ['Ana', 'João', 'Nobody'], 'Ana'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Who says thank you at the end?', ['Ana', 'João', 'Nobody'], 'Ana'),
            ], [Kit::word('doente'), Kit::word('a febre', 'febre'), Kit::word('a cabeça', 'cabeça'), Kit::word('a farmácia', 'farmácia'), Kit::word('dói-me')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.doente-febre', 'Estou doente e tenho febre.', 'I am sick and I have a fever.', [Kit::word('doente'), Kit::word('a febre', 'febre'), Kit::form('tenho')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.rui-receita', 'O Rui tem a receita do médico.', 'Rui has the doctor\'s prescription.', [Kit::word('a receita', 'receita'), Kit::word('o médico', 'médico'), Kit::form('tem')], 'listen', homophoneNote: $article),
            Kit::listenType($stage, 'task.listen_type.ana-garganta', 'A Ana tem dor de garganta e febre.', 'Ana has a sore throat and a fever.', [Kit::word('a dor', 'dor'), Kit::word('a garganta', 'garganta'), Kit::form('tem')], 'listen', homophoneNote: $article),

            Kit::speakAnswer($stage, 'task.speak_answer.dor-garganta', 'Tens dor de garganta?', 'Do you have a sore throat? (informal you)', [['sim', 'não', 'tenho'], ['dor', 'garganta', 'dói']], 'Sim, tenho dor de garganta.', [Kit::word('a dor', 'dor'), Kit::word('a garganta', 'garganta'), Kit::form('tenho')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medico', 'Onde está o médico?', 'Where is the doctor?', [['médico', 'está'], ['aqui', 'ali']], 'O médico está aqui.', [Kit::word('o médico', 'médico')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medicamento', 'Tens o medicamento?', 'Do you have the medicine? (informal you)', [['sim', 'não', 'tenho'], ['medicamento']], 'Sim, tenho o medicamento.', [Kit::word('o medicamento', 'medicamento'), Kit::form('tenho')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.receita', 'Tens a receita?', 'Do you have the prescription? (informal you)', [['sim', 'não', 'tenho'], ['receita']], 'Sim, tenho a receita.', [Kit::word('a receita', 'receita'), Kit::form('tenho')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.febre-cabeca', 'Bom dia, dói-me a cabeça e tenho febre.', 'Good morning, my head hurts and I have a fever.', [Kit::word('a febre', 'febre'), Kit::word('dói-me'), Kit::word('a cabeça', 'cabeça')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.medico-receita', 'O médico tem a receita, Ana.', 'The doctor has the prescription, Ana.', [Kit::word('o médico', 'médico'), Kit::word('a receita', 'receita'), Kit::form('tem')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $article = 'The a is the article a (the), not à (to the) and not há (there is).';

        return [
            Kit::translate($stage, 'check.a.translate.farmacia', 'The pharmacy has medicine for a headache.', ['A farmácia tem medicamento para a dor de cabeça.', 'A farmácia tem um medicamento para a dor de cabeça.', 'A farmácia tem remédio para a dor de cabeça.', 'A farmácia tem um remédio para a dor de cabeça.'], [Kit::word('a farmácia', 'farmácia'), Kit::word('o medicamento', 'medicamento', ['remédio']), Kit::word('a dor', 'dor'), Kit::word('a cabeça', 'cabeça'), Kit::form('tem')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.receita', 'I have a prescription from the doctor.', ['Tenho uma receita do médico.', 'Tenho uma receita da médica.', 'Tenho uma receita do doutor.', 'Tenho uma receita da doutora.', 'Tenho a receita do médico.', 'Tenho a receita da médica.', 'Tenho a receita do doutor.', 'Tenho a receita da doutora.', 'Eu tenho uma receita do médico.', 'Eu tenho uma receita da médica.', 'Eu tenho uma receita do doutor.', 'Eu tenho uma receita da doutora.', 'Eu tenho a receita do médico.', 'Eu tenho a receita da médica.', 'Eu tenho a receita do doutor.', 'Eu tenho a receita da doutora.'], [Kit::word('a receita', 'receita'), Kit::word('o médico', 'médico', ['médica', 'doutor', 'doutora']), Kit::form('tenho')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.febre', 'Do you have a fever? (informal you)', ['Tens febre?', 'Tu tens febre?'], [Kit::word('a febre', 'febre'), Kit::form('tens')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.rui', 'Rui has a sore throat.', ['O Rui tem dor de garganta.', 'Rui tem dor de garganta.'], [Kit::word('a dor', 'dor'), Kit::word('a garganta', 'garganta'), Kit::form('tem')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.doi', 'Estou doente e ___ a garganta.', 'I am sick and my throat hurts.', 'dói-me', Kit::form('dói-me', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.doem', '___ os pés e as mãos.', 'My feet and hands hurt.', 'Doem-me', Kit::form('doem-me', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.doente', 'Estou muito doente e tenho febre.', 'I am very sick and I have a fever.', [Kit::word('doente'), Kit::word('a febre', 'febre')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.cabeca', 'Dói-me a cabeça e tenho febre.', 'My head hurts and I have a fever.', [Kit::word('dói-me'), Kit::word('a cabeça', 'cabeça'), Kit::word('a febre', 'febre')], 'dictation', $set, homophoneNote: $article),
            Kit::listenType($stage, 'check.a.listen_type.medica', 'A médica está na farmácia.', 'The doctor is in the pharmacy.', [Kit::word('o médico', 'médica'), Kit::word('a farmácia', 'farmácia')], 'dictation', $set, homophoneNote: $article),
            Kit::listenPassage($stage, 'check.a.listen_passage.receita', [
                Kit::line('Médico', 'Bom dia. Como está?'),
                Kit::line('João', 'Estou doente e dói-me a garganta.'),
                Kit::line('Médico', 'Aqui tem a receita para o medicamento.'),
                Kit::line('João', 'Obrigado. Com a receita vou à farmácia.'),
            ], [
                Kit::question('What is wrong with João?', ['His throat hurts', 'His head hurts', 'He has a fever'], 'His throat hurts'),
                Kit::question('What does the doctor give João?', ['A prescription', 'The medicine', 'A fever'], 'A prescription'),
                Kit::question('Where does João go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [
                Kit::question('Who speaks first?', ['The doctor', 'João', 'Nobody'], 'The doctor'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does João say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('doente'), Kit::word('dói-me'), Kit::word('a garganta', 'garganta'), Kit::word('a receita', 'receita'), Kit::word('o medicamento', 'medicamento')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.marta', 'Read the conversation.', [
                Kit::line('Marta', 'Olá, Rui. Estou doente.'),
                Kit::line('Rui', 'Estás bem?'),
                Kit::line('Marta', 'Não. Tenho dor de cabeça e febre.'),
                Kit::line('Rui', 'A farmácia tem medicamento para a febre.'),
            ], [
                Kit::question('What is wrong with Marta?', ['She has a headache and a fever', 'She has a sore throat', 'She has a prescription'], 'She has a headache and a fever'),
                Kit::question('Where is there medicine for the fever?', ['At the pharmacy', 'At the doctor', 'At home'], 'At the pharmacy'),
            ], [Kit::word('doente'), Kit::word('a dor', 'dor'), Kit::word('a febre', 'febre'), Kit::word('a farmácia', 'farmácia'), Kit::word('o medicamento', 'medicamento')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.medicamento', 'Há medicamento para a garganta?', 'Is there medicine for the throat?', [['sim', 'não', 'há'], ['medicamento', 'garganta']], 'Sim, há medicamento para a garganta.', [Kit::word('o medicamento', 'medicamento'), Kit::word('a garganta', 'garganta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.farmacia', 'Onde está a farmácia?', 'Where is the pharmacy?', [['farmácia', 'está'], ['aqui', 'ali']], 'A farmácia está aqui.', [Kit::word('a farmácia', 'farmácia')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.dor', 'Tens dor de cabeça?', 'Do you have a headache? (informal you)', [['sim', 'não', 'tenho'], ['dor', 'cabeça', 'dói']], 'Sim, tenho dor de cabeça.', [Kit::word('a dor', 'dor'), Kit::word('a cabeça', 'cabeça')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $article = 'The a is the article a (the), not à (to the) and not há (there is).';

        return [
            Kit::translate($stage, 'check.b.translate.medico', 'The doctor is sick and has a fever.', ['O médico está doente e tem febre.', 'A médica está doente e tem febre.', 'O doutor está doente e tem febre.', 'A doutora está doente e tem febre.'], [Kit::word('o médico', 'médico', ['médica', 'doutor', 'doutora']), Kit::word('doente'), Kit::word('a febre', 'febre'), Kit::form('tem')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.receita', 'I have the prescription and I go to the pharmacy.', ['Tenho a receita e vou à farmácia.', 'Eu tenho a receita e vou à farmácia.'], [Kit::word('a receita', 'receita'), Kit::word('a farmácia', 'farmácia'), Kit::form('tenho')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.farmacia', 'Does the pharmacy have medicine for a sore throat?', ['A farmácia tem medicamento para a garganta?', 'A farmácia tem um medicamento para a garganta?', 'A farmácia tem medicamento para a dor de garganta?', 'A farmácia tem um medicamento para a dor de garganta?', 'A farmácia tem remédio para a garganta?', 'A farmácia tem um remédio para a garganta?'], [Kit::word('a farmácia', 'farmácia'), Kit::word('o medicamento', 'medicamento', ['remédio']), Kit::word('a garganta', 'garganta'), Kit::form('tem')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pes', 'My feet hurt and I have a fever.', ['Doem-me os pés e tenho febre.', 'Tenho febre e doem-me os pés.'], [Kit::word('dói-me', 'doem-me'), Kit::word('a febre', 'febre'), Kit::form('doem-me', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.doi', 'Rui, tenho febre e ___ a cabeça.', 'Rui, I have a fever and my head hurts.', 'dói-me', Kit::form('dói-me', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.doem', 'Estou doente e ___ as mãos.', 'I am sick and my hands hurt.', 'doem-me', Kit::form('doem-me', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.garganta', 'Tenho dor de cabeça e dói-me a garganta.', 'I have a headache and my throat hurts.', [Kit::word('a dor', 'dor'), Kit::word('a cabeça', 'cabeça'), Kit::word('dói-me'), Kit::word('a garganta', 'garganta')], 'dictation', $set, homophoneNote: $article),
            Kit::listenType($stage, 'check.b.listen_type.ana', 'A Ana está doente e tem a receita.', 'Ana is sick and has the prescription.', [Kit::word('doente'), Kit::word('a receita', 'receita')], 'dictation', $set, homophoneNote: 'Both a words are the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.medicamento', 'O médico tem o medicamento para a dor de cabeça.', 'The doctor has the medicine for the headache.', [Kit::word('o médico', 'médico'), Kit::word('o medicamento', 'medicamento'), Kit::word('a dor', 'dor'), Kit::word('a cabeça', 'cabeça')], 'dictation', $set, homophoneNote: $article),
        ];
    }
}
