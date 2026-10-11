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

final class TakingTheTrainOrBus implements UnitContent
{
    private const TIME = 'a che ora parte?';

    private const NUMBERS = ['uno', 'una', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci', 'undici', 'dodici'];

    private const NOTE_E = 'È (is) and e (and) sound close. In writing, the accent on è is what tells them apart.';

    private const NOTE_E_ONLY = 'E (and) is written without an accent, unlike è (is), and the two sound close.';

    private const NOTE_A = 'A (at) sounds like ha (has), but here it is the little word a, written without an h.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'taking-the-train-or-bus';
    }

    public function words(): array
    {
        return [
            new WordData('il treno', cue: 'train', forms: ['i treni', 'sul treno']),
            new WordData('l\'autobus', cue: 'bus', forms: ['gli autobus', 'dell\'autobus']),
            new WordData('la stazione', cue: 'station', forms: ['le stazioni', 'andare']),
            new WordData('la fermata', cue: 'stop (where a bus or tram stops)', forms: ['le fermate', 'dalla fermata']),
            new WordData('il binario', cue: 'platform (at a train station)', forms: ['i binari', 'dal binario']),
            new WordData('l\'orario', cue: 'timetable', forms: ['gli orari']),
            new WordData('il posto', cue: 'seat', forms: ['i posti']),
            new WordData('di andata e ritorno', cue: 'return (as in a return ticket)', forms: ['biglietto', 'biglietti'], note: 'You say un biglietto di andata e ritorno for a return ticket. A single ticket is un biglietto di sola andata.'),
            new WordData(self::TIME, cue: 'what time does it leave?', accepted: ['a che ora parte']),
            new WordData('la linea', cue: 'line (of a bus or metro)', forms: ['le linee', 'della linea']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Voglio un biglietto.', 'english' => 'I want a ticket.'],
            ['text' => 'Voglio andare alla stazione.', 'english' => 'I want to go to the station.'],
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
        $time = Kit::word(self::TIME, 'a che ora parte');

        return [
            Kit::gap($stage, 'sentences.choose_gap.voglio-biglietto', 'Io ___ un biglietto.', ['voglio', 'vuoi', 'vuole'], 'voglio', Kit::form('voglio'), 'Io goes with voglio. Vuoi is for tu and vuole is for lui or lei.', 'choose', glosses: ['voglio' => 'I want', 'vuoi' => 'you want', 'vuole' => 'he or she wants']),
            Kit::gap($stage, 'sentences.choose_gap.marta-vuole', 'Marta ___ andare alla stazione.', ['vuole', 'voglio', 'vogliamo'], 'vuole', Kit::form('vuole'), 'Marta is one person, so the verb is vuole. Voglio is for io and vogliamo is for noi.', 'choose', glosses: ['vuole' => 'he or she wants', 'voglio' => 'I want', 'vogliamo' => 'we want']),
            Kit::gap($stage, 'sentences.choose_gap.binario', 'Voglio andare al ___ tre.', ['binario', 'fermata', 'linea'], 'binario', Kit::word('il binario', 'binario'), 'Al is a + il, so it needs a masculine word. Binario is masculine, while fermata and linea are feminine.', 'choose', english: 'I want to go to platform three.', glosses: ['fermata' => 'stop', 'linea' => 'line']),
            Kit::gap($stage, 'sentences.choose_gap.linea', "L'autobus della ___ due.", ['linea', 'binario', 'posto'], 'linea', Kit::word('la linea', 'linea'), 'Della goes with a feminine word, and linea is feminine. Binario and posto are masculine.', 'choose', english: 'The line two bus.', glosses: ['binario' => 'platform', 'posto' => 'seat']),
            Kit::gap($stage, 'sentences.choose_gap.andata-ritorno', 'Voglio un biglietto ___.', ['di andata e ritorno', 'per andata e ritorno', 'con andata e ritorno'], 'di andata e ritorno', Kit::word('di andata e ritorno'), 'The fixed phrase for a return ticket is di andata e ritorno. Per and con do not work here.', 'choose', english: 'I want a return ticket.', glosses: ['per andata e ritorno' => 'for going and returning', 'con andata e ritorno' => 'with going and returning']),
            Kit::gap($stage, 'sentences.choose_gap.andare-fermata', 'Io ___ andare alla fermata.', ['voglio', 'ho', 'sto'], 'voglio', Kit::form('voglio', true), 'Voglio + andare means I want to go. Ho and sto do not work straight before andare.', 'choose', english: 'I want to go to the stop.', glosses: ['ho' => 'I have', 'sto' => 'I am']),

            Kit::typeGap($stage, 'sentences.type_gap.vogliamo', 'Noi ___ andare alla stazione.', 'We want to go to the station.', 'vogliamo', Kit::form('vogliamo'), 'Noi goes with vogliamo. Note the gl, just as in voglio.'),
            Kit::typeGap($stage, 'sentences.type_gap.vogliono', 'Loro ___ un posto.', 'They want a seat.', 'vogliono', Kit::form('vogliono'), 'Loro means more than one person, so the verb ends in -ono, like sono.', glosses: ['vogliono' => 'they want']),
            Kit::typeGap($stage, 'sentences.type_gap.vuoi', "Tu ___ l'orario.", 'You want the timetable (informal you).', 'vuoi', Kit::form('vuoi', true), 'Tu goes with vuoi. Vuole is for lui, lei or Lei.', glosses: ['vuoi' => 'you want']),
            Kit::typeGap($stage, 'sentences.type_gap.fermata', 'La ___ è qui.', 'The bus stop is here.', 'fermata', Kit::word('la fermata', 'fermata'), 'A stop where buses stop is a fermata.'),
            Kit::typeGap($stage, 'sentences.type_gap.orario', 'Ecco ___ del treno.', 'Here is the timetable of the train.', "l'orario", Kit::word("l'orario"), "The timetable is l'orario. Orario starts with a vowel, so the article is l'."),

            Kit::translate($stage, 'sentences.translate.andata-ritorno', 'I want a return ticket.', ['Voglio un biglietto di andata e ritorno.'], [Kit::word('di andata e ritorno'), Kit::form('voglio')]),
            Kit::translate($stage, 'sentences.translate.posto', 'We want a seat.', ['Vogliamo un posto.', 'Noi vogliamo un posto.'], [Kit::word('il posto', 'posto'), Kit::form('vogliamo')]),
            Kit::translate($stage, 'sentences.translate.stazione', 'I want to go to the station.', ['Voglio andare alla stazione.', 'Io voglio andare alla stazione.'], [Kit::word('la stazione', 'stazione'), Kit::form('voglio')]),

            Kit::build($stage, 'sentences.build.orario', 'Anna wants the timetable.', "Anna vuole l'orario.", ['vogliono'], [Kit::word("l'orario"), Kit::form('vuole')]),
            Kit::build($stage, 'sentences.build.linea', 'I want the line five bus.', "Voglio l'autobus della linea cinque.", ['vuoi'], [Kit::word("l'autobus"), Kit::word('la linea', 'linea'), Kit::form('voglio')], glosses: ['vuoi' => 'you want']),
            Kit::build($stage, 'sentences.build.treno-binario', 'The train is at platform three.', 'Il treno è al binario tre.', ['la'], [Kit::word('il treno', 'treno'), Kit::word('il binario', 'binario')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.andata-ritorno', 'Voglio un biglietto di andata e ritorno.', ['I want a return ticket.', 'I want a train ticket.', 'I want a seat.', 'I want the timetable.'], 'I want a return ticket.', [Kit::word('di andata e ritorno'), Kit::form('voglio')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ora', 'A che ora parte il treno?', ['What time does the train leave?', 'Where does the train leave from?', 'Which train is it?', 'Is the train here?'], 'What time does the train leave?', [$time, Kit::word('il treno', 'treno')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.fermata', 'Voglio andare alla fermata.', ['I want to go to the stop.', 'I want to go to the station.', 'I want to go to the platform.', 'I want to go home.'], 'I want to go to the stop.', [Kit::word('la fermata', 'fermata'), Kit::form('voglio')]),
            Kit::listenType($stage, 'sentences.listen_type.posto', 'Vogliamo un posto.', 'We want a seat.', [Kit::word('il posto', 'posto'), Kit::form('vogliamo')]),
            Kit::listenType($stage, 'sentences.listen_type.autobus', "L'autobus è alla fermata.", 'The bus is at the stop.', [Kit::word("l'autobus"), Kit::word('la fermata', 'fermata')], homophoneNote: self::NOTE_E),
            Kit::listenType($stage, 'sentences.listen_type.orario', "L'orario è qui.", 'The timetable is here.', [Kit::word("l'orario")], homophoneNote: self::NOTE_E),
            Kit::listenType($stage, 'sentences.listen_type.vogliono', 'Vogliono andare alla stazione.', 'They want to go to the station.', [Kit::word('la stazione', 'stazione'), Kit::form('vogliono')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.andata-ritorno', 'Un biglietto di andata e ritorno.', 'A return ticket.', [Kit::word('di andata e ritorno')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ora', "A che ora parte l'autobus?", 'What time does the bus leave?', [$time, Kit::word("l'autobus")]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.binario', 'Vogliamo andare al binario.', 'We want to go to the platform.', [Kit::word('il binario', 'binario'), Kit::form('vogliamo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.orario', "L'orario è alla stazione.", 'The timetable is at the station.', [Kit::word("l'orario"), Kit::word('la stazione', 'stazione')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.posto', 'Vuoi un posto?', 'Do you want a seat? (informal you)', [['sì', 'no'], ['voglio', 'posto']], 'Sì, voglio un posto.', [Kit::word('il posto', 'posto'), Kit::form('voglio')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.fermata', 'La fermata è qui?', 'Is the stop here?', [['sì', 'no', 'è'], ['fermata', 'qui', 'lì']], 'Sì, la fermata è qui.', [Kit::word('la fermata', 'fermata')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.binario', 'Da quale binario parte il treno?', 'Which platform does the train leave from?', [['parte', 'binario', 'treno'], self::NUMBERS], 'Il treno parte dal binario tre.', [Kit::word('il binario', 'binario'), Kit::word('il treno', 'treno')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $time = Kit::word(self::TIME, 'a che ora parte');

        return [
            Kit::readPassage($stage, 'task.read_passage.biglietto', 'Read the conversation at the ticket desk.', [
                Kit::line('Paolo', 'Buonasera. Vorrei un biglietto di andata e ritorno.'),
                Kit::line('Impiegato', 'Va bene.'),
                Kit::line('Paolo', 'A che ora parte il treno?'),
                Kit::line('Impiegato', 'Parte alle cinque, dal binario tre.'),
                Kit::line('Paolo', 'Voglio un posto, per favore.'),
                Kit::line('Impiegato', 'Ecco il biglietto. Arrivederci.'),
            ], [
                Kit::question('What does Paolo ask for first?', ['A return ticket', 'A bus', 'The timetable'], 'A return ticket'),
                Kit::question('What time does the train leave?', ['At five', 'At three', 'At two'], 'At five'),
                Kit::question('Which platform does it leave from?', ['Platform three', 'Platform five', 'Platform two'], 'Platform three'),
            ], [Kit::word('di andata e ritorno'), Kit::word('il treno', 'treno'), $time, Kit::word('il binario', 'binario'), Kit::word('il posto', 'posto'), Kit::form('voglio')], 'read', glosses: ['impiegato' => 'clerk', 'vorrei' => 'I would like']),
            Kit::gap($stage, 'task.choose_gap.vogliono', 'Anna e Luca ___ andare in autobus.', ['vogliono', 'vuole', 'voglio'], 'vogliono', Kit::form('vogliono', true), 'Anna e Luca means two people, so the verb is vogliono.', 'read', english: 'Anna and Luca want to go by bus.', glosses: ['vogliono' => 'they want', 'vuole' => 'he or she wants', 'voglio' => 'I want']),
            Kit::gap($stage, 'task.choose_gap.orari', 'Voglio gli ___ della linea cinque.', ['orari', 'fermate', 'stazioni'], 'orari', Kit::word("l'orario", 'orari'), 'Gli is the plural of il and l\' for masculine words. Orari is masculine, while fermate and stazioni are feminine.', 'read', english: 'I want the timetables of line five.', glosses: ['fermate' => 'stops', 'stazioni' => 'stations']),

            Kit::transform($stage, 'task.transform.vogliamo', 'Change it to noi.', 'Voglio un posto.', ['Vogliamo un posto.', 'Noi vogliamo un posto.'], [Kit::word('il posto', 'posto'), Kit::form('vogliamo')]),
            Kit::transform($stage, 'task.transform.vogliono', 'Change it to loro.', 'Vuole andare alla stazione.', ['Vogliono andare alla stazione.', 'Loro vogliono andare alla stazione.'], [Kit::word('la stazione', 'stazione'), Kit::form('vogliono')]),
            Kit::transform($stage, 'task.transform.vuole', 'Say it about Anna.', "Voglio l'orario del treno.", ["Anna vuole l'orario del treno."], [Kit::word("l'orario"), Kit::word('il treno', 'treno'), Kit::form('vuole')]),
            Kit::writeGuided($stage, 'task.write_guided.biglietto', 'Say that you want a return ticket and a seat.', ['voglio', 'biglietto', 'di andata e ritorno', 'posto'], 'Voglio un biglietto di andata e ritorno. Voglio un posto.', [
                ['forms' => ['voglio'], 'term' => null],
                ['forms' => ['ritorno'], 'term' => 'di andata e ritorno'],
                ['forms' => ['posto'], 'term' => 'il posto'],
            ], [Kit::word('di andata e ritorno'), Kit::word('il posto', 'posto'), Kit::form('voglio')]),
            Kit::writeGuided($stage, 'task.write_guided.orario', 'Ask what time the bus leaves and say that you want the timetable.', ['voglio', 'a che ora parte', "l'autobus", "l'orario"], "A che ora parte l'autobus? Voglio l'orario.", [
                ['forms' => ['parte'], 'term' => self::TIME],
                ['forms' => ["l'autobus", 'autobus', 'bus'], 'term' => "l'autobus"],
                ['forms' => ["l'orario"], 'term' => "l'orario"],
                ['forms' => ['voglio'], 'term' => null],
            ], [$time, Kit::word("l'autobus"), Kit::word("l'orario"), Kit::form('voglio')]),
            Kit::build($stage, 'task.build.fermata', 'We want to go to the bus stop.', 'Vogliamo andare alla fermata.', ['voglio', 'vogliono'], [Kit::word('la fermata', 'fermata'), Kit::form('vogliamo')], 'write', ['voglio' => 'I want', 'vogliono' => 'they want']),
            Kit::build($stage, 'task.build.linea', 'The line five bus leaves from the stop.', "L'autobus della linea cinque parte dalla fermata.", ['binario', 'al'], [Kit::word("l'autobus"), Kit::word('la linea', 'linea'), Kit::word('la fermata', 'fermata')], 'write', ['binario' => 'platform']),
            Kit::build($stage, 'task.build.posto', 'I want a seat on the train, please.', 'Voglio un posto in treno, per favore.', ['vuoi', 'una'], [Kit::word('il posto', 'posto'), Kit::word('il treno', 'treno'), Kit::form('voglio')], 'write', ['vuoi' => 'you want']),
            Kit::translate($stage, 'task.translate.vuoi', 'Do you want a return ticket? (informal you)', ['Vuoi un biglietto di andata e ritorno?', 'Tu vuoi un biglietto di andata e ritorno?'], [Kit::word('di andata e ritorno'), Kit::form('vuoi')], 'write'),
            Kit::translate($stage, 'task.translate.ora', 'What time does the bus leave?', ["A che ora parte l'autobus?", 'A che ora parte il bus?'], [$time, Kit::word("l'autobus", null, ['bus'])], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.fermata', [
                Kit::line('Anna', 'Buonasera. La fermata della linea cinque è qui?'),
                Kit::line('Marta', 'Sì, è qui.'),
                Kit::line('Anna', "A che ora parte l'autobus?"),
                Kit::line('Marta', "Ecco l'orario. Parte alle due."),
                Kit::line('Anna', 'Grazie. Voglio andare alla stazione.'),
            ], [
                Kit::question('What does Anna ask about first?', ['Whether the bus stop is here', 'The price of a ticket', 'A seat'], 'Whether the bus stop is here'),
                Kit::question('What does Marta show Anna?', ['The timetable', 'A ticket', 'A map'], 'The timetable'),
                Kit::question('Where does Anna want to go?', ['To the station', 'To the platform', 'Home'], 'To the station'),
            ], [
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the bus leave?', ['At two', 'At five', 'At three'], 'At two'),
                Kit::question('Does Anna say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('la fermata', 'fermata'), Kit::word('la linea', 'linea'), $time, Kit::word("l'autobus"), Kit::word("l'orario"), Kit::word('la stazione', 'stazione'), Kit::form('voglio')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.treno', 'Il treno parte dal binario due.', 'The train leaves from platform two.', [Kit::word('il treno', 'treno'), Kit::word('il binario', 'binario')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.volete', 'Voi volete andare alla stazione.', 'You (all) want to go to the station.', [Kit::word('la stazione', 'stazione'), Kit::form('volete')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.vuole', 'Anna vuole un biglietto di andata e ritorno.', 'Anna wants a return ticket.', [Kit::word('di andata e ritorno'), Kit::form('vuole')], 'listen', homophoneNote: self::NOTE_E_ONLY),

            Kit::speakAnswer($stage, 'task.speak_answer.biglietto', 'Vuoi un biglietto di andata e ritorno?', 'Do you want a return ticket? (informal you)', [['sì', 'no'], ['voglio', 'biglietto', 'ritorno']], 'Sì, voglio un biglietto di andata e ritorno.', [Kit::word('di andata e ritorno'), Kit::form('voglio')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ora', 'A che ora parte il treno?', 'What time does the train leave?', [['parte', 'treno'], self::NUMBERS], 'Il treno parte alle cinque.', [$time, Kit::word('il treno', 'treno')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.linea', 'Quale linea vuoi?', 'Which line do you want? (informal you)', [['voglio', 'linea', 'autobus'], self::NUMBERS], 'Voglio la linea cinque.', [Kit::word('la linea', 'linea'), Kit::form('voglio')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.stazione', 'Dove vuoi andare? Alla stazione?', 'Where do you want to go? To the station? (informal you)', [['voglio', 'vado', 'sì', 'no'], ['stazione', 'fermata', 'binario']], 'Voglio andare alla stazione.', [Kit::word('la stazione', 'stazione'), Kit::form('vuoi')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.posto', 'Buonasera, voglio un posto, per favore.', 'Good evening, I want a seat, please.', [Kit::word('il posto', 'posto'), Kit::form('voglio')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.treno', 'Il treno parte dalla stazione alle due.', 'The train leaves from the station at two.', [Kit::word('il treno', 'treno'), Kit::word('la stazione', 'stazione')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.ora', 'What time does the train leave from the station?', ['A che ora parte il treno dalla stazione?'], [Kit::word(self::TIME, 'a che ora parte'), Kit::word('il treno', 'treno'), Kit::word('la stazione', 'stazione')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vuoi', 'Do you want a seat on the bus? (informal you)', ['Vuoi un posto in autobus?', 'Tu vuoi un posto in autobus?', "Vuoi un posto sull'autobus?", "Tu vuoi un posto sull'autobus?"], [Kit::word('il posto', 'posto'), Kit::word("l'autobus", 'autobus', ["sull'autobus"]), Kit::form('vuoi', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vogliamo', 'We want the timetable of line five.', ["Vogliamo l'orario della linea cinque.", "Noi vogliamo l'orario della linea cinque."], [Kit::word("l'orario"), Kit::word('la linea', 'linea'), Kit::form('vogliamo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vogliono', 'They want a return ticket.', ['Vogliono un biglietto di andata e ritorno.', 'Loro vogliono un biglietto di andata e ritorno.'], [Kit::word('di andata e ritorno'), Kit::form('vogliono')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vuole', 'Anna ___ andare alla stazione.', 'Anna wants to go to the station.', 'vuole', Kit::form('vuole'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vogliamo', 'Noi ___ un posto.', 'We want a seat.', 'vogliamo', Kit::form('vogliamo', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.treno', 'Il treno parte dal binario quattro.', 'The train leaves from platform four.', [Kit::word('il treno', 'treno'), Kit::word('il binario', 'binario')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.fermata', 'La fermata della linea due è qui.', 'The stop of line two is here.', [Kit::word('la fermata', 'fermata'), Kit::word('la linea', 'linea')], 'dictation', $set, homophoneNote: self::NOTE_E),
            Kit::listenType($stage, 'check.a.listen_type.posto', "Voglio un posto e l'orario, per favore.", 'I want a seat and the timetable, please.', [Kit::word('il posto', 'posto'), Kit::word("l'orario"), Kit::form('voglio')], 'dictation', $set, homophoneNote: self::NOTE_E_ONLY),
            Kit::listenPassage($stage, 'check.a.listen_passage.treno', [
                Kit::line('Luca', 'Buongiorno. A che ora parte il treno, per favore?'),
                Kit::line('Impiegato', 'Parte alle quattro, dal binario due.'),
                Kit::line('Luca', 'Il binario due è qui?'),
                Kit::line('Impiegato', 'No, è lì.'),
            ], [
                Kit::question('What does Luca ask about?', ['The time of the train', 'A return ticket', 'The bus stop'], 'The time of the train'),
                Kit::question('Which platform does the train leave from?', ['Platform two', 'Platform four', 'Platform five'], 'Platform two'),
                Kit::question('Where is the platform?', ['Over there', 'Here', 'The conversation does not say.'], 'Over there'),
            ], [
                Kit::question('Who speaks first?', ['Luca', 'The clerk', 'Nobody'], 'Luca'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the train leave?', ['At four', 'At two', 'At five'], 'At four'),
            ], [Kit::word('il treno', 'treno'), Kit::word(self::TIME, 'a che ora parte'), Kit::word('il binario', 'binario')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.orario', 'Read the conversation.', [
                Kit::line('Marta', "Buonasera. Voglio l'orario dell'autobus, per favore."),
                Kit::line('Impiegato', "Ecco l'orario. L'autobus della linea cinque parte alle due."),
                Kit::line('Marta', 'Voglio un biglietto di andata e ritorno, per favore.'),
                Kit::line('Impiegato', 'Ecco il biglietto, Marta.'),
            ], [
                Kit::question('What does Marta ask for first?', ['The timetable', 'A ticket', 'A seat'], 'The timetable'),
                Kit::question('Which line leaves at two?', ['Line five', 'Line two', 'Line ten'], 'Line five'),
            ], [Kit::word("l'orario"), Kit::word('la stazione', 'stazione'), Kit::word("l'autobus"), Kit::word('la linea', 'linea'), Kit::word('di andata e ritorno')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.posto', 'Vuole un posto?', 'Do you want a seat? (formal you)', [['sì', 'no'], ['voglio', 'posto']], 'Sì, voglio un posto.', [Kit::word('il posto', 'posto')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fermata', 'Il treno è alla stazione?', 'Is the train at the station?', [['sì', 'no', 'è'], ['treno', 'stazione']], 'Sì, il treno è alla stazione.', [Kit::word('il treno', 'treno'), Kit::word('la stazione', 'stazione')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.biglietto', 'Vuole un biglietto di andata e ritorno?', 'Do you want a return ticket? (formal you)', [['sì', 'no'], ['voglio', 'biglietto', 'ritorno']], 'Sì, voglio un biglietto di andata e ritorno.', [Kit::word('di andata e ritorno')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.posto', 'I want a seat on the train, at platform three.', ['Voglio un posto sul treno, al binario tre.', 'Io voglio un posto sul treno, al binario tre.', 'Voglio un posto in treno, al binario tre.', 'Io voglio un posto in treno, al binario tre.'], [Kit::word('il posto', 'posto'), Kit::word('il treno', 'treno'), Kit::word('il binario', 'binario'), Kit::form('voglio')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ora', 'What time does the bus leave from the stop?', ["A che ora parte l'autobus dalla fermata?", 'A che ora parte il bus dalla fermata?'], [Kit::word(self::TIME, 'a che ora parte'), Kit::word("l'autobus", null, ['bus']), Kit::word('la fermata', 'fermata')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vogliono', 'Do they want a seat and the timetable of line five?', ["Vogliono un posto e l'orario della linea cinque?", "Loro vogliono un posto e l'orario della linea cinque?"], [Kit::word('il posto', 'posto'), Kit::word("l'orario"), Kit::word('la linea', 'linea'), Kit::form('vogliono', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vuoi', 'At the station, do you want a return ticket? (informal you)', ['Alla stazione vuoi un biglietto di andata e ritorno?', 'Alla stazione tu vuoi un biglietto di andata e ritorno?', 'Tu vuoi un biglietto di andata e ritorno alla stazione?'], [Kit::word('di andata e ritorno'), Kit::word('la stazione', 'stazione'), Kit::form('vuoi')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vuole', 'Luca ___ un biglietto di andata e ritorno.', 'Luca wants a return ticket.', 'vuole', Kit::form('vuole'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.volete', 'Voi ___ andare alla fermata.', 'You (all) want to go to the stop.', 'volete', Kit::form('volete', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.fermata', 'La fermata della linea cinque è alla stazione.', 'The stop of line five is at the station.', [Kit::word('la fermata', 'fermata'), Kit::word('la linea', 'linea'), Kit::word('la stazione', 'stazione')], 'dictation', $set, homophoneNote: self::NOTE_E),
            Kit::listenType($stage, 'check.b.listen_type.vogliamo', "Vogliamo l'orario dell'autobus e il biglietto di andata e ritorno.", 'We want the bus timetable and the return ticket.', [Kit::word("l'orario"), Kit::word("l'autobus", "dell'autobus"), Kit::word('di andata e ritorno'), Kit::form('vogliamo')], 'dictation', $set, homophoneNote: self::NOTE_E_ONLY),
            Kit::listenType($stage, 'check.b.listen_type.ora', 'A che ora parte il treno dal binario due?', 'What time does the train leave from platform two?', [Kit::word(self::TIME, 'a che ora parte'), Kit::word('il treno', 'treno'), Kit::word('il binario', 'binario')], 'dictation', $set, homophoneNote: self::NOTE_A),
        ];
    }
}
