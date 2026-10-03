<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\TargetSpec;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class CheckingIntoAHotel implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [
            new WordData('el hotel', cue: 'hotel'),
            new WordData('la habitación', cue: 'room (in a hotel)'),
            new WordData('la reserva', cue: 'reservation (booking)'),
            new WordData('la llave', cue: 'key (for your room)'),
            new WordData('el recepcionista', cue: 'receptionist (at the hotel desk)', commonGender: true),
            new WordData('disponible', cue: 'available'),
            new WordData('la noche', cue: 'night'),
            new WordData('el baño', cue: 'bathroom', accepted: ['el cuarto de baño', 'el aseo']),
            new WordData('incluido', cue: 'included (masculine)', forms: ['incluida']),
            new WordData('el desayuno', cue: 'breakfast'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El hotel está cerca.', 'english' => 'The hotel is near.'],
            ['text' => 'La habitación está lista.', 'english' => 'The room is ready.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: DLE (recepcionista m. y f.), Wikcionario. Fixed: accepted el aseo for bathroom. el recepcionista and la recepcionista both accepted, spelling confirmed. Open questions answered and removed.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            self::gap($stage, 'sentences.choose_gap.bano-aqui', 'El baño ___ aquí.', ['está', 'es', 'hay'], 'está', self::form('está'), 'Where something is takes estar.', 'choose'),
            self::gap($stage, 'sentences.choose_gap.hay-habitacion', '___ una habitación disponible.', ['Hay', 'Está', 'Es'], 'Hay', self::form('Hay', true), 'Hay says that something exists. Estar says where a thing you already know is.', 'choose', 'There is a room available.'),
            self::gap($stage, 'sentences.choose_gap.llaves', 'Las llaves ___ en la habitación.', ['están', 'está', 'son'], 'están', self::form('están'), 'More than one thing takes están.', 'choose'),
            self::gap($stage, 'sentences.choose_gap.quien', '¿Quién ___ el recepcionista?', ['está', 'es', 'hay'], 'es', self::form('es', true), 'Who someone is takes ser.', 'choose'),
            self::gap($stage, 'sentences.choose_gap.desayuno-incluido', 'El desayuno ___ incluido.', ['está', 'es', 'hay'], 'está', self::form('está'), 'Included is a state that can change, so it takes estar.', 'choose'),
            self::gap($stage, 'sentences.choose_gap.donde-bano', '¿Dónde ___ el baño?', ['está', 'es', 'hay'], 'está', self::form('está'), 'Asking where something is takes estar.', 'choose'),

            self::typeGap($stage, 'sentences.type_gap.llave', 'La llave ___ en la habitación.', 'The key is in the room.', 'está', self::form('está'), 'Where something is takes estar.'),
            self::typeGap($stage, 'sentences.type_gap.estamos', '___ en el hotel.', 'We are in the hotel.', 'Estamos', self::form('estamos'), 'We are, about where we are, is estamos.'),
            self::typeGap($stage, 'sentences.type_gap.habitaciones', 'Las habitaciones ___ disponibles.', 'The rooms are available.', 'están', self::form('están'), 'More than one thing takes están.'),
            self::typeGap($stage, 'sentences.type_gap.hay-llave', '___ una llave en la habitación.', 'There is a key in the room.', 'Hay', self::form('Hay', true), 'Hay says that something exists.'),
            self::typeGap($stage, 'sentences.type_gap.estoy', '___ en la habitación.', 'I am in the room.', 'Estoy', self::form('estoy'), 'I am, about where I am, is estoy.'),
            self::translate($stage, 'sentences.translate.desayuno', 'The breakfast is included.', ['El desayuno está incluido.', 'Está incluido el desayuno.'], [self::word('el desayuno'), self::word('incluido'), self::form('está')]),
            self::translate($stage, 'sentences.translate.habitacion', 'The room is available.', ['La habitación está disponible.'], [self::word('la habitación'), self::word('disponible'), self::form('está')]),
            self::translate($stage, 'sentences.translate.hotel', 'The hotel is here.', ['El hotel está aquí.'], [self::word('el hotel'), self::form('está')]),
            self::build($stage, 'sentences.build.bano-aqui', 'The bathroom is here.', 'El baño está aquí.', ['es'], [self::word('el baño'), self::form('está')]),
            self::build($stage, 'sentences.build.hay-habitacion', 'There is a room available.', 'Hay una habitación disponible.', ['está'], [self::word('la habitación', 'habitación'), self::word('disponible'), self::form('Hay', true)]),
            self::build($stage, 'sentences.build.reserva-noches', 'The reservation is for two nights.', 'La reserva es para dos noches.', ['hay'], [self::word('la reserva'), self::word('la noche', 'noches'), self::form('es', true)]),

            self::listenChoose($stage, 'sentences.listen_choose.bano-aqui', 'El baño está aquí.', ['The key is here.', 'The hotel is here.', 'The bathroom is here.', 'The breakfast is here.'], 'The bathroom is here.', [self::word('el baño'), self::form('está')]),
            self::listenChoose($stage, 'sentences.listen_choose.hay-habitacion', 'Hay una habitación disponible.', ['The room is not available.', 'There is a room available.', 'The reservation is for one night.', 'There is a bathroom in the room.'], 'There is a room available.', [self::word('la habitación'), self::word('disponible'), self::form('Hay', true)]),
            self::listenChoose($stage, 'sentences.listen_choose.hotel', 'El hotel está aquí.', ['The hotel is near.', 'The breakfast is here.', 'The hotel is here.', 'The bathroom is here.'], 'The hotel is here.', [self::word('el hotel'), self::form('está')]),
            self::listenType($stage, 'sentences.listen_type.llave', 'La llave está en la habitación.', 'The key is in the room.', [self::word('la llave'), self::word('la habitación'), self::form('está')]),
            self::listenType($stage, 'sentences.listen_type.desayuno', 'El desayuno está incluido.', 'Breakfast is included.', [self::word('el desayuno'), self::word('incluido'), self::form('está')]),
            self::listenType($stage, 'sentences.listen_type.reserva', 'Tengo una reserva.', 'I have a reservation.', [self::word('la reserva', 'reserva')]),
            self::listenType($stage, 'sentences.listen_type.recepcionista', 'Soy el recepcionista.', 'I am the receptionist.', [self::word('el recepcionista', 'recepcionista'), self::form('Soy', true)], 'listen', null, ['Soy la recepcionista.']),

            self::speakRepeat($stage, 'sentences.speak_repeat.hay', '¿Hay una habitación disponible para dos noches?', 'Is there a room available for two nights?', [self::word('disponible'), self::word('la noche', 'noches'), self::form('Hay', true)]),
            self::speakRepeat($stage, 'sentences.speak_repeat.llave', 'La llave está en la habitación.', 'The key is in the room.', [self::word('la llave'), self::word('la habitación'), self::form('está')]),
            self::speakRepeat($stage, 'sentences.speak_repeat.recepcionista', 'Soy el recepcionista.', 'I am the receptionist.', [self::word('el recepcionista', 'recepcionista'), self::form('Soy', true)], 'speak', ['Soy la recepcionista.']),
            self::speakRepeat($stage, 'sentences.speak_repeat.reserva', 'La reserva es para dos noches.', 'The reservation is for two nights.', [self::word('la reserva'), self::word('la noche', 'noches'), self::form('es', true)]),
            self::speakAnswer($stage, 'sentences.speak_answer.reserva', '¿Tiene una reserva?', 'Do you have a reservation?', [['tengo', 'tiene', 'tenemos'], ['reserva']], 'Sí, tengo una reserva.', [self::word('la reserva')]),
            self::speakAnswer($stage, 'sentences.speak_answer.bano', '¿Dónde está el baño?', 'Where is the bathroom?', [['baño', 'está'], ['aquí', 'allí', 'habitación']], 'El baño está aquí.', [self::word('el baño'), self::form('está')]),
            self::speakAnswer($stage, 'sentences.speak_answer.disponible', '¿Hay una habitación disponible?', 'Is there a room available?', [['sí', 'no', 'hay'], ['habitación', 'habitaciones', 'disponible', 'disponibles', 'una']], 'Sí, hay una habitación disponible.', [self::word('la habitación'), self::word('disponible'), self::form('Hay', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            new AuthoredExercise($stage, Format::ReadPassage, 'task.read_passage.reception', [
                'prompt' => 'Read the conversation at the reception desk.',
                'dialogue' => [
                    ['speaker' => 'Recepcionista', 'text' => 'Buenas tardes, soy el recepcionista. ¿Tiene una reserva?'],
                    ['speaker' => 'Ana', 'text' => 'Sí, tengo una reserva para dos noches.'],
                    ['speaker' => 'Recepcionista', 'text' => 'Muy bien. La habitación está disponible. Aquí está la llave.'],
                    ['speaker' => 'Ana', 'text' => '¿Y el desayuno?'],
                    ['speaker' => 'Recepcionista', 'text' => 'El desayuno está incluido.'],
                    ['speaker' => 'Ana', 'text' => 'Muchas gracias.'],
                ],
                'questions' => [
                    self::question('How many nights is the reservation for?', ['one', 'two', 'three'], 'two'),
                    self::question('Is the room available?', ['No, it is not available.', 'Yes, it is available.', 'The text does not say.'], 'Yes, it is available.'),
                    self::question('Is breakfast included?', ['Yes, it is included.', 'No, it is not included.', 'The text does not say.'], 'Yes, it is included.'),
                ],
            ], targets: [self::word('la reserva'), self::word('la noche'), self::word('el recepcionista'), self::word('la habitación'), self::word('disponible'), self::word('la llave'), self::word('el desayuno'), self::word('incluido')], block: 'read'),
            self::gap($stage, 'task.choose_gap.habitaciones', 'Las habitaciones están ___.', ['disponibles', 'disponible'], 'disponibles', self::word('disponible', 'disponibles'), 'The adjective agrees with the noun: more than one room, so disponibles.', 'read'),
            self::gap($stage, 'task.choose_gap.desayuno', 'El desayuno está ___.', ['incluida', 'incluido'], 'incluido', self::word('incluido'), 'Desayuno is masculine, so the adjective is incluido.', 'read'),

            self::transform($stage, 'task.transform.habitacion', 'Make it plural.', 'La habitación está disponible.', ['Las habitaciones están disponibles.'], [self::word('la habitación', 'las habitaciones'), self::word('disponible', 'disponibles'), self::form('están')]),
            self::transform($stage, 'task.transform.hotel', 'Make it plural.', 'El hotel está aquí.', ['Los hoteles están aquí.'], [self::word('el hotel', 'los hoteles'), self::form('están')]),
            self::transform($stage, 'task.transform.pregunta', 'Make it a question.', 'El desayuno está incluido.', ['¿Está incluido el desayuno?', '¿El desayuno está incluido?', '¿Está el desayuno incluido?'], [self::word('el desayuno'), self::word('incluido'), self::form('está')]),
            new AuthoredExercise($stage, Format::WriteGuided, 'task.write_guided.room', [
                'prompt' => 'Ask for a room for two nights and ask whether breakfast is included.',
                'chips' => ['habitación', 'noches', 'desayuno', 'incluido'],
                'glosses' => ['quiero' => 'I want'],
                'model' => 'Quiero una habitación para dos noches. ¿El desayuno está incluido?',
                'required' => [
                    ['forms' => ['habitación', 'habitaciones'], 'term' => 'la habitación'],
                    ['forms' => ['noches'], 'term' => 'la noche'],
                    ['forms' => ['desayuno'], 'term' => 'el desayuno'],
                    ['forms' => ['incluido'], 'term' => 'incluido'],
                ],
            ], targets: [self::word('la habitación'), self::word('la noche'), self::word('el desayuno'), self::word('incluido')], block: 'write'),
            new AuthoredExercise($stage, Format::WriteGuided, 'task.write_guided.reserva', [
                'prompt' => 'Say that you have a reservation and ask where the bathroom is.',
                'chips' => ['reserva', 'dónde', 'baño'],
                'model' => 'Tengo una reserva. ¿Dónde está el baño?',
                'required' => [
                    ['forms' => ['reserva'], 'term' => 'la reserva'],
                    ['forms' => ['dónde'], 'term' => null],
                    ['forms' => ['baño', 'aseo'], 'term' => 'el baño'],
                ],
            ], targets: [self::word('la reserva'), self::word('el baño')], block: 'write'),
            self::build($stage, 'task.build.llave', 'The key is in the room.', 'La llave está en la habitación.', ['es', 'hay'], [self::word('la llave'), self::word('la habitación'), self::form('está')], 'write'),
            self::build($stage, 'task.build.estamos', 'We are in the hotel.', 'Estamos en el hotel.', ['Estoy', 'hay'], [self::word('el hotel'), self::form('estamos')], 'write'),
            self::build($stage, 'task.build.habitaciones', 'There are rooms available for two nights.', 'Hay habitaciones disponibles para dos noches.', ['están', 'es'], [self::word('la habitación', 'habitaciones'), self::word('disponible', 'disponibles'), self::word('la noche', 'noches'), self::form('Hay', true)], 'write'),
            self::translate($stage, 'task.translate.reserva', 'Do you have a reservation?', ['¿Tiene una reserva?', '¿Tienes una reserva?', '¿Tiene usted una reserva?', '¿Tiene reserva?', '¿Tienes reserva?', '¿Tienen una reserva?'], [self::word('la reserva', 'reserva')], 'write'),
            self::translate($stage, 'task.translate.noches', 'The reservation is for two nights.', ['La reserva es para dos noches.'], [self::word('la reserva'), self::word('la noche', 'noches'), self::form('es', true)], 'write'),

            new AuthoredExercise($stage, Format::ListenPassage, 'task.listen_passage.reception', [
                'prompt' => 'Listen to the conversation.',
                'dialogue' => [
                    ['speaker' => 'Recepcionista', 'text' => 'Su habitación es la tres.'],
                    ['speaker' => 'Ana', 'text' => '¿Dónde está el baño?'],
                    ['speaker' => 'Recepcionista', 'text' => 'Está en la habitación. Aquí está la llave.'],
                    ['speaker' => 'Ana', 'text' => '¿Y el desayuno?'],
                    ['speaker' => 'Recepcionista', 'text' => 'El desayuno está incluido.'],
                ],
                'questions' => [
                    self::question('Which room is it?', ['two', 'three', 'four'], 'three'),
                    self::question('Where is the bathroom?', ['Next to the reception desk.', 'In the room.', 'The conversation does not say.'], 'In the room.'),
                    self::question('What does Ana ask about last?', ['The key', 'The bathroom', 'The breakfast'], 'The breakfast'),
                ],
                'substitute_questions' => [
                    self::question('Who gives Ana the information?', ['Another guest', 'The receptionist', 'The conversation does not say.'], 'The receptionist'),
                    self::question('What is included?', ['The breakfast', 'The key', 'Nothing'], 'The breakfast'),
                    self::question('How many people speak?', ['One', 'Three', 'Two'], 'Two'),
                ],
            ], targets: [self::word('la habitación'), self::word('el baño'), self::word('la llave'), self::word('el desayuno'), self::word('incluido')], block: 'listen'),
            self::listenType($stage, 'task.listen_type.saludo', 'Buenas tardes, tengo una reserva.', 'Good afternoon, I have a reservation.', [self::word('la reserva', 'reserva')], 'listen'),
            self::listenType($stage, 'task.listen_type.noches', 'La habitación está disponible para dos noches.', 'The room is available for two nights.', [self::word('la habitación'), self::word('disponible'), self::word('la noche', 'noches'), self::form('está')], 'listen'),
            self::listenType($stage, 'task.listen_type.llave', '¿Dónde está la llave?', 'Where is the key?', [self::word('la llave'), self::form('está')], 'listen'),

            self::speakAnswer($stage, 'task.speak_answer.noches', '¿Para cuántas noches?', 'For how many nights?', [['una', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete']], 'Para dos noches.', [self::word('la noche', 'noches')], 'speak'),
            self::speakAnswer($stage, 'task.speak_answer.desayuno', '¿Está incluido el desayuno?', 'Is breakfast included?', [['sí', 'no', 'está'], ['incluido']], 'Sí, está incluido.', [self::word('el desayuno'), self::word('incluido'), self::form('está')], 'speak'),
            self::speakAnswer($stage, 'task.speak_answer.llave', '¿Dónde está la llave?', 'Where is the key?', [['llave', 'está'], ['aquí', 'allí', 'habitación']], 'La llave está aquí.', [self::word('la llave'), self::form('está')], 'speak'),
            self::speakAnswer($stage, 'task.speak_answer.bano', '¿Hay un baño en la habitación?', 'Is there a bathroom in the room?', [['sí', 'no', 'hay', 'está'], ['baño', 'aseo']], 'Sí, hay un baño en la habitación.', [self::word('el baño'), self::word('la habitación'), self::form('Hay', true)], 'speak'),
            self::speakRepeat($stage, 'task.speak_repeat.hotel', 'Buenas noches, estamos en el hotel.', 'Good evening, we are in the hotel.', [self::word('el hotel'), self::form('estamos')], 'speak'),
            self::speakRepeat($stage, 'task.speak_repeat.recepcionista', 'Buenas tardes, soy el recepcionista.', 'Good afternoon, I am the receptionist.', [self::word('el recepcionista', 'recepcionista')], 'speak', ['Buenas tardes, soy la recepcionista.']),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            self::translate($stage, 'check.a.translate.bano', 'The bathroom is in the room.', ['El baño está en la habitación.'], [self::word('el baño'), self::word('la habitación'), self::form('está')], 'sentences', $set),
            self::translate($stage, 'check.a.translate.hay', 'There are rooms available.', ['Hay habitaciones disponibles.'], [self::word('la habitación', 'habitaciones'), self::word('disponible', 'disponibles'), self::form('Hay', true)], 'sentences', $set),
            self::translate($stage, 'check.a.translate.recepcionista', 'The receptionist is in the hotel.', ['El recepcionista está en el hotel.', 'La recepcionista está en el hotel.'], [self::word('el recepcionista', 'recepcionista'), self::word('el hotel')], 'sentences', $set),
            self::translate($stage, 'check.a.translate.desayuno', 'Breakfast is included in the reservation.', ['El desayuno está incluido en la reserva.'], [self::word('el desayuno'), self::word('incluido'), self::word('la reserva')], 'sentences', $set),
            self::typeGap($stage, 'check.a.type_gap.habitaciones', 'Las habitaciones ___ en el hotel.', 'The rooms are in the hotel.', 'están', self::form('están'), null, 'sentences', $set),
            self::typeGap($stage, 'check.a.type_gap.recepcionista', 'Él ___ el recepcionista.', 'He is the receptionist.', 'es', self::form('es', true), null, 'sentences', $set),
            self::listenType($stage, 'check.a.listen_type.reserva', 'Tengo una reserva para dos noches.', 'I have a reservation for two nights.', [self::word('la reserva', 'reserva'), self::word('la noche', 'noches')], 'dictation', $set),
            self::listenType($stage, 'check.a.listen_type.estamos', 'Estamos en la habitación.', 'We are in the room.', [self::word('la habitación'), self::form('estamos')], 'dictation', $set),
            self::listenType($stage, 'check.a.listen_type.llave', 'La llave está aquí.', 'The key is here.', [self::word('la llave'), self::form('está')], 'dictation', $set),
            new AuthoredExercise($stage, Format::ListenPassage, 'check.a.listen_passage.reception', [
                'prompt' => 'Listen to the conversation.',
                'dialogue' => [
                    ['speaker' => 'Recepcionista', 'text' => 'Buenas noches. Su habitación es la cinco.'],
                    ['speaker' => 'Ana', 'text' => 'Gracias. ¿Hay desayuno?'],
                    ['speaker' => 'Recepcionista', 'text' => 'No, no hay desayuno. Aquí tiene su llave.'],
                ],
                'questions' => [
                    self::question('Which room is it?', ['three', 'four', 'five'], 'five'),
                    self::question('Is there breakfast?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                    self::question('What does the receptionist give Ana?', ['The bill', 'The key', 'The breakfast'], 'The key'),
                ],
                'substitute_questions' => [
                    self::question('What does the receptionist say first?', ['Good morning', 'Good afternoon', 'Good evening'], 'Good evening'),
                    self::question('Who asks about breakfast?', ['Ana', 'The receptionist', 'Nobody'], 'Ana'),
                    self::question('Is the room number three?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                ],
            ], targets: [self::word('la habitación'), self::word('el desayuno'), self::word('la llave')], probeSet: $set, block: 'passages'),
            new AuthoredExercise($stage, Format::ReadPassage, 'check.a.read_passage.reception', [
                'prompt' => 'Read the conversation.',
                'dialogue' => [
                    ['speaker' => 'Recepcionista', 'text' => 'Buenas tardes. Su reserva es para tres noches.'],
                    ['speaker' => 'Ana', 'text' => 'Muy bien. ¿Hay desayuno?'],
                    ['speaker' => 'Recepcionista', 'text' => 'Sí, hay desayuno.'],
                ],
                'questions' => [
                    self::question('How many nights is the reservation for?', ['two', 'three', 'four'], 'three'),
                    self::question('Is there breakfast?', ['No', 'The text does not say.', 'Yes'], 'Yes'),
                ],
            ], targets: [self::word('la reserva'), self::word('la noche'), self::word('el desayuno')], probeSet: $set, block: 'passages'),
            self::speakAnswer($stage, 'check.a.speak_answer.reserva', '¿Tiene usted una reserva?', 'Do you have a reservation?', [['tengo', 'tiene', 'tenemos'], ['reserva']], 'Sí, tengo una reserva.', [self::word('la reserva')], 'speaking', $set),
            self::speakAnswer($stage, 'check.a.speak_answer.noches', '¿Cuántas noches?', 'How many nights?', [['una', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete']], 'Dos noches.', [self::word('la noche', 'noches')], 'speaking', $set),
            self::speakAnswer($stage, 'check.a.speak_answer.desayuno', '¿Hay desayuno?', 'Is there breakfast?', [['sí', 'no', 'hay'], ['desayuno', 'incluido']], 'Sí, hay desayuno.', [self::word('el desayuno')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            self::translate($stage, 'check.b.translate.hotel', 'Where is the hotel?', ['¿Dónde está el hotel?'], [self::word('el hotel'), self::form('está')], 'sentences', $set),
            self::translate($stage, 'check.b.translate.desayuno', 'The breakfast is not included.', ['El desayuno no está incluido.', 'No está incluido el desayuno.'], [self::word('el desayuno'), self::word('incluido')], 'sentences', $set),
            self::translate($stage, 'check.b.translate.recepcionista', 'The receptionist has the key.', ['El recepcionista tiene la llave.', 'La recepcionista tiene la llave.'], [self::word('el recepcionista', 'recepcionista'), self::word('la llave')], 'sentences', $set),
            self::translate($stage, 'check.b.translate.hay', 'There are no rooms available.', ['No hay habitaciones disponibles.'], [self::word('la habitación', 'habitaciones'), self::word('disponible', 'disponibles'), self::form('hay', true)], 'sentences', $set),
            self::typeGap($stage, 'check.b.type_gap.recepcionista', 'El recepcionista ___ aquí.', 'The receptionist is here.', 'está', self::form('está'), null, 'sentences', $set),
            self::typeGap($stage, 'check.b.type_gap.ella', 'Ella ___ la recepcionista.', 'She is the receptionist.', 'es', self::form('es', true), null, 'sentences', $set),
            self::listenType($stage, 'check.b.listen_type.llaves', 'Las llaves están en el hotel.', 'The keys are in the hotel.', [self::word('la llave', 'llaves'), self::word('el hotel'), self::form('están')], 'dictation', $set),
            self::listenType($stage, 'check.b.listen_type.reserva', 'La reserva es para una noche.', 'The reservation is for one night.', [self::word('la reserva'), self::word('la noche', 'noche')], 'dictation', $set),
            self::listenType($stage, 'check.b.listen_type.bano', 'Estoy en el baño.', 'I am in the bathroom.', [self::word('el baño'), self::form('estoy')], 'dictation', $set),
        ];
    }

    private static function word(string $term, ?string $form = null): TargetSpec
    {
        return TargetSpec::word($term, $form);
    }

    private static function form(string $form, bool $contrast = false): TargetSpec
    {
        return TargetSpec::grammar($form, $contrast);
    }

    /**
     * @param  list<string>  $options
     * @return array{prompt: string, options: list<string>, answer: string}
     */
    private static function question(string $prompt, array $options, string $answer): array
    {
        return ['prompt' => $prompt, 'options' => $options, 'answer' => $answer];
    }

    /** @param  list<string>  $options */
    private static function gap(Stage $stage, string $key, string $prompt, array $options, string $answer, TargetSpec $target, string $why, string $block, ?string $english = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ChooseGap, $key, array_filter(['prompt' => $prompt, 'english' => $english, 'options' => $options, 'answer' => $answer, 'why' => $why]), [$answer], [$target], block: $block);
    }

    private static function typeGap(Stage $stage, string $key, string $prompt, string $english, string $answer, TargetSpec $target, ?string $why = null, string $block = 'write', ?string $set = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TypeGap, $key, array_filter(['prompt' => $prompt, 'english' => $english, 'why' => $why]), [$answer], [$target], $set, $block);
    }

    /**
     * @param  list<string>  $answers
     * @param  list<TargetSpec>  $targets
     */
    private static function translate(Stage $stage, string $key, string $english, array $answers, array $targets, string $block = 'write', ?string $set = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TranslateSentence, $key, ['prompt' => $english, 'english' => $english], $answers, $targets, $set, $block);
    }

    /**
     * @param  list<string>  $distractors
     * @param  list<TargetSpec>  $targets
     */
    private static function build(Stage $stage, string $key, string $english, string $answer, array $distractors, array $targets, string $block = 'write'): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::BuildSentence, $key, ['prompt' => $english, 'english' => $english, 'distractors' => $distractors], [$answer], $targets, block: $block);
    }

    /**
     * @param  list<string>  $answers
     * @param  list<TargetSpec>  $targets
     */
    private static function transform(Stage $stage, string $key, string $instruction, string $source, array $answers, array $targets): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TransformSentence, $key, ['prompt' => $instruction, 'source' => $source], $answers, $targets, block: 'write');
    }

    /**
     * @param  list<string>  $options  English meanings
     * @param  list<TargetSpec>  $targets
     */
    private static function listenChoose(Stage $stage, string $key, string $text, array $options, string $answer, array $targets): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ListenChoose, $key, ['text' => $text, 'options' => $options, 'answer' => $answer], targets: $targets, block: 'listen');
    }

    /**
     * @param  list<TargetSpec>  $targets
     * @param  list<string>  $alsoAccepted
     */
    private static function listenType(Stage $stage, string $key, string $text, string $english, array $targets, string $block = 'listen', ?string $set = null, array $alsoAccepted = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ListenType, $key, ['text' => $text, 'english' => $english], [$text, ...$alsoAccepted], $targets, $set, $block);
    }

    /**
     * @param  list<TargetSpec>  $targets
     * @param  list<string>  $alsoAccepted
     */
    private static function speakRepeat(Stage $stage, string $key, string $text, string $english, array $targets, string $block = 'speak', array $alsoAccepted = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::SpeakRepeat, $key, ['text' => $text, 'english' => $english], $alsoAccepted === [] ? [] : [$text, ...$alsoAccepted], $targets, block: $block);
    }

    /**
     * @param  list<list<string>>  $slots
     * @param  list<TargetSpec>  $targets
     */
    private static function speakAnswer(Stage $stage, string $key, string $prompt, string $english, array $slots, string $model, array $targets, string $block = 'speak', ?string $set = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::SpeakAnswer, $key, ['prompt' => $prompt, 'english' => $english, 'slots' => $slots, 'model' => $model], targets: $targets, probeSet: $set, block: $block);
    }
}
