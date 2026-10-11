<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

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
    private const TIME = 'a que horas sai?';

    private const NUMBERS = ['um', 'uma', 'dois', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove', 'dez', 'onze', 'doze'];

    private const A_NOTE = 'The à here is a + a (to the). It sounds like há (there is) but is not the article a.';

    private const ART_NOTE = 'The a here is the article a (the), the short unstressed one, not à (to the) or há (there is).';

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'taking-the-train-or-bus';
    }

    public function words(): array
    {
        return [
            new WordData('o comboio', cue: 'train', forms: ['comboios'], portunolSlips: ['tren', 'el tren']),
            new WordData('o autocarro', cue: 'bus', forms: ['autocarros'], portunolSlips: ['autobús', 'el autobús']),
            new WordData('a estação', cue: 'station', forms: ['ir'], portunolSlips: ['estación', 'la estación']),
            new WordData('a paragem', cue: 'stop (where a bus or tram stops)', forms: ['paragens'], portunolSlips: ['parada', 'la parada']),
            new WordData('o cais', cue: 'platform (at a train station)', portunolSlips: ['andén', 'el andén']),
            new WordData('o horário', cue: 'timetable', forms: ['horários'], portunolSlips: ['horario', 'el horario']),
            new WordData('o lugar', cue: 'seat', forms: ['lugares'], portunolSlips: ['asiento', 'el asiento']),
            new WordData('de ida e volta', cue: 'return (as in a return ticket)', forms: ['bilhete', 'bilhetes'], portunolSlips: ['de ida y vuelta'], note: 'You say um bilhete de ida e volta for a return ticket. A single ticket is um bilhete simples.'),
            new WordData(self::TIME, cue: 'what time does it leave?', accepted: ['a que horas sai'], portunolSlips: ['a qué hora sale']),
            new WordData('a linha', cue: 'line (of a bus or metro)', forms: ['linhas'], portunolSlips: ['línea', 'la línea']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Quero um bilhete.', 'english' => 'I want a ticket.'],
            ['text' => 'Quero ir à estação.', 'english' => 'I want to go to the station.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural European Portuguese (Portugal). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural European Portuguese (Portugal), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $time = Kit::word(self::TIME, 'a que horas sai');

        return [
            Kit::gap($stage, 'sentences.choose_gap.quero-bilhete', 'Eu ___ um bilhete.', ['quero', 'queres', 'quer'], 'quero', Kit::form('quero'), 'Eu goes with quero. Queres is for tu and quer is for ele or ela.', 'choose', glosses: ['quero' => 'I want', 'queres' => 'you want', 'quer' => 'he or she wants']),
            Kit::gap($stage, 'sentences.choose_gap.marta-quer', 'A Marta ___ ir à estação.', ['quer', 'quero', 'queremos'], 'quer', Kit::form('quer'), 'A Marta is one person, so the verb is quer. Quero is for eu and queremos is for nós.', 'choose', glosses: ['quer' => 'he or she wants', 'quero' => 'I want', 'queremos' => 'we want']),
            Kit::gap($stage, 'sentences.choose_gap.cais', 'Quero ir ao ___ três.', ['cais', 'paragem', 'linha'], 'cais', Kit::word('o cais', 'cais'), 'Ao is a + o, so it needs a masculine word. Cais is masculine, while paragem and linha are feminine.', 'choose', english: 'I want to go to platform three.', glosses: ['paragem' => 'stop', 'linha' => 'line']),
            Kit::gap($stage, 'sentences.choose_gap.linha', 'O autocarro da ___ cinco.', ['linha', 'cais', 'lugar'], 'linha', Kit::word('a linha', 'linha'), 'Da is de + a, so it goes with a feminine word, and linha is feminine. Cais and lugar are masculine.', 'choose', english: 'The line five bus.', glosses: ['cais' => 'platform', 'lugar' => 'seat']),
            Kit::gap($stage, 'sentences.choose_gap.ida-volta', 'Quero um bilhete ___.', ['de ida e volta', 'em ida e volta', 'a ida e volta'], 'de ida e volta', Kit::word('de ida e volta'), 'The fixed phrase for a return ticket is de ida e volta. The other two start with the wrong little word.', 'choose', english: 'I want a return ticket.', glosses: ['em ida e volta' => 'in going and coming back', 'a ida e volta' => 'at going and coming back']),
            Kit::gap($stage, 'sentences.choose_gap.ir-paragem', 'Eu ___ ir à paragem.', ['quero', 'tenho', 'estou'], 'quero', Kit::form('quero', true), 'Quero + ir means I want to go. Tenho and estou do not work straight before ir.', 'choose', english: 'I want to go to the stop.', glosses: ['tenho' => 'I have', 'estou' => 'I am']),

            Kit::typeGap($stage, 'sentences.type_gap.queremos', 'Nós ___ ir à estação.', 'We want to go to the station.', 'queremos', Kit::form('queremos'), 'Nós goes with queremos, with the ending -emos.'),
            Kit::typeGap($stage, 'sentences.type_gap.querem', 'Eles ___ um lugar.', 'They want a seat.', 'querem', Kit::form('querem'), 'Eles means more than one person, so the verb ends in -em.', glosses: ['querem' => 'they want']),
            Kit::typeGap($stage, 'sentences.type_gap.queres', 'Tu ___ o horário.', 'You want the timetable (informal you).', 'queres', Kit::form('queres', true), 'Tu goes with queres. Quer is for ele, ela or o senhor.', glosses: ['queres' => 'you want']),
            Kit::typeGap($stage, 'sentences.type_gap.paragem', 'A ___ está aqui.', 'The bus stop is here.', 'paragem', Kit::word('a paragem', 'paragem'), 'A stop where buses stop is a paragem.'),
            Kit::typeGap($stage, 'sentences.type_gap.horario', 'Quero o ___ do comboio.', 'I want the timetable of the train.', 'horário', Kit::word('o horário', 'horário'), 'The timetable is o horário.'),

            Kit::translate($stage, 'sentences.translate.ida-volta', 'I want a return ticket.', ['Quero um bilhete de ida e volta.'], [Kit::word('de ida e volta'), Kit::form('quero')]),
            Kit::translate($stage, 'sentences.translate.lugar', 'We want a seat.', ['Queremos um lugar.', 'Nós queremos um lugar.'], [Kit::word('o lugar', 'lugar'), Kit::form('queremos')]),
            Kit::translate($stage, 'sentences.translate.estacao', 'I want to go to the station.', ['Quero ir à estação.', 'Eu quero ir à estação.'], [Kit::word('a estação', 'estação'), Kit::form('quero')]),

            Kit::build($stage, 'sentences.build.horario', 'Ana wants the timetable.', 'A Ana quer o horário.', ['querem'], [Kit::word('o horário', 'horário'), Kit::form('quer')]),
            Kit::build($stage, 'sentences.build.linha', 'I want the line five bus.', 'Quero o autocarro da linha cinco.', ['queres'], [Kit::word('o autocarro', 'autocarro'), Kit::word('a linha', 'linha'), Kit::form('quero')], glosses: ['queres' => 'you want']),
            Kit::build($stage, 'sentences.build.comboio-cais', 'The train is on the platform.', 'O comboio está no cais.', ['na'], [Kit::word('o comboio', 'comboio'), Kit::word('o cais', 'cais')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.ida-volta', 'Quero um bilhete de ida e volta.', ['I want a return ticket.', 'I want a train ticket.', 'I want a seat.', 'I want the timetable.'], 'I want a return ticket.', [Kit::word('de ida e volta'), Kit::form('quero')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.hora', 'A que horas sai o comboio?', ['What time does the train leave?', 'Where does the train leave from?', 'Which train is it?', 'Is the train here?'], 'What time does the train leave?', [$time, Kit::word('o comboio', 'comboio')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.paragem', 'Quero ir à paragem.', ['I want to go to the stop.', 'I want to go to the station.', 'I want to go to the platform.', 'I want to go home.'], 'I want to go to the stop.', [Kit::word('a paragem', 'paragem'), Kit::form('quero')]),
            Kit::listenType($stage, 'sentences.listen_type.lugar', 'Queremos um lugar no comboio.', 'We want a seat on the train.', [Kit::word('o lugar', 'lugar'), Kit::form('queremos')]),
            Kit::listenType($stage, 'sentences.listen_type.autocarro', 'O autocarro sai da paragem.', 'The bus leaves from the stop.', [Kit::word('o autocarro', 'autocarro'), Kit::word('a paragem', 'paragem')]),
            Kit::listenType($stage, 'sentences.listen_type.horario', 'O horário está aqui.', 'The timetable is here.', [Kit::word('o horário', 'horário')]),
            Kit::listenType($stage, 'sentences.listen_type.querem', 'Querem ir à estação.', 'They want to go to the station.', [Kit::word('a estação', 'estação'), Kit::form('querem')], homophoneNote: self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ida-volta', 'Um bilhete de ida e volta.', 'A return ticket.', [Kit::word('de ida e volta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hora', 'A que horas sai o autocarro?', 'What time does the bus leave?', [$time, Kit::word('o autocarro', 'autocarro')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cais', 'Queremos ir ao cais.', 'We want to go to the platform.', [Kit::word('o cais', 'cais'), Kit::form('queremos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.horario', 'O horário está na estação.', 'The timetable is at the station.', [Kit::word('o horário', 'horário'), Kit::word('a estação', 'estação')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.lugar', 'Queres um lugar?', 'Do you want a seat? (informal you)', [['sim', 'não'], ['quero', 'lugar']], 'Sim, quero um lugar.', [Kit::word('o lugar', 'lugar'), Kit::form('quero')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.paragem', 'Onde está a paragem?', 'Where is the stop?', [['paragem', 'está'], ['aqui', 'ali']], 'A paragem está aqui.', [Kit::word('a paragem', 'paragem')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cais', 'De que cais sai o comboio?', 'Which platform does the train leave from?', [['sai', 'cais', 'comboio'], self::NUMBERS], 'O comboio sai do cais três.', [Kit::word('o cais', 'cais'), Kit::word('o comboio', 'comboio')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $time = Kit::word(self::TIME, 'a que horas sai');

        return [
            Kit::readPassage($stage, 'task.read_passage.bilhete', 'Read the conversation at the ticket desk.', [
                Kit::line('João', 'Boa tarde. Quero um bilhete de ida e volta.'),
                Kit::line('Funcionário', 'Muito bem.'),
                Kit::line('João', 'A que horas sai o comboio?'),
                Kit::line('Funcionário', 'Sai às cinco, do cais três.'),
                Kit::line('João', 'Quero um lugar, por favor.'),
                Kit::line('Funcionário', 'Aqui tem. Adeus.'),
            ], [
                Kit::question('What does João ask for first?', ['A return ticket', 'A bus', 'The timetable'], 'A return ticket'),
                Kit::question('What time does the train leave?', ['At five', 'At three', 'At two'], 'At five'),
                Kit::question('Which platform does it leave from?', ['Platform three', 'Platform five', 'Platform two'], 'Platform three'),
            ], [Kit::word('de ida e volta'), Kit::word('o comboio', 'comboio'), $time, Kit::word('o cais', 'cais'), Kit::word('o lugar', 'lugar'), Kit::form('quero')], 'read', glosses: ['funcionário' => 'clerk']),
            Kit::gap($stage, 'task.choose_gap.querem', 'A Ana e o Rui ___ ir de autocarro.', ['querem', 'quer', 'quero'], 'querem', Kit::form('querem', true), 'A Ana e o Rui means two people, so the verb is querem.', 'read', english: 'Ana and Rui want to go by bus.', glosses: ['querem' => 'they want', 'quer' => 'he or she wants', 'quero' => 'I want']),
            Kit::gap($stage, 'task.choose_gap.horario', 'Quero o ___ da linha cinco.', ['horário', 'paragem', 'estação'], 'horário', Kit::word('o horário', 'horário'), 'O needs a masculine word, and horário is masculine. Paragem and estação are feminine.', 'read', english: 'I want the timetable of line five.', glosses: ['paragem' => 'stop', 'estação' => 'station']),

            Kit::transform($stage, 'task.transform.queremos', 'Change it to nós.', 'Quero um lugar.', ['Queremos um lugar.', 'Nós queremos um lugar.'], [Kit::word('o lugar', 'lugar'), Kit::form('queremos')]),
            Kit::transform($stage, 'task.transform.querem', 'Change it to eles.', 'Quer ir à estação.', ['Querem ir à estação.', 'Eles querem ir à estação.'], [Kit::word('a estação', 'estação'), Kit::form('querem')]),
            Kit::transform($stage, 'task.transform.quer', 'Say it about Ana.', 'Quero o horário do comboio.', ['A Ana quer o horário do comboio.', 'Ana quer o horário do comboio.'], [Kit::word('o horário', 'horário'), Kit::word('o comboio', 'comboio'), Kit::form('quer')]),
            Kit::writeGuided($stage, 'task.write_guided.bilhete', 'Say that you want a return ticket and a seat.', ['quero', 'bilhete', 'de ida e volta', 'lugar'], 'Quero um bilhete de ida e volta. Quero um lugar.', [
                ['forms' => ['quero'], 'term' => null],
                ['forms' => ['volta'], 'term' => 'de ida e volta'],
                ['forms' => ['lugar'], 'term' => 'o lugar'],
            ], [Kit::word('de ida e volta'), Kit::word('o lugar', 'lugar'), Kit::form('quero')]),
            Kit::writeGuided($stage, 'task.write_guided.horario', 'Ask what time the bus leaves and say that you want the timetable.', ['quero', 'a que horas sai', 'autocarro', 'horário'], 'A que horas sai o autocarro? Quero o horário.', [
                ['forms' => ['sai'], 'term' => self::TIME],
                ['forms' => ['autocarro'], 'term' => 'o autocarro'],
                ['forms' => ['horário'], 'term' => 'o horário'],
                ['forms' => ['quero'], 'term' => null],
            ], [$time, Kit::word('o autocarro', 'autocarro'), Kit::word('o horário', 'horário'), Kit::form('quero')]),
            Kit::build($stage, 'task.build.paragem', 'We want to go to the bus stop.', 'Queremos ir à paragem de autocarro.', ['quero', 'querem'], [Kit::word('a paragem', 'paragem'), Kit::word('o autocarro', 'autocarro'), Kit::form('queremos')], 'write', ['quero' => 'I want', 'querem' => 'they want']),
            Kit::build($stage, 'task.build.linha', 'The line five bus leaves from the stop.', 'O autocarro da linha cinco sai da paragem.', ['cais', 'ao'], [Kit::word('o autocarro', 'autocarro'), Kit::word('a linha', 'linha'), Kit::word('a paragem', 'paragem')], 'write', ['cais' => 'platform']),
            Kit::build($stage, 'task.build.lugar', 'I want a seat on the train, please.', 'Quero um lugar no comboio, por favor.', ['queres', 'uma'], [Kit::word('o lugar', 'lugar'), Kit::word('o comboio', 'comboio'), Kit::form('quero')], 'write', ['queres' => 'you want']),
            Kit::translate($stage, 'task.translate.queres', 'Do you want a return ticket? (informal you)', ['Queres um bilhete de ida e volta?', 'Tu queres um bilhete de ida e volta?'], [Kit::word('de ida e volta'), Kit::form('queres')], 'write'),
            Kit::translate($stage, 'task.translate.hora', 'What time does the bus leave?', ['A que horas sai o autocarro?'], [$time, Kit::word('o autocarro', 'autocarro')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.paragem', [
                Kit::line('Ana', 'Boa tarde. Onde está a paragem da linha cinco?'),
                Kit::line('Marta', 'Está aqui.'),
                Kit::line('Ana', 'A que horas sai o autocarro?'),
                Kit::line('Marta', 'Aqui está o horário. Sai às duas.'),
                Kit::line('Ana', 'Muito obrigada. Quero ir à estação.'),
            ], [
                Kit::question('What does Ana ask about first?', ['Where the bus stop is', 'The price of a ticket', 'A seat'], 'Where the bus stop is'),
                Kit::question('What does Marta show Ana?', ['The timetable', 'A ticket', 'A map'], 'The timetable'),
                Kit::question('Where does Ana want to go?', ['To the station', 'To the platform', 'Home'], 'To the station'),
            ], [
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the bus leave?', ['At two', 'At five', 'At three'], 'At two'),
                Kit::question('Does Ana say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('a paragem', 'paragem'), Kit::word('a linha', 'linha'), $time, Kit::word('o autocarro', 'autocarro'), Kit::word('o horário', 'horário'), Kit::word('a estação', 'estação'), Kit::form('quero')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.comboio', 'O meu comboio sai do cais dois.', 'My train leaves from platform two.', [Kit::word('o comboio', 'comboio'), Kit::word('o cais', 'cais')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.vocês', 'Vocês querem ir à estação.', 'You (all) want to go to the station.', [Kit::word('a estação', 'estação'), Kit::form('querem')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.quer', 'A Ana quer um bilhete de ida e volta.', 'Ana wants a return ticket.', [Kit::word('de ida e volta'), Kit::form('quer')], 'listen', homophoneNote: self::ART_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.bilhete', 'Queres um bilhete de ida e volta?', 'Do you want a return ticket? (informal you)', [['sim', 'não'], ['quero', 'bilhete', 'volta']], 'Sim, quero um bilhete de ida e volta.', [Kit::word('de ida e volta'), Kit::form('quero')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.hora', 'A que horas sai o comboio?', 'What time does the train leave?', [['sai', 'comboio'], self::NUMBERS], 'O comboio sai às cinco.', [Kit::word(self::TIME, 'a que horas sai'), Kit::word('o comboio', 'comboio')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.linha', 'Que linha queres?', 'Which line do you want? (informal you)', [['quero', 'linha', 'autocarro'], self::NUMBERS], 'Quero a linha cinco.', [Kit::word('a linha', 'linha'), Kit::form('quero')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.estacao', 'Onde queres ir? À estação?', 'Where do you want to go? To the station? (informal you)', [['quero', 'vou', 'sim', 'não'], ['estação', 'paragem', 'cais']], 'Quero ir à estação.', [Kit::word('a estação', 'estação'), Kit::form('queres')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.lugar', 'Boa tarde, quero um lugar, por favor.', 'Good afternoon, I want a seat, please.', [Kit::word('o lugar', 'lugar'), Kit::form('quero')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.comboio', 'O comboio sai da estação às duas.', 'The train leaves from the station at two.', [Kit::word('o comboio', 'comboio'), Kit::word('a estação', 'estação')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.hora', 'What time does the bus leave from the stop?', ['A que horas sai o autocarro da paragem?'], [Kit::word(self::TIME, 'a que horas sai'), Kit::word('o autocarro', 'autocarro'), Kit::word('a paragem', 'paragem')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.queres', 'Do you want a seat on the train? (informal you)', ['Queres um lugar no comboio?', 'Tu queres um lugar no comboio?'], [Kit::word('o lugar', 'lugar'), Kit::word('o comboio', 'comboio'), Kit::form('queres', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.queremos', 'We want the timetable of line five.', ['Queremos o horário da linha cinco.', 'Nós queremos o horário da linha cinco.'], [Kit::word('o horário', 'horário'), Kit::word('a linha', 'linha'), Kit::form('queremos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.querem', 'They want a return ticket.', ['Querem um bilhete de ida e volta.', 'Eles querem um bilhete de ida e volta.', 'Elas querem um bilhete de ida e volta.'], [Kit::word('de ida e volta'), Kit::form('querem')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.quer', 'A Marta ___ o horário.', 'Marta wants the timetable.', 'quer', Kit::form('quer'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.queremos', 'Nós ___ um lugar.', 'We want a seat.', 'queremos', Kit::form('queremos', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.horario', 'Quero o horário da estação, por favor.', 'I want the timetable of the station, please.', [Kit::word('o horário', 'horário'), Kit::word('a estação', 'estação'), Kit::form('quero')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.comboio', 'O comboio sai do cais quatro.', 'The train leaves from platform four.', [Kit::word('o comboio', 'comboio'), Kit::word('o cais', 'cais')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.autocarro', 'O meu lugar está no autocarro.', 'My seat is on the bus.', [Kit::word('o lugar', 'lugar'), Kit::word('o autocarro', 'autocarro')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.comboio', [
                Kit::line('Rui', 'Bom dia. A que horas sai o comboio, por favor?'),
                Kit::line('Funcionário', 'Sai às quatro, do cais dois.'),
                Kit::line('Rui', 'E onde está o cais?'),
                Kit::line('Funcionário', 'Está ali.'),
            ], [
                Kit::question('What does Rui ask about?', ['The time of the train', 'A return ticket', 'The bus stop'], 'The time of the train'),
                Kit::question('Which platform does the train leave from?', ['Platform two', 'Platform four', 'Platform five'], 'Platform two'),
                Kit::question('Where is the platform?', ['Over there', 'Here', 'The conversation does not say.'], 'Over there'),
            ], [
                Kit::question('Who speaks first?', ['Rui', 'The clerk', 'Nobody'], 'Rui'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the train leave?', ['At four', 'At two', 'At five'], 'At four'),
            ], [Kit::word('o comboio', 'comboio'), Kit::word(self::TIME, 'a que horas sai'), Kit::word('o cais', 'cais'), Kit::word('a estação', 'estação')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.horario', 'Read the conversation.', [
                Kit::line('Marta', 'Boa tarde. Tem o horário do autocarro?'),
                Kit::line('Funcionário', 'Sim. O autocarro da linha cinco sai às duas.'),
                Kit::line('Marta', 'Quero um bilhete de ida e volta, por favor.'),
                Kit::line('Funcionário', 'Aqui tem.'),
            ], [
                Kit::question('What does Marta ask for first?', ['The timetable', 'A ticket', 'A seat'], 'The timetable'),
                Kit::question('Which line leaves at two?', ['Line five', 'Line two', 'Line ten'], 'Line five'),
            ], [Kit::word('o horário', 'horário'), Kit::word('o autocarro', 'autocarro'), Kit::word('a linha', 'linha'), Kit::word('de ida e volta')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.lugar', 'Há um lugar aqui?', 'Is there a seat here?', [['sim', 'não', 'há'], ['lugar']], 'Sim, há um lugar.', [Kit::word('o lugar', 'lugar')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.estacao', 'Onde está a estação?', 'Where is the station?', [['estação', 'está'], ['aqui', 'ali']], 'A estação está ali.', [Kit::word('a estação', 'estação')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.bilhete', 'O senhor quer um bilhete de ida e volta?', 'Do you want a return ticket? (formal you)', [['sim', 'não'], ['quero', 'bilhete', 'volta']], 'Sim, quero um bilhete de ida e volta.', [Kit::word('de ida e volta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.cais', 'I want to go to the platform. (use cais)', ['Quero ir ao cais.', 'Eu quero ir ao cais.'], [Kit::word('o cais', 'cais'), Kit::form('quero')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.lugar', 'The seat is on the train.', ['O lugar está no comboio.'], [Kit::word('o lugar', 'lugar'), Kit::word('o comboio', 'comboio')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.querem', 'Do they want the line five bus?', ['Querem o autocarro da linha cinco?', 'Eles querem o autocarro da linha cinco?', 'Elas querem o autocarro da linha cinco?'], [Kit::word('o autocarro', 'autocarro'), Kit::word('a linha', 'linha'), Kit::form('querem', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hora', 'What time does the train leave from the station?', ['A que horas sai o comboio da estação?'], [Kit::word(self::TIME, 'a que horas sai'), Kit::word('o comboio', 'comboio'), Kit::word('a estação', 'estação')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.quer', 'O Rui ___ um lugar no comboio.', 'Rui wants a seat on the train.', 'quer', Kit::form('quer'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.querem', 'Vocês ___ ir à paragem.', 'You (all) want to go to the stop.', 'querem', Kit::form('querem', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.lugar', 'Quero um lugar, obrigado.', 'I want a seat, thank you.', [Kit::word('o lugar', 'lugar'), Kit::form('quero')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.paragem', 'A paragem da linha cinco está aqui.', 'The stop of line five is here.', [Kit::word('a paragem', 'paragem'), Kit::word('a linha', 'linha')], 'dictation', $set, homophoneNote: self::ART_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.queremos', 'Queremos o horário e um bilhete de ida e volta.', 'We want the timetable and a return ticket.', [Kit::word('de ida e volta'), Kit::word('o horário', 'horário'), Kit::form('queremos')], 'dictation', $set),
        ];
    }
}
