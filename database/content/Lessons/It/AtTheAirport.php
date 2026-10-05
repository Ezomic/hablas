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

final class AtTheAirport implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'at-the-airport';
    }

    public function words(): array
    {
        return [
            new WordData('l\'aeroporto', cue: 'airport', forms: ['gli aeroporti']),
            new WordData('il volo', cue: 'flight', forms: ['i voli']),
            new WordData('la valigia', cue: 'suitcase', forms: ['le valigie']),
            new WordData('il passaporto', cue: 'passport', forms: ['i passaporti']),
            new WordData('l\'uscita', cue: 'gate (as on an airport board, "uscita B12")', accepted: ['il gate'], forms: ['le uscite']),
            new WordData('la partenza', cue: 'departure (on an airport board)', forms: ['le partenze']),
            new WordData('l\'arrivo', cue: 'arrival', forms: ['gli arrivi']),
            new WordData('il biglietto', cue: 'ticket (for a flight or train)', forms: ['i biglietti']),
            new WordData('in ritardo', cue: 'delayed, late'),
            new WordData('internazionale', cue: 'international', forms: ['internazionali']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Il volo è internazionale.', 'english' => 'The flight is international.'],
            ['text' => 'La valigia è qui.', 'english' => 'The suitcase is here.'],
            ['text' => 'L\'uscita è lì.', 'english' => 'The gate is over there.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-05', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-05', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-05', 'Released on the owner\'s instruction on 2026-10-05, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $noteE = 'È (is) and e (and) sound close. In writing, the accent on è is what tells them apart.';
        $noteHo = 'Ho (I have) sounds the same as o (or): the h is silent and only shows in writing.';

        return [
            Kit::gap($stage, 'sentences.choose_gap.volo', '___ volo è internazionale.', ['Il', 'La', 'I'], 'Il', Kit::form('il'), 'Volo ends in -o, so it is masculine: il volo.', 'choose', 'The flight is international.'),
            Kit::gap($stage, 'sentences.choose_gap.valigia', '___ valigia è qui.', ['La', 'Il', 'Le'], 'La', Kit::form('la'), 'Valigia ends in -a, so it is feminine: la valigia.', 'choose', 'The suitcase is here.'),
            Kit::gap($stage, 'sentences.choose_gap.arrivo', '___ arrivo è in ritardo.', ["L'", 'Il', 'La'], "L'", Kit::form("l'", true), "Before a vowel, lo and la become l': l'arrivo. The apostrophe hides the gender.", 'choose', 'The arrival is late.', ['arrivo' => 'arrival']),
            Kit::gap($stage, 'sentences.choose_gap.biglietto', 'Ho ___ biglietto.', ['un', 'una', 'uno'], 'un', Kit::form('un'), 'Biglietto ends in -o, so it is masculine: un biglietto. Una is for feminine nouns.', 'choose', 'I have a ticket.'),
            Kit::gap($stage, 'sentences.choose_gap.internazionale', 'Il volo dieci è ___.', ['internazionale', 'internazionali'], 'internazionale', Kit::word('internazionale'), 'Internazionale has one form for masculine and feminine. Only the plural changes, to -i.', 'choose', 'Flight ten is international.'),
            Kit::gap($stage, 'sentences.choose_gap.ritardo', 'Il volo è ___.', ['in ritardo', 'di ritardo', 'a ritardo'], 'in ritardo', Kit::word('in ritardo'), 'Delayed is said with in ritardo: il volo è in ritardo.', 'choose', 'The flight is delayed.'),

            Kit::typeGap($stage, 'sentences.type_gap.volo', '___ volo sette è in ritardo.', 'Flight seven is delayed.', 'Il', Kit::form('il'), 'Volo ends in -o, so it is masculine: il volo.'),
            Kit::typeGap($stage, 'sentences.type_gap.uscita', 'Ecco ___ uscita.', 'Here is the gate.', "l'", Kit::form("l'", true), "Before a vowel, lo and la become l': l'uscita. The apostrophe hides the gender.", glosses: ['uscita' => 'gate']),
            Kit::typeGap($stage, 'sentences.type_gap.valigia', 'Ho ___ valigia.', 'I have a suitcase.', 'una', Kit::form('una'), 'Valigia is feminine, so a suitcase is una valigia.'),
            Kit::typeGap($stage, 'sentences.type_gap.passaporti', '___ passaporti sono qui.', 'The passports are here.', 'I', Kit::form('i'), 'Most masculine nouns take i in the plural: i passaporti. Gli is for nouns that start with a vowel (and with z or s plus a consonant, which you will meet later).'),
            Kit::typeGap($stage, 'sentences.type_gap.valigie', 'Dove sono ___ valigie?', 'Where are the suitcases?', 'le', Kit::form('le'), 'Feminine nouns in the plural take le: le valigie.'),

            Kit::translate($stage, 'sentences.translate.passaporto', 'The passport is with the ticket.', ['Il passaporto è con il biglietto.'], [Kit::word('il passaporto'), Kit::word('il biglietto'), Kit::form('il')]),
            Kit::translate($stage, 'sentences.translate.arrivo', 'The arrival is late.', ["L'arrivo è in ritardo."], [Kit::word("l'arrivo"), Kit::word('in ritardo'), Kit::form("l'arrivo", true)]),
            Kit::translate($stage, 'sentences.translate.volo', 'It is an international flight.', ['È un volo internazionale.', 'Questo è un volo internazionale.'], [Kit::word('il volo', 'volo'), Kit::word('internazionale'), Kit::form('un')]),

            Kit::build($stage, 'sentences.build.aeroporto', 'We are at the airport.', 'Siamo in aeroporto.', ['la'], [Kit::word("l'aeroporto", 'aeroporto')]),
            Kit::build($stage, 'sentences.build.uscita', 'Here is gate seven.', "Ecco l'uscita sette.", ['la'], [Kit::word("l'uscita"), Kit::form("l'uscita", true)]),
            Kit::build($stage, 'sentences.build.uscite', 'Where are the gates?', 'Dove sono le uscite?', ['gli'], [Kit::word("l'uscita", 'uscite'), Kit::form('le', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.valigia', 'La valigia è qui.', ['The suitcase is here.', 'The passport is here.', 'The ticket is here.', 'The gate is here.'], 'The suitcase is here.', [Kit::word('la valigia'), Kit::form('la')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.volo', 'Il volo è in ritardo.', ['The flight is delayed.', 'The flight is international.', 'The arrival is delayed.', 'The departure is delayed.'], 'The flight is delayed.', [Kit::word('il volo'), Kit::word('in ritardo'), Kit::form('il')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.voli', 'Ecco i voli internazionali.', ['Here are the international flights.', 'Here is the international flight.', 'Here are the international airports.', 'Here are the delayed flights.'], 'Here are the international flights.', [Kit::word('il volo', 'voli'), Kit::word('internazionale', 'internazionali'), Kit::form('i')]),
            Kit::listenType($stage, 'sentences.listen_type.passaporto', 'Il mio passaporto è qui.', 'My passport is here.', [Kit::word('il passaporto', 'passaporto')], homophoneNote: $noteE),
            Kit::listenType($stage, 'sentences.listen_type.partenza', 'La partenza è in ritardo.', 'The departure is delayed.', [Kit::word('la partenza'), Kit::word('in ritardo'), Kit::form('la')], homophoneNote: $noteE),
            Kit::listenType($stage, 'sentences.listen_type.biglietto', 'Ho un biglietto per il volo dieci.', 'I have a ticket for flight ten.', [Kit::word('il biglietto', 'biglietto'), Kit::word('il volo'), Kit::form('un')], homophoneNote: $noteHo),
            Kit::listenType($stage, 'sentences.listen_type.valigie', 'Le valigie sono in aeroporto.', 'The suitcases are at the airport.', [Kit::word('la valigia', 'valigie'), Kit::word("l'aeroporto", 'aeroporto'), Kit::form('le')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.volo', 'Il volo dieci è in ritardo.', 'Flight ten is delayed.', [Kit::word('il volo'), Kit::word('in ritardo'), Kit::form('il')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.uscita', "L'uscita sette è lì.", 'Gate seven is over there.', [Kit::word("l'uscita"), Kit::form("l'uscita", true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.passaporto', 'Il passaporto e il biglietto sono qui.', 'The passport and the ticket are here.', [Kit::word('il passaporto'), Kit::word('il biglietto'), Kit::form('il')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.valigia', 'La mia valigia è qui.', 'My suitcase is here.', [Kit::word('la valigia', 'valigia')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.valigie', 'Dove sono le valigie?', 'Where are the suitcases?', [['valigie', 'sono'], ['qui', 'lì', 'ecco']], 'Le valigie sono qui.', [Kit::word('la valigia', 'valigie'), Kit::form('le')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.arrivo', "L'arrivo è qui?", 'Is the arrival here?', [['sì', 'no', 'è'], ['qui', 'lì', "l'arrivo", 'arrivo']], "Sì, l'arrivo è qui.", [Kit::word("l'arrivo", "l'arrivo", ['arrivo']), Kit::form("l'arrivo", true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.partenza', 'La partenza è in ritardo?', 'Is the departure late?', [['sì', 'no', 'è'], ['ritardo', 'partenza']], 'Sì, la partenza è in ritardo.', [Kit::word('la partenza'), Kit::word('in ritardo'), Kit::form('la')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $noteE = 'È (is) and e (and) sound close. In writing, the accent on è is what tells them apart.';
        $noteHoE = 'Ho (I have) sounds the same as o (or): the h is silent and only shows in writing. E (and) and è (is) sound close, and the accent on è is what tells them apart in writing.';

        return [
            Kit::readPassage($stage, 'task.read_passage.check-in', 'Read the conversation at the airport.', [
                Kit::line('Luca', 'Buongiorno. Ha il passaporto e il biglietto?'),
                Kit::line('Marta', 'Sì, ecco il passaporto e il biglietto per il volo dieci.'),
                Kit::line('Luca', 'Va bene, è un volo internazionale. Quante valigie ha?'),
                Kit::line('Marta', 'Ho una valigia.'),
                Kit::line('Luca', "Bene. L'uscita sette è lì, ma il volo è in ritardo."),
                Kit::line('Marta', 'Va bene, grazie.'),
            ], [
                Kit::question('How many suitcases does Marta have?', ['None', 'One', 'Two'], 'One'),
                Kit::question('Which flight is it?', ['Flight seven', 'Flight ten', 'Flight two'], 'Flight ten'),
                Kit::question('What is the problem?', ['The flight is delayed.', 'Marta has no passport.', 'The gate is closed.'], 'The flight is delayed.'),
            ], [Kit::word('il passaporto'), Kit::word('il biglietto'), Kit::word('il volo'), Kit::word('internazionale'), Kit::word('la valigia', 'valigia'), Kit::word("l'uscita"), Kit::word('in ritardo')]),
            Kit::gap($stage, 'task.choose_gap.aeroporti', '___ aeroporti sono internazionali.', ['Gli', 'I', 'Le'], 'Gli', Kit::form('gli', true), 'Most masculine nouns take i in the plural: i voli. Before a vowel it is gli: gli aeroporti (and before z or s plus a consonant, which you will meet later).', 'read'),
            Kit::gap($stage, 'task.choose_gap.partenze', 'Le partenze sono ___.', ['internazionali', 'internazionale'], 'internazionali', Kit::word('internazionale', 'internazionali'), 'Internazionale ends in -e, so the plural ends in -i: internazionali.', 'read'),

            Kit::transform($stage, 'task.transform.voli', 'Make it plural.', 'Il volo è internazionale.', ['I voli sono internazionali.'], [Kit::word('il volo', 'voli'), Kit::word('internazionale', 'internazionali'), Kit::form('i')]),
            Kit::transform($stage, 'task.transform.valigie', 'Make it plural.', 'La valigia è qui.', ['Le valigie sono qui.'], [Kit::word('la valigia', 'valigie'), Kit::form('le')]),
            Kit::transform($stage, 'task.transform.arrivi', 'Make it plural.', "L'arrivo è in ritardo.", ['Gli arrivi sono in ritardo.'], [Kit::word("l'arrivo", 'arrivi'), Kit::word('in ritardo'), Kit::form('gli', true)]),
            Kit::writeGuided($stage, 'task.write_guided.biglietto', 'Say that you have a ticket and a passport, and ask where the suitcases are.', ['biglietto', 'passaporto', 'valigie', 'dove'], 'Ho un biglietto e un passaporto. Dove sono le valigie?', [
                ['forms' => ['biglietto', 'biglietti'], 'term' => 'il biglietto'],
                ['forms' => ['passaporto', 'passaporti'], 'term' => 'il passaporto'],
                ['forms' => ['valigie', 'valigia'], 'term' => 'la valigia'],
                ['forms' => ['dove'], 'term' => null],
            ], [Kit::word('il biglietto'), Kit::word('il passaporto'), Kit::word('la valigia')]),
            Kit::writeGuided($stage, 'task.write_guided.volo', 'Say that the flight is delayed, and say here is the gate.', ['il volo', 'in ritardo', "l'uscita", 'ecco'], "Il volo è in ritardo. Ecco l'uscita.", [
                ['forms' => ['volo', 'voli'], 'term' => 'il volo'],
                ['forms' => ['ritardo'], 'term' => 'in ritardo'],
                ['forms' => ["l'uscita", 'uscite'], 'term' => "l'uscita"],
                ['forms' => ['ecco'], 'term' => null],
            ], [Kit::word('il volo'), Kit::word('in ritardo'), Kit::word("l'uscita")]),
            Kit::build($stage, 'task.build.passaporto', 'The passport and the ticket are here.', 'Il passaporto e il biglietto sono qui.', ['è', 'la'], [Kit::word('il passaporto'), Kit::word('il biglietto'), Kit::form('il')], 'write'),
            Kit::build($stage, 'task.build.volo', 'The flight is international and the departure is delayed.', 'Il volo è internazionale e la partenza è in ritardo.', ['i', 'le'], [Kit::word('il volo'), Kit::word('internazionale'), Kit::word('la partenza'), Kit::word('in ritardo'), Kit::form('la')], 'write'),
            Kit::build($stage, 'task.build.uscite', 'Here are the gates and the suitcases.', 'Ecco le uscite e le valigie.', ['gli', 'i'], [Kit::word("l'uscita", 'uscite'), Kit::word('la valigia', 'valigie'), Kit::form('le', true)], 'write'),
            Kit::translate($stage, 'task.translate.passaporti', 'Where are the passports?', ['Dove sono i passaporti?'], [Kit::word('il passaporto', 'passaporti'), Kit::form('i')], 'write'),
            Kit::translate($stage, 'task.translate.biglietto', 'The ticket is for the international flight.', ['Il biglietto è per il volo internazionale.'], [Kit::word('il biglietto'), Kit::word('il volo'), Kit::word('internazionale'), Kit::form('il')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.aeroporto', [
                Kit::line('Anna', 'Ciao, Paolo. Sei in aeroporto?'),
                Kit::line('Paolo', 'Sì, sono qui. Ho il passaporto e il biglietto.'),
                Kit::line('Anna', 'Il tuo volo è in ritardo?'),
                Kit::line('Paolo', 'Sì, la partenza è in ritardo. È un volo internazionale.'),
                Kit::line('Anna', 'Grazie, Paolo. Ciao!'),
            ], [
                Kit::question('Where is Paolo?', ['At the airport', 'At home', 'At the hotel'], 'At the airport'),
                Kit::question('How is the flight?', ['It is delayed.', 'It is on time.', 'The conversation does not say.'], 'It is delayed.'),
                Kit::question('What kind of flight is it?', ['An international flight', 'A short flight', 'The conversation does not say.'], 'An international flight'),
            ], [
                Kit::question('Who asks if the flight is delayed?', ['Anna', 'Paolo', 'Nobody'], 'Anna'),
                Kit::question('What does Paolo have?', ['A passport and a ticket', 'A suitcase only', 'Nothing'], 'A passport and a ticket'),
                Kit::question('How does Anna end the conversation?', ['She says thank you.', 'She asks about the gate.', 'She says the flight is late.'], 'She says thank you.'),
            ], [Kit::word("l'aeroporto"), Kit::word('il passaporto'), Kit::word('il biglietto'), Kit::word('il volo'), Kit::word('in ritardo'), Kit::word('la partenza'), Kit::word('internazionale')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.valigia', 'Ho la valigia e il passaporto.', 'I have the suitcase and the passport.', [Kit::word('la valigia', 'valigia'), Kit::word('il passaporto', 'passaporto'), Kit::form('la')], 'listen', homophoneNote: $noteHoE),
            Kit::listenType($stage, 'task.listen_type.uscita', "Il volo è in ritardo, ma l'uscita è qui.", 'The flight is late, but the gate is here.', [Kit::word('il volo'), Kit::word('in ritardo'), Kit::word("l'uscita"), Kit::form("l'uscita", true)], 'listen', homophoneNote: $noteE),
            Kit::listenType($stage, 'task.listen_type.arrivo', "L'arrivo e la partenza sono internazionali.", 'The arrival and the departure are international.', [Kit::word("l'arrivo"), Kit::word('la partenza'), Kit::word('internazionale', 'internazionali'), Kit::form("l'arrivo", true)], 'listen', homophoneNote: 'E (and) is written without an accent, unlike è (is), and the two sound close.'),

            Kit::speakAnswer($stage, 'task.speak_answer.passaporto', 'Ha il passaporto?', 'Do you have your passport? (formal)', [['sì', 'no', 'ho', 'ecco'], ['passaporto', 'ecco']], 'Sì, ecco il mio passaporto.', [Kit::word('il passaporto', 'passaporto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.aeroporto', 'Sei in aeroporto?', 'Are you at the airport? (informal)', [['sì', 'no', 'sono'], ['aeroporto', 'qui']], 'Sì, sono in aeroporto.', [Kit::word("l'aeroporto", 'aeroporto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.valigie', 'Quante valigie ha?', 'How many suitcases do you have? (formal)', [['ho', 'una', 'due', 'tre', 'quattro'], ['valigia', 'valigie', 'una', 'due', 'tre', 'quattro']], 'Ho una valigia.', [Kit::word('la valigia', 'valigia')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.arrivo', "L'arrivo è in ritardo?", 'Is the arrival late?', [['sì', 'no', 'è'], ['ritardo', "l'arrivo", 'arrivo']], "Sì, l'arrivo è in ritardo.", [Kit::word("l'arrivo", "l'arrivo", ['arrivo']), Kit::word('in ritardo'), Kit::form("l'arrivo", true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.biglietto', 'Ecco il biglietto per il volo dieci.', 'Here is the ticket for flight ten.', [Kit::word('il biglietto'), Kit::word('il volo'), Kit::form('il')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.valigie', 'Le valigie e i passaporti sono qui.', 'The suitcases and the passports are here.', [Kit::word('la valigia', 'valigie'), Kit::word('il passaporto', 'passaporti'), Kit::form('le')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $noteE = 'È (is) and e (and) sound close. In writing, the accent on è is what tells them apart.';
        $noteHoE = 'Ho (I have) sounds the same as o (or): the h is silent and only shows in writing. E (and) and è (is) sound close, and the accent on è is what tells them apart in writing.';

        return [
            Kit::translate($stage, 'check.a.translate.voli', 'Where are the international flights?', ['Dove sono i voli internazionali?'], [Kit::word('il volo', 'voli'), Kit::word('internazionale', 'internazionali'), Kit::form('i')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.biglietto', 'The ticket and the passport are with the suitcase.', ['Il biglietto e il passaporto sono con la valigia.'], [Kit::word('il biglietto', 'biglietto'), Kit::word('il passaporto', 'passaporto'), Kit::word('la valigia', 'valigia'), Kit::form('la')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.partenza', 'The departure and the arrival are late.', ["La partenza e l'arrivo sono in ritardo.", "L'arrivo e la partenza sono in ritardo."], [Kit::word('la partenza', 'partenza'), Kit::word("l'arrivo"), Kit::word('in ritardo'), Kit::form("l'arrivo", true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.uscita', 'The airport is international, but the gate is over there.', ["L'aeroporto è internazionale, ma l'uscita è lì."], [Kit::word("l'uscita"), Kit::word("l'aeroporto"), Kit::word('internazionale')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.passaporto', '___ passaporto è con la valigia.', 'The passport is with the suitcase.', 'Il', Kit::form('il'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.arrivi', '___ arrivi sono internazionali.', 'The arrivals are international.', 'Gli', Kit::form('gli', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.arrivo', "L'arrivo del volo è in ritardo.", 'The arrival of the flight is late.', [Kit::word("l'arrivo"), Kit::word('il volo', 'volo'), Kit::word('in ritardo')], 'dictation', $set, homophoneNote: $noteE),
            Kit::listenType($stage, 'check.a.listen_type.biglietto', 'Sono in aeroporto. Ho il biglietto, il passaporto e la valigia.', 'I am at the airport. I have the ticket, the passport and the suitcase.', [Kit::word("l'aeroporto", 'aeroporto'), Kit::word('il biglietto', 'biglietto'), Kit::word('il passaporto', 'passaporto'), Kit::word('la valigia', 'valigia')], 'dictation', $set, homophoneNote: $noteHoE),
            Kit::listenType($stage, 'check.a.listen_type.partenza', "La partenza è qui, l'uscita è lì.", 'The departure is here, the gate is over there.', [Kit::word('la partenza', 'partenza'), Kit::word("l'uscita"), Kit::form('la')], 'dictation', $set, homophoneNote: $noteE.' Lì (there) is written with an accent; li without it is a different word (them).'),
            Kit::listenPassage($stage, 'check.a.listen_passage.volo', [
                Kit::line('Luca', 'Ciao, Marta. Dove sei?'),
                Kit::line('Marta', 'Sono in aeroporto. Ho il biglietto per il volo sette.'),
                Kit::line('Luca', 'Il volo sette è internazionale?'),
                Kit::line('Marta', 'Sì, e la partenza è in ritardo.'),
                Kit::line('Luca', 'Va bene. Ciao, Marta!'),
            ], [
                Kit::question('Where is Marta?', ['At the airport', 'At home', 'At the hotel'], 'At the airport'),
                Kit::question('Which flight is it?', ['Flight five', 'Flight seven', 'Flight ten'], 'Flight seven'),
                Kit::question('What is late?', ['The departure', 'The arrival', 'The suitcase'], 'The departure'),
            ], [
                Kit::question('Who asks if the flight is international?', ['Luca', 'Marta', 'Nobody'], 'Luca'),
                Kit::question('What does Marta have?', ['A ticket', 'A suitcase', 'A passport'], 'A ticket'),
                Kit::question('Who says goodbye at the end?', ['Luca', 'Marta', 'Nobody'], 'Luca'),
            ], [Kit::word("l'aeroporto"), Kit::word('il biglietto'), Kit::word('il volo'), Kit::word('internazionale'), Kit::word('la partenza'), Kit::word('in ritardo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.valigia', 'Read the conversation.', [
                Kit::line('Anna', 'Ciao, Luca. Ecco la mia valigia e il mio passaporto.'),
                Kit::line('Luca', 'Bene. Hai anche il biglietto?'),
                Kit::line('Anna', "Sì, ecco il biglietto. L'uscita è lì?"),
                Kit::line('Luca', "Sì, l'uscita è lì."),
            ], [
                Kit::question('What does Anna show first?', ['Her suitcase and her passport', 'Her ticket and her gate', 'Only her ticket'], 'Her suitcase and her passport'),
                Kit::question('Where is the gate?', ['Over there', 'Here', 'The text does not say.'], 'Over there'),
            ], [Kit::word('la valigia'), Kit::word('il passaporto'), Kit::word('il biglietto'), Kit::word("l'uscita")], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.biglietto', 'Ha il biglietto?', 'Do you have your ticket? (formal)', [['sì', 'no', 'ho', 'ecco'], ['biglietto', 'ecco']], 'Sì, ecco il mio biglietto.', [Kit::word('il biglietto', 'biglietto')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.volo', 'Il volo nove è internazionale?', 'Is flight nine international?', [['sì', 'no', 'è'], ['internazionale', 'volo']], 'Sì, il volo nove è internazionale.', [Kit::word('il volo'), Kit::word('internazionale')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.valigia', 'Ha una valigia?', 'Do you have a suitcase? (formal)', [['sì', 'no', 'ho'], ['valigia', 'una', 'ecco']], 'Sì, ho una valigia.', [Kit::word('la valigia', 'valigia')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $noteE = 'È (is) and e (and) sound close. In writing, the accent on è is what tells them apart.';
        $noteEOnly = 'E (and) is written without an accent, unlike è (is), and the two sound close.';

        return [
            Kit::translate($stage, 'check.b.translate.voli', 'The international flights are late.', ['I voli internazionali sono in ritardo.'], [Kit::word('il volo', 'voli'), Kit::word('internazionale', 'internazionali'), Kit::word('in ritardo'), Kit::form('i')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.valigia', 'I have a suitcase and a ticket.', ['Ho una valigia e un biglietto.'], [Kit::word('la valigia', 'valigia'), Kit::word('il biglietto', 'biglietto'), Kit::form('una')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.partenza', 'Here are the departure, the arrival and the gate.', ["Ecco la partenza, l'arrivo e l'uscita."], [Kit::word('la partenza', 'partenza'), Kit::word("l'arrivo"), Kit::word("l'uscita"), Kit::form("l'arrivo", true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.passaporto', 'The passport and the suitcase are at the airport.', ['Il passaporto e la valigia sono in aeroporto.'], [Kit::word('il passaporto', 'passaporto'), Kit::word('la valigia', 'valigia'), Kit::word("l'aeroporto", 'aeroporto')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.biglietto', '___ biglietto è per il volo sette.', 'The ticket is for flight seven.', 'Il', Kit::form('il'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.uscite', '___ uscite sono lì.', 'The gates are over there.', 'Le', Kit::form('le', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.volo', 'La partenza del volo dieci è in ritardo.', 'The departure of flight ten is late.', [Kit::word('il volo', 'volo'), Kit::word('la partenza', 'partenza'), Kit::word('in ritardo')], 'dictation', $set, homophoneNote: $noteE),
            Kit::listenType($stage, 'check.b.listen_type.arrivo', "L'aeroporto internazionale è qui, ma l'arrivo è lì.", 'The international airport is here, but the arrival is over there.', [Kit::word("l'arrivo"), Kit::word('internazionale'), Kit::word("l'aeroporto"), Kit::form("l'arrivo", true)], 'dictation', $set, homophoneNote: $noteE.' Lì (there) is written with an accent; li without it is a different word (them).'),
            Kit::listenType($stage, 'check.b.listen_type.passaporto', "Ecco il passaporto e il biglietto per l'uscita sette.", 'Here are the passport and the ticket for gate seven.', [Kit::word('il passaporto', 'passaporto'), Kit::word('il biglietto', 'biglietto'), Kit::word("l'uscita")], 'dictation', $set, homophoneNote: $noteEOnly),
        ];
    }
}
