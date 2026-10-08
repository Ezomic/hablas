<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ATripITook implements UnitContent
{
    private const PLACES = ['playa', 'montaña', 'casa', 'ciudad', 'pueblo', 'parque', 'museo'];

    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'a-trip-i-took';
    }

    public function words(): array
    {
        return [
            new WordData('viajar', cue: 'to travel', forms: ['viajo', 'viajé', 'viajó', 'viajamos']),
            new WordData('el viaje', cue: 'trip (a journey)', note: 'El viaje is the whole journey. A short outing for the day is una excursión.'),
            new WordData('la playa', cue: 'beach'),
            new WordData('la montaña', cue: 'mountain'),
            new WordData('el avión', cue: 'plane (aeroplane)', note: 'By plane is en avión, without an article.'),
            new WordData('el equipaje', cue: 'luggage', note: 'Equipaje is singular and cannot be counted: mi equipaje, never a plural. One suitcase is una maleta.'),
            new WordData('perder', cue: 'to lose, to miss (a plane, train or bus)', forms: ['perdí', 'perdiste', 'perdió']),
            new WordData('el recuerdo', cue: 'souvenir', forms: ['recuerdos'], note: 'El recuerdo is a souvenir you bring home. It also means a memory.'),
            new WordData('la excursión', cue: 'excursion, day trip (a planned outing)'),
            new WordData('el barco', cue: 'boat, ship'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Fui a la playa.', 'english' => 'I went to the beach.'],
            ['text' => 'Estuve en la montaña.', 'english' => 'I was in the mountains.'],
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
            Kit::gap($stage, 'sentences.choose_gap.fui-playa', 'Yo ___ a la playa el domingo.', ['fui', 'voy', 'fue'], 'fui', Kit::form('fui', true), 'Fui is the past of ir for yo: I went. Voy is the present, and fue is for él or ella.', 'choose', 'On Sunday I went to the beach.'),
            Kit::gap($stage, 'sentences.choose_gap.estuve-montana', 'Yo ___ en la montaña con Luis.', ['estuve', 'estoy', 'estuvo'], 'estuve', Kit::form('estuve', true), 'Estuve is the past of estar for yo: I was. Estoy is the present, and estuvo is for él or ella.', 'choose', 'I was in the mountains with Luis.'),
            Kit::gap($stage, 'sentences.choose_gap.tuvo-ana', 'Ana ___ mucho calor en la playa.', ['tuvo', 'tuve', 'tiene'], 'tuvo', Kit::form('tuvo'), 'Ana is ella, so the form ends in -o: tuvo. Tuve is for yo, and tiene is the present.', 'choose', 'Ana was very hot on the beach.'),
            Kit::gap($stage, 'sentences.choose_gap.hizo-marta', 'Marta ___ un viaje con Luis.', ['hizo', 'hice', 'hace'], 'hizo', Kit::form('hizo'), 'Marta is ella, so hizo (the c becomes z). Hice is for yo, and hace is the present.', 'choose', 'Marta took a trip with Luis.'),
            Kit::gap($stage, 'sentences.choose_gap.vimos-barco', 'Nosotros ___ el barco en la playa.', ['vimos', 'vemos', 'vio'], 'vimos', Kit::form('vimos', true), 'Nosotros takes vimos. Vemos is the present, and vio is for él or ella.', 'choose', 'We saw the boat on the beach.'),
            Kit::gap($stage, 'sentences.choose_gap.avion', 'Voy a España en ___.', ['avión', 'playa', 'recuerdo'], 'avión', Kit::word('el avión', 'avión'), 'En avión means by plane. A playa is a place, and a recuerdo is something you keep.', 'choose', 'I am going to Spain by plane.'),

            Kit::typeGap($stage, 'sentences.type_gap.fuimos-montana', 'Ana y yo ___ a la montaña.', 'Ana and I went to the mountains.', 'fuimos', Kit::form('fuimos'), 'Ana y yo means we, so use fuimos, the nosotros form of ir in the past.'),
            Kit::typeGap($stage, 'sentences.type_gap.estuvo-barco', 'Marta ___ en el barco.', 'Marta was on the boat.', 'estuvo', Kit::form('estuvo'), 'Marta is ella, so estuvo: the él and ella form of estar in the past.'),
            Kit::typeGap($stage, 'sentences.type_gap.tuve-frio', 'Yo ___ mucho frío en la montaña.', 'I was very cold in the mountains.', 'tuve', Kit::form('tuve'), 'Yo takes tuve, the past of tener. Tuvo would be for él or ella.'),
            Kit::typeGap($stage, 'sentences.type_gap.hice-viaje', 'Yo ___ un viaje con Ana.', 'I took a trip with Ana.', 'hice', Kit::form('hice'), 'Yo takes hice, the past of hacer. It has no accent, and hizo is for él or ella.'),
            Kit::typeGap($stage, 'sentences.type_gap.equipaje', 'Mi ___ está en el avión.', 'My luggage is on the plane.', 'equipaje', Kit::word('el equipaje', 'equipaje')),

            Kit::translate($stage, 'sentences.translate.lunes-montana', 'On Monday I went to the mountains.', ['El lunes fui a la montaña.', 'El lunes yo fui a la montaña.', 'Fui a la montaña el lunes.', 'Yo fui a la montaña el lunes.'], [Kit::word('la montaña', 'montaña'), Kit::form('fui')]),
            Kit::translate($stage, 'sentences.translate.viste-barco', 'Did you see the boat on the beach? (informal you)', ['¿Viste el barco en la playa?', '¿Tú viste el barco en la playa?'], [Kit::word('el barco', 'barco'), Kit::word('la playa', 'playa'), Kit::form('viste', true)]),
            Kit::translate($stage, 'sentences.translate.viaje-barco', 'I travelled by boat with Ana.', ['Viajé en barco con Ana.', 'Yo viajé en barco con Ana.'], [Kit::word('viajar', 'viajé'), Kit::word('el barco', 'barco')]),

            Kit::build($stage, 'sentences.build.vio-montana', 'Luis saw a mountain.', 'Luis vio una montaña.', ['vi'], [Kit::word('la montaña', 'montaña'), Kit::form('vio')]),
            Kit::build($stage, 'sentences.build.hice-viaje', 'I took a trip by boat.', 'Hice un viaje en barco.', ['hizo'], [Kit::word('el viaje', 'viaje'), Kit::word('el barco', 'barco'), Kit::form('hice')]),
            Kit::build($stage, 'sentences.build.fuimos-excursion', 'Ana and I went on an excursion.', 'Ana y yo fuimos de excursión.', ['fue'], [Kit::word('la excursión', 'excursión'), Kit::form('fuimos')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.playa', '¿Fuiste a la playa con Marta?', ['Did you go to the beach with Marta?', 'Are you going to the beach with Marta?', 'Did Marta go to the beach?', 'Were you at the beach with Marta?'], 'Did you go to the beach with Marta?', [Kit::word('la playa', 'playa'), Kit::form('fuiste')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.montana', 'Pablo vio la montaña.', ['Pablo saw the mountain.', 'Pablo sees the mountain.', 'I saw the mountain.', 'Pablo was in the mountains.'], 'Pablo saw the mountain.', [Kit::word('la montaña', 'montaña'), Kit::form('vio')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.viaje', 'El viaje fue muy caro.', ['The trip was very expensive.', 'The trip is very expensive.', 'The trip was very cheap.', 'I took a very expensive trip.'], 'The trip was very expensive.', [Kit::word('el viaje', 'viaje'), Kit::form('fue')]),
            Kit::listenType($stage, 'sentences.listen_type.perdio-equipaje', 'Ana perdió su recuerdo y su equipaje.', 'Ana lost her souvenir and her luggage.', [Kit::word('perder', 'perdió'), Kit::word('el recuerdo', 'recuerdo'), Kit::word('el equipaje', 'equipaje')]),
            Kit::listenType($stage, 'sentences.listen_type.estuvo-playa', '¿Estuviste en la playa?', 'Were you on the beach? (informal you)', [Kit::word('la playa', 'playa'), Kit::form('estuviste')]),
            Kit::listenType($stage, 'sentences.listen_type.fuimos-excursion', 'Fuimos a una excursión con Pablo.', 'We went on an excursion with Pablo.', [Kit::word('la excursión', 'excursión'), Kit::form('fuimos')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.hizo-viaje', 'Luis hizo un viaje en avión.', 'Luis took a trip by plane.', [Kit::word('el viaje', 'viaje'), Kit::word('el avión', 'avión'), Kit::form('hizo')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.viaje-barco', 'Viajé en barco con Ana.', 'I travelled by boat with Ana.', [Kit::word('viajar', 'viajé'), Kit::word('el barco', 'barco')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.recuerdo', 'Tengo un recuerdo del viaje.', 'I have a souvenir from the trip.', [Kit::word('el recuerdo', 'recuerdo'), Kit::word('el viaje', 'viaje')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.perdi-avion', 'Perdí el avión y mi equipaje.', 'I missed the plane and lost my luggage.', [Kit::word('perder', 'perdí'), Kit::word('el avión', 'avión'), Kit::word('el equipaje', 'equipaje')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.excursion', 'Ana y yo fuimos a la excursión.', 'Ana and I went on the excursion.', [Kit::word('la excursión', 'excursión'), Kit::form('fuimos')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.donde', '¿Dónde estuviste el domingo?', 'Where were you on Sunday?', [['estuve', 'fui'], self::PLACES], 'El domingo estuve en la playa.', [Kit::word('la playa', 'playa'), Kit::form('estuve')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.fuiste', '¿Fuiste a la playa?', 'Did you go to the beach?', [['sí', 'no'], ['fui', 'fuimos', 'estuve', ...self::PLACES]], 'Sí, fui a la playa.', [Kit::word('la playa', 'playa'), Kit::form('fui')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.viste', '¿Viste el barco?', 'Did you see the boat?', [['sí', 'no'], ['vi', 'vimos', 'barco']], 'Sí, vi el barco.', [Kit::word('el barco', 'barco'), Kit::form('vi')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.viaje-luis', 'Read the conversation about Luis and his trip.', [
                Kit::line('Ana', 'Luis, ¿hiciste un viaje en verano?'),
                Kit::line('Luis', 'Sí, fui a la playa con Pablo. Viajamos en avión.'),
                Kit::line('Ana', '¿Qué viste allí?'),
                Kit::line('Luis', 'Vi una montaña muy alta. Pero perdí mi equipaje.'),
            ], [
                Kit::question('Where did Luis go?', ['To the beach', 'To the mountains', 'To the city'], 'To the beach'),
                Kit::question('How did Luis and Pablo travel?', ['By plane', 'By boat', 'By train'], 'By plane'),
                Kit::question('What did Luis lose?', ['His luggage', 'His plane', 'His souvenir'], 'His luggage'),
            ], [Kit::word('el viaje', 'viaje'), Kit::word('la playa', 'playa'), Kit::word('viajar', 'Viajamos'), Kit::word('el avión', 'avión'), Kit::word('la montaña', 'montaña'), Kit::word('perder', 'perdí'), Kit::word('el equipaje', 'equipaje')], 'read'),
            Kit::gap($stage, 'task.choose_gap.hiciste-montana', '¿Qué ___ tú en la montaña?', ['hiciste', 'hizo', 'hice'], 'hiciste', Kit::form('hiciste', true), 'Tú goes with hiciste, the past of hacer for tú. Hice is for yo, and hizo is for él or ella.', 'read', 'What did you do in the mountains? (informal you)'),
            Kit::gap($stage, 'task.choose_gap.excursion-hoy', 'Hoy hay una ___ a la montaña.', ['excursión', 'recuerdo', 'equipaje'], 'excursión', Kit::word('la excursión', 'excursión'), 'Una excursión is a planned outing, and it fits hay and a la montaña. A recuerdo and an equipaje are things, not events.', 'read', 'Today there is an excursion to the mountains.'),

            Kit::transform($stage, 'task.transform.voy-playa', 'Say that you went to the beach (past).', 'Voy a la playa.', ['Fui a la playa.', 'Yo fui a la playa.'], [Kit::word('la playa', 'playa'), Kit::form('fui', true)]),
            Kit::transform($stage, 'task.transform.ana-barco', 'Say that Ana was on the boat (past).', 'Ana está en el barco.', ['Ana estuvo en el barco.'], [Kit::word('el barco', 'barco'), Kit::form('estuvo', true)]),
            Kit::transform($stage, 'task.transform.veo-montana', 'Say that you saw the mountain (past).', 'Veo la montaña.', ['Vi la montaña.', 'Yo vi la montaña.'], [Kit::word('la montaña', 'montaña'), Kit::form('vi', true)]),
            Kit::writeGuided($stage, 'task.write_guided.playa-avion', 'Say that you went to the beach by plane and that you lost your luggage.', ['fui a', 'playa', 'avión', 'perdí', 'equipaje'], 'Fui a la playa en avión y perdí mi equipaje.', [
                ['forms' => ['fui'], 'term' => null],
                ['forms' => ['playa'], 'term' => 'la playa'],
                ['forms' => ['avión'], 'term' => 'el avión'],
                ['forms' => ['perdí'], 'term' => 'perder'],
                ['forms' => ['equipaje'], 'term' => 'el equipaje'],
            ], [Kit::word('la playa', 'playa'), Kit::word('el avión', 'avión'), Kit::word('perder', 'perdí'), Kit::word('el equipaje', 'equipaje'), Kit::form('fui')]),
            Kit::writeGuided($stage, 'task.write_guided.ana-barco-montana', 'Say that Ana made a trip by boat and that she saw a mountain.', ['hizo', 'viaje', 'barco', 'vio', 'montaña'], 'Ana hizo un viaje en barco y vio una montaña.', [
                ['forms' => ['hizo'], 'term' => null],
                ['forms' => ['viaje'], 'term' => 'el viaje'],
                ['forms' => ['barco'], 'term' => 'el barco'],
                ['forms' => ['vio'], 'term' => null],
                ['forms' => ['montaña'], 'term' => 'la montaña'],
            ], [Kit::word('el viaje', 'viaje'), Kit::word('el barco', 'barco'), Kit::word('la montaña', 'montaña'), Kit::form('hizo')]),
            Kit::build($stage, 'task.build.viajamos-avion', 'We travelled by plane to the mountains.', 'Viajamos en avión a la montaña.', ['por', 'fuimos'], [Kit::word('viajar', 'Viajamos'), Kit::word('el avión', 'avión'), Kit::word('la montaña', 'montaña')]),
            Kit::build($stage, 'task.build.marta-barco', 'Marta saw a boat and a souvenir shop.', 'Marta vio un barco y una tienda de recuerdos.', ['vi', 'el'], [Kit::word('el barco', 'barco'), Kit::word('el recuerdo', 'recuerdos'), Kit::form('vio')]),
            Kit::build($stage, 'task.build.pablo-excursion', 'Pablo was on the excursion with Ana.', 'Pablo estuvo en la excursión con Ana.', ['estuve', 'fue'], [Kit::word('la excursión', 'excursión'), Kit::form('estuvo', true)]),
            Kit::translate($stage, 'task.translate.lunes-excursion', 'On Monday we went on an excursion to the mountains.', ['El lunes fuimos de excursión a la montaña.', 'Fuimos de excursión a la montaña el lunes.', 'El lunes fuimos a una excursión a la montaña.', 'Fuimos a una excursión a la montaña el lunes.'], [Kit::word('la excursión', 'excursión'), Kit::word('la montaña', 'montaña'), Kit::form('fuimos')]),
            Kit::translate($stage, 'task.translate.pablo-equipaje', 'Pablo did not lose his luggage on the trip.', ['Pablo no perdió su equipaje en el viaje.'], [Kit::word('perder', 'perdió'), Kit::word('el equipaje', 'equipaje'), Kit::word('el viaje', 'viaje')]),

            Kit::listenPassage($stage, 'task.listen_passage.viaje-pablo', [
                Kit::line('Marta', 'Pablo, ¿cómo fue tu viaje?'),
                Kit::line('Pablo', 'Muy bien. Estuve en la montaña con Luis.'),
                Kit::line('Marta', '¿Fuiste en avión?'),
                Kit::line('Pablo', 'No, viajé en barco. Hice muchas fotos.'),
                Kit::line('Marta', '¿Y tienes un recuerdo?'),
                Kit::line('Pablo', 'Sí, tengo un recuerdo del viaje.'),
            ], [
                Kit::question('Where was Pablo?', ['In the mountains', 'On the beach', 'In the city'], 'In the mountains'),
                Kit::question('How did Pablo travel?', ['By boat', 'By plane', 'By train'], 'By boat'),
                Kit::question('Does Pablo have a souvenir?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Pablo', 'Luis'], 'Marta'),
                Kit::question('Who was with Pablo in the mountains?', ['Luis', 'Marta', 'Ana'], 'Luis'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el viaje', 'viaje'), Kit::word('la montaña', 'montaña'), Kit::word('el avión', 'avión'), Kit::word('viajar', 'viajé'), Kit::word('el barco', 'barco'), Kit::word('el recuerdo', 'recuerdo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.luis-playa-barco', 'Luis vio la playa y el barco.', 'Luis saw the beach and the boat.', [Kit::word('la playa', 'playa'), Kit::word('el barco', 'barco'), Kit::form('vio')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ana-avion', 'Ana perdió el avión y su equipaje.', 'Ana missed the plane and lost her luggage.', [Kit::word('perder', 'perdió'), Kit::word('el avión', 'avión'), Kit::word('el equipaje', 'equipaje')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.marta-excursion', 'Marta hizo una excursión a la montaña.', 'Marta went on an excursion to the mountains.', [Kit::word('la excursión', 'excursión'), Kit::word('la montaña', 'montaña'), Kit::form('hizo')], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.avion-barco', '¿Fuiste en avión o en barco?', 'Did you go by plane or by boat?', [['fui', 'viajé'], ['avión', 'barco']], 'Fui en barco.', [Kit::word('el avión', 'avión'), Kit::word('el barco', 'barco'), Kit::form('fui')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.domingo', '¿Qué hiciste el domingo?', 'What did you do on Sunday?', [['fui', 'estuve', 'hice', 'vi'], [...self::PLACES, 'excursión', 'cine']], 'El domingo fui a la playa.', [Kit::word('la playa', 'playa'), Kit::form('fui')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.equipaje', '¿Perdiste tu equipaje?', 'Did you lose your luggage?', [['sí', 'no'], ['perdí', 'equipaje']], 'No, no perdí mi equipaje.', [Kit::word('perder', 'perdí'), Kit::word('el equipaje', 'equipaje')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.recuerdo', '¿Tienes un recuerdo del viaje?', 'Do you have a souvenir from the trip?', [['sí', 'no'], ['tengo', 'recuerdo']], 'Sí, tengo un recuerdo del viaje.', [Kit::word('el recuerdo', 'recuerdo'), Kit::word('el viaje', 'viaje')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.viaje-playa', 'Hice un viaje en barco a la playa.', 'I took a trip by boat to the beach.', [Kit::word('el viaje', 'viaje'), Kit::word('el barco', 'barco'), Kit::word('la playa', 'playa'), Kit::form('hice')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.equipaje-excursion', 'Pablo perdió su equipaje en la excursión.', 'Pablo lost his luggage on the excursion.', [Kit::word('perder', 'perdió'), Kit::word('el equipaje', 'equipaje'), Kit::word('la excursión', 'excursión')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.domingo-playa-barco', 'On Sunday I went to the beach and I saw a boat.', ['El domingo fui a la playa y vi un barco.', 'Fui a la playa el domingo y vi un barco.'], [Kit::word('la playa', 'playa'), Kit::word('el barco', 'barco'), Kit::form('fui')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-viaje', 'Ana made a trip by plane.', ['Ana hizo un viaje en avión.'], [Kit::word('el viaje', 'viaje'), Kit::word('el avión', 'avión'), Kit::form('hizo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.excursion-montana', 'I was on an excursion in the mountains.', ['Estuve de excursión en la montaña.', 'Estuve en una excursión en la montaña.', 'Yo estuve de excursión en la montaña.'], [Kit::word('la excursión', 'excursión'), Kit::word('la montaña', 'montaña'), Kit::form('estuve', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo-equipaje', 'Pablo travelled with his luggage and was hot.', ['Pablo viajó con su equipaje y tuvo calor.'], [Kit::word('viajar', 'viajó'), Kit::word('el equipaje', 'equipaje'), Kit::form('tuvo', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ana-aeropuerto', 'Ana ___ su equipaje en el aeropuerto.', 'Ana lost her luggage at the airport.', 'perdió', Kit::word('perder', 'perdió'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.recuerdo-maleta', 'Tengo un ___ de la playa en la maleta.', 'I have a souvenir from the beach in my suitcase.', 'recuerdo', Kit::word('el recuerdo', 'recuerdo'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.viaje-marta', 'En el viaje en avión, Marta perdió su equipaje.', 'On the plane trip, Marta lost her luggage.', [Kit::word('el viaje', 'viaje'), Kit::word('el avión', 'avión'), Kit::word('perder', 'perdió'), Kit::word('el equipaje', 'equipaje')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.luis-barco-recuerdo', 'Luis vio un barco y un recuerdo en la playa.', 'Luis saw a boat and a souvenir on the beach.', [Kit::word('la playa', 'playa'), Kit::word('el barco', 'barco'), Kit::word('el recuerdo', 'recuerdo'), Kit::form('vio')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.viajamos-montana', 'Viajamos a la montaña y fuimos de excursión.', 'We travelled to the mountains and went on an excursion.', [Kit::word('viajar', 'viajamos'), Kit::word('la montaña', 'montaña'), Kit::word('la excursión', 'excursión'), Kit::form('fuimos')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.viaje-marta', [
                Kit::line('Luis', 'Marta, ¿cómo fue el viaje?'),
                Kit::line('Marta', 'Fue muy bien. Viajé en avión con Ana.'),
                Kit::line('Luis', '¿Perdiste el equipaje?'),
                Kit::line('Marta', 'No. Tengo el equipaje y un recuerdo de la playa.'),
            ], [
                Kit::question('How did Marta travel?', ['By plane', 'By boat', 'By train'], 'By plane'),
                Kit::question('Did Marta lose her luggage?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('What does Marta have from the beach?', ['A souvenir', 'A boat', 'A mountain'], 'A souvenir'),
            ], [
                Kit::question('Who asks the questions?', ['Luis', 'Marta', 'Ana'], 'Luis'),
                Kit::question('Who travelled with Marta?', ['Ana', 'Pablo', 'Luis'], 'Ana'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el viaje', 'viaje'), Kit::word('viajar', 'Viajé'), Kit::word('el avión', 'avión'), Kit::word('perder', 'Perdiste'), Kit::word('el equipaje', 'equipaje'), Kit::word('el recuerdo', 'recuerdo'), Kit::word('la playa', 'playa')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.excursion-ana', 'Read the conversation.', [
                Kit::line('Pablo', 'Ana, ¿qué hiciste el lunes?'),
                Kit::line('Ana', 'Fui de excursión a la montaña con Marta.'),
                Kit::line('Pablo', '¿Y qué tal?'),
                Kit::line('Ana', 'Tuve mucho calor, pero vi una montaña muy alta.'),
            ], [
                Kit::question('What did Ana do on Monday?', ['She went on an excursion', 'She travelled by plane', 'She stayed at home'], 'She went on an excursion'),
                Kit::question('How did Ana feel?', ['Very hot', 'Very cold', 'Very tired'], 'Very hot'),
            ], [Kit::word('la excursión', 'excursión'), Kit::word('la montaña', 'montaña')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.equipaje', '¿Tienes mucho equipaje?', 'Do you have a lot of luggage?', [['sí', 'no'], ['tengo', 'equipaje', 'maleta']], 'Sí, tengo mucho equipaje.', [Kit::word('el equipaje', 'equipaje')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.viaje-verano', '¿Hiciste un viaje en verano?', 'Did you take a trip in the summer?', [['sí', 'no'], ['hice', 'viaje', 'verano']], 'Sí, hice un viaje en verano.', [Kit::word('el viaje', 'viaje')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.avion', '¿Perdiste el avión?', 'Did you miss the plane?', [['sí', 'no'], ['perdí', 'avión']], 'No, no perdí el avión.', [Kit::word('perder', 'perdí'), Kit::word('el avión', 'avión')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.luis-montana-avion', 'Luis went to the mountains and saw a plane.', ['Luis fue a la montaña y vio un avión.'], [Kit::word('la montaña', 'montaña'), Kit::word('el avión', 'avión'), Kit::form('vio', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.viaje-equipaje', 'On the trip I lost my luggage and my souvenir.', ['En el viaje perdí mi equipaje y mi recuerdo.', 'Perdí mi equipaje y mi recuerdo en el viaje.'], [Kit::word('el viaje', 'viaje'), Kit::word('perder', 'perdí'), Kit::word('el equipaje', 'equipaje'), Kit::word('el recuerdo', 'recuerdo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pablo-excursion', 'Pablo and I were on an excursion by boat.', ['Pablo y yo estuvimos de excursión en barco.', 'Pablo y yo estuvimos en una excursión en barco.'], [Kit::word('la excursión', 'excursión'), Kit::word('el barco', 'barco'), Kit::form('estuvimos')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-avion', 'Marta travelled by plane. The trip was very expensive.', ['Marta viajó en avión. El viaje fue muy caro.'], [Kit::word('viajar', 'viajó'), Kit::word('el avión', 'avión'), Kit::word('el viaje', 'viaje'), Kit::form('fue')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.calor-playa', 'Yo ___ mucho calor en la playa.', 'I was very hot on the beach.', 'tuve', Kit::form('tuve', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.viste-excursion', '¿Qué ___ tú en la excursión?', 'What did you see on the excursion? (informal you)', 'viste', Kit::form('viste', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.pablo-playa', 'Pablo perdió su equipaje y su recuerdo en la playa.', 'Pablo lost his luggage and his souvenir on the beach.', [Kit::word('perder', 'perdió'), Kit::word('el equipaje', 'equipaje'), Kit::word('el recuerdo', 'recuerdo'), Kit::word('la playa', 'playa')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.viajamos-barco', 'Viajamos en barco a la playa y a la montaña.', 'We travelled by boat to the beach and to the mountains.', [Kit::word('viajar', 'viajamos'), Kit::word('el barco', 'barco'), Kit::word('la playa', 'playa'), Kit::word('la montaña', 'montaña')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.hice-excursion', 'Hice una excursión en barco.', 'I went on an excursion by boat.', [Kit::word('la excursión', 'excursión'), Kit::word('el barco', 'barco'), Kit::form('hice')], 'dictation', $set),
        ];
    }
}
