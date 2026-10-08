<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class WhenIWasAChild implements UnitContent
{
    private const A_NOTE = 'A without an h is the preposition a. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'when-i-was-a-child';
    }

    public function words(): array
    {
        return [
            new WordData('el colegio', cue: 'school (for children)'),
            new WordData('el juguete', cue: 'toy', forms: ['juguetes']),
            new WordData('el vecino', cue: 'neighbour (man)', accepted: ['la vecina'], forms: ['vecina', 'vecinos']),
            new WordData('la bicicleta', cue: 'bicycle'),
            new WordData('el niño', cue: 'boy (child)', accepted: ['la niña'], forms: ['niña', 'niños', 'niñas'], note: 'Niño is a boy, and la niña is a girl. The plural niños can also mean children in general.'),
            new WordData('vivir', cue: 'to live (in a place)', forms: ['vivo']),
            new WordData('pasear', cue: 'to go for a walk', forms: ['paseo']),
            new WordData('de pequeño', cue: 'as a child', accepted: ['de pequeña'], note: 'A man says de pequeño and a woman says de pequeña. Both are accepted.'),
            new WordData('siempre', cue: 'always'),
            new WordData('a menudo', cue: 'often'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'De pequeño vivía en un pueblo.', 'english' => 'As a child I lived in a village.'],
            ['text' => 'Siempre íbamos al colegio en bicicleta.', 'english' => 'We always went to school by bike.'],
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
            Kit::gap($stage, 'sentences.choose_gap.vivia', 'De pequeño ___ en un pueblo.', ['vivía', 'vivo'], 'vivía', Kit::form('vivía', true), 'De pequeño looks back at the past, so the imperfect vivía. Vivo is the present.', 'choose', 'As a child I lived in a village.'),
            Kit::gap($stage, 'sentences.choose_gap.era', 'De pequeño ___ bajo.', ['era', 'soy'], 'era', Kit::form('era', true), 'De pequeño is about the past, so the imperfect era. Soy is the present.', 'choose', 'As a child I was short.'),
            Kit::gap($stage, 'sentences.choose_gap.iba', 'De pequeño ___ al parque con mi madre.', ['iba', 'voy'], 'iba', Kit::form('iba', true), 'De pequeño is about the past, so the imperfect iba. Voy is the present.', 'choose', 'As a child I went to the park with my mother.'),
            Kit::gap($stage, 'sentences.choose_gap.juguete', 'Mi ___ era un coche.', ['juguete', 'colegio', 'vecino'], 'juguete', Kit::word('el juguete', 'juguete'), 'A car can be a toy, so juguete. A colegio is a school and a vecino is a person.', 'choose', 'My toy was a car.'),
            Kit::gap($stage, 'sentences.choose_gap.vecino', 'Mi ___ Luis vivía en mi calle.', ['vecino', 'juguete', 'colegio'], 'vecino', Kit::word('el vecino', 'vecino'), 'Luis is a person who lives near you, so vecino. A juguete is a toy and a colegio is a school.', 'choose', 'My neighbour Luis lived on my street.'),
            Kit::gap($stage, 'sentences.choose_gap.bicicleta', 'Íbamos al parque en ___.', ['bicicleta', 'vecino', 'niño'], 'bicicleta', Kit::word('la bicicleta', 'bicicleta'), 'En bicicleta means by bike, with no article. A vecino and a niño are people.', 'choose', 'We went to the park by bike.'),

            Kit::typeGap($stage, 'sentences.type_gap.vivias', 'Tú ___ en el pueblo.', 'You lived in the village.', 'vivías', Kit::form('vivías'), 'Tú goes with vivías: the imperfect of vivir ends in -ías for tú.'),
            Kit::typeGap($stage, 'sentences.type_gap.eras', 'Tú ___ un niño simpático.', 'You were a nice boy.', 'eras', Kit::form('eras'), 'Tú goes with eras, the imperfect of ser. Era is for yo, él or ella.'),
            Kit::typeGap($stage, 'sentences.type_gap.colegio', 'Mi hermano va al ___.', 'My brother goes to school.', 'colegio', Kit::word('el colegio', 'colegio')),
            Kit::typeGap($stage, 'sentences.type_gap.veia', 'De pequeño, yo ___ películas con mi padre.', 'As a child I watched films with my father.', 'veía', Kit::form('veía'), 'Yo goes with veía. Ver is irregular: it adds -ía to ve.'),
            Kit::typeGap($stage, 'sentences.type_gap.paseaban', 'Mis abuelos ___ por el parque.', 'My grandparents used to walk in the park.', 'paseaban', Kit::word('pasear', 'paseaban')),

            Kit::translate($stage, 'sentences.translate.futbol', 'As a child I always played football.', ['De pequeño siempre jugaba al fútbol.', 'De pequeño, yo siempre jugaba al fútbol.', 'De pequeño jugaba siempre al fútbol.', 'Siempre jugaba al fútbol de pequeño.', 'Yo siempre jugaba al fútbol de pequeño.', 'De pequeña siempre jugaba al fútbol.', 'De pequeña, yo siempre jugaba al fútbol.'], [Kit::word('de pequeño', null, ['de pequeña']), Kit::word('siempre'), Kit::form('jugaba')]),
            Kit::translate($stage, 'sentences.translate.vecino', 'My neighbour was nice.', ['Mi vecino era simpático.', 'Mi vecina era simpática.'], [Kit::word('el vecino', 'vecino', ['vecina']), Kit::form('era')]),
            Kit::translate($stage, 'sentences.translate.colegio', 'We used to go to school by bike.', ['Íbamos al colegio en bicicleta.', 'Nosotros íbamos al colegio en bicicleta.'], [Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('íbamos')]),

            Kit::build($stage, 'sentences.build.abuelos', 'My grandparents lived in a village.', 'Mis abuelos vivían en un pueblo.', ['vivía'], [Kit::word('vivir', 'vivían'), Kit::form('vivían')]),
            Kit::build($stage, 'sentences.build.madre', 'My mother used to walk in the park.', 'Mi madre paseaba por el parque.', ['en'], [Kit::word('pasear', 'paseaba')]),
            Kit::build($stage, 'sentences.build.cine', 'We often watched films at the cinema.', 'A menudo veíamos películas en el cine.', ['veía'], [Kit::word('a menudo'), Kit::form('veíamos')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vivia', 'De pequeño vivía en un pueblo.', ['As a child I lived in a village.', 'As a child I lived in a city.', 'Today I live in a village.', 'As a child I went to a village.'], 'As a child I lived in a village.', [Kit::word('de pequeño'), Kit::word('vivir', 'vivía'), Kit::form('vivía')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.juguete', 'Mi juguete era un coche.', ['My toy was a car.', 'My toy is a car.', 'My toy was a bicycle.', 'My neighbour has a car.'], 'My toy was a car.', [Kit::word('el juguete', 'juguete'), Kit::form('era')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.menudo', 'A menudo íbamos al parque.', ['We often went to the park.', 'We always went to the park.', 'I often go to the park.', 'We often went to the museum.'], 'We often went to the park.', [Kit::word('a menudo'), Kit::form('íbamos')]),
            Kit::listenType($stage, 'sentences.listen_type.nino', 'De pequeño, yo era un niño simpático.', 'As a child I was a nice boy.', [Kit::word('de pequeño'), Kit::word('el niño', 'niño'), Kit::form('era')]),
            Kit::listenType($stage, 'sentences.listen_type.siempre', 'Siempre paseaba con mis abuelos.', 'I always walked with my grandparents.', [Kit::word('siempre'), Kit::word('pasear', 'paseaba'), Kit::form('paseaba')]),
            Kit::listenType($stage, 'sentences.listen_type.vecina', 'Mi vecina vivía en mi calle.', 'My neighbour lived on my street.', [Kit::word('el vecino', 'vecina'), Kit::word('vivir', 'vivía'), Kit::form('vivía')]),
            Kit::listenType($stage, 'sentences.listen_type.colegio', 'Íbamos al colegio a las ocho.', 'We went to school at eight.', [Kit::word('el colegio', 'colegio'), Kit::form('íbamos')], homophoneNote: self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.vivia', 'Vivía en un pueblo con mis abuelos.', 'I lived in a village with my grandparents.', [Kit::word('vivir', 'vivía'), Kit::form('vivía')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paseabamos', 'A menudo paseábamos por el parque.', 'We often walked in the park.', [Kit::word('a menudo'), Kit::word('pasear', 'paseábamos'), Kit::form('paseábamos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ibamos', 'Siempre íbamos al colegio en bicicleta.', 'We always went to school by bike.', [Kit::word('siempre'), Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('íbamos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tenia', 'Mi vecino tenía un juguete.', 'My neighbour had a toy.', [Kit::word('el vecino', 'vecino'), Kit::word('el juguete', 'juguete'), Kit::form('tenía')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vivias', '¿Dónde vivías?', 'Where did you live?', [['vivía'], ['pueblo', 'ciudad', 'casa', 'piso']], 'Vivía en un pueblo.', [Kit::word('vivir', 'vivía'), Kit::form('vivía')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ibas', '¿Ibas al colegio?', 'Did you go to school?', [['sí', 'no'], ['iba', 'colegio']], 'Sí, iba al colegio.', [Kit::word('el colegio', 'colegio'), Kit::form('iba')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.eras', '¿Eras un niño simpático?', 'Were you a nice child?', [['sí', 'no'], ['era', 'niño', 'simpático']], 'Sí, era un niño simpático.', [Kit::word('el niño', 'niño'), Kit::form('era')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.infancia', 'Read the conversation about Pablo and Ana as children.', [
                Kit::line('Ana', 'Pablo, ¿dónde vivías de pequeño?'),
                Kit::line('Pablo', 'Vivía en un pueblo con mis abuelos. Íbamos al colegio en bicicleta.'),
                Kit::line('Ana', 'Yo vivía en la ciudad. Siempre iba al colegio con mi madre.'),
                Kit::line('Pablo', 'A menudo paseábamos con el vecino y veíamos películas.'),
            ], [
                Kit::question('Where did Pablo live as a child?', ['In a village', 'In a city', 'In a park'], 'In a village'),
                Kit::question('How did Pablo go to school?', ['By bike', 'By train', 'By bus'], 'By bike'),
                Kit::question('Who did Ana go to school with?', ['With her mother', 'With her father', 'With her neighbour'], 'With her mother'),
            ], [Kit::word('de pequeño'), Kit::word('vivir', 'vivía'), Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::word('siempre'), Kit::word('a menudo'), Kit::word('pasear', 'paseábamos'), Kit::word('el vecino', 'vecino')], 'read'),
            Kit::gap($stage, 'task.choose_gap.profesor', 'Hoy soy profesor, pero de pequeño ___ un niño bajo.', ['era', 'soy'], 'era', Kit::form('era', true), 'De pequeño is about the past, so era. Soy is the present, as in hoy soy profesor.', 'read', 'Today I am a teacher, but as a child I was a short boy.'),
            Kit::gap($stage, 'task.choose_gap.nino', 'De pequeño era un ___ simpático.', ['niño', 'colegio', 'juguete'], 'niño', Kit::word('el niño', 'niño'), 'You can be a niño, a boy. A colegio is a school and a juguete is a toy.', 'read', 'As a child I was a nice boy.'),

            Kit::transform($stage, 'task.transform.nosotros', 'Change the subject to we.', 'Vivía en un pueblo.', ['Vivíamos en un pueblo.', 'Nosotros vivíamos en un pueblo.'], [Kit::word('vivir', 'vivíamos'), Kit::form('vivíamos')]),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to you (tú).', 'Iba al colegio en bicicleta.', ['Ibas al colegio en bicicleta.', 'Tú ibas al colegio en bicicleta.'], [Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('ibas')]),
            Kit::transform($stage, 'task.transform.pequeno', 'Say it about the past, with de pequeño.', 'Mi vecino es simpático.', ['De pequeño mi vecino era simpático.', 'Mi vecino era simpático de pequeño.', 'De pequeño, mi vecino era simpático.'], [Kit::word('de pequeño'), Kit::word('el vecino', 'vecino'), Kit::form('era', true)]),
            Kit::writeGuided($stage, 'task.write_guided.colegio', 'Say where you lived as a child and that you went to school by bike.', ['de pequeño', 'vivía', 'iba', 'colegio', 'bicicleta'], 'De pequeño vivía en un pueblo. Iba al colegio en bicicleta.', [
                ['forms' => ['vivía', 'vivíamos', 'vivían'], 'term' => 'vivir'],
                ['forms' => ['colegio'], 'term' => 'el colegio'],
                ['forms' => ['bicicleta'], 'term' => 'la bicicleta'],
                ['forms' => ['iba', 'íbamos', 'ibas', 'iban'], 'term' => null],
            ], [Kit::word('de pequeño'), Kit::word('vivir', 'vivía'), Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('iba')]),
            Kit::writeGuided($stage, 'task.write_guided.parque', 'Say that you always walked in the park and often watched films.', ['siempre', 'paseaba', 'a menudo', 'veía'], 'Siempre paseaba por el parque y a menudo veía películas.', [
                ['forms' => ['siempre'], 'term' => 'siempre'],
                ['forms' => ['paseaba', 'paseábamos', 'paseaban'], 'term' => 'pasear'],
                ['forms' => ['menudo'], 'term' => 'a menudo'],
                ['forms' => ['veía', 'veíamos'], 'term' => null],
            ], [Kit::word('siempre'), Kit::word('pasear', 'paseaba'), Kit::word('a menudo'), Kit::form('veía')]),
            Kit::build($stage, 'task.build.abuelos-parque', 'My grandparents used to live near the park.', 'Mis abuelos vivían cerca del parque.', ['vivía', 'lejos'], [Kit::word('vivir', 'vivían'), Kit::form('vivían')], 'write'),
            Kit::build($stage, 'task.build.luis', 'As a child I always walked with Luis, my neighbour.', 'De pequeño siempre paseaba con Luis, mi vecino.', ['paseo', 'vecina'], [Kit::word('de pequeño'), Kit::word('siempre'), Kit::word('pasear', 'paseaba'), Kit::word('el vecino', 'vecino')], 'write'),
            Kit::build($stage, 'task.build.casa', 'We often watched films at my house.', 'A menudo veíamos películas en mi casa.', ['veía', 'tu'], [Kit::word('a menudo'), Kit::form('veíamos')], 'write'),
            Kit::translate($stage, 'task.translate.juguetes', 'As a child I often played with my toys in the garden.', ['De pequeño jugaba a menudo con mis juguetes en el jardín.', 'De pequeño a menudo jugaba con mis juguetes en el jardín.', 'A menudo jugaba con mis juguetes en el jardín de pequeño.', 'De pequeña jugaba a menudo con mis juguetes en el jardín.', 'De pequeña a menudo jugaba con mis juguetes en el jardín.'], [Kit::word('de pequeño', null, ['de pequeña']), Kit::word('a menudo'), Kit::word('el juguete', 'juguetes'), Kit::form('jugaba')], 'write'),
            Kit::translate($stage, 'task.translate.pablo', 'Pablo and I were children and we lived in the village.', ['Pablo y yo éramos niños y vivíamos en el pueblo.'], [Kit::word('el niño', 'niños'), Kit::word('vivir', 'vivíamos'), Kit::form('éramos')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.infancia', [
                Kit::line('Marta', 'Luis, ¿dónde vivías de pequeño?'),
                Kit::line('Luis', 'Vivía en una ciudad, pero mis abuelos vivían en un pueblo.'),
                Kit::line('Marta', '¿Ibas al colegio en bicicleta?'),
                Kit::line('Luis', 'No, íbamos en autobús. ¿Y tú?'),
                Kit::line('Marta', 'Yo siempre paseaba con mi madre. A menudo íbamos al parque.'),
            ], [
                Kit::question('Where did Luis live as a child?', ['In a city', 'In a village', 'In a park'], 'In a city'),
                Kit::question('Where did his grandparents live?', ['In a village', 'In a city', 'In a school'], 'In a village'),
                Kit::question('How did Luis go to school?', ['By bus', 'By bike', 'By car'], 'By bus'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is the conversation about the past?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('de pequeño'), Kit::word('vivir', 'vivía'), Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::word('siempre'), Kit::word('pasear', 'paseaba'), Kit::word('a menudo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.vecino', 'Siempre veíamos películas con mi vecino.', 'We always watched films with my neighbour.', [Kit::word('siempre'), Kit::word('el vecino', 'vecino'), Kit::form('veíamos')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ninos', 'Los niños iban al colegio en bicicleta.', 'The children went to school by bike.', [Kit::word('el niño', 'niños'), Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('iban')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.parque', 'A menudo paseaba por el parque con mi hermana.', 'I often walked in the park with my sister.', [Kit::word('a menudo'), Kit::word('pasear', 'paseaba'), Kit::form('paseaba')], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.vecino', '¿Dónde vivía tu vecino?', 'Where did your neighbour live?', [['vivía'], ['calle', 'casa', 'pueblo', 'ciudad', 'piso']], 'Mi vecino vivía en mi calle.', [Kit::word('el vecino', 'vecino'), Kit::word('vivir', 'vivía')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.colegio', '¿Cómo ibas al colegio?', 'How did you go to school?', [['iba', 'íbamos'], ['bicicleta', 'autobús', 'coche', 'tren']], 'Iba al colegio en bicicleta.', [Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('iba')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pueblo', '¿Vivías en un pueblo?', 'Did you live in a village?', [['sí', 'no'], ['vivía', 'pueblo', 'ciudad']], 'Sí, vivía en un pueblo.', [Kit::word('vivir', 'vivía')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.simpatico', '¿Era simpático tu vecino?', 'Was your neighbour nice?', [['sí', 'no'], ['era', 'simpático']], 'Sí, mi vecino era simpático.', [Kit::word('el vecino', 'vecino')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.juguetes', 'De pequeño jugaba con mis juguetes.', 'As a child I played with my toys.', [Kit::word('de pequeño'), Kit::word('el juguete', 'juguetes'), Kit::form('jugaba')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.vecino', 'Mi vecino paseaba con mi madre.', 'My neighbour walked with my mother.', [Kit::word('el vecino', 'vecino'), Kit::word('pasear', 'paseaba'), Kit::form('paseaba')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.vivia', 'As a child I lived in a village. I went to school by bike.', ['De pequeño vivía en un pueblo. Iba al colegio en bicicleta.', 'De pequeña vivía en un pueblo. Iba al colegio en bicicleta.'], [Kit::word('de pequeño', null, ['de pequeña']), Kit::word('vivir', 'vivía'), Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('iba')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paseabamos', 'We often walked in the park with the neighbour.', ['A menudo paseábamos por el parque con el vecino.', 'A menudo paseábamos por el parque con la vecina.', 'Paseábamos a menudo por el parque con el vecino.', 'Paseábamos a menudo por el parque con la vecina.'], [Kit::word('a menudo'), Kit::word('pasear', 'paseábamos'), Kit::word('el vecino', 'vecino', ['vecina']), Kit::form('paseábamos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.alto', 'Today I am tall, but as a child I was short.', ['Hoy soy alto, pero de pequeño era bajo.', 'Hoy soy alta, pero de pequeña era baja.'], [Kit::word('de pequeño', null, ['de pequeña']), Kit::form('era', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.jugabamos', 'We were children and we played in the garden.', ['Éramos niños y jugábamos en el jardín.', 'Nosotros éramos niños y jugábamos en el jardín.'], [Kit::word('el niño', 'niños'), Kit::form('jugábamos')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.juguete', 'Mi hermano tenía un ___.', 'My brother had a toy.', 'juguete', Kit::word('el juguete', 'juguete'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vecino', 'Luis, mi ___, vivía en mi calle.', 'Luis, my neighbour, lived on my street.', 'vecino', Kit::word('el vecino', 'vecino'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.veia', 'De pequeño, mi vecino veía películas en mi casa.', 'As a child my neighbour watched films at my house.', [Kit::word('de pequeño'), Kit::word('el vecino', 'vecino'), Kit::form('veía')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.ibamos', 'A menudo íbamos al parque en bicicleta.', 'We often went to the park by bike.', [Kit::word('a menudo'), Kit::word('la bicicleta', 'bicicleta'), Kit::form('íbamos', true)], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.ninos', 'Marta siempre paseaba con los niños del pueblo.', 'Marta always walked with the children of the village.', [Kit::word('siempre'), Kit::word('pasear', 'paseaba'), Kit::word('el niño', 'niños')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.infancia', [
                Kit::line('Marta', 'Ana, ¿dónde vivías de pequeña?'),
                Kit::line('Ana', 'Vivía con mis abuelos en un pueblo.'),
                Kit::line('Marta', '¿Y ibas al colegio en bicicleta?'),
                Kit::line('Ana', 'No, siempre iba con mi madre.'),
            ], [
                Kit::question('Where did Ana live as a child?', ['In a village', 'In a city', 'In a school'], 'In a village'),
                Kit::question('Who did Ana live with?', ['With her grandparents', 'With her neighbour', 'With her brother'], 'With her grandparents'),
                Kit::question('Who did Ana go to school with?', ['With her mother', 'With her grandparents', 'With her neighbour'], 'With her mother'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Ana', 'Nobody'], 'Marta'),
                Kit::question('Does Ana go to school by bike?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('de pequeño', 'de pequeña'), Kit::word('vivir', 'vivía'), Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta'), Kit::word('siempre')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.juguetes', 'Read the conversation.', [
                Kit::line('Pablo', 'De pequeño tenía un juguete: un coche.'),
                Kit::line('Luis', 'Yo tenía una bicicleta. A menudo paseaba con mi vecino.'),
            ], [
                Kit::question('What toy did Pablo have?', ['A car', 'A bicycle', 'A book'], 'A car'),
                Kit::question('Who did Luis walk with?', ['With his neighbour', 'With his brother', 'With his mother'], 'With his neighbour'),
            ], [Kit::word('de pequeño'), Kit::word('el juguete', 'juguete'), Kit::word('la bicicleta', 'bicicleta'), Kit::word('a menudo'), Kit::word('pasear', 'paseaba'), Kit::word('el vecino', 'vecino')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vivias', '¿Dónde vivías de pequeño?', 'Where did you live as a child?', [['vivía'], ['pueblo', 'ciudad', 'casa', 'piso']], 'Vivía en una ciudad.', [Kit::word('vivir', 'vivía')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.bicicleta', '¿Ibas en bicicleta al colegio?', 'Did you go to school by bike?', [['sí', 'no'], ['iba', 'colegio', 'bicicleta']], 'Sí, iba al colegio en bicicleta.', [Kit::word('el colegio', 'colegio'), Kit::word('la bicicleta', 'bicicleta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.siempre', '¿Siempre ibas al parque?', 'Did you always go to the park?', [['sí', 'no'], ['iba', 'siempre', 'parque']], 'Sí, siempre iba al parque.', [Kit::word('siempre')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.paseaba', 'As a child I always walked in the park.', ['De pequeño siempre paseaba por el parque.', 'De pequeño, yo siempre paseaba por el parque.', 'De pequeño paseaba siempre por el parque.', 'Siempre paseaba por el parque de pequeño.', 'De pequeña siempre paseaba por el parque.'], [Kit::word('de pequeño', null, ['de pequeña']), Kit::word('siempre'), Kit::word('pasear', 'paseaba'), Kit::form('paseaba')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.juguetes', 'My neighbour often played with my toys.', ['Mi vecino jugaba a menudo con mis juguetes.', 'A menudo mi vecino jugaba con mis juguetes.', 'Mi vecino jugaba con mis juguetes a menudo.', 'Mi vecina jugaba a menudo con mis juguetes.', 'A menudo mi vecina jugaba con mis juguetes.', 'Mi vecina jugaba con mis juguetes a menudo.'], [Kit::word('el vecino', 'vecino', ['vecina']), Kit::word('a menudo'), Kit::word('el juguete', 'juguetes'), Kit::form('jugaba')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vivo', 'Today I live here, but as a child I lived there.', ['Hoy vivo aquí, pero de pequeño vivía allí.', 'Hoy vivo aquí, pero de pequeña vivía allí.'], [Kit::word('de pequeño', null, ['de pequeña']), Kit::word('vivir', 'vivía'), Kit::form('vivía', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ninos', 'We were children and we always went to school.', ['Éramos niños y siempre íbamos al colegio.', 'Nosotros éramos niños y siempre íbamos al colegio.'], [Kit::word('el niño', 'niños'), Kit::word('siempre'), Kit::word('el colegio', 'colegio'), Kit::form('íbamos')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vecina', 'Mi ___ Marta vivía cerca de mi casa.', 'My neighbour Marta lived near my house.', 'vecina', Kit::word('el vecino', 'vecina'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.bicicleta', 'Pablo iba al parque en ___.', 'Pablo went to the park by bike.', 'bicicleta', Kit::word('la bicicleta', 'bicicleta'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.juguetes', 'Mis juguetes eran de mi hermano.', 'My toys were my brother\'s.', [Kit::word('el juguete', 'juguetes'), Kit::form('eran')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.abuelos', 'Mis abuelos vivían cerca del colegio.', 'My grandparents lived near the school.', [Kit::word('vivir', 'vivían'), Kit::word('el colegio', 'colegio'), Kit::form('vivían', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.nino', 'A menudo el niño paseaba en bicicleta.', 'The boy often went for a bike ride.', [Kit::word('a menudo'), Kit::word('el niño', 'niño'), Kit::word('pasear', 'paseaba'), Kit::word('la bicicleta', 'bicicleta')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
