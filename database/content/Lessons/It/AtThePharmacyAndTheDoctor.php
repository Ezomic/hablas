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

final class AtThePharmacyAndTheDoctor implements UnitContent
{
    private const NOTE_E = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

    private const NOTE_E_ACCENT = 'È (is) and e (and) sound close. In writing, the accent on è is what tells them apart.';

    private const NOTE_HO = 'Ho (I have) sounds the same as o (or): the h is silent and only shows in writing.';

    private const NOTE_HA = 'Ha (has) sounds the same as a (to, at): the h is silent and only shows in writing.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'at-the-pharmacy-and-the-doctor';
    }

    public function words(): array
    {
        return [
            new WordData('la farmacia', cue: 'pharmacy'),
            new WordData('il medico', cue: 'doctor (man)', accepted: ['il dottore', 'la dottoressa'], forms: ['dal medico', 'della dottoressa']),
            new WordData('la medicina', cue: 'medicine', accepted: ['il farmaco']),
            new WordData('il dolore', cue: 'pain, ache', note: 'Il dolore is a pain, as in una medicina per il dolore (a medicine for the pain). For a headache or a sore throat, say mal di testa and mal di gola.'),
            new WordData('la testa', cue: 'head', forms: ['mal di testa'], note: 'Ho mal di testa (I have a headache) is the everyday way to say it. Ho male alla testa also works.'),
            new WordData('la gola', cue: 'throat', forms: ['mal di gola'], note: 'Ho mal di gola (I have a sore throat) is the everyday way to say it. Ho male alla gola also works.'),
            new WordData('la febbre', cue: 'fever'),
            new WordData('malato', cue: 'sick, ill (masculine)', forms: ['malata']),
            new WordData('la ricetta', cue: 'prescription (from the doctor)', note: 'La ricetta is the paper from the doctor. The same word also means a recipe.'),
            new WordData('mi fa male', cue: 'it hurts (me)', forms: ['mi fanno male', 'piedi', 'mani'], note: 'Mi fa male is used with the thing that hurts: mi fa male la testa. For more than one thing you say mi fanno male: mi fanno male i piedi (the feet) and mi fanno male le mani (the hands). Note that la mano is feminine despite its -o ending: la mano, le mani.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ho la febbre e mi fa male la testa.', 'english' => 'I have a fever and my head hurts.'],
            ['text' => 'Mi fanno male i piedi.', 'english' => 'My feet hurt.'],
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

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.ho-febbre', 'Io ___ la febbre.', ['ho', 'ha', 'hai'], 'ho', Kit::form('ho', true), 'Io goes with ho. Ha is for he, she or you (formal), and hai is for you (informal).', 'choose', glosses: ['ho' => 'I have', 'ha' => 'he, she or you (formal) has', 'hai' => 'you (informal) have']),
            Kit::gap($stage, 'sentences.choose_gap.fa-testa', 'Mi ___ male la testa.', ['fa', 'fanno'], 'fa', Kit::form('fa', true), 'La testa is one thing, so the verb is fa. Fanno is for more than one thing.', 'choose', glosses: ['fa' => 'it does', 'fanno' => 'they do']),
            Kit::gap($stage, 'sentences.choose_gap.fanno-piedi', 'Mi ___ male i piedi.', ['fa', 'fanno'], 'fanno', Kit::form('fanno', true), 'I piedi is more than one thing, so the verb is fanno.', 'choose', glosses: ['fa' => 'it does', 'fanno' => 'they do']),
            Kit::gap($stage, 'sentences.choose_gap.anna-malata', 'Anna è ___.', ['malata', 'malato'], 'malata', Kit::word('malato', 'malata'), 'Anna is a woman, so the adjective ends in -a.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.vado-farmacia', 'Vado in ___.', ['farmacia', 'febbre', 'testa'], 'farmacia', Kit::word('la farmacia', 'farmacia'), 'You can go to a place. La farmacia is a place, but la febbre and la testa are not.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.ha-medicina', 'Ha una ___ per la febbre?', ['medicina', 'testa', 'gola'], 'medicina', Kit::word('la medicina', 'medicina'), 'You ask for medicine for a fever. You do not ask for a head or a throat.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.anna-ha', 'Anna ___ la febbre.', 'Anna has a fever.', 'ha', Kit::form('ha'), 'Anna is one person, so the verb is ha.'),
            Kit::typeGap($stage, 'sentences.type_gap.fa-gola', 'Mi ___ male la gola.', 'My throat hurts.', 'fa', Kit::form('fa'), 'La gola is one thing, so the verb is fa.'),
            Kit::typeGap($stage, 'sentences.type_gap.fanno-mani', 'Mi ___ male le mani.', 'My hands hurt.', 'fanno', Kit::form('fanno', true), 'Le mani is more than one thing, so the verb is fanno.'),
            Kit::typeGap($stage, 'sentences.type_gap.dolore', 'Ha una medicina per il ___?', 'Do you have a medicine for the pain? (formal you)', 'dolore', Kit::word('il dolore', 'dolore')),
            Kit::typeGap($stage, 'sentences.type_gap.paolo-malato', 'Paolo è ___.', 'Paolo is sick.', 'malato', Kit::word('malato'), 'Paolo is a man, so the adjective ends in -o.'),

            Kit::translate($stage, 'sentences.translate.febbre', 'I have a fever.', ['Ho la febbre.', 'Io ho la febbre.'], [Kit::word('la febbre', 'febbre'), Kit::form('ho')]),
            Kit::translate($stage, 'sentences.translate.testa', 'My head hurts, Anna.', ['Anna, mi fa male la testa.', 'Mi fa male la testa, Anna.', 'Anna, la testa mi fa male.', 'La testa mi fa male, Anna.'], [Kit::word('la testa', 'testa'), Kit::form('fa')]),
            Kit::translate($stage, 'sentences.translate.medico', 'The doctor has a fever.', ['Il medico ha la febbre.', 'Il dottore ha la febbre.', 'La dottoressa ha la febbre.'], [Kit::word('il medico', 'medico', ['dottore', 'dottoressa']), Kit::form('ha')]),

            Kit::build($stage, 'sentences.build.dolore-gola', 'I have a sore throat.', 'Ho mal di gola.', ['fa'], [Kit::word('la gola', 'mal di gola'), Kit::form('ho')]),
            Kit::build($stage, 'sentences.build.vado-farmacia', 'I go to the pharmacy.', 'Vado in farmacia.', ['va'], [Kit::word('la farmacia', 'farmacia')]),
            Kit::build($stage, 'sentences.build.medicina-febbre', 'The medicine is for the fever.', 'La medicina è per la febbre.', ['con'], [Kit::word('la medicina', 'medicina'), Kit::word('la febbre', 'febbre')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.gola', 'Mi fa male la gola.', ['My throat hurts.', 'My head hurts.', 'My hands hurt.', 'My feet hurt.'], 'My throat hurts.', [Kit::word('mi fa male'), Kit::word('la gola', 'gola')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.anna-malata', 'Anna è malata.', ['Anna is sick.', 'Anna is at the doctor.', 'Anna has a fever.', 'Anna is at the pharmacy.'], 'Anna is sick.', [Kit::word('malato', 'malata')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ricetta', 'Vado in farmacia con la ricetta.', ['I go to the pharmacy with the prescription.', 'I go to the doctor with the prescription.', 'I go to the pharmacy with the medicine.', 'I go to the pharmacy with Anna.'], 'I go to the pharmacy with the prescription.', [Kit::word('la farmacia', 'farmacia'), Kit::word('la ricetta', 'ricetta')]),
            Kit::listenType($stage, 'sentences.listen_type.dolore-testa', 'Ho mal di testa.', 'I have a headache.', [Kit::word('la testa', 'mal di testa'), Kit::form('ho')], homophoneNote: self::NOTE_HO),
            Kit::listenType($stage, 'sentences.listen_type.medico-ricetta', 'Il medico ha la ricetta.', 'The doctor has the prescription.', [Kit::word('il medico', 'medico'), Kit::word('la ricetta', 'ricetta'), Kit::form('ha')], homophoneNote: self::NOTE_HA),
            Kit::listenType($stage, 'sentences.listen_type.fanno-piedi', 'Mi fanno male i piedi.', 'My feet hurt.', [Kit::word('mi fa male', 'mi fanno male')]),
            Kit::listenType($stage, 'sentences.listen_type.medicina-gola', 'La medicina è per il dolore.', 'The medicine is for the pain.', [Kit::word('la medicina', 'medicina'), Kit::word('il dolore', 'dolore')], homophoneNote: self::NOTE_E_ACCENT),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.fa-testa', 'Mi fa male la testa.', 'My head hurts.', [Kit::word('mi fa male'), Kit::word('la testa', 'testa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.dal-medico', 'Vado dal medico.', 'I am going to the doctor.', [Kit::word('il medico', 'medico')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.farmacia', 'La farmacia ha la medicina.', 'The pharmacy has the medicine.', [Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina'), Kit::form('ha')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.dolore-febbre', 'Ho la febbre e mal di gola.', 'I have a fever and a sore throat.', [Kit::word('la gola', 'mal di gola'), Kit::word('la febbre', 'febbre'), Kit::form('ho')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.come-stai', 'Come stai?', 'How are you? (informal you)', [['ho', 'mi', 'sto', 'sono'], ['febbre', 'dolore', 'male', 'fa', 'fanno', 'malato', 'malata', 'bene']], 'Ho la febbre.', [Kit::word('la febbre', 'febbre'), Kit::form('ho')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.sei-malato', 'Sei malato?', 'Are you sick? (informal you)', [['sì', 'no', 'sono', 'sto'], ['malato', 'malata', 'bene', 'male']], 'Sì, sono malato.', [Kit::word('malato')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ce-farmacia', "C'è una farmacia qui?", 'Is there a pharmacy here?', [['sì', 'no', "c'è"], ['farmacia']], "Sì, c'è una farmacia qui.", [Kit::word('la farmacia', 'farmacia')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.medico', 'Read the conversation at the doctor.', [
                Kit::line('Medico', 'Buongiorno. Come sta?'),
                Kit::line('Paolo', 'Ho la febbre. Mi fa male la gola.'),
                Kit::line('Medico', 'È malato. Ecco la ricetta.'),
                Kit::line('Medico', 'La medicina è per la febbre. Prenda la medicina e riposi.'),
                Kit::line('Paolo', 'Grazie. Vado in farmacia.'),
            ], [
                Kit::question('What is wrong with Paolo?', ['He has a fever and a sore throat', 'He has a headache', 'He is not sick'], 'He has a fever and a sore throat'),
                Kit::question('What does the doctor tell Paolo to do?', ['Take the medicine and rest', 'Go to work', 'Call Anna'], 'Take the medicine and rest'),
                Kit::question('Where does Paolo go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [Kit::word('il medico', 'medico'), Kit::word('la ricetta', 'ricetta'), Kit::word('la medicina', 'medicina'), Kit::word('la farmacia', 'farmacia'), Kit::word('la febbre', 'febbre')], 'read', glosses: ['prenda' => 'take', 'riposi' => 'rest']),
            Kit::gap($stage, 'task.choose_gap.ricetta', 'Ecco la ___ per la farmacia.', ['ricetta', 'febbre', 'gola'], 'ricetta', Kit::word('la ricetta', 'ricetta'), 'Ecco means here you are, so you hand something over. You can hand over a prescription, but not a fever or a throat.', 'read'),
            Kit::gap($stage, 'task.choose_gap.paolo-malato', 'Paolo ha la febbre, è ___.', ['malato', 'malata'], 'malato', Kit::word('malato'), 'Paolo is a man, so the adjective ends in -o.', 'read'),

            Kit::transform($stage, 'task.transform.anna-ha', 'Say it about Anna.', 'Ho la febbre.', ['Anna ha la febbre.'], [Kit::word('la febbre', 'febbre'), Kit::form('ha')]),
            Kit::transform($stage, 'task.transform.fanno', 'Change la testa to i piedi.', 'Mi fa male la testa.', ['Mi fanno male i piedi.'], [Kit::form('fanno', true)]),
            Kit::transform($stage, 'task.transform.hai', 'Say it to a friend (informal you).', 'Ho mal di gola.', ['Hai mal di gola.', 'Tu hai mal di gola.'], [Kit::word('la gola', 'mal di gola'), Kit::form('hai')]),
            Kit::writeGuided($stage, 'task.write_guided.febbre', 'Say that you have a fever and that your head hurts.', ['ho', 'febbre', 'mi fa male', 'testa'], 'Ho la febbre e mi fa male la testa.', [
                ['forms' => ['ho'], 'term' => null],
                ['forms' => ['febbre'], 'term' => 'la febbre'],
                ['forms' => ['fa'], 'term' => 'mi fa male'],
                ['forms' => ['testa'], 'term' => 'la testa'],
            ], [Kit::word('la febbre', 'febbre'), Kit::word('mi fa male'), Kit::word('la testa', 'testa')]),
            Kit::writeGuided($stage, 'task.write_guided.medico', 'Say that you are sick and that you go to the doctor.', ['sono', 'malato', 'vado', 'medico'], 'Sono malato. Vado dal medico.', [
                ['forms' => ['sono'], 'term' => null],
                ['forms' => ['malato', 'malata'], 'term' => 'malato'],
                ['forms' => ['vado'], 'term' => null],
                ['forms' => ['medico', 'dottore', 'dottoressa'], 'term' => 'il medico'],
            ], [Kit::word('malato'), Kit::word('il medico', 'medico')]),
            Kit::build($stage, 'task.build.ricetta-medicina', 'I have the prescription for the pain.', 'Ho la ricetta per il dolore.', ['ha', 'a'], [Kit::word('la ricetta', 'ricetta'), Kit::word('il dolore', 'dolore'), Kit::form('ho')], 'write'),
            Kit::build($stage, 'task.build.mani-febbre', 'My hands hurt and I have a fever.', 'Mi fanno male le mani e ho la febbre.', ['fa', 'ha'], [Kit::word('la febbre', 'febbre'), Kit::form('fanno', true)], 'write'),
            Kit::build($stage, 'task.build.anna-medico', 'Anna is sick and goes to the doctor.', 'Anna è malata e va dal medico.', ['malato', 'vado'], [Kit::word('malato', 'malata'), Kit::word('il medico', 'medico')], 'write'),
            Kit::translate($stage, 'task.translate.gola', 'Do you have medicine for a sore throat? (formal you)', ['Ha una medicina per la gola?', 'Ha una medicina per il dolore alla gola?', 'Ha un farmaco per la gola?', 'Ha un farmaco per il dolore alla gola?', 'Lei ha una medicina per la gola?', 'Lei ha una medicina per il dolore alla gola?', 'Ha una medicina per il mal di gola?', 'Ha un farmaco per il mal di gola?', 'Lei ha una medicina per il mal di gola?'], [Kit::word('la medicina', 'medicina', ['farmaco']), Kit::word('la gola', 'gola'), Kit::form('ha')], 'write'),
            Kit::translate($stage, 'task.translate.gola-febbre', 'My throat hurts and I have a fever.', ['Mi fa male la gola e ho la febbre.', 'Ho la febbre e mi fa male la gola.', 'La gola mi fa male e ho la febbre.', 'Ho la febbre e la gola mi fa male.'], [Kit::word('la gola', 'gola'), Kit::word('la febbre', 'febbre'), Kit::form('ho')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.luca-anna', [
                Kit::line('Luca', 'Ciao, Anna. Come stai?'),
                Kit::line('Anna', 'Sono malata. Ho la febbre e mi fa male la testa.'),
                Kit::line('Luca', "In farmacia c'è una medicina per la febbre. Vado."),
                Kit::line('Anna', 'Grazie, Luca.'),
            ], [
                Kit::question('What does Anna have?', ['A fever and a headache', 'A sore throat', 'A prescription'], 'A fever and a headache'),
                Kit::question('Where does Luca go?', ['To the pharmacy', 'To the doctor', 'To the airport'], 'To the pharmacy'),
                Kit::question('What is the medicine for?', ['The fever', 'The throat', 'The feet'], 'The fever'),
            ], [
                Kit::question('Who is sick?', ['Anna', 'Luca', 'Nobody'], 'Anna'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Who says thank you at the end?', ['Anna', 'Luca', 'Nobody'], 'Anna'),
            ], [Kit::word('malato', 'malata'), Kit::word('la febbre', 'febbre'), Kit::word('la testa', 'testa'), Kit::word('la farmacia', 'farmacia'), Kit::word('mi fa male')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.malato-febbre', 'Sono malato e ho la febbre.', 'I am sick and I have a fever.', [Kit::word('malato'), Kit::word('la febbre', 'febbre'), Kit::form('ho')], 'listen', homophoneNote: self::NOTE_E.' '.self::NOTE_HO),
            Kit::listenType($stage, 'task.listen_type.paolo-ricetta', 'Paolo ha la ricetta del medico.', "Paolo has the doctor's prescription.", [Kit::word('la ricetta', 'ricetta'), Kit::word('il medico', 'medico'), Kit::form('ha')], 'listen', homophoneNote: self::NOTE_HA),
            Kit::listenType($stage, 'task.listen_type.anna-gola', 'Anna ha la febbre e mal di gola.', 'Anna has a fever and a sore throat.', [Kit::word('la gola', 'mal di gola'), Kit::form('ha')], 'listen', homophoneNote: self::NOTE_HA.' '.self::NOTE_E),

            Kit::speakAnswer($stage, 'task.speak_answer.dolore-gola', 'Hai mal di gola?', 'Do you have a sore throat? (informal you)', [['sì', 'no', 'ho'], ['mal', 'gola', 'male']], 'Sì, ho mal di gola.', [Kit::word('la gola', 'mal di gola'), Kit::form('ho')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medico', 'Il medico è qui o lì?', 'Is the doctor here or over there?', [['medico', 'è'], ['qui', 'lì']], 'Il medico è qui.', [Kit::word('il medico', 'medico')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medicina', 'Hai la medicina per il dolore?', 'Do you have the medicine for the pain? (informal you)', [['sì', 'no', 'ho'], ['medicina', 'dolore']], 'Sì, ho la medicina per il dolore.', [Kit::word('la medicina', 'medicina'), Kit::word('il dolore', 'dolore'), Kit::form('ho')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ricetta', 'Hai la ricetta?', 'Do you have the prescription? (informal you)', [['sì', 'no', 'ho'], ['ricetta']], 'Sì, ho la ricetta.', [Kit::word('la ricetta', 'ricetta'), Kit::form('ho')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.febbre-testa', 'Buongiorno, mi fa male la testa e ho la febbre.', 'Good morning, my head hurts and I have a fever.', [Kit::word('la febbre', 'febbre'), Kit::word('mi fa male'), Kit::word('la testa', 'testa')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.medico-ricetta', 'Il medico ha la ricetta, Anna.', 'The doctor has the prescription, Anna.', [Kit::word('il medico', 'medico'), Kit::word('la ricetta', 'ricetta'), Kit::form('ha')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.farmacia', 'The pharmacy has medicine for the pain.', ['La farmacia ha una medicina per il dolore.', 'La farmacia ha la medicina per il dolore.', 'La farmacia ha un farmaco per il dolore.', 'La farmacia ha il farmaco per il dolore.'], [Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina', ['farmaco']), Kit::word('il dolore', 'dolore'), Kit::form('ha')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ricetta', 'I have a prescription from the doctor.', ['Ho una ricetta del medico.', 'Ho una ricetta del dottore.', 'Ho una ricetta della dottoressa.', 'Ho la ricetta del medico.', 'Ho la ricetta del dottore.', 'Ho la ricetta della dottoressa.', 'Io ho una ricetta del medico.', 'Io ho una ricetta del dottore.', 'Io ho una ricetta della dottoressa.', 'Io ho la ricetta del medico.', 'Io ho la ricetta del dottore.', 'Io ho la ricetta della dottoressa.'], [Kit::word('la ricetta', 'ricetta'), Kit::word('il medico', 'medico', ['dottore', 'dottoressa']), Kit::form('ho')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.febbre', 'Do you have a fever? (informal you)', ['Hai la febbre?', 'Tu hai la febbre?'], [Kit::word('la febbre', 'febbre'), Kit::form('hai')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paolo', 'Paolo has a sore throat.', ['Paolo ha mal di gola.', 'Paolo ha male alla gola.', 'Paolo ha un dolore alla gola.'], [Kit::word('la gola', 'gola'), Kit::form('ha')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.fa', 'Sono malato e mi ___ male la gola.', 'I am sick and my throat hurts.', 'fa', Kit::form('fa', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.fanno', 'Mi ___ male i piedi e le mani.', 'My feet and hands hurt.', 'fanno', Kit::form('fanno', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.malata', 'Sono malata e ho la febbre.', 'I am sick and I have a fever.', [Kit::word('malato', 'malata'), Kit::word('la febbre', 'febbre')], 'dictation', $set, homophoneNote: self::NOTE_E.' '.self::NOTE_HO),
            Kit::listenType($stage, 'check.a.listen_type.testa', 'Mi fa male la testa e ho la febbre.', 'My head hurts and I have a fever.', [Kit::word('mi fa male'), Kit::word('la testa', 'testa'), Kit::word('la febbre', 'febbre')], 'dictation', $set, homophoneNote: self::NOTE_E.' '.self::NOTE_HO),
            Kit::listenType($stage, 'check.a.listen_type.dottoressa', 'La dottoressa è qui.', 'The doctor is here.', [Kit::word('il medico', 'dottoressa')], 'dictation', $set, homophoneNote: self::NOTE_E_ACCENT),
            Kit::listenPassage($stage, 'check.a.listen_passage.ricetta', [
                Kit::line('Medico', 'Buongiorno. Come sta?'),
                Kit::line('Luca', 'Sono malato. Ho la febbre e mi fanno male le mani.'),
                Kit::line('Medico', 'Ecco la ricetta per la medicina.'),
                Kit::line('Luca', 'Grazie. Con la ricetta vado in farmacia.'),
            ], [
                Kit::question('What is wrong with Luca?', ['He has a fever and his hands hurt', 'His head hurts', 'He has a prescription'], 'He has a fever and his hands hurt'),
                Kit::question('What does the doctor give Luca?', ['A prescription', 'The medicine', 'A fever'], 'A prescription'),
                Kit::question('Where does Luca go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [
                Kit::question('Who speaks first?', ['The doctor', 'Luca', 'Nobody'], 'The doctor'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Luca say thank you?', ['Yes', 'No', 'The conversation does not say'], 'Yes'),
            ], [Kit::word('malato'), Kit::word('mi fa male', 'mi fanno male'), Kit::word('la ricetta', 'ricetta'), Kit::word('la medicina', 'medicina')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.marta', 'Read the conversation.', [
                Kit::line('Marta', 'Ciao, Paolo. Sono malata.'),
                Kit::line('Paolo', 'Come stai?'),
                Kit::line('Marta', 'Ho la febbre e mal di testa.'),
                Kit::line('Paolo', 'La farmacia ha la medicina per la febbre.'),
            ], [
                Kit::question('What is wrong with Marta?', ['She has a headache and a fever', 'She has a sore throat', 'She has a prescription'], 'She has a headache and a fever'),
                Kit::question('Where is there medicine for the fever?', ['At the pharmacy', 'At the doctor', 'At home'], 'At the pharmacy'),
            ], [Kit::word('malato', 'malata'), Kit::word('la testa', 'mal di testa'), Kit::word('la febbre', 'febbre'), Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.medicina', "C'è una medicina per la gola?", 'Is there medicine for the throat?', [['sì', 'no', "c'è"], ['medicina', 'gola']], "Sì, c'è una medicina per la gola.", [Kit::word('la medicina', 'medicina'), Kit::word('la gola', 'gola')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.farmacia', 'La farmacia è qui o lì?', 'Is the pharmacy here or over there?', [['farmacia', 'è'], ['qui', 'lì']], 'La farmacia è qui.', [Kit::word('la farmacia', 'farmacia')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.dolore', 'Hai mal di testa?', 'Do you have a headache? (informal you)', [['sì', 'no', 'ho'], ['mal', 'testa', 'male']], 'Sì, ho mal di testa.', [Kit::word('la testa', 'mal di testa')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.medico', 'The doctor is sick and has a fever.', ['Il medico è malato e ha la febbre.', 'Il dottore è malato e ha la febbre.', 'La dottoressa è malata e ha la febbre.'], [Kit::word('il medico', 'medico', ['dottore', 'dottoressa']), Kit::word('malato', null, ['malata']), Kit::word('la febbre', 'febbre'), Kit::form('ha')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ricetta', 'I have the prescription and I go to the pharmacy.', ['Ho la ricetta e vado in farmacia.', 'Io ho la ricetta e vado in farmacia.', 'Ho la ricetta e vado alla farmacia.', 'Io ho la ricetta e vado alla farmacia.'], [Kit::word('la ricetta', 'ricetta'), Kit::word('la farmacia', 'farmacia'), Kit::form('ho')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.gola', 'Does the pharmacy have medicine for a sore throat?', ['La farmacia ha una medicina per la gola?', 'La farmacia ha la medicina per la gola?', 'La farmacia ha un farmaco per la gola?', 'La farmacia ha il farmaco per la gola?', 'La farmacia ha una medicina per il mal di gola?', 'La farmacia ha un farmaco per il mal di gola?'], [Kit::word('la farmacia', 'farmacia'), Kit::word('la medicina', 'medicina', ['farmaco']), Kit::word('la gola', 'gola'), Kit::form('ha')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.piedi', 'My feet hurt and I have a fever.', ['Mi fanno male i piedi e ho la febbre.', 'Ho la febbre e mi fanno male i piedi.', 'I piedi mi fanno male e ho la febbre.', 'Ho la febbre e i piedi mi fanno male.'], [Kit::word('mi fa male', 'mi fanno male'), Kit::word('la febbre', 'febbre'), Kit::form('fanno', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.fa', 'Paolo, ho la febbre e mi ___ male la testa.', 'Paolo, I have a fever and my head hurts.', 'fa', Kit::form('fa', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.fanno', 'Sono malata e mi ___ male le mani.', 'I am sick and my hands hurt.', 'fanno', Kit::form('fanno', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.gola', 'Ho mal di testa e mi fa male la gola.', 'I have a headache and my throat hurts.', [Kit::word('la testa', 'mal di testa'), Kit::word('mi fa male'), Kit::word('la gola', 'gola')], 'dictation', $set, homophoneNote: self::NOTE_HO.' '.self::NOTE_E),
            Kit::listenType($stage, 'check.b.listen_type.anna', 'Anna è malata e ha la ricetta.', 'Anna is sick and has the prescription.', [Kit::word('malato', 'malata'), Kit::word('la ricetta', 'ricetta')], 'dictation', $set, homophoneNote: self::NOTE_E_ACCENT.' '.self::NOTE_HA),
            Kit::listenType($stage, 'check.b.listen_type.medicina', 'Il medico ha la medicina per il dolore.', 'The doctor has the medicine for the pain.', [Kit::word('il medico', 'medico'), Kit::word('la medicina', 'medicina'), Kit::word('il dolore', 'dolore')], 'dictation', $set, homophoneNote: self::NOTE_HA),
        ];
    }
}
