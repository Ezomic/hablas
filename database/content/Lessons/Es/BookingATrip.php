<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class BookingATrip implements UnitContent
{
    private const A_NOTE = 'A without an h means at here, in a qué hora. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'booking-a-trip';
    }

    public function words(): array
    {
        return [
            new WordData('reservar', cue: 'to book (a room, a ticket, a table)', forms: ['reservo', 'reservas', 'reserva', 'reservamos'], note: 'Reservar is the verb for booking a room, a table or a ticket. La reserva is the booking itself.'),
            new WordData('cancelar', cue: 'to cancel', forms: ['cancelo', 'cancela', 'cancelamos']),
            new WordData('confirmar', cue: 'to confirm', forms: ['confirmo', 'confirma', 'confirmamos']),
            new WordData('doble', cue: 'double (for two people)', note: 'Doble does not change for masculine or feminine: una habitación doble, una cama doble.'),
            new WordData('individual', cue: 'single (for one person)', note: 'Una habitación individual is a room for one person. Doble is for two.'),
            new WordData('completo', cue: 'full, fully booked', note: 'Completo says that a hotel, a flight or a restaurant has no places left. It agrees with the noun: el hotel está completo.'),
            new WordData('la agencia de viajes', cue: 'travel agency', accepted: ['la agencia']),
            new WordData('la oferta', cue: 'offer (a special deal)'),
            new WordData('el huésped', cue: 'guest (at a hotel)', commonGender: true, forms: ['huéspedes'], note: 'El huésped is the guest who stays at a hotel. It is the same for a man or a woman: el huésped, la huésped.'),
            new WordData('el pasajero', cue: 'passenger', accepted: ['la pasajera']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Quisiera reservar una habitación doble.', 'english' => 'I would like to book a double room.'],
            ['text' => '¿A qué hora sale el tren?', 'english' => 'At what time does the train leave?'],
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
        return [];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.quisiera', '___ reservar una habitación.', ['Quisiera', 'Reservo', 'Reservamos'], 'Quisiera', Kit::form('quisiera'), 'To ask politely, use quisiera and then the infinitive: quisiera reservar. Reservo and reservamos are conjugated forms and do not take another verb after them.', 'choose', 'I would like to book a room.'),
            Kit::gap($stage, 'sentences.choose_gap.cuanto', '¿___ cuesta la habitación doble?', ['Cuánto', 'Cuándo', 'Cuántas'], 'Cuánto', Kit::form('cuánto', true), 'Cuánto asks about an amount or a price. Cuándo asks about a day, and cuántas is for a feminine plural noun like noches.', 'choose', 'How much does the double room cost?'),
            Kit::gap($stage, 'sentences.choose_gap.a-que-hora', '¿___ sale el autobús? A las ocho.', ['A qué hora', 'Cuánto', 'Cuántas'], 'A qué hora', Kit::form('a qué hora'), 'For a clock time ask a qué hora, like Dutch hoe laat. The answer, a las ocho, is a time.', 'choose', 'At what time does the bus leave? At eight.'),
            Kit::gap($stage, 'sentences.choose_gap.cuantas', '¿___ personas son?', ['Cuántas', 'Cuántos', 'Cuánto'], 'Cuántas', Kit::form('cuántas', true), 'Personas is feminine and plural, so the question word is cuántas. Cuántos is for masculine plurals like días.', 'choose', 'How many people are there?'),
            Kit::gap($stage, 'sentences.choose_gap.para', 'Una mesa ___ cuatro personas.', ['para', 'por', 'en'], 'para', Kit::form('para', true), 'For how many people a table or room is, use para: para cuatro personas. Dutch voor is para here, not por or en.', 'choose', 'A table for four people.'),
            Kit::gap($stage, 'sentences.choose_gap.completo', 'El hotel está ___ el lunes.', ['completo', 'incluido', 'doble'], 'completo', Kit::word('completo', 'completo'), 'Completo means there is no room left. Incluido means included and doble means double.', 'choose', 'The hotel is full on Monday.'),

            Kit::typeGap($stage, 'sentences.type_gap.agencia', 'Marta está en la ___ de viajes.', 'Marta is at the travel agency.', 'agencia', Kit::word('la agencia de viajes', 'agencia')),
            Kit::typeGap($stage, 'sentences.type_gap.oferta', 'Hay una ___ para el domingo.', 'There is a special offer for Sunday.', 'oferta', Kit::word('la oferta', 'oferta')),
            Kit::typeGap($stage, 'sentences.type_gap.huesped', 'Luis es el ___ del hotel.', 'Luis is the guest at the hotel.', 'huésped', Kit::word('el huésped', 'huésped')),
            Kit::typeGap($stage, 'sentences.type_gap.reservo', 'Yo ___ una mesa para el domingo.', 'I book a table for Sunday.', 'reservo', Kit::word('reservar', 'reservo')),
            Kit::typeGap($stage, 'sentences.type_gap.cuando', '¿___ sale el vuelo? El lunes.', 'When does the flight leave? On Monday.', 'Cuándo', Kit::form('cuándo', true), 'Cuándo asks about a day or a date, like Dutch wanneer. Cuánto is for a price and a qué hora is for a clock time.'),

            Kit::translate($stage, 'sentences.translate.habitacion-doble', 'I would like to book a double room.', ['Quisiera reservar una habitación doble.', 'Me gustaría reservar una habitación doble.'], [Kit::word('reservar'), Kit::word('doble'), Kit::form('quisiera', false, ['me gustaría'])]),
            Kit::translate($stage, 'sentences.translate.individual', 'How much is the single room?', ['¿Cuánto cuesta la habitación individual?', '¿Cuánto es la habitación individual?'], [Kit::word('individual'), Kit::form('cuánto')]),
            Kit::translate($stage, 'sentences.translate.confirmo', 'I confirm the booking for Monday.', ['Confirmo la reserva para el lunes.', 'Yo confirmo la reserva para el lunes.'], [Kit::word('confirmar', 'confirmo'), Kit::form('para')]),

            Kit::build($stage, 'sentences.build.cancelo', 'I cancel the booking for Sunday.', 'Cancelo la reserva para el domingo.', ['cancelar'], [Kit::word('cancelar', 'cancelo'), Kit::form('para')]),
            Kit::build($stage, 'sentences.build.a-que-hora', 'At what time does the flight leave?', '¿A qué hora sale el vuelo?', ['cuántas'], [Kit::form('a qué hora', true)]),
            Kit::build($stage, 'sentences.build.pasajero', 'The passenger confirms the ticket.', 'El pasajero confirma el billete.', ['confirmo'], [Kit::word('el pasajero', 'pasajero'), Kit::word('confirmar', 'confirma')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.individual', 'Quisiera una habitación individual para tres noches.', ['I would like a single room for three nights.', 'I would like a double room for three nights.', 'I would like a single room for two nights.', 'I would like to cancel a single room.'], 'I would like a single room for three nights.', [Kit::word('individual'), Kit::form('quisiera')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.completo', 'El hotel está completo el domingo.', ['The hotel is full on Sunday.', 'The hotel is full on Monday.', 'The hotel is open on Sunday.', 'The hotel has an offer on Sunday.'], 'The hotel is full on Sunday.', [Kit::word('completo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.oferta', 'La oferta es de cien euros.', ['The offer is a hundred euros.', 'The offer is fifty euros.', 'The offer is for a hundred people.', 'The room is a hundred euros.'], 'The offer is a hundred euros.', [Kit::word('la oferta', 'oferta')]),
            Kit::listenType($stage, 'sentences.listen_type.cancelar', 'Quisiera cancelar una mesa.', 'I would like to cancel a table.', [Kit::word('cancelar'), Kit::form('quisiera')]),
            Kit::listenType($stage, 'sentences.listen_type.cuando', '¿Cuándo sale el vuelo del domingo?', 'When does the Sunday flight leave?', [Kit::form('cuándo', true)]),
            Kit::listenType($stage, 'sentences.listen_type.huesped', 'El huésped confirma la reserva.', 'The guest confirms the booking.', [Kit::word('el huésped', 'huésped'), Kit::word('confirmar', 'confirma')]),
            Kit::listenType($stage, 'sentences.listen_type.reservamos', 'Reservamos una mesa para dos personas.', 'We book a table for two people.', [Kit::word('reservar', 'reservamos'), Kit::form('para')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.individual', 'Quisiera reservar una habitación individual.', 'I would like to book a single room.', [Kit::word('reservar'), Kit::word('individual'), Kit::form('quisiera')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.oferta', '¿Cuánto cuesta la oferta del domingo?', 'How much does the Sunday offer cost?', [Kit::word('la oferta', 'oferta'), Kit::form('cuánto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pasajero', 'El pasajero está en la agencia.', 'The passenger is at the travel agency.', [Kit::word('el pasajero', 'pasajero'), Kit::word('la agencia de viajes', 'agencia')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cancelar', 'Quisiera cancelar el billete para el lunes.', 'I would like to cancel the ticket for Monday.', [Kit::word('cancelar'), Kit::form('quisiera')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.doble', '¿Una habitación doble o individual?', 'A double or a single room?', [['quisiera', 'reservo', 'una'], ['doble', 'individual']], 'Quisiera una habitación doble, por favor.', [Kit::word('doble'), Kit::word('individual')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.para', '¿Para cuántas personas?', 'For how many people?', [['para'], ['una', 'un', 'dos', 'tres', 'cuatro', 'cinco', 'seis']], 'Para dos personas, por favor.', [Kit::form('para')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.reservas', '¿Reservas una mesa o una habitación?', 'Do you book a table or a room?', [['reservo'], ['mesa', 'habitación']], 'Reservo una mesa para el domingo.', [Kit::word('reservar', 'reservo')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.hotel', 'Read the conversation at the hotel.', [
                Kit::line('Ana', 'Buenos días. Quisiera reservar una habitación doble para el lunes.'),
                Kit::line('Pablo', 'Lo siento, el hotel está completo el lunes.'),
                Kit::line('Ana', '¿Y el domingo?'),
                Kit::line('Pablo', 'El domingo hay una habitación doble. Hay una oferta de cincuenta euros.'),
                Kit::line('Ana', '¿Está incluido el desayuno?'),
                Kit::line('Pablo', 'Sí. ¿Confirma la reserva?'),
                Kit::line('Ana', 'Sí, confirmo la reserva para dos personas.'),
            ], [
                Kit::question('Why can Ana not book a room for Monday?', ['The hotel is full', 'The room is too expensive', 'The hotel is closed'], 'The hotel is full'),
                Kit::question('How much is the offer?', ['Fifty euros', 'Forty euros', 'A hundred euros'], 'Fifty euros'),
                Kit::question('Is breakfast included?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('reservar'), Kit::word('doble'), Kit::word('completo'), Kit::word('la oferta', 'oferta'), Kit::word('confirmar', 'confirmo')], 'read'),
            Kit::gap($stage, 'task.choose_gap.cancelar', 'Quisiera ___ la reserva del domingo.', ['cancelar', 'cancelo', 'cancela'], 'cancelar', Kit::form('cancelar', true), 'After quisiera the verb stays in the infinitive: quisiera cancelar. Cancelo means I cancel and cancela means he or she cancels.', 'read', 'I would like to cancel the Sunday booking.'),
            Kit::gap($stage, 'task.choose_gap.a-que-hora', '¿___ es la cena? A las nueve.', ['A qué hora', 'Cuánto', 'Cuántos'], 'A qué hora', Kit::form('a qué hora', true), 'A las nueve is a clock time, so the question is a qué hora. Cuánto is for a price and cuántos is for a number of masculine things.', 'read', 'At what time is dinner? At nine.'),

            Kit::transform($stage, 'task.transform.quisiera', 'Say it politely, as I would like to.', 'Reservo una habitación individual.', ['Quisiera reservar una habitación individual.', 'Me gustaría reservar una habitación individual.'], [Kit::word('reservar'), Kit::word('individual'), Kit::form('quisiera', false, ['me gustaría'])]),
            Kit::transform($stage, 'task.transform.cuanto', 'Ask how much it costs.', 'La habitación individual cuesta cuarenta euros.', ['¿Cuánto cuesta la habitación individual?', '¿Cuánto es la habitación individual?'], [Kit::word('individual'), Kit::form('cuánto', true)]),
            Kit::transform($stage, 'task.transform.a-que-hora', 'Ask at what time it happens.', 'El tren sale a las ocho.', ['¿A qué hora sale el tren?'], [Kit::form('a qué hora')]),
            Kit::writeGuided($stage, 'task.write_guided.doble', 'Ask politely to book a double room for two nights.', ['quisiera reservar', 'una habitación doble', 'para dos noches'], 'Quisiera reservar una habitación doble para dos noches.', [
                ['forms' => ['quisiera', 'me gustaría'], 'term' => null],
                ['forms' => ['reservar'], 'term' => 'reservar'],
                ['forms' => ['doble'], 'term' => 'doble'],
            ], [Kit::word('reservar'), Kit::word('doble'), Kit::form('quisiera')]),
            Kit::writeGuided($stage, 'task.write_guided.cancelar', 'Say that you would like to cancel the booking and confirm the ticket.', ['quisiera cancelar', 'la reserva', 'y confirmar', 'el billete'], 'Quisiera cancelar la reserva y confirmar el billete.', [
                ['forms' => ['quisiera'], 'term' => null],
                ['forms' => ['cancelar'], 'term' => 'cancelar'],
                ['forms' => ['confirmar'], 'term' => 'confirmar'],
                ['forms' => ['billete'], 'term' => null],
            ], [Kit::word('cancelar'), Kit::word('confirmar'), Kit::form('quisiera')]),
            Kit::build($stage, 'task.build.huesped', 'The guest cancels the booking.', 'El huésped cancela la reserva.', ['cancelo', 'cancelar'], [Kit::word('el huésped', 'huésped'), Kit::word('cancelar', 'cancela')]),
            Kit::build($stage, 'task.build.cuantas', 'For how many people is the table?', '¿Para cuántas personas es la mesa?', ['por', 'cuántos'], [Kit::form('para')]),
            Kit::build($stage, 'task.build.confirmar', 'I would like to confirm the booking for Monday.', 'Quisiera confirmar la reserva para el lunes.', ['confirmo', 'cancelar'], [Kit::word('confirmar'), Kit::word('reservar', 'reserva'), Kit::form('quisiera')]),
            Kit::translate($stage, 'task.translate.agencia', 'Marta books a single room at the travel agency.', ['Marta reserva una habitación individual en la agencia de viajes.', 'Marta reserva una habitación individual en la agencia.'], [Kit::word('la agencia de viajes', 'agencia'), Kit::word('reservar', 'reserva'), Kit::word('individual')]),
            Kit::translate($stage, 'task.translate.completo', 'Is the hotel fully booked on Monday?', ['¿Está completo el hotel el lunes?', '¿El hotel está completo el lunes?'], [Kit::word('completo')]),

            Kit::listenPassage($stage, 'task.listen_passage.agencia', [
                Kit::line('Ana', 'Buenas tardes. Agencia de viajes.'),
                Kit::line('Luis', 'Soy pasajero del vuelo del lunes. Quisiera cancelar el billete.'),
                Kit::line('Ana', 'Hay una oferta para el domingo. Cuesta cuarenta euros.'),
                Kit::line('Luis', 'Muy bien. Confirmo el billete para el domingo.'),
            ], [
                Kit::question('What does Luis want to do with his ticket?', ['Cancel it', 'Change his name', 'Pay for it'], 'Cancel it'),
                Kit::question('What does the agency offer?', ['An offer for Sunday', 'An offer for Monday', 'A free breakfast'], 'An offer for Sunday'),
                Kit::question('How much does the offer cost?', ['Forty euros', 'Fifty euros', 'A hundred euros'], 'Forty euros'),
            ], [
                Kit::question('Who calls the agency?', ['Luis', 'Ana', 'Pablo'], 'Luis'),
                Kit::question('Is Luis a passenger?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Does Luis confirm the ticket for Monday?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('la agencia de viajes', 'agencia'), Kit::word('el pasajero', 'pasajero'), Kit::word('cancelar'), Kit::word('la oferta', 'oferta'), Kit::word('confirmar', 'confirmo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.agencia', 'La agencia de viajes confirma la reserva.', 'The travel agency confirms the booking.', [Kit::word('la agencia de viajes', 'agencia'), Kit::word('confirmar', 'confirma'), Kit::word('reservar', 'reserva')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.individual', 'Quisiera una habitación individual para el huésped.', 'I would like a single room for the guest.', [Kit::word('individual'), Kit::word('el huésped', 'huésped'), Kit::form('quisiera')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.a-que-hora', '¿A qué hora confirma la agencia la reserva?', 'At what time does the agency confirm the booking?', [Kit::word('la agencia de viajes', 'agencia'), Kit::word('confirmar', 'confirma'), Kit::form('a qué hora')], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.reservo', '¿Qué habitación reserva, doble o individual? Son dos personas.', 'Which room do you book, double or single? There are two people.', [['reservo', 'quisiera'], ['doble']], 'Reservo una habitación doble.', [Kit::word('reservar', 'reservo'), Kit::word('doble')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.agencia', 'Ana está en la agencia de viajes. ¿Dónde está Ana?', 'Ana is at the travel agency. Where is Ana?', [['agencia']], 'Ana está en la agencia de viajes.', [Kit::word('la agencia de viajes', 'agencia')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.completo', 'Hay una habitación doble libre. ¿Está completo el hotel?', 'There is a double room free. Is the hotel full?', [['no'], ['completo']], 'No, el hotel no está completo.', [Kit::word('completo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.huesped', 'Luis es el huésped de la habitación tres. ¿Quién es el huésped?', 'Luis is the guest of room three. Who is the guest?', [['luis'], ['huésped']], 'Luis es el huésped.', [Kit::word('el huésped', 'huésped')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.confirmar', 'Quisiera confirmar la reserva del pasajero.', 'I would like to confirm the passenger\'s booking.', [Kit::word('confirmar'), Kit::word('el pasajero', 'pasajero'), Kit::form('quisiera')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.oferta', 'La oferta del hotel es para dos personas.', 'The hotel offer is for two people.', [Kit::word('la oferta', 'oferta'), Kit::form('para')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.doble-dos', 'I would like to book a double room for two people.', ['Quisiera reservar una habitación doble para dos personas.', 'Me gustaría reservar una habitación doble para dos personas.'], [Kit::word('reservar'), Kit::word('doble'), Kit::form('quisiera', false, ['me gustaría'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.noches-oferta', 'How many nights is the offer for?', ['¿Para cuántas noches es la oferta?'], [Kit::word('la oferta', 'oferta'), Kit::form('cuántas', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.agencia-lunes', 'The agency confirms the single room for Monday.', ['La agencia confirma la habitación individual para el lunes.'], [Kit::word('la agencia de viajes', 'agencia'), Kit::word('confirmar', 'confirma'), Kit::word('individual'), Kit::form('para')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vuelo-pasajero', 'When does the passenger\'s flight leave?', ['¿Cuándo sale el vuelo del pasajero?'], [Kit::word('el pasajero', 'pasajero'), Kit::form('cuándo', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.confirmar', 'Quisiera ___ la oferta, por favor.', 'I would like to confirm the offer, please.', 'confirmar', Kit::word('confirmar'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.completo', 'Lo siento, el hotel está ___.', 'Sorry, the hotel is fully booked.', 'completo', Kit::word('completo'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.mesa', 'Quisiera reservar una mesa para el domingo.', 'I would like to book a table for Sunday.', [Kit::word('reservar'), Kit::form('quisiera')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.a-que-hora', '¿A qué hora confirma la agencia el billete?', 'At what time does the agency confirm the ticket?', [Kit::word('la agencia de viajes', 'agencia'), Kit::word('confirmar', 'confirma'), Kit::form('a qué hora')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.huesped', 'El huésped cancela la habitación doble.', 'The guest cancels the double room.', [Kit::word('el huésped', 'huésped'), Kit::word('cancelar', 'cancela'), Kit::word('doble')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.restaurante', [
                Kit::line('Pablo', 'Buenos días. Quisiera reservar una mesa.'),
                Kit::line('Marta', 'Lo siento, el restaurante está completo el domingo.'),
                Kit::line('Pablo', '¿Y el lunes?'),
                Kit::line('Marta', 'El lunes hay una mesa para cuatro personas.'),
                Kit::line('Pablo', 'Muy bien. Reservo la mesa para el lunes.'),
            ], [
                Kit::question('What does Pablo want to book?', ['A table', 'A room', 'A ticket'], 'A table'),
                Kit::question('When is the restaurant full?', ['On Sunday', 'On Monday', 'Every day'], 'On Sunday'),
                Kit::question('For how many people is the table on Monday?', ['Four', 'Two', 'Three'], 'Four'),
            ], [
                Kit::question('Who speaks first?', ['Pablo', 'Marta', 'Nobody'], 'Pablo'),
                Kit::question('Is the restaurant full on Monday?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Does Pablo book the table?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('reservar', 'reservo'), Kit::word('completo'), Kit::form('quisiera')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.billete', 'Read the conversation.', [
                Kit::line('Ana', 'Buenas tardes. Quisiera confirmar el billete del pasajero.'),
                Kit::line('Pablo', 'Sí. Cuesta cien euros.'),
                Kit::line('Ana', '¿Cuándo sale el tren?'),
                Kit::line('Pablo', 'Sale el lunes a las ocho.'),
            ], [
                Kit::question('What does Ana want to confirm?', ['A ticket', 'A room', 'A table'], 'A ticket'),
                Kit::question('How much does the ticket cost?', ['A hundred euros', 'Fifty euros', 'Forty euros'], 'A hundred euros'),
            ], [Kit::word('confirmar'), Kit::word('el pasajero', 'pasajero'), Kit::form('cuándo', true)], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.hotel', '¿Reservas un billete o una habitación?', 'Do you book a ticket or a room?', [['reservo'], ['billete', 'habitación']], 'Reservo una habitación para el lunes.', [Kit::word('reservar', 'reservo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.individual', 'Hay una habitación para una persona. ¿Es doble o individual?', 'There is a room for one person. Is it double or single?', [['individual']], 'Es una habitación individual.', [Kit::word('individual')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.huesped', 'Pablo es el huésped de la habitación dos. ¿Quién es el huésped?', 'Pablo is the guest of room two. Who is the guest?', [['pablo'], ['huésped']], 'Pablo es el huésped.', [Kit::word('el huésped', 'huésped')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.cancelar-domingo', 'I would like to cancel the double room for Sunday.', ['Quisiera cancelar la habitación doble para el domingo.', 'Me gustaría cancelar la habitación doble para el domingo.'], [Kit::word('cancelar'), Kit::word('doble'), Kit::form('quisiera', false, ['me gustaría'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.oferta-pasajero', 'How much does the offer cost for the passenger?', ['¿Cuánto cuesta la oferta para el pasajero?'], [Kit::word('la oferta', 'oferta'), Kit::word('el pasajero', 'pasajero'), Kit::form('cuánto', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.huesped-agencia', 'The guest books the room at the travel agency.', ['El huésped reserva la habitación en la agencia de viajes.', 'El huésped reserva la habitación en la agencia.'], [Kit::word('el huésped', 'huésped'), Kit::word('reservar', 'reserva'), Kit::word('la agencia de viajes', 'agencia')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.completo-cuando', 'When is the hotel fully booked?', ['¿Cuándo está completo el hotel?'], [Kit::word('completo'), Kit::form('cuándo', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.personas', 'Quisiera una mesa ___ cuatro personas.', 'I would like a table for four people.', 'para', Kit::form('para'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.confirma', 'Pablo ___ la reserva del domingo.', 'Pablo confirms the Sunday booking.', 'confirma', Kit::word('confirmar', 'confirma'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.reservamos', 'Reservamos una habitación individual para el domingo.', 'We book a single room for Sunday.', [Kit::word('reservar', 'reservamos'), Kit::word('individual')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.a-que-hora', '¿A qué hora sale el vuelo del pasajero?', 'At what time does the passenger\'s flight leave?', [Kit::word('el pasajero', 'pasajero'), Kit::form('a qué hora')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.cuantas', '¿Cuántas noches tiene la oferta del hotel?', 'How many nights does the hotel offer have?', [Kit::word('la oferta', 'oferta'), Kit::form('cuántas', true)], 'dictation', $set),
        ];
    }
}
