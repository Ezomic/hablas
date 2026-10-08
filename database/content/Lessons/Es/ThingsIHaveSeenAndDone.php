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

final class ThingsIHaveSeenAndDone implements UnitContent
{
    private const HA_NOTE = 'Ha with an h is a form of haber, the helper in ha escrito. It sounds the same as a (to), but here it is ha.';

    private const HECHO_NOTE = 'Hecho (done) is written with a silent h. It sounds the same as echo (I throw), but here it is hecho.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'things-i-have-seen-and-done';
    }

    public function words(): array
    {
        return [
            new WordData('escribir', cue: 'to write', forms: ['escrito'], note: 'The participle of escribir is escrito, not escribido: he escrito una carta.'),
            new WordData('abrir', cue: 'to open', forms: ['abierto'], note: 'The participle of abrir is abierto, not abrido: he abierto la puerta.'),
            new WordData('romper', cue: 'to break', forms: ['roto'], note: 'The participle of romper is roto, not rompido: he roto el vaso.'),
            new WordData('volver', cue: 'to come back (to return)', forms: ['vuelto'], note: 'The participle of volver is vuelto, not volvido. Spanish uses haber here, not a verb like zijn: he vuelto.'),
            new WordData('el viaje', cue: 'trip (journey)'),
            new WordData('el concierto', cue: 'concert'),
            new WordData('el teatro', cue: 'theatre'),
            new WordData('la playa', cue: 'beach'),
            new WordData('la carta', cue: 'letter', note: 'La carta is a letter that you write and send. It is also the word for the menu in a restaurant.'),
            new WordData('el mensaje', cue: 'message'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ana ha escrito una carta.', 'english' => 'Ana has written a letter.'],
            ['text' => 'Hemos visto un concierto.', 'english' => 'We have seen a concert.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-08', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-08', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-08', 'Released on the owner\'s instruction on 2026-10-08, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.escrito', 'Marta ha ___ una carta.', ['escrito', 'escribir'], 'escrito', Kit::form('escrito', true), 'Escribir has an irregular participle: escrito, not escribido. After ha you need the participle, not the infinitive escribir.', 'choose', 'Marta has written a letter.'),
            Kit::gap($stage, 'sentences.choose_gap.roto', 'Luis ha ___ el vaso.', ['roto', 'romper'], 'roto', Kit::form('roto', true), 'Romper has an irregular participle: roto, not rompido. After ha you need the participle, not the infinitive romper.', 'choose', 'Luis has broken the glass.'),
            Kit::gap($stage, 'sentences.choose_gap.dicho', 'Marta ha ___ hola a Pablo.', ['dicho', 'hecho'], 'dicho', Kit::form('dicho', true), 'Decir has the irregular participle dicho. Hecho is the participle of hacer (to do, to make).', 'choose', 'Marta has said hello to Pablo.'),
            Kit::gap($stage, 'sentences.choose_gap.visto', 'Hemos ___ un concierto.', ['visto', 'vuelto'], 'visto', Kit::form('visto', true), 'Ver has the irregular participle visto. Vuelto is the participle of volver (to come back).', 'choose', 'We have seen a concert.'),
            Kit::gap($stage, 'sentences.choose_gap.viaje', 'Pablo ha hecho un ___.', ['viaje', 'playa', 'carta'], 'viaje', Kit::word('el viaje', 'viaje'), 'Un goes with a masculine word, and viaje is masculine. Playa and carta take una.', 'choose', 'Pablo has taken a trip.'),
            Kit::gap($stage, 'sentences.choose_gap.playa', 'Luis ha vuelto de la ___.', ['playa', 'teatro', 'viaje'], 'playa', Kit::word('la playa', 'playa'), 'La goes with a feminine word, and playa is feminine. Teatro and viaje take el.', 'choose', 'Luis has come back from the beach.'),

            Kit::typeGap($stage, 'sentences.type_gap.abierto', 'Luis ha ___ la puerta.', 'Luis has opened the door.', 'abierto', Kit::word('abrir', 'abierto'), 'Abrir has an irregular participle: abierto, not abrido.'),
            Kit::typeGap($stage, 'sentences.type_gap.vuelto', 'Ana ha ___ de la playa.', 'Ana has come back from the beach.', 'vuelto', Kit::word('volver', 'vuelto'), 'Volver has an irregular participle: vuelto, not volvido.'),
            Kit::typeGap($stage, 'sentences.type_gap.hecho', 'Hemos ___ un viaje.', 'We have taken a trip.', 'hecho', Kit::form('hecho'), 'Hacer has the irregular participle hecho, not hacido.'),
            Kit::typeGap($stage, 'sentences.type_gap.puesto', 'Luis ha ___ el libro aquí.', 'Luis has put the book here.', 'puesto', Kit::form('puesto'), 'Poner has the irregular participle puesto, not ponido.'),
            Kit::typeGap($stage, 'sentences.type_gap.mensaje', 'Pablo ha escrito un ___.', 'Pablo has written a message.', 'mensaje', Kit::word('el mensaje', 'mensaje')),

            Kit::translate($stage, 'sentences.translate.concierto', 'We have seen a concert.', ['Hemos visto un concierto.', 'Nosotros hemos visto un concierto.'], [Kit::word('el concierto', 'concierto'), Kit::form('hemos visto')]),
            Kit::translate($stage, 'sentences.translate.ventana', 'Marta has broken the window.', ['Marta ha roto la ventana.'], [Kit::word('romper', 'roto'), Kit::form('ha roto')]),
            Kit::translate($stage, 'sentences.translate.carta', 'Have you opened the letter? (informal you)', ['¿Has abierto la carta?', '¿Tú has abierto la carta?'], [Kit::word('abrir', 'abierto'), Kit::word('la carta', 'carta'), Kit::form('has abierto')]),

            Kit::build($stage, 'sentences.build.escrito', 'I have written a message.', 'He escrito un mensaje.', ['ha'], [Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje'), Kit::form('he escrito')]),
            Kit::build($stage, 'sentences.build.puesto', 'We have put the book on the table.', 'Hemos puesto el libro en la mesa.', ['hecho'], [Kit::form('hemos puesto')]),
            Kit::build($stage, 'sentences.build.vuelto', 'They have come back from the trip.', 'Han vuelto del viaje.', ['de'], [Kit::word('volver', 'vuelto'), Kit::word('el viaje', 'viaje'), Kit::form('han vuelto')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.abierto', 'Ana ha abierto la ventana.', ['Ana has opened the window.', 'Ana is opening the window.', 'Ana has broken the window.', 'Ana has seen the window.'], 'Ana has opened the window.', [Kit::word('abrir', 'abierto'), Kit::form('ha abierto')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.concierto', 'Hemos visto el concierto.', ['We have seen the concert.', 'We are going to the concert.', 'We have written the concert.', 'They have seen the concert.'], 'We have seen the concert.', [Kit::word('el concierto', 'concierto'), Kit::form('hemos visto')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.teatro', 'Luis ha vuelto del teatro.', ['Luis has come back from the theatre.', 'Luis is going to the theatre.', 'Luis has come back from the beach.', 'Luis has written in the theatre.'], 'Luis has come back from the theatre.', [Kit::word('el teatro', 'teatro'), Kit::word('volver', 'vuelto'), Kit::form('ha vuelto')]),
            Kit::listenType($stage, 'sentences.listen_type.roto', 'Marta ha roto el vaso.', 'Marta has broken the glass.', [Kit::word('romper', 'roto'), Kit::form('ha roto')], homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.hecho', 'Hemos hecho un viaje.', 'We have taken a trip.', [Kit::word('el viaje', 'viaje'), Kit::form('hemos hecho')], homophoneNote: self::HECHO_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.escrito', 'Pablo ha escrito un mensaje.', 'Pablo has written a message.', [Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje'), Kit::form('ha escrito')], homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.vuelto', 'Hemos vuelto de la playa.', 'We have come back from the beach.', [Kit::word('volver', 'vuelto'), Kit::word('la playa', 'playa'), Kit::form('hemos vuelto')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.abierto', 'Luis ha abierto la puerta.', 'Luis has opened the door.', [Kit::word('abrir', 'abierto'), Kit::form('ha abierto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.teatro', 'Hemos visto un concierto en el teatro.', 'We have seen a concert in the theatre.', [Kit::word('el concierto', 'concierto'), Kit::word('el teatro', 'teatro'), Kit::form('hemos visto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.carta', 'He escrito una carta.', 'I have written a letter.', [Kit::word('la carta', 'carta'), Kit::word('escribir', 'escrito'), Kit::form('he escrito')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.roto', 'Pablo ha roto el vaso.', 'Pablo has broken the glass.', [Kit::word('romper', 'roto'), Kit::form('ha roto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.viaje', '¿Has hecho un viaje?', 'Have you taken a trip? (informal you)', [['sí', 'no'], ['he']], 'Sí, he hecho un viaje.', [Kit::word('el viaje', 'viaje'), Kit::form('he hecho')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.carta', '¿Has escrito la carta?', 'Have you written the letter? (informal you)', [['sí', 'no'], ['escrito']], 'Sí, he escrito la carta.', [Kit::word('la carta', 'carta'), Kit::word('escribir', 'escrito')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.teatro', '¿Ha vuelto Pablo del teatro?', 'Has Pablo come back from the theatre?', [['sí', 'no'], ['vuelto']], 'Sí, Pablo ha vuelto del teatro.', [Kit::word('el teatro', 'teatro'), Kit::word('volver', 'vuelto')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.playa', 'Read the conversation about Pablo and his trip.', [
                Kit::line('Ana', 'Pablo, ¿has vuelto de la playa?'),
                Kit::line('Pablo', 'Sí, he vuelto hoy. He hecho un viaje con Luis.'),
                Kit::line('Ana', '¿Y has visto un concierto?'),
                Kit::line('Pablo', 'Sí, hemos visto un concierto en el teatro. He escrito un mensaje a Marta.'),
            ], [
                Kit::question('Where has Pablo come back from?', ['From the beach', 'From the theatre', 'From the library'], 'From the beach'),
                Kit::question('What have Pablo and Luis seen?', ['A concert', 'A film', 'A letter'], 'A concert'),
                Kit::question('Who has Pablo written a message to?', ['Marta', 'Ana', 'Luis'], 'Marta'),
            ], [Kit::word('la playa', 'playa'), Kit::word('volver', 'vuelto'), Kit::word('el viaje', 'viaje'), Kit::word('el concierto', 'concierto'), Kit::word('el teatro', 'teatro'), Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje')], 'read'),
            Kit::gap($stage, 'task.choose_gap.puesto', 'Luis ha ___ el vaso en la mesa.', ['puesto', 'dicho'], 'puesto', Kit::form('puesto', true), 'Poner has the irregular participle puesto. Dicho is the participle of decir (to say).', 'read', 'Luis has put the glass on the table.'),
            Kit::gap($stage, 'task.choose_gap.abierto', 'Ana ha ___ la puerta y la ventana.', ['abierto', 'abrir'], 'abierto', Kit::form('abierto', true), 'Abrir has the irregular participle abierto. After ha you need the participle, not the infinitive abrir.', 'read', 'Ana has opened the door and the window.'),

            Kit::transform($stage, 'task.transform.escrito', 'Change the subject to I.', 'Ana ha escrito una carta.', ['He escrito una carta.', 'Yo he escrito una carta.'], [Kit::word('escribir', 'escrito'), Kit::word('la carta', 'carta'), Kit::form('he escrito', true)]),
            Kit::transform($stage, 'task.transform.hecho', 'Change the subject to we.', 'Pablo ha hecho un viaje.', ['Hemos hecho un viaje.', 'Nosotros hemos hecho un viaje.'], [Kit::word('el viaje', 'viaje'), Kit::form('hemos hecho')]),
            Kit::transform($stage, 'task.transform.visto', 'Ask a friend instead (informal you).', 'He visto el concierto.', ['¿Has visto el concierto?', '¿Tú has visto el concierto?'], [Kit::word('el concierto', 'concierto'), Kit::form('has visto')]),
            Kit::writeGuided($stage, 'task.write_guided.escrito-abierto', 'Say that you have written a message and opened a letter.', ['he escrito', 'un mensaje', 'he abierto', 'una carta'], 'He escrito un mensaje y he abierto una carta.', [
                ['forms' => ['escrito'], 'term' => 'escribir'],
                ['forms' => ['mensaje'], 'term' => 'el mensaje'],
                ['forms' => ['abierto'], 'term' => 'abrir'],
                ['forms' => ['carta'], 'term' => 'la carta'],
            ], [Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje'), Kit::word('abrir', 'abierto'), Kit::word('la carta', 'carta'), Kit::form('he escrito')]),
            Kit::writeGuided($stage, 'task.write_guided.vuelto-roto', 'Say that you have come back from the trip and broken a glass.', ['he vuelto', 'del viaje', 'he roto', 'un vaso'], 'He vuelto del viaje y he roto un vaso.', [
                ['forms' => ['vuelto'], 'term' => 'volver'],
                ['forms' => ['viaje'], 'term' => 'el viaje'],
                ['forms' => ['roto'], 'term' => 'romper'],
            ], [Kit::word('volver', 'vuelto'), Kit::word('el viaje', 'viaje'), Kit::word('romper', 'roto'), Kit::form('he roto')]),
            Kit::build($stage, 'task.build.visto', 'Ana has seen a concert in the theatre.', 'Ana ha visto un concierto en el teatro.', ['ver', 'he'], [Kit::word('el concierto', 'concierto'), Kit::word('el teatro', 'teatro'), Kit::form('ha visto')], 'write'),
            Kit::build($stage, 'task.build.puesto', 'Marta has put the letter here.', 'Marta ha puesto la carta aquí.', ['poner', 'han'], [Kit::word('la carta', 'carta'), Kit::form('ha puesto', true)], 'write'),
            Kit::build($stage, 'task.build.roto-abierto', 'I have broken the window and opened the door.', 'He roto la ventana y he abierto la puerta.', ['ha', 'hemos'], [Kit::word('romper', 'roto'), Kit::word('abrir', 'abierto'), Kit::form('he roto')], 'write'),
            Kit::translate($stage, 'task.translate.vuelto', 'Pablo and I have come back from the beach.', ['Pablo y yo hemos vuelto de la playa.'], [Kit::word('la playa', 'playa'), Kit::word('volver', 'vuelto'), Kit::form('hemos vuelto')], 'write'),
            Kit::translate($stage, 'task.translate.carta-mensaje', 'Ana has written a letter and Luis has opened a message.', ['Ana ha escrito una carta y Luis ha abierto un mensaje.'], [Kit::word('la carta', 'carta'), Kit::word('el mensaje', 'mensaje'), Kit::word('escribir', 'escrito'), Kit::word('abrir', 'abierto'), Kit::form('ha escrito', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.viaje', [
                Kit::line('Marta', 'Luis, ¿has hecho un viaje?'),
                Kit::line('Luis', 'Sí, he vuelto de la playa hoy.'),
                Kit::line('Marta', '¿Has escrito una carta a Ana?'),
                Kit::line('Luis', 'No, he escrito un mensaje.'),
            ], [
                Kit::question('Where has Luis come back from?', ['From the beach', 'From the theatre', 'From the concert'], 'From the beach'),
                Kit::question('What has Luis written to Ana?', ['A message', 'A letter', 'Nothing'], 'A message'),
                Kit::question('Has Luis taken a trip?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Has Luis written a letter?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el viaje', 'viaje'), Kit::word('la playa', 'playa'), Kit::word('volver', 'vuelto'), Kit::word('escribir', 'escrito'), Kit::word('la carta', 'carta'), Kit::word('el mensaje', 'mensaje'), Kit::form('has escrito')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.abierto', 'Pablo ha abierto la carta de Ana.', 'Pablo has opened the letter from Ana.', [Kit::word('abrir', 'abierto'), Kit::word('la carta', 'carta'), Kit::form('ha abierto')], 'listen', homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'task.listen_type.concierto', 'Luis y yo hemos visto un concierto.', 'Luis and I have seen a concert.', [Kit::word('el concierto', 'concierto'), Kit::form('hemos visto')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.roto', 'Marta ha vuelto y ha roto el vaso.', 'Marta has come back and has broken the glass.', [Kit::word('volver', 'vuelto'), Kit::word('romper', 'roto'), Kit::form('ha roto')], 'listen', homophoneNote: self::HA_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.escrito', '¿Qué has escrito?', 'What have you written? (informal you)', [['escrito'], ['carta', 'mensaje']], 'He escrito una carta.', [Kit::word('escribir', 'escrito'), Kit::word('la carta', 'carta'), Kit::word('el mensaje', 'mensaje')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.concierto', '¿Has visto un concierto?', 'Have you seen a concert? (informal you)', [['sí', 'no'], ['he']], 'Sí, he visto un concierto.', [Kit::word('el concierto', 'concierto'), Kit::form('he visto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ventana', '¿Has abierto la ventana?', 'Have you opened the window? (informal you)', [['sí', 'no'], ['he']], 'Sí, he abierto la ventana.', [Kit::word('abrir', 'abierto'), Kit::form('he abierto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.playa', '¿Has vuelto de la playa?', 'Have you come back from the beach? (informal you)', [['sí', 'no'], ['he']], 'Sí, he vuelto de la playa.', [Kit::word('la playa', 'playa'), Kit::word('volver', 'vuelto')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.carta', 'Marta ha escrito una carta a Luis.', 'Marta has written a letter to Luis.', [Kit::word('escribir', 'escrito'), Kit::word('la carta', 'carta'), Kit::form('ha escrito')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.playa', 'Han hecho un viaje a la playa.', 'They have taken a trip to the beach.', [Kit::word('el viaje', 'viaje'), Kit::word('la playa', 'playa'), Kit::form('han hecho')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.escrito', 'Ana has written a letter and a message.', ['Ana ha escrito una carta y un mensaje.'], [Kit::word('escribir', 'escrito'), Kit::word('la carta', 'carta'), Kit::word('el mensaje', 'mensaje'), Kit::form('ha escrito')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.concierto-playa', 'We have seen the concert on the beach.', ['Hemos visto el concierto en la playa.', 'Nosotros hemos visto el concierto en la playa.'], [Kit::word('el concierto', 'concierto'), Kit::word('la playa', 'playa'), Kit::form('hemos visto', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vuelto', 'Pablo has come back from the trip and from the theatre.', ['Pablo ha vuelto del viaje y del teatro.'], [Kit::word('volver', 'vuelto'), Kit::word('el viaje', 'viaje'), Kit::word('el teatro', 'teatro'), Kit::form('ha vuelto')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.roto-abierto', 'Luis has broken the glass and opened the letter.', ['Luis ha roto el vaso y ha abierto la carta.'], [Kit::word('romper', 'roto'), Kit::word('abrir', 'abierto'), Kit::word('la carta', 'carta'), Kit::form('ha roto')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.dicho', 'Ana ha ___ hola a Luis.', 'Ana has said hello to Luis.', 'dicho', Kit::form('dicho', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.roto', 'Ana ha ___ la ventana.', 'Ana has broken the window.', 'roto', Kit::word('romper', 'roto'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.hecho-visto', 'Pablo ha hecho un viaje y ha visto un concierto.', 'Pablo has taken a trip and has seen a concert.', [Kit::word('el viaje', 'viaje'), Kit::word('el concierto', 'concierto'), Kit::form('ha hecho')], 'dictation', $set, homophoneNote: self::HA_NOTE.' '.self::HECHO_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.mensaje-playa', 'Ana ha escrito un mensaje en la playa.', 'Ana has written a message on the beach.', [Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje'), Kit::word('la playa', 'playa')], 'dictation', $set, homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.vuelto-abierto', 'Luis ha vuelto y ha abierto la puerta.', 'Luis has come back and has opened the door.', [Kit::word('volver', 'vuelto'), Kit::word('abrir', 'abierto')], 'dictation', $set, homophoneNote: self::HA_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.teatro', [
                Kit::line('Pablo', 'Ana, ¿has vuelto del teatro?'),
                Kit::line('Ana', 'Sí, he visto un concierto con Marta.'),
                Kit::line('Pablo', '¿Y has escrito un mensaje a Luis?'),
                Kit::line('Ana', 'No, he escrito una carta.'),
            ], [
                Kit::question('Where has Ana come back from?', ['From the theatre', 'From the beach', 'From the library'], 'From the theatre'),
                Kit::question('What has Ana seen?', ['A concert', 'A film', 'A letter'], 'A concert'),
                Kit::question('What has Ana written?', ['A letter', 'A message', 'A book'], 'A letter'),
            ], [
                Kit::question('Who asks the questions?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Has Ana seen the concert with Marta?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el teatro', 'teatro'), Kit::word('el concierto', 'concierto'), Kit::word('volver', 'vuelto'), Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje'), Kit::word('la carta', 'carta')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ventana', 'Read the conversation.', [
                Kit::line('Luis', 'Marta, ¿has abierto la ventana?'),
                Kit::line('Marta', 'No, he abierto la puerta. Pablo ha roto la ventana.'),
                Kit::line('Luis', '¿Has puesto el libro en la mesa?'),
                Kit::line('Marta', 'Sí, he puesto el libro en la mesa.'),
            ], [
                Kit::question('What has Marta opened?', ['The door', 'The window', 'The book'], 'The door'),
                Kit::question('Who has broken the window?', ['Pablo', 'Marta', 'Luis'], 'Pablo'),
                Kit::question('Where has Marta put the book?', ['On the table', 'On the beach', 'In the theatre'], 'On the table'),
            ], [Kit::word('abrir', 'abierto'), Kit::word('romper', 'roto')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.puerta', '¿Has abierto la puerta?', 'Have you opened the door? (informal you)', [['sí', 'no'], ['he']], 'Sí, he abierto la puerta.', [Kit::word('abrir', 'abierto')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.concierto-teatro', '¿Has visto un concierto en el teatro?', 'Have you seen a concert in the theatre? (informal you)', [['sí', 'no'], ['he']], 'Sí, he visto un concierto.', [Kit::word('el concierto', 'concierto'), Kit::word('el teatro', 'teatro')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.mensaje-carta', '¿Has escrito un mensaje o una carta?', 'Have you written a message or a letter? (informal you)', [['he'], ['mensaje', 'carta']], 'He escrito un mensaje.', [Kit::word('el mensaje', 'mensaje'), Kit::word('la carta', 'carta'), Kit::word('escribir', 'escrito')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.abierto-escrito', 'Pablo has opened the letter and written a message.', ['Pablo ha abierto la carta y ha escrito un mensaje.'], [Kit::word('abrir', 'abierto'), Kit::word('la carta', 'carta'), Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje'), Kit::form('ha abierto')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vuelto-concierto', 'We have come back from the concert and from the beach.', ['Hemos vuelto del concierto y de la playa.', 'Nosotros hemos vuelto del concierto y de la playa.'], [Kit::word('volver', 'vuelto'), Kit::word('el concierto', 'concierto'), Kit::word('la playa', 'playa'), Kit::form('hemos vuelto')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.roto-teatro', 'Ana has broken a glass in the theatre.', ['Ana ha roto un vaso en el teatro.'], [Kit::word('romper', 'roto'), Kit::word('el teatro', 'teatro'), Kit::form('ha roto', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hecho-viaje', 'Luis has taken a trip and has seen a concert.', ['Luis ha hecho un viaje y ha visto un concierto.'], [Kit::word('el viaje', 'viaje'), Kit::word('el concierto', 'concierto'), Kit::form('ha hecho')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.dicho', 'Luis ha ___ adiós a Marta.', 'Luis has said goodbye to Marta.', 'dicho', Kit::form('dicho', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.concierto', 'Pablo ha visto un ___ con Ana.', 'Pablo has seen a concert with Ana.', 'concierto', Kit::word('el concierto', 'concierto'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.abierto-carta', 'Ana ha abierto la carta del teatro.', 'Ana has opened the letter from the theatre.', [Kit::word('abrir', 'abierto'), Kit::word('la carta', 'carta'), Kit::word('el teatro', 'teatro')], 'dictation', $set, homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.escrito-playa', 'Hemos escrito un mensaje en la playa.', 'We have written a message on the beach.', [Kit::word('escribir', 'escrito'), Kit::word('el mensaje', 'mensaje'), Kit::word('la playa', 'playa'), Kit::form('hemos escrito')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.vuelto-roto', 'Luis ha vuelto del viaje y ha roto un vaso.', 'Luis has come back from the trip and has broken a glass.', [Kit::word('volver', 'vuelto'), Kit::word('el viaje', 'viaje'), Kit::word('romper', 'roto')], 'dictation', $set, homophoneNote: self::HA_NOTE),
        ];
    }
}
