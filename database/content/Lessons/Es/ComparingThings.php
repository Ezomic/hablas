<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ComparingThings implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'comparing-things';
    }

    public function words(): array
    {
        return [
            new WordData('barato', cue: 'cheap (masculine)', forms: ['barata', 'baratos', 'baratas']),
            new WordData('caro', cue: 'expensive (masculine)', forms: ['cara', 'caros', 'caras'], note: 'Caro is expensive. Cara is also the word for face, but after ser or estar it means expensive: la casa es cara.'),
            new WordData('rápido', cue: 'fast, quick (masculine)', forms: ['rápida', 'rápidos', 'rápidas']),
            new WordData('lento', cue: 'slow (masculine)', forms: ['lenta', 'lentos', 'lentas']),
            new WordData('cómodo', cue: 'comfortable (masculine)', forms: ['cómoda', 'cómodos', 'cómodas']),
            new WordData('fácil', cue: 'easy', forms: ['fáciles'], note: 'Fácil has the same form for masculine and feminine. The plural is fáciles.'),
            new WordData('difícil', cue: 'difficult, hard', forms: ['difíciles'], note: 'Difícil has the same form for masculine and feminine. The plural is difíciles.'),
            new WordData('grande', cue: 'big, large', forms: ['grandes'], note: 'Grande has the same form for masculine and feminine. The plural is grandes.'),
            new WordData('pequeño', cue: 'small, little (masculine)', forms: ['pequeña', 'pequeños', 'pequeñas']),
            new WordData('nuevo', cue: 'new (masculine)', forms: ['nueva', 'nuevos', 'nuevas']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El tren es más rápido que el autobús.', 'english' => 'The train is faster than the bus.'],
            ['text' => 'Ana es tan alta como Marta.', 'english' => 'Ana is as tall as Marta.'],
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
            Kit::gap($stage, 'sentences.choose_gap.mas-rapido', 'Pablo es ___ rápido que Luis.', ['más', 'tan', 'tanto'], 'más', Kit::form('más', true), 'Faster than is más + adjective + que. Tan needs como after the adjective, and tanto goes with a verb or a noun.', 'choose', 'Pablo is faster than Luis.'),
            Kit::gap($stage, 'sentences.choose_gap.tan-barato', 'Aquí es tan ___ como allí.', ['barato', 'barata', 'baratos'], 'barato', Kit::word('barato', 'barato'), 'There is no noun here, only es, so the adjective stays masculine and singular: barato.', 'choose', 'It is as cheap here as there.'),
            Kit::gap($stage, 'sentences.choose_gap.pequena', 'Ana es más ___ que Marta.', ['pequeña', 'pequeño', 'pequeñas'], 'pequeña', Kit::word('pequeño', 'pequeña'), 'Ana is one woman, so the adjective is feminine and singular: pequeña. It agrees with Ana, not with Marta.', 'choose', 'Ana is smaller than Marta.'),
            Kit::gap($stage, 'sentences.choose_gap.mayor', 'Luis es ___ que Pablo.', ['mayor', 'mejor', 'peor'], 'mayor', Kit::form('mayor', true), 'For age, Spanish has the irregular mayor (older). Mejor means better and peor means worse.', 'choose', 'Luis is older than Pablo.'),
            Kit::gap($stage, 'sentences.choose_gap.como', 'Ana es tan lenta ___ Marta.', ['como', 'que'], 'como', Kit::form('como', true), 'After tan you need como: tan lenta como. Que belongs to más and menos.', 'choose', 'Ana is as slow as Marta.'),
            Kit::gap($stage, 'sentences.choose_gap.mas-caro', 'Allí es más ___ que aquí.', ['caro', 'barato', 'rápido'], 'caro', Kit::word('caro', 'caro'), 'More expensive is más caro. Más barato would mean cheaper, and más rápido would mean faster.', 'choose', 'It is more expensive there than here.'),

            Kit::typeGap($stage, 'sentences.type_gap.tan-alto', 'Pablo es ___ alto como Luis.', 'Pablo is as tall as Luis.', 'tan', Kit::form('tan'), 'Before an adjective, as ... as is tan ... como.'),
            Kit::typeGap($stage, 'sentences.type_gap.tanto-trabaja', 'Ana trabaja ___ como Pablo.', 'Ana works as much as Pablo.', 'tanto', Kit::form('tanto', true), 'After a verb, as much as is tanto como. Tan only goes before an adjective.'),
            Kit::typeGap($stage, 'sentences.type_gap.lenta', 'El autobús es más ___ que el tren.', 'The bus is slower than the train.', 'lento', Kit::word('lento', 'lento')),
            Kit::typeGap($stage, 'sentences.type_gap.grande', 'El piso de Ana es más ___ que mi piso.', 'Ana\'s flat is bigger than my flat.', 'grande', Kit::word('grande', 'grande')),
            Kit::typeGap($stage, 'sentences.type_gap.mejor', 'El tren es ___ que el autobús.', 'The train is better than the bus.', 'mejor', Kit::form('mejor', true), 'Better is the irregular mejor. Spanish never says más bueno.'),

            Kit::translate($stage, 'sentences.translate.lento-ana', 'Luis is slower than Ana.', ['Luis es más lento que Ana.'], [Kit::word('lento', 'lento'), Kit::form('más')]),
            Kit::translate($stage, 'sentences.translate.menos-comodo', 'It is less comfortable than the car.', ['Es menos cómodo que el coche.'], [Kit::word('cómodo', 'cómodo'), Kit::form('menos', true)]),
            Kit::translate($stage, 'sentences.translate.tan-rapida', 'Ana is as fast as Marta.', ['Ana es tan rápida como Marta.'], [Kit::word('rápido', 'rápida'), Kit::form('tan')]),

            Kit::build($stage, 'sentences.build.comodo-silla', 'It is more comfortable than the chair.', 'Es más cómodo que la silla.', ['menos'], [Kit::word('cómodo', 'cómodo'), Kit::form('que')]),
            Kit::build($stage, 'sentences.build.facil-leer', 'It is as easy as reading.', 'Es tan fácil como leer.', ['que'], [Kit::word('fácil', 'fácil'), Kit::form('tan')]),
            Kit::build($stage, 'sentences.build.dificil-hablar', 'It is harder than reading.', 'Es más difícil que leer.', ['tan'], [Kit::word('difícil', 'difícil'), Kit::form('más')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.mas-alto', 'Luis es más alto que Pablo.', ['Luis is taller than Pablo.', 'Pablo is taller than Luis.', 'Luis is as tall as Pablo.', 'Luis is shorter than Pablo.'], 'Luis is taller than Pablo.', [Kit::form('más')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.rapido-tren', 'Es más rápido que el tren.', ['It is faster than the train.', 'It is slower than the train.', 'It is as fast as the train.', 'The train is faster than it.'], 'It is faster than the train.', [Kit::word('rápido', 'rápido'), Kit::form('más')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.tan-caro', 'Aquí es tan caro como allí.', ['It is as expensive here as there.', 'It is more expensive here than there.', 'It is cheaper here than there.', 'It is as cheap here as there.'], 'It is as expensive here as there.', [Kit::word('caro', 'caro'), Kit::form('tan')]),
            Kit::listenType($stage, 'sentences.listen_type.menos-rapido', 'Pablo es menos rápido que Ana.', 'Pablo is less fast than Ana.', [Kit::word('rápido', 'rápido'), Kit::form('menos', true)]),
            Kit::listenType($stage, 'sentences.listen_type.pequeno-casa', 'Es más pequeño que mi casa.', 'It is smaller than my house.', [Kit::word('pequeño', 'pequeño'), Kit::form('más')]),
            Kit::listenType($stage, 'sentences.listen_type.dificil-leer', 'Es tan difícil como leer.', 'It is as difficult as reading.', [Kit::word('difícil', 'difícil'), Kit::form('tan')]),
            Kit::listenType($stage, 'sentences.listen_type.coche-nuevo', 'Mi coche es nuevo y grande.', 'My car is new and big.', [Kit::word('nuevo', 'nuevo'), Kit::word('grande', 'grande')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.rapido-yo', 'Pablo es más rápido que yo.', 'Pablo is faster than me.', [Kit::word('rápido', 'rápido'), Kit::form('más')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.barato-pan', 'Es tan barato como el pan.', 'It is as cheap as bread.', [Kit::word('barato', 'barato'), Kit::form('tan')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.nuevo-comodo', 'Mi coche es nuevo y cómodo.', 'My car is new and comfortable.', [Kit::word('nuevo', 'nuevo'), Kit::word('cómodo', 'cómodo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.menor', 'Marta es menor que Luis.', 'Marta is younger than Luis.', [Kit::form('menor', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.casa', '¿Es tu casa grande o pequeña?', 'Is your house big or small?', [['mi', 'es', 'casa'], ['grande', 'pequeña']], 'Mi casa es grande.', [Kit::word('grande', 'grande'), Kit::word('pequeño', 'pequeña')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cafe', '¿Es caro o barato el café?', 'Is the coffee expensive or cheap?', [['es', 'café', 'aquí'], ['caro', 'barato']], 'El café es barato.', [Kit::word('caro', 'caro'), Kit::word('barato', 'barato')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.leer', '¿Es fácil o difícil leer?', 'Is reading easy or difficult?', [['es', 'leer'], ['fácil', 'difícil']], 'Leer es fácil.', [Kit::word('fácil', 'fácil'), Kit::word('difícil', 'difícil')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.dos-pisos', 'Read the conversation about two flats.', [
                Kit::line('Ana', 'Pablo, ¿qué piso es mejor?'),
                Kit::line('Pablo', 'El piso de la ciudad es más grande y más nuevo.'),
                Kit::line('Ana', 'Sí, pero es más caro que el piso del pueblo.'),
                Kit::line('Pablo', 'El piso del pueblo es pequeño, pero es barato y cómodo.'),
                Kit::line('Ana', 'Muy bien, el piso del pueblo es mejor.'),
            ], [
                Kit::question('Which flat is bigger?', ['The one in the city', 'The one in the village', 'They are the same size'], 'The one in the city'),
                Kit::question('Which flat is cheaper?', ['The one in the village', 'The one in the city', 'They cost the same'], 'The one in the village'),
                Kit::question('Which flat does Ana choose?', ['The one in the village', 'The one in the city', 'Neither'], 'The one in the village'),
            ], [Kit::word('grande', 'grande'), Kit::word('nuevo', 'nuevo'), Kit::word('caro', 'caro'), Kit::word('pequeño', 'pequeño'), Kit::word('barato', 'barato'), Kit::word('cómodo', 'cómodo'), Kit::form('mejor')], 'read'),
            Kit::gap($stage, 'task.choose_gap.tren-autobus', 'El tren es más ___ que el autobús, pero más caro.', ['cómodo', 'fácil', 'pequeño'], 'cómodo', Kit::word('cómodo', 'cómodo'), 'Comfortable is cómodo, which fits a train. Fácil is easy and pequeño is small.', 'read', 'The train is more comfortable than the bus, but more expensive.'),
            Kit::gap($stage, 'task.choose_gap.vivir', 'Vivir en la ciudad es más ___ que vivir en el pueblo.', ['caro', 'cara', 'caros'], 'caro', Kit::word('caro', 'caro'), 'The subject is the verb vivir, so the adjective is masculine and singular: caro.', 'read', 'Living in the city is more expensive than living in the village.', ['vivir' => 'to live']),

            Kit::transform($stage, 'task.transform.tan-rapida', 'Say that Ana is as fast as Pablo.', 'Pablo es más rápido que Ana.', ['Ana es tan rápida como Pablo.'], [Kit::word('rápido', 'rápida'), Kit::form('tan', true)]),
            Kit::transform($stage, 'task.transform.coche-tren', 'Turn it around: the car is faster than the train.', 'El tren es más lento que el coche.', ['El coche es más rápido que el tren.'], [Kit::word('rápido', 'rápido'), Kit::form('más', true)]),
            Kit::transform($stage, 'task.transform.piso-ana', 'Say that your flat is bigger than the flat of Ana.', 'Mi piso es grande.', ['Mi piso es más grande que el piso de Ana.', 'Mi piso es más grande que el de Ana.'], [Kit::word('grande', 'grande'), Kit::form('que')]),
            Kit::writeGuided($stage, 'task.write_guided.tren-autobus', 'Say that the train is faster than the bus, but more expensive.', ['el tren', 'más rápido', 'que', 'pero', 'más caro'], 'El tren es más rápido que el autobús, pero es más caro.', [
                ['forms' => ['rápido'], 'term' => 'rápido'],
                ['forms' => ['caro'], 'term' => 'caro'],
                ['forms' => ['más'], 'term' => null],
            ], [Kit::word('rápido', 'rápido'), Kit::word('caro', 'caro'), Kit::form('más')]),
            Kit::writeGuided($stage, 'task.write_guided.piso-ana', 'Say that your flat is smaller than the flat of Ana, but as comfortable.', ['mi piso', 'más pequeño', 'pero', 'tan cómodo', 'como'], 'Mi piso es más pequeño, pero tan cómodo como el de Ana.', [
                ['forms' => ['pequeño'], 'term' => 'pequeño'],
                ['forms' => ['cómodo'], 'term' => 'cómodo'],
                ['forms' => ['tan'], 'term' => null],
            ], [Kit::word('pequeño', 'pequeño'), Kit::word('cómodo', 'cómodo'), Kit::form('tan')]),
            Kit::build($stage, 'task.build.coche-tren', 'The car is faster than the train and more expensive.', 'El coche es más rápido que el tren y más caro.', ['menos', 'tan'], [Kit::word('rápido', 'rápido'), Kit::word('caro', 'caro'), Kit::form('más')]),
            Kit::build($stage, 'task.build.casa-piso', 'My house is as big as my flat.', 'Mi casa es tan grande como mi piso.', ['que', 'más'], [Kit::word('grande', 'grande'), Kit::form('tan')]),
            Kit::build($stage, 'task.build.coche-nuevo', 'My new car is slower than the train.', 'Mi coche nuevo es más lento que el tren.', ['menos', 'tanto'], [Kit::word('nuevo', 'nuevo'), Kit::word('lento', 'lento'), Kit::form('que')]),
            Kit::translate($stage, 'task.translate.dificil-facil', 'It is harder than reading, but easier than working.', ['Es más difícil que leer, pero más fácil que trabajar.', 'Es más difícil que leer, pero es más fácil que trabajar.'], [Kit::word('difícil', 'difícil'), Kit::word('fácil', 'fácil'), Kit::form('que')]),
            Kit::translate($stage, 'task.translate.tren-barato', 'The train is cheaper than the car.', ['El tren es más barato que el coche.'], [Kit::word('barato', 'barato'), Kit::form('más')]),

            Kit::listenPassage($stage, 'task.listen_passage.tren-autobus', [
                Kit::line('Marta', 'Luis, ¿vamos en tren o en autobús?'),
                Kit::line('Luis', 'El tren es más rápido, pero es más caro.'),
                Kit::line('Marta', 'El autobús es más lento, pero es tan cómodo como el tren.'),
                Kit::line('Luis', 'Muy bien, vamos en autobús. Es más barato.'),
            ], [
                Kit::question('Which is faster?', ['The train', 'The bus', 'They are equally fast'], 'The train'),
                Kit::question('Which is cheaper?', ['The bus', 'The train', 'They cost the same'], 'The bus'),
                Kit::question('How do they go?', ['By bus', 'By train', 'By car'], 'By bus'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Do they decide to go by train?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('rápido', 'rápido'), Kit::word('caro', 'caro'), Kit::word('lento', 'lento'), Kit::word('cómodo', 'cómodo'), Kit::word('barato', 'barato')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.autobus', 'El autobús es más lento, pero es más barato.', 'The bus is slower, but it is cheaper.', [Kit::word('lento', 'lento'), Kit::word('barato', 'barato'), Kit::form('más')]),
            Kit::listenType($stage, 'task.listen_type.piso-nuevo', 'El piso nuevo es más grande que mi casa.', 'The new flat is bigger than my house.', [Kit::word('nuevo', 'nuevo'), Kit::word('grande', 'grande'), Kit::form('que')]),
            Kit::listenType($stage, 'task.listen_type.tanto-menor', 'Pablo trabaja tanto como Luis, pero Luis es mayor.', 'Pablo works as much as Luis, but Luis is older.', [Kit::form('tanto', true)]),

            Kit::speakAnswer($stage, 'task.speak_answer.tren-autobus', '¿Es el tren más rápido o más lento que el autobús?', 'Is the train faster or slower than the bus?', [['más', 'es'], ['rápido', 'lento']], 'El tren es más rápido que el autobús.', [Kit::word('rápido', 'rápido'), Kit::word('lento', 'lento'), Kit::form('más')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mayor-menor', '¿Eres mayor o menor que Ana?', 'Are you older or younger than Ana?', [['soy'], ['mayor', 'menor']], 'Soy mayor que Ana.', [Kit::form('mayor')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ciudad-pueblo', '¿Es la ciudad más cara que el pueblo?', 'Is the city more expensive than the village?', [['sí', 'no'], ['cara', 'más', 'ciudad', 'pueblo']], 'Sí, la ciudad es más cara.', [Kit::word('caro', 'cara'), Kit::form('más')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.leer-trabajar', '¿Es leer más fácil o más difícil que trabajar?', 'Is reading easier or harder than working?', [['más', 'es'], ['fácil', 'difícil']], 'Leer es más fácil que trabajar.', [Kit::word('fácil', 'fácil'), Kit::word('difícil', 'difícil')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.casa-comoda', 'Mi casa es tan cómoda como tu piso.', 'My house is as comfortable as your flat.', [Kit::word('cómodo', 'cómoda'), Kit::form('tan')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.coche-caro', 'El coche es más caro y más rápido que el tren.', 'The car is more expensive and faster than the train.', [Kit::word('caro', 'caro'), Kit::word('rápido', 'rápido'), Kit::form('más')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.lento-barato', 'It is slower, but cheaper than the train.', ['Es más lento, pero más barato que el tren.'], [Kit::word('lento', 'lento'), Kit::word('barato', 'barato'), Kit::form('más', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.nuevo-grande', 'It is new and comfortable, but it is not as big.', ['Es nuevo y cómodo, pero no es tan grande.'], [Kit::word('nuevo', 'nuevo'), Kit::word('cómodo', 'cómodo'), Kit::word('grande', 'grande'), Kit::form('tan', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.piso-mejor', 'My flat is small and cheap. Your house is better.', ['Mi piso es pequeño y barato. Tu casa es mejor.'], [Kit::word('pequeño', 'pequeño'), Kit::word('barato', 'barato'), Kit::form('mejor')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.leer-escribir', 'Reading is easy and fast. Working is difficult and slow.', ['Leer es fácil y rápido. Trabajar es difícil y lento.'], [Kit::word('fácil', 'fácil'), Kit::word('rápido', 'rápido'), Kit::word('difícil', 'difícil'), Kit::word('lento', 'lento')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.tanto-trabaja', 'Luis trabaja ___ como Ana.', 'Luis works as much as Ana.', 'tanto', Kit::form('tanto'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.mas-facil', 'Leer es ___ que trabajar.', 'Reading is easier than working.', 'más fácil', Kit::word('fácil', 'fácil'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.caro-dificil', 'Es caro y difícil, pero es rápido.', 'It is expensive and difficult, but it is fast.', [Kit::word('caro', 'caro'), Kit::word('difícil', 'difícil'), Kit::word('rápido', 'rápido')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-menor', 'Marta es menor y su piso es pequeño y cómodo.', 'Marta is younger and her flat is small and comfortable.', [Kit::word('pequeño', 'pequeño'), Kit::word('cómodo', 'cómodo'), Kit::form('menor')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.tren-menos', 'El piso nuevo es grande y menos caro.', 'The new flat is big and less expensive.', [Kit::word('nuevo', 'nuevo'), Kit::word('grande', 'grande'), Kit::word('caro', 'caro'), Kit::form('menos', true)], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.coche-autobus', [
                Kit::line('Marta', 'Pablo, ¿vamos en coche o en autobús?'),
                Kit::line('Pablo', 'En coche. Es más rápido y más cómodo.'),
                Kit::line('Marta', 'Pero el autobús es más barato.'),
                Kit::line('Pablo', 'Sí, pero es más lento.'),
                Kit::line('Marta', 'Muy bien, vamos en coche.'),
            ], [
                Kit::question('What does Pablo want to take?', ['The car', 'The train', 'The bus'], 'The car'),
                Kit::question('What is better about the bus, says Marta?', ['It is cheaper', 'It is faster', 'It is more comfortable'], 'It is cheaper'),
                Kit::question('What do they decide?', ['To go by car', 'To go by bus', 'To stay at home'], 'To go by car'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Pablo', 'Nobody'], 'Marta'),
                Kit::question('Do they go by bus?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('rápido', 'rápido'), Kit::word('cómodo', 'cómodo'), Kit::word('barato', 'barato'), Kit::word('lento', 'lento')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.cafes', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿vamos al café nuevo?'),
                Kit::line('Pablo', 'Es más grande, pero es más caro.'),
                Kit::line('Ana', 'El café del pueblo es pequeño, pero es barato.'),
                Kit::line('Pablo', 'Muy bien, vamos al café del pueblo.'),
            ], [
                Kit::question('Which café is bigger?', ['The new café', 'The café in the village', 'They are the same'], 'The new café'),
                Kit::question('Where do they go?', ['To the café in the village', 'To the new café', 'Home'], 'To the café in the village'),
            ], [Kit::word('nuevo', 'nuevo'), Kit::word('grande', 'grande'), Kit::word('caro', 'caro'), Kit::word('pequeño', 'pequeño'), Kit::word('barato', 'barato')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.tren-autobus', '¿Es el tren más caro que el autobús?', 'Is the train more expensive than the bus?', [['sí', 'no'], ['caro', 'más', 'barato']], 'Sí, es más caro.', [Kit::word('caro', 'caro'), Kit::form('más')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.piso', '¿Es tu piso grande o pequeño?', 'Is your flat big or small?', [['es', 'piso', 'mi'], ['grande', 'pequeño']], 'Mi piso es grande.', [Kit::word('grande', 'grande'), Kit::word('pequeño', 'pequeño')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.trabajar', '¿Es difícil o fácil trabajar?', 'Is working difficult or easy?', [['es', 'trabajar'], ['fácil', 'difícil']], 'Trabajar es difícil.', [Kit::word('fácil', 'fácil'), Kit::word('difícil', 'difícil')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.menos-caro', 'It is less expensive and more comfortable than the car.', ['Es menos caro y más cómodo que el coche.'], [Kit::word('caro', 'caro'), Kit::word('cómodo', 'cómodo'), Kit::form('menos', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tan-pequeno', 'My new flat is as small as your house.', ['Mi piso nuevo es tan pequeño como tu casa.'], [Kit::word('nuevo', 'nuevo'), Kit::word('pequeño', 'pequeño'), Kit::form('tan')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.coche-rapido', 'My car is cheap, big and fast.', ['Mi coche es barato, grande y rápido.'], [Kit::word('barato', 'barato'), Kit::word('grande', 'grande'), Kit::word('rápido', 'rápido')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.mas-dificil', 'Reading is easy, but working is more difficult and slow.', ['Leer es fácil, pero trabajar es más difícil y lento.'], [Kit::word('fácil', 'fácil'), Kit::word('difícil', 'difícil'), Kit::word('lento', 'lento'), Kit::form('más', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.tanto-trabaja', 'Marta trabaja ___ como Ana.', 'Marta works as much as Ana.', 'tanto', Kit::form('tanto'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.mas-dificil', 'Trabajar es ___ que leer.', 'Working is harder than reading.', 'más difícil', Kit::word('difícil', 'difícil'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tren-facil', 'El tren es rápido, cómodo y fácil.', 'The train is fast, comfortable and easy.', [Kit::word('rápido', 'rápido'), Kit::word('cómodo', 'cómodo'), Kit::word('fácil', 'fácil')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pablo-mayor', 'Pablo es mayor. Su coche es nuevo, grande, lento y caro.', 'Pablo is older. His car is new, big, slow and expensive.', [Kit::word('nuevo', 'nuevo'), Kit::word('grande', 'grande'), Kit::word('lento', 'lento'), Kit::word('caro', 'caro'), Kit::form('mayor')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.piso-peor', 'Mi piso es peor que tu casa. Es pequeño, pero barato.', 'My flat is worse than your house. It is small, but cheap.', [Kit::word('pequeño', 'pequeño'), Kit::word('barato', 'barato'), Kit::form('peor')], 'dictation', $set),
        ];
    }
}
