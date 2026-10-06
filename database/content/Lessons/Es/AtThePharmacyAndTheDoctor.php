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

final class AtThePharmacyAndTheDoctor implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'at-the-pharmacy-and-the-doctor';
    }

    public function words(): array
    {
        return [
            new WordData('la farmacia', cue: 'pharmacy'),
            new WordData('el médico', cue: 'doctor (man)', accepted: ['la médica', 'el doctor', 'la doctora'], forms: ['médica']),
            new WordData('la medicina', cue: 'medicine', accepted: ['el medicamento']),
            new WordData('el dolor', cue: 'pain, ache'),
            new WordData('la cabeza', cue: 'head'),
            new WordData('la garganta', cue: 'throat'),
            new WordData('la fiebre', cue: 'fever'),
            new WordData('enfermo', cue: 'sick, ill (masculine)', forms: ['enferma']),
            new WordData('la receta', cue: 'prescription (from the doctor)', note: 'La receta is the paper from the doctor. The same word also means a recipe.'),
            new WordData('me duele', cue: 'it hurts (me)', forms: ['me duelen', 'pies', 'manos'], note: 'Me duele is used with the thing that hurts: me duele la cabeza. For more than one thing you say me duelen: me duelen los pies (the feet) and me duelen las manos (the hands).'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Tengo fiebre y me duele la cabeza.', 'english' => 'I have a fever and my head hurts.'],
            ['text' => 'Me duelen los pies.', 'english' => 'My feet hurt.'],
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

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.tengo-fiebre', 'Yo ___ fiebre.', ['tengo', 'tiene', 'tienes'], 'tengo', Kit::form('tengo', true), 'Yo goes with tengo. Tiene is for he, she or you (formal), and tienes is for you (informal).', 'choose', glosses: ['tengo' => 'I have', 'tiene' => 'he, she or you (formal) has', 'tienes' => 'you (informal) have']),
            Kit::gap($stage, 'sentences.choose_gap.duele-cabeza', 'Me ___ la cabeza.', ['duele', 'duelen'], 'duele', Kit::form('duele', true), 'La cabeza is one thing, so the verb is duele. Duelen is for more than one thing.', 'choose', glosses: ['duele' => 'it hurts', 'duelen' => 'they hurt']),
            Kit::gap($stage, 'sentences.choose_gap.duelen-pies', 'Me ___ los pies.', ['duele', 'duelen'], 'duelen', Kit::form('duelen', true), 'Los pies is more than one thing, so the verb is duelen.', 'choose', glosses: ['duele' => 'it hurts', 'duelen' => 'they hurt']),
            Kit::gap($stage, 'sentences.choose_gap.ana-enferma', 'Ana está ___.', ['enferma', 'enfermo'], 'enferma', Kit::word('enfermo', 'enferma'), 'Ana is a woman, so the adjective ends in -a.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.voy-farmacia', 'Voy a la ___.', ['farmacia', 'fiebre', 'cabeza'], 'farmacia', Kit::word('la farmacia', 'farmacia'), 'You can go to a place. La farmacia is a place, but la fiebre and la cabeza are not.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tiene-medicina', '¿Tiene una ___ para la fiebre?', ['medicina', 'cabeza', 'garganta'], 'medicina', Kit::word('la medicina', 'medicina'), 'You ask for medicine for a fever. You do not ask for a head or a throat.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.ana-tiene', 'Ana ___ fiebre.', 'Ana has a fever.', 'tiene', Kit::form('tiene'), 'Ana is one person, so the verb is tiene.'),
            Kit::typeGap($stage, 'sentences.type_gap.duele-garganta', 'Me ___ la garganta.', 'My throat hurts.', 'duele', Kit::form('duele'), 'La garganta is one thing, so the verb is duele.'),
            Kit::typeGap($stage, 'sentences.type_gap.duelen-manos', 'Me ___ las manos.', 'My hands hurt.', 'duelen', Kit::form('duelen', true), 'Las manos is more than one thing, so the verb is duelen.'),
            Kit::typeGap($stage, 'sentences.type_gap.dolor', 'Tengo ___ de cabeza.', 'I have a headache.', 'dolor', Kit::word('el dolor', 'dolor')),
            Kit::typeGap($stage, 'sentences.type_gap.pablo-enfermo', 'Pablo está ___.', 'Pablo is sick.', 'enfermo', Kit::word('enfermo'), 'Pablo is a man, so the adjective ends in -o.'),

            Kit::translate($stage, 'sentences.translate.fiebre', 'I have a fever.', ['Tengo fiebre.', 'Yo tengo fiebre.'], [Kit::word('la fiebre', 'fiebre'), Kit::form('tengo')]),
            Kit::translate($stage, 'sentences.translate.cabeza', 'My head hurts, Ana.', ['Ana, me duele la cabeza.', 'Me duele la cabeza, Ana.'], [Kit::word('la cabeza', 'cabeza'), Kit::form('duele')]),
            Kit::translate($stage, 'sentences.translate.medico', 'The doctor has a fever.', ['El médico tiene fiebre.', 'La médica tiene fiebre.', 'El doctor tiene fiebre.', 'La doctora tiene fiebre.'], [Kit::word('el médico', 'médico', ['médica', 'doctor', 'doctora']), Kit::form('tiene')]),

            Kit::build($stage, 'sentences.build.dolor-garganta', 'I have pain in my throat.', 'Tengo dolor de garganta.', ['duele'], [Kit::word('el dolor', 'dolor'), Kit::word('la garganta', 'garganta'), Kit::form('tengo')]),
            Kit::build($stage, 'sentences.build.voy-farmacia', 'I go to the pharmacy.', 'Voy a la farmacia.', ['va'], [Kit::word('la farmacia', 'farmacia')]),
            Kit::build($stage, 'sentences.build.medicina-fiebre', 'The medicine is for the fever.', 'La medicina es para la fiebre.', ['con'], [Kit::word('la medicina', 'medicina'), Kit::word('la fiebre', 'fiebre')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.fiebre-garganta', 'Tengo fiebre y me duele la garganta.', ['I have a fever and my throat hurts.', 'I have a fever and my head hurts.', 'I have a headache and my throat hurts.', 'I have a fever and my feet hurt.'], 'I have a fever and my throat hurts.', [Kit::word('me duele'), Kit::word('la garganta', 'garganta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ana-enferma', 'Ana está enferma.', ['Ana is sick.', 'Ana is at the doctor.', 'Ana has a fever.', 'Ana is at the pharmacy.'], 'Ana is sick.', [Kit::word('enfermo', 'enferma')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.receta', 'Voy a la farmacia con la receta.', ['I go to the pharmacy with the prescription.', 'I go to the doctor with the prescription.', 'I go to the pharmacy with the medicine.', 'I go to the pharmacy with Ana.'], 'I go to the pharmacy with the prescription.', [Kit::word('la farmacia', 'farmacia'), Kit::word('la receta', 'receta')]),
            Kit::listenType($stage, 'sentences.listen_type.dolor-cabeza', 'Tengo dolor de cabeza.', 'I have a headache.', [Kit::word('el dolor', 'dolor'), Kit::word('la cabeza', 'cabeza'), Kit::form('tengo')]),
            Kit::listenType($stage, 'sentences.listen_type.medico-receta', 'El médico tiene la receta.', 'The doctor has the prescription.', [Kit::word('el médico', 'médico'), Kit::word('la receta', 'receta'), Kit::form('tiene')]),
            Kit::listenType($stage, 'sentences.listen_type.duelen-pies', 'Me duelen los pies.', 'My feet hurt.', [Kit::word('me duele', 'me duelen')]),
            Kit::listenType($stage, 'sentences.listen_type.medicina-garganta', 'La medicina es para la garganta.', 'The medicine is for the throat.', [Kit::word('la medicina', 'medicina'), Kit::word('la garganta', 'garganta')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.duele-cabeza', 'Me duele la cabeza.', 'My head hurts.', [Kit::word('me duele'), Kit::word('la cabeza', 'cabeza')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.al-medico', 'Voy al médico.', 'I am going to the doctor.', [Kit::word('el médico', 'médico')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.farmacia', 'La farmacia tiene la medicina.', 'The pharmacy has the medicine.', [Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina'), Kit::form('tiene')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.dolor-fiebre', 'Tengo dolor de garganta y fiebre.', 'I have a sore throat and a fever.', [Kit::word('el dolor', 'dolor'), Kit::word('la garganta', 'garganta'), Kit::word('la fiebre', 'fiebre'), Kit::form('tengo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.que-tienes', '¿Qué tienes?', 'What is wrong with you? (informal you)', [['tengo', 'me', 'estoy'], ['fiebre', 'dolor', 'duele', 'duelen', 'enfermo', 'enferma']], 'Tengo fiebre.', [Kit::word('la fiebre', 'fiebre'), Kit::form('tengo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.estas-enfermo', '¿Estás enfermo?', 'Are you sick? (informal you)', [['sí', 'no', 'estoy'], ['enfermo', 'enferma', 'bien']], 'Sí, estoy enfermo.', [Kit::word('enfermo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.hay-farmacia', '¿Hay una farmacia aquí?', 'Is there a pharmacy here?', [['sí', 'no', 'hay'], ['farmacia']], 'Sí, hay una farmacia aquí.', [Kit::word('la farmacia', 'farmacia')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.medico', 'Read the conversation at the doctor.', [
                Kit::line('Médico', 'Buenos días. ¿Qué tiene?'),
                Kit::line('Pablo', 'Tengo fiebre. Me duele la garganta.'),
                Kit::line('Médico', 'Está enfermo. Aquí tiene la receta.'),
                Kit::line('Médico', 'La medicina es para la fiebre. Tome la medicina y descanse.'),
                Kit::line('Pablo', 'Gracias. Voy a la farmacia.'),
            ], [
                Kit::question('What is wrong with Pablo?', ['He has a fever and a sore throat', 'He has a headache', 'He is not sick'], 'He has a fever and a sore throat'),
                Kit::question('What does the doctor tell Pablo to do?', ['Take the medicine and rest', 'Go to work', 'Call Ana'], 'Take the medicine and rest'),
                Kit::question('Where does Pablo go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [Kit::word('el médico', 'médico'), Kit::word('la receta', 'receta'), Kit::word('la medicina', 'medicina'), Kit::word('la farmacia', 'farmacia'), Kit::word('la fiebre', 'fiebre')], 'read', glosses: ['tome' => 'take', 'descanse' => 'rest']),
            Kit::gap($stage, 'task.choose_gap.receta', 'Aquí tiene la ___ para la farmacia.', ['receta', 'fiebre', 'garganta'], 'receta', Kit::word('la receta', 'receta'), 'Aquí tiene means here you are, so you hand something over. You can hand over a prescription, but not a fever or a throat.', 'read'),
            Kit::gap($stage, 'task.choose_gap.pablo-enfermo', 'Pablo tiene fiebre y está ___.', ['enfermo', 'enferma'], 'enfermo', Kit::word('enfermo'), 'Pablo is a man, so the adjective ends in -o.', 'read'),

            Kit::transform($stage, 'task.transform.ana-tiene', 'Say it about Ana.', 'Tengo fiebre.', ['Ana tiene fiebre.'], [Kit::word('la fiebre', 'fiebre'), Kit::form('tiene')]),
            Kit::transform($stage, 'task.transform.duelen', 'Change la cabeza to los pies.', 'Me duele la cabeza.', ['Me duelen los pies.'], [Kit::form('duelen', true)]),
            Kit::transform($stage, 'task.transform.tienes', 'Say it to a friend (informal you).', 'Tengo dolor de garganta.', ['Tienes dolor de garganta.', 'Tú tienes dolor de garganta.'], [Kit::word('el dolor', 'dolor'), Kit::form('tienes')]),
            Kit::writeGuided($stage, 'task.write_guided.fiebre', 'Say that you have a fever and that your head hurts.', ['tengo', 'fiebre', 'me duele', 'cabeza'], 'Tengo fiebre y me duele la cabeza.', [
                ['forms' => ['tengo'], 'term' => null],
                ['forms' => ['fiebre'], 'term' => 'la fiebre'],
                ['forms' => ['duele'], 'term' => 'me duele'],
                ['forms' => ['cabeza'], 'term' => 'la cabeza'],
            ], [Kit::word('la fiebre', 'fiebre'), Kit::word('me duele'), Kit::word('la cabeza', 'cabeza')]),
            Kit::writeGuided($stage, 'task.write_guided.medico', 'Say that you are sick and that you go to the doctor.', ['estoy', 'enfermo', 'voy', 'médico'], 'Estoy enfermo. Voy al médico.', [
                ['forms' => ['estoy'], 'term' => null],
                ['forms' => ['enfermo', 'enferma'], 'term' => 'enfermo'],
                ['forms' => ['voy'], 'term' => null],
                ['forms' => ['médico', 'médica'], 'term' => 'el médico'],
            ], [Kit::word('enfermo'), Kit::word('el médico', 'médico')]),
            Kit::build($stage, 'task.build.receta-medicina', 'I have the prescription for the medicine.', 'Tengo la receta para la medicina.', ['tiene', 'a'], [Kit::word('la receta', 'receta'), Kit::word('la medicina', 'medicina'), Kit::form('tengo')], 'write'),
            Kit::build($stage, 'task.build.manos-fiebre', 'My hands hurt and I have a fever.', 'Me duelen las manos y tengo fiebre.', ['duele', 'tiene'], [Kit::word('la fiebre', 'fiebre'), Kit::form('duelen', true)], 'write'),
            Kit::build($stage, 'task.build.ana-medico', 'Ana is sick and goes to the doctor.', 'Ana está enferma y va al médico.', ['enfermo', 'voy'], [Kit::word('enfermo', 'enferma'), Kit::word('el médico', 'médico')], 'write'),
            Kit::translate($stage, 'task.translate.garganta', 'Do you have medicine for a sore throat? (formal you)', ['¿Tiene medicina para la garganta?', '¿Tiene una medicina para la garganta?', '¿Tiene medicina para el dolor de garganta?', '¿Tiene usted medicina para la garganta?', '¿Tiene usted medicina para el dolor de garganta?'], [Kit::word('la medicina', 'medicina'), Kit::word('la garganta', 'garganta'), Kit::form('tiene')], 'write'),
            Kit::translate($stage, 'task.translate.garganta-fiebre', 'My throat hurts and I have a fever.', ['Me duele la garganta y tengo fiebre.', 'Tengo fiebre y me duele la garganta.'], [Kit::word('la garganta', 'garganta'), Kit::word('la fiebre', 'fiebre'), Kit::form('tengo')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.luis-ana', [
                Kit::line('Luis', 'Hola, Ana. ¿Qué tienes?'),
                Kit::line('Ana', 'Estoy enferma. Tengo fiebre y me duele la cabeza.'),
                Kit::line('Luis', 'Voy a la farmacia. Hay medicina para la fiebre.'),
                Kit::line('Ana', 'Gracias, Luis.'),
            ], [
                Kit::question('What does Ana have?', ['A fever and a headache', 'A sore throat', 'A prescription'], 'A fever and a headache'),
                Kit::question('Where does Luis go?', ['To the pharmacy', 'To the doctor', 'To the airport'], 'To the pharmacy'),
                Kit::question('What is the medicine for?', ['The fever', 'The throat', 'The feet'], 'The fever'),
            ], [
                Kit::question('Who is sick?', ['Ana', 'Luis', 'Nobody'], 'Ana'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Who says thank you at the end?', ['Ana', 'Luis', 'Nobody'], 'Ana'),
            ], [Kit::word('enfermo', 'enferma'), Kit::word('la fiebre', 'fiebre'), Kit::word('la cabeza', 'cabeza'), Kit::word('la farmacia', 'farmacia'), Kit::word('me duele')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.enfermo-fiebre', 'Estoy enfermo y tengo fiebre.', 'I am sick and I have a fever.', [Kit::word('enfermo'), Kit::word('la fiebre', 'fiebre'), Kit::form('tengo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pablo-receta', 'Pablo tiene la receta del médico.', 'Pablo has the doctor\'s prescription.', [Kit::word('la receta', 'receta'), Kit::word('el médico', 'médico'), Kit::form('tiene')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ana-garganta', 'Ana tiene dolor de garganta y fiebre.', 'Ana has a sore throat and a fever.', [Kit::word('el dolor', 'dolor'), Kit::word('la garganta', 'garganta'), Kit::form('tiene')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.dolor-garganta', '¿Tienes dolor de garganta?', 'Do you have a sore throat? (informal you)', [['sí', 'no', 'tengo'], ['dolor', 'garganta', 'duele']], 'Sí, tengo dolor de garganta.', [Kit::word('el dolor', 'dolor'), Kit::word('la garganta', 'garganta'), Kit::form('tengo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medico', '¿Dónde está el médico?', 'Where is the doctor?', [['médico', 'está'], ['aquí', 'allí']], 'El médico está aquí.', [Kit::word('el médico', 'médico')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medicina', '¿Tienes la medicina?', 'Do you have the medicine? (informal you)', [['sí', 'no', 'tengo'], ['medicina']], 'Sí, tengo la medicina.', [Kit::word('la medicina', 'medicina'), Kit::form('tengo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.receta', '¿Tienes la receta?', 'Do you have the prescription? (informal you)', [['sí', 'no', 'tengo'], ['receta']], 'Sí, tengo la receta.', [Kit::word('la receta', 'receta'), Kit::form('tengo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.fiebre-cabeza', 'Buenos días, me duele la cabeza y tengo fiebre.', 'Good morning, my head hurts and I have a fever.', [Kit::word('la fiebre', 'fiebre'), Kit::word('me duele'), Kit::word('la cabeza', 'cabeza')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.medico-receta', 'El médico tiene la receta, Ana.', 'The doctor has the prescription, Ana.', [Kit::word('el médico', 'médico'), Kit::word('la receta', 'receta'), Kit::form('tiene')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.farmacia', 'The pharmacy has medicine for a headache.', ['La farmacia tiene medicina para el dolor de cabeza.', 'La farmacia tiene una medicina para el dolor de cabeza.'], [Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina'), Kit::word('el dolor', 'dolor'), Kit::word('la cabeza', 'cabeza'), Kit::form('tiene')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.receta', 'I have a prescription from the doctor.', ['Tengo una receta del médico.', 'Tengo una receta de la médica.', 'Tengo una receta del doctor.', 'Tengo una receta de la doctora.', 'Tengo la receta del médico.', 'Tengo la receta de la médica.', 'Tengo la receta del doctor.', 'Tengo la receta de la doctora.', 'Yo tengo una receta del médico.', 'Yo tengo una receta de la médica.', 'Yo tengo una receta del doctor.', 'Yo tengo una receta de la doctora.'], [Kit::word('la receta', 'receta'), Kit::word('el médico', 'médico', ['médica', 'doctor', 'doctora']), Kit::form('tengo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fiebre', 'Do you have a fever? (informal you)', ['¿Tienes fiebre?', '¿Tú tienes fiebre?'], [Kit::word('la fiebre', 'fiebre'), Kit::form('tienes')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo', 'Pablo has a sore throat.', ['Pablo tiene dolor de garganta.'], [Kit::word('el dolor', 'dolor'), Kit::word('la garganta', 'garganta'), Kit::form('tiene')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.duele', 'Estoy enfermo y me ___ la garganta.', 'I am sick and my throat hurts.', 'duele', Kit::form('duele', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.duelen', 'Me ___ los pies y las manos.', 'My feet and hands hurt.', 'duelen', Kit::form('duelen', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.enferma', 'Estoy enferma y tengo fiebre.', 'I am sick and I have a fever.', [Kit::word('enfermo', 'enferma'), Kit::word('la fiebre', 'fiebre')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.cabeza', 'Me duele la cabeza y tengo fiebre.', 'My head hurts and I have a fever.', [Kit::word('me duele'), Kit::word('la cabeza', 'cabeza'), Kit::word('la fiebre', 'fiebre')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.medica', 'La médica está en la farmacia.', 'The doctor is in the pharmacy.', [Kit::word('el médico', 'médica'), Kit::word('la farmacia', 'farmacia')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.receta', [
                Kit::line('Médico', 'Buenos días. ¿Qué tiene?'),
                Kit::line('Luis', 'Estoy enfermo y me duele la garganta.'),
                Kit::line('Médico', 'Aquí tiene la receta para la medicina.'),
                Kit::line('Luis', 'Gracias. Con la receta voy a la farmacia.'),
            ], [
                Kit::question('What is wrong with Luis?', ['His throat hurts', 'His head hurts', 'He has a fever'], 'His throat hurts'),
                Kit::question('What does the doctor give Luis?', ['A prescription', 'The medicine', 'A fever'], 'A prescription'),
                Kit::question('Where does Luis go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [
                Kit::question('Who speaks first?', ['The doctor', 'Luis', 'Nobody'], 'The doctor'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Luis say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('enfermo'), Kit::word('me duele'), Kit::word('la garganta', 'garganta'), Kit::word('la receta', 'receta'), Kit::word('la medicina', 'medicina')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.marta', 'Read the conversation.', [
                Kit::line('Marta', 'Hola, Pablo. Estoy enferma.'),
                Kit::line('Pablo', '¿Qué tienes?'),
                Kit::line('Marta', 'Tengo dolor de cabeza y fiebre.'),
                Kit::line('Pablo', 'La farmacia tiene medicina para la fiebre.'),
            ], [
                Kit::question('What is wrong with Marta?', ['She has a headache and a fever', 'She has a sore throat', 'She has a prescription'], 'She has a headache and a fever'),
                Kit::question('Where is there medicine for the fever?', ['At the pharmacy', 'At the doctor', 'At home'], 'At the pharmacy'),
            ], [Kit::word('enfermo', 'enferma'), Kit::word('el dolor', 'dolor'), Kit::word('la fiebre', 'fiebre'), Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.medicina', '¿Hay medicina para la garganta?', 'Is there medicine for the throat?', [['sí', 'no', 'hay'], ['medicina', 'garganta']], 'Sí, hay medicina para la garganta.', [Kit::word('la medicina', 'medicina'), Kit::word('la garganta', 'garganta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.farmacia', '¿Dónde está la farmacia?', 'Where is the pharmacy?', [['farmacia', 'está'], ['aquí', 'allí']], 'La farmacia está aquí.', [Kit::word('la farmacia', 'farmacia')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.dolor', '¿Tienes dolor de cabeza?', 'Do you have a headache? (informal you)', [['sí', 'no', 'tengo'], ['dolor', 'cabeza', 'duele']], 'Sí, tengo dolor de cabeza.', [Kit::word('el dolor', 'dolor'), Kit::word('la cabeza', 'cabeza')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.medico', 'The doctor is sick and has a fever.', ['El médico está enfermo y tiene fiebre.', 'La médica está enferma y tiene fiebre.', 'El doctor está enfermo y tiene fiebre.', 'La doctora está enferma y tiene fiebre.'], [Kit::word('el médico', 'médico', ['médica', 'doctor', 'doctora']), Kit::word('enfermo', null, ['enferma']), Kit::word('la fiebre', 'fiebre'), Kit::form('tiene')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.receta', 'I have the prescription and I go to the pharmacy.', ['Tengo la receta y voy a la farmacia.', 'Yo tengo la receta y voy a la farmacia.'], [Kit::word('la receta', 'receta'), Kit::word('la farmacia', 'farmacia'), Kit::form('tengo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana', 'Does the pharmacy have medicine for a sore throat?', ['¿La farmacia tiene medicina para la garganta?', '¿La farmacia tiene una medicina para la garganta?', '¿La farmacia tiene medicina para el dolor de garganta?', '¿Tiene la farmacia medicina para la garganta?'], [Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina'), Kit::word('la garganta', 'garganta'), Kit::form('tiene')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pies', 'My feet hurt and I have a fever.', ['Me duelen los pies y tengo fiebre.', 'Tengo fiebre y me duelen los pies.'], [Kit::word('me duele', 'me duelen'), Kit::word('la fiebre', 'fiebre'), Kit::form('duelen', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.duele', 'Pablo, tengo fiebre y me ___ la cabeza.', 'Pablo, I have a fever and my head hurts.', 'duele', Kit::form('duele', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.duelen', 'Estoy enferma y me ___ las manos.', 'I am sick and my hands hurt.', 'duelen', Kit::form('duelen', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.garganta', 'Tengo dolor de cabeza y me duele la garganta.', 'I have a headache and my throat hurts.', [Kit::word('el dolor', 'dolor'), Kit::word('la cabeza', 'cabeza'), Kit::word('me duele'), Kit::word('la garganta', 'garganta')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ana', 'Ana está enferma y tiene la receta.', 'Ana is sick and has the prescription.', [Kit::word('enfermo', 'enferma'), Kit::word('la receta', 'receta')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.medicina', 'El médico tiene la medicina para el dolor de cabeza.', 'The doctor has the medicine for the headache.', [Kit::word('el médico', 'médico'), Kit::word('la medicina', 'medicina'), Kit::word('el dolor', 'dolor'), Kit::word('la cabeza', 'cabeza')], 'dictation', $set),
        ];
    }
}
