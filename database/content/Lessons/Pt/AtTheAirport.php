<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AtTheAirport implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'at-the-airport';
    }

    public function words(): array
    {
        return [
            new WordData('o aeroporto', cue: 'airport', portunolSlips: ['aeropuerto', 'el aeropuerto']),
            new WordData('o voo', cue: 'flight', portunolSlips: ['vuelo', 'el vuelo'], questions: ['Is "o voo" (1990 spelling, no circumflex) the right form, and is "voo" the everyday word on a Portuguese departures board and in speech?']),
            new WordData('a mala', cue: 'suitcase', portunolSlips: ['maleta', 'la maleta'], questions: ['Is "a mala" alone natural for a suitcase at an airport in Portugal, or would learners also be expected to say "a mala de viagem" or "a bagagem"? Should either be accepted?']),
            new WordData('o passaporte', cue: 'passport', portunolSlips: ['pasaporte', 'el pasaporte']),
            new WordData('a porta', cue: 'gate (at an airport)', accepted: ['a porta de embarque'], portunolSlips: ['puerta', 'la puerta'], questions: ['Do people at a Portuguese airport say "a porta" for the boarding gate, or only "a porta de embarque"? Is accepting both right for the cue "gate (at an airport)"?']),
            new WordData('a saída', cue: 'exit (the way out)', portunolSlips: ['salida', 'la salida'], questions: ['Is "a saída" correctly the way out only, with departures read as "partidas" on a Portuguese board? Is "Estou na saída" natural for being at the exit, or should it be "à saída"?']),
            new WordData('a chegada', cue: 'arrival', portunolSlips: ['llegada', 'la llegada']),
            new WordData('o bilhete', cue: 'ticket (for a flight or train)', portunolSlips: ['billete', 'el billete'], questions: ['Is "o bilhete" the natural word for a flight or train ticket in Portugal, and is "a passagem" correctly left out as Brazilian? Should "o bilhete de avião" be accepted?']),
            new WordData('atrasado', cue: 'delayed (masculine)', forms: ['atrasada'], portunolSlips: ['retrasado'], questions: ['Is "atrasado" the wording an airport board or announcement uses for a delayed flight in Portugal, and is "O voo está atrasado" natural?']),
            new WordData('internacional', cue: 'international (singular)', forms: ['internacionais']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'O aeroporto é internacional.', 'english' => 'The airport is international.'],
            ['text' => 'A mala está aqui.', 'english' => 'The suitcase is here.'],
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
            Kit::gap($stage, 'sentences.choose_gap.aeroporto', '___ aeroporto está aqui.', ['O', 'A', 'Os'], 'O', Kit::form('o'), 'Aeroporto ends in -o, so it is masculine: o aeroporto.', 'choose', 'The airport is here.'),
            Kit::gap($stage, 'sentences.choose_gap.mala', '___ mala está aqui.', ['A', 'O', 'As'], 'A', Kit::form('a'), 'Mala ends in -a, so it is feminine: a mala. Do not mix it up with Spanish mala, which means bad. Spanish says maleta for a suitcase, but the everyday Portuguese word is mala.', 'choose', 'The suitcase is here.'),
            Kit::gap($stage, 'sentences.choose_gap.passaporte', '___ passaporte está aqui.', ['O', 'A', 'As'], 'O', Kit::form('o', true), 'Passaporte ends in -e, and that ending does not show the gender. It is masculine: o passaporte.', 'choose', 'The passport is here.'),
            Kit::gap($stage, 'sentences.choose_gap.bilhete', 'Tenho ___ bilhete.', ['um', 'uma', 'os'], 'um', Kit::form('um', true), 'Bilhete ends in -e, and that ending does not show the gender. It is masculine: um bilhete.', 'choose', 'I have a ticket.'),
            Kit::gap($stage, 'sentences.choose_gap.internacional', 'A chegada é ___.', ['internacional', 'internacionais'], 'internacional', Kit::word('internacional'), 'Internacional has one form for masculine and feminine. Only the plural changes, to internacionais.', 'choose', 'The arrival is international.'),
            Kit::gap($stage, 'sentences.choose_gap.atrasado', 'O voo está ___.', ['atrasado', 'atrasada'], 'atrasado', Kit::word('atrasado'), 'Atrasado agrees with the noun. Voo is masculine, so atrasado.', 'choose', 'The flight is delayed.'),

            Kit::typeGap($stage, 'sentences.type_gap.voo', '___ voo dez é internacional.', 'Flight ten is international.', 'O', Kit::form('o'), 'Voo ends in -o, so it is masculine: o voo.'),
            Kit::typeGap($stage, 'sentences.type_gap.porta', 'Onde está ___ porta?', 'Where is the gate?', 'a', Kit::form('a'), 'Porta ends in -a, so it is feminine: a porta.'),
            Kit::typeGap($stage, 'sentences.type_gap.mala', 'Tenho ___ mala.', 'I have a suitcase.', 'uma', Kit::form('uma'), 'Mala is feminine, so a suitcase is uma mala.'),
            Kit::typeGap($stage, 'sentences.type_gap.passaportes', '___ passaportes estão aqui.', 'The passports are here.', 'Os', Kit::form('os'), 'Masculine nouns in the plural take os: os passaportes.'),
            Kit::typeGap($stage, 'sentences.type_gap.bilhete', 'Tens ___ bilhete?', 'Do you have the ticket?', 'o', Kit::form('o', true), 'Bilhete ends in -e, and that ending does not show the gender. It is masculine: o bilhete.'),

            Kit::translate($stage, 'sentences.translate.passaporte', 'The passport is in the suitcase.', ['O passaporte está na mala.'], [Kit::word('o passaporte'), Kit::word('a mala', 'mala'), Kit::form('o', true)]),
            Kit::translate($stage, 'sentences.translate.chegada', 'The arrival is delayed.', ['A chegada está atrasada.', 'Está atrasada a chegada.'], [Kit::word('a chegada'), Kit::word('atrasado', 'atrasada'), Kit::form('a')]),
            Kit::translate($stage, 'sentences.translate.internacional', 'It is an international flight.', ['É um voo internacional.'], [Kit::word('o voo', 'voo'), Kit::word('internacional'), Kit::form('um')]),

            Kit::build($stage, 'sentences.build.aeroporto', 'We are at the airport.', 'Estamos no aeroporto.', ['na'], [Kit::word('o aeroporto', 'aeroporto')]),
            Kit::build($stage, 'sentences.build.bilhete', 'The ticket is here.', 'O bilhete está aqui.', ['a'], [Kit::word('o bilhete'), Kit::form('o', true)]),
            Kit::build($stage, 'sentences.build.porta', 'Gate five is over there.', 'A porta cinco está ali.', ['o'], [Kit::word('a porta'), Kit::form('a')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.mala', 'A mala está aqui.', ['The suitcase is here.', 'The passport is here.', 'The ticket is here.', 'The gate is here.'], 'The suitcase is here.', [Kit::word('a mala'), Kit::form('a')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.voo', 'O voo está atrasado.', ['The flight is delayed.', 'The flight is international.', 'The arrival is delayed.', 'The exit is delayed.'], 'The flight is delayed.', [Kit::word('o voo'), Kit::word('atrasado'), Kit::form('o')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.aeroporto', 'É um aeroporto internacional.', ['It is an international airport.', 'It is an international flight.', 'It is a small airport.', 'It is a national airport.'], 'It is an international airport.', [Kit::word('o aeroporto', 'aeroporto'), Kit::word('internacional'), Kit::form('um')]),
            Kit::listenType($stage, 'sentences.listen_type.passaporte', 'O meu passaporte está aqui.', 'My passport is here.', [Kit::word('o passaporte', 'passaporte')]),
            Kit::listenType($stage, 'sentences.listen_type.saida', 'A saída está ali.', 'The exit is over there.', [Kit::word('a saída'), Kit::form('a')], homophoneNote: 'The a before saída is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.bilhete', 'Tenho um bilhete para o voo dez.', 'I have a ticket for flight ten.', [Kit::word('o bilhete', 'bilhete'), Kit::word('o voo', 'voo'), Kit::form('um')]),
            Kit::listenType($stage, 'sentences.listen_type.malas', 'As malas estão aqui.', 'The suitcases are here.', [Kit::word('a mala', 'malas'), Kit::form('as')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.voo', 'O voo está atrasado.', 'The flight is delayed.', [Kit::word('o voo'), Kit::word('atrasado'), Kit::form('o')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.porta', 'Onde está a porta?', 'Where is the gate?', [Kit::word('a porta'), Kit::form('a')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.passaporte', 'O passaporte e o bilhete estão aqui.', 'The passport and the ticket are here.', [Kit::word('o passaporte'), Kit::word('o bilhete'), Kit::form('o', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mala', 'A minha mala está aqui.', 'My suitcase is here.', [Kit::word('a mala', 'mala')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.saida', 'Onde está a saída?', 'Where is the exit?', [['saída', 'está', 'é'], ['aqui', 'ali', 'lá']], 'A saída está ali.', [Kit::word('a saída'), Kit::form('a')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.voo', 'É um voo internacional?', 'Is it an international flight?', [['sim', 'não', 'é'], ['internacional', 'voo']], 'Sim, é um voo internacional.', [Kit::word('o voo', 'voo'), Kit::word('internacional'), Kit::form('um')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.chegada', 'A chegada está atrasada?', 'Is the arrival delayed?', [['sim', 'não', 'está'], ['atrasada', 'chegada']], 'Sim, a chegada está atrasada.', [Kit::word('a chegada'), Kit::word('atrasado', 'atrasada'), Kit::form('a')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.check-in', 'Read the conversation at the airport.', [
                Kit::line('Funcionária', 'Bom dia. Tem o passaporte e o bilhete?'),
                Kit::line('Ana', 'Sim, aqui estão. É um voo internacional.'),
                Kit::line('Funcionária', 'Muito bem. Tem uma mala?'),
                Kit::line('Ana', 'Sim, tenho uma mala.'),
                Kit::line('Funcionária', 'A porta é a cinco. O voo está atrasado.'),
                Kit::line('Ana', 'Obrigada.'),
            ], [
                Kit::question('How many suitcases does Ana have?', ['None', 'One', 'Two'], 'One'),
                Kit::question('Which gate is it?', ['Gate three', 'Gate five', 'Gate ten'], 'Gate five'),
                Kit::question('What is the problem with the flight?', ['It is delayed.', 'It is full.', 'Ana has no ticket.'], 'It is delayed.'),
            ], [Kit::word('o passaporte'), Kit::word('o bilhete'), Kit::word('o voo'), Kit::word('internacional'), Kit::word('a mala'), Kit::word('a porta'), Kit::word('atrasado')]),
            Kit::gap($stage, 'task.choose_gap.voos', 'Os voos estão ___.', ['atrasados', 'atrasado'], 'atrasados', Kit::word('atrasado', 'atrasados'), 'The adjective agrees with the noun: more than one flight, so atrasados.', 'read'),
            Kit::gap($stage, 'task.choose_gap.chegadas', 'As chegadas são ___.', ['internacionais', 'internacional'], 'internacionais', Kit::word('internacional', 'internacionais'), 'Internacional has no separate feminine form, but the plural is internacionais, not internacionals.', 'read'),

            Kit::transform($stage, 'task.transform.voo', 'Make it plural.', 'O voo está atrasado.', ['Os voos estão atrasados.'], [Kit::word('o voo', 'os voos'), Kit::word('atrasado', 'atrasados'), Kit::form('os')]),
            Kit::transform($stage, 'task.transform.chegada', 'Make it plural.', 'A chegada é internacional.', ['As chegadas são internacionais.'], [Kit::word('a chegada', 'as chegadas'), Kit::word('internacional', 'internacionais'), Kit::form('as')]),
            Kit::transform($stage, 'task.transform.passaporte', 'Make it plural.', 'O passaporte está aqui.', ['Os passaportes estão aqui.'], [Kit::word('o passaporte', 'os passaportes'), Kit::form('os', true)]),
            Kit::writeGuided($stage, 'task.write_guided.bilhete', 'Say that you have a ticket and a passport, and ask where the gate is.', ['bilhete', 'passaporte', 'porta', 'onde'], 'Tenho um bilhete e um passaporte. Onde está a porta?', [
                ['forms' => ['bilhete'], 'term' => 'o bilhete'],
                ['forms' => ['passaporte', 'passaportes'], 'term' => 'o passaporte'],
                ['forms' => ['porta'], 'term' => 'a porta'],
                ['forms' => ['onde'], 'term' => null],
            ], [Kit::word('o bilhete'), Kit::word('o passaporte'), Kit::word('a porta')]),
            Kit::writeGuided($stage, 'task.write_guided.voo', 'Say that the flight is delayed and ask where your suitcase is.', ['voo', 'atrasado', 'onde', 'mala'], 'O voo está atrasado. Onde está a minha mala?', [
                ['forms' => ['voo', 'voos'], 'term' => 'o voo'],
                ['forms' => ['atrasado', 'atrasada'], 'term' => 'atrasado'],
                ['forms' => ['onde'], 'term' => null],
                ['forms' => ['mala', 'malas'], 'term' => 'a mala'],
            ], [Kit::word('o voo'), Kit::word('atrasado'), Kit::word('a mala')]),
            Kit::build($stage, 'task.build.voo', 'The flight is international.', 'O voo é internacional.', ['a', 'está'], [Kit::word('o voo'), Kit::word('internacional'), Kit::form('o')]),
            Kit::build($stage, 'task.build.mala', 'The suitcase and the passport are here.', 'A mala e o passaporte estão aqui.', ['os', 'está'], [Kit::word('a mala'), Kit::word('o passaporte'), Kit::form('o', true)]),
            Kit::build($stage, 'task.build.bilhete', 'I have a ticket for the international flight.', 'Tenho um bilhete para o voo internacional.', ['uma', 'a'], [Kit::word('o bilhete', 'bilhete'), Kit::word('o voo'), Kit::word('internacional'), Kit::form('um')]),
            Kit::translate($stage, 'task.translate.passaporte', 'Where is the passport?', ['Onde está o passaporte?'], [Kit::word('o passaporte'), Kit::form('o', true)]),
            Kit::translate($stage, 'task.translate.bilhete', 'The ticket is in the suitcase.', ['O bilhete está na mala.'], [Kit::word('o bilhete'), Kit::word('a mala', 'mala'), Kit::form('o', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.chegada', [
                Kit::line('Marta', 'Olá, Rui. Estás no aeroporto?'),
                Kit::line('Rui', 'Sim, estou na saída.'),
                Kit::line('Marta', 'O voo da Ana está atrasado?'),
                Kit::line('Rui', 'Sim, a chegada está atrasada. É um voo internacional.'),
                Kit::line('Marta', 'Obrigada, Rui. Adeus.'),
            ], [
                Kit::question('Where is Rui?', ['At the exit', 'At the gate', 'At home'], 'At the exit'),
                Kit::question('How is the flight?', ['It is on time.', 'It is delayed.', 'The conversation does not say.'], 'It is delayed.'),
                Kit::question('What kind of flight is it?', ['A short flight', 'An international flight', 'The conversation does not say.'], 'An international flight'),
            ], [
                Kit::question('What does Marta say at the end?', ['Thank you and goodbye.', 'Where is the gate?', 'The flight is delayed.'], 'Thank you and goodbye.'),
                Kit::question('Who asks if the flight is delayed?', ['Rui', 'Marta', 'Ana'], 'Marta'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('o aeroporto', 'aeroporto'), Kit::word('a chegada'), Kit::word('o voo'), Kit::word('atrasado'), Kit::word('internacional'), Kit::word('a saída')]),
            Kit::listenType($stage, 'task.listen_type.saida', 'Aqui está a saída.', 'Here is the exit.', [Kit::word('a saída'), Kit::form('a')], homophoneNote: 'The a before saída is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'task.listen_type.mala', 'Estou no aeroporto com a minha mala.', 'I am at the airport with my suitcase.', [Kit::word('o aeroporto', 'aeroporto'), Kit::word('a mala', 'mala'), Kit::form('a')], homophoneNote: 'The a before minha is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'task.listen_type.bilhete', 'Tenho o passaporte e o bilhete.', 'I have the passport and the ticket.', [Kit::word('o passaporte'), Kit::word('o bilhete'), Kit::form('o', true)]),

            Kit::speakAnswer($stage, 'task.speak_answer.passaporte', 'O senhor tem o passaporte?', 'Do you have your passport?', [['sim', 'não', 'tenho', 'está'], ['passaporte', 'aqui']], 'Sim, aqui está o meu passaporte.', [Kit::word('o passaporte', 'passaporte')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.aeroporto', 'Estás no aeroporto?', 'Are you at the airport?', [['sim', 'não', 'estou'], ['aeroporto', 'aqui']], 'Sim, estou no aeroporto.', [Kit::word('o aeroporto', 'aeroporto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.porta', 'Qual é a porta, por favor?', 'Which gate is it, please?', [['é', 'porta', 'a'], ['um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove', 'dez']], 'É a porta cinco.', [Kit::word('a porta'), Kit::form('a')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.voo', 'O voo está atrasado?', 'Is the flight delayed?', [['sim', 'não', 'está'], ['atrasado', 'voo']], 'Sim, o voo está atrasado.', [Kit::word('o voo'), Kit::word('atrasado'), Kit::form('o')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bilhete', 'O bilhete é para um voo internacional.', 'The ticket is for an international flight.', [Kit::word('o bilhete'), Kit::word('o voo', 'voo'), Kit::word('internacional'), Kit::form('o', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.mala', 'A minha mala e o meu bilhete estão aqui.', 'My suitcase and my ticket are here.', [Kit::word('a mala', 'mala'), Kit::word('o bilhete', 'bilhete')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.aeroporto', 'Where is the international airport?', ['Onde está o aeroporto internacional?'], [Kit::word('o aeroporto'), Kit::word('internacional'), Kit::form('o')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.passaporte', 'My passport and my ticket are here.', ['O meu passaporte e o meu bilhete estão aqui.', 'Aqui estão o meu passaporte e o meu bilhete.'], [Kit::word('o passaporte', 'passaporte'), Kit::word('o bilhete', 'bilhete')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.porta', 'The exit is here and the gate is over there.', ['A saída está aqui e a porta está ali.', 'A saída está aqui e a porta é ali.', 'A saída está aqui e a porta está lá.'], [Kit::word('a saída'), Kit::word('a porta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.chegada', 'The arrival is not international.', ['A chegada não é internacional.'], [Kit::word('a chegada'), Kit::word('internacional'), Kit::form('a')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.bilhete', 'Onde está ___ bilhete?', 'Where is the ticket?', 'o', Kit::form('o', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.passaporte', 'Aqui está ___ passaporte.', 'Here is the passport.', 'o', Kit::form('o', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.malas', 'As malas estão ali.', 'The suitcases are over there.', [Kit::word('a mala', 'malas'), Kit::form('as')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.mala', 'Tenho uma mala e um passaporte.', 'I have a suitcase and a passport.', [Kit::word('a mala', 'mala'), Kit::word('o passaporte', 'passaporte'), Kit::form('uma')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.voo', 'O voo nove está atrasado.', 'Flight nine is delayed.', [Kit::word('o voo'), Kit::word('atrasado')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.voo', [
                Kit::line('João', 'Boa tarde. Tenho um bilhete para o voo sete.'),
                Kit::line('Funcionária', 'Muito bem. A sua porta é a nove.'),
                Kit::line('João', 'O meu voo está atrasado?'),
                Kit::line('Funcionária', 'Não, não está atrasado.'),
                Kit::line('João', 'Muito obrigado.'),
            ], [
                Kit::question('Which flight is it?', ['Flight five', 'Flight seven', 'Flight nine'], 'Flight seven'),
                Kit::question('Which gate is it?', ['Gate seven', 'Gate nine', 'Gate ten'], 'Gate nine'),
                Kit::question('Is the flight delayed?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('What does João have?', ['A passport', 'A ticket', 'A suitcase'], 'A ticket'),
                Kit::question('Who asks if the flight is delayed?', ['João', 'The employee', 'Nobody'], 'João'),
                Kit::question('How does the conversation end?', ['João says thank you.', 'João asks about the gate.', 'The employee says goodbye.'], 'João says thank you.'),
            ], [Kit::word('o bilhete'), Kit::word('o voo'), Kit::word('a porta'), Kit::word('atrasado')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.mala', 'Read the conversation.', [
                Kit::line('Marta', 'Rui, tens a tua mala?'),
                Kit::line('Rui', 'Sim, tenho a mala e o passaporte.'),
                Kit::line('Marta', 'Muito bem. A saída é ali.'),
                Kit::line('Rui', 'Obrigado, Marta.'),
            ], [
                Kit::question('What does Rui have?', ['A suitcase and a passport', 'A ticket and a passport', 'Only a suitcase'], 'A suitcase and a passport'),
                Kit::question('Where is the exit?', ['Over there', 'Here', 'The text does not say.'], 'Over there'),
            ], [Kit::word('a mala'), Kit::word('o passaporte'), Kit::word('a saída')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.bilhete', 'O senhor tem o bilhete?', 'Do you have your ticket?', [['sim', 'não', 'tenho', 'está'], ['bilhete', 'aqui']], 'Sim, aqui está o meu bilhete.', [Kit::word('o bilhete', 'bilhete')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.porta', 'Onde está a porta cinco?', 'Where is gate five?', [['porta', 'está', 'é'], ['aqui', 'ali', 'lá']], 'A porta cinco está ali.', [Kit::word('a porta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aeroporto', 'O aeroporto é internacional?', 'Is the airport international?', [['sim', 'não', 'é'], ['internacional', 'aeroporto']], 'Sim, é um aeroporto internacional.', [Kit::word('o aeroporto', 'aeroporto'), Kit::word('internacional')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.bilhete', 'The ticket is not here.', ['O bilhete não está aqui.', 'Não está aqui o bilhete.'], [Kit::word('o bilhete'), Kit::form('o', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.malas', 'The suitcases are in the airport.', ['As malas estão no aeroporto.'], [Kit::word('a mala', 'malas'), Kit::word('o aeroporto', 'aeroporto'), Kit::form('as')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.voo', 'The flight is not delayed.', ['O voo não está atrasado.', 'Não está atrasado o voo.'], [Kit::word('o voo'), Kit::word('atrasado')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.porta', 'Gate nine is here and the exit is over there.', ['A porta nove está aqui e a saída está ali.', 'A porta nove está aqui e a saída é ali.', 'A porta nove está aqui e a saída está lá.'], [Kit::word('a porta'), Kit::word('a saída')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.passaporte', 'Tens ___ passaporte?', 'Do you have the passport?', 'o', Kit::form('o', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.voos', '___ voos são internacionais.', 'The flights are international.', 'Os', Kit::form('os'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.passaporte', 'O meu passaporte está na mala.', 'My passport is in the suitcase.', [Kit::word('o passaporte', 'passaporte'), Kit::word('a mala', 'mala'), Kit::form('o', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.chegada', 'A chegada não está atrasada.', 'The arrival is not delayed.', [Kit::word('a chegada'), Kit::word('atrasado', 'atrasada'), Kit::form('a')], 'dictation', $set, homophoneNote: 'The a before chegada is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.voo', 'O meu voo é internacional.', 'My flight is international.', [Kit::word('o voo', 'voo'), Kit::word('internacional')], 'dictation', $set),
        ];
    }
}
