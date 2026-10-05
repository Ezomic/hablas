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

final class CheckingIntoAHotel implements UnitContent
{
    private const string EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const string A_NOTE = 'A in il y a is a form of avoir, and à (at, to) sounds the same but has an accent. The sentence tells you which is which.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [
            new WordData('l\'hôtel', cue: 'hotel'),
            new WordData('la chambre', cue: 'room (in a hotel)'),
            new WordData('la réservation', cue: 'reservation'),
            new WordData('la clé', cue: 'key', accepted: ['la clef']),
            new WordData('le réceptionniste', cue: 'receptionist (a man)', accepted: ['la réceptionniste']),
            new WordData('disponible', cue: 'available', forms: ['disponibles']),
            new WordData('la nuit', cue: 'night'),
            new WordData('la salle de bains', cue: 'bathroom (the room with the bath or shower; the toilet is les toilettes)', accepted: ['la salle de bain']),
            new WordData('compris', cue: 'included (masculine)', forms: ['comprise', 'comprises']),
            new WordData('le petit-déjeuner', cue: 'breakfast'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'J\'ai une réservation.', 'english' => 'I have a reservation.'],
            ['text' => 'Il y a une chambre disponible.', 'english' => 'There is a room available.'],
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
            Kit::gap($stage, 'sentences.choose_gap.nous-reservation', 'Nous ___ une réservation.', ['avons', 'sommes', 'avez'], 'avons', Kit::form('avons', true), 'Having something takes avoir: nous avons. Être says who or how you are.', 'choose', 'We have a reservation.'),
            Kit::gap($stage, 'sentences.choose_gap.il-y-a', '___ une chambre disponible.', ['Il y a', 'Il est', 'Elle est'], 'Il y a', Kit::form('il y a', true), 'There is says that something exists and is il y a. Être does not mean there is.', 'choose', 'There is a room available.'),
            Kit::gap($stage, 'sentences.choose_gap.vous-chambre', 'Vous ___ une chambre ?', ['avez', 'êtes', 'avons'], 'avez', Kit::form('avez', true), 'Do you have takes avoir: vous avez. Être would say what you are.', 'choose', 'Do you have a room?'),
            Kit::gap($stage, 'sentences.choose_gap.jai-nuits', '___ une réservation pour deux nuits.', ["J'ai", 'Je suis', 'Il est'], "J'ai", Kit::form("j'ai", true), "I have takes avoir: j'ai. Je suis would say who or how you are.", 'choose', 'I have a reservation for two nights.'),
            Kit::gap($stage, 'sentences.choose_gap.paul-cle', 'Paul ___ la clé.', ['a', 'est', 'ai'], 'a', Kit::form('a'), 'He and she take a: il a, elle a.', 'choose', 'Paul has the key.'),
            Kit::gap($stage, 'sentences.choose_gap.ils-chambre', 'Ils ___ une chambre.', ['ont', 'sont', 'avons'], 'ont', Kit::form('ont', true), 'They have takes ont. Sont would say what they are.', 'choose', 'They have a room.'),

            Kit::typeGap($stage, 'sentences.type_gap.nous-chambre', 'Nous ___ une chambre.', 'We have a room.', 'avons', Kit::form('avons'), 'Nous (we) takes avons.'),
            Kit::typeGap($stage, 'sentences.type_gap.il-y-a', '___ une chambre disponible.', 'There is a room available.', 'Il y a', Kit::form('il y a', true), 'There is is il y a. It stays the same for one thing or several.'),
            Kit::typeGap($stage, 'sentences.type_gap.vous-cle', 'Vous ___ la clé.', 'You have the key.', 'avez', Kit::form('avez'), 'Vous (you) takes avez.'),
            Kit::typeGap($stage, 'sentences.type_gap.ils-reservation', 'Ils ___ une réservation.', 'They have a reservation.', 'ont', Kit::form('ont'), 'Ils and elles (they) take ont.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-cle', 'Anne, tu ___ la clé ?', 'Anne, do you have the key?', 'as', Kit::form('as'), 'Tu (you, to a friend) takes as.'),
            Kit::translate($stage, 'sentences.translate.reservation', 'I have a reservation.', ["J'ai une réservation."], [Kit::word('la réservation', 'réservation'), Kit::form("j'ai", true)]),
            Kit::translate($stage, 'sentences.translate.petit-dejeuner', 'The breakfast is included.', ['Le petit-déjeuner est compris.'], [Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris')]),
            Kit::translate($stage, 'sentences.translate.hotel', 'The hotel has a room available.', ["L'hôtel a une chambre disponible."], [Kit::word("l'hôtel"), Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::form('a')]),
            Kit::build($stage, 'sentences.build.il-y-a', 'There is a room available.', 'Il y a une chambre disponible.', ['est'], [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::form('il y a', true)]),
            Kit::build($stage, 'sentences.build.cle', 'The key is in the room.', 'La clé est dans la chambre.', ['a'], [Kit::word('la clé', 'clé'), Kit::word('la chambre', 'chambre')]),
            Kit::build($stage, 'sentences.build.nous-avons', 'We have a room for two nights.', 'Nous avons une chambre pour deux nuits.', ['sommes'], [Kit::word('la chambre', 'chambre'), Kit::word('la nuit', 'nuits'), Kit::form('avons', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.cle', 'La clé de la chambre est ici.', ['The room key is here.', 'The hotel is here.', 'The breakfast is here.', 'The bathroom is here.'], 'The room key is here.', [Kit::word('la clé', 'clé'), Kit::word('la chambre', 'chambre')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.il-y-a', 'Il y a une chambre disponible.', ['There is a room available.', 'The room is not available.', 'There is a key available.', 'There is a bathroom in the room.'], 'There is a room available.', [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::form('il y a', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.salle-de-bains', 'La salle de bains est ici.', ['The bathroom is here.', 'The key is here.', 'The hotel is here.', 'The breakfast is here.'], 'The bathroom is here.', [Kit::word('la salle de bains', 'salle de bains')]),
            Kit::listenType($stage, 'sentences.listen_type.reservation', 'Nous avons une réservation.', 'We have a reservation.', [Kit::word('la réservation', 'réservation'), Kit::form('avons')]),
            Kit::listenType($stage, 'sentences.listen_type.petit-dejeuner', 'Le petit-déjeuner est compris.', 'Breakfast is included.', [Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.il-y-a', 'Il y a une chambre ici.', 'There is a room here.', [Kit::word('la chambre', 'chambre'), Kit::form('il y a', true)], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.receptionniste', "Voici le réceptionniste de l'hôtel.", 'Here is the hotel receptionist.', [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word("l'hôtel")]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.nous-avons', 'Nous avons une chambre disponible.', 'We have a room available.', [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::form('avons')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.salle-de-bains', 'Où est la salle de bains ?', 'Where is the bathroom?', [Kit::word('la salle de bains', 'salle de bains')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.receptionniste', 'Le réceptionniste a la clé.', 'The receptionist has the key.', [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word('la clé', 'clé'), Kit::form('a')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hotel', "J'ai une réservation pour deux nuits.", 'I have a reservation for two nights.', [Kit::word('la nuit', 'nuits'), Kit::word('la réservation', 'réservation'), Kit::form("j'ai")]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.reservation', 'Vous avez une réservation ?', 'Do you have a reservation?', [['oui', 'non', "j'ai", 'avons'], ['réservation']], "Oui, j'ai une réservation.", [Kit::word('la réservation', 'réservation')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cle', 'Où est la clé ?', 'Where is the key?', [['clé', 'est', 'voici', "c'est"], ['ici', 'là', 'chambre', 'clé']], 'La clé est ici.', [Kit::word('la clé', 'clé')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.petit-dejeuner', 'Le petit-déjeuner est compris ?', 'Is breakfast included?', [['oui', 'non', 'est', "c'est"], ['compris', 'comprise']], 'Oui, il est compris.', [Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.reception', 'Read the conversation at the reception desk.', [
                Kit::line('Réceptionniste', "Bonsoir, je suis le réceptionniste de l'hôtel. Vous avez une réservation ?"),
                Kit::line('Anne', "Oui, j'ai une réservation pour deux nuits."),
                Kit::line('Réceptionniste', 'Très bien. La chambre est disponible. Voici la clé.'),
                Kit::line('Anne', 'Où est la salle de bains ?'),
                Kit::line('Réceptionniste', 'Elle est dans la chambre. Le petit-déjeuner est compris.'),
                Kit::line('Anne', 'Merci beaucoup.'),
            ], [
                Kit::question('How many nights is the reservation for?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Where is the bathroom?', ['In the room.', 'Next to the reception desk.', 'The text does not say.'], 'In the room.'),
                Kit::question('Is breakfast included?', ['Yes, it is included.', 'No, it is not included.', 'The text does not say.'], 'Yes, it is included.'),
            ], [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word("l'hôtel"), Kit::word('la réservation', 'réservation'), Kit::word('la nuit', 'nuits'), Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::word('la clé', 'clé'), Kit::word('la salle de bains', 'salle de bains'), Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris')], 'read'),
            Kit::gap($stage, 'task.choose_gap.disponibles', 'Les chambres sont ___.', ['disponibles', 'disponible'], 'disponibles', Kit::word('disponible', 'disponibles'), 'The adjective agrees with the noun: more than one room, so disponibles.', 'read'),
            Kit::gap($stage, 'task.choose_gap.compris', 'Le petit-déjeuner est ___.', ['compris', 'comprise'], 'compris', Kit::word('compris'), 'Petit-déjeuner is masculine, so the adjective is compris.', 'read'),

            Kit::transform($stage, 'task.transform.nous', 'Change the subject to nous.', "J'ai une réservation.", ['Nous avons une réservation.'], [Kit::word('la réservation', 'réservation'), Kit::form('avons')]),
            Kit::transform($stage, 'task.transform.vous', 'Change the subject to vous.', 'Nous avons la clé.', ['Vous avez la clé.'], [Kit::word('la clé', 'clé'), Kit::form('avez')]),
            Kit::transform($stage, 'task.transform.pluriel', 'Make it plural.', 'La chambre est disponible.', ['Les chambres sont disponibles.'], [Kit::word('la chambre', 'chambres'), Kit::word('disponible', 'disponibles')]),
            Kit::writeGuided($stage, 'task.write_guided.reservation', 'Say that you have a reservation for two nights and ask whether breakfast is included.', ['réservation', 'nuits', 'petit-déjeuner', 'compris'], "J'ai une réservation pour deux nuits. Le petit-déjeuner est compris ?", [
                ['forms' => ['réservation'], 'term' => 'la réservation'],
                ['forms' => ['nuits', 'nuit'], 'term' => 'la nuit'],
                ['forms' => ['petit-déjeuner'], 'term' => 'le petit-déjeuner'],
                ['forms' => ['compris', 'comprise'], 'term' => 'compris'],
            ], [Kit::word('la réservation'), Kit::word('la nuit'), Kit::word('le petit-déjeuner'), Kit::word('compris')]),
            Kit::writeGuided($stage, 'task.write_guided.chambre', 'Ask whether they have a room available and where the bathroom is.', ['chambre', 'disponible', 'salle de bains'], 'Vous avez une chambre disponible ? Où est la salle de bains ?', [
                ['forms' => ['chambre', 'chambres'], 'term' => 'la chambre'],
                ['forms' => ['disponible', 'disponibles'], 'term' => 'disponible'],
                ['forms' => ['bains', 'bain'], 'term' => 'la salle de bains'],
            ], [Kit::word('la chambre'), Kit::word('disponible'), Kit::word('la salle de bains')]),
            Kit::build($stage, 'task.build.il-y-a', 'There is a room available for two nights.', 'Il y a une chambre disponible pour deux nuits.', ['sommes', 'ai'], [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::word('la nuit', 'nuits'), Kit::form('il y a', true)], 'write'),
            Kit::build($stage, 'task.build.cle-hotel', 'We have the room key.', 'Nous avons la clé de la chambre.', ['sommes', 'avez'], [Kit::word('la clé', 'clé'), Kit::word('la chambre', 'chambre'), Kit::form('avons', true)], 'write'),
            Kit::build($stage, 'task.build.receptionniste', 'The room is available and the receptionist has the key.', 'La chambre est disponible et le réceptionniste a la clé.', ['ai', 'sont'], [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::word('le réceptionniste', 'réceptionniste'), Kit::form('a')], 'write'),
            Kit::translate($stage, 'task.translate.reservation', 'Do you have a reservation? (formal)', ['Vous avez une réservation ?', 'Avez-vous une réservation ?'], [Kit::word('la réservation', 'réservation'), Kit::form('avez', true)], 'write'),
            Kit::translate($stage, 'task.translate.hotel', 'The hotel has two rooms available.', ["L'hôtel a deux chambres disponibles."], [Kit::word("l'hôtel"), Kit::word('la chambre', 'chambres'), Kit::word('disponible', 'disponibles'), Kit::form('a')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.reception', [
                Kit::line('Réceptionniste', 'Bonsoir. Votre chambre est la chambre trois.'),
                Kit::line('Paul', 'Où est la salle de bains ?'),
                Kit::line('Réceptionniste', 'Elle est dans la chambre. Voici la clé.'),
                Kit::line('Paul', 'Et le petit-déjeuner ?'),
                Kit::line('Réceptionniste', 'Il est compris.'),
            ], [
                Kit::question('Which room is it?', ['Two', 'Three', 'Four'], 'Three'),
                Kit::question('Where is the bathroom?', ['Next to the reception desk.', 'In the room.', 'The conversation does not say.'], 'In the room.'),
                Kit::question('What does Paul ask about last?', ['The key', 'The bathroom', 'The breakfast'], 'The breakfast'),
            ], [
                Kit::question('Who gives Paul the information?', ['Another guest', 'The receptionist', 'The conversation does not say.'], 'The receptionist'),
                Kit::question('What is included?', ['The breakfast', 'The key', 'Nothing'], 'The breakfast'),
                Kit::question('How many people speak?', ['One', 'Three', 'Two'], 'Two'),
            ], [Kit::word('la chambre', 'chambre'), Kit::word('la salle de bains', 'salle de bains'), Kit::word('la clé', 'clé'), Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.reservation', "Bonsoir, j'ai une réservation.", 'Good evening, I have a reservation.', [Kit::word('la réservation', 'réservation'), Kit::form("j'ai")], 'listen'),
            Kit::listenType($stage, 'task.listen_type.chambre', 'La chambre est disponible pour deux nuits.', 'The room is available for two nights.', [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::word('la nuit', 'nuits')], 'listen', homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'task.listen_type.cle', 'Où est la clé de la chambre ?', 'Where is the key to the room?', [Kit::word('la clé', 'clé'), Kit::word('la chambre', 'chambre')], 'listen', homophoneNote: 'Où (where) and ou (or) sound the same: the accent marks the question word. Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which.'),

            Kit::speakAnswer($stage, 'task.speak_answer.nuits', 'Pour combien de nuits ?', 'For how many nights?', [['une', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix']], 'Pour deux nuits.', [Kit::word('la nuit', 'nuits')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.salle-de-bains', 'Où est la salle de bains ?', 'Where is the bathroom?', [['salle', 'bains', 'elle', 'est', "c'est"], ['chambre', 'ici', 'là', 'dans']], 'La salle de bains est dans la chambre.', [Kit::word('la salle de bains', 'salle de bains')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.receptionniste', 'Vous êtes le réceptionniste ?', 'Are you the receptionist?', [['oui', 'non', 'suis'], ['réceptionniste']], 'Oui, je suis le réceptionniste.', [Kit::word('le réceptionniste', 'réceptionniste')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chambre', 'Vous avez une chambre disponible ?', 'Do you have a room available?', [['oui', 'non', 'avons', 'il'], ['chambre', 'disponible', 'disponibles']], 'Oui, nous avons une chambre disponible.', [Kit::word('la chambre', 'chambre'), Kit::word('disponible')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.reservation', "Bonsoir, j'ai une réservation pour deux nuits.", 'Good evening, I have a reservation for two nights.', [Kit::word('la réservation', 'réservation'), Kit::word('la nuit', 'nuits'), Kit::form("j'ai")], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.receptionniste', "Voici le réceptionniste de l'hôtel.", 'Here is the hotel receptionist.', [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word("l'hôtel")], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.cle', 'Is there a key in the room?', ['Il y a une clé dans la chambre ?'], [Kit::word('la clé', 'clé'), Kit::word('la chambre', 'chambre'), Kit::form('il y a', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.reservation', 'I have a reservation for one night.', ["J'ai une réservation pour une nuit."], [Kit::word('la réservation', 'réservation'), Kit::word('la nuit', 'nuit'), Kit::form("j'ai", true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.receptionniste', 'The receptionist has two keys at the hotel.', ["Le réceptionniste a deux clés à l'hôtel.", "La réceptionniste a deux clés à l'hôtel."], [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word('la clé', 'clés'), Kit::word("l'hôtel"), Kit::form('a')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.petit-dejeuner', 'Breakfast is included for two nights.', ['Le petit-déjeuner est compris pour deux nuits.'], [Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris'), Kit::word('la nuit', 'nuits')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.avez', 'Vous ___ une chambre pour deux nuits ?', 'Do you have a room for two nights?', 'avez', Kit::form('avez', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ont', 'Elles ___ une réservation.', 'They have a reservation.', 'ont', Kit::form('ont', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.nous-avons', 'Nous avons une chambre disponible pour trois nuits.', 'We have a room available for three nights.', [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::word('la nuit', 'nuits'), Kit::form('avons')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.salle-de-bains', 'Le réceptionniste est ici avec la clé.', 'The receptionist is here with the key.', [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word('la clé', 'clé')], 'dictation', $set, homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.hotel', 'La chambre est disponible, avec une salle de bains.', 'The room is available, with a bathroom.', [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::word('la salle de bains', 'salle de bains')], 'dictation', $set, homophoneNote: self::EST_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.reception', [
                Kit::line('Anne', "Bonjour. Une chambre pour trois nuits, s'il vous plaît."),
                Kit::line('Réceptionniste', 'Oui, nous avons une chambre. Voici la clé, Anne.'),
                Kit::line('Anne', 'Il y a un petit-déjeuner ?'),
                Kit::line('Réceptionniste', "Oui, c'est compris."),
            ], [
                Kit::question('How many nights does Anne want the room for?', ['Two', 'Three', 'Four'], 'Three'),
                Kit::question('Is a room available?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What does the receptionist give Anne?', ['The key', 'The bill', 'The breakfast'], 'The key'),
            ], [
                Kit::question('Who asks about breakfast?', ['Anne', 'The receptionist', 'Nobody'], 'Anne'),
                Kit::question('Is breakfast included?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Does Anne want the room for one night?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('la chambre', 'chambre'), Kit::word('la nuit', 'nuits'), Kit::word('la clé', 'clé'), Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.reception', 'Read the conversation.', [
                Kit::line('Réceptionniste', "Bonsoir, Marie. Je suis le réceptionniste de l'hôtel."),
                Kit::line('Réceptionniste', "Vous avez une réservation à l'hôtel ?"),
                Kit::line('Marie', 'Oui. La salle de bains est dans la chambre ?'),
                Kit::line('Réceptionniste', 'Oui, elle est dans la chambre.'),
            ], [
                Kit::question('Does Marie have a reservation?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Where is the bathroom?', ['In the room.', 'Outside the hotel.', 'The text does not say.'], 'In the room.'),
            ], [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word('la réservation', 'réservation'), Kit::word("l'hôtel"), Kit::word('la salle de bains', 'salle de bains'), Kit::word('la chambre', 'chambre')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.reservation', 'Anne, vous avez une réservation ?', 'Anne, do you have a reservation?', [['oui', 'non', "j'ai", 'avons'], ['réservation']], "Oui, j'ai une réservation.", [Kit::word('la réservation', 'réservation')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.nuits', "Combien de nuits, s'il vous plaît ?", 'How many nights, please?', [['une', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix']], 'Deux nuits.', [Kit::word('la nuit', 'nuits')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.salle-de-bains', 'Dans la chambre, il y a une salle de bains ?', 'Is there a bathroom in the room?', [['oui', 'non', 'a', 'il'], ['salle', 'bains', 'elle', 'dans', 'chambre', 'là']], 'Oui, il y a une salle de bains.', [Kit::word('la salle de bains', 'salle de bains'), Kit::word('la chambre', 'chambre')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.receptionniste', 'Does the receptionist have the room key?', ['Le réceptionniste a la clé de la chambre ?', 'La réceptionniste a la clé de la chambre ?'], [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word('la clé', 'clé'), Kit::word('la chambre', 'chambre'), Kit::form('a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.petit-dejeuner', 'The reservation is for one night, breakfast included.', ['La réservation est pour une nuit, petit-déjeuner compris.'], [Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris'), Kit::word('la réservation', 'réservation'), Kit::word('la nuit', 'nuit')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.il-y-a', 'There are two rooms available at the hotel.', ["Il y a deux chambres disponibles à l'hôtel."], [Kit::word("l'hôtel"), Kit::word('la chambre', 'chambres'), Kit::word('disponible', 'disponibles'), Kit::form('il y a', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.avez', 'Do you have a room available with a bathroom? (formal)', ['Vous avez une chambre disponible avec une salle de bains ?', 'Avez-vous une chambre disponible avec une salle de bains ?', 'Vous avez une chambre disponible avec salle de bains ?'], [Kit::word('la chambre', 'chambre'), Kit::word('disponible'), Kit::word('la salle de bains', 'salle de bains'), Kit::form('avez', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ont', "Anne et Paul ___ une chambre à l'hôtel.", 'Anne and Paul have a room at the hotel.', 'ont', Kit::form('ont', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.jai', '___ la clé de la chambre.', 'I have the room key.', "J'ai", Kit::form("j'ai"), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.reservation', 'Nous avons une réservation pour trois nuits, petit-déjeuner compris.', 'We have a reservation for three nights, breakfast included.', [Kit::word('la réservation', 'réservation'), Kit::word('la nuit', 'nuits'), Kit::word('le petit-déjeuner', 'petit-déjeuner'), Kit::word('compris'), Kit::form('avons')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.receptionniste', "Le réceptionniste est à l'hôtel avec la clé.", 'The receptionist is at the hotel with the key.', [Kit::word('le réceptionniste', 'réceptionniste'), Kit::word("l'hôtel"), Kit::word('la clé', 'clé')], 'dictation', $set, homophoneNote: 'Est (is, the verb) and et (and) sound very close, and context decides: here est is the verb. À (at, with an accent) sounds the same as a (a form of avoir, without an accent).'),
            Kit::listenType($stage, 'check.b.listen_type.salle-de-bains', 'Il y a une salle de bains ici.', 'There is a bathroom here.', [Kit::word('la salle de bains', 'salle de bains')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
