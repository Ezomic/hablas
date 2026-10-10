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

final class DescribingPeople implements UnitContent
{
    private const EST = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const ET = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et joins two things.';

    private const A = 'The a of il a and elle a (has) has no accent, unlike à (to, at): here a is the verb avoir.';

    private const SONT = 'Sont (are) sounds like son (his) and ont (have); here sont is the verb être.';

    private const MAIS = 'Mais (but) and mes (my) sound very close, and the sentence tells you which is which: here it means but.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'describing-people';
    }

    public function words(): array
    {
        return [
            new WordData('grand', cue: 'tall (masculine singular; the other forms are grande, grands, grandes)', forms: ['grande', 'grands', 'grandes']),
            new WordData('petit', cue: 'short, not tall; small (masculine singular; the other forms are petite, petits, petites)', forms: ['petite', 'petits', 'petites']),
            new WordData('sympathique', cue: 'nice, friendly (the same for men and women; the plural is sympathiques)', forms: ['sympathiques'], note: 'Sympathique means nice, friendly. It is not "sympathetic"; Dutch sympathiek is closer.'),
            new WordData('jeune', cue: 'young (the same for men and women; the plural is jeunes)', forms: ['jeunes']),
            new WordData('blond', cue: 'blond (masculine singular; the other forms are blonde, blonds, blondes)', forms: ['blonde', 'blonds', 'blondes']),
            new WordData('brun', cue: 'dark-haired, brown (masculine singular; the other forms are brune, bruns, brunes)', forms: ['brune', 'bruns', 'brunes']),
            new WordData('beau', cue: 'good-looking, handsome (masculine singular; the other forms are belle, beaux, belles)', forms: ['belle', 'beaux', 'belles']),
            new WordData('les cheveux', cue: 'hair (plural in French, one single hair is un cheveu)', note: 'Les cheveux is masculine and plural: les cheveux blonds.'),
            new WordData('les yeux', cue: 'eyes (the singular is un œil)', note: 'Les yeux is the irregular plural of un œil. It is masculine and plural: les yeux bleus.'),
            new WordData('la personne', cue: 'person', forms: ['personnes'], note: 'La personne is always feminine, also for a man: Paul est une personne sympathique.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Anne est grande et elle a les cheveux blonds.', 'english' => 'Anne is tall and has blond hair.'],
            ['text' => 'Paul et Luc sont sympathiques.', 'english' => 'Paul and Luc are nice.'],
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
            Kit::gap($stage, 'sentences.choose_gap.anne-grande', 'Anne est ___.', ['grande', 'grand', 'grandes'], 'grande', Kit::word('grand', 'grande'), 'Anne is one woman, so the adjective takes an -e and stays singular: grande.', 'choose', 'Anne is tall.'),
            Kit::gap($stage, 'sentences.choose_gap.luc-petit', 'Luc est ___.', ['petit', 'petite', 'petits'], 'petit', Kit::word('petit'), 'Luc is one man, so the adjective has no ending: petit.', 'choose', 'Luc is short.'),
            Kit::gap($stage, 'sentences.choose_gap.paul-cheveux', 'Paul ___ les cheveux bruns.', ['a', 'est'], 'a', Kit::form('a', true), 'You have hair and eyes, so French uses avoir: a. Est says what someone is, not what they have.', 'choose', 'Paul has dark hair.'),
            Kit::gap($stage, 'sentences.choose_gap.anne-yeux', 'Anne a les ___ bleus.', ['yeux', 'personne', 'belle'], 'yeux', Kit::word('les yeux', 'yeux'), 'Les is plural and blue goes with eyes: les yeux, the irregular plural of un œil. Personne and belle are singular.', 'choose', 'Anne has blue eyes.', ['bleus' => 'blue']),
            Kit::gap($stage, 'sentences.choose_gap.marie-personne', 'Marie est une ___ sympathique.', ['personne', 'cheveux', 'yeux'], 'personne', Kit::word('la personne', 'personne'), 'Une goes with a feminine singular noun, and a person is une personne. Cheveux and yeux are plural.', 'choose', 'Marie is a nice person.'),
            Kit::gap($stage, 'sentences.choose_gap.paul-luc-jeunes', 'Paul et Luc ___ jeunes.', ['sont', 'est'], 'sont', Kit::form('sont', true), 'Two people need the plural of être: sont. Est is for one person.', 'choose', 'Paul and Luc are young.'),

            Kit::typeGap($stage, 'sentences.type_gap.luc-grand', 'Luc ___ grand.', 'Luc is tall.', 'est', Kit::form('est'), 'Height is something you are, so you use être: est.'),
            Kit::typeGap($stage, 'sentences.type_gap.marie-cheveux', 'Marie ___ les cheveux blonds.', 'Marie has blond hair.', 'a', Kit::form('a'), 'Hair is something you have, so you use avoir: a.'),
            Kit::typeGap($stage, 'sentences.type_gap.luc-blonds', 'Luc a les cheveux ___.', 'Luc has blond hair.', 'blonds', Kit::word('blond', 'blonds'), 'Les cheveux is plural, so the colour takes an -s: blonds.'),
            Kit::typeGap($stage, 'sentences.type_gap.paul-luc-grands', 'Paul et Luc ___ grands.', 'Paul and Luc are tall.', 'sont', Kit::form('sont'), 'Two people need the plural of être: sont. Grands is plural as well.'),
            Kit::typeGap($stage, 'sentences.type_gap.anne-belle', 'Anne est très ___.', 'Anne is very good-looking.', 'belle', Kit::word('beau', 'belle')),

            Kit::translate($stage, 'sentences.translate.anne-grande-blonde', 'Anne is tall and blond.', ['Anne est grande et blonde.', 'Anne est blonde et grande.'], [Kit::word('grand', 'grande'), Kit::word('blond', 'blonde'), Kit::form('est')]),
            Kit::translate($stage, 'sentences.translate.luc-brun', 'Luc is dark-haired.', ['Luc est brun.', 'Luc a les cheveux bruns.'], [Kit::word('brun', 'brun', ['bruns']), Kit::form('est', alternates: ['a'])]),
            Kit::translate($stage, 'sentences.translate.marie-jeune', 'Marie is young and nice.', ['Marie est jeune et sympathique.', 'Marie est sympathique et jeune.'], [Kit::word('jeune'), Kit::word('sympathique'), Kit::form('est')]),

            Kit::build($stage, 'sentences.build.anne-yeux', 'Anne has blue eyes.', 'Anne a les yeux bleus.', ['est'], [Kit::word('les yeux', 'yeux'), Kit::form('a')], 'write', ['bleus' => 'blue']),
            Kit::build($stage, 'sentences.build.anne-tres-grande', 'Anne is very tall.', 'Anne est très grande.', ['grand'], [Kit::word('grand', 'grande'), Kit::form('est')]),
            Kit::build($stage, 'sentences.build.luc-cheveux', 'Luc has blond hair.', 'Luc a les cheveux blonds.', ['est'], [Kit::word('les cheveux', 'cheveux'), Kit::word('blond', 'blonds'), Kit::form('a')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.anne-grande-brune', 'Anne est grande et brune.', ['Anne is tall and dark-haired.', 'Anne is short and dark-haired.', 'Anne is tall and blond.', 'Anne is young and dark-haired.'], 'Anne is tall and dark-haired.', [Kit::word('grand', 'grande'), Kit::word('brun', 'brune'), Kit::form('est')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.luc-cheveux', 'Luc a les cheveux blonds.', ['Luc has blond hair.', 'Luc has dark hair.', 'Anne has blond hair.', 'Luc has blond eyes.'], 'Luc has blond hair.', [Kit::word('les cheveux', 'cheveux'), Kit::word('blond', 'blonds'), Kit::form('a')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.paul-jeune', 'Paul est jeune et beau.', ['Paul is young and good-looking.', 'Paul is old and good-looking.', 'Paul is young and tall.', 'Paul is nice and good-looking.'], 'Paul is young and good-looking.', [Kit::word('jeune'), Kit::word('beau'), Kit::form('est')]),
            Kit::listenType($stage, 'sentences.listen_type.luc-yeux', 'Luc a les yeux de Marie.', 'Luc has Marie\'s eyes.', [Kit::word('les yeux', 'yeux'), Kit::form('a')], homophoneNote: self::A),
            Kit::listenType($stage, 'sentences.listen_type.anne-personne', 'Anne est une personne très sympathique.', 'Anne is a very nice person.', [Kit::word('la personne', 'personne'), Kit::word('sympathique'), Kit::form('est')], homophoneNote: self::EST),
            Kit::listenType($stage, 'sentences.listen_type.paul-petit', 'Paul est grand, mais Luc est petit.', 'Paul is tall, but Luc is short.', [Kit::word('petit'), Kit::word('grand'), Kit::form('est')], homophoneNote: self::EST.' '.self::MAIS),
            Kit::listenType($stage, 'sentences.listen_type.marie-anne-jeunes', 'Marie et Anne sont jeunes.', 'Marie and Anne are young.', [Kit::word('jeune', 'jeunes'), Kit::form('sont')], homophoneNote: self::ET),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.marie-grande-belle', 'Marie est grande et belle.', 'Marie is tall and good-looking.', [Kit::word('grand', 'grande'), Kit::word('beau', 'belle'), Kit::form('est')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paul-cheveux', 'Paul a les cheveux bruns.', 'Paul has dark hair.', [Kit::word('les cheveux', 'cheveux'), Kit::word('brun', 'bruns'), Kit::form('a')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.marie-yeux', 'Marie a les yeux de Paul.', 'Marie has Paul\'s eyes.', [Kit::word('les yeux', 'yeux'), Kit::form('a')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paul-personne', 'Paul est une personne sympathique.', 'Paul is a nice person.', [Kit::word('la personne', 'personne'), Kit::word('sympathique'), Kit::form('est')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.es-grand', 'Tu es grand ?', 'Are you tall?', [['oui', 'non'], ['grand', 'grande', 'petit', 'petite']], 'Oui, je suis grand.', [Kit::word('grand')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.comment-est-anne', 'Comment est Anne ?', 'What is Anne like?', [['est', 'a'], ['grande', 'petite', 'blonde', 'brune', 'belle', 'jeune', 'sympathique', 'cheveux', 'blonds', 'bruns', 'yeux']], 'Elle est grande et blonde.', [Kit::word('blond', 'blonde'), Kit::form('est')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cheveux-blonds', 'Tu as les cheveux blonds ?', 'Do you have blond hair?', [['oui', 'non', "j'ai", 'ai'], ['blonds', 'blonde', 'bruns', 'brune']], "Oui, j'ai les cheveux blonds.", [Kit::word('les cheveux', 'cheveux'), Kit::word('blond'), Kit::form("j'ai")]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.comment-est-paul', 'Read the conversation about Paul and Luc.', [
                Kit::line('Marie', 'Anne, comment est Paul ?'),
                Kit::line('Anne', 'Il est grand et brun. Il a les yeux bleus.'),
                Kit::line('Marie', 'Il est sympathique ?'),
                Kit::line('Anne', "Oui, c'est une personne très sympathique."),
                Kit::line('Marie', 'Et Luc ?'),
                Kit::line('Anne', 'Luc est petit et blond. Il est jeune et très beau.'),
            ], [
                Kit::question('What does Paul look like?', ['Tall and dark-haired', 'Short and blond', 'Tall and blond'], 'Tall and dark-haired'),
                Kit::question('Is Paul nice?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Who is blond?', ['Luc', 'Paul', 'Both of them'], 'Luc'),
            ], [Kit::word('grand'), Kit::word('brun'), Kit::word('les yeux', 'yeux'), Kit::word('sympathique'), Kit::word('la personne', 'personne'), Kit::word('petit'), Kit::word('blond'), Kit::word('jeune'), Kit::word('beau')], 'read', null, ['bleus' => 'blue']),
            Kit::gap($stage, 'task.choose_gap.comment-est-paul', 'Paul ___ une personne sympathique.', ['est', 'a'], 'est', Kit::form('est', true), 'What kind of person he is, a lasting trait, is said with être: est. A would mean he has a person.', 'read', 'Paul is a nice person.'),
            Kit::gap($stage, 'task.choose_gap.anne-blonds', 'Anne a les cheveux ___ et elle est très belle.', ['blonds', 'blonde'], 'blonds', Kit::word('blond', 'blonds'), 'Les cheveux is plural, so the colour is blonds. Blonde would describe Anne herself: Anne est blonde.', 'read', 'Anne has blond hair and is very good-looking.'),

            Kit::transform($stage, 'task.transform.marie-petite-brune', 'Change it to talk about Marie.', 'Paul est petit et brun.', ['Marie est petite et brune.', 'Marie est brune et petite.'], [Kit::word('petit', 'petite'), Kit::word('brun', 'brune'), Kit::form('est')]),
            Kit::transform($stage, 'task.transform.jeunes-sympathiques', 'Change it to talk about Luc and Paul.', 'Anne est jeune et sympathique.', ['Luc et Paul sont jeunes et sympathiques.', 'Paul et Luc sont jeunes et sympathiques.', 'Luc et Paul sont sympathiques et jeunes.', 'Paul et Luc sont sympathiques et jeunes.'], [Kit::word('jeune', 'jeunes'), Kit::word('sympathique', 'sympathiques'), Kit::form('sont')]),
            Kit::transform($stage, 'task.transform.anne-cheveux', 'Say that Anne has blond hair.', 'Anne est blonde.', ['Anne a les cheveux blonds.', 'Anne a des cheveux blonds.'], [Kit::word('les cheveux', 'cheveux'), Kit::word('blond', 'blonds'), Kit::form('a', true)]),
            Kit::writeGuided($stage, 'task.write_guided.marie-grande', 'Say that Marie is tall and has blond hair.', ['est', 'grande', 'a', 'cheveux', 'blonds'], 'Marie est grande et elle a les cheveux blonds.', [
                ['forms' => ['est'], 'term' => null],
                ['forms' => ['grande'], 'term' => 'grand'],
                ['forms' => ['cheveux'], 'term' => 'les cheveux'],
                ['forms' => ['blonds'], 'term' => 'blond'],
            ], [Kit::word('grand', 'grande'), Kit::word('les cheveux', 'cheveux'), Kit::word('blond', 'blonds')]),
            Kit::writeGuided($stage, 'task.write_guided.paul-yeux', 'Say that Paul is a nice person and has blue eyes.', ['est', 'personne', 'sympathique', 'a', 'yeux', 'bleus'], 'Paul est une personne sympathique et il a les yeux bleus.', [
                ['forms' => ['est'], 'term' => null],
                ['forms' => ['personne'], 'term' => 'la personne'],
                ['forms' => ['sympathique'], 'term' => 'sympathique'],
                ['forms' => ['yeux'], 'term' => 'les yeux'],
            ], [Kit::word('la personne', 'personne'), Kit::word('sympathique'), Kit::word('les yeux', 'yeux')], ['bleus' => 'blue']),
            Kit::build($stage, 'task.build.paul-pas-grand', 'Paul is not tall.', "Paul n'est pas grand.", ['a', 'grande'], [Kit::word('grand'), Kit::form("n'est")], 'write'),
            Kit::build($stage, 'task.build.luc-petit-anne-grande', 'Luc is short, but Anne is tall.', 'Luc est petit, mais Anne est grande.', ['a', 'yeux'], [Kit::word('petit'), Kit::word('grand', 'grande'), Kit::form('est')], 'write'),
            Kit::build($stage, 'task.build.luc-brun', 'Luc has dark hair.', 'Luc a les cheveux bruns.', ['est', 'brune'], [Kit::word('brun', 'bruns'), Kit::form('a')], 'write'),
            Kit::translate($stage, 'task.translate.anne-jeune-pas-blonde', 'Anne is young, but she is not blond.', ["Anne est jeune, mais elle n'est pas blonde."], [Kit::word('jeune'), Kit::word('blond', 'blonde'), Kit::form("n'est")], 'write'),
            Kit::translate($stage, 'task.translate.paul-grand-petit', 'Is Paul tall or short?', ['Paul est grand ou petit ?', 'Paul est-il grand ou petit ?'], [Kit::word('grand'), Kit::word('petit'), Kit::form('est')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.comment-est-anne', [
                Kit::line('Luc', 'Marie, comment est Anne ?'),
                Kit::line('Marie', 'Elle est jeune et très belle. Elle a les cheveux blonds.'),
                Kit::line('Luc', 'Elle est grande ?'),
                Kit::line('Marie', "Non, elle est petite. Mais c'est une personne très sympathique."),
            ], [
                Kit::question('What does Anne look like?', ['Young, good-looking and blond', 'Tall and dark-haired', 'Short and dark-haired'], 'Young, good-looking and blond'),
                Kit::question('Is Anne tall?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What is Anne like as a person?', ['Nice', 'Not nice', 'The conversation does not say.'], 'Nice'),
            ], [
                Kit::question('Who asks about Anne?', ['Luc', 'Marie', 'Nobody'], 'Luc'),
                Kit::question('Does Anne have blond hair?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('jeune'), Kit::word('beau', 'belle'), Kit::word('les cheveux', 'cheveux'), Kit::word('blond'), Kit::word('grand', 'grande'), Kit::word('petit', 'petite'), Kit::word('la personne', 'personne'), Kit::word('sympathique')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.anne-cheveux-yeux', 'Anne a les cheveux blonds et les yeux de Luc.', 'Anne has blond hair and Luc\'s eyes.', [Kit::word('les cheveux', 'cheveux'), Kit::word('blond', 'blonds'), Kit::word('les yeux', 'yeux'), Kit::form('a')], 'listen', homophoneNote: self::A.' '.self::ET),
            Kit::listenType($stage, 'task.listen_type.luc-jeune-beau', 'Luc est jeune, brun et très beau.', 'Luc is young, dark-haired and very good-looking.', [Kit::word('jeune'), Kit::word('brun'), Kit::word('beau'), Kit::form('est')], 'listen', homophoneNote: self::EST.' '.self::ET),
            Kit::listenType($stage, 'task.listen_type.anne-va-bien', 'Anne est petite, mais elle est très sympathique.', 'Anne is short, but she is very nice.', [Kit::word('petit', 'petite'), Kit::word('sympathique'), Kit::form('est', true)], 'listen', homophoneNote: self::MAIS.' '.self::EST),

            Kit::speakAnswer($stage, 'task.speak_answer.tu-es-comment', 'Tu es comment ?', 'What are you like?', [['suis', 'ai'], ['grand', 'grande', 'petit', 'petite', 'jeune', 'blond', 'blonde', 'brun', 'brune', 'sympathique', 'beau', 'belle', 'cheveux', 'yeux']], 'Je suis grand et sympathique.', [Kit::word('grand'), Kit::word('sympathique')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cheveux-bruns', 'Tu as les cheveux bruns ?', 'Do you have dark hair?', [['oui', 'non', "j'ai", 'ai'], ['bruns', 'brune', 'blonds', 'blonde']], "Oui, j'ai les cheveux bruns.", [Kit::word('les cheveux', 'cheveux'), Kit::word('brun', 'bruns'), Kit::form("j'ai")], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.marie-grande', 'Marie est grande ?', 'Is Marie tall?', [['oui', 'non'], ['grande', 'petite']], 'Non, elle est petite.', [Kit::word('petit', 'petite')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tu-es-jeune', 'Tu es jeune ?', 'Are you young?', [['oui', 'non'], ['jeune', 'jeunes']], 'Oui, je suis jeune.', [Kit::word('jeune')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.luc-petit-cheveux', 'Luc est petit et il a les cheveux bruns.', 'Luc is short and has dark hair.', [Kit::word('petit'), Kit::word('brun', 'bruns'), Kit::form('a')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.marie-jeune-belle', 'Marie est jeune et très belle.', 'Marie is young and very good-looking.', [Kit::word('jeune'), Kit::word('beau', 'belle'), Kit::form('est')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.anne-grande-belle', 'Anne is tall and good-looking.', ['Anne est grande et belle.', 'Anne est belle et grande.'], [Kit::word('grand', 'grande'), Kit::word('beau', 'belle'), Kit::form('est')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paul-cheveux', 'Paul is tall and has blond hair.', ['Paul est grand et il a les cheveux blonds.', 'Paul est grand et a les cheveux blonds.'], [Kit::word('grand'), Kit::word('les cheveux', 'cheveux'), Kit::word('blond', 'blonds'), Kit::form('a')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.luc-personne', 'Luc is a young and nice person.', ['Luc est une personne jeune et sympathique.', 'Luc est une personne sympathique et jeune.', 'Luc est une jeune personne sympathique.'], [Kit::word('la personne', 'personne'), Kit::word('jeune'), Kit::word('sympathique'), Kit::form('est')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marie-petite-cheveux', 'Marie is short, but she has blond hair.', ['Marie est petite, mais elle a les cheveux blonds.'], [Kit::word('petit', 'petite'), Kit::form('a', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.paul-luc-petits', 'Paul et Luc sont ___.', 'Paul and Luc are short.', 'petits', Kit::word('petit', 'petits'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.anne-yeux', 'Anne a les ___ de Luc.', 'Anne has Luc\'s eyes.', 'yeux', Kit::word('les yeux', 'yeux'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marie-anne-grandes', 'Marie et Anne sont grandes et très sympathiques.', 'Marie and Anne are tall and very nice.', [Kit::word('grand', 'grandes'), Kit::word('sympathique', 'sympathiques'), Kit::form('sont')], 'dictation', $set, homophoneNote: self::SONT.' '.self::ET),
            Kit::listenType($stage, 'check.a.listen_type.paul-jeune-cheveux', 'Paul est jeune et il a les cheveux bruns.', 'Paul is young and has dark hair.', [Kit::word('jeune'), Kit::word('brun', 'bruns'), Kit::word('les cheveux', 'cheveux'), Kit::form('a', true)], 'dictation', $set, homophoneNote: self::EST.' '.self::ET.' '.self::A),
            Kit::listenType($stage, 'check.a.listen_type.anne-petite-belle', 'Anne est petite et très belle.', 'Anne is short and very good-looking.', [Kit::word('petit', 'petite'), Kit::word('beau', 'belle')], 'dictation', $set, homophoneNote: self::EST.' '.self::ET),
            Kit::listenPassage($stage, 'check.a.listen_passage.comment-est-marie', [
                Kit::line('Anne', 'Paul, comment est Marie ?'),
                Kit::line('Paul', 'Elle est jeune et belle. Elle a les yeux de Luc.'),
                Kit::line('Anne', 'Elle est blonde ?'),
                Kit::line('Paul', 'Non, elle est brune.'),
            ], [
                Kit::question('What is Marie like?', ['Young and good-looking', 'Short and nice', 'Tall and blond'], 'Young and good-looking'),
                Kit::question('Whose eyes does Marie have?', ['Luc\'s', 'Paul\'s', 'Anne\'s'], 'Luc\'s'),
                Kit::question('Is Marie blond?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('Who asks about Marie?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
                Kit::question('Is Marie young?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('jeune'), Kit::word('beau', 'belle'), Kit::word('les yeux', 'yeux'), Kit::word('blond', 'blonde'), Kit::word('brun', 'brune')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.comment-est-luc', 'Read the conversation.', [
                Kit::line('Marie', 'Anne, comment est Luc ?'),
                Kit::line('Anne', "Il est petit, mais c'est une personne très sympathique. Il a les yeux de Paul."),
                Kit::line('Marie', 'Et Paul ?'),
                Kit::line('Anne', 'Paul est grand et blond.'),
            ], [
                Kit::question('What is Luc like?', ['Short and nice', 'Tall and nice', 'Short and blond'], 'Short and nice'),
                Kit::question('Who is blond?', ['Paul', 'Luc', 'Nobody'], 'Paul'),
            ], [Kit::word('petit'), Kit::word('la personne', 'personne'), Kit::word('sympathique'), Kit::word('les yeux', 'yeux'), Kit::word('grand'), Kit::word('blond')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.es-blond', 'Tu es blond ?', 'Are you blond?', [['oui', 'non'], ['blond', 'blonde', 'brun', 'brune']], 'Non, je suis brun.', [Kit::word('blond'), Kit::word('brun')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cheveux-blonds-bruns', 'Tu as les cheveux blonds ou bruns ?', 'Do you have blond or dark hair?', [['sont', "j'ai", 'ai', 'suis'], ['blonds', 'bruns', 'blonde', 'brune']], "J'ai les cheveux bruns.", [Kit::word('les cheveux', 'cheveux'), Kit::word('blond'), Kit::form("j'ai")], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.personne-sympathique', 'Tu es une personne sympathique ?', 'Are you a nice person?', [['oui', 'non'], ['suis', 'sympathique']], 'Oui, je suis une personne sympathique.', [Kit::word('la personne', 'personne'), Kit::word('sympathique')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.luc-grand-brun', 'Luc is tall and dark-haired.', ['Luc est grand et brun.', 'Luc est brun et grand.'], [Kit::word('grand'), Kit::word('brun'), Kit::form('est')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marie-anne-blondes', 'Marie and Anne are blond and good-looking.', ['Marie et Anne sont blondes et belles.', 'Marie et Anne sont belles et blondes.'], [Kit::word('blond', 'blondes'), Kit::word('beau', 'belles'), Kit::form('sont')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.anne-jeune-yeux', 'Anne is young and has Paul\'s eyes.', ['Anne est jeune et elle a les yeux de Paul.', 'Anne est jeune et a les yeux de Paul.'], [Kit::word('jeune'), Kit::word('les yeux', 'yeux'), Kit::form('a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luc-personne-petit', 'Luc is a nice person, but he is short.', ['Luc est une personne sympathique, mais il est petit.'], [Kit::word('la personne', 'personne'), Kit::word('sympathique'), Kit::form('est', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.anne-marie-petites', 'Anne et Marie sont ___.', 'Anne and Marie are short.', 'petites', Kit::word('petit', 'petites'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.paul-yeux', 'Paul a les ___ de Marie.', 'Paul has Marie\'s eyes.', 'yeux', Kit::word('les yeux', 'yeux'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.marie-personne', 'Marie est une personne sympathique et belle.', 'Marie is a nice and good-looking person.', [Kit::word('la personne', 'personne'), Kit::word('sympathique'), Kit::word('beau', 'belle')], 'dictation', $set, homophoneNote: self::EST.' '.self::ET),
            Kit::listenType($stage, 'check.b.listen_type.anne-petite-cheveux', 'Anne est petite et elle a les cheveux blonds.', 'Anne is short and has blond hair.', [Kit::word('petit', 'petite'), Kit::word('les cheveux', 'cheveux'), Kit::word('blond', 'blonds'), Kit::form('est')], 'dictation', $set, homophoneNote: self::EST.' '.self::ET.' '.self::A),
            Kit::listenType($stage, 'check.b.listen_type.luc-jeune-grand', 'Luc est jeune, grand et il a les cheveux bruns.', 'Luc is young, tall and has dark hair.', [Kit::word('jeune'), Kit::word('grand'), Kit::word('les cheveux', 'cheveux'), Kit::word('brun', 'bruns'), Kit::form('a', true)], 'dictation', $set, homophoneNote: self::EST.' '.self::ET.' '.self::A),
        ];
    }
}
