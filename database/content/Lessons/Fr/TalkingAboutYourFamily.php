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

final class TalkingAboutYourFamily implements UnitContent
{
    private const EST = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const SA_EST = 'Sa (his or her) and ça (this, it) sound identical, so the sentence decides: here sa is the possessive. Est (is, the verb) and et (and) sound very close, and context tells you which is which.';

    private const A = 'A (has, the verb) and à (to, at) sound identical, and the accent and the sentence tell you which is which: here a is the verb.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'talking-about-your-family';
    }

    public function words(): array
    {
        return [
            new WordData('la famille', cue: 'family'),
            new WordData('le père', cue: 'father'),
            new WordData('la mère', cue: 'mother'),
            new WordData('le frère', cue: 'brother'),
            new WordData('la sœur', cue: 'sister', accepted: ['la soeur']),
            new WordData('le fils', cue: 'son'),
            new WordData('les grands-parents', cue: 'grandparents'),
            new WordData('marié', cue: 'married (masculine)', forms: ['mariée', 'mariés', 'mariées']),
            new WordData('célibataire', cue: 'single, not married (the same for a man and a woman)', forms: ['célibataires']),
            new WordData('aîné', cue: 'older, eldest (of the siblings; masculine)', forms: ['aînée', 'aînés', 'aînées']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mon frère est marié.', 'english' => 'My brother is married.'],
            ['text' => 'Ma sœur est mariée.', 'english' => 'My sister is married.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-05', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-05', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-05', 'Released on the owner\'s instruction on 2026-10-05, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.mes-freres', '___ frères aînés sont ici.', ['Mes', 'Mon', 'Ma'], 'Mes', Kit::form('mes', true), 'Frères is plural, so the possessive is plural too: mes.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.ma-mere', '___ mère est ici.', ['Ma', 'Mon', 'Mes'], 'Ma', Kit::form('ma'), 'Mère is feminine and singular, so the possessive is ma.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tes-soeurs', '___ sœurs sont ici.', ['Tes', 'Ton', 'Ta'], 'Tes', Kit::form('tes', true), 'A plural noun needs the plural possessive: tes. Ton and ta go with a singular noun.', 'choose', glosses: ['sœurs' => 'sisters']),
            Kit::gap($stage, 'sentences.choose_gap.soeur-mariee', 'Ma sœur est ___.', ['mariée', 'marié'], 'mariée', Kit::word('marié', 'mariée'), 'Sœur is feminine, so the adjective takes an extra -e.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.luc-aine', 'Luc est mon frère ___.', ['aîné', 'aînée'], 'aîné', Kit::word('aîné'), 'Frère is masculine, so aîné has no extra -e.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.anne-soeur', 'Anne est ma ___.', ['sœur', 'frère'], 'sœur', Kit::word('la sœur', 'sœur'), 'Anne is a woman, so the feminine noun: la sœur.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.mon-amie', 'Anne est ___ amie.', 'Anne is my friend.', 'mon', Kit::form('mon', true), 'Amie is feminine, but it starts with a vowel, so ma becomes mon: mon amie, never ma amie.', glosses: ['amie' => 'friend (female)']),
            Kit::typeGap($stage, 'sentences.type_gap.mes-grands-parents', '___ grands-parents sont ici.', 'My grandparents are here.', 'Mes', Kit::form('mes', true), 'Grands-parents is plural, so the possessive is plural too: mes.'),
            Kit::typeGap($stage, 'sentences.type_gap.ton-frere', 'Paul est ___ frère.', 'Paul is your brother (informal you).', 'ton', Kit::form('ton'), 'You, to a friend, takes ton before a masculine noun. Votre is the formal one.'),
            Kit::typeGap($stage, 'sentences.type_gap.famille', 'La ___ est ici.', 'The family is here.', 'famille', Kit::word('la famille', 'famille')),
            Kit::typeGap($stage, 'sentences.type_gap.ses-freres', 'Marie et ___ frères sont ici.', 'Marie and her brothers are here.', 'ses', Kit::form('ses', true), 'Son, sa and ses agree with the thing owned, not with the owner: more than one brother, so ses (his or her).', glosses: ['frères' => 'brothers']),

            Kit::translate($stage, 'sentences.translate.pere', 'My father is with my grandparents.', ['Mon père est avec mes grands-parents.'], [Kit::word('le père', 'père'), Kit::word('les grands-parents', 'grands-parents'), Kit::form('mon')]),
            Kit::translate($stage, 'sentences.translate.soeurs', 'My sisters are here.', ['Mes sœurs sont ici.'], [Kit::word('la sœur', 'sœurs'), Kit::form('mes', true)]),
            Kit::translate($stage, 'sentences.translate.mere', 'Your mother is married (informal you).', ['Ta mère est mariée.'], [Kit::word('la mère', 'mère'), Kit::word('marié', 'mariée'), Kit::form('ta')]),
            Kit::build($stage, 'sentences.build.fils', 'His son is single.', 'Son fils est célibataire.', ['sa'], [Kit::word('le fils', 'fils'), Kit::word('célibataire'), Kit::form('son')]),
            Kit::build($stage, 'sentences.build.freres', 'My brothers are here.', 'Mes frères sont ici.', ['mon'], [Kit::word('le frère', 'frères'), Kit::form('mes', true)]),
            Kit::build($stage, 'sentences.build.frere-aine', 'Your older brother is here (informal you).', 'Ton frère aîné est ici.', ['tes'], [Kit::word('le frère', 'frère'), Kit::word('aîné'), Kit::form('ton')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.mere', 'Ma mère est ici.', ['My mother is here.', 'My father is here.', 'My sister is here.', 'My family is here.'], 'My mother is here.', [Kit::word('la mère', 'mère'), Kit::form('ma')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.freres', 'Mes frères aînés sont ici.', ['My older brothers are here.', 'My older brother is here.', 'My older sisters are here.', 'My brothers are here.'], 'My older brothers are here.', [Kit::word('le frère', 'frères'), Kit::word('aîné', 'aînés'), Kit::form('mes', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.fils', 'Son fils est marié.', ['His or her son is married.', 'His or her son is single.', 'His or her sons are married.', 'His or her mother is married.'], 'His or her son is married.', [Kit::word('le fils', 'fils'), Kit::word('marié'), Kit::form('son')]),
            Kit::listenType($stage, 'sentences.listen_type.soeur', 'Ma sœur est célibataire.', 'My sister is single.', [Kit::word('la sœur', 'sœur'), Kit::word('célibataire'), Kit::form('ma')], homophoneNote: self::EST),
            Kit::listenType($stage, 'sentences.listen_type.grands-parents', 'Tes grands-parents sont ici.', 'Your grandparents are here (informal you).', [Kit::word('les grands-parents', 'grands-parents'), Kit::form('tes', true)], homophoneNote: 'Sont (are) and son (his or her) sound identical, and the sentence tells you which is which.'),
            Kit::listenType($stage, 'sentences.listen_type.famille', 'La famille est ici.', 'The family is here.', [Kit::word('la famille')], homophoneNote: self::EST),
            Kit::listenType($stage, 'sentences.listen_type.pere-mere', 'Mon père et ma mère sont ici.', 'My father and my mother are here.', [Kit::word('le père', 'père'), Kit::word('la mère', 'mère'), Kit::form('mon')], homophoneNote: 'Et (and) sounds very close to est (is, the verb), and the sentence tells you which is which: here et joins father and mother. Sont (are) and son (his or her) sound identical, and the sentence tells you which is which.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.frere', 'Mon frère est célibataire.', 'My brother is single.', [Kit::word('le frère', 'frère'), Kit::word('célibataire'), Kit::form('mon')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.soeur', 'Sa sœur est mariée.', 'His or her sister is married.', [Kit::word('la sœur', 'sœur'), Kit::word('marié', 'mariée'), Kit::form('sa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.soeur-de-paul', 'Anne est la sœur de Paul.', "Anne is Paul's sister.", [Kit::word('la sœur')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.soeur-ainee', 'Ma sœur aînée est ici.', 'My older sister is here.', [Kit::word('la sœur', 'sœur'), Kit::word('aîné', 'aînée'), Kit::form('ma')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.qui-marie', 'Qui est Marie ?', 'Who is Marie?', [['ma', 'mon', 'ta', 'ton', 'sa', 'son', 'est', "c'est", 'marie', 'la', 'elle', 'il'], ['sœur', 'mère', 'famille', 'frère', 'père']], 'Marie est ma sœur.', [Kit::word('la sœur', 'sœur'), Kit::form('ma')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.frere-marie', 'Ton frère est marié ?', 'Is your brother married?', [['oui', 'non', 'est', 'mon', 'marié', 'célibataire', 'frère']], 'Oui, mon frère est marié.', [Kit::word('le frère', 'frère'), Kit::word('marié'), Kit::form('mon')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ou-mere', 'Où est ta mère ?', 'Where is your mother?', [['ma', 'mère', 'est', 'ici', 'là']], 'Ma mère est ici.', [Kit::word('la mère', 'mère'), Kit::form('ma')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.famille', "Read the conversation about Anne's family.", [
                Kit::line('Paul', 'Tu as des frères, Anne ?'),
                Kit::line('Anne', 'Oui, un frère et une sœur. Ma sœur est célibataire.'),
                Kit::line('Paul', 'Et ton frère ?'),
                Kit::line('Anne', 'Mon frère aîné est marié. Son fils est ici.'),
                Kit::line('Paul', 'Et ton père et ta mère ?'),
                Kit::line('Anne', 'Ma famille est ici avec mes grands-parents. Mon père et ma mère sont ici aussi.'),
            ], [
                Kit::question('Who is single?', ["Anne's sister", "Anne's brother", "Anne's mother"], "Anne's sister"),
                Kit::question('Who is described as older and married?', ['Her sister', 'Her brother', 'Her father'], 'Her brother'),
                Kit::question('Who is here with Anne?', ['Only her brother', 'Her grandparents, her father and her mother', 'Nobody'], 'Her grandparents, her father and her mother'),
            ], [Kit::word('la famille'), Kit::word('le père'), Kit::word('la mère'), Kit::word('le frère'), Kit::word('la sœur'), Kit::word('le fils'), Kit::word('les grands-parents'), Kit::word('marié'), Kit::word('célibataire'), Kit::word('aîné')]),
            Kit::gap($stage, 'task.choose_gap.pere', 'Paul est le ___ de Marie.', ['père', 'mère'], 'père', Kit::word('le père', 'père'), 'Le goes with a masculine noun, so the father.', 'read'),
            Kit::gap($stage, 'task.choose_gap.mere', 'Anne est la ___ de Paul.', ['mère', 'père'], 'mère', Kit::word('la mère', 'mère'), 'La goes with a feminine noun, so the mother.', 'read'),

            Kit::transform($stage, 'task.transform.freres', 'Make it plural.', 'Son frère est marié.', ['Ses frères sont mariés.'], [Kit::word('le frère', 'frères'), Kit::word('marié', 'mariés'), Kit::form('ses', true)]),
            Kit::transform($stage, 'task.transform.fils', 'Make it plural.', 'Ton fils est célibataire.', ['Tes fils sont célibataires.'], [Kit::word('le fils', 'fils'), Kit::word('célibataire', 'célibataires'), Kit::form('tes', true)]),
            Kit::transform($stage, 'task.transform.sa', 'Change my to his or her.', 'Ma sœur est mariée.', ['Sa sœur est mariée.'], [Kit::word('la sœur', 'sœur'), Kit::word('marié', 'mariée'), Kit::form('sa')]),
            Kit::writeGuided($stage, 'task.write_guided.soeur', 'Say that Anne is your sister and that she is single.', ['sœur', 'célibataire'], 'Anne est ma sœur. Elle est célibataire.', [
                ['forms' => ['sœur', 'soeur'], 'term' => 'la sœur'],
                ['forms' => ['célibataire'], 'term' => 'célibataire'],
            ], [Kit::word('la sœur'), Kit::word('célibataire')]),
            Kit::writeGuided($stage, 'task.write_guided.frere', 'Say that your older brother is married.', ['frère', 'aîné', 'marié'], 'Mon frère aîné est marié.', [
                ['forms' => ['frère'], 'term' => 'le frère'],
                ['forms' => ['aîné'], 'term' => 'aîné'],
                ['forms' => ['marié'], 'term' => 'marié'],
            ], [Kit::word('le frère'), Kit::word('aîné'), Kit::word('marié')]),
            Kit::build($stage, 'task.build.amie-frere', 'Anne is my friend. Luc is my brother.', 'Anne est mon amie. Luc est mon frère.', ['ma', 'mes'], [Kit::word('le frère', 'frère'), Kit::form('mon', true)], 'write', ['amie' => 'friend (female)']),
            Kit::build($stage, 'task.build.freres-pere', 'My brothers are here with my father.', 'Mes frères sont ici avec mon père.', ['ma', 'ton'], [Kit::word('le frère', 'frères'), Kit::word('le père', 'père'), Kit::form('mes', true)]),
            Kit::build($stage, 'task.build.frere-aine', 'Her older brother is married.', 'Son frère aîné est marié.', ['sa', 'ses'], [Kit::word('le frère', 'frère'), Kit::word('aîné'), Kit::word('marié'), Kit::form('son', true)]),
            Kit::translate($stage, 'task.translate.paul-anne', 'Paul is my brother and Anne is my sister.', ['Paul est mon frère et Anne est ma sœur.'], [Kit::word('le frère', 'frère'), Kit::word('la sœur', 'sœur'), Kit::form('ma')]),
            Kit::translate($stage, 'task.translate.mariee', 'My mother is married and my father too.', ['Ma mère est mariée et mon père aussi.', 'Ma mère est mariée et mon père est marié aussi.', 'Ma mère est mariée et mon père est aussi marié.'], [Kit::word('la mère', 'mère'), Kit::word('le père', 'père'), Kit::word('marié', 'mariée'), Kit::form('mon')]),

            Kit::listenPassage($stage, 'task.listen_passage.frere', [
                Kit::line('Luc', 'Ton frère est marié, Anne ?'),
                Kit::line('Anne', 'Oui, et son fils est ici.'),
                Kit::line('Luc', 'Et ta sœur ?'),
                Kit::line('Anne', 'Ma sœur est célibataire. Mes grands-parents sont avec elle.'),
                Kit::line('Luc', 'Très bien. Ma famille est ici aussi.'),
            ], [
                Kit::question("Is Anne's brother married?", ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who is single?', ["Anne's brother", "Anne's sister", "Anne's mother"], "Anne's sister"),
                Kit::question("Who is with Anne's sister?", ['Her grandparents', 'Her father', 'Luc'], 'Her grandparents'),
            ], [
                Kit::question("Where is the son of Anne's brother?", ['Here', 'At home', 'The conversation does not say.'], 'Here'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Whose family is mentioned at the end?', ["Luc's", "Anne's", "Paul's"], "Luc's"),
            ], [Kit::word('le frère'), Kit::word('marié'), Kit::word('le fils'), Kit::word('la sœur'), Kit::word('célibataire'), Kit::word('les grands-parents'), Kit::word('la famille')]),
            Kit::listenType($stage, 'task.listen_type.frere-aine', 'Mon frère aîné est marié.', 'My older brother is married.', [Kit::word('le frère', 'frère'), Kit::word('aîné'), Kit::word('marié'), Kit::form('mon')], homophoneNote: self::EST),
            Kit::listenType($stage, 'task.listen_type.famille', 'La famille de Paul est ici.', "Paul's family is here.", [Kit::word('la famille')], homophoneNote: self::EST),
            Kit::listenType($stage, 'task.listen_type.pere-mere', 'Ton père et ta mère sont ici.', 'Your father and your mother are here (informal you).', [Kit::word('le père', 'père'), Kit::word('la mère', 'mère'), Kit::form('ta')], homophoneNote: 'Et (and) sounds very close to est (is, the verb), and the sentence tells you which is which: here et joins father and mother. Sont (are) and son (his or her) sound identical, and the sentence tells you which is which.'),

            Kit::speakAnswer($stage, 'task.speak_answer.mere', 'Ta mère est mariée ?', 'Is your mother married?', [['oui', 'non', 'est', 'ma', 'mariée', 'célibataire']], 'Oui, ma mère est mariée.', [Kit::word('la mère', 'mère'), Kit::word('marié', 'mariée'), Kit::form('ma')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.famille', 'Ta famille est ici ?', 'Is your family here?', [['oui', 'non', 'ma', 'famille', 'ici', 'est', 'mon', 'père', 'mère', 'grands-parents', 'frère', 'sœur', 'sont', 'là']], 'Oui, ma famille est ici.', [Kit::word('la famille', 'famille'), Kit::form('ma')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.frere-aine', 'Ton frère aîné est ici ?', 'Is your older brother here?', [['oui', 'non', 'mon', 'est', 'ici', 'là', 'frère', 'aîné']], 'Oui, mon frère aîné est ici.', [Kit::word('le frère', 'frère'), Kit::word('aîné'), Kit::form('mon')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.grands-parents', 'Où sont tes grands-parents ?', 'Where are your grandparents?', [['mes', 'grands-parents', 'sont', 'ici', 'là']], 'Mes grands-parents sont là.', [Kit::word('les grands-parents', 'grands-parents'), Kit::form('mes', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.pere', 'Où est ton père ?', 'Where is your father?', [Kit::word('le père', 'père'), Kit::form('ton')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.fils', 'Son fils est célibataire.', 'His or her son is single.', [Kit::word('le fils', 'fils'), Kit::word('célibataire'), Kit::form('son')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.soeur', 'Your older sister is here (informal you).', ['Ta sœur aînée est ici.'], [Kit::word('la sœur', 'sœur'), Kit::word('aîné', 'aînée'), Kit::form('ta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pere', 'Her father is married.', ['Son père est marié.'], [Kit::word('le père', 'père'), Kit::word('marié'), Kit::form('son', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.freres', 'My brothers are single.', ['Mes frères sont célibataires.'], [Kit::word('le frère', 'frères'), Kit::word('célibataire', 'célibataires'), Kit::form('mes', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fils', 'My son is with my grandparents.', ['Mon fils est avec mes grands-parents.'], [Kit::word('le fils', 'fils'), Kit::word('les grands-parents', 'grands-parents')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ses-grands-parents', 'Marie et ___ grands-parents sont ici.', 'Marie and her grandparents are here.', 'ses', Kit::form('ses', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ton-frere', 'Marie, ___ frère est ici ?', 'Marie, is your brother here (informal you)?', 'ton', Kit::form('ton'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.mere', 'Ma mère est célibataire.', 'My mother is single.', [Kit::word('la mère', 'mère'), Kit::word('célibataire'), Kit::form('ma')], 'dictation', $set, homophoneNote: self::EST),
            Kit::listenType($stage, 'check.a.listen_type.famille', 'Luc est avec sa famille.', 'Luc is with his family.', [Kit::word('la famille', 'famille')], 'dictation', $set, homophoneNote: self::SA_EST),
            Kit::listenType($stage, 'check.a.listen_type.mere-paul', 'La mère de Paul est ici.', "Paul's mother is here.", [Kit::word('la mère', 'mère')], 'dictation', $set, homophoneNote: self::EST),
            Kit::listenPassage($stage, 'check.a.listen_passage.soeur', [
                Kit::line('Anne', 'Ta sœur est mariée, Paul ?'),
                Kit::line('Paul', 'Non, elle est célibataire. Mon frère est marié.'),
                Kit::line('Anne', 'Et tes grands-parents ?'),
                Kit::line('Paul', 'Mes grands-parents sont ici aussi.'),
            ], [
                Kit::question("Is Paul's sister married?", ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Who is married?', ["Paul's sister", "Paul's brother", "Anne's brother"], "Paul's brother"),
                Kit::question("Where are Paul's grandparents?", ['Here', 'At home', 'The conversation does not say.'], 'Here'),
            ], [
                Kit::question('Who asks the questions?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What does Anne ask about last?', ['The grandparents', 'The sister', 'The brother'], 'The grandparents'),
            ], [Kit::word('la sœur'), Kit::word('marié'), Kit::word('célibataire'), Kit::word('le frère'), Kit::word('les grands-parents')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.mere', 'Read the conversation.', [
                Kit::line('Luc', 'Anne, ta mère est ici ?'),
                Kit::line('Anne', 'Oui, et mon père aussi.'),
                Kit::line('Luc', 'Et ton fils ?'),
                Kit::line('Anne', 'Non, mon fils est avec mes grands-parents.'),
            ], [
                Kit::question('Who is here?', ['Her mother and her father', 'Her mother and her son', 'Only her son'], 'Her mother and her father'),
                Kit::question("Who is with Anne's son?", ['Her grandparents', 'Her father', 'Her brother'], 'Her grandparents'),
            ], [Kit::word('la mère'), Kit::word('le père'), Kit::word('le fils'), Kit::word('les grands-parents')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.soeur', 'Anne est ta sœur ?', 'Is Anne your sister?', [['oui', 'non', 'est', 'anne', 'ma', 'sœur', "c'est"]], 'Oui, Anne est ma sœur.', [Kit::word('la sœur', 'sœur')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.celibataire', 'Ton frère est célibataire ?', 'Is your brother single?', [['oui', 'non', 'est', 'mon', 'célibataire', 'marié']], 'Oui, mon frère est célibataire.', [Kit::word('célibataire'), Kit::word('le frère', 'frère')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aine', 'Ta sœur aînée est ici ?', 'Is your older sister here?', [['oui', 'non', 'est', 'ma', 'ici', 'là', 'sœur', 'aînée']], 'Oui, ma sœur aînée est ici.', [Kit::word('aîné', 'aînée')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.mere-pere', 'My mother is here with my father.', ['Ma mère est ici avec mon père.'], [Kit::word('la mère', 'mère'), Kit::word('le père', 'père'), Kit::form('mon')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.soeur', 'Her sister is single.', ['Sa sœur est célibataire.'], [Kit::word('la sœur', 'sœur'), Kit::word('célibataire'), Kit::form('sa', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.fils', 'My sons are single.', ['Mes fils sont célibataires.'], [Kit::word('le fils', 'fils'), Kit::word('célibataire', 'célibataires'), Kit::form('mes', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.grands-parents', 'My grandparents are married.', ['Mes grands-parents sont mariés.'], [Kit::word('les grands-parents', 'grands-parents'), Kit::word('marié', 'mariés'), Kit::form('mes', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.tes-fils', '___ fils sont ici.', 'Your sons are here (informal you).', 'Tes', Kit::form('tes', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.son-pere', 'Paul est avec ___ père.', 'Paul is with his father.', 'son', Kit::form('son'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.soeur', 'Ma sœur aînée est avec mon frère.', 'My older sister is with my brother.', [Kit::word('la sœur', 'sœur'), Kit::word('aîné', 'aînée'), Kit::word('le frère', 'frère')], 'dictation', $set, homophoneNote: self::EST),
            Kit::listenType($stage, 'check.b.listen_type.famille', 'Anne a une famille ici.', 'Anne has a family here.', [Kit::word('la famille', 'famille')], 'dictation', $set, homophoneNote: self::A),
            Kit::listenType($stage, 'check.b.listen_type.fils', 'Mon frère aîné a un fils.', 'My older brother has a son.', [Kit::word('le frère', 'frère'), Kit::word('le fils', 'fils'), Kit::word('aîné')], 'dictation', $set, homophoneNote: self::A),
        ];
    }
}
