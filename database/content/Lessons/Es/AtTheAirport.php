<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

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
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'at-the-airport';
    }

    public function words(): array
    {
        return [
            new WordData('el aeropuerto', cue: 'airport'),
            new WordData('el vuelo', cue: 'flight'),
            new WordData('la maleta', cue: 'suitcase'),
            new WordData('el pasaporte', cue: 'passport'),
            new WordData('la puerta', cue: 'gate (at an airport)', accepted: ['la puerta de embarque']),
            new WordData('la salida', cue: 'exit, or departure (on an airport board)'),
            new WordData('la llegada', cue: 'arrival'),
            new WordData('el billete', cue: 'ticket (for a flight or train)'),
            new WordData('retrasado', cue: 'delayed (masculine)', forms: ['retrasada']),
            new WordData('internacional', cue: 'international'),
            new WordData('¿dónde?', cue: 'where? (asking about a place)'),
            new WordData('¿cuál?', cue: 'which? (asking which one)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El vuelo es internacional.', 'english' => 'The flight is international.'],
            ['text' => 'La maleta está aquí.', 'english' => 'The suitcase is here.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), Aena boards and site. Fixed: accepted la puerta de embarque for the gate cue. la salida covers exit and departure (boards read Salidas), kept. retrasado is the Aena board wording for a delayed flight (offensive only said of people). Open questions answered and removed.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.aeropuerto', '___ aeropuerto está aquí.', ['El', 'La', 'Los'], 'El', Kit::form('el'), 'Aeropuerto ends in -o, so it is masculine: el aeropuerto.', 'choose', 'The airport is here.'),
            Kit::gap($stage, 'sentences.choose_gap.maleta', '___ maleta está aquí.', ['La', 'El', 'Las'], 'La', Kit::form('la'), 'Maleta ends in -a, so it is feminine: la maleta.', 'choose', 'The suitcase is here.'),
            Kit::gap($stage, 'sentences.choose_gap.pasaporte', '___ pasaporte está aquí.', ['El', 'La', 'Las'], 'El', Kit::form('el', true), 'Pasaporte ends in -e, and that ending does not show the gender. It is masculine: el pasaporte.', 'choose', 'The passport is here.'),
            Kit::gap($stage, 'sentences.choose_gap.billete', 'Tengo ___ billete.', ['un', 'una', 'los'], 'un', Kit::form('un', true), 'Billete ends in -e, and that ending does not show the gender. It is masculine: un billete.', 'choose', 'I have a ticket.'),
            Kit::gap($stage, 'sentences.choose_gap.internacional', 'La llegada es ___.', ['internacional', 'internacionales'], 'internacional', Kit::word('internacional'), 'Internacional has one form for masculine and feminine. Only the plural adds -es.', 'choose', 'The arrival is international.'),
            Kit::gap($stage, 'sentences.choose_gap.retrasado', 'El vuelo está ___.', ['retrasado', 'retrasada'], 'retrasado', Kit::word('retrasado'), 'Retrasado agrees with the noun. Vuelo is masculine, so retrasado.', 'choose', 'The flight is delayed.'),

            Kit::typeGap($stage, 'sentences.type_gap.vuelo', '___ vuelo diez es internacional.', 'Flight ten is international.', 'El', Kit::form('el'), 'Vuelo ends in -o, so it is masculine: el vuelo.'),
            Kit::typeGap($stage, 'sentences.type_gap.puerta', '¿Dónde está ___ puerta?', 'Where is the gate?', 'la', Kit::form('la'), 'Puerta ends in -a, so it is feminine: la puerta.'),
            Kit::typeGap($stage, 'sentences.type_gap.maleta', 'Tengo ___ maleta.', 'I have a suitcase.', 'una', Kit::form('una'), 'Maleta is feminine, so a suitcase is una maleta.'),
            Kit::typeGap($stage, 'sentences.type_gap.pasaportes', '___ pasaportes están aquí.', 'The passports are here.', 'Los', Kit::form('los'), 'Masculine nouns in the plural take los: los pasaportes.'),
            Kit::typeGap($stage, 'sentences.type_gap.billete', '¿Tienes ___ billete?', 'Do you have the ticket?', 'el', Kit::form('el', true), 'Billete ends in -e, and that ending does not show the gender. It is masculine: el billete.'),

            Kit::translate($stage, 'sentences.translate.pasaporte', 'The passport is in the suitcase.', ['El pasaporte está en la maleta.'], [Kit::word('el pasaporte'), Kit::word('la maleta'), Kit::form('el', true)]),
            Kit::translate($stage, 'sentences.translate.llegada', 'The arrival is delayed.', ['La llegada está retrasada.', 'Está retrasada la llegada.'], [Kit::word('la llegada'), Kit::word('retrasado', 'retrasada'), Kit::form('la')]),
            Kit::translate($stage, 'sentences.translate.internacional', 'It is an international flight.', ['Es un vuelo internacional.'], [Kit::word('el vuelo', 'vuelo'), Kit::word('internacional'), Kit::form('un')]),

            Kit::build($stage, 'sentences.build.aeropuerto', 'We are at the airport.', 'Estamos en el aeropuerto.', ['la'], [Kit::word('el aeropuerto'), Kit::form('el')]),
            Kit::build($stage, 'sentences.build.billete', 'The ticket is here.', 'El billete está aquí.', ['la'], [Kit::word('el billete'), Kit::form('el', true)]),
            Kit::build($stage, 'sentences.build.puerta', 'Which is your gate? Gate five.', '¿Cuál es su puerta? La puerta cinco.', ['el'], [Kit::word('¿cuál?', 'cuál'), Kit::word('la puerta'), Kit::form('la')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.maleta', 'La maleta está aquí.', ['The suitcase is here.', 'The passport is here.', 'The ticket is here.', 'The gate is here.'], 'The suitcase is here.', [Kit::word('la maleta'), Kit::form('la')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vuelo', 'El vuelo está retrasado.', ['The flight is delayed.', 'The flight is international.', 'The arrival is delayed.', 'The departure is delayed.'], 'The flight is delayed.', [Kit::word('el vuelo'), Kit::word('retrasado'), Kit::form('el')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.aeropuerto', 'Es un aeropuerto internacional.', ['It is an international airport.', 'It is an international flight.', 'It is a small airport.', 'It is a national airport.'], 'It is an international airport.', [Kit::word('el aeropuerto', 'aeropuerto'), Kit::word('internacional'), Kit::form('un')]),
            Kit::listenType($stage, 'sentences.listen_type.pasaporte', 'Mi pasaporte está aquí.', 'My passport is here.', [Kit::word('el pasaporte', 'pasaporte')]),
            Kit::listenType($stage, 'sentences.listen_type.salida', 'La salida está retrasada.', 'The departure is delayed.', [Kit::word('la salida'), Kit::word('retrasado', 'retrasada'), Kit::form('la')]),
            Kit::listenType($stage, 'sentences.listen_type.billete', 'Tengo un billete para el vuelo diez.', 'I have a ticket for flight ten.', [Kit::word('el billete', 'billete'), Kit::word('el vuelo'), Kit::form('un')]),
            Kit::listenType($stage, 'sentences.listen_type.maletas', 'Las maletas están aquí.', 'The suitcases are here.', [Kit::word('la maleta', 'maletas'), Kit::form('las')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.vuelo', 'El vuelo está retrasado.', 'The flight is delayed.', [Kit::word('el vuelo'), Kit::word('retrasado'), Kit::form('el')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.puerta', '¿Dónde está la puerta?', 'Where is the gate?', [Kit::word('¿dónde?', 'dónde'), Kit::word('la puerta'), Kit::form('la')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pasaporte', '¿Cuál es su pasaporte? Mi pasaporte está aquí.', 'Which is your passport? My passport is here.', [Kit::word('¿cuál?', 'cuál'), Kit::word('el pasaporte', 'pasaporte')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.maleta', 'Mi maleta y mi pasaporte están aquí.', 'My suitcase and my passport are here.', [Kit::word('la maleta', 'maleta'), Kit::word('el pasaporte', 'pasaporte')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.salida', '¿Dónde está la salida?', 'Where is the exit?', [['salida', 'está'], ['aquí', 'allí']], 'La salida está allí.', [Kit::word('¿dónde?', 'dónde'), Kit::word('la salida'), Kit::form('la')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vuelo', '¿Es un vuelo internacional?', 'Is it an international flight?', [['sí', 'no', 'es'], ['internacional', 'vuelo']], 'Sí, es un vuelo internacional.', [Kit::word('el vuelo', 'vuelo'), Kit::word('internacional'), Kit::form('un')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.llegada', '¿La llegada está retrasada?', 'Is the arrival delayed?', [['sí', 'no', 'está'], ['retrasada', 'llegada']], 'Sí, la llegada está retrasada.', [Kit::word('la llegada'), Kit::word('retrasado', 'retrasada'), Kit::form('la')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.facturacion', 'Read the conversation at the airport.', [
                Kit::line('Agente', 'Buenos días. ¿Tiene su pasaporte y su billete?'),
                Kit::line('Ana', 'Sí, aquí están. Es un vuelo internacional.'),
                Kit::line('Agente', 'Muy bien. ¿Tiene una maleta?'),
                Kit::line('Ana', 'Sí, tengo una maleta. ¿Cuál es mi puerta?'),
                Kit::line('Agente', 'Su puerta es la cinco. El vuelo está retrasado.'),
                Kit::line('Ana', 'Gracias.'),
            ], [
                Kit::question('How many suitcases does Ana have?', ['None', 'One', 'Two'], 'One'),
                Kit::question('Which gate is it?', ['Gate three', 'Gate five', 'Gate ten'], 'Gate five'),
                Kit::question('What is the problem with the flight?', ['It is delayed.', 'It is full.', 'Ana has no ticket.'], 'It is delayed.'),
            ], [Kit::word('el pasaporte'), Kit::word('el billete'), Kit::word('el vuelo'), Kit::word('internacional'), Kit::word('la maleta'), Kit::word('la puerta'), Kit::word('retrasado'), Kit::word('¿cuál?', 'cuál')]),
            Kit::gap($stage, 'task.choose_gap.vuelos', 'Los vuelos están ___.', ['retrasados', 'retrasado'], 'retrasados', Kit::word('retrasado', 'retrasados'), 'The adjective agrees with the noun: more than one flight, so retrasados.', 'read'),
            Kit::gap($stage, 'task.choose_gap.llegadas', 'Las llegadas son ___.', ['internacionales', 'internacional'], 'internacionales', Kit::word('internacional', 'internacionales'), 'Internacional has no separate feminine form, but it takes -es in the plural.', 'read'),

            Kit::transform($stage, 'task.transform.vuelo', 'Make it plural.', 'El vuelo está retrasado.', ['Los vuelos están retrasados.'], [Kit::word('el vuelo', 'los vuelos'), Kit::word('retrasado', 'retrasados'), Kit::form('los')]),
            Kit::transform($stage, 'task.transform.llegada', 'Make it plural.', 'La llegada es internacional.', ['Las llegadas son internacionales.'], [Kit::word('la llegada', 'las llegadas'), Kit::word('internacional', 'internacionales'), Kit::form('las')]),
            Kit::transform($stage, 'task.transform.pasaporte', 'Make it plural.', 'El pasaporte está aquí.', ['Los pasaportes están aquí.'], [Kit::word('el pasaporte', 'los pasaportes'), Kit::form('los', true)]),
            Kit::writeGuided($stage, 'task.write_guided.billete', 'Say that you have a ticket and a passport, and ask where the gate is.', ['billete', 'pasaporte', 'puerta', 'dónde'], 'Tengo un billete y un pasaporte. ¿Dónde está la puerta?', [
                ['forms' => ['billete'], 'term' => 'el billete'],
                ['forms' => ['pasaporte', 'pasaportes'], 'term' => 'el pasaporte'],
                ['forms' => ['puerta'], 'term' => 'la puerta'],
                ['forms' => ['dónde'], 'term' => null],
            ], [Kit::word('¿dónde?', 'dónde'), Kit::word('el billete'), Kit::word('el pasaporte'), Kit::word('la puerta')]),
            Kit::writeGuided($stage, 'task.write_guided.vuelo', 'Say that the flight is delayed and ask where your suitcase is.', ['vuelo', 'retrasado', 'dónde', 'maleta'], 'El vuelo está retrasado. ¿Dónde está mi maleta?', [
                ['forms' => ['vuelo', 'vuelos'], 'term' => 'el vuelo'],
                ['forms' => ['retrasado', 'retrasada'], 'term' => 'retrasado'],
                ['forms' => ['dónde'], 'term' => null],
                ['forms' => ['maleta', 'maletas'], 'term' => 'la maleta'],
            ], [Kit::word('¿dónde?', 'dónde'), Kit::word('el vuelo'), Kit::word('retrasado'), Kit::word('la maleta')]),
            Kit::build($stage, 'task.build.vuelo', 'The flight is international.', 'El vuelo es internacional.', ['la', 'está'], [Kit::word('el vuelo'), Kit::word('internacional'), Kit::form('el')]),
            Kit::build($stage, 'task.build.maleta', 'The suitcase and the passport are here.', 'La maleta y el pasaporte están aquí.', ['las', 'está'], [Kit::word('la maleta'), Kit::word('el pasaporte'), Kit::form('el', true)]),
            Kit::build($stage, 'task.build.billete', 'I have a ticket for the international flight.', 'Tengo un billete para el vuelo internacional.', ['una', 'la'], [Kit::word('el billete', 'billete'), Kit::word('el vuelo'), Kit::word('internacional'), Kit::form('un')]),
            Kit::translate($stage, 'task.translate.pasaporte', 'Where is the passport?', ['¿Dónde está el pasaporte?'], [Kit::word('¿dónde?', 'dónde'), Kit::word('el pasaporte'), Kit::form('el', true)]),
            Kit::translate($stage, 'task.translate.billete', 'The ticket is in the suitcase.', ['El billete está en la maleta.'], [Kit::word('el billete'), Kit::word('la maleta'), Kit::form('el', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.llegada', [
                Kit::line('Marta', 'Hola, Luis. ¿Estás en el aeropuerto?'),
                Kit::line('Luis', 'Sí, estoy en la salida.'),
                Kit::line('Marta', '¿Está retrasado el vuelo de Ana?'),
                Kit::line('Luis', 'Sí, la llegada está retrasada. Es un vuelo internacional.'),
                Kit::line('Marta', 'Gracias, Luis. Adiós.'),
            ], [
                Kit::question('Where is Luis?', ['At the exit', 'At the gate', 'At home'], 'At the exit'),
                Kit::question('How is the flight?', ['It is on time.', 'It is delayed.', 'The conversation does not say.'], 'It is delayed.'),
                Kit::question('What kind of flight is it?', ['A short flight', 'An international flight', 'The conversation does not say.'], 'An international flight'),
            ], [
                Kit::question('What does Marta say at the end?', ['Thank you and goodbye.', 'Where is the gate?', 'The flight is delayed.'], 'Thank you and goodbye.'),
                Kit::question('Who asks if the flight is delayed?', ['Luis', 'Marta', 'Ana'], 'Marta'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('el aeropuerto'), Kit::word('la llegada'), Kit::word('el vuelo'), Kit::word('retrasado'), Kit::word('internacional'), Kit::word('la salida')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.salida', 'Aquí está la salida.', 'Here is the exit.', [Kit::word('la salida'), Kit::form('la')]),
            Kit::listenType($stage, 'task.listen_type.maleta', 'Estoy en el aeropuerto con mi maleta.', 'I am at the airport with my suitcase.', [Kit::word('el aeropuerto'), Kit::word('la maleta', 'maleta'), Kit::form('el')]),
            Kit::listenType($stage, 'task.listen_type.billete', 'Tengo el pasaporte y el billete.', 'I have the passport and the ticket.', [Kit::word('el pasaporte'), Kit::word('el billete'), Kit::form('el', true)]),

            Kit::speakAnswer($stage, 'task.speak_answer.pasaporte', '¿Tiene usted su pasaporte?', 'Do you have your passport?', [['sí', 'no', 'tengo', 'está'], ['pasaporte', 'aquí']], 'Sí, aquí está mi pasaporte.', [Kit::word('el pasaporte', 'pasaporte')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.aeropuerto', '¿Estás en el aeropuerto?', 'Are you at the airport?', [['sí', 'no', 'estoy'], ['aeropuerto', 'aquí']], 'Sí, estoy en el aeropuerto.', [Kit::word('el aeropuerto'), Kit::form('el')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.puerta', '¿Cuál es su puerta?', 'Which is your gate?', [['es', 'puerta', 'la'], ['uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez']], 'Es la puerta cinco.', [Kit::word('¿cuál?', 'cuál'), Kit::word('la puerta'), Kit::form('la')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.vuelo', '¿Está retrasado el vuelo?', 'Is the flight delayed?', [['sí', 'no', 'está'], ['retrasado', 'vuelo']], 'Sí, el vuelo está retrasado.', [Kit::word('el vuelo'), Kit::word('retrasado'), Kit::form('el')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.billete', 'El billete es para un vuelo internacional.', 'The ticket is for an international flight.', [Kit::word('el billete'), Kit::word('el vuelo', 'vuelo'), Kit::word('internacional'), Kit::form('el', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.maleta', 'Mi maleta y mi billete están aquí.', 'My suitcase and my ticket are here.', [Kit::word('la maleta', 'maleta'), Kit::word('el billete', 'billete')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.aeropuerto', 'Where is the international airport?', ['¿Dónde está el aeropuerto internacional?'], [Kit::word('¿dónde?', 'dónde'), Kit::word('el aeropuerto'), Kit::word('internacional'), Kit::form('el')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pasaporte', 'My passport and my ticket are here.', ['Mi pasaporte y mi billete están aquí.', 'Aquí están mi pasaporte y mi billete.'], [Kit::word('el pasaporte', 'pasaporte'), Kit::word('el billete', 'billete')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.salida', 'The departure and the arrival are not delayed.', ['La salida y la llegada no están retrasadas.'], [Kit::word('la salida'), Kit::word('la llegada'), Kit::word('retrasado', 'retrasadas'), Kit::form('la')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.puerta', 'The gate is over there and the suitcase is here.', ['La puerta está allí y la maleta está aquí.'], [Kit::word('la puerta'), Kit::word('la maleta')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.billete', '¿Dónde está ___ billete?', 'Where is the ticket?', 'el', Kit::form('el', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pasaporte', 'Aquí está ___ pasaporte.', 'Here is the passport.', 'el', Kit::form('el', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.maletas', 'Las maletas están allí.', 'The suitcases are over there.', [Kit::word('la maleta', 'maletas'), Kit::form('las')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.maleta', 'Tengo una maleta y un pasaporte.', 'I have a suitcase and a passport.', [Kit::word('la maleta', 'maleta'), Kit::word('el pasaporte', 'pasaporte'), Kit::form('una')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.vuelo', '¿Cuál es su vuelo? Mi vuelo es internacional.', 'Which is your flight? My flight is international.', [Kit::word('¿cuál?', 'cuál'), Kit::word('el vuelo', 'vuelo'), Kit::word('internacional')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.vuelo', [
                Kit::line('Pablo', 'Buenas tardes. Tengo un billete para el vuelo siete. ¿Cuál es mi puerta, por favor?'),
                Kit::line('Empleado', 'Su puerta es la nueve.'),
                Kit::line('Pablo', 'Gracias. ¿Está retrasado mi vuelo?'),
                Kit::line('Empleado', 'No, no está retrasado.'),
                Kit::line('Pablo', 'Muchas gracias.'),
            ], [
                Kit::question('Which flight is it?', ['Flight five', 'Flight seven', 'Flight nine'], 'Flight seven'),
                Kit::question('Which gate is it?', ['Gate seven', 'Gate nine', 'Gate ten'], 'Gate nine'),
                Kit::question('Is the flight delayed?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('What does Pablo have?', ['A passport', 'A ticket', 'A suitcase'], 'A ticket'),
                Kit::question('Who asks if the flight is delayed?', ['Pablo', 'The employee', 'Nobody'], 'Pablo'),
                Kit::question('How does the conversation end?', ['Pablo says thank you.', 'Pablo asks about the gate.', 'The employee says goodbye.'], 'Pablo says thank you.'),
            ], [Kit::word('el billete'), Kit::word('el vuelo'), Kit::word('la puerta'), Kit::word('retrasado'), Kit::word('¿cuál?', 'cuál')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.maleta', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿tienes tu maleta?'),
                Kit::line('Luis', 'Sí, tengo la maleta y el pasaporte.'),
                Kit::line('Marta', 'Muy bien. La salida está allí.'),
                Kit::line('Luis', 'Gracias, Marta.'),
            ], [
                Kit::question('What does Luis have?', ['A suitcase and a passport', 'A ticket and a passport', 'Only a suitcase'], 'A suitcase and a passport'),
                Kit::question('Where is the exit?', ['Over there', 'Here', 'The text does not say.'], 'Over there'),
            ], [Kit::word('la maleta'), Kit::word('el pasaporte'), Kit::word('la salida')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.billete', '¿Tiene usted su billete?', 'Do you have your ticket?', [['sí', 'no', 'tengo', 'está'], ['billete', 'aquí']], 'Sí, aquí está mi billete.', [Kit::word('el billete', 'billete')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.puerta', '¿Dónde está la puerta cinco?', 'Where is gate five?', [['puerta', 'está'], ['aquí', 'allí']], 'La puerta cinco está allí.', [Kit::word('¿dónde?', 'dónde'), Kit::word('la puerta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aeropuerto', '¿Es internacional el aeropuerto?', 'Is the airport international?', [['sí', 'no', 'es'], ['internacional', 'aeropuerto']], 'Sí, es un aeropuerto internacional.', [Kit::word('el aeropuerto', 'aeropuerto'), Kit::word('internacional')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.billete', 'The ticket is not here.', ['El billete no está aquí.', 'No está aquí el billete.'], [Kit::word('el billete'), Kit::form('el', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.maletas', 'The suitcases are at the airport.', ['Las maletas están en el aeropuerto.'], [Kit::word('la maleta', 'maletas'), Kit::word('el aeropuerto'), Kit::form('las')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vuelo', 'Which is your flight? My flight is not delayed.', ['¿Cuál es su vuelo? Mi vuelo no está retrasado.', '¿Cuál es su vuelo? No está retrasado mi vuelo.'], [Kit::word('¿cuál?', 'cuál'), Kit::word('el vuelo', 'vuelo'), Kit::word('retrasado')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.puerta', 'Where is gate nine? It is here and the exit is over there.', ['¿Dónde está la puerta nueve? Está aquí y la salida está allí.', '¿Dónde está la puerta nueve? La puerta nueve está aquí y la salida está allí.'], [Kit::word('¿dónde?', 'dónde'), Kit::word('la puerta'), Kit::word('la salida')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pasaporte', '¿Tienes ___ pasaporte?', 'Do you have the passport?', 'el', Kit::form('el', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vuelos', '___ vuelos son internacionales.', 'The flights are international.', 'Los', Kit::form('los'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.pasaporte', '¿Dónde está mi pasaporte? Está en la maleta.', 'Where is my passport? It is in the suitcase.', [Kit::word('¿dónde?', 'dónde'), Kit::word('el pasaporte', 'pasaporte'), Kit::word('la maleta'), Kit::form('la')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.llegada', 'La llegada no está retrasada.', 'The arrival is not delayed.', [Kit::word('la llegada'), Kit::word('retrasado', 'retrasada'), Kit::form('la')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.vuelo', '¿Cuál es tu vuelo? Mi vuelo es internacional.', 'Which is your flight? My flight is international.', [Kit::word('¿cuál?', 'cuál'), Kit::word('el vuelo', 'vuelo'), Kit::word('internacional')], 'dictation', $set),
        ];
    }
}
