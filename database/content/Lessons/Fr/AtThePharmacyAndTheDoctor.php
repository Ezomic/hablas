<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

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
    private const A_NOTE = 'A (has, the verb) and à (to, at) sound the same, and the sentence tells you which is which.';

    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'at-the-pharmacy-and-the-doctor';
    }

    public function words(): array
    {
        return [
            new WordData('la pharmacie', cue: 'pharmacy'),
            new WordData('le médecin', cue: 'doctor (physician)', accepted: ['le docteur', 'la médecin'], forms: ['chez', 'médecins'], note: 'To go to the doctor you say aller chez le médecin: chez means at or to the home or practice of a person. French does not say à le médecin.'),
            new WordData('le médicament', cue: 'medicine', forms: ['médicaments']),
            new WordData('la douleur', cue: 'pain, ache', note: 'Une douleur à la gorge is correct but more formal. In daily speech use J\'ai mal à la gorge.'),
            new WordData('la tête', cue: 'head'),
            new WordData('la gorge', cue: 'throat'),
            new WordData('la fièvre', cue: 'fever', note: 'You say J\'ai de la fièvre (literally: I have some fever). J\'ai la fièvre is also correct.'),
            new WordData('malade', cue: 'sick, ill (the same for a man and a woman; the plural is malades)', forms: ['malades']),
            new WordData('l\'ordonnance', cue: 'prescription (from the doctor)', forms: ['ordonnance']),
            new WordData('avoir mal', cue: 'to hurt, to be in pain (avoir mal à + body part)', forms: ['mal', 'ventre', 'dos', 'pieds', 'mains', 'l\'oreille'], note: 'You say J\'ai mal à la tête (I have a headache), not ma tête. A headache is also le mal de tête and a sore throat le mal de gorge: un médicament pour le mal de tête. With a masculine word à becomes au (au dos, au ventre) and with a plural it becomes aux (aux pieds, aux mains).'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => "J'ai de la fièvre et j'ai mal à la tête.", 'english' => 'I have a fever and my head hurts.'],
            ['text' => "J'ai mal aux pieds.", 'english' => 'My feet hurt.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.tu-fievre', 'Tu ___ la fièvre.', ['as', 'a', 'ai'], 'as', Kit::form('as', true), 'Tu goes with as. Ai is for je (j\'ai) and a is for il or elle.', 'choose', 'You have a fever.', ['as' => 'have', 'a' => 'has', 'ai' => 'have']),
            Kit::gap($stage, 'sentences.choose_gap.mal-tete', "J'ai mal ___ tête.", ['à la', 'au', 'aux'], 'à la', Kit::form('à la', true), 'Tête is feminine singular, so à la. Au is for a masculine word and aux for more than one.', 'choose', 'My head hurts.'),
            Kit::gap($stage, 'sentences.choose_gap.mal-pieds', "J'ai mal ___ pieds.", ['aux', 'au', 'à la'], 'aux', Kit::form('aux', true), 'Pieds is plural, so à + les becomes aux.', 'choose', 'My feet hurt.'),
            Kit::gap($stage, 'sentences.choose_gap.paul-anne-malades', 'Paul et Anne sont ___.', ['malades', 'malade'], 'malades', Kit::word('malade', 'malades'), 'Two people, so malade takes an -s. It has no feminine ending: Anne est malade, Paul est malade.', 'choose', 'Paul and Anne are sick.'),
            Kit::gap($stage, 'sentences.choose_gap.vais-pharmacie', 'Je vais à la ___.', ['pharmacie', 'fièvre', 'tête'], 'pharmacie', Kit::word('la pharmacie', 'pharmacie'), 'You can go to a place. La pharmacie is a place, but la fièvre and la tête are not.', 'choose', 'I go to the pharmacy.'),
            Kit::gap($stage, 'sentences.choose_gap.medicament', 'Vous avez un ___ pour la fièvre ?', ['médicament', 'dos', 'ventre'], 'médicament', Kit::word('le médicament', 'médicament'), 'You ask for medicine for a fever. You do not ask for a back or a belly.', 'choose', 'Do you have a medicine for the fever?', ['dos' => 'back', 'ventre' => 'belly']),

            Kit::typeGap($stage, 'sentences.type_gap.anne-a', 'Anne ___ de la fièvre.', 'Anne has a fever.', 'a', Kit::form('a'), 'Anne is one person (elle), so avoir is a.'),
            Kit::typeGap($stage, 'sentences.type_gap.mal-gorge', "J'ai mal ___ gorge.", 'My throat hurts.', 'à la', Kit::form('à la', true), 'Gorge is feminine singular, so à la.'),
            Kit::typeGap($stage, 'sentences.type_gap.mal-mains', "J'ai mal ___ mains.", 'My hands hurt.', 'aux', Kit::form('aux', true), 'Mains is plural, so à + les becomes aux.', glosses: ['mains' => 'hands']),
            Kit::typeGap($stage, 'sentences.type_gap.douleur', 'Où est la ___ ?', 'Where is the pain?', 'douleur', Kit::word('la douleur', 'douleur')),
            Kit::typeGap($stage, 'sentences.type_gap.paul-malade', 'Paul est ___.', 'Paul is sick.', 'malade', Kit::word('malade'), 'Malade is the same for a man and a woman, so there is no ending to add.'),

            Kit::translate($stage, 'sentences.translate.fievre', 'I have a fever.', ["J'ai de la fièvre.", "J'ai la fièvre."], [Kit::word('la fièvre', 'fièvre'), Kit::form("j'ai")]),
            Kit::translate($stage, 'sentences.translate.tete', 'My head hurts, Anne.', ["Anne, j'ai mal à la tête.", "J'ai mal à la tête, Anne."], [Kit::word('la tête', 'tête'), Kit::form('à la')]),
            Kit::translate($stage, 'sentences.translate.medecin', 'The doctor has a fever.', ['Le médecin a de la fièvre.', 'Le médecin a la fièvre.', 'La médecin a de la fièvre.', 'La médecin a la fièvre.', 'Le docteur a de la fièvre.', 'Le docteur a la fièvre.'], [Kit::word('le médecin', 'médecin', ['docteur']), Kit::form('a')]),

            Kit::build($stage, 'sentences.build.douleur-gorge', 'I have a pain in my throat.', "J'ai une douleur à la gorge.", ['au'], [Kit::word('la douleur', 'douleur'), Kit::word('la gorge', 'gorge'), Kit::form('à la')]),
            Kit::build($stage, 'sentences.build.vais-pharmacie', 'I go to the pharmacy.', 'Je vais à la pharmacie.', ['va'], [Kit::word('la pharmacie', 'pharmacie')]),
            Kit::build($stage, 'sentences.build.medicament-fievre', 'The medicine is for the fever.', 'Le médicament est pour la fièvre.', ['avec'], [Kit::word('le médicament', 'médicament'), Kit::word('la fièvre', 'fièvre')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.douleur', 'Où est la douleur ?', ['Where is the pain?', 'Where is the pharmacy?', 'Where is the doctor?', 'Where is the fever?'], 'Where is the pain?', [Kit::word('la douleur', 'douleur')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.anne-malade', 'Anne est malade.', ['Anne is sick.', 'Anne is at the doctor.', 'Anne has a fever.', 'Anne is at the pharmacy.'], 'Anne is sick.', [Kit::word('malade')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ordonnance', "Je vais à la pharmacie avec l'ordonnance.", ['I go to the pharmacy with the prescription.', 'I go to the doctor with the prescription.', 'I go to the pharmacy with the medicine.', 'I go to the pharmacy with Anne.'], 'I go to the pharmacy with the prescription.', [Kit::word('la pharmacie', 'pharmacie'), Kit::word("l'ordonnance", "l'ordonnance")]),
            Kit::listenType($stage, 'sentences.listen_type.mal-tete', "J'ai mal à la tête.", 'I have a headache.', [Kit::word('la tête', 'tête'), Kit::word('avoir mal', 'mal'), Kit::form("j'ai")], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.medecin-ordonnance', "Le médecin a l'ordonnance.", 'The doctor has the prescription.', [Kit::word('le médecin', 'médecin'), Kit::word("l'ordonnance", "l'ordonnance"), Kit::form('a')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.mal-pieds', "J'ai mal aux pieds.", 'My feet hurt.', [Kit::word('avoir mal', 'mal'), Kit::form('aux')]),
            Kit::listenType($stage, 'sentences.listen_type.medicament-gorge', 'Le médicament est pour la gorge.', 'The medicine is for the throat.', [Kit::word('le médicament', 'médicament'), Kit::word('la gorge', 'gorge')], homophoneNote: self::EST_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.mal-tete', "J'ai mal à la tête.", 'My head hurts.', [Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.chez-medecin', 'Je vais chez le médecin.', 'I am going to the doctor.', [Kit::word('le médecin', 'médecin')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pharmacie', 'La pharmacie a le médicament.', 'The pharmacy has the medicine.', [Kit::word('la pharmacie', 'pharmacie'), Kit::word('le médicament', 'médicament'), Kit::form('a')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fievre', "J'ai de la fièvre.", 'I have a fever.', [Kit::word('la fièvre', 'fièvre'), Kit::form("j'ai")]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.douleur', 'Où as-tu mal ?', 'Where does it hurt? (informal you)', [["j'ai", 'mal'], ['tête', 'gorge', 'ventre', 'dos', 'pieds', 'mains']], "J'ai mal à la tête.", [Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête'), Kit::form("j'ai")]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.malade', 'Tu es malade ?', 'Are you sick? (informal you)', [['oui', 'non', 'suis'], ['malade', 'bien']], 'Oui, je suis malade.', [Kit::word('malade')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pharmacie', 'Il y a une pharmacie ici ?', 'Is there a pharmacy here?', [['oui', 'non', 'a'], ['pharmacie']], 'Oui, il y a une pharmacie ici.', [Kit::word('la pharmacie', 'pharmacie')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.medecin', 'Read the conversation at the doctor.', [
                Kit::line('Médecin', 'Bonjour, Paul. Vous avez mal où ?'),
                Kit::line('Paul', "J'ai mal à la tête et à la gorge. J'ai de la fièvre."),
                Kit::line('Médecin', "Vous êtes malade. Voici l'ordonnance."),
                Kit::line('Médecin', 'Le médicament est pour la fièvre. Prenez le médicament et reposez-vous.'),
                Kit::line('Paul', 'Merci. Je vais à la pharmacie.'),
            ], [
                Kit::question('What is wrong with Paul?', ['He has a fever, a headache and a sore throat', 'He has only a fever', 'He is not sick'], 'He has a fever, a headache and a sore throat'),
                Kit::question('What does the doctor tell Paul to do?', ['Take the medicine and rest', 'Go to work', 'Call Anne'], 'Take the medicine and rest'),
                Kit::question('Where does Paul go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [Kit::word('le médecin', 'médecin'), Kit::word("l'ordonnance", "l'ordonnance"), Kit::word('le médicament', 'médicament'), Kit::word('la pharmacie', 'pharmacie'), Kit::word('la fièvre', 'fièvre'), Kit::word('la douleur', 'douleur'), Kit::word('la tête', 'tête'), Kit::word('la gorge', 'gorge'), Kit::word('avoir mal', 'mal'), Kit::word('malade')], 'read', glosses: ['prenez' => 'take', 'reposez' => 'rest']),
            Kit::gap($stage, 'task.choose_gap.ordonnance', 'Voici ___ pour la pharmacie.', ["l'ordonnance", 'la tête', 'la gorge'], "l'ordonnance", Kit::word("l'ordonnance", "l'ordonnance"), 'Voici means here is, so you hand something over. You can hand over a prescription, but not a head or a throat.', 'read', 'Here is the prescription for the pharmacy.'),
            Kit::gap($stage, 'task.choose_gap.paul-malade', 'Paul a de la fièvre et il est ___.', ['malade', 'malades'], 'malade', Kit::word('malade'), 'Paul is one person, so malade has no -s. It also has no ending for a man or a woman.', 'read', 'Paul has a fever and he is sick.'),

            Kit::transform($stage, 'task.transform.anne-a', 'Say it about Anne.', "J'ai de la fièvre.", ['Anne a de la fièvre.', 'Anne a la fièvre.'], [Kit::word('la fièvre', 'fièvre'), Kit::form('a')]),
            Kit::transform($stage, 'task.transform.aux', 'Change la tête to les pieds.', "J'ai mal à la tête.", ["J'ai mal aux pieds."], [Kit::form('aux', true)]),
            Kit::transform($stage, 'task.transform.tu-as', 'Say it to a friend (informal you).', "J'ai une douleur à la gorge.", ['Tu as une douleur à la gorge.'], [Kit::word('la douleur', 'douleur'), Kit::form('as')]),
            Kit::writeGuided($stage, 'task.write_guided.fievre', 'Say that you have a fever and that your head hurts.', ["j'ai", 'fièvre', 'mal', 'tête'], "J'ai de la fièvre et j'ai mal à la tête.", [
                ['forms' => ["j'ai"], 'term' => null],
                ['forms' => ['fièvre'], 'term' => 'la fièvre'],
                ['forms' => ['mal'], 'term' => 'avoir mal'],
                ['forms' => ['tête'], 'term' => 'la tête'],
            ], [Kit::word('la fièvre', 'fièvre'), Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête')]),
            Kit::writeGuided($stage, 'task.write_guided.medecin', 'Say that you are sick and that you go to the doctor.', ['suis', 'malade', 'vais', 'chez', 'médecin'], 'Je suis malade. Je vais chez le médecin.', [
                ['forms' => ['suis'], 'term' => null],
                ['forms' => ['malade'], 'term' => 'malade'],
                ['forms' => ['vais'], 'term' => null],
                ['forms' => ['chez'], 'term' => null],
                ['forms' => ['médecin', 'docteur'], 'term' => 'le médecin'],
            ], [Kit::word('malade'), Kit::word('le médecin', 'médecin')]),
            Kit::build($stage, 'task.build.ordonnance-medicament', 'I have the prescription for the medicine.', "J'ai l'ordonnance pour le médicament.", ['a', 'au'], [Kit::word("l'ordonnance", "l'ordonnance"), Kit::word('le médicament', 'médicament'), Kit::form("j'ai")], 'write'),
            Kit::build($stage, 'task.build.mains-fievre', 'My hands hurt and I have a fever.', "J'ai mal aux mains et j'ai de la fièvre.", ['au', 'a'], [Kit::word('la fièvre', 'fièvre'), Kit::form('aux', true)], 'write'),
            Kit::build($stage, 'task.build.anne-medecin', 'Anne is sick and she goes to the doctor.', 'Anne est malade et elle va chez le médecin.', ['malades', 'vas'], [Kit::word('malade'), Kit::word('le médecin', 'médecin')], 'write'),
            Kit::translate($stage, 'task.translate.gorge', 'Do you have medicine for a sore throat? (formal you)', ['Vous avez un médicament pour la gorge ?', 'Avez-vous un médicament pour la gorge ?', 'Vous avez un médicament pour le mal de gorge ?', 'Avez-vous un médicament pour le mal de gorge ?', 'Vous avez des médicaments pour la gorge ?', 'Avez-vous des médicaments pour la gorge ?', 'Vous avez des médicaments pour le mal de gorge ?', 'Avez-vous des médicaments pour le mal de gorge ?'], [Kit::word('le médicament', 'médicament', ['médicaments']), Kit::word('la gorge', 'gorge'), Kit::form('avez')], 'write'),
            Kit::translate($stage, 'task.translate.gorge-fievre', 'My throat hurts and I have a fever.', ["J'ai mal à la gorge et j'ai de la fièvre.", "J'ai de la fièvre et j'ai mal à la gorge."], [Kit::word('la gorge', 'gorge'), Kit::word('la fièvre', 'fièvre'), Kit::form("j'ai")], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.luc-anne', [
                Kit::line('Luc', 'Bonjour, Anne. Où as-tu mal ?'),
                Kit::line('Anne', "Je suis malade. J'ai de la fièvre et j'ai mal à la tête."),
                Kit::line('Luc', 'Je vais à la pharmacie. Il y a un médicament pour la fièvre.'),
                Kit::line('Anne', 'Merci, Luc.'),
            ], [
                Kit::question('What does Anne have?', ['A fever and a headache', 'A sore throat', 'A prescription'], 'A fever and a headache'),
                Kit::question('Where does Luc go?', ['To the pharmacy', 'To the doctor', 'To the airport'], 'To the pharmacy'),
                Kit::question('What is the medicine for?', ['The fever', 'The throat', 'The feet'], 'The fever'),
            ], [
                Kit::question('Who is sick?', ['Anne', 'Luc', 'Nobody'], 'Anne'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Who says thank you at the end?', ['Anne', 'Luc', 'Nobody'], 'Anne'),
            ], [Kit::word('malade'), Kit::word('la fièvre', 'fièvre'), Kit::word('la tête', 'tête'), Kit::word('la pharmacie', 'pharmacie'), Kit::word('avoir mal', 'mal')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.malade-fievre', "Je suis malade et j'ai de la fièvre.", 'I am sick and I have a fever.', [Kit::word('malade'), Kit::word('la fièvre', 'fièvre'), Kit::form("j'ai")], 'listen', homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'task.listen_type.paul-ordonnance', "Paul a l'ordonnance du médecin.", "Paul has the doctor's prescription.", [Kit::word("l'ordonnance", "l'ordonnance"), Kit::word('le médecin', 'médecin'), Kit::form('a')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.anne-gorge', 'Anne a une douleur à la gorge.', 'Anne has a pain in her throat.', [Kit::word('la douleur', 'douleur'), Kit::word('la gorge', 'gorge'), Kit::form('a')], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.mal-gorge', 'Tu as mal à la gorge ?', 'Do you have a sore throat? (informal you)', [['oui', 'non', "j'ai"], ['mal', 'gorge']], "Oui, j'ai mal à la gorge.", [Kit::word('avoir mal', 'mal'), Kit::word('la gorge', 'gorge'), Kit::form("j'ai")], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medecin', 'Où est le médecin ?', 'Where is the doctor?', [['médecin', 'est'], ['ici', 'là']], 'Le médecin est ici.', [Kit::word('le médecin', 'médecin')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.medicament', 'Tu as le médicament ?', 'Do you have the medicine? (informal you)', [['oui', 'non', "j'ai"], ['médicament']], "Oui, j'ai le médicament.", [Kit::word('le médicament', 'médicament'), Kit::form("j'ai")], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ordonnance', "Tu as l'ordonnance ?", 'Do you have the prescription? (informal you)', [['oui', 'non', "j'ai"], ['ordonnance', "l'ordonnance"]], "Oui, j'ai l'ordonnance.", [Kit::word("l'ordonnance", "l'ordonnance"), Kit::form("j'ai")], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.fievre-tete', "Bonjour, j'ai mal à la tête et j'ai de la fièvre.", 'Good morning, my head hurts and I have a fever.', [Kit::word('la fièvre', 'fièvre'), Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.medecin-ordonnance', "Le médecin a l'ordonnance, Anne.", 'The doctor has the prescription, Anne.', [Kit::word('le médecin', 'médecin'), Kit::word("l'ordonnance", "l'ordonnance"), Kit::form('a')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.pharmacie', 'The pharmacy has medicine for a headache.', ['La pharmacie a un médicament pour le mal de tête.', 'La pharmacie a des médicaments pour le mal de tête.'], [Kit::word('la pharmacie', 'pharmacie'), Kit::word('le médicament', 'médicament', ['médicaments']), Kit::word('la tête', 'tête'), Kit::word('avoir mal', 'mal'), Kit::form('a')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ordonnance', 'I have a prescription from the doctor.', ["J'ai une ordonnance du médecin.", "J'ai l'ordonnance du médecin.", "J'ai une ordonnance de la médecin.", "J'ai l'ordonnance de la médecin.", "J'ai une ordonnance du docteur.", "J'ai l'ordonnance du docteur."], [Kit::word("l'ordonnance", 'ordonnance', ["l'ordonnance"]), Kit::word('le médecin', 'médecin', ['docteur']), Kit::form("j'ai")], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fievre', 'Do you have a fever? (informal you)', ['Tu as de la fièvre ?', 'Tu as la fièvre ?', 'As-tu de la fièvre ?', 'As-tu la fièvre ?'], [Kit::word('la fièvre', 'fièvre'), Kit::form('as')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paul', 'Paul has a pain in his throat.', ['Paul a une douleur à la gorge.', 'Paul a mal à la gorge.'], [Kit::word('la douleur', 'douleur', ['mal']), Kit::word('avoir mal', 'mal', ['douleur']), Kit::word('la gorge', 'gorge'), Kit::form('a')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.gorge', "Je suis malade et j'ai mal ___ gorge.", 'I am sick and my throat hurts.', 'à la', Kit::form('à la', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.mains', "Marie, j'ai mal ___ mains.", 'Marie, my hands hurt.', 'aux', Kit::form('aux', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marie', 'Marie est malade et elle a de la fièvre.', 'Marie is sick and she has a fever.', [Kit::word('malade'), Kit::word('la fièvre', 'fièvre')], 'dictation', $set, homophoneNote: self::EST_NOTE.' '.self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.paul', 'Paul a mal à la tête et de la fièvre.', 'Paul has a headache and a fever.', [Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête'), Kit::word('la fièvre', 'fièvre')], 'dictation', $set, homophoneNote: self::A_NOTE.' '.self::EST_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.docteur', 'Le médecin est à la pharmacie.', 'The doctor is at the pharmacy.', [Kit::word('le médecin', 'médecin'), Kit::word('la pharmacie', 'pharmacie')], 'dictation', $set, homophoneNote: self::EST_NOTE.' '.self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.ordonnance', [
                Kit::line('Médecin', 'Bonjour. Où avez-vous mal ?'),
                Kit::line('Luc', "Je suis malade et j'ai mal à la gorge."),
                Kit::line('Médecin', "Voici l'ordonnance pour le médicament."),
                Kit::line('Luc', "Merci. Avec l'ordonnance, je vais à la pharmacie."),
            ], [
                Kit::question('What is wrong with Luc?', ['His throat hurts', 'His head hurts', 'He has a fever'], 'His throat hurts'),
                Kit::question('What does the doctor give Luc?', ['A prescription', 'The medicine', 'A fever'], 'A prescription'),
                Kit::question('Where does Luc go?', ['To the pharmacy', 'To the doctor', 'Home'], 'To the pharmacy'),
            ], [
                Kit::question('Who speaks first?', ['The doctor', 'Luc', 'Nobody'], 'The doctor'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Luc say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('malade'), Kit::word('avoir mal', 'mal'), Kit::word('la gorge', 'gorge'), Kit::word("l'ordonnance", "l'ordonnance"), Kit::word('le médicament', 'médicament')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.marie', 'Read the conversation.', [
                Kit::line('Marie', 'Bonjour, Paul. Je suis très malade.'),
                Kit::line('Paul', 'Tu as mal où ?'),
                Kit::line('Marie', "J'ai mal à la tête et de la fièvre."),
                Kit::line('Paul', 'La pharmacie a un médicament pour la fièvre.'),
            ], [
                Kit::question('What is wrong with Marie?', ['She has a headache and a fever', 'She has a sore throat', 'She has a prescription'], 'She has a headache and a fever'),
                Kit::question('Where is there medicine for the fever?', ['At the pharmacy', 'At the doctor', 'At home'], 'At the pharmacy'),
            ], [Kit::word('malade'), Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête'), Kit::word('la fièvre', 'fièvre'), Kit::word('la pharmacie', 'pharmacie'), Kit::word('le médicament', 'médicament')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.medicament', 'Il y a un médicament pour la gorge ?', 'Is there medicine for the throat?', [['oui', 'non', 'a'], ['médicament', 'gorge']], 'Oui, il y a un médicament pour la gorge.', [Kit::word('le médicament', 'médicament'), Kit::word('la gorge', 'gorge')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pharmacie', 'Où est la pharmacie ?', 'Where is the pharmacy?', [['pharmacie', 'est'], ['ici', 'là']], 'La pharmacie est ici.', [Kit::word('la pharmacie', 'pharmacie')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.tete', 'Tu as mal à la tête ?', 'Do you have a headache? (informal you)', [['oui', 'non', "j'ai"], ['mal', 'tête']], "Oui, j'ai mal à la tête.", [Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.medecin', 'The doctor is sick and has a fever.', ['Le médecin est malade et a de la fièvre.', 'Le médecin est malade et a la fièvre.', 'La médecin est malade et a de la fièvre.', 'La médecin est malade et a la fièvre.', 'Le docteur est malade et a de la fièvre.', 'Le docteur est malade et a la fièvre.'], [Kit::word('le médecin', 'médecin', ['docteur']), Kit::word('malade'), Kit::word('la fièvre', 'fièvre'), Kit::form('a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ordonnance', 'I have the prescription and I go to the pharmacy.', ["J'ai l'ordonnance et je vais à la pharmacie."], [Kit::word("l'ordonnance", "l'ordonnance"), Kit::word('la pharmacie', 'pharmacie'), Kit::form("j'ai")], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.douleur', 'Does the pharmacy have medicine for the pain?', ['La pharmacie a un médicament pour la douleur ?', 'La pharmacie a des médicaments pour la douleur ?'], [Kit::word('la pharmacie', 'pharmacie'), Kit::word('le médicament', 'médicament', ['médicaments']), Kit::word('la douleur', 'douleur'), Kit::form('a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tete', 'My head hurts and I have a fever.', ["J'ai mal à la tête et j'ai de la fièvre.", "J'ai de la fièvre et j'ai mal à la tête."], [Kit::word('la tête', 'tête'), Kit::word('avoir mal', 'mal'), Kit::word('la fièvre', 'fièvre'), Kit::form('à la', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pieds', "J'ai mal ___ pieds et j'ai de la fièvre.", 'My feet hurt and I have a fever.', 'aux', Kit::form('aux', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.dos', "Je suis malade et j'ai mal ___ dos.", 'I am sick and my back hurts.', 'au', Kit::form('au', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.douleur', 'Anne a mal à la tête et à la gorge.', 'Anne has a headache and a sore throat.', [Kit::word('avoir mal', 'mal'), Kit::word('la tête', 'tête'), Kit::word('la gorge', 'gorge')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.anne', 'Anne est malade et elle a mal à la gorge.', 'Anne is sick and she has a sore throat.', [Kit::word('malade'), Kit::word('avoir mal', 'mal'), Kit::word('la gorge', 'gorge')], 'dictation', $set, homophoneNote: self::EST_NOTE.' '.self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.medicament', "Le médecin a l'ordonnance pour le médicament.", 'The doctor has the prescription for the medicine.', [Kit::word('le médecin', 'médecin'), Kit::word("l'ordonnance", "l'ordonnance"), Kit::word('le médicament', 'médicament')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
