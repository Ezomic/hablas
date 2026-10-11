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

final class TellingTheTimeAndDates implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'telling-the-time-and-dates';
    }

    public function words(): array
    {
        return [
            new WordData('l\'ora', cue: 'time (on the clock) or hour', forms: ['ora', 'ore', 'l\'una'], note: 'L\'ora is the time of day on the clock, and also one hour. The plural is le ore.'),
            new WordData('l\'orologio', cue: 'clock or watch', forms: ['orologio'], note: 'L\'orologio is both a wall clock and a watch.'),
            new WordData('il giorno', cue: 'day', forms: ['giorni'], note: 'Giorno is masculine: il giorno, i giorni.'),
            new WordData('la settimana', cue: 'week'),
            new WordData('oggi', cue: 'today'),
            new WordData('domani', cue: 'tomorrow (not the morning)', note: 'Domani means tomorrow. The morning is la mattina, a different word.'),
            new WordData('lunedì', cue: 'Monday', note: 'Days of the week have no capital letter in Italian. Lunedì means on Monday.'),
            new WordData('domenica', cue: 'Sunday', note: 'Days of the week have no capital letter in Italian. Domenica means on Sunday.'),
            new WordData('in punto', cue: 'on the dot (exactly, for the hour)'),
            new WordData('e mezza', cue: 'half past', note: 'Italians also say e mezzo; this course uses e mezza.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'È l\'una. Sono le due.', 'english' => 'It is one o\'clock. It is two o\'clock.'],
            ['text' => 'Vado alle tre.', 'english' => 'I am going at three.'],
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
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';
        $ee = 'È (is) sounds close to e (and); the accent is the written difference. Here it is è, the verb is.';

        return [
            Kit::gap($stage, 'sentences.choose_gap.e-una', '___ l\'una.', ['È', 'Sono', 'Sei'], 'È', Kit::form('È', true), 'L\'una is just one hour, so the time uses è. Sono means I am or they are.', 'choose', 'It is one o\'clock.'),
            Kit::gap($stage, 'sentences.choose_gap.mezza', 'Sono le tre e ___.', ['mezza', 'ora', 'giorno'], 'mezza', Kit::word('e mezza', 'mezza'), 'E mezza means half past. Ora and giorno are not used to add half an hour.', 'choose', 'It is half past three.'),
            Kit::gap($stage, 'sentences.choose_gap.lunedi', 'Oggi è ___.', ['lunedì', 'settimana', 'orologio'], 'lunedì', Kit::word('lunedì'), 'Lunedì is a day of the week. Settimana and orologio are not days.', 'choose', 'Today is Monday.'),
            Kit::gap($stage, 'sentences.choose_gap.sono-due', '___ le due.', ['Sono', 'È', 'Sei'], 'Sono', Kit::form('Sono', true), 'Le due is plural, so the time uses sono. Sei means you are.', 'choose', 'It is two o\'clock.'),
            Kit::gap($stage, 'sentences.choose_gap.alle', 'Vado ___ cinque.', ['alle', 'alla', 'le'], 'alle', Kit::form('alle', true), 'To say at five, use alle with a plural hour. Alla is singular. For one o\'clock the form is all\'una, and every other hour uses alle.', 'choose', 'I go at five.'),
            Kit::gap($stage, 'sentences.choose_gap.domani', 'Oggi è domenica. ___ è lunedì.', ['Domani', 'Settimana', 'Orologio'], 'Domani', Kit::word('domani'), 'Domani means tomorrow, the day after today. Settimana and orologio are not days.', 'choose', 'Today is Sunday. Tomorrow is Monday.'),

            Kit::typeGap($stage, 'sentences.type_gap.punto', 'Sono le quattro in ___.', 'It is four o\'clock on the dot.', 'punto', Kit::word('in punto', 'punto')),
            Kit::typeGap($stage, 'sentences.type_gap.sono-sei', '___ le sei.', 'It is six o\'clock.', 'Sono', Kit::form('Sono', true), 'Le sei is plural, so the time uses sono.'),
            Kit::typeGap($stage, 'sentences.type_gap.ora', 'Anna, che ___ è?', 'Anna, what time is it?', 'ora', Kit::word('l\'ora', 'ora')),
            Kit::typeGap($stage, 'sentences.type_gap.orologio', 'Il mio ___ è qui.', 'My watch is here.', 'orologio', Kit::word('l\'orologio', 'orologio')),
            Kit::typeGap($stage, 'sentences.type_gap.giorno', 'Che ___ è oggi? È domenica.', 'What day is it today? It is Sunday.', 'giorno', Kit::word('il giorno', 'giorno')),

            Kit::translate($stage, 'sentences.translate.una-in-punto', 'It is exactly one o\'clock.', ['È l\'una in punto.'], [Kit::word('in punto'), Kit::form('È')]),
            Kit::translate($stage, 'sentences.translate.ora', 'What time is it?', ['Che ora è?', 'Che ore sono?'], [Kit::word('l\'ora', 'ora', ['ore'])]),
            Kit::translate($stage, 'sentences.translate.due-mezza', 'It is half past two.', ['Sono le due e mezza.'], [Kit::word('e mezza'), Kit::form('Sono')]),

            Kit::build($stage, 'sentences.build.vado-sei', 'I go at six.', 'Vado alle sei.', ['alla'], [Kit::form('alle')]),
            Kit::build($stage, 'sentences.build.domani-lunedi', 'Tomorrow is Monday.', 'Domani è lunedì.', ['sono'], [Kit::word('domani'), Kit::word('lunedì'), Kit::form('è')]),
            Kit::build($stage, 'sentences.build.sette-in-punto', 'It is seven o\'clock on the dot.', 'Sono le sette in punto.', ['è'], [Kit::word('in punto'), Kit::form('Sono')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.una-mezza', 'È l\'una e mezza.', ['It is half past one.', 'It is one o\'clock.', 'It is half past two.', 'It is two o\'clock.'], 'It is half past one.', [Kit::word('e mezza'), Kit::form('È', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.oggi-domenica', 'Oggi è domenica.', ['Today is Sunday.', 'Tomorrow is Sunday.', 'Today is Monday.', 'Tomorrow is Monday.'], 'Today is Sunday.', [Kit::word('oggi'), Kit::word('domenica')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.settimana', 'La settimana ha sette giorni.', ['The week has seven days.', 'The week has five days.', 'The day has seven weeks.', 'The day has seven hours.'], 'The week has seven days.', [Kit::word('la settimana', 'settimana'), Kit::word('il giorno', 'giorni')]),
            Kit::listenType($stage, 'sentences.listen_type.orologio', 'Il mio orologio è qui, Anna.', 'My watch is here, Anna.', [Kit::word('l\'orologio', 'orologio')], homophoneNote: $ee),
            Kit::listenType($stage, 'sentences.listen_type.nove-mezza', 'Sono le nove e mezza.', 'It is half past nine.', [Kit::word('e mezza'), Kit::form('Sono')], homophoneNote: $e),
            Kit::listenType($stage, 'sentences.listen_type.giorno-oggi', 'Che giorno è oggi, Anna?', 'What day is it today, Anna?', [Kit::word('il giorno', 'giorno'), Kit::word('oggi')], homophoneNote: $ee),
            Kit::listenType($stage, 'sentences.listen_type.ora-una', 'Che ora è? È l\'una.', 'What time is it? It is one o\'clock.', [Kit::word('l\'ora', 'ora'), Kit::form('È')], homophoneNote: $ee),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.cinque-mezza', 'Sono le cinque e mezza.', 'It is half past five.', [Kit::word('e mezza'), Kit::form('Sono')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.oggi-domani', 'Oggi è domenica. Domani è lunedì.', 'Today is Sunday. Tomorrow is Monday.', [Kit::word('oggi'), Kit::word('domani'), Kit::word('domenica'), Kit::word('lunedì')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.otto-in-punto', 'Vado alle otto in punto.', 'I go at eight on the dot.', [Kit::word('in punto'), Kit::form('alle')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ora-orologio', 'Il mio orologio è qui. Che ora è?', 'My watch is here. What time is it?', [Kit::word('l\'ora', 'ora'), Kit::word('l\'orologio', 'orologio')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.una-mezza', 'È l\'una e mezza?', 'Is it half past one?', [['sì', 'no', 'è', 'sono'], ['mezza']], 'Sì, è l\'una e mezza.', [Kit::word('e mezza')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.domani', 'Oggi è domenica. Che giorno è domani?', 'Today is Sunday. What day is tomorrow?', [['domani', 'è'], ['lunedì']], 'Domani è lunedì.', [Kit::word('domani'), Kit::word('lunedì')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.settimana', 'La settimana ha sette giorni?', 'Does the week have seven days?', [['sì', 'no', 'ha'], ['settimana', 'sette', 'giorni']], 'Sì, la settimana ha sette giorni.', [Kit::word('la settimana', 'settimana'), Kit::word('il giorno', 'giorni')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';
        $ee = 'È (is) sounds close to e (and); the accent is the written difference. Here it is è, the verb is.';

        return [
            Kit::readPassage($stage, 'task.read_passage.ora', 'Read the conversation.', [
                Kit::line('Anna', 'Paolo, che ora è?'),
                Kit::line('Paolo', 'Sono le due e mezza.'),
                Kit::line('Anna', 'Oggi vado alle tre. Domani vado alle cinque.'),
                Kit::line('Paolo', 'Ciao, Anna.'),
            ], [
                Kit::question('What time is it?', ['Half past two', 'Two o\'clock', 'Half past three'], 'Half past two'),
                Kit::question('At what time does Anna go today?', ['At three', 'At five', 'At two'], 'At three'),
                Kit::question('At what time does Anna go tomorrow?', ['At five', 'At three', 'At two'], 'At five'),
            ], [Kit::word('l\'ora', 'ora'), Kit::word('e mezza'), Kit::word('oggi'), Kit::word('domani')], 'read'),
            Kit::gap($stage, 'task.choose_gap.giorni', 'Una settimana ha sette ___.', ['giorni', 'ora', 'orologio'], 'giorni', Kit::word('il giorno', 'giorni'), 'Sette means more than one, so we need the plural: giorni.', 'read', 'A week has seven days.'),
            Kit::gap($stage, 'task.choose_gap.in-punto', 'Sono le cinque ___ punto.', ['in', 'di', 'con'], 'in', Kit::word('in punto', 'in'), 'In punto means on the dot, exactly. The words di and con do not fit here.', 'read', 'It is five o\'clock on the dot.'),

            Kit::transform($stage, 'task.transform.tre', 'Change it to three o\'clock.', 'Sono le due in punto.', ['Sono le tre in punto.'], [Kit::word('in punto'), Kit::form('Sono')]),
            Kit::transform($stage, 'task.transform.una-mezza', 'Change it to half past one.', 'Sono le tre e mezza.', ['È l\'una e mezza.'], [Kit::word('e mezza'), Kit::form('È')]),
            Kit::transform($stage, 'task.transform.domani', 'Say it for tomorrow.', 'Oggi è lunedì.', ['Domani è lunedì.'], [Kit::word('domani'), Kit::word('lunedì')]),
            Kit::writeGuided($stage, 'task.write_guided.oggi', 'Say that today is Monday and that it is two o\'clock exactly.', ['oggi', 'lunedì', 'sono', 'in punto'], 'Oggi è lunedì. Sono le due in punto.', [
                ['forms' => ['oggi'], 'term' => 'oggi'],
                ['forms' => ['lunedì'], 'term' => 'lunedì'],
                ['forms' => ['punto'], 'term' => 'in punto'],
            ], [Kit::word('oggi'), Kit::word('lunedì'), Kit::word('in punto')]),
            Kit::writeGuided($stage, 'task.write_guided.orologio', 'Say that the watch is here and that it is half past four.', ['orologio', 'qui', 'sono', 'e mezza'], 'L\'orologio è qui. Sono le quattro e mezza.', [
                ['forms' => ['orologio'], 'term' => 'l\'orologio'],
                ['forms' => ['mezza'], 'term' => 'e mezza'],
            ], [Kit::word('l\'orologio', 'orologio'), Kit::word('e mezza')]),
            Kit::build($stage, 'task.build.domani-mezza', 'Tomorrow I go at half past nine.', 'Domani vado alle nove e mezza.', ['alla', 'è'], [Kit::word('domani'), Kit::word('e mezza'), Kit::form('alle')], 'write'),
            Kit::build($stage, 'task.build.sette-giorni', 'The week has seven days.', 'La settimana ha sette giorni.', ['hanno', 'sono'], [Kit::word('la settimana', 'settimana'), Kit::word('il giorno', 'giorni')], 'write'),
            Kit::build($stage, 'task.build.ora-marta', 'What time is it, Marta?', 'Che ora è, Marta?', ['ore', 'la'], [Kit::word('l\'ora', 'ora'), Kit::form('è')], 'write'),
            Kit::translate($stage, 'task.translate.giorni-settimana', 'How many days does the week have?', ['Quanti giorni ha la settimana?', 'Quanti giorni ha una settimana?', 'Quanti giorni ci sono in una settimana?'], [Kit::word('la settimana', 'settimana'), Kit::word('il giorno', 'giorni')], 'write'),
            Kit::translate($stage, 'task.translate.a-che-ora', 'At what time are you going, Paolo? (informal you)', ['A che ora vai, Paolo?', 'Paolo, a che ora vai?'], [Kit::word('l\'ora', 'ora')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.giorno', [
                Kit::line('Paolo', 'Che giorno è oggi, Anna?'),
                Kit::line('Anna', 'È domenica. Domani è lunedì.'),
                Kit::line('Paolo', 'E a che ora vai?'),
                Kit::line('Anna', 'Vado alle otto in punto.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What day is tomorrow?', ['Monday', 'Sunday', 'Friday'], 'Monday'),
                Kit::question('At what time does Anna go?', ['At eight on the dot', 'At half past eight', 'At nine'], 'At eight on the dot'),
            ], [
                Kit::question('Who asks the questions?', ['Paolo', 'Anna', 'Nobody'], 'Paolo'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is tomorrow Monday?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('il giorno', 'giorno'), Kit::word('oggi'), Kit::word('domani'), Kit::word('domenica'), Kit::word('lunedì'), Kit::word('in punto'), Kit::word('l\'ora', 'ora')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.orologio', 'L\'orologio di Paolo è qui.', 'Paolo\'s watch is here.', [Kit::word('l\'orologio')], 'listen', homophoneNote: $ee),
            Kit::listenType($stage, 'task.listen_type.dieci-mezza', 'Oggi vado alle dieci e mezza.', 'Today I go at half past ten.', [Kit::word('oggi'), Kit::word('e mezza'), Kit::form('alle')], 'listen', homophoneNote: $e),
            Kit::listenType($stage, 'task.listen_type.domenica', 'Oggi è domenica. Che ora è, Anna?', 'Today is Sunday. What time is it, Anna?', [Kit::word('oggi'), Kit::word('domenica'), Kit::word('l\'ora', 'ora')], 'listen', homophoneNote: $ee),

            Kit::speakAnswer($stage, 'task.speak_answer.una-in-punto', 'È l\'una in punto?', 'Is it one o\'clock exactly?', [['sì', 'no', 'è', 'sono'], ['punto']], 'Sì, è l\'una in punto.', [Kit::word('in punto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.alle', 'A che ora vai oggi?', 'At what time are you going today? (informal you)', [['vado', 'alle'], ['due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci', 'undici', 'dodici', 'una']], 'Vado alle tre.', [Kit::form('alle')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.oggi-lunedi', 'Oggi è lunedì?', 'Is today Monday?', [['sì', 'no', 'è', 'oggi'], ['lunedì', 'domenica']], 'No, oggi è domenica.', [Kit::word('oggi'), Kit::word('lunedì')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.orologio', 'Hai un orologio?', 'Do you have a watch? (informal you)', [['sì', 'no', 'ho'], ['orologio']], 'Sì, ho un orologio.', [Kit::word('l\'orologio', 'orologio')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.tre-mezza', 'Oggi vado alle tre e mezza.', 'Today I go at half past three.', [Kit::word('oggi'), Kit::word('e mezza'), Kit::form('alle')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.giorno', 'Che giorno è domani? È lunedì.', 'What day is tomorrow? It is Monday.', [Kit::word('il giorno', 'giorno'), Kit::word('domani'), Kit::word('lunedì')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';
        $ee = 'È (is) sounds close to e (and); the accent is the written difference. Here it is è, the verb is.';

        return [
            Kit::translate($stage, 'check.a.translate.quattro-mezza', 'It is half past four.', ['Sono le quattro e mezza.'], [Kit::word('e mezza'), Kit::form('Sono')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.domani', 'Tomorrow is not Monday.', ['Domani non è lunedì.'], [Kit::word('domani'), Kit::word('lunedì'), Kit::form('è')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.settimana', 'A week has seven days, not five.', ['Una settimana ha sette giorni, non cinque.', 'La settimana ha sette giorni, non cinque.'], [Kit::word('la settimana', 'settimana'), Kit::word('il giorno', 'giorni')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.nove', 'I go at nine on the dot.', ['Vado alle nove in punto.', 'Io vado alle nove in punto.'], [Kit::word('in punto'), Kit::form('alle', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sette', 'Marta va ___ sette.', 'Marta goes at seven.', 'alle', Kit::form('alle'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.una-venti', '___ le tre e venti.', 'It is twenty past three.', 'È', Kit::form('È', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.otto-mezza', 'Sono le otto e mezza.', 'It is half past eight.', [Kit::word('e mezza'), Kit::form('Sono')], 'dictation', $set, homophoneNote: $e),
            Kit::listenType($stage, 'check.a.listen_type.orologio', 'L\'orologio è qui, Marta.', 'The watch is here, Marta.', [Kit::word('l\'orologio')], 'dictation', $set, homophoneNote: $ee),
            Kit::listenType($stage, 'check.a.listen_type.oggi', 'Oggi è domenica. Che ora è, Luca?', 'Today is Sunday. What time is it, Luca?', [Kit::word('oggi'), Kit::word('domenica'), Kit::word('l\'ora', 'ora')], 'dictation', $set, homophoneNote: $ee),
            Kit::listenPassage($stage, 'check.a.listen_passage.ora', [
                Kit::line('Anna', 'Luca, che ora è?'),
                Kit::line('Luca', 'Sono le sei e mezza.'),
                Kit::line('Anna', 'Domani vado alle nove in punto.'),
            ], [
                Kit::question('What time is it?', ['Half past six', 'Six o\'clock', 'Half past nine'], 'Half past six'),
                Kit::question('At what time does Anna go tomorrow?', ['At nine on the dot', 'At six', 'At half past nine'], 'At nine on the dot'),
                Kit::question('When does Anna go?', ['Tomorrow', 'Today', 'On Sunday'], 'Tomorrow'),
            ], [
                Kit::question('Who answers the question?', ['Luca', 'Anna', 'Nobody'], 'Luca'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is it six o\'clock on the dot?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
            ], [Kit::word('l\'ora', 'ora'), Kit::word('e mezza'), Kit::word('domani'), Kit::word('in punto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.giorno', 'Read the conversation.', [
                Kit::line('Marta', 'Luca, che giorno è oggi?'),
                Kit::line('Luca', 'Oggi è domenica e domani è lunedì.'),
                Kit::line('Marta', 'Grazie. E che ora è?'),
                Kit::line('Luca', 'Sono le cinque.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What does Marta ask at the end?', ['What time it is', 'What day it is', 'Where the watch is'], 'What time it is'),
            ], [Kit::word('il giorno', 'giorno'), Kit::word('oggi'), Kit::word('domenica'), Kit::word('lunedì'), Kit::word('l\'ora', 'ora')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.orologio', 'Il tuo orologio è qui o lì?', 'Is your watch here or there?', [['orologio', 'è'], ['qui', 'lì']], 'Il mio orologio è qui.', [Kit::word('l\'orologio', 'orologio')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.oggi', 'Domani è lunedì. Che giorno è oggi?', 'Tomorrow is Monday. What day is it today?', [['oggi', 'è'], ['domenica']], 'Oggi è domenica.', [Kit::word('oggi'), Kit::word('domenica')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.settimana', 'Quanti giorni ha una settimana?', 'How many days does a week have?', [['settimana', 'ha', 'sette', 'giorni'], ['sette']], 'Ha sette giorni.', [Kit::word('la settimana', 'settimana')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $e = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';
        $ee = 'È (is) sounds close to e (and); the accent is the written difference. Here it is è, the verb is.';
        $o = 'O (or) sounds the same as ho (I have), which is written with an h. Here it is o, meaning or.';

        return [
            Kit::translate($stage, 'check.b.translate.undici', 'My watch is here. It is eleven o\'clock on the dot.', ['Il mio orologio è qui. Sono le undici in punto.'], [Kit::word('in punto'), Kit::word('l\'orologio', 'orologio'), Kit::form('Sono')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ora', 'What time is it? My watch is here.', ['Che ora è? Il mio orologio è qui.', 'Che ore sono? Il mio orologio è qui.'], [Kit::word('l\'ora', 'ora', ['ore']), Kit::word('l\'orologio', 'orologio'), Kit::form('è', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.domani', 'Tomorrow is Sunday.', ['Domani è domenica.'], [Kit::word('domani'), Kit::word('domenica'), Kit::form('È')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.settimana', 'The week has seven days and today is Monday.', ['La settimana ha sette giorni e oggi è lunedì.'], [Kit::word('la settimana', 'settimana'), Kit::word('il giorno', 'giorni'), Kit::word('oggi'), Kit::word('lunedì')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.settimana', 'Luca, la ___ ha sette giorni.', 'Luca, the week has seven days.', 'settimana', Kit::word('la settimana', 'settimana'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ora', 'A che ___ vai, Luca?', 'At what time are you going, Luca?', 'ora', Kit::word('l\'ora', 'ora'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.dodici-mezza', 'Domani vado alle dodici e mezza.', 'Tomorrow I go at half past twelve.', [Kit::word('domani'), Kit::word('e mezza'), Kit::form('alle', true)], 'dictation', $set, homophoneNote: $e),
            Kit::listenType($stage, 'check.b.listen_type.lunedi', 'Che giorno è oggi, lunedì o domenica?', 'What day is it today, Monday or Sunday?', [Kit::word('il giorno', 'giorno'), Kit::word('oggi'), Kit::word('lunedì'), Kit::word('domenica'), Kit::form('è')], 'dictation', $set, homophoneNote: $ee.' '.$o),
            Kit::listenType($stage, 'check.b.listen_type.dodici', 'Sono le dodici in punto o le dodici e mezza?', 'Is it twelve o\'clock on the dot or half past twelve?', [Kit::word('in punto'), Kit::word('e mezza'), Kit::form('Sono', true)], 'dictation', $set, homophoneNote: $o.' '.$e),
        ];
    }
}
