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

final class CheckingIntoAHotel implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [
            new WordData('l\'albergo', cue: 'hotel', accepted: ['l\'hotel']),
            new WordData('la camera', cue: 'room (in a hotel)'),
            new WordData('la prenotazione', cue: 'reservation'),
            new WordData('la chiave', cue: 'key'),
            new WordData('il receptionist', cue: 'receptionist (a man; the word is the same for a woman)', accepted: ['la receptionist']),
            new WordData('disponibile', cue: 'available', forms: ['disponibili']),
            new WordData('la notte', cue: 'night'),
            new WordData('il bagno', cue: 'bathroom (the room with the bath or shower)'),
            new WordData('incluso', cue: 'included (masculine)', forms: ['inclusa', 'inclusi', 'incluse']),
            new WordData('la colazione', cue: 'breakfast'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ho una prenotazione.', 'english' => 'I have a reservation.'],
            ['text' => 'C\'è una camera disponibile.', 'english' => 'There is a room available.'],
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

        return [
            Kit::gap($stage, 'sentences.choose_gap.io-prenotazione', 'Io ___ una prenotazione.', ['ho', 'ha', 'sono'], 'ho', Kit::form('ho'), 'Having something takes avere: io ho. Sono (I am) says who you are, not what you have.', 'choose', 'I have a reservation.'),
            Kit::gap($stage, 'sentences.choose_gap.ce-camera', '___ una camera disponibile.', ["C'è", 'Ci sono', 'Sono'], "C'è", Kit::form("c'è", true), "To say that one thing is there, Italian uses c'è. Ci sono is for more than one.", 'choose', 'There is a room available.'),
            Kit::gap($stage, 'sentences.choose_gap.anna-luca', 'Anna e Luca ___ la chiave.', ['hanno', 'ha', 'hai'], 'hanno', Kit::form('hanno', true), 'Two people take hanno. Ha is for one person (he, she, Lei).', 'choose', 'Anna and Luca have the key.'),
            Kit::gap($stage, 'sentences.choose_gap.noi-camera', 'Noi ___ una camera.', ['abbiamo', 'avete', 'hanno'], 'abbiamo', Kit::form('abbiamo'), 'Noi (we) takes abbiamo.', 'choose', 'We have a room.'),
            Kit::gap($stage, 'sentences.choose_gap.tu-prenotazione', 'Tu ___ la prenotazione?', ['hai', 'ha', 'ho'], 'hai', Kit::form('hai'), 'Tu (you, to one person you know) takes hai.', 'choose', 'Do you have the reservation?'),
            Kit::gap($stage, 'sentences.choose_gap.ci-sono', '___ due camere disponibili.', ['Ci sono', "C'è"], 'Ci sono', Kit::form('ci sono', true), "More than one thing takes ci sono. C'è is for one.", 'choose', 'There are two rooms available.'),

            Kit::typeGap($stage, 'sentences.type_gap.voi-chiave', 'Voi ___ la chiave.', 'You (all) have the key.', 'avete', Kit::form('avete'), 'Voi (you, more than one) takes avete.'),
            Kit::typeGap($stage, 'sentences.type_gap.lei-prenotazione', 'Lei ___ una prenotazione?', 'Do you have a reservation?', 'ha', Kit::form('ha', true), 'Lei (the polite you) takes ha, like he and she. Hai is for tu.'),
            Kit::typeGap($stage, 'sentences.type_gap.ce-colazione', '___ la colazione?', 'Is there breakfast?', "C'è", Kit::form("c'è", true), "To ask whether one thing is there, use c'è."),
            Kit::typeGap($stage, 'sentences.type_gap.marta-paolo', 'Marta e Paolo ___ una camera.', 'Marta and Paolo have a room.', 'hanno', Kit::form('hanno'), 'Two people take hanno.'),
            Kit::typeGap($stage, 'sentences.type_gap.io-chiave', 'Io ___ la chiave.', 'I have the key.', 'ho', Kit::form('ho'), 'Io (I) takes ho.'),
            Kit::translate($stage, 'sentences.translate.prenotazione', 'I have a reservation.', ['Ho una prenotazione.', 'Io ho una prenotazione.'], [Kit::word('la prenotazione', 'prenotazione'), Kit::form('ho')]),
            Kit::translate($stage, 'sentences.translate.colazione', 'The breakfast is included.', ['La colazione è inclusa.'], [Kit::word('la colazione', 'colazione'), Kit::word('incluso', 'inclusa')]),
            Kit::translate($stage, 'sentences.translate.camera', 'There is a room available.', ["C'è una camera disponibile.", 'Una camera è disponibile.'], [Kit::word('la camera', 'camera'), Kit::word('disponibile')]),
            Kit::build($stage, 'sentences.build.chiave', 'We have the key.', 'Abbiamo la chiave.', ['ho'], [Kit::word('la chiave', 'chiave'), Kit::form('abbiamo')]),
            Kit::build($stage, 'sentences.build.camere', 'There are two rooms available.', 'Ci sono due camere disponibili.', ["c'è"], [Kit::word('la camera', 'camere'), Kit::word('disponibile', 'disponibili'), Kit::form('ci sono', true)]),
            Kit::build($stage, 'sentences.build.notti', 'The reservation is for two nights.', 'La prenotazione è per due notti.', ['ha'], [Kit::word('la prenotazione', 'prenotazione'), Kit::word('la notte', 'notti')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.prenotazione', 'Ho una prenotazione.', ['I have a reservation.', 'She has a reservation.', 'We have a reservation.', 'There is a reservation.'], 'I have a reservation.', [Kit::word('la prenotazione', 'prenotazione'), Kit::form('ho')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.camera', "C'è una camera disponibile.", ['The room is not available.', 'There is a room available.', 'The reservation is for one night.', 'There is a bathroom in the room.'], 'There is a room available.', [Kit::word('la camera', 'camera'), Kit::word('disponibile'), Kit::form("c'è", true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.bagno', 'Il bagno è in camera.', ['The bathroom is in the room.', 'The key is in the room.', 'The breakfast is in the room.', 'The bathroom is not available.'], 'The bathroom is in the room.', [Kit::word('il bagno', 'bagno'), Kit::word('la camera', 'camera')]),
            Kit::listenType($stage, 'sentences.listen_type.chiave', 'Ho la chiave.', 'I have the key.', [Kit::word('la chiave', 'chiave'), Kit::form('ho')], homophoneNote: 'Ho (I have) and o (or) sound the same. The h is silent and only shows in writing.'),
            Kit::listenType($stage, 'sentences.listen_type.colazione', 'La colazione è inclusa.', 'Breakfast is included.', [Kit::word('la colazione', 'colazione'), Kit::word('incluso', 'inclusa')], homophoneNote: 'È (is) and e (and) sound close. The accent on è is only seen in writing.'),
            Kit::listenType($stage, 'sentences.listen_type.receptionist', 'Il receptionist ha la chiave.', 'The receptionist has the key.', [Kit::word('il receptionist', 'receptionist'), Kit::word('la chiave', 'chiave'), Kit::form('ha')], homophoneNote: 'Ha (has) and a (to) sound the same. The h is silent and only shows in writing.', alsoAccepted: ['La receptionist ha la chiave.']),
            Kit::listenType($stage, 'sentences.listen_type.albergo', 'Abbiamo una camera in albergo.', 'We have a room in the hotel.', [Kit::word('la camera', 'camera'), Kit::word("l'albergo", 'albergo'), Kit::form('abbiamo')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.notti', "C'è una camera disponibile per due notti?", 'Is there a room available for two nights?', [Kit::word('disponibile'), Kit::word('la notte', 'notti'), Kit::form("c'è", true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.chiave', 'La chiave è in camera.', 'The key is in the room.', [Kit::word('la chiave', 'chiave'), Kit::word('la camera', 'camera')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.receptionist', 'Sono il receptionist in albergo.', 'I am the receptionist in the hotel.', [Kit::word('il receptionist', 'receptionist'), Kit::word("l'albergo", 'albergo')], 'speak', ['Sono la receptionist in albergo.']),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.abbiamo', 'Abbiamo una prenotazione per due notti.', 'We have a reservation for two nights.', [Kit::word('la prenotazione', 'prenotazione'), Kit::word('la notte', 'notti'), Kit::form('abbiamo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.prenotazione', 'Ha una prenotazione?', 'Do you have a reservation?', [['sì', 'no', 'ho', 'abbiamo']], 'Sì, ho una prenotazione.', [Kit::word('la prenotazione', 'prenotazione')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.bagno', "C'è un bagno in camera?", 'Is there a bathroom in the room?', [['sì', 'no', "c'è"]], "Sì, c'è un bagno in camera.", [Kit::word('il bagno', 'bagno'), Kit::word('la camera', 'camera'), Kit::form("c'è", true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.colazione', 'La colazione è inclusa?', 'Is breakfast included?', [['sì', 'no', 'è', 'inclusa', 'incluso']], 'Sì, è inclusa.', [Kit::word('la colazione', 'colazione'), Kit::word('incluso', 'inclusa')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.reception', 'Read the conversation at the reception desk.', [
                Kit::line('Receptionist', 'Buonasera. Sono il receptionist. Ha una prenotazione?'),
                Kit::line('Anna', 'Sì, ho una prenotazione per due notti.'),
                Kit::line('Receptionist', 'Molto bene. La camera è disponibile. Ecco la chiave.'),
                Kit::line('Anna', 'La colazione è inclusa?'),
                Kit::line('Receptionist', 'Sì, la colazione è inclusa.'),
                Kit::line('Anna', 'Grazie.'),
            ], [
                Kit::question('How many nights is the reservation for?', ['one', 'two', 'three'], 'two'),
                Kit::question('Is the room available?', ['No, it is not available.', 'Yes, it is available.', 'The text does not say.'], 'Yes, it is available.'),
                Kit::question('Is breakfast included?', ['Yes, it is included.', 'No, it is not included.', 'The text does not say.'], 'Yes, it is included.'),
            ], [Kit::word('la prenotazione'), Kit::word('la notte'), Kit::word('il receptionist'), Kit::word('la camera'), Kit::word('disponibile'), Kit::word('la chiave'), Kit::word('la colazione'), Kit::word('incluso')]),
            Kit::gap($stage, 'task.choose_gap.camere', 'Le camere sono ___.', ['disponibili', 'disponibile'], 'disponibili', Kit::word('disponibile', 'disponibili'), 'The adjective agrees with the noun: more than one room, so disponibili.', 'read'),
            Kit::gap($stage, 'task.choose_gap.colazione', 'La colazione è ___.', ['inclusa', 'incluso'], 'inclusa', Kit::word('incluso', 'inclusa'), 'Colazione is feminine, so the adjective is inclusa.', 'read'),

            Kit::transform($stage, 'task.transform.albergo', 'Make it plural.', "L'albergo è qui.", ['Gli alberghi sono qui.', 'Gli hotel sono qui.'], [Kit::word("l'albergo", 'alberghi', ['hotel'])]),
            Kit::transform($stage, 'task.transform.camera', 'Make it plural.', "C'è una camera disponibile.", ['Ci sono due camere disponibili.', 'Ci sono camere disponibili.'], [Kit::word('la camera', 'camere'), Kit::word('disponibile', 'disponibili'), Kit::form('ci sono', true)]),
            Kit::transform($stage, 'task.transform.domanda', 'Make it a question.', 'Lei ha una prenotazione.', ['Ha una prenotazione?', 'Lei ha una prenotazione?'], [Kit::word('la prenotazione', 'prenotazione'), Kit::form('ha', true)]),
            Kit::writeGuided($stage, 'task.write_guided.camera', 'Ask for a room for two nights and ask whether breakfast is included.', ['camera', 'notti', 'colazione', 'inclusa'], 'Vorrei una camera per due notti. La colazione è inclusa?', [
                ['forms' => ['camera', 'camere'], 'term' => 'la camera'],
                ['forms' => ['notti', 'notte'], 'term' => 'la notte'],
                ['forms' => ['colazione'], 'term' => 'la colazione'],
                ['forms' => ['inclusa', 'incluso'], 'term' => 'incluso'],
            ], [Kit::word('la camera'), Kit::word('la notte'), Kit::word('la colazione'), Kit::word('incluso')], ['vorrei' => 'I would like']),
            Kit::writeGuided($stage, 'task.write_guided.prenotazione', 'Say that you have a reservation and ask whether there is a bathroom in the room.', ['prenotazione', 'bagno', 'camera'], "Ho una prenotazione. C'è un bagno in camera?", [
                ['forms' => ['prenotazione'], 'term' => 'la prenotazione'],
                ['forms' => ['bagno'], 'term' => 'il bagno'],
            ], [Kit::word('la prenotazione'), Kit::word('il bagno')]),
            Kit::build($stage, 'task.build.chiave', 'The key is in the room.', 'La chiave è in camera.', ['sono', 'hanno'], [Kit::word('la chiave', 'chiave'), Kit::word('la camera', 'camera')], 'write'),
            Kit::build($stage, 'task.build.hanno', 'Marta and Luca have a reservation.', 'Marta e Luca hanno una prenotazione.', ['ha', 'abbiamo'], [Kit::word('la prenotazione', 'prenotazione'), Kit::form('hanno', true)], 'write'),
            Kit::build($stage, 'task.build.camere', 'There are rooms available for two nights.', 'Ci sono camere disponibili per due notti.', ["c'è", 'siamo'], [Kit::word('la camera', 'camere'), Kit::word('disponibile', 'disponibili'), Kit::word('la notte', 'notti'), Kit::form('ci sono', true)], 'write'),
            Kit::translate($stage, 'task.translate.prenotazione', 'Do you have a reservation?', ['Ha una prenotazione?', 'Lei ha una prenotazione?', 'Hai una prenotazione?', 'Tu hai una prenotazione?', 'Avete una prenotazione?', 'Voi avete una prenotazione?'], [Kit::word('la prenotazione', 'prenotazione')], 'write'),
            Kit::translate($stage, 'task.translate.notti', 'We have a room for three nights.', ['Abbiamo una camera per tre notti.', 'Noi abbiamo una camera per tre notti.'], [Kit::word('la camera', 'camera'), Kit::word('la notte', 'notti'), Kit::form('abbiamo')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.reception', [
                Kit::line('Receptionist', 'La sua camera è la tre.'),
                Kit::line('Anna', 'Il bagno è in camera?'),
                Kit::line('Receptionist', "Sì, c'è un bagno in camera. Ecco la chiave."),
                Kit::line('Anna', 'E la colazione?'),
                Kit::line('Receptionist', 'La colazione è inclusa.'),
            ], [
                Kit::question('Which room is it?', ['two', 'three', 'four'], 'three'),
                Kit::question('Is there a bathroom in the room?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What does Anna ask about last?', ['The key', 'The bathroom', 'The breakfast'], 'The breakfast'),
            ], [
                Kit::question('Who gives Anna the information?', ['Another guest', 'The receptionist', 'The conversation does not say.'], 'The receptionist'),
                Kit::question('What is included?', ['The breakfast', 'The key', 'Nothing'], 'The breakfast'),
                Kit::question('How many people speak?', ['One', 'Three', 'Two'], 'Two'),
            ], [Kit::word('la camera'), Kit::word('il bagno'), Kit::word('la chiave'), Kit::word('la colazione'), Kit::word('incluso')]),
            Kit::listenType($stage, 'task.listen_type.saluto', 'Buonasera, ho una prenotazione.', 'Good evening, I have a reservation.', [Kit::word('la prenotazione', 'prenotazione'), Kit::form('ho')], homophoneNote: 'Ho (I have) and o (or) sound the same. The h is silent and only shows in writing.'),
            Kit::listenType($stage, 'task.listen_type.notti', 'La camera è disponibile per due notti.', 'The room is available for two nights.', [Kit::word('la camera', 'camera'), Kit::word('disponibile'), Kit::word('la notte', 'notti')], homophoneNote: 'È (is) and e (and) sound close. The accent on è is only seen in writing.'),
            Kit::listenType($stage, 'task.listen_type.chiave', 'Hai la chiave?', 'Do you have the key?', [Kit::word('la chiave', 'chiave'), Kit::form('hai')], homophoneNote: 'Hai (you have) and ai (to the) sound the same. The h is silent and only shows in writing.'),

            Kit::speakAnswer($stage, 'task.speak_answer.notti', 'Per quante notti?', 'For how many nights?', [['uno', 'una', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci']], 'Per due notti.', [Kit::word('la notte', 'notti')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.colazione', "C'è la colazione?", 'Is there breakfast?', [['sì', 'no', "c'è"]], "Sì, c'è la colazione.", [Kit::word('la colazione', 'colazione'), Kit::form("c'è", true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.incluso', 'La colazione è inclusa?', 'Is breakfast included?', [['sì', 'no', 'è', 'inclusa', 'incluso']], 'Sì, è inclusa.', [Kit::word('la colazione', 'colazione'), Kit::word('incluso', 'inclusa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chiave', 'Ha la chiave?', 'Do you have the key?', [['sì', 'no', 'ho', 'abbiamo']], 'Sì, ho la chiave.', [Kit::word('la chiave', 'chiave'), Kit::form('ho')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.albergo', 'Buonasera, siamo in albergo.', 'Good evening, we are in the hotel.', [Kit::word("l'albergo", 'albergo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.receptionist', 'Buonasera, sono il receptionist.', 'Good evening, I am the receptionist.', [Kit::word('il receptionist', 'receptionist')], 'speak', ['Buonasera, sono la receptionist.']),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.bagno', 'The bathroom is here, in the room.', ['Il bagno è qui, in camera.'], [Kit::word('il bagno', 'bagno'), Kit::word('la camera', 'camera')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.camere', 'There are no rooms available.', ['Non ci sono camere disponibili.'], [Kit::word('la camera', 'camere'), Kit::word('disponibile', 'disponibili'), Kit::form('ci sono', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.receptionist', 'The receptionist has the reservation.', ['Il receptionist ha la prenotazione.', 'La receptionist ha la prenotazione.'], [Kit::word('il receptionist', 'receptionist'), Kit::word('la prenotazione', 'prenotazione'), Kit::form('ha')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.colazione', 'In the hotel, breakfast is included.', ['In albergo la colazione è inclusa.', 'In albergo, la colazione è inclusa.', 'La colazione è inclusa in albergo.', 'In hotel la colazione è inclusa.', 'In hotel, la colazione è inclusa.', 'La colazione è inclusa in hotel.'], [Kit::word("l'albergo", 'albergo', ['hotel']), Kit::word('la colazione', 'colazione'), Kit::word('incluso', 'inclusa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta', 'Marta ___ la chiave.', 'Marta has the key.', 'ha', Kit::form('ha', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.anna-marta', 'Anna e Marta ___ una camera.', 'Anna and Marta have a room.', 'hanno', Kit::form('hanno', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.prenotazione', 'Ho una prenotazione per tre notti.', 'I have a reservation for three nights.', [Kit::word('la prenotazione', 'prenotazione'), Kit::word('la notte', 'notti'), Kit::form('ho')], 'dictation', $set, homophoneNote: 'Ho (I have) and o (or) sound the same. The h is silent and only shows in writing.'),
            Kit::listenType($stage, 'check.a.listen_type.camera', 'Abbiamo una camera disponibile.', 'We have a room available.', [Kit::word('la camera', 'camera'), Kit::word('disponibile'), Kit::form('abbiamo')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.chiave', 'La chiave del bagno è qui.', 'The key to the bathroom is here.', [Kit::word('la chiave', 'chiave'), Kit::word('il bagno', 'bagno')], 'dictation', $set, homophoneNote: 'È (is) and e (and) sound close. The accent on è is only seen in writing.'),
            Kit::listenPassage($stage, 'check.a.listen_passage.reception', [
                Kit::line('Receptionist', 'Buonasera. La sua camera è la cinque.'),
                Kit::line('Anna', "Grazie. Ma c'è la colazione?"),
                Kit::line('Receptionist', "No, non c'è la colazione. Ecco la sua chiave."),
            ], [
                Kit::question('Which room is it?', ['three', 'four', 'five'], 'five'),
                Kit::question('Is there breakfast?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What does the receptionist give Anna?', ['The bill', 'The key', 'The breakfast'], 'The key'),
            ], [
                Kit::question('What does the receptionist say first?', ['Good morning', 'Good afternoon', 'Good evening'], 'Good evening'),
                Kit::question('Who asks about breakfast?', ['Anna', 'The receptionist', 'Nobody'], 'Anna'),
                Kit::question('Is the room number three?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('la camera'), Kit::word('la colazione'), Kit::word('la chiave')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.reception', 'Read the conversation.', [
                Kit::line('Receptionist', 'Buongiorno. La prenotazione è per tre notti.'),
                Kit::line('Anna', "Va bene. C'è anche la colazione?"),
                Kit::line('Receptionist', "Sì, c'è la colazione."),
            ], [
                Kit::question('How many nights is the reservation for?', ['two', 'three', 'four'], 'three'),
                Kit::question('Is there breakfast?', ['No', 'The text does not say.', 'Yes'], 'Yes'),
            ], [Kit::word('la prenotazione'), Kit::word('la notte'), Kit::word('la colazione')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.prenotazione', 'Ha una prenotazione per due notti?', 'Do you have a reservation for two nights?', [['sì', 'no', 'ho', 'abbiamo']], 'Sì, ho una prenotazione.', [Kit::word('la prenotazione')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.notti', 'Quante notti?', 'How many nights?', [['uno', 'una', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci']], 'Due notti.', [Kit::word('la notte')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.colazione', "In albergo, c'è la colazione?", 'In the hotel, is there breakfast?', [['sì', 'no', "c'è"]], "Sì, c'è la colazione.", [Kit::word('la colazione'), Kit::word("l'albergo")], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.receptionist', 'The receptionist has two keys.', ['Il receptionist ha due chiavi.', 'La receptionist ha due chiavi.'], [Kit::word('il receptionist', 'receptionist'), Kit::word('la chiave', 'chiavi'), Kit::form('ha')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.bagno', 'Is there a bathroom in the hotel?', ["C'è un bagno in albergo?", "C'è un bagno in hotel?"], [Kit::word('il bagno', 'bagno'), Kit::word("l'albergo", 'albergo', ['hotel']), Kit::form("c'è", true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.colazione', 'In the hotel, breakfast is not included.', ['In albergo la colazione non è inclusa.', 'In albergo, la colazione non è inclusa.', 'La colazione non è inclusa in albergo.', 'In hotel la colazione non è inclusa.', 'In hotel, la colazione non è inclusa.', 'La colazione non è inclusa in hotel.'], [Kit::word("l'albergo", 'albergo', ['hotel']), Kit::word('la colazione', 'colazione'), Kit::word('incluso', 'inclusa')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.camere', 'We have two rooms available with a bathroom for one night.', ['Abbiamo due camere disponibili con bagno per una notte.', 'Noi abbiamo due camere disponibili con bagno per una notte.', 'Abbiamo due camere con bagno disponibili per una notte.'], [Kit::word('la camera', 'camere'), Kit::word('disponibile', 'disponibili'), Kit::word('il bagno', 'bagno'), Kit::word('la notte', 'notte'), Kit::form('abbiamo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.voi', 'Voi ___ la prenotazione?', 'Do you (all) have the reservation?', 'avete', Kit::form('avete'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.lui', 'Lui ___ una camera.', 'He has a room.', 'ha', Kit::form('ha', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.colazione', 'La colazione è inclusa, la camera è disponibile.', 'Breakfast is included, the room is available.', [Kit::word('la colazione', 'colazione'), Kit::word('incluso', 'inclusa'), Kit::word('la camera', 'camera'), Kit::word('disponibile')], 'dictation', $set, homophoneNote: 'È (is) and e (and) sound close. The accent on è is only seen in writing.'),
            Kit::listenType($stage, 'check.b.listen_type.prenotazione', 'Hanno una prenotazione per una notte.', 'They have a reservation for one night.', [Kit::word('la prenotazione', 'prenotazione'), Kit::word('la notte', 'notte'), Kit::form('hanno')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.receptionist', 'Il receptionist ha la chiave e la prenotazione.', 'The receptionist has the key and the reservation.', [Kit::word('il receptionist', 'receptionist'), Kit::word('la chiave', 'chiave'), Kit::word('la prenotazione', 'prenotazione')], 'dictation', $set, homophoneNote: 'Ha (has) and a (to) sound the same, and e (and) sounds close to è. The h is silent and only shows in writing.', alsoAccepted: ['La receptionist ha la chiave e la prenotazione.']),
        ];
    }
}
