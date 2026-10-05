<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AtTheAirport implements UnitContent
{
    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and context decides: here est is the verb.';

    private const ET_NOTE = 'Et (and) sounds very close to est (is), and context decides: here et joins two nouns.';

    private const A_NOTE = 'À (at, to) has a grave accent, unlike a (has). Here it means at.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'at-the-airport';
    }

    public function words(): array
    {
        return [
            new WordData('l\'aéroport', cue: 'airport (masculine: un aéroport)', forms: ['aéroport', 'aéroports']),
            new WordData('le vol', cue: 'flight', forms: ['vols']),
            new WordData('la valise', cue: 'suitcase', forms: ['valises']),
            new WordData('le passeport', cue: 'passport', forms: ['passeports']),
            new WordData('la porte', cue: 'gate (at an airport), also door', accepted: ['la porte d\'embarquement'], forms: ['portes']),
            new WordData('le départ', cue: 'departure (on an airport board)', forms: ['départs']),
            new WordData('l\'arrivée', cue: 'arrival (feminine: une arrivée)', forms: ['arrivée', 'arrivées']),
            new WordData('le billet', cue: 'ticket (for a flight or train)', forms: ['billets']),
            new WordData('en retard', cue: 'delayed, late'),
            new WordData('international', cue: 'international', forms: ['internationale', 'internationaux', 'internationales']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Le vol est en retard.', 'english' => 'The flight is late.'],
            ['text' => 'La valise est ici.', 'english' => 'The suitcase is here.'],
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
            Kit::gap($stage, 'sentences.choose_gap.vol', '___ vol est ici.', ['Le', 'La', 'Les'], 'Le', Kit::form('le'), 'Vol is masculine, so it takes le. Learn the gender together with the noun: le vol.', 'choose', 'The flight is here.'),
            Kit::gap($stage, 'sentences.choose_gap.valise', '___ valise est ici.', ['La', 'Le', 'Les'], 'La', Kit::form('la'), 'Valise is feminine, so it takes la: la valise.', 'choose', 'The suitcase is here.'),
            Kit::gap($stage, 'sentences.choose_gap.aeroport', 'C\'est ___ aéroport.', ['un', 'une', 'des'], 'un', Kit::form('un', true), 'Before a vowel, le and la both shrink to l\', so l\'aéroport hides the gender. Un shows it: aéroport is masculine, un aéroport.', 'choose', 'It is an airport.'),
            Kit::gap($stage, 'sentences.choose_gap.arrivee', 'Il y a ___ arrivée internationale.', ['une', 'un', 'des'], 'une', Kit::form('une', true), 'Before a vowel, l\'arrivée hides the gender. Une shows it: arrivée is feminine, une arrivée.', 'choose', 'There is an international arrival.'),
            Kit::gap($stage, 'sentences.choose_gap.internationale', 'L\'arrivée est ___.', ['internationale', 'international'], 'internationale', Kit::word('international', 'internationale'), 'The adjective agrees with the noun. Arrivée is feminine, so internationale.', 'choose', 'The arrival is international.'),
            Kit::gap($stage, 'sentences.choose_gap.retard', 'Le départ est en ___.', ['retard', 'porte', 'billet'], 'retard', Kit::word('en retard', 'retard'), 'En retard is the set phrase for delayed or late.', 'choose', 'The departure is delayed.'),

            Kit::typeGap($stage, 'sentences.type_gap.vol', '___ vol dix est international.', 'Flight ten is international.', 'Le', Kit::form('le'), 'Vol is masculine, so it takes le: le vol.'),
            Kit::typeGap($stage, 'sentences.type_gap.porte', 'Où est ___ porte ?', 'Where is the gate?', 'la', Kit::form('la'), 'Porte is feminine, so it takes la: la porte.'),
            Kit::typeGap($stage, 'sentences.type_gap.valise', 'Voici ___ valise.', 'Here is a suitcase.', 'une', Kit::form('une'), 'Valise is feminine, so a suitcase is une valise.'),
            Kit::typeGap($stage, 'sentences.type_gap.passeports', '___ passeports sont ici.', 'The passports are here.', 'Les', Kit::form('les'), 'The plural of le and la is les: les passeports.'),
            Kit::typeGap($stage, 'sentences.type_gap.billet', 'Où est ___ billet ?', 'Where is the ticket?', 'le', Kit::form('le', true), 'The ending of billet does not show its gender. It is masculine: le billet.'),

            Kit::translate($stage, 'sentences.translate.passeport', 'The passport is in the suitcase.', ['Le passeport est dans la valise.'], [Kit::word('le passeport'), Kit::word('la valise'), Kit::form('le', true)]),
            Kit::translate($stage, 'sentences.translate.arrivee', 'The arrival is delayed.', ['L\'arrivée est en retard.'], [Kit::word('l\'arrivée'), Kit::word('en retard')]),
            Kit::translate($stage, 'sentences.translate.international', 'It is an international flight.', ['C\'est un vol international.'], [Kit::word('le vol', 'vol'), Kit::word('international'), Kit::form('un')]),

            Kit::build($stage, 'sentences.build.aeroport', 'We are at the airport.', 'Nous sommes à l\'aéroport.', ['le'], [Kit::word('l\'aéroport')]),
            Kit::build($stage, 'sentences.build.billet', 'The ticket is here.', 'Le billet est ici.', ['la'], [Kit::word('le billet'), Kit::form('le', true)]),
            Kit::build($stage, 'sentences.build.porte', 'Gate five is over there.', 'La porte cinq est là.', ['le'], [Kit::word('la porte'), Kit::form('la')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.valise', 'La valise est ici.', ['The suitcase is here.', 'The passport is here.', 'The ticket is here.', 'The gate is here.'], 'The suitcase is here.', [Kit::word('la valise'), Kit::form('la')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vol', 'Le vol est en retard.', ['The flight is delayed.', 'The flight is international.', 'The arrival is delayed.', 'The departure is delayed.'], 'The flight is delayed.', [Kit::word('le vol'), Kit::word('en retard'), Kit::form('le')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.aeroport', 'C\'est un aéroport international.', ['It is an international airport.', 'It is an international flight.', 'It is a small airport.', 'It is a national airport.'], 'It is an international airport.', [Kit::word('l\'aéroport', 'aéroport'), Kit::word('international'), Kit::form('un', true)]),
            Kit::listenType($stage, 'sentences.listen_type.passeport', 'Mon passeport est ici.', 'My passport is here.', [Kit::word('le passeport', 'passeport')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.depart', 'Le départ est en retard.', 'The departure is delayed.', [Kit::word('le départ'), Kit::word('en retard'), Kit::form('le')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.billet', 'Voici un billet pour le vol dix.', 'Here is a ticket for flight ten.', [Kit::word('le billet', 'billet'), Kit::word('le vol'), Kit::form('un')]),
            Kit::listenType($stage, 'sentences.listen_type.valises', 'Les valises sont ici.', 'The suitcases are here.', [Kit::word('la valise', 'valises'), Kit::form('les')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.vol', 'Le vol est en retard.', 'The flight is delayed.', [Kit::word('le vol'), Kit::word('en retard'), Kit::form('le')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.porte', 'Où est la porte ?', 'Where is the gate?', [Kit::word('la porte'), Kit::form('la')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.passeport', 'Le passeport et le billet sont ici.', 'The passport and the ticket are here.', [Kit::word('le passeport'), Kit::word('le billet'), Kit::form('le', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.valise', 'Ma valise et mon passeport sont ici.', 'My suitcase and my passport are here.', [Kit::word('la valise', 'valise'), Kit::word('le passeport', 'passeport')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.depart', 'Le départ est en retard ?', 'Is the departure delayed?', [['oui', 'non', 'est', 'c\'est'], ['retard', 'départ']], 'Oui, le départ est en retard.', [Kit::word('le départ'), Kit::word('en retard'), Kit::form('le')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vol', 'C\'est un vol international ?', 'Is it an international flight?', [['oui', 'non', 'est'], ['international', 'vol']], 'Oui, c\'est un vol international.', [Kit::word('le vol', 'vol'), Kit::word('international'), Kit::form('un')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.arrivee', 'L\'arrivée est en retard ?', 'Is the arrival delayed?', [['oui', 'non', 'est'], ['retard', 'arrivée']], 'Oui, l\'arrivée est en retard.', [Kit::word('l\'arrivée'), Kit::word('en retard')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.enregistrement', 'Read the conversation at the airport.', [
                Kit::line('Agent', 'Bonjour. Vous avez votre passeport et votre billet ?'),
                Kit::line('Anne', 'Oui, voici mon passeport et mon billet. C\'est un vol international.'),
                Kit::line('Agent', 'Très bien. Vous avez une valise ?'),
                Kit::line('Anne', 'Oui, voici ma valise.'),
                Kit::line('Agent', 'C\'est la porte cinq. Le vol est en retard.'),
                Kit::line('Anne', 'Merci. Au revoir.'),
            ], [
                Kit::question('How many suitcases does Anne have?', ['None', 'One', 'Two'], 'One'),
                Kit::question('Which gate is it?', ['Gate three', 'Gate five', 'Gate ten'], 'Gate five'),
                Kit::question('What is the problem with the flight?', ['It is delayed.', 'It is full.', 'Anne has no ticket.'], 'It is delayed.'),
            ], [Kit::word('le passeport'), Kit::word('le billet'), Kit::word('le vol'), Kit::word('international'), Kit::word('la valise'), Kit::word('la porte'), Kit::word('en retard')]),
            Kit::gap($stage, 'task.choose_gap.vols', 'Les vols sont ___.', ['internationaux', 'internationales', 'international'], 'internationaux', Kit::word('international', 'internationaux'), 'Vol is masculine, so the plural adjective is internationaux, not internationales.', 'read', 'The flights are international.'),
            Kit::gap($stage, 'task.choose_gap.arrivees', 'Les arrivées sont ___.', ['internationales', 'internationaux', 'internationale'], 'internationales', Kit::word('international', 'internationales'), 'Arrivée is feminine, so the plural adjective is internationales.', 'read', 'The arrivals are international.'),

            Kit::transform($stage, 'task.transform.vol', 'Make it plural.', 'Le vol est en retard.', ['Les vols sont en retard.'], [Kit::word('le vol', 'vols'), Kit::word('en retard'), Kit::form('les')]),
            Kit::transform($stage, 'task.transform.depart', 'Make it plural.', 'Le départ est là.', ['Les départs sont là.'], [Kit::word('le départ', 'départs'), Kit::form('les')]),
            Kit::transform($stage, 'task.transform.aeroport', 'Make it plural.', 'L\'aéroport est international.', ['Les aéroports sont internationaux.'], [Kit::word('l\'aéroport', 'aéroports'), Kit::word('international', 'internationaux'), Kit::form('les', true)]),
            Kit::writeGuided($stage, 'task.write_guided.billet', 'Say here is my ticket and my passport, and ask where the gate is.', ['billet', 'passeport', 'porte', 'où'], 'Voici mon billet et mon passeport. Où est la porte ?', [
                ['forms' => ['billet', 'billets'], 'term' => 'le billet'],
                ['forms' => ['passeport', 'passeports'], 'term' => 'le passeport'],
                ['forms' => ['porte'], 'term' => 'la porte'],
                ['forms' => ['où'], 'term' => null],
            ], [Kit::word('le billet'), Kit::word('le passeport'), Kit::word('la porte')]),
            Kit::writeGuided($stage, 'task.write_guided.vol', 'Say that the flight is delayed and ask where your suitcase is.', ['vol', 'en retard', 'où', 'valise'], 'Le vol est en retard. Où est ma valise ?', [
                ['forms' => ['vol', 'vols'], 'term' => 'le vol'],
                ['forms' => ['retard'], 'term' => 'en retard'],
                ['forms' => ['où'], 'term' => null],
                ['forms' => ['valise', 'valises'], 'term' => 'la valise'],
            ], [Kit::word('le vol'), Kit::word('en retard'), Kit::word('la valise')]),
            Kit::build($stage, 'task.build.vol', 'The flight is international.', 'Le vol est international.', ['la', 'sont'], [Kit::word('le vol'), Kit::word('international'), Kit::form('le')]),
            Kit::build($stage, 'task.build.valise', 'The suitcase and the passport are here.', 'La valise et le passeport sont ici.', ['les', 'est'], [Kit::word('la valise'), Kit::word('le passeport'), Kit::form('le', true)]),
            Kit::build($stage, 'task.build.billet', 'Here is a ticket for the international flight.', 'Voici un billet pour le vol international.', ['une', 'la'], [Kit::word('le billet', 'billet'), Kit::word('le vol'), Kit::word('international'), Kit::form('un')]),
            Kit::translate($stage, 'task.translate.passeport', 'Where is the passport?', ['Où est le passeport ?'], [Kit::word('le passeport'), Kit::form('le', true)]),
            Kit::translate($stage, 'task.translate.billet', 'The passport and the ticket are in my suitcase.', ['Le passeport et le billet sont dans ma valise.'], [Kit::word('le passeport'), Kit::word('le billet'), Kit::word('la valise', 'valise')]),

            Kit::listenPassage($stage, 'task.listen_passage.arrivee', [
                Kit::line('Paul', 'Bonjour, Anne. Tu es à l\'aéroport ?'),
                Kit::line('Anne', 'Oui, je suis à la porte cinq.'),
                Kit::line('Paul', 'L\'arrivée du vol est en retard ?'),
                Kit::line('Anne', 'Oui, l\'arrivée est en retard. C\'est un vol international.'),
                Kit::line('Paul', 'Merci, Anne. Au revoir.'),
            ], [
                Kit::question('Where is Anne?', ['At the gate', 'At home', 'At the hotel'], 'At the gate'),
                Kit::question('How is the arrival?', ['It is on time.', 'It is delayed.', 'The conversation does not say.'], 'It is delayed.'),
                Kit::question('What kind of flight is it?', ['A short flight', 'An international flight', 'The conversation does not say.'], 'An international flight'),
            ], [
                Kit::question('What does Paul say at the end?', ['Thank you and goodbye.', 'Where is the gate?', 'The flight is delayed.'], 'Thank you and goodbye.'),
                Kit::question('Who asks if the arrival is delayed?', ['Anne', 'Paul', 'Nobody'], 'Paul'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('l\'aéroport'), Kit::word('l\'arrivée'), Kit::word('le vol'), Kit::word('en retard'), Kit::word('international'), Kit::word('la porte')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.valise', 'Je suis à l\'aéroport avec ma valise.', 'I am at the airport with my suitcase.', [Kit::word('l\'aéroport'), Kit::word('la valise', 'valise')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.billet', 'Voici le passeport et le billet.', 'Here are the passport and the ticket.', [Kit::word('le passeport'), Kit::word('le billet'), Kit::form('le', true)], homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'task.listen_type.retard', 'Le départ est en retard, l\'arrivée aussi.', 'The departure is delayed, the arrival too.', [Kit::word('le départ'), Kit::word('l\'arrivée'), Kit::word('en retard')], homophoneNote: self::EST_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.passeport', 'Vous avez votre passeport ?', 'Do you have your passport?', [['oui', 'non', 'voici', 'mon'], ['passeport', 'ici']], 'Oui, voici mon passeport.', [Kit::word('le passeport', 'passeport')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.aeroport', 'Vous êtes à l\'aéroport ?', 'Are you at the airport?', [['oui', 'non', 'suis'], ['aéroport', 'l\'aéroport', 'ici']], 'Oui, je suis à l\'aéroport.', [Kit::word('l\'aéroport')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.porte', 'Quelle est votre porte ?', 'Which is your gate?', [['c\'est', 'voici', 'porte', 'la', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix'], ['un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix']], 'C\'est la porte cinq.', [Kit::word('la porte'), Kit::form('la')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.vol', 'Le vol est en retard ?', 'Is the flight delayed?', [['oui', 'non', 'est'], ['retard', 'vol']], 'Oui, le vol est en retard.', [Kit::word('le vol'), Kit::word('en retard'), Kit::form('le')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.billet', 'Le billet est pour un vol international.', 'The ticket is for an international flight.', [Kit::word('le billet'), Kit::word('le vol', 'vol'), Kit::word('international'), Kit::form('le', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.valise', 'Ma valise et mon billet sont ici.', 'My suitcase and my ticket are here.', [Kit::word('la valise', 'valise'), Kit::word('le billet', 'billet')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.vol', 'The international flight is at the airport.', ['Le vol international est à l\'aéroport.'], [Kit::word('le vol'), Kit::word('international'), Kit::word('l\'aéroport'), Kit::form('le')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.passeport', 'My passport, my ticket and my suitcase are here.', ['Mon passeport, mon billet et ma valise sont ici.', 'Voici mon passeport, mon billet et ma valise.'], [Kit::word('le passeport', 'passeport'), Kit::word('le billet', 'billet'), Kit::word('la valise', 'valise')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.depart', 'The departure and the arrival are delayed.', ['L\'arrivée est en retard, le départ aussi.'], [Kit::word('le départ'), Kit::word('l\'arrivée'), Kit::word('en retard')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.porte', 'The gate of the international airport is over there.', ['La porte de l\'aéroport international est là.'], [Kit::word('la porte'), Kit::word('l\'aéroport'), Kit::word('international'), Kit::form('la')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.aeroport', 'Voici ___ aéroport.', 'Here is an airport.', 'un', Kit::form('un', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.arrivee', 'C\'est ___ arrivée internationale.', 'It is an international arrival.', 'une', Kit::form('une', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.passeports', 'Les valises, les passeports et les billets sont là.', 'The suitcases, the passports and the tickets are over there.', [Kit::word('la valise', 'valises'), Kit::word('le passeport', 'passeports'), Kit::word('le billet', 'billets'), Kit::form('les')], 'dictation', $set, homophoneNote: 'Et (and) sounds very close to est (is), and context decides: here et joins the last two nouns. Là (there) has an accent, la (the) does not, and they sound the same.'),
            Kit::listenType($stage, 'check.a.listen_type.arrivee', 'L\'arrivée du vol sept est en retard, le départ aussi.', 'The arrival of flight seven is delayed, the departure too.', [Kit::word('l\'arrivée'), Kit::word('le vol', 'vol'), Kit::word('le départ'), Kit::word('en retard')], 'dictation', $set, homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.depart', 'Le vol international est à la porte neuf.', 'The international flight is at gate nine.', [Kit::word('le vol'), Kit::word('la porte'), Kit::word('international'), Kit::form('la')], 'dictation', $set, homophoneNote: self::EST_NOTE.' À (at) has a grave accent, unlike a (has).'),
            Kit::listenPassage($stage, 'check.a.listen_passage.vol', [
                Kit::line('Agent', 'Bonsoir. Vous avez un billet pour le vol sept ?'),
                Kit::line('Luc', 'Oui, voici mon billet pour le vol sept.'),
                Kit::line('Agent', 'Très bien. Votre porte est la porte neuf.'),
                Kit::line('Luc', 'Mon vol est en retard ?'),
                Kit::line('Agent', 'Oui, il est en retard.'),
                Kit::line('Luc', 'Merci. Au revoir.'),
            ], [
                Kit::question('Which flight is it?', ['Flight five', 'Flight seven', 'Flight nine'], 'Flight seven'),
                Kit::question('Which gate is it?', ['Gate seven', 'Gate nine', 'Gate ten'], 'Gate nine'),
                Kit::question('Is the flight delayed?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('What does Luc have?', ['A passport', 'A ticket', 'A suitcase'], 'A ticket'),
                Kit::question('Who asks if the flight is delayed?', ['Luc', 'The agent', 'Nobody'], 'Luc'),
                Kit::question('How does the conversation end?', ['Luc says thank you and goodbye.', 'Luc asks about the gate.', 'The agent says goodbye.'], 'Luc says thank you and goodbye.'),
            ], [Kit::word('le billet'), Kit::word('le vol'), Kit::word('la porte'), Kit::word('en retard')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.valise', 'Read the conversation.', [
                Kit::line('Marie', 'Paul, tu as ta valise et ton passeport ?'),
                Kit::line('Paul', 'Oui, voici ma valise et mon passeport.'),
                Kit::line('Marie', 'Très bien. La porte neuf est là.'),
                Kit::line('Paul', 'Merci, Marie.'),
            ], [
                Kit::question('What does Paul have?', ['A suitcase and a passport', 'A ticket and a passport', 'Only a suitcase'], 'A suitcase and a passport'),
                Kit::question('Where is gate nine?', ['Over there', 'Here', 'The text does not say.'], 'Over there'),
            ], [Kit::word('la valise'), Kit::word('le passeport'), Kit::word('la porte')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.billet', 'Vous avez votre billet ?', 'Do you have your ticket?', [['oui', 'non', 'voici', 'mon'], ['billet', 'ici']], 'Oui, voici mon billet.', [Kit::word('le billet', 'billet')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.porte', 'Où est la porte cinq ?', 'Where is gate five?', [['porte', 'est', 'c\'est', 'voici', 'la'], ['ici', 'là', 'voici']], 'La porte cinq est là.', [Kit::word('la porte')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aeroport', 'Vous êtes à l\'aéroport international ?', 'Are you at the international airport?', [['oui', 'non', 'suis'], ['international', 'aéroport', 'l\'aéroport']], 'Oui, je suis à l\'aéroport international.', [Kit::word('l\'aéroport'), Kit::word('international')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.depart', 'The departure of the flight is delayed.', ['Le départ du vol est en retard.'], [Kit::word('le départ'), Kit::word('le vol', 'vol'), Kit::word('en retard'), Kit::form('le')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.arrivee', 'The arrival of the international flight is delayed.', ['L\'arrivée du vol international est en retard.'], [Kit::word('l\'arrivée'), Kit::word('le vol', 'vol'), Kit::word('international'), Kit::word('en retard')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.valise', 'My suitcase and my passport are at the airport.', ['Ma valise et mon passeport sont à l\'aéroport.'], [Kit::word('la valise', 'valise'), Kit::word('le passeport', 'passeport'), Kit::word('l\'aéroport')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.porte', 'The passport and the ticket are at the gate.', ['Le passeport et le billet sont à la porte.'], [Kit::word('le passeport'), Kit::word('le billet'), Kit::word('la porte')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.aeroport', 'Il y a ___ aéroport ici.', 'There is an airport here.', 'un', Kit::form('un', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.arrivee', 'Voici ___ arrivée internationale.', 'Here is an international arrival.', 'une', Kit::form('une', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.valises', 'Les valises sont à l\'aéroport international.', 'The suitcases are at the international airport.', [Kit::word('la valise', 'valises'), Kit::word('l\'aéroport'), Kit::word('international'), Kit::form('les')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.retard', 'Le départ et l\'arrivée sont en retard.', 'The departure and the arrival are delayed.', [Kit::word('le départ'), Kit::word('l\'arrivée'), Kit::word('en retard'), Kit::form('le')], 'dictation', $set, homophoneNote: 'Est (is, the verb) and et (and) sound very close, and context decides: here et joins the two nouns.'),
            Kit::listenType($stage, 'check.b.listen_type.billet', 'Le billet est ici, la porte est là.', 'The ticket is here, the gate is over there.', [Kit::word('le billet'), Kit::word('la porte'), Kit::form('la')], 'dictation', $set, homophoneNote: self::EST_NOTE.' Là (there) has an accent, la (the) does not, and they sound the same.'),
        ];
    }
}
