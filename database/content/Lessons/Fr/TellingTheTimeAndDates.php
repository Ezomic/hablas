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

final class TellingTheTimeAndDates implements UnitContent
{
    private const string EST = 'Est (is, the verb) and et (and) sound very close, and context decides: here est is the verb.';

    private const string DEMIE = 'Demi and demie sound the same. After the feminine heure it is written demie.';

    private const string EST_ET = 'Est (is, the verb) and et (and) sound very close, and context decides: here est is the verb and et means and. '.self::DEMIE;

    private const string A_ET = 'À (at, with an accent) sounds the same as a (has, without an accent). Et (and) and est (is) sound very close, and context decides: here et means and. '.self::DEMIE;

    private const string EST_OU_ET = 'Est (is, the verb), et (and) and ou (or) are easy to mix up, and context decides: here est is the verb, ou means or and et means and. '.self::DEMIE;

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'telling-the-time-and-dates';
    }

    public function words(): array
    {
        return [
            new WordData('l\'heure', cue: 'time (on the clock) or hour (feminine: une heure)', forms: ['heure', 'heures'], note: 'L\'heure is the time of day on the clock, and also one hour. Heure is feminine: une heure.'),
            new WordData('la montre', cue: 'watch', forms: ['montre'], note: 'La montre is a watch. A wall clock is une horloge, which you do not need yet.'),
            new WordData('le jour', cue: 'day', forms: ['jour', 'jours']),
            new WordData('la semaine', cue: 'week', forms: ['semaine']),
            new WordData('aujourd\'hui', cue: 'today'),
            new WordData('demain', cue: 'tomorrow'),
            new WordData('lundi', cue: 'Monday', note: 'Days of the week have no capital letter in French. Lundi on its own means on Monday.'),
            new WordData('dimanche', cue: 'Sunday', note: 'Days of the week have no capital letter in French. Dimanche on its own means on Sunday.'),
            new WordData('pile', cue: 'on the dot (exactly, for the hour)', note: 'Pile comes after the hour: trois heures pile.'),
            new WordData('et demie', cue: 'half past', forms: ['demie'], note: 'After heure (feminine) the half is demie: deux heures et demie.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Il est une heure.', 'english' => 'It is one o\'clock.'],
            ['text' => 'Je suis ici à trois heures.', 'english' => 'I am here at three.'],
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
            Kit::gap($stage, 'sentences.choose_gap.une-heure', 'Il est une ___.', ['heure', 'heures', 'montre'], 'heure', Kit::form('heure', true), 'Une is singular, so heure has no s: une heure.', 'choose', 'It is one o\'clock.'),
            Kit::gap($stage, 'sentences.choose_gap.demie', 'Il est trois heures et ___.', ['demie', 'pile', 'jour'], 'demie', Kit::word('et demie', 'demie'), 'Et demie means half past. Heure is feminine, so the half ends in an e: demie.', 'choose', 'It is half past three.'),
            Kit::gap($stage, 'sentences.choose_gap.lundi', 'Aujourd\'hui, c\'est ___.', ['lundi', 'semaine', 'montre'], 'lundi', Kit::word('lundi'), 'Lundi is a day of the week. Semaine and montre are not days.', 'choose', 'Today is Monday.'),
            Kit::gap($stage, 'sentences.choose_gap.il-est-deux', 'Il ___ deux heures.', ['est', 'a', 'va'], 'est', Kit::form('est', true), 'Telling the time uses il est. Il a would mean he has, and il va would mean he goes.', 'choose', 'It is two o\'clock.'),
            Kit::gap($stage, 'sentences.choose_gap.a-cinq', 'Je suis ici ___ cinq heures.', ['à', 'de', 'en'], 'à', Kit::form('à', true), 'To say at five o\'clock, use à: à cinq heures. De and en do not fit here.', 'choose', 'I am here at five o\'clock.'),
            Kit::gap($stage, 'sentences.choose_gap.demain', 'Aujourd\'hui, c\'est dimanche. ___, c\'est lundi.', ['Demain', 'Semaine', 'Heure'], 'Demain', Kit::word('demain'), 'Demain means tomorrow, the day after today. Semaine and heure are not days.', 'choose', 'Today is Sunday. Tomorrow is Monday.'),

            Kit::typeGap($stage, 'sentences.type_gap.pile', 'Il est quatre heures ___.', 'It is four o\'clock on the dot.', 'pile', Kit::word('pile')),
            Kit::typeGap($stage, 'sentences.type_gap.six-heures', 'Il est six ___.', 'It is six o\'clock.', 'heures', Kit::form('heures'), 'Six is more than one, so heure takes an s: six heures.'),
            Kit::typeGap($stage, 'sentences.type_gap.heure', 'Anne, quelle ___ est-il ?', 'Anne, what time is it?', 'heure', Kit::word('l\'heure', 'heure')),
            Kit::typeGap($stage, 'sentences.type_gap.montre', 'Ma ___ est ici.', 'My watch is here.', 'montre', Kit::word('la montre', 'montre')),
            Kit::typeGap($stage, 'sentences.type_gap.jour', 'Aujourd\'hui, c\'est quel ___ ? C\'est dimanche.', 'What day is it today? It is Sunday.', 'jour', Kit::word('le jour', 'jour')),

            Kit::translate($stage, 'sentences.translate.une-pile', 'It is exactly one o\'clock.', ['Il est une heure pile.', 'Il est pile une heure.'], [Kit::word('pile'), Kit::form('heure', true)]),
            Kit::translate($stage, 'sentences.translate.heure', 'What time is it?', ['Quelle heure est-il ?', 'Quelle heure est-il', 'Quelle heure il est ?', 'Il est quelle heure ?'], [Kit::word('l\'heure', 'heure')]),
            Kit::translate($stage, 'sentences.translate.deux-demie', 'It is half past two.', ['Il est deux heures et demie.'], [Kit::word('et demie', 'demie'), Kit::form('heures')]),

            Kit::build($stage, 'sentences.build.ici-six', 'I am here at six.', 'Je suis ici à six heures.', ['en'], [Kit::form('à')]),
            Kit::build($stage, 'sentences.build.demain-lundi', 'Tomorrow is Monday.', 'Demain, c\'est lundi.', ['sont'], [Kit::word('demain'), Kit::word('lundi')]),
            Kit::build($stage, 'sentences.build.sept-pile', 'It is seven o\'clock on the dot.', 'Il est sept heures pile.', ['heure'], [Kit::word('pile'), Kit::form('est')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.une-demie', 'Il est une heure et demie.', ['It is half past one.', 'It is one o\'clock.', 'It is half past two.', 'It is two o\'clock.'], 'It is half past one.', [Kit::word('et demie', 'demie'), Kit::form('est', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.aujourdhui-dimanche', 'Aujourd\'hui, c\'est dimanche.', ['Today is Sunday.', 'Tomorrow is Sunday.', 'Today is Monday.', 'Tomorrow is Monday.'], 'Today is Sunday.', [Kit::word('aujourd\'hui'), Kit::word('dimanche')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.semaine', 'Une semaine a sept jours.', ['There are seven days in a week.', 'There are five days in a week.', 'There are seven weeks in a day.', 'There are seven hours in a day.'], 'There are seven days in a week.', [Kit::word('la semaine', 'semaine'), Kit::word('le jour', 'jours')]),
            Kit::listenType($stage, 'sentences.listen_type.montre', 'Ma montre est ici, Anne.', 'My watch is here, Anne.', [Kit::word('la montre', 'montre')], homophoneNote: self::EST),
            Kit::listenType($stage, 'sentences.listen_type.neuf-demie', 'Il est neuf heures et demie.', 'It is half past nine.', [Kit::word('et demie', 'demie'), Kit::form('est')], homophoneNote: self::EST_ET),
            Kit::listenType($stage, 'sentences.listen_type.jour-aujourdhui', 'Aujourd\'hui, c\'est quel jour, Anne ?', 'What day is it today, Anne?', [Kit::word('le jour', 'jour'), Kit::word('aujourd\'hui')]),
            Kit::listenType($stage, 'sentences.listen_type.heure-une', 'Quelle heure est-il ? Il est une heure.', 'What time is it? It is one o\'clock.', [Kit::word('l\'heure', 'heure'), Kit::form('heure')], homophoneNote: 'Est (is, the verb) sounds close to et (and). Here both times est is the verb.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.cinq-demie', 'Il est cinq heures et demie.', 'It is half past five.', [Kit::word('et demie', 'demie'), Kit::form('est')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.aujourdhui-demain', 'Aujourd\'hui, c\'est dimanche. Demain, c\'est lundi.', 'Today is Sunday. Tomorrow is Monday.', [Kit::word('aujourd\'hui'), Kit::word('demain'), Kit::word('dimanche'), Kit::word('lundi')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.huit-pile', 'Je suis ici à huit heures pile.', 'I am here at eight on the dot.', [Kit::word('pile'), Kit::form('à')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.heure-montre', 'Quelle heure est-il sur ta montre ?', 'What time is it on your watch?', [Kit::word('l\'heure', 'heure'), Kit::word('la montre', 'montre')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.une-demie', 'Il est une heure et demie ?', 'Is it half past one?', [['oui', 'non', 'il', 'est'], ['demie']], 'Oui, il est une heure et demie.', [Kit::word('et demie', 'demie')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.demain', 'Aujourd\'hui, c\'est dimanche. Demain, c\'est quel jour ?', 'Today is Sunday. What day is tomorrow?', [['demain', 'c\'est'], ['lundi']], 'Demain, c\'est lundi.', [Kit::word('demain'), Kit::word('lundi')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.semaine', 'Sept jours dans la semaine ?', 'Are there seven days in the week?', [['oui', 'non', 'il'], ['semaine', 'sept', 'jours']], 'Oui, il y a sept jours dans la semaine.', [Kit::word('la semaine', 'semaine'), Kit::word('le jour', 'jours')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.heure', 'Read the conversation.', [
                Kit::line('Anne', 'Paul, quelle heure est-il ?'),
                Kit::line('Paul', 'Il est deux heures et demie.'),
                Kit::line('Anne', 'Aujourd\'hui, je suis ici à trois heures. Demain, je suis ici à cinq heures.'),
                Kit::line('Paul', 'Au revoir, Anne.'),
            ], [
                Kit::question('What time is it?', ['Half past two', 'Two o\'clock', 'Half past three'], 'Half past two'),
                Kit::question('At what time is Anne here today?', ['At three', 'At five', 'At two'], 'At three'),
                Kit::question('At what time is Anne here tomorrow?', ['At five', 'At three', 'At two'], 'At five'),
            ], [Kit::word('l\'heure', 'heure'), Kit::word('et demie', 'demie'), Kit::word('aujourd\'hui'), Kit::word('demain')], 'read'),
            Kit::gap($stage, 'task.choose_gap.jours', 'Une semaine a sept ___.', ['jours', 'heure', 'montre'], 'jours', Kit::word('le jour', 'jours'), 'Sept means more than one, so we need the plural: jours.', 'read', 'A week has seven days.'),
            Kit::gap($stage, 'task.choose_gap.pile', 'Il est cinq heures ___.', ['pile', 'jour', 'semaine'], 'pile', Kit::word('pile'), 'Pile means on the dot, exactly. Jour and semaine do not fit here.', 'read', 'It is five o\'clock on the dot.'),

            Kit::transform($stage, 'task.transform.trois', 'Change it to three o\'clock.', 'Il est deux heures pile.', ['Il est trois heures pile.'], [Kit::word('pile'), Kit::form('heures')]),
            Kit::transform($stage, 'task.transform.une-demie', 'Change it to half past one.', 'Il est trois heures et demie.', ['Il est une heure et demie.'], [Kit::word('et demie', 'demie'), Kit::form('heure', true)]),
            Kit::transform($stage, 'task.transform.demain', 'Say it for tomorrow.', 'Aujourd\'hui, c\'est lundi.', ['Demain, c\'est lundi.', 'Demain c\'est lundi.'], [Kit::word('demain'), Kit::word('lundi')]),
            Kit::writeGuided($stage, 'task.write_guided.aujourdhui', 'Say that today is Monday and that it is two o\'clock exactly.', ['aujourd\'hui', 'lundi', 'est', 'pile'], 'Aujourd\'hui, c\'est lundi. Il est deux heures pile.', [
                ['forms' => ['aujourd\'hui'], 'term' => 'aujourd\'hui'],
                ['forms' => ['lundi'], 'term' => 'lundi'],
                ['forms' => ['pile'], 'term' => 'pile'],
            ], [Kit::word('aujourd\'hui'), Kit::word('lundi'), Kit::word('pile')]),
            Kit::writeGuided($stage, 'task.write_guided.montre', 'Say that your watch is here and that it is half past four.', ['montre', 'ici', 'est', 'et demie'], 'Ma montre est ici. Il est quatre heures et demie.', [
                ['forms' => ['montre'], 'term' => 'la montre'],
                ['forms' => ['demie'], 'term' => 'et demie'],
            ], [Kit::word('la montre', 'montre'), Kit::word('et demie', 'demie')]),
            Kit::build($stage, 'task.build.demain-demie', 'Tomorrow I am here at half past nine.', 'Demain, je suis ici à neuf heures et demie.', ['en', 'heure'], [Kit::word('demain'), Kit::word('et demie', 'demie'), Kit::form('à')], 'write'),
            Kit::build($stage, 'task.build.sept-jours', 'There are seven days in the week.', 'Il y a sept jours dans la semaine.', ['jour', 'le'], [Kit::word('la semaine', 'semaine'), Kit::word('le jour', 'jours')], 'write'),
            Kit::build($stage, 'task.build.heure-marie', 'What time is it, Marie?', 'Quelle heure est-il, Marie ?', ['heures', 'sont'], [Kit::word('l\'heure', 'heure'), Kit::form('est')], 'write'),
            Kit::translate($stage, 'task.translate.jours-semaine', 'How many days does the week have?', ['La semaine a combien de jours ?', 'Combien de jours a la semaine ?', 'Il y a combien de jours dans la semaine ?', 'Une semaine a combien de jours ?'], [Kit::word('la semaine', 'semaine'), Kit::word('le jour', 'jours')], 'write'),
            Kit::translate($stage, 'task.translate.quelle-heure', 'At what time are you here, Paul? (informal you)', ['Tu es ici à quelle heure, Paul ?', 'Paul, tu es ici à quelle heure ?', 'À quelle heure tu es ici, Paul ?', 'Paul, à quelle heure tu es ici ?', 'À quelle heure es-tu ici, Paul ?', 'Paul, à quelle heure es-tu ici ?', 'À quelle heure es-tu là, Paul ?', 'Paul, à quelle heure es-tu là ?'], [Kit::word('l\'heure', 'heure')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.jour', [
                Kit::line('Paul', 'Aujourd\'hui, c\'est quel jour, Anne ?'),
                Kit::line('Anne', 'C\'est dimanche. Demain, c\'est lundi.'),
                Kit::line('Paul', 'Et tu es ici à quelle heure ?'),
                Kit::line('Anne', 'Je suis ici à huit heures pile.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What day is tomorrow?', ['Monday', 'Sunday', 'Friday'], 'Monday'),
                Kit::question('At what time is Anne here?', ['At eight on the dot', 'At half past eight', 'At nine'], 'At eight on the dot'),
            ], [
                Kit::question('Who asks the questions?', ['Paul', 'Anne', 'Nobody'], 'Paul'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is tomorrow Monday?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('le jour', 'jour'), Kit::word('aujourd\'hui'), Kit::word('demain'), Kit::word('dimanche'), Kit::word('lundi'), Kit::word('pile'), Kit::word('l\'heure', 'heure')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.montre', 'La montre de Paul est ici.', 'Paul\'s watch is here.', [Kit::word('la montre', 'montre')], 'listen', homophoneNote: self::EST),
            Kit::listenType($stage, 'task.listen_type.dix-demie', 'Aujourd\'hui, je suis ici à dix heures et demie.', 'Today I am here at half past ten.', [Kit::word('aujourd\'hui'), Kit::word('et demie', 'demie'), Kit::form('à')], 'listen', homophoneNote: self::A_ET),
            Kit::listenType($stage, 'task.listen_type.dimanche', 'Aujourd\'hui, c\'est dimanche. Quelle heure est-il, Anne ?', 'Today is Sunday. What time is it, Anne?', [Kit::word('aujourd\'hui'), Kit::word('dimanche'), Kit::word('l\'heure', 'heure')], 'listen', homophoneNote: self::EST),

            Kit::speakAnswer($stage, 'task.speak_answer.une-pile', 'Il est une heure pile ?', 'Is it exactly one o\'clock?', [['oui', 'non', 'il', 'est'], ['pile']], 'Oui, il est une heure pile.', [Kit::word('pile')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.a-quelle-heure', 'Tu es ici à quelle heure aujourd\'hui ?', 'At what time are you here today? (informal you)', [['je', 'suis'], ['heures', 'heure']], 'Je suis ici à trois heures.', [Kit::form('heures')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.aujourdhui-lundi', 'Aujourd\'hui, c\'est lundi ?', 'Is today Monday?', [['oui', 'non', 'c\'est', 'aujourd\'hui'], ['lundi', 'dimanche']], 'Non, aujourd\'hui, c\'est dimanche.', [Kit::word('aujourd\'hui'), Kit::word('lundi')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.montre', 'Ta montre est ici ?', 'Is your watch here? (informal you)', [['oui', 'non', 'ma', 'est'], ['montre']], 'Oui, ma montre est ici.', [Kit::word('la montre', 'montre')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.trois-demie', 'Aujourd\'hui, je suis ici à trois heures et demie.', 'Today I am here at half past three.', [Kit::word('aujourd\'hui'), Kit::word('et demie', 'demie'), Kit::form('à')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.jour', 'Aujourd\'hui, c\'est quel jour ? C\'est dimanche.', 'What day is it today? It is Sunday.', [Kit::word('le jour', 'jour'), Kit::word('dimanche')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.quatre-demie', 'It is half past four.', ['Il est quatre heures et demie.'], [Kit::word('et demie', 'demie'), Kit::form('heures')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.demain', 'Tomorrow is Monday, not Sunday.', ['Demain, c\'est lundi, pas dimanche.', 'Demain c\'est lundi, pas dimanche.'], [Kit::word('demain'), Kit::word('lundi'), Kit::word('dimanche')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.semaine', 'The week has seven days.', ['La semaine a sept jours.'], [Kit::word('la semaine', 'semaine'), Kit::word('le jour', 'jours')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.neuf', 'I am here at nine on the dot.', ['Je suis ici à neuf heures pile.', 'Je suis là à neuf heures pile.'], [Kit::word('pile'), Kit::form('à', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sept', 'Marie est ici ___ sept heures.', 'Marie is here at seven.', 'à', Kit::form('à'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.une-vingt', 'Il est une ___ vingt.', 'It is twenty past one.', 'heure', Kit::form('heure', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.huit-demie', 'Il est huit heures et demie.', 'It is half past eight.', [Kit::word('et demie', 'demie'), Kit::form('est')], 'dictation', $set, homophoneNote: self::EST_ET),
            Kit::listenType($stage, 'check.a.listen_type.montre', 'La montre est ici, Marie.', 'The watch is here, Marie.', [Kit::word('la montre', 'montre')], 'dictation', $set, homophoneNote: self::EST),
            Kit::listenType($stage, 'check.a.listen_type.aujourdhui', 'Aujourd\'hui, c\'est dimanche. Quelle heure est-il, Luc ?', 'Today is Sunday. What time is it, Luc?', [Kit::word('aujourd\'hui'), Kit::word('dimanche'), Kit::word('l\'heure', 'heure'), Kit::form('est')], 'dictation', $set, homophoneNote: self::EST),
            Kit::listenPassage($stage, 'check.a.listen_passage.heure', [
                Kit::line('Anne', 'Luc, quelle heure est-il ?'),
                Kit::line('Luc', 'Il est six heures et demie.'),
                Kit::line('Anne', 'Demain, je suis ici à neuf heures pile.'),
            ], [
                Kit::question('What time is it?', ['Half past six', 'Six o\'clock', 'Half past nine'], 'Half past six'),
                Kit::question('At what time is Anne here tomorrow?', ['At nine on the dot', 'At six', 'At half past nine'], 'At nine on the dot'),
                Kit::question('On which day is Anne here at nine on the dot?', ['Tomorrow', 'Today', 'On Sunday'], 'Tomorrow'),
            ], [
                Kit::question('Who answers the question?', ['Luc', 'Anne', 'Nobody'], 'Luc'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is it six o\'clock on the dot?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
            ], [Kit::word('l\'heure', 'heure'), Kit::word('et demie', 'demie'), Kit::word('demain'), Kit::word('pile')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.jour', 'Read the conversation.', [
                Kit::line('Marie', 'Luc, c\'est quel jour aujourd\'hui ?'),
                Kit::line('Luc', 'Aujourd\'hui, c\'est dimanche et demain, c\'est lundi.'),
                Kit::line('Marie', 'Merci. Et quelle heure est-il ?'),
                Kit::line('Luc', 'Il est cinq heures.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What does Marie ask at the end?', ['What time it is', 'What day it is', 'Where the watch is'], 'What time it is'),
            ], [Kit::word('le jour', 'jour'), Kit::word('aujourd\'hui'), Kit::word('dimanche'), Kit::word('lundi')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.montre', 'Où est ta montre ?', 'Where is your watch?', [['montre', 'est'], ['ici', 'là']], 'Ma montre est ici.', [Kit::word('la montre', 'montre')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aujourdhui', 'Demain, c\'est lundi. Aujourd\'hui, c\'est quel jour ?', 'Tomorrow is Monday. What day is it today?', [['aujourd\'hui', 'c\'est'], ['dimanche']], 'Aujourd\'hui, c\'est dimanche.', [Kit::word('aujourd\'hui'), Kit::word('dimanche')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.semaine', 'Une semaine a combien de jours ?', 'How many days does a week have?', [['semaine', 'a'], ['sept']], 'La semaine a sept jours.', [Kit::word('la semaine', 'semaine')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.onze', 'It is eleven o\'clock on the dot on my watch.', ['Il est onze heures pile sur ma montre.', 'Sur ma montre, il est onze heures pile.', 'Il est onze heures pile à ma montre.', 'À ma montre, il est onze heures pile.'], [Kit::word('pile'), Kit::word('la montre', 'montre'), Kit::form('heures')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.heure', 'What time is it on the watch?', ['Quelle heure est-il sur la montre ?', 'Quelle heure est-il à la montre ?', 'À la montre, quelle heure est-il ?', 'Sur la montre, quelle heure est-il ?', 'Il est quelle heure sur la montre ?', 'Sur la montre, il est quelle heure ?'], [Kit::word('l\'heure', 'heure'), Kit::word('la montre', 'montre'), Kit::form('est', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.demain', 'Tomorrow is Monday and today is Sunday.', ['Demain, c\'est lundi et aujourd\'hui, c\'est dimanche.', 'Demain c\'est lundi et aujourd\'hui c\'est dimanche.'], [Kit::word('demain'), Kit::word('lundi'), Kit::word('aujourd\'hui'), Kit::word('dimanche')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.semaine', 'The week has seven days and today is Monday.', ['La semaine a sept jours et aujourd\'hui, c\'est lundi.', 'La semaine a sept jours et aujourd\'hui c\'est lundi.'], [Kit::word('la semaine', 'semaine'), Kit::word('le jour', 'jours'), Kit::word('aujourd\'hui'), Kit::word('lundi')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.une-pile', 'Il est une ___ pile, Marie.', 'It is one o\'clock on the dot, Marie.', 'heure', Kit::form('heure', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.onze', 'Il ___ onze heures pile, Marie.', 'It is eleven o\'clock on the dot, Marie.', 'est', Kit::form('est'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.douze-demie', 'Demain, je suis ici à onze heures et demie.', 'Tomorrow I am here at half past eleven.', [Kit::word('demain'), Kit::word('et demie', 'demie'), Kit::form('à', true)], 'dictation', $set, homophoneNote: self::A_ET),
            Kit::listenType($stage, 'check.b.listen_type.semaine', 'La semaine a sept jours, de lundi à dimanche.', 'The week has seven days, from Monday to Sunday.', [Kit::word('la semaine', 'semaine'), Kit::word('le jour', 'jours'), Kit::word('lundi'), Kit::word('dimanche')], 'dictation', $set, homophoneNote: 'A (has) and à (to, at) sound the same. Here a is the verb in la semaine a and à is the word for to.'),
            Kit::listenType($stage, 'check.b.listen_type.une-ou', 'Il est une heure pile ou une heure et demie ?', 'Is it one o\'clock on the dot or half past one?', [Kit::word('pile'), Kit::word('et demie', 'demie'), Kit::word('l\'heure', 'heure'), Kit::form('est', true)], 'dictation', $set, homophoneNote: self::EST_OU_ET),
        ];
    }
}
