<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class MyHome implements UnitContent
{
    private const HAY_NOTE = 'Hay (there is) sounds like ay (a cry of surprise or pain). Here it means there is or there are.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'my-home';
    }

    public function words(): array
    {
        return [
            new WordData('el baño', cue: 'bathroom', forms: ['baños'], note: 'El baño is the bathroom. It also means bath or swim, so el baño can be a bath you take.'),
            new WordData('el pasillo', cue: 'hallway', forms: ['pasillos']),
            new WordData('la terraza', cue: 'terrace', forms: ['terrazas']),
            new WordData('el sofá', cue: 'sofa', forms: ['sofás']),
            new WordData('el armario', cue: 'wardrobe (cupboard)', forms: ['armarios'], note: 'El armario is a wardrobe in a bedroom and a cupboard in a kitchen.'),
            new WordData('la estantería', cue: 'bookcase (shelves)', forms: ['estanterías']),
            new WordData('la lámpara', cue: 'lamp', forms: ['lámparas']),
            new WordData('la alfombra', cue: 'rug (carpet)', forms: ['alfombras']),
            new WordData('la nevera', cue: 'fridge', accepted: ['el frigorífico'], forms: ['neveras'], note: 'La nevera and el frigorífico are both a fridge in Spain. La nevera is the everyday word. El frigorífico is a bit more formal.'),
            new WordData('la puerta', cue: 'door', forms: ['puertas']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Hay una lámpara en el salón.', 'english' => 'There is a lamp in the living room.'],
            ['text' => 'La lámpara está encima de la mesa.', 'english' => 'The lamp is on top of the table.'],
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
            Kit::gap($stage, 'sentences.choose_gap.hay-sofa', 'Hay un ___ en el salón.', ['sofá', 'alfombra', 'lámpara'], 'sofá', Kit::word('el sofá', 'sofá'), 'Un goes with a masculine word, and sofá is masculine. Alfombra and lámpara take una.', 'choose', 'There is a sofa in the living room.'),
            Kit::gap($stage, 'sentences.choose_gap.lampara-esta', 'La lámpara ___ encima de la mesa.', ['está', 'hay'], 'está', Kit::form('está', true), 'La lámpara is a lamp you both know, so we say está. Hay is for introducing something new: hay una lámpara.', 'choose', 'The lamp is on top of the table.'),
            Kit::gap($stage, 'sentences.choose_gap.mesa-entre', 'La mesa está ___ dos sillas.', ['entre', 'encima', 'debajo'], 'entre', Kit::form('entre'), 'Entre means between and works with two things. Encima and debajo need de after them and mean on top of and under.', 'choose', 'The table is between two chairs.'),
            Kit::gap($stage, 'sentences.choose_gap.armario-lado', 'El armario está ___ la ventana.', ['al lado de', 'entre', 'debajo de'], 'al lado de', Kit::form('lado'), 'Al lado de means next to. Entre needs two things, and debajo de means under.', 'choose', 'The wardrobe is next to the window.'),
            Kit::gap($stage, 'sentences.choose_gap.terraza', 'Hay una mesa en la ___.', ['terraza', 'nevera', 'puerta'], 'terraza', Kit::word('la terraza', 'terraza'), 'La terraza is the terrace, an outdoor place where a table can stand. A table does not stand in a fridge or in a door.', 'choose', 'There is a table on the terrace.'),
            Kit::gap($stage, 'sentences.choose_gap.nevera', 'La leche está en la ___.', ['nevera', 'terraza', 'alfombra'], 'nevera', Kit::word('la nevera', 'nevera'), 'The milk is kept in la nevera, the fridge. A terraza is a terrace and an alfombra is a rug.', 'choose', 'The milk is in the fridge.'),

            Kit::typeGap($stage, 'sentences.type_gap.bano', 'Mi piso tiene un ___.', 'My flat has a bathroom.', 'baño', Kit::word('el baño', 'baño')),
            Kit::typeGap($stage, 'sentences.type_gap.estanteria', 'Hay un libro en la ___.', 'There is a book in the bookcase.', 'estantería', Kit::word('la estantería', 'estantería')),
            Kit::typeGap($stage, 'sentences.type_gap.puerta', 'La ___ está al lado del baño.', 'The door is next to the bathroom.', 'puerta', Kit::word('la puerta', 'puerta')),
            Kit::typeGap($stage, 'sentences.type_gap.hay-armario', '___ un armario en el dormitorio.', 'There is a wardrobe in the bedroom.', 'Hay', Kit::form('hay', true), 'Un armario is something new, so we say hay: there is a wardrobe. Hay never changes.'),
            Kit::typeGap($stage, 'sentences.type_gap.bolso-debajo', 'El bolso está ___ de la silla.', 'The bag is under the chair.', 'debajo', Kit::form('debajo'), 'Debajo de means under. It always needs de before the noun.'),

            Kit::translate($stage, 'sentences.translate.sofa-ventana', 'The sofa is in front of the window.', ['El sofá está delante de la ventana.', 'Delante de la ventana está el sofá.'], [Kit::word('el sofá', 'sofá'), Kit::form('delante')]),
            Kit::translate($stage, 'sentences.translate.lampara-sofa', 'The lamp is behind the sofa.', ['La lámpara está detrás del sofá.'], [Kit::word('la lámpara', 'lámpara'), Kit::form('detrás')]),
            Kit::translate($stage, 'sentences.translate.alfombra-mesa', 'There is a rug under the table.', ['Hay una alfombra debajo de la mesa.', 'Debajo de la mesa hay una alfombra.'], [Kit::word('la alfombra', 'alfombra'), Kit::form('hay', true)]),

            Kit::build($stage, 'sentences.build.armario-bano', 'The wardrobe is next to the bathroom.', 'El armario está al lado del baño.', ['encima'], [Kit::word('el armario', 'armario'), Kit::word('el baño', 'baño'), Kit::form('lado')]),
            Kit::build($stage, 'sentences.build.nevera-cocina', 'The fridge is in the kitchen.', 'La nevera está en la cocina.', ['hay'], [Kit::word('la nevera', 'nevera'), Kit::form('está', true)]),
            Kit::build($stage, 'sentences.build.puerta-pasillo', 'There is a door in the hallway. (start with the verb)', 'Hay una puerta en el pasillo.', ['está'], [Kit::word('la puerta', 'puerta'), Kit::word('el pasillo', 'pasillo'), Kit::form('hay', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.lampara-mesa', 'La lámpara está encima de la mesa.', ['The lamp is on the table.', 'The lamp is under the table.', 'The lamp is next to the table.', 'The table is on the lamp.'], 'The lamp is on the table.', [Kit::word('la lámpara', 'lámpara'), Kit::form('encima')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.alfombra-pasillo', 'Hay una alfombra en el pasillo.', ['There is a rug in the hallway.', 'There is a rug in the bathroom.', 'There is a lamp in the hallway.', 'The rug is in the living room.'], 'There is a rug in the hallway.', [Kit::word('la alfombra', 'alfombra'), Kit::word('el pasillo', 'pasillo'), Kit::form('hay', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.nevera-puerta', 'La nevera está delante de la puerta.', ['The fridge is in front of the door.', 'The fridge is behind the door.', 'The fridge is next to the door.', 'The door is in front of the fridge.'], 'The fridge is in front of the door.', [Kit::word('la nevera', 'nevera'), Kit::word('la puerta', 'puerta'), Kit::form('delante')]),
            Kit::listenType($stage, 'sentences.listen_type.sofa-terraza', 'Hay un sofá en la terraza.', 'There is a sofa on the terrace.', [Kit::word('el sofá', 'sofá'), Kit::word('la terraza', 'terraza'), Kit::form('hay', true)], homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.estanteria-puerta', 'La estantería está detrás de la puerta.', 'The bookcase is behind the door.', [Kit::word('la estantería', 'estantería'), Kit::word('la puerta', 'puerta'), Kit::form('detrás')]),
            Kit::listenType($stage, 'sentences.listen_type.armario-ventanas', 'El armario está entre las ventanas.', 'The wardrobe is between the windows.', [Kit::word('el armario', 'armario'), Kit::form('entre')]),
            Kit::listenType($stage, 'sentences.listen_type.bano-pasillo', 'El baño está al lado del pasillo.', 'The bathroom is next to the hallway.', [Kit::word('el baño', 'baño'), Kit::word('el pasillo', 'pasillo'), Kit::form('lado')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.terraza', 'Mi piso tiene una terraza.', 'My flat has a terrace.', [Kit::word('la terraza', 'terraza')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.sofa-mesa', 'El sofá está delante de la mesa.', 'The sofa is in front of the table.', [Kit::word('el sofá', 'sofá'), Kit::form('delante')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.lampara', 'Hay una lámpara encima de la mesa.', 'There is a lamp on top of the table.', [Kit::word('la lámpara', 'lámpara'), Kit::form('encima')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.armario', 'El armario está en el dormitorio.', 'The wardrobe is in the bedroom.', [Kit::word('el armario', 'armario'), Kit::form('está', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.nevera', '¿Dónde está la nevera?', 'Where is the fridge?', [['nevera', 'está'], ['cocina', 'pasillo', 'salón', 'terraza']], 'La nevera está en la cocina.', [Kit::word('la nevera', 'nevera'), Kit::form('está', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.lampara', '¿Hay una lámpara en el salón?', 'Is there a lamp in the living room?', [['sí', 'no', 'hay'], ['lámpara', 'salón']], 'Sí, hay una lámpara en el salón.', [Kit::word('la lámpara', 'lámpara'), Kit::form('hay', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.alfombra', '¿Dónde está la alfombra?', 'Where is the rug?', [['alfombra', 'está'], ['debajo', 'encima', 'delante', 'detrás', 'entre', 'lado', 'mesa', 'sofá', 'salón']], 'La alfombra está debajo de la mesa.', [Kit::word('la alfombra', 'alfombra'), Kit::form('debajo')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.piso-ana', 'Read the conversation about Ana\'s flat.', [
                Kit::line('Pablo', 'Hola, Ana. ¿Tienes una terraza?'),
                Kit::line('Ana', 'Sí, y tengo un sofá en el salón.'),
                Kit::line('Pablo', '¿Dónde está la nevera?'),
                Kit::line('Ana', 'La nevera está en la cocina, al lado de la puerta.'),
                Kit::line('Pablo', '¿Hay una lámpara en tu salón?'),
                Kit::line('Ana', 'Sí, hay una lámpara encima de la estantería.'),
            ], [
                Kit::question('What does Ana have in the living room?', ['A sofa', 'A fridge', 'A rug'], 'A sofa'),
                Kit::question('Where is the fridge?', ['In the kitchen, next to the door', 'In the hallway', 'On the terrace'], 'In the kitchen, next to the door'),
                Kit::question('Where is the lamp?', ['On top of the bookcase', 'Under the table', 'Behind the sofa'], 'On top of the bookcase'),
            ], [Kit::word('la terraza', 'terraza'), Kit::word('el sofá', 'sofá'), Kit::word('la nevera', 'nevera'), Kit::word('la puerta', 'puerta'), Kit::word('la lámpara', 'lámpara'), Kit::word('la estantería', 'estantería'), Kit::form('lado')], 'read'),
            Kit::gap($stage, 'task.choose_gap.hay-estanteria', '¿___ una estantería en el pasillo?', ['Hay', 'Está'], 'Hay', Kit::form('hay', true), 'Una estantería is something new you ask about, so we say hay: is there a bookcase? Está is for something you both know.', 'read', 'Is there a bookcase in the hallway?'),
            Kit::gap($stage, 'task.choose_gap.alfombra-esta', 'Mi alfombra ___ debajo del sofá.', ['está', 'hay'], 'está', Kit::form('está', true), 'Mi alfombra is a known rug, so we say está. Hay does not go with mi, el or la.', 'read', 'My rug is under the sofa.'),

            Kit::transform($stage, 'task.transform.lampara-esta', 'Say where it is: use está, not hay.', 'Hay una lámpara encima del armario.', ['La lámpara está encima del armario.'], [Kit::word('la lámpara', 'lámpara'), Kit::word('el armario', 'armario'), Kit::form('está', true)]),
            Kit::transform($stage, 'task.transform.debajo', 'Say the opposite: under, not on top of.', 'El bolso está encima de la silla.', ['El bolso está debajo de la silla.'], [Kit::form('debajo')]),
            Kit::transform($stage, 'task.transform.delante', 'Say it is in front, not behind.', 'El sofá está detrás de la estantería.', ['El sofá está delante de la estantería.'], [Kit::word('el sofá', 'sofá'), Kit::word('la estantería', 'estantería'), Kit::form('delante')]),
            Kit::writeGuided($stage, 'task.write_guided.salon', 'Describe your living room: say where the sofa and the lamp are.', ['el sofá', 'la lámpara', 'está', 'delante de', 'al lado de'], 'El sofá está delante de la ventana. La lámpara está al lado del sofá.', [
                ['forms' => ['sofá'], 'term' => 'el sofá'],
                ['forms' => ['lámpara'], 'term' => 'la lámpara'],
                ['forms' => ['delante', 'lado', 'encima', 'debajo', 'detrás', 'entre'], 'term' => null],
            ], [Kit::word('el sofá', 'sofá'), Kit::word('la lámpara', 'lámpara'), Kit::form('lado')]),
            Kit::writeGuided($stage, 'task.write_guided.cocina', 'Describe your kitchen: say what is in the fridge and that the fridge is next to the door.', ['la nevera', 'hay', 'la leche', 'la puerta', 'al lado de'], 'Hay leche y fruta en la nevera. La nevera está al lado de la puerta.', [
                ['forms' => ['hay'], 'term' => null],
                ['forms' => ['nevera'], 'term' => 'la nevera'],
                ['forms' => ['puerta'], 'term' => 'la puerta'],
            ], [Kit::word('la nevera', 'nevera'), Kit::word('la puerta', 'puerta'), Kit::form('hay', true)]),
            Kit::build($stage, 'task.build.armario-puerta', 'The wardrobe is behind the bedroom door.', 'El armario está detrás de la puerta del dormitorio.', ['delante', 'hay'], [Kit::word('el armario', 'armario'), Kit::word('la puerta', 'puerta'), Kit::form('detrás')]),
            Kit::build($stage, 'task.build.alfombra-entre', 'There is a rug between the sofa and the table. (start with the verb)', 'Hay una alfombra entre el sofá y la mesa.', ['debajo', 'está'], [Kit::word('la alfombra', 'alfombra'), Kit::word('el sofá', 'sofá'), Kit::form('entre')]),
            Kit::build($stage, 'task.build.lampara-estanteria', 'There is a lamp and a bookcase in the hallway. (start with the verb)', 'Hay una lámpara y una estantería en el pasillo.', ['está', 'encima'], [Kit::word('la lámpara', 'lámpara'), Kit::word('la estantería', 'estantería'), Kit::word('el pasillo', 'pasillo'), Kit::form('hay', true)]),
            Kit::translate($stage, 'task.translate.terraza-salon', 'There is a terrace next to the living room.', ['Hay una terraza al lado del salón.', 'Al lado del salón hay una terraza.'], [Kit::word('la terraza', 'terraza'), Kit::form('lado')]),
            Kit::translate($stage, 'task.translate.bano-entre', 'The bathroom is between the bedroom and the hallway.', ['El baño está entre el dormitorio y el pasillo.', 'El baño está entre el pasillo y el dormitorio.'], [Kit::word('el baño', 'baño'), Kit::word('el pasillo', 'pasillo'), Kit::form('entre')]),

            Kit::listenPassage($stage, 'task.listen_passage.piso-luis', [
                Kit::line('Marta', '¿Dónde está tu sofá, Luis?'),
                Kit::line('Luis', 'Mi sofá está en el salón, delante de la estantería.'),
                Kit::line('Marta', '¿Y la alfombra?'),
                Kit::line('Luis', 'La alfombra está delante del sofá.'),
                Kit::line('Marta', '¿Hay un armario en el pasillo?'),
                Kit::line('Luis', 'No, el armario está en el dormitorio, al lado de la puerta.'),
            ], [
                Kit::question('Where is Luis\'s sofa?', ['In the living room, in front of the bookcase', 'In the bedroom', 'On the terrace'], 'In the living room, in front of the bookcase'),
                Kit::question('Where is the rug?', ['In front of the sofa', 'On the sofa', 'In the hallway'], 'In front of the sofa'),
                Kit::question('Where is the wardrobe?', ['In the bedroom', 'In the hallway', 'In the kitchen'], 'In the bedroom'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is the wardrobe in the hallway?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Does Luis have a rug?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('el sofá', 'sofá'), Kit::word('la estantería', 'estantería'), Kit::word('la alfombra', 'alfombra'), Kit::word('el armario', 'armario'), Kit::word('la puerta', 'puerta'), Kit::form('delante')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.lampara-alfombra', 'Hay una lámpara y una alfombra en el pasillo.', 'There is a lamp and a rug in the hallway.', [Kit::word('la lámpara', 'lámpara'), Kit::word('la alfombra', 'alfombra'), Kit::word('el pasillo', 'pasillo'), Kit::form('hay', true)], 'listen', homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'task.listen_type.nevera-entre', 'La nevera está entre el armario y la puerta.', 'The fridge is between the wardrobe and the door.', [Kit::word('la nevera', 'nevera'), Kit::word('el armario', 'armario'), Kit::word('la puerta', 'puerta'), Kit::form('entre')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.bano-terraza', 'Mi baño está al lado de la terraza.', 'My bathroom is next to the terrace.', [Kit::word('el baño', 'baño'), Kit::word('la terraza', 'terraza'), Kit::form('lado')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.salon', '¿Qué hay en tu salón?', 'What is there in your living room?', [['hay', 'tengo'], ['sofá', 'lámpara', 'alfombra', 'estantería', 'mesa']], 'Hay un sofá y una lámpara en mi salón.', [Kit::word('el sofá', 'sofá'), Kit::word('la lámpara', 'lámpara'), Kit::form('hay', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.bano', '¿Dónde está el baño?', 'Where is the bathroom?', [['baño', 'está'], ['pasillo', 'cocina', 'dormitorio', 'lado', 'entre', 'detrás', 'delante']], 'El baño está al lado del dormitorio.', [Kit::word('el baño', 'baño'), Kit::form('lado')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.nevera', '¿Qué hay en la nevera?', 'What is there in the fridge?', [['hay'], ['leche', 'fruta', 'queso', 'pescado', 'carne', 'agua', 'vino', 'pan']], 'Hay queso y pan en la nevera.', [Kit::word('la nevera', 'nevera'), Kit::form('hay', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.puerta', '¿Dónde está la puerta?', 'Where is the door?', [['puerta', 'está'], ['cocina', 'pasillo', 'salón', 'entre', 'lado', 'delante', 'detrás']], 'La puerta está entre la cocina y el pasillo.', [Kit::word('la puerta', 'puerta'), Kit::form('entre')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.armario-puerta', 'Mi armario está detrás de la puerta.', 'My wardrobe is behind the door.', [Kit::word('el armario', 'armario'), Kit::word('la puerta', 'puerta'), Kit::form('detrás')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.estanteria', 'Hay una estantería al lado de la ventana.', 'There is a bookcase next to the window.', [Kit::word('la estantería', 'estantería'), Kit::form('lado')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.nevera-pasillo', 'There is a fridge in the hallway.', ['Hay una nevera en el pasillo.', 'En el pasillo hay una nevera.'], [Kit::word('la nevera', 'nevera'), Kit::word('el pasillo', 'pasillo'), Kit::form('hay', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.armario-puerta', 'The wardrobe is next to the bathroom door.', ['El armario está al lado de la puerta del baño.'], [Kit::word('el armario', 'armario'), Kit::word('la puerta', 'puerta'), Kit::word('el baño', 'baño'), Kit::form('lado')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.lampara-estanteria', 'The lamp is on top of the bookcase.', ['La lámpara está encima de la estantería.'], [Kit::word('la lámpara', 'lámpara'), Kit::word('la estantería', 'estantería'), Kit::form('encima')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.alfombra-sofa', 'The rug is under the sofa.', ['La alfombra está debajo del sofá.'], [Kit::word('la alfombra', 'alfombra'), Kit::word('el sofá', 'sofá')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.estanteria-sofa', 'La estantería está ___ del sofá.', 'The bookcase is behind the sofa.', 'detrás', Kit::form('detrás'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.terraza-salon', 'La ___ está al lado del salón.', 'The terrace is next to the living room.', 'terraza', Kit::word('la terraza', 'terraza'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.terraza-delante', 'Hay una terraza delante del salón.', 'There is a terrace in front of the living room.', [Kit::word('la terraza', 'terraza'), Kit::form('hay', true)], 'dictation', $set, homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.sofa-entre', 'El sofá está entre la mesa y la ventana.', 'The sofa is between the table and the window.', [Kit::word('el sofá', 'sofá')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.puerta-bano', 'La puerta del baño está en el pasillo.', 'The bathroom door is in the hallway.', [Kit::word('la puerta', 'puerta'), Kit::word('el baño', 'baño'), Kit::word('el pasillo', 'pasillo'), Kit::form('está', true)], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.piso-ana', [
                Kit::line('Pablo', 'Ana, ¿dónde está el baño en tu piso?'),
                Kit::line('Ana', 'El baño está al lado del dormitorio.'),
                Kit::line('Pablo', '¿Y la nevera?'),
                Kit::line('Ana', 'La nevera está en la cocina, delante de la ventana.'),
                Kit::line('Pablo', '¿Hay una terraza?'),
                Kit::line('Ana', 'Sí, hay una terraza con una mesa y dos sillas.'),
            ], [
                Kit::question('Where is the bathroom?', ['Next to the bedroom', 'Next to the kitchen', 'In the hallway'], 'Next to the bedroom'),
                Kit::question('Where is the fridge?', ['In the kitchen, in front of the window', 'In the hallway', 'On the terrace'], 'In the kitchen, in front of the window'),
                Kit::question('What is on the terrace?', ['A table and two chairs', 'A sofa and a lamp', 'A rug'], 'A table and two chairs'),
            ], [
                Kit::question('Who asks the questions?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Is the fridge in the kitchen?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Does the flat have a terrace?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('el baño', 'baño'), Kit::word('la nevera', 'nevera'), Kit::word('la terraza', 'terraza'), Kit::form('lado')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.dormitorio-marta', 'Read the conversation.', [
                Kit::line('Marta', 'Mi dormitorio tiene una cama y un armario.'),
                Kit::line('Luis', '¿Dónde está la lámpara?'),
                Kit::line('Marta', 'La lámpara está al lado de la cama.'),
                Kit::line('Luis', '¿Hay una alfombra?'),
                Kit::line('Marta', 'Sí, hay una alfombra debajo de la cama.'),
            ], [
                Kit::question('Which two things does Marta say her bedroom has?', ['A bed and a wardrobe', 'A sofa and a rug', 'A fridge'], 'A bed and a wardrobe'),
                Kit::question('Where is the lamp?', ['Next to the bed', 'On the bookcase', 'Behind the door'], 'Next to the bed'),
                Kit::question('Where is the rug?', ['Under the bed', 'Under the sofa', 'In the hallway'], 'Under the bed'),
            ], [Kit::word('el armario', 'armario'), Kit::word('la lámpara', 'lámpara'), Kit::word('la alfombra', 'alfombra'), Kit::form('debajo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cocina', '¿Qué hay en tu cocina?', 'What is there in your kitchen?', [['hay', 'tengo'], ['nevera', 'mesa', 'silla']], 'Hay una nevera y una mesa.', [Kit::word('la nevera', 'nevera')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sofa', '¿Dónde está el sofá?', 'Where is the sofa?', [['sofá', 'está'], ['salón', 'terraza', 'delante', 'detrás', 'lado', 'entre', 'encima', 'debajo']], 'El sofá está delante de la ventana.', [Kit::word('el sofá', 'sofá')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.armario', '¿Hay un armario en tu dormitorio?', 'Is there a wardrobe in your bedroom?', [['sí', 'no', 'hay'], ['armario', 'dormitorio']], 'Sí, hay un armario en mi dormitorio.', [Kit::word('el armario', 'armario')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.terraza-dormitorio', 'The terrace is in front of the bedroom.', ['La terraza está delante del dormitorio.', 'Delante del dormitorio está la terraza.'], [Kit::word('la terraza', 'terraza'), Kit::form('delante')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.estanteria-sofa', 'There is a bookcase and a sofa in the hallway.', ['Hay una estantería y un sofá en el pasillo.', 'En el pasillo hay una estantería y un sofá.'], [Kit::word('la estantería', 'estantería'), Kit::word('el sofá', 'sofá'), Kit::word('el pasillo', 'pasillo'), Kit::form('hay', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.lampara-entre', 'The lamp is between the sofa and the wardrobe.', ['La lámpara está entre el sofá y el armario.', 'La lámpara está entre el armario y el sofá.'], [Kit::word('la lámpara', 'lámpara'), Kit::word('el sofá', 'sofá'), Kit::word('el armario', 'armario'), Kit::form('entre')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.puerta-armario', 'The door is next to the wardrobe.', ['La puerta está al lado del armario.'], [Kit::word('la puerta', 'puerta'), Kit::word('el armario', 'armario')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estanteria-esta', 'La estantería ___ al lado de la ventana.', 'The bookcase is next to the window.', 'está', Kit::form('está', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.lampara-bano', 'Hay una lámpara en el ___.', 'There is a lamp in the bathroom.', 'baño', Kit::word('el baño', 'baño'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.nevera-puerta', 'La nevera está detrás de la puerta.', 'The fridge is behind the door.', [Kit::word('la nevera', 'nevera'), Kit::word('la puerta', 'puerta'), Kit::form('detrás')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.alfombra-nevera', 'Hay una alfombra delante de la nevera.', 'There is a rug in front of the fridge.', [Kit::word('la alfombra', 'alfombra'), Kit::word('la nevera', 'nevera'), Kit::form('hay', true)], 'dictation', $set, homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.bano-armario', 'Mi baño tiene una ventana y un armario.', 'My bathroom has a window and a wardrobe.', [Kit::word('el baño', 'baño'), Kit::word('el armario', 'armario')], 'dictation', $set),
        ];
    }
}
