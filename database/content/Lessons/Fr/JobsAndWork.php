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

final class JobsAndWork implements UnitContent
{
    private const EST = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const EST_ET = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb and et joins two parts of the sentence.';

    private const ET = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et means and.';

    private const ES_OU = 'Es (you are) sounds just like est (is), and ou (or) sounds just like où (where). The sentence tells you which is which: here es goes with tu and ou means or.';

    private const OU = 'Ou (or) and où (where) sound identical, and the sentence tells you which is which: here ou means or.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'jobs-and-work';
    }

    public function words(): array
    {
        return [
            new WordData('travailler', cue: 'to work', forms: ['travaille', 'travailles', 'travaillons', 'travaillez', 'travaillent']),
            new WordData('le professeur', cue: 'teacher (school teacher, prof in speech; man or woman; a woman can also be la professeure)', accepted: ['la professeure'], forms: ['professeur', 'professeure', 'professeurs', 'professeures']),
            new WordData('l\'ingénieur', cue: 'engineer (man; a woman can be l\'ingénieure)', accepted: ['l\'ingénieure'], forms: ['ingénieur', 'ingénieure', 'ingénieurs', 'ingénieures']),
            new WordData('l\'avocat', cue: 'lawyer (man)', accepted: ['l\'avocate'], forms: ['avocat', 'avocate', 'avocats', 'avocates']),
            new WordData('le cuisinier', cue: 'cook (man)', accepted: ['la cuisinière'], forms: ['cuisinière', 'cuisiniers', 'cuisinières']),
            new WordData('l\'infirmier', cue: 'nurse (man)', accepted: ['l\'infirmière'], forms: ['infirmier', 'infirmière', 'infirmiers', 'infirmières']),
            new WordData('le bureau', cue: 'office (also: desk)', forms: ['bureaux']),
            new WordData('l\'entreprise', cue: 'company (feminine: une entreprise)', forms: ['entreprise', 'entreprises']),
            new WordData('le patron', cue: 'boss (man)', accepted: ['la patronne'], forms: ['patronne', 'patrons', 'patronnes']),
            new WordData('le collègue', cue: 'colleague (man or woman; the word does not change)', accepted: ['la collègue'], forms: ['collègue', 'collègues']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je suis cuisinier.', 'english' => 'I am a cook.'],
            ['text' => 'Je travaille comme cuisinier dans une entreprise.', 'english' => 'I work as a cook in a company.'],
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
            Kit::gap($stage, 'sentences.choose_gap.collegue-avocate', 'Ma collègue ___ avocate.', ['est', 'travaille'], 'est', Kit::form('est', true), 'Your job says what you are, so it goes with être: est. Travaille needs comme or dans after it.', 'choose', 'My colleague is a lawyer.'),
            Kit::gap($stage, 'sentences.choose_gap.travaille-cuisinier', 'Je travaille ___ cuisinier.', ['comme', 'dans'], 'comme', Kit::form('comme', true), 'To say the job you do, use comme: je travaille comme cuisinier. Dans is for the place where you work.', 'choose', 'I work as a cook.'),
            Kit::gap($stage, 'sentences.choose_gap.travaille-bureau', 'Marie travaille ___ un bureau.', ['dans', 'comme'], 'dans', Kit::form('dans', true), 'An office is a place, so you use dans. Comme is for the job you do, as in elle travaille comme professeur.', 'choose', 'Marie works in an office.'),
            Kit::gap($stage, 'sentences.choose_gap.collegue-ingenieur', 'Mon collègue est ___.', ['ingénieur', 'bureau', 'entreprise'], 'ingénieur', Kit::word('l\'ingénieur', 'ingénieur'), 'Ingénieur is a job. Bureau and entreprise are places, not things a person can be.', 'choose', 'My colleague is an engineer.'),
            Kit::gap($stage, 'sentences.choose_gap.une-entreprise', 'Je travaille dans une ___.', ['entreprise', 'avocat', 'professeur'], 'entreprise', Kit::word('l\'entreprise', 'entreprise'), 'Une entreprise is a company, a place to work in. Avocat and professeur are masculine jobs, so they do not fit after une.', 'choose', 'I work in a company.'),
            Kit::gap($stage, 'sentences.choose_gap.anne-infirmiere', 'Anne est ___.', ['infirmière', 'infirmier', 'entreprise'], 'infirmière', Kit::word('l\'infirmier', 'infirmière'), 'Anne is a woman, so the job ends in -ière: infirmière. Infirmier is for a man, and entreprise is a company.', 'choose', 'Anne is a nurse.'),

            Kit::typeGap($stage, 'sentences.type_gap.paul-ingenieur', 'Paul est ___.', 'Paul is an engineer.', 'ingénieur', Kit::word('l\'ingénieur', 'ingénieur')),
            Kit::typeGap($stage, 'sentences.type_gap.marie-avocate', 'Marie est ___.', 'Marie is a lawyer.', 'avocate', Kit::word('l\'avocat', 'avocate')),
            Kit::typeGap($stage, 'sentences.type_gap.luc-est', 'Luc ___ cuisinier.', 'Luc is a cook.', 'est', Kit::form('est'), 'The job after être has no article: Luc est cuisinier, not Luc est un cuisinier.'),
            Kit::typeGap($stage, 'sentences.type_gap.luc-entreprise', 'Luc travaille ___ une entreprise.', 'Luc works in a company.', 'dans', Kit::form('dans'), 'A company is a place where you work, so you use dans.'),
            Kit::typeGap($stage, 'sentences.type_gap.marie-comme', 'Marie travaille ___ professeur.', 'Marie works as a teacher.', 'comme', Kit::form('comme'), 'The job you do after travailler takes comme: elle travaille comme professeur.'),

            Kit::translate($stage, 'sentences.translate.cuisinier', 'I am a cook.', ['Je suis cuisinier.', 'Je suis cuisinière.'], [Kit::word('le cuisinier', 'cuisinier', ['cuisinière']), Kit::form('suis', true)]),
            Kit::translate($stage, 'sentences.translate.travailles-bureau', 'Do you work in an office? (informal you)', ['Tu travailles dans un bureau ?'], [Kit::word('travailler', 'travailles'), Kit::word('le bureau', 'bureau'), Kit::form('travailles dans')]),
            Kit::translate($stage, 'sentences.translate.patron-entreprise', 'My boss works in a company.', ['Mon patron travaille dans une entreprise.', 'Ma patronne travaille dans une entreprise.'], [Kit::word('le patron', 'patron', ['patronne']), Kit::word('l\'entreprise', 'entreprise'), Kit::word('travailler', 'travaille'), Kit::form('travaille dans')]),

            Kit::build($stage, 'sentences.build.marie-professeur', 'Marie is a teacher.', 'Marie est professeur.', ['un'], [Kit::word('le professeur', 'professeur'), Kit::form('est', true)]),
            Kit::build($stage, 'sentences.build.collegue-bureau', 'My colleague (a man) works in an office.', 'Mon collègue travaille dans un bureau.', ['comme'], [Kit::word('le collègue', 'collègue'), Kit::word('le bureau', 'bureau'), Kit::word('travailler', 'travaille'), Kit::form('travaille dans')]),
            Kit::build($stage, 'sentences.build.paul-ingenieur', 'Paul works as an engineer.', 'Paul travaille comme ingénieur.', ['dans'], [Kit::word('l\'ingénieur', 'ingénieur'), Kit::word('travailler', 'travaille'), Kit::form('travaille comme')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.avocat', 'Je suis avocat.', ['I am a lawyer.', 'I am a teacher.', 'I am a cook.', 'You are a lawyer.'], 'I am a lawyer.', [Kit::word('l\'avocat', 'avocat'), Kit::form('suis')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.marie-infirmiere', 'Marie est infirmière.', ['Marie is a nurse.', 'Marie is a teacher.', 'Marie is a cook.', 'Anne is a nurse.'], 'Marie is a nurse.', [Kit::word('l\'infirmier', 'infirmière'), Kit::form('est')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.comme-quoi', 'Tu travailles comme cuisinier ?', ['Do you work as a cook?', 'Do you work as a nurse?', 'Where do you work?', 'I work as a cook.'], 'Do you work as a cook?', [Kit::word('travailler', 'travailles')]),
            Kit::listenType($stage, 'sentences.listen_type.entreprise', 'Paul travaille dans une entreprise.', 'Paul works in a company.', [Kit::word('l\'entreprise', 'entreprise'), Kit::word('travailler', 'travaille'), Kit::form('travaille dans')]),
            Kit::listenType($stage, 'sentences.listen_type.collegue-ingenieur', 'Mon collègue est ingénieur.', 'My colleague (a man) is an engineer.', [Kit::word('le collègue', 'collègue'), Kit::word('l\'ingénieur', 'ingénieur'), Kit::form('est')], homophoneNote: self::EST),
            Kit::listenType($stage, 'sentences.listen_type.professeur-cuisinier', 'Tu es professeur ou cuisinier ?', 'Are you a teacher or a cook? (informal you)', [Kit::word('le professeur', 'professeur', ['professeure']), Kit::word('le cuisinier', 'cuisinier'), Kit::form('es')], alsoAccepted: ['Tu es professeure ou cuisinier ?'], homophoneNote: self::ES_OU),
            Kit::listenType($stage, 'sentences.listen_type.patronne-bureau', 'Ma patronne travaille dans un bureau.', 'My boss (a woman) works in an office.', [Kit::word('le patron', 'patronne'), Kit::word('le bureau', 'bureau'), Kit::form('travaille dans')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ingenieur', 'Je suis ingénieur.', 'I am an engineer.', [Kit::word('l\'ingénieur', 'ingénieur'), Kit::form('suis')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bureau', 'Je travaille au bureau.', 'I work in the office.', [Kit::word('le bureau', 'bureau'), Kit::word('travailler', 'travaille')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.patron-avocat', 'Mon patron est avocat.', 'My boss is a lawyer.', [Kit::word('le patron', 'patron'), Kit::word('l\'avocat', 'avocat'), Kit::form('est')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.collegue-cuisinier', 'Mon collègue travaille comme cuisinier.', 'My colleague works as a cook.', [Kit::word('le collègue', 'collègue'), Kit::word('le cuisinier', 'cuisinier'), Kit::form('travaille comme')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.comme-quoi', 'Tu travailles comme cuisinier ?', 'Do you work as a cook?', [['oui', 'non'], ['travaille', 'suis']], 'Oui, je travaille comme cuisinier.', [Kit::word('le cuisinier', 'cuisinier'), Kit::word('travailler', 'travaille')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.travailles-ou', 'Tu travailles où ?', 'Where do you work?', [['travaille'], ['bureau', 'entreprise']], 'Je travaille dans un bureau.', [Kit::word('travailler', 'travaille')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.es-avocat', 'Tu es avocat ?', 'Are you a lawyer?', [['oui', 'non'], ['suis', 'avocat', 'avocate']], 'Oui, je suis avocat.', [Kit::word('l\'avocat', 'avocat')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.travail', 'Read the conversation about work.', [
                Kit::line('Anne', 'Tu es avocat, Paul ?'),
                Kit::line('Paul', 'Non, je suis ingénieur. Je travaille dans une entreprise avec mon patron.'),
                Kit::line('Paul', 'Et toi, Anne ?'),
                Kit::line('Anne', 'Oui, je suis avocate. Je travaille dans un bureau avec ma collègue.'),
            ], [
                Kit::question('What is Paul\'s job?', ['Engineer', 'Lawyer', 'Teacher'], 'Engineer'),
                Kit::question('Where does Anne work?', ['In an office', 'In a company', 'At home'], 'In an office'),
                Kit::question('Who does Paul work with?', ['His boss', 'His colleague', 'Anne'], 'His boss'),
            ], [Kit::word('travailler', 'travaille'), Kit::word('l\'ingénieur', 'ingénieur'), Kit::word('l\'entreprise', 'entreprise'), Kit::word('le patron', 'patron'), Kit::word('l\'avocat', 'avocate'), Kit::word('le bureau', 'bureau'), Kit::word('le collègue', 'collègue')], 'read', glosses: ['toi' => 'you (informal, after et)']),
            Kit::gap($stage, 'task.choose_gap.paul-ingenieur', 'Paul ___ ingénieur.', ['est', 'travaille'], 'est', Kit::form('est', true), 'With a job after it, the verb is être: Paul est ingénieur, with no un. Travaille needs comme or dans, as in il travaille comme ingénieur.', 'read', 'Paul is an engineer.'),
            Kit::gap($stage, 'task.choose_gap.patronne-bureau', 'Ma patronne travaille ___ un bureau.', ['dans', 'comme'], 'dans', Kit::form('dans', true), 'Un bureau is a place, so you use dans. Comme goes with a job, as in elle travaille comme avocate.', 'read', 'My boss works in an office.'),

            Kit::transform($stage, 'task.transform.marie', 'Now say it about Marie.', 'Je suis professeur.', ['Marie est professeur.', 'Marie est professeure.'], [Kit::word('le professeur', 'professeur', ['professeure']), Kit::form('est')]),
            Kit::transform($stage, 'task.transform.question', 'Now ask a colleague (informal you). Hint: au = à + le, as in au bureau.', 'Je travaille au bureau.', ['Tu travailles au bureau ?'], [Kit::word('le bureau', 'bureau'), Kit::word('travailler', 'travailles')]),
            Kit::transform($stage, 'task.transform.travaille-comme', 'Say that you work as an engineer.', 'Je suis ingénieur.', ['Je travaille comme ingénieur.'], [Kit::word('l\'ingénieur', 'ingénieur'), Kit::word('travailler', 'travaille'), Kit::form('travaille comme')]),
            Kit::writeGuided($stage, 'task.write_guided.cuisinier', 'Say that you are a cook and that you work in a company.', ['suis', 'cuisinier', 'travaille dans', 'entreprise'], 'Je suis cuisinier. Je travaille dans une entreprise.', [
                ['forms' => ['suis'], 'term' => null],
                ['forms' => ['cuisinier', 'cuisinière'], 'term' => 'le cuisinier'],
                ['forms' => ['travaille'], 'term' => 'travailler'],
                ['forms' => ['entreprise'], 'term' => 'l\'entreprise'],
            ], [Kit::word('le cuisinier', 'cuisinier'), Kit::word('travailler', 'travaille'), Kit::word('l\'entreprise', 'entreprise')]),
            Kit::writeGuided($stage, 'task.write_guided.patron-collegue', 'Say that your boss is a lawyer and that your colleague (a woman) is a nurse.', ['mon patron', 'avocat', 'ma collègue', 'infirmière'], 'Mon patron est avocat. Ma collègue est infirmière.', [
                ['forms' => ['patron', 'patronne'], 'term' => 'le patron'],
                ['forms' => ['avocat', 'avocate'], 'term' => 'l\'avocat'],
                ['forms' => ['collègue'], 'term' => 'le collègue'],
                ['forms' => ['infirmière', 'infirmier'], 'term' => 'l\'infirmier'],
            ], [Kit::word('le patron', 'patron'), Kit::word('l\'avocat', 'avocat'), Kit::word('le collègue', 'collègue'), Kit::word('l\'infirmier', 'infirmière')]),
            Kit::build($stage, 'task.build.collegue-professeur', 'My colleague (a man) is a teacher.', 'Mon collègue est professeur.', ['dans', 'travaille'], [Kit::word('le collègue', 'collègue'), Kit::word('le professeur', 'professeur'), Kit::form('est', true)], 'write'),
            Kit::build($stage, 'task.build.travaille-entreprise', 'I work in a company.', 'Je travaille dans une entreprise.', ['comme', 'suis'], [Kit::word('l\'entreprise', 'entreprise'), Kit::word('travailler', 'travaille'), Kit::form('travaille dans')], 'write'),
            Kit::build($stage, 'task.build.patron-cuisinier', 'My boss (a man) works as a cook.', 'Mon patron travaille comme cuisinier.', ['dans', 'avec'], [Kit::word('le patron', 'patron'), Kit::word('le cuisinier', 'cuisinier'), Kit::word('travailler', 'travaille'), Kit::form('travaille comme')], 'write'),
            Kit::translate($stage, 'task.translate.marie-luc', 'Marie is a nurse and Luc is a cook.', ['Marie est infirmière et Luc est cuisinier.'], [Kit::word('l\'infirmier', 'infirmière'), Kit::word('le cuisinier', 'cuisinier'), Kit::form('est')], 'write'),
            Kit::translate($stage, 'task.translate.entreprise-bureau', 'Do you work in a company? No, I work in the office. (informal you; hint: au = à + le, as in au bureau)', ['Tu travailles dans une entreprise ? Non, je travaille au bureau.'], [Kit::word('travailler', 'travailles'), Kit::word('l\'entreprise', 'entreprise'), Kit::word('le bureau', 'bureau'), Kit::form('travailles dans')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.travail', [
                Kit::line('Marie', 'Luc, tu es cuisinier ou professeur ?'),
                Kit::line('Luc', 'Je suis cuisinier. Je travaille avec mon patron.'),
                Kit::line('Luc', 'Tu es infirmière ou avocate, Marie ?'),
                Kit::line('Marie', 'Je suis infirmière. Je travaille avec ma collègue.'),
            ], [
                Kit::question('What is Luc\'s job?', ['Cook', 'Nurse', 'Engineer'], 'Cook'),
                Kit::question('Who does Luc work with?', ['His boss', 'His colleague', 'Marie'], 'His boss'),
                Kit::question('What is Marie\'s job?', ['Nurse', 'Cook', 'Lawyer'], 'Nurse'),
            ], [
                Kit::question('Who asks about the job first?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('Is Marie a nurse?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('travailler', 'travaille'), Kit::word('le cuisinier', 'cuisinier'), Kit::word('le patron', 'patron'), Kit::word('l\'infirmier', 'infirmière'), Kit::word('le collègue', 'collègue')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.professeur-collegue', 'Je suis professeur et je travaille avec mon collègue.', 'I am a teacher and I work with my colleague (a man).', [Kit::word('le professeur', 'professeur', ['professeure']), Kit::word('le collègue', 'collègue'), Kit::word('travailler', 'travaille'), Kit::form('suis')], 'listen', alsoAccepted: ['Je suis professeure et je travaille avec mon collègue.'], homophoneNote: self::ET),
            Kit::listenType($stage, 'task.listen_type.patron-avocat', 'Mon patron est avocat et travaille dans un bureau.', 'My boss (a man) is a lawyer and works in an office.', [Kit::word('le patron', 'patron'), Kit::word('l\'avocat', 'avocat'), Kit::word('le bureau', 'bureau'), Kit::word('travailler', 'travaille'), Kit::form('est')], 'listen', homophoneNote: self::EST_ET),
            Kit::listenType($stage, 'task.listen_type.entreprise-bureau', 'Tu travailles dans une entreprise ou dans un bureau ?', 'Do you work in a company or in an office? (informal you)', [Kit::word('l\'entreprise', 'entreprise'), Kit::word('le bureau', 'bureau'), Kit::word('travailler', 'travailles'), Kit::form('travailles dans')], 'listen', homophoneNote: self::OU),

            Kit::speakAnswer($stage, 'task.speak_answer.bureau', 'Tu travailles dans un bureau ?', 'Do you work in an office?', [['oui', 'non'], ['travaille', 'bureau', 'entreprise']], 'Oui, je travaille dans un bureau.', [Kit::word('le bureau', 'bureau'), Kit::word('travailler', 'travaille')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.patron', 'Ton patron est avocat ?', 'Is your boss a lawyer?', [['oui', 'non'], ['est', 'mon', 'ma']], 'Oui, mon patron est avocat.', [Kit::word('le patron', 'patron')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.infirmier-cuisinier', 'Tu es infirmier ou cuisinier ?', 'Are you a nurse or a cook?', [['suis'], ['infirmier', 'infirmière', 'cuisinier', 'cuisinière']], 'Je suis cuisinier.', [Kit::word('l\'infirmier', 'infirmier'), Kit::word('le cuisinier', 'cuisinier')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.avec-qui', 'Tu travailles avec qui ?', 'Who do you work with?', [['travaille', 'avec'], ['collègue', 'patron', 'patronne']], 'Je travaille avec mon collègue.', [Kit::word('le collègue', 'collègue'), Kit::word('le patron', 'patron')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.collegue-infirmiere', 'Ma collègue est infirmière et travaille ici.', 'My colleague (a woman) is a nurse and works here.', [Kit::word('le collègue', 'collègue'), Kit::word('l\'infirmier', 'infirmière'), Kit::form('est')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cuisinier-patron', 'Je suis cuisinier et je travaille avec mon patron.', 'I am a cook and I work with my boss.', [Kit::word('le cuisinier', 'cuisinier'), Kit::word('le patron', 'patron'), Kit::word('travailler', 'travaille'), Kit::form('suis')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.collegue-ingenieur', 'Your colleague is an engineer. (informal you)', ['Ton collègue est ingénieur.', 'Ta collègue est ingénieure.', 'Ta collègue est ingénieur.'], [Kit::word('le collègue', 'collègue'), Kit::word('l\'ingénieur', 'ingénieur', ['ingénieure']), Kit::form('est', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.anne-professeur', 'Anne works as a teacher.', ['Anne travaille comme professeur.', 'Anne travaille comme professeure.'], [Kit::word('travailler', 'travaille'), Kit::word('le professeur', 'professeur', ['professeure']), Kit::form('travaille comme', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ou-travailles', 'Where do you work? I work in an office. (informal you)', ['Tu travailles où ? Je travaille dans un bureau.'], [Kit::word('travailler', 'travailles'), Kit::word('le bureau', 'bureau'), Kit::form('travaille dans')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.trois-metiers', 'Luc is a cook, Marie is a nurse and Anne is a lawyer.', ['Luc est cuisinier, Marie est infirmière et Anne est avocate.'], [Kit::word('le cuisinier', 'cuisinier'), Kit::word('l\'infirmier', 'infirmière'), Kit::word('l\'avocat', 'avocate'), Kit::form('est')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marie-entreprise', 'Marie travaille dans une ___.', 'Marie works in a company.', 'entreprise', Kit::word('l\'entreprise', 'entreprise'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.collegue-infirmiere', 'Ma collègue est ___.', 'My colleague (a woman) is a nurse.', 'infirmière', Kit::word('l\'infirmier', 'infirmière'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.patronne-avocate', 'Ma patronne est avocate et travaille dans un bureau.', 'My boss (a woman) is a lawyer and works in an office.', [Kit::word('le patron', 'patronne'), Kit::word('l\'avocat', 'avocate'), Kit::word('le bureau', 'bureau'), Kit::word('travailler', 'travaille'), Kit::form('est')], 'dictation', $set, homophoneNote: self::EST_ET),
            Kit::listenType($stage, 'check.a.listen_type.es', 'Tu es professeur, ingénieur ou cuisinier ?', 'Are you a teacher, an engineer or a cook? (informal you)', [Kit::word('le professeur', 'professeur', ['professeure']), Kit::word('l\'ingénieur', 'ingénieur', ['ingénieure']), Kit::word('le cuisinier', 'cuisinier'), Kit::form('es')], 'dictation', $set, ['Tu es professeure, ingénieur ou cuisinier ?', 'Tu es professeur, ingénieure ou cuisinier ?', 'Tu es professeure, ingénieure ou cuisinier ?'], homophoneNote: self::ES_OU),
            Kit::listenType($stage, 'check.a.listen_type.patron-collegue', 'Je travaille avec mon patron et mon collègue.', 'I work with my boss and my colleague (both men).', [Kit::word('travailler', 'travaille'), Kit::word('le patron', 'patron'), Kit::word('le collègue', 'collègue')], 'dictation', $set, homophoneNote: self::ET),
            Kit::listenPassage($stage, 'check.a.listen_passage.bureau', [
                Kit::line('Paul', 'Anne, tu travailles où ?'),
                Kit::line('Anne', 'Je travaille dans un bureau. Je suis avocate.'),
                Kit::line('Anne', 'Tu es professeur, Paul ?'),
                Kit::line('Paul', 'Non, je suis cuisinier. Je travaille ici avec mon collègue.'),
            ], [
                Kit::question('Where does Anne work?', ['In an office', 'In a company', 'Here'], 'In an office'),
                Kit::question('What is Paul\'s job?', ['Cook', 'Lawyer', 'Engineer'], 'Cook'),
                Kit::question('Who works with Paul?', ['His colleague', 'His boss', 'Anne'], 'His colleague'),
            ], [
                Kit::question('Who asks the first question?', ['Paul', 'Anne', 'Nobody'], 'Paul'),
                Kit::question('Is Anne a lawyer?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('le bureau', 'bureau'), Kit::word('l\'avocat', 'avocate'), Kit::word('le cuisinier', 'cuisinier'), Kit::word('le collègue', 'collègue'), Kit::word('travailler', 'travaille')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ingenieur', 'Read the conversation.', [
                Kit::line('Marie', 'Luc, tu es ingénieur ?'),
                Kit::line('Luc', 'Non, je suis professeur. Mon patron est ingénieur.'),
                Kit::line('Luc', 'Marie, tu es avocate ?'),
                Kit::line('Marie', 'Non, je suis infirmière.'),
            ], [
                Kit::question('What is Luc\'s job?', ['Teacher', 'Engineer', 'Nurse'], 'Teacher'),
                Kit::question('Who is the engineer?', ['Luc\'s boss', 'Luc', 'Marie'], 'Luc\'s boss'),
            ], [Kit::word('l\'ingénieur', 'ingénieur'), Kit::word('le professeur', 'professeur'), Kit::word('le patron', 'patron'), Kit::word('l\'infirmier', 'infirmière')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.professeur-ingenieur', 'Tu es professeur ou ingénieur ?', 'Are you a teacher or an engineer?', [['suis'], ['professeur', 'professeure', 'ingénieur', 'ingénieure']], 'Je suis professeur.', [Kit::word('le professeur', 'professeur'), Kit::word('l\'ingénieur', 'ingénieur')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.entreprise', 'Tu travailles dans une entreprise ?', 'Do you work in a company?', [['oui', 'non'], ['travaille', 'entreprise', 'bureau']], 'Oui, je travaille dans une entreprise.', [Kit::word('l\'entreprise', 'entreprise'), Kit::word('travailler', 'travaille')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.collegue', 'Ton collègue est professeur ?', 'Is your colleague a teacher?', [['oui', 'non'], ['est', 'mon', 'ma']], 'Oui, mon collègue est professeur.', [Kit::word('le collègue', 'collègue'), Kit::word('le professeur', 'professeur')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.marie-avocate', 'Marie is a lawyer and works in an office.', ['Marie est avocate et travaille dans un bureau.'], [Kit::word('l\'avocat', 'avocate'), Kit::word('travailler', 'travaille'), Kit::word('le bureau', 'bureau'), Kit::form('est')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.paul-luc', 'Paul and Luc are engineers and work in a company.', ['Paul et Luc sont ingénieurs et travaillent dans une entreprise.'], [Kit::word('l\'ingénieur', 'ingénieurs'), Kit::word('travailler', 'travaillent'), Kit::word('l\'entreprise', 'entreprise'), Kit::form('sont')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.anne-luc', 'Anne is my colleague and Luc is my boss.', ['Anne est ma collègue et Luc est mon patron.'], [Kit::word('le collègue', 'collègue'), Kit::word('le patron', 'patron'), Kit::form('est', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.travaille-comme', 'I work as a cook and Marie works as a nurse.', ['Je travaille comme cuisinier et Marie travaille comme infirmière.', 'Je travaille comme cuisinière et Marie travaille comme infirmière.'], [Kit::word('le cuisinier', 'cuisinier', ['cuisinière']), Kit::word('l\'infirmier', 'infirmière'), Kit::word('travailler', 'travaille'), Kit::form('travaille comme', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.patronne-avocate', 'Ma patronne est ___.', 'My boss (a woman) is a lawyer.', 'avocate', Kit::word('l\'avocat', 'avocate'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.anne-entreprise', 'Anne travaille dans une ___.', 'Anne works in a company.', 'entreprise', Kit::word('l\'entreprise', 'entreprise'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.professeur-patron', 'Je suis professeur et mon patron est ingénieur.', 'I am a teacher (man) and my boss (a man) is an engineer.', [Kit::word('le professeur', 'professeur'), Kit::word('le patron', 'patron'), Kit::word('l\'ingénieur', 'ingénieur'), Kit::form('suis')], 'dictation', $set, homophoneNote: self::EST_ET),
            Kit::listenType($stage, 'check.b.listen_type.collegue-bureau', 'Ma collègue travaille dans un bureau.', 'My colleague (a woman) works in an office.', [Kit::word('le collègue', 'collègue'), Kit::word('travailler', 'travaille'), Kit::word('le bureau', 'bureau')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.trois-metiers', 'Anne est professeur, Luc est cuisinier et Marie est infirmière.', 'Anne is a teacher, Luc is a cook and Marie is a nurse.', [Kit::word('le professeur', 'professeur', ['professeure']), Kit::word('le cuisinier', 'cuisinier'), Kit::word('l\'infirmier', 'infirmière'), Kit::form('est')], 'dictation', $set, ['Anne est professeure, Luc est cuisinier et Marie est infirmière.'], homophoneNote: self::EST_ET),
        ];
    }
}
