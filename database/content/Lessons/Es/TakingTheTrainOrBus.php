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

final class TakingTheTrainOrBus implements UnitContent
{
    private const TIME = '¿a qué hora sale?';

    private const NUMBERS = ['uno', 'una', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once', 'doce'];

    private const A_NOTE = 'The word a (to) sounds like ha, but here it is the little word a, as in a la estación.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'taking-the-train-or-bus';
    }

    public function words(): array
    {
        return [
            new WordData('el tren', cue: 'train', forms: ['trenes']),
            new WordData('el autobús', cue: 'bus', forms: ['autobuses']),
            new WordData('la estación', cue: 'station', forms: ['ir']),
            new WordData('la parada', cue: 'stop (where a bus or tram stops)', forms: ['paradas']),
            new WordData('el andén', cue: 'platform (at a train station)'),
            new WordData('el horario', cue: 'timetable'),
            new WordData('el asiento', cue: 'seat'),
            new WordData('de ida y vuelta', cue: 'return (as in a return ticket)', forms: ['billete', 'billetes'], note: 'You say un billete de ida y vuelta for a return ticket. A single ticket is un billete de ida.'),
            new WordData(self::TIME, cue: 'what time does it leave?', accepted: ['a qué hora sale']),
            new WordData('la línea', cue: 'line (of a bus or metro)', forms: ['líneas']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Quiero un billete.', 'english' => 'I want a ticket.'],
            ['text' => 'Quiero ir a la estación.', 'english' => 'I want to go to the station.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-06', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $time = Kit::word(self::TIME, 'a qué hora sale');

        return [
            Kit::gap($stage, 'sentences.choose_gap.quiero-billete', 'Yo ___ un billete.', ['quiero', 'quieres', 'quiere'], 'quiero', Kit::form('quiero'), 'Yo goes with quiero. Quieres is for tú and quiere is for él or ella.', 'choose', glosses: ['quiero' => 'I want', 'quieres' => 'you want', 'quiere' => 'he or she wants']),
            Kit::gap($stage, 'sentences.choose_gap.marta-quiere', 'Marta ___ ir a la estación.', ['quiere', 'quiero', 'queremos'], 'quiere', Kit::form('quiere'), 'Marta is one person, so the verb is quiere. Quiero is for yo and queremos is for nosotros.', 'choose', glosses: ['quiere' => 'he or she wants', 'quiero' => 'I want', 'queremos' => 'we want']),
            Kit::gap($stage, 'sentences.choose_gap.andén', 'Quiero ir al ___ tres.', ['andén', 'parada', 'línea'], 'andén', Kit::word('el andén', 'andén'), 'Al is a + el, so it needs a masculine word. Andén is masculine, while parada and línea are feminine.', 'choose', english: 'I want to go to platform three.', glosses: ['parada' => 'stop', 'línea' => 'line']),
            Kit::gap($stage, 'sentences.choose_gap.línea', 'El autobús de la ___ cinco.', ['línea', 'andén', 'asiento'], 'línea', Kit::word('la línea', 'línea'), 'La goes with a feminine word, and línea is feminine. Andén and asiento are masculine.', 'choose', english: 'The line five bus.', glosses: ['andén' => 'platform', 'asiento' => 'seat']),
            Kit::gap($stage, 'sentences.choose_gap.ida-vuelta', 'Quiero un billete ___.', ['de ida y vuelta', 'en ida y vuelta', 'a ida y vuelta'], 'de ida y vuelta', Kit::word('de ida y vuelta'), 'The fixed phrase for a return ticket is de ida y vuelta. The other two start with the wrong little word.', 'choose', english: 'I want a return ticket.', glosses: ['en ida y vuelta' => 'in going and coming back', 'a ida y vuelta' => 'at going and coming back']),
            Kit::gap($stage, 'sentences.choose_gap.ir-parada', 'Yo ___ ir a la parada.', ['quiero', 'tengo', 'estoy'], 'quiero', Kit::form('quiero', true), 'Quiero + ir means I want to go. Tengo and estoy do not work straight before ir.', 'choose', english: 'I want to go to the stop.', glosses: ['tengo' => 'I have', 'estoy' => 'I am']),

            Kit::typeGap($stage, 'sentences.type_gap.queremos', 'Nosotros ___ ir a la estación.', 'We want to go to the station.', 'queremos', Kit::form('queremos'), 'Nosotros goes with queremos. Note that the e does not change here.'),
            Kit::typeGap($stage, 'sentences.type_gap.quieren', 'Ellos ___ un asiento.', 'They want a seat.', 'quieren', Kit::form('quieren'), 'Ellos means more than one person, so the verb ends in -en.', glosses: ['quieren' => 'they want']),
            Kit::typeGap($stage, 'sentences.type_gap.quieres', 'Tú ___ el horario.', 'You want the timetable (informal you).', 'quieres', Kit::form('quieres', true), 'Tú goes with quieres. Quiere is for él, ella or usted.', glosses: ['quieres' => 'you want']),
            Kit::typeGap($stage, 'sentences.type_gap.parada', 'La ___ está aquí.', 'The bus stop is here.', 'parada', Kit::word('la parada', 'parada'), 'A stop where buses stop is a parada.'),
            Kit::typeGap($stage, 'sentences.type_gap.horario', 'Quiero el ___ del tren.', 'I want the timetable of the train.', 'horario', Kit::word('el horario', 'horario'), 'The timetable is el horario.'),

            Kit::translate($stage, 'sentences.translate.ida-vuelta', 'I want a return ticket.', ['Quiero un billete de ida y vuelta.'], [Kit::word('de ida y vuelta'), Kit::form('quiero')]),
            Kit::translate($stage, 'sentences.translate.asiento', 'We want a seat.', ['Queremos un asiento.', 'Nosotros queremos un asiento.', 'Nosotras queremos un asiento.'], [Kit::word('el asiento', 'asiento'), Kit::form('queremos')]),
            Kit::translate($stage, 'sentences.translate.estacion', 'I want to go to the station.', ['Quiero ir a la estación.', 'Yo quiero ir a la estación.'], [Kit::word('la estación', 'estación'), Kit::form('quiero')]),

            Kit::build($stage, 'sentences.build.horario', 'Ana wants the timetable.', 'Ana quiere el horario.', ['quieren'], [Kit::word('el horario', 'horario'), Kit::form('quiere')]),
            Kit::build($stage, 'sentences.build.linea', 'I want the line five bus.', 'Quiero el autobús de la línea cinco.', ['quieres'], [Kit::word('el autobús', 'autobús'), Kit::word('la línea', 'línea'), Kit::form('quiero')], glosses: ['quieres' => 'you want']),
            Kit::build($stage, 'sentences.build.tren-anden', 'The train is on the platform.', 'El tren está en el andén.', ['la'], [Kit::word('el tren', 'tren'), Kit::word('el andén', 'andén')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.ida-vuelta', 'Quiero un billete de ida y vuelta.', ['I want a return ticket.', 'I want a train ticket.', 'I want a seat.', 'I want the timetable.'], 'I want a return ticket.', [Kit::word('de ida y vuelta'), Kit::form('quiero')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.hora', '¿A qué hora sale el tren?', ['What time does the train leave?', 'Where does the train leave from?', 'Which train is it?', 'Is the train here?'], 'What time does the train leave?', [$time, Kit::word('el tren', 'tren')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.parada', 'Quiero ir a la parada.', ['I want to go to the stop.', 'I want to go to the station.', 'I want to go to the platform.', 'I want to go home.'], 'I want to go to the stop.', [Kit::word('la parada', 'parada'), Kit::form('quiero')]),
            Kit::listenType($stage, 'sentences.listen_type.asiento', 'Queremos un asiento.', 'We want a seat.', [Kit::word('el asiento', 'asiento'), Kit::form('queremos')]),
            Kit::listenType($stage, 'sentences.listen_type.autobus', 'El autobús sale de la parada.', 'The bus leaves from the stop.', [Kit::word('el autobús', 'autobús'), Kit::word('la parada', 'parada')]),
            Kit::listenType($stage, 'sentences.listen_type.horario', 'El horario está aquí.', 'The timetable is here.', [Kit::word('el horario', 'horario')]),
            Kit::listenType($stage, 'sentences.listen_type.quieren', 'Quieren ir a la estación.', 'They want to go to the station.', [Kit::word('la estación', 'estación'), Kit::form('quieren')], homophoneNote: self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ida-vuelta', 'Un billete de ida y vuelta.', 'A return ticket.', [Kit::word('de ida y vuelta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hora', '¿A qué hora sale el autobús?', 'What time does the bus leave?', [$time, Kit::word('el autobús', 'autobús')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.anden', 'Queremos ir al andén.', 'We want to go to the platform.', [Kit::word('el andén', 'andén'), Kit::form('queremos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.horario', 'El horario está en la estación.', 'The timetable is at the station.', [Kit::word('el horario', 'horario'), Kit::word('la estación', 'estación')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.asiento', '¿Quieres un asiento?', 'Do you want a seat? (informal you)', [['sí', 'no'], ['quiero', 'asiento']], 'Sí, quiero un asiento.', [Kit::word('el asiento', 'asiento'), Kit::form('quiero')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.parada', '¿Dónde está la parada?', 'Where is the stop?', [['parada', 'está'], ['aquí', 'allí']], 'La parada está aquí.', [Kit::word('la parada', 'parada')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.anden', '¿De qué andén sale el tren?', 'Which platform does the train leave from?', [['sale', 'andén', 'tren'], self::NUMBERS], 'El tren sale del andén tres.', [Kit::word('el andén', 'andén'), Kit::word('el tren', 'tren')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $time = Kit::word(self::TIME, 'a qué hora sale');

        return [
            Kit::readPassage($stage, 'task.read_passage.billete', 'Read the conversation at the ticket desk.', [
                Kit::line('Pablo', 'Buenas tardes. Quiero un billete de ida y vuelta.'),
                Kit::line('Empleado', 'Muy bien.'),
                Kit::line('Pablo', '¿A qué hora sale el tren?'),
                Kit::line('Empleado', 'Sale a las cinco, del andén tres.'),
                Kit::line('Pablo', 'Quiero un asiento, por favor.'),
                Kit::line('Empleado', 'Aquí tiene. Adiós.'),
            ], [
                Kit::question('What does Pablo ask for first?', ['A return ticket', 'A bus', 'The timetable'], 'A return ticket'),
                Kit::question('What time does the train leave?', ['At five', 'At three', 'At two'], 'At five'),
                Kit::question('Which platform does it leave from?', ['Platform three', 'Platform five', 'Platform two'], 'Platform three'),
            ], [Kit::word('de ida y vuelta'), Kit::word('el tren', 'tren'), $time, Kit::word('el andén', 'andén'), Kit::word('el asiento', 'asiento'), Kit::form('quiero')], 'read', glosses: ['empleado' => 'clerk']),
            Kit::gap($stage, 'task.choose_gap.quieren', 'Ana y Luis ___ ir en autobús.', ['quieren', 'quiere', 'quiero'], 'quieren', Kit::form('quieren', true), 'Ana y Luis means two people, so the verb is quieren.', 'read', english: 'Ana and Luis want to go by bus.', glosses: ['quieren' => 'they want', 'quiere' => 'he or she wants', 'quiero' => 'I want']),
            Kit::gap($stage, 'task.choose_gap.horario', 'Quiero el ___ de la línea cinco.', ['horario', 'parada', 'estación'], 'horario', Kit::word('el horario', 'horario'), 'El needs a masculine word, and horario is masculine. Parada and estación are feminine.', 'read', english: 'I want the timetable of line five.', glosses: ['parada' => 'stop', 'estación' => 'station']),

            Kit::transform($stage, 'task.transform.queremos', 'Change it to nosotros.', 'Quiero un asiento.', ['Queremos un asiento.', 'Nosotros queremos un asiento.'], [Kit::word('el asiento', 'asiento'), Kit::form('queremos')]),
            Kit::transform($stage, 'task.transform.quieren', 'Change it to ellos.', 'Quiere ir a la estación.', ['Quieren ir a la estación.', 'Ellos quieren ir a la estación.'], [Kit::word('la estación', 'estación'), Kit::form('quieren')]),
            Kit::transform($stage, 'task.transform.quiere', 'Say it about Ana.', 'Quiero el horario del tren.', ['Ana quiere el horario del tren.'], [Kit::word('el horario', 'horario'), Kit::word('el tren', 'tren'), Kit::form('quiere')]),
            Kit::writeGuided($stage, 'task.write_guided.billete', 'Say that you want a return ticket and a seat.', ['quiero', 'billete', 'de ida y vuelta', 'asiento'], 'Quiero un billete de ida y vuelta. Quiero un asiento.', [
                ['forms' => ['quiero'], 'term' => null],
                ['forms' => ['vuelta'], 'term' => 'de ida y vuelta'],
                ['forms' => ['asiento'], 'term' => 'el asiento'],
            ], [Kit::word('de ida y vuelta'), Kit::word('el asiento', 'asiento'), Kit::form('quiero')]),
            Kit::writeGuided($stage, 'task.write_guided.horario', 'Ask what time the bus leaves and say that you want the timetable.', ['quiero', 'a qué hora sale', 'autobús', 'horario'], '¿A qué hora sale el autobús? Quiero el horario.', [
                ['forms' => ['sale'], 'term' => self::TIME],
                ['forms' => ['autobús'], 'term' => 'el autobús'],
                ['forms' => ['horario'], 'term' => 'el horario'],
                ['forms' => ['quiero'], 'term' => null],
            ], [$time, Kit::word('el autobús', 'autobús'), Kit::word('el horario', 'horario'), Kit::form('quiero')]),
            Kit::build($stage, 'task.build.parada', 'We want to go to the bus stop.', 'Queremos ir a la parada de autobús.', ['quiero', 'quieren'], [Kit::word('la parada', 'parada'), Kit::word('el autobús', 'autobús'), Kit::form('queremos')], 'write', ['quiero' => 'I want', 'quieren' => 'they want']),
            Kit::build($stage, 'task.build.linea', 'The line five bus leaves from the stop.', 'El autobús de la línea cinco sale de la parada.', ['andén', 'al'], [Kit::word('el autobús', 'autobús'), Kit::word('la línea', 'línea'), Kit::word('la parada', 'parada')], 'write', ['andén' => 'platform']),
            Kit::build($stage, 'task.build.asiento', 'I want a seat on the train, please.', 'Quiero un asiento en el tren, por favor.', ['quieres', 'una'], [Kit::word('el asiento', 'asiento'), Kit::word('el tren', 'tren'), Kit::form('quiero')], 'write', ['quieres' => 'you want']),
            Kit::translate($stage, 'task.translate.quieres', 'Do you want a return ticket? (informal you)', ['¿Quieres un billete de ida y vuelta?', '¿Tú quieres un billete de ida y vuelta?'], [Kit::word('de ida y vuelta'), Kit::form('quieres')], 'write'),
            Kit::translate($stage, 'task.translate.hora', 'What time does the bus leave?', ['¿A qué hora sale el autobús?'], [$time, Kit::word('el autobús', 'autobús')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.parada', [
                Kit::line('Ana', 'Buenas tardes. ¿Dónde está la parada de la línea cinco?'),
                Kit::line('Marta', 'Está aquí.'),
                Kit::line('Ana', '¿A qué hora sale el autobús?'),
                Kit::line('Marta', 'Aquí está el horario. Sale a las dos.'),
                Kit::line('Ana', 'Muchas gracias. Quiero ir a la estación.'),
            ], [
                Kit::question('What does Ana ask about first?', ['Where the bus stop is', 'The price of a ticket', 'A seat'], 'Where the bus stop is'),
                Kit::question('What does Marta show Ana?', ['The timetable', 'A ticket', 'A map'], 'The timetable'),
                Kit::question('Where does Ana want to go?', ['To the station', 'To the platform', 'Home'], 'To the station'),
            ], [
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the bus leave?', ['At two', 'At five', 'At three'], 'At two'),
                Kit::question('Does Ana say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('la parada', 'parada'), Kit::word('la línea', 'línea'), $time, Kit::word('el autobús', 'autobús'), Kit::word('el horario', 'horario'), Kit::word('la estación', 'estación'), Kit::form('quiero')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.tren', 'Mi tren sale del andén dos.', 'My train leaves from platform two.', [Kit::word('el tren', 'tren'), Kit::word('el andén', 'andén')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.queréis', 'Vosotros queréis ir a la estación.', 'You (all) want to go to the station.', [Kit::word('la estación', 'estación'), Kit::form('queréis')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.quiere', 'Ana quiere un billete de ida y vuelta.', 'Ana wants a return ticket.', [Kit::word('de ida y vuelta'), Kit::form('quiere')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.billete', '¿Quieres un billete de ida y vuelta?', 'Do you want a return ticket? (informal you)', [['sí', 'no'], ['quiero', 'billete', 'vuelta']], 'Sí, quiero un billete de ida y vuelta.', [Kit::word('de ida y vuelta'), Kit::form('quiero')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.hora', '¿A qué hora sale el tren?', 'What time does the train leave?', [['sale', 'tren'], self::NUMBERS], 'El tren sale a las cinco.', [$time, Kit::word('el tren', 'tren')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.linea', '¿Qué línea quieres?', 'Which line do you want? (informal you)', [['quiero', 'línea', 'autobús'], self::NUMBERS], 'Quiero la línea cinco.', [Kit::word('la línea', 'línea'), Kit::form('quiero')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.estacion', '¿Dónde quieres ir? ¿A la estación?', 'Where do you want to go? To the station? (informal you)', [['quiero', 'voy', 'sí', 'no'], ['estación', 'parada', 'andén']], 'Quiero ir a la estación.', [Kit::word('la estación', 'estación'), Kit::form('quieres')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.asiento', 'Buenas tardes, quiero un asiento, por favor.', 'Good afternoon, I want a seat, please.', [Kit::word('el asiento', 'asiento'), Kit::form('quiero')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.tren', 'El tren sale de la estación a las dos.', 'The train leaves from the station at two.', [Kit::word('el tren', 'tren'), Kit::word('la estación', 'estación')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.hora', 'What time does the bus leave from the stop?', ['¿A qué hora sale el autobús de la parada?'], [Kit::word(self::TIME, 'a qué hora sale'), Kit::word('el autobús', 'autobús'), Kit::word('la parada', 'parada')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.quieres', 'Do you want a seat on the train? (informal you)', ['¿Quieres un asiento en el tren?', '¿Tú quieres un asiento en el tren?'], [Kit::word('el asiento', 'asiento'), Kit::word('el tren', 'tren'), Kit::form('quieres', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.queremos', 'We want the timetable of line five.', ['Queremos el horario de la línea cinco.', 'Nosotros queremos el horario de la línea cinco.', 'Nosotras queremos el horario de la línea cinco.'], [Kit::word('el horario', 'horario'), Kit::word('la línea', 'línea'), Kit::form('queremos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.quieren', 'They want a return ticket.', ['Quieren un billete de ida y vuelta.', 'Ellos quieren un billete de ida y vuelta.', 'Ellas quieren un billete de ida y vuelta.'], [Kit::word('de ida y vuelta'), Kit::form('quieren')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.quiere', 'Ana ___ ir a la estación.', 'Ana wants to go to the station.', 'quiere', Kit::form('quiere'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.queremos', 'Nosotros ___ un asiento.', 'We want a seat.', 'queremos', Kit::form('queremos', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.horario', 'Quiero el horario de la estación, por favor.', 'I want the timetable of the station, please.', [Kit::word('el horario', 'horario'), Kit::word('la estación', 'estación'), Kit::form('quiero')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.tren', 'El tren sale del andén cuatro.', 'The train leaves from platform four.', [Kit::word('el tren', 'tren'), Kit::word('el andén', 'andén')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.autobus', 'Mi asiento está en el autobús.', 'My seat is on the bus.', [Kit::word('el asiento', 'asiento'), Kit::word('el autobús', 'autobús')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.tren', [
                Kit::line('Luis', 'Buenos días. ¿A qué hora sale el tren, por favor?'),
                Kit::line('Empleado', 'Sale a las cuatro, del andén dos.'),
                Kit::line('Luis', '¿Y dónde está el andén?'),
                Kit::line('Empleado', 'Está allí, en la estación.'),
            ], [
                Kit::question('What does Luis ask about?', ['The time of the train', 'A return ticket', 'The bus stop'], 'The time of the train'),
                Kit::question('Which platform does the train leave from?', ['Platform two', 'Platform four', 'Platform five'], 'Platform two'),
                Kit::question('Where is the platform?', ['Over there', 'Here', 'The conversation does not say.'], 'Over there'),
            ], [
                Kit::question('Who speaks first?', ['Luis', 'The clerk', 'Nobody'], 'Luis'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the train leave?', ['At four', 'At two', 'At five'], 'At four'),
            ], [Kit::word('el tren', 'tren'), Kit::word(self::TIME, 'a qué hora sale'), Kit::word('el andén', 'andén'), Kit::word('la estación', 'estación')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.horario', 'Read the conversation.', [
                Kit::line('Marta', 'Buenas tardes. ¿Tiene el horario del autobús?'),
                Kit::line('Empleado', 'Sí. El autobús de la línea cinco sale a las dos.'),
                Kit::line('Marta', 'Quiero un billete de ida y vuelta, por favor.'),
                Kit::line('Empleado', 'Aquí tiene.'),
            ], [
                Kit::question('What does Marta ask for first?', ['The timetable', 'A ticket', 'A seat'], 'The timetable'),
                Kit::question('Which line leaves at two?', ['Line five', 'Line two', 'Line ten'], 'Line five'),
            ], [Kit::word('el horario', 'horario'), Kit::word('el autobús', 'autobús'), Kit::word('la línea', 'línea'), Kit::word('de ida y vuelta')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.asiento', '¿Hay un asiento aquí?', 'Is there a seat here?', [['sí', 'no', 'hay'], ['asiento']], 'Sí, hay un asiento.', [Kit::word('el asiento', 'asiento')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.estacion', '¿Dónde está la estación?', 'Where is the station?', [['estación', 'está'], ['aquí', 'allí']], 'La estación está allí.', [Kit::word('la estación', 'estación')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.billete', '¿Quiere un billete de ida y vuelta?', 'Do you want a return ticket? (formal you)', [['sí', 'no'], ['quiero', 'billete', 'vuelta']], 'Sí, quiero un billete de ida y vuelta.', [Kit::word('de ida y vuelta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.anden', 'I want to go to the platform.', ['Quiero ir al andén.', 'Yo quiero ir al andén.'], [Kit::word('el andén', 'andén'), Kit::form('quiero')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.asiento', 'The seat is on the train.', ['El asiento está en el tren.'], [Kit::word('el asiento', 'asiento'), Kit::word('el tren', 'tren')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.quieren', 'Do they want the line five bus?', ['¿Quieren el autobús de la línea cinco?', '¿Ellos quieren el autobús de la línea cinco?', '¿Ellas quieren el autobús de la línea cinco?'], [Kit::word('el autobús', 'autobús'), Kit::word('la línea', 'línea'), Kit::form('quieren', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hora', 'What time does the train leave from the station?', ['¿A qué hora sale el tren de la estación?'], [Kit::word(self::TIME, 'a qué hora sale'), Kit::word('el tren', 'tren'), Kit::word('la estación', 'estación')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.quiere', 'Luis ___ un billete de ida y vuelta.', 'Luis wants a return ticket.', 'quiere', Kit::form('quiere'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.queréis', 'Vosotros ___ ir a la parada.', 'You (all) want to go to the stop.', 'queréis', Kit::form('queréis', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.asiento', 'Quiero un asiento, gracias.', 'I want a seat, thank you.', [Kit::word('el asiento', 'asiento'), Kit::form('quiero')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.parada', 'La parada de la línea cinco está aquí.', 'The stop of line five is here.', [Kit::word('la parada', 'parada'), Kit::word('la línea', 'línea')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.queremos', 'Queremos el horario y un billete de ida y vuelta.', 'We want the timetable and a return ticket.', [Kit::word('de ida y vuelta'), Kit::word('el horario', 'horario'), Kit::form('queremos')], 'dictation', $set),
        ];
    }
}
