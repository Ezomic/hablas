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

final class WeatherAndSeasons implements UnitContent
{
    private const HAY_NOTE = 'Hay (there is, there are) sounds just like the exclamation ay. In a sentence about what is there, it is hay.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'weather-and-seasons';
    }

    public function words(): array
    {
        return [
            new WordData('el tiempo', cue: 'weather', note: 'Tiempo also means time, but here it is the weather: ¿Qué tiempo hace? Do not mix it up with el tiempo libre (free time).'),
            new WordData('el frío', cue: 'cold (cold weather)'),
            new WordData('el calor', cue: 'heat (hot weather)'),
            new WordData('el sol', cue: 'sun'),
            new WordData('la lluvia', cue: 'rain (the noun)'),
            new WordData('la nube', cue: 'cloud', forms: ['nubes']),
            new WordData('el invierno', cue: 'winter'),
            new WordData('el verano', cue: 'summer'),
            new WordData('la primavera', cue: 'spring (the season)'),
            new WordData('el otoño', cue: 'autumn (fall)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Hace frío en invierno.', 'english' => 'It is cold in winter.'],
            ['text' => 'Llueve y hay nubes.', 'english' => 'It rains and there are clouds.'],
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

        return [
            Kit::gap($stage, 'sentences.choose_gap.invierno', 'En invierno ___ frío.', ['hace', 'es'], 'hace', Kit::form('hace', true), 'Spanish says it makes cold: hace frío. Es would not describe the weather.', 'choose', 'In winter it is cold.'),
            Kit::gap($stage, 'sentences.choose_gap.yo-frio', 'Yo ___ frío.', ['tengo', 'hace'], 'tengo', Kit::form('tengo', true), 'When you feel cold yourself, you say tengo frío. Hace frío is only for the weather.', 'choose', 'I am cold.'),
            Kit::gap($stage, 'sentences.choose_gap.nubes', '___ nubes.', ['Hay', 'Hace'], 'Hay', Kit::form('hay'), 'Clouds are things that are there, so you say hay nubes. Hace is for cold, heat and sun.', 'choose', 'There are clouds.'),
            Kit::gap($stage, 'sentences.choose_gap.calor', 'En verano hace ___.', ['calor', 'nube', 'lluvia'], 'calor', Kit::word('el calor', 'calor'), 'Hace calor means it is hot. Hace nube and hace lluvia do not exist: clouds use hay and rain uses llueve.', 'choose', 'In summer it is hot.'),
            Kit::gap($stage, 'sentences.choose_gap.otono-llueve', 'En otoño ___ y hay nubes.', ['llueve', 'hace'], 'llueve', Kit::form('llueve', true), 'Rain has its own verb: llueve. You do not say hace for rain.', 'choose', 'In autumn it rains and there are clouds.'),
            Kit::gap($stage, 'sentences.choose_gap.sol', 'En primavera hace ___.', ['sol', 'nube', 'lluvia'], 'sol', Kit::word('el sol', 'sol'), 'Hace sol means it is sunny. Nube and lluvia do not go after hace.', 'choose', 'In spring it is sunny.'),

            Kit::typeGap($stage, 'sentences.type_gap.primavera-tiempo', 'En primavera ___ buen tiempo.', 'In spring the weather is nice.', 'hace', Kit::form('hace'), 'The weather is nice is hace buen tiempo, with hace as in hace frío.'),
            Kit::typeGap($stage, 'sentences.type_gap.que-tiempo', '¿Qué ___ hace en invierno?', 'What is the weather like in winter?', 'tiempo', Kit::word('el tiempo', 'tiempo')),
            Kit::typeGap($stage, 'sentences.type_gap.nubes', 'Hay sol y no hay ___.', 'There is sun and there are no clouds.', 'nubes', Kit::word('la nube', 'nubes')),
            Kit::typeGap($stage, 'sentences.type_gap.llueve', 'Allí ___ en otoño.', 'It rains there in autumn.', 'llueve', Kit::form('llueve'), 'It rains is one verb in Spanish: llueve.'),
            Kit::typeGap($stage, 'sentences.type_gap.invierno', 'En ___ hace frío.', 'In winter it is cold.', 'invierno', Kit::word('el invierno', 'invierno')),

            Kit::translate($stage, 'sentences.translate.calor-verano', 'It is hot in summer.', ['Hace calor en verano.', 'En verano hace calor.'], [Kit::word('el calor', 'calor'), Kit::word('el verano', 'verano'), Kit::form('hace')]),
            Kit::translate($stage, 'sentences.translate.tengo-frio', 'I am cold.', ['Tengo frío.', 'Yo tengo frío.'], [Kit::word('el frío', 'frío'), Kit::form('tengo', true)]),
            Kit::translate($stage, 'sentences.translate.sol-primavera', 'Is it sunny in spring?', ['¿Hace sol en primavera?', '¿Hay sol en primavera?'], [Kit::word('el sol', 'sol'), Kit::word('la primavera', 'primavera'), Kit::form('hace', alternates: ['hay'])]),

            Kit::build($stage, 'sentences.build.que-tiempo', 'What is the weather like in winter?', '¿Qué tiempo hace en invierno?', ['es'], [Kit::word('el tiempo', 'tiempo'), Kit::word('el invierno', 'invierno'), Kit::form('hace')]),
            Kit::build($stage, 'sentences.build.una-nube', 'There is a cloud.', 'Hay una nube.', ['hace'], [Kit::word('la nube', 'nube'), Kit::form('hay')]),
            Kit::build($stage, 'sentences.build.no-tengo-calor', 'I am not hot.', 'No tengo calor.', ['es'], [Kit::word('el calor', 'calor'), Kit::form('tengo', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.calor-verano', 'Hace calor en verano.', ['It is hot in summer.', 'It is cold in summer.', 'It is hot in winter.', 'It rains in summer.'], 'It is hot in summer.', [Kit::word('el calor', 'calor'), Kit::word('el verano', 'verano'), Kit::form('hace')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.otono', 'En otoño llueve.', ['It rains in autumn.', 'It is sunny in autumn.', 'It rains in spring.', 'It is cold in autumn.'], 'It rains in autumn.', [Kit::word('el otoño', 'otoño'), Kit::form('llueve')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.nubes-primavera', '¿Hay nubes en primavera?', ['Are there clouds in spring?', 'Is it sunny in spring?', 'Are there clouds in autumn?', 'Does it rain in spring?'], 'Are there clouds in spring?', [Kit::word('la primavera', 'primavera'), Kit::word('la nube', 'nubes'), Kit::form('hay')]),
            Kit::listenType($stage, 'sentences.listen_type.invierno-frio', 'En invierno hace frío.', 'In winter it is cold.', [Kit::word('el invierno', 'invierno'), Kit::word('el frío', 'frío'), Kit::form('hace')]),
            Kit::listenType($stage, 'sentences.listen_type.verano-sol', 'En verano hay sol y no llueve.', 'In summer there is sun and it does not rain.', [Kit::word('el verano', 'verano'), Kit::word('el sol', 'sol'), Kit::form('hay')], homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.lluvia-frio', 'Con la lluvia hace frío.', 'With the rain it is cold.', [Kit::word('la lluvia', 'lluvia'), Kit::word('el frío', 'frío'), Kit::form('hace')]),
            Kit::listenType($stage, 'sentences.listen_type.tiempo-otono', '¿Qué tiempo hace en otoño?', 'What is the weather like in autumn?', [Kit::word('el tiempo', 'tiempo'), Kit::word('el otoño', 'otoño'), Kit::form('hace')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.buen-tiempo', 'Hace buen tiempo aquí en primavera.', 'The weather is nice here in spring.', [Kit::word('el tiempo', 'tiempo'), Kit::word('la primavera', 'primavera'), Kit::form('hace buen tiempo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tengo-calor', 'Tengo calor en verano.', 'I am hot in summer.', [Kit::word('el calor', 'calor'), Kit::word('el verano', 'verano'), Kit::form('tengo', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.lluvia-nubes', 'Hay lluvia y nubes en otoño.', 'There is rain and there are clouds in autumn.', [Kit::word('el otoño', 'otoño'), Kit::word('la lluvia', 'lluvia'), Kit::word('la nube', 'nubes'), Kit::form('hay')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.invierno-llueve', 'En invierno llueve y hace frío.', 'In winter it rains and it is cold.', [Kit::word('el invierno', 'invierno'), Kit::word('el frío', 'frío'), Kit::form('llueve')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.verano', '¿Qué tiempo hace en verano?', 'What is the weather like in summer?', [['hace', 'hay'], ['calor', 'sol', 'buen']], 'En verano hace calor.', [Kit::word('el calor', 'calor'), Kit::form('hace')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.invierno', '¿Hace frío en invierno?', 'Is it cold in winter?', [['sí', 'no'], ['hace', 'hay', 'llueve']], 'Sí, hace frío.', [Kit::word('el frío', 'frío'), Kit::word('el invierno', 'invierno'), Kit::form('hace')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.llueve', '¿Llueve en otoño?', 'Does it rain in autumn?', [['sí', 'no'], ['llueve', 'lluvia']], 'Sí, llueve en otoño.', [Kit::word('el otoño', 'otoño'), Kit::form('llueve')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tiempo-verano', 'Read the conversation about the weather.', [
                Kit::line('Ana', 'Pablo, ¿qué tiempo hace en verano?'),
                Kit::line('Pablo', 'Hace calor y hay sol. Voy a la playa. ¿Y en invierno?'),
                Kit::line('Ana', 'Hace frío y llueve. Estoy en casa.'),
                Kit::line('Pablo', '¿Y en primavera?'),
                Kit::line('Ana', 'Hace buen tiempo.'),
            ], [
                Kit::question('What is the weather like in summer?', ['Hot and sunny', 'Cold and rainy', 'Cloudy'], 'Hot and sunny'),
                Kit::question('What does Pablo do in summer?', ['He goes to the beach.', 'He stays at home.', 'He goes to the cinema.'], 'He goes to the beach.'),
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
            ], [Kit::word('el tiempo', 'tiempo'), Kit::word('el verano', 'verano'), Kit::word('el calor', 'calor'), Kit::word('el sol', 'sol'), Kit::word('el invierno', 'invierno'), Kit::word('el frío', 'frío'), Kit::word('la primavera', 'primavera')], 'read', glosses: ['playa' => 'beach', 'casa' => 'home']),
            Kit::gap($stage, 'task.choose_gap.aqui-calor', 'Aquí ___ calor y hay sol.', ['hace', 'es'], 'hace', Kit::form('hace', true), 'Spanish says it makes hot: hace calor. Es would not describe the weather.', 'read', 'It is hot here and there is sun.'),
            Kit::gap($stage, 'task.choose_gap.buen-tiempo', 'Aquí hace buen ___.', ['tiempo', 'lluvia', 'nube'], 'tiempo', Kit::word('el tiempo', 'tiempo'), 'Hace buen tiempo means the weather is nice. Buen goes with masculine words, and lluvia and nube are feminine.', 'read', 'The weather is nice here.'),

            Kit::transform($stage, 'task.transform.pregunta-sol', 'Now ask a question.', 'Hace sol en verano.', ['¿Hace sol en verano?', '¿En verano hace sol?'], [Kit::word('el sol', 'sol'), Kit::word('el verano', 'verano'), Kit::form('hace')]),
            Kit::transform($stage, 'task.transform.tengo-frio', 'Now say that you are cold.', 'Hace frío.', ['Tengo frío.', 'Yo tengo frío.'], [Kit::word('el frío', 'frío'), Kit::form('tengo', true)]),
            Kit::transform($stage, 'task.transform.nubes', 'Change it to several clouds.', 'Hay una nube.', ['Hay nubes.'], [Kit::word('la nube', 'nubes'), Kit::form('hay')]),
            Kit::writeGuided($stage, 'task.write_guided.verano', 'Say that in summer it is hot and there is sun.', ['verano', 'hace', 'calor', 'sol'], 'En verano hace calor y hay sol.', [
                ['forms' => ['verano'], 'term' => 'el verano'],
                ['forms' => ['calor'], 'term' => 'el calor'],
                ['forms' => ['sol'], 'term' => 'el sol'],
            ], [Kit::word('el verano', 'verano'), Kit::word('el calor', 'calor'), Kit::word('el sol', 'sol')]),
            Kit::writeGuided($stage, 'task.write_guided.otono', 'Say that in autumn it rains and there are clouds.', ['otoño', 'llueve', 'hay', 'nubes'], 'En otoño llueve y hay nubes.', [
                ['forms' => ['otoño'], 'term' => 'el otoño'],
                ['forms' => ['llueve'], 'term' => null],
                ['forms' => ['nubes'], 'term' => 'la nube'],
            ], [Kit::word('el otoño', 'otoño'), Kit::word('la nube', 'nubes'), Kit::form('llueve')]),
            Kit::build($stage, 'task.build.que-tiempo-primavera', 'What is the weather like in spring?', '¿Qué tiempo hace en primavera?', ['está', 'son'], [Kit::word('el tiempo', 'tiempo'), Kit::word('la primavera', 'primavera'), Kit::form('hace')], 'write'),
            Kit::build($stage, 'task.build.invierno-nubes', 'In winter there are clouds.', 'En invierno hay nubes.', ['hace', 'es'], [Kit::word('el invierno', 'invierno'), Kit::word('la nube', 'nubes'), Kit::form('hay')], 'write'),
            Kit::build($stage, 'task.build.verano-buen-tiempo', 'In summer the weather is nice.', 'En verano hace buen tiempo.', ['está', 'son'], [Kit::word('el verano', 'verano'), Kit::word('el tiempo', 'tiempo'), Kit::form('hace buen tiempo')], 'write'),
            Kit::translate($stage, 'task.translate.calor-frio', 'It is hot in summer, but it is cold in winter.', ['En verano hace calor, pero en invierno hace frío.', 'Hace calor en verano, pero hace frío en invierno.'], [Kit::word('el verano', 'verano'), Kit::word('el calor', 'calor'), Kit::word('el invierno', 'invierno'), Kit::word('el frío', 'frío'), Kit::form('hace')], 'write'),
            Kit::translate($stage, 'task.translate.otono-lluvia', 'In autumn there are clouds and rain.', ['En otoño hay nubes y lluvia.', 'Hay nubes y lluvia en otoño.'], [Kit::word('el otoño', 'otoño'), Kit::word('la nube', 'nubes'), Kit::word('la lluvia', 'lluvia'), Kit::form('hay')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.frio-calor', [
                Kit::line('Marta', 'Luis, ¿hace frío allí?'),
                Kit::line('Luis', 'Sí, hace frío y hay nubes.'),
                Kit::line('Marta', '¿Llueve?'),
                Kit::line('Luis', 'No, no llueve. Pero no hay sol.'),
                Kit::line('Marta', 'Aquí hace calor y hay sol.'),
            ], [
                Kit::question('What is the weather like where Luis is?', ['Cold and cloudy', 'Hot and sunny', 'Rainy'], 'Cold and cloudy'),
                Kit::question('Does it rain where Luis is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('What is the weather like where Marta is?', ['Hot and sunny', 'Cold and cloudy', 'Rainy'], 'Hot and sunny'),
            ], [
                Kit::question('Who asks about the weather first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is there sun where Luis is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el frío', 'frío'), Kit::word('la nube', 'nubes'), Kit::word('el sol', 'sol'), Kit::word('el calor', 'calor')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.primavera', 'En primavera hay sol y lluvia.', 'In spring there is sun and rain.', [Kit::word('la primavera', 'primavera'), Kit::word('el sol', 'sol'), Kit::word('la lluvia', 'lluvia'), Kit::form('hay')], 'listen', homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'task.listen_type.calor-frio', 'Tengo calor, pero allí hace frío.', 'I am hot, but it is cold there.', [Kit::word('el calor', 'calor'), Kit::word('el frío', 'frío'), Kit::form('tengo', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.verano-no-llueve', 'En verano no hace frío y no llueve.', 'In summer it is not cold and it does not rain.', [Kit::word('el verano', 'verano'), Kit::word('el frío', 'frío'), Kit::form('llueve')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.primavera', '¿Qué tiempo hace en primavera?', 'What is the weather like in spring?', [['hace', 'hay'], ['buen', 'sol', 'nubes', 'lluvia', 'frío', 'calor']], 'En primavera hace buen tiempo.', [Kit::word('el tiempo', 'tiempo'), Kit::word('la primavera', 'primavera')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sol-invierno', '¿Hay sol en invierno?', 'Is there sun in winter?', [['sí', 'no'], ['hay', 'hace', 'nubes', 'llueve']], 'Sí, hace sol.', [Kit::word('el sol', 'sol'), Kit::form('hace')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tienes-frio', '¿Tienes frío?', 'Are you cold?', [['sí', 'no'], ['tengo', 'frío']], 'Sí, tengo frío.', [Kit::word('el frío', 'frío'), Kit::form('tengo', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.llueve-verano', '¿Llueve en verano?', 'Does it rain in summer?', [['sí', 'no'], ['llueve', 'lluvia']], 'No, en verano no llueve.', [Kit::word('el verano', 'verano'), Kit::word('la lluvia', 'lluvia'), Kit::form('llueve')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.verano-calor-sol', 'En verano hace calor y hay sol.', 'In summer it is hot and there is sun.', [Kit::word('el verano', 'verano'), Kit::word('el calor', 'calor'), Kit::word('el sol', 'sol')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.buen-tiempo-frio', 'Hace buen tiempo, pero tengo frío.', 'The weather is nice, but I am cold.', [Kit::word('el tiempo', 'tiempo'), Kit::word('el frío', 'frío')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.invierno-nubes', 'In winter it is cold and there are clouds.', ['En invierno hace frío y hay nubes.', 'Hace frío y hay nubes en invierno.'], [Kit::word('el invierno', 'invierno'), Kit::word('el frío', 'frío'), Kit::word('la nube', 'nubes'), Kit::form('hay')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.calor-otono', 'It is hot in summer, but in autumn it rains.', ['Hace calor en verano, pero en otoño llueve.', 'En verano hace calor, pero en otoño llueve.'], [Kit::word('el calor', 'calor'), Kit::word('el verano', 'verano'), Kit::word('el otoño', 'otoño'), Kit::form('llueve', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.frio-otono', 'I am cold in autumn.', ['Tengo frío en otoño.', 'En otoño tengo frío.', 'Yo tengo frío en otoño.', 'En otoño yo tengo frío.'], [Kit::word('el frío', 'frío'), Kit::word('el otoño', 'otoño'), Kit::form('tengo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.buen-tiempo', 'The weather is nice in spring.', ['Hace buen tiempo en primavera.', 'En primavera hace buen tiempo.'], [Kit::word('el tiempo', 'tiempo'), Kit::word('la primavera', 'primavera'), Kit::form('hace buen tiempo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sol', 'Hace ___ y no hay nubes.', 'It is sunny and there are no clouds.', 'sol', Kit::word('el sol', 'sol'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.lluvia', 'No hay sol, hay nubes y ___.', 'There is no sun, there are clouds and rain.', 'lluvia', Kit::word('la lluvia', 'lluvia'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.sol-invierno', '¿Hace sol en invierno?', 'Is it sunny in winter?', [Kit::word('el sol', 'sol'), Kit::word('el invierno', 'invierno'), Kit::form('hace')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.nubes-lluvia', 'Con las nubes y la lluvia tengo frío.', 'With the clouds and the rain I am cold.', [Kit::word('la nube', 'nubes'), Kit::word('la lluvia', 'lluvia')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.frio-calor', '¿Hace frío o calor en verano?', 'Is it cold or hot in summer?', [Kit::word('el frío', 'frío'), Kit::word('el calor', 'calor'), Kit::word('el verano', 'verano'), Kit::form('hace')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.primavera', [
                Kit::line('Pablo', 'Ana, ¿llueve en primavera?'),
                Kit::line('Ana', 'Sí, llueve y hay nubes. Pero en verano hace sol.'),
                Kit::line('Pablo', 'Aquí también hace sol. ¡Tengo calor!'),
            ], [
                Kit::question('When does it rain, according to Ana?', ['In spring', 'In summer', 'In winter'], 'In spring'),
                Kit::question('What is the weather like in summer?', ['Sunny', 'Rainy', 'Cold'], 'Sunny'),
                Kit::question('How does Pablo feel?', ['Hot', 'Cold', 'Ill'], 'Hot'),
            ], [
                Kit::question('Who asks the question?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Does it rain in spring?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la primavera', 'primavera'), Kit::word('la nube', 'nubes'), Kit::word('el verano', 'verano'), Kit::word('el sol', 'sol'), Kit::word('el calor', 'calor')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.invierno', 'Read the conversation.', [
                Kit::line('Luis', 'Marta, ¿hace frío en invierno?'),
                Kit::line('Marta', 'Sí, hace frío y hay lluvia. ¡Tengo frío!'),
                Kit::line('Luis', '¿Y en otoño?'),
                Kit::line('Marta', 'En otoño hay nubes y hace buen tiempo.'),
            ], [
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
                Kit::question('How does Marta feel in winter?', ['Cold', 'Hot', 'Fine'], 'Cold'),
            ], [Kit::word('el invierno', 'invierno'), Kit::word('el frío', 'frío'), Kit::word('la lluvia', 'lluvia'), Kit::word('el otoño', 'otoño'), Kit::word('la nube', 'nubes'), Kit::word('el tiempo', 'tiempo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aqui', '¿Qué tiempo hace aquí?', 'What is the weather like here?', [['hace', 'hay'], ['calor', 'frío', 'sol', 'nubes', 'lluvia', 'buen']], 'Aquí hace calor.', [Kit::word('el tiempo', 'tiempo'), Kit::word('el calor', 'calor')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.nubes-otono', '¿Hay nubes en otoño?', 'Are there clouds in autumn?', [['sí', 'no'], ['hay', 'nubes']], 'Sí, hay nubes en otoño.', [Kit::word('la nube', 'nubes'), Kit::word('el otoño', 'otoño')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.calor-verano', '¿Tienes calor en verano?', 'Are you hot in summer?', [['sí', 'no'], ['tengo', 'calor']], 'Sí, tengo calor.', [Kit::word('el calor', 'calor'), Kit::word('el verano', 'verano')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.primavera-sol', 'In spring the weather is nice and there is sun.', ['En primavera hace buen tiempo y hay sol.', 'Hace buen tiempo y hay sol en primavera.'], [Kit::word('la primavera', 'primavera'), Kit::word('el tiempo', 'tiempo'), Kit::word('el sol', 'sol'), Kit::form('hace buen tiempo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.otono-frio', 'In autumn it rains and I am cold.', ['En otoño llueve y tengo frío.', 'En otoño llueve y yo tengo frío.', 'Llueve y tengo frío en otoño.'], [Kit::word('el otoño', 'otoño'), Kit::word('el frío', 'frío'), Kit::form('llueve', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.verano-nubes', 'In summer it is hot and there are no clouds.', ['En verano hace calor y no hay nubes.', 'Hace calor y no hay nubes en verano.'], [Kit::word('el verano', 'verano'), Kit::word('el calor', 'calor'), Kit::word('la nube', 'nubes'), Kit::form('hace')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.invierno-calor', 'It is not hot in winter.', ['No hace calor en invierno.', 'En invierno no hace calor.'], [Kit::word('el invierno', 'invierno'), Kit::word('el calor', 'calor'), Kit::form('hace')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.primavera', 'Aquí hace buen tiempo en ___.', 'The weather is nice here in spring.', 'primavera', Kit::word('la primavera', 'primavera'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.lluvia', 'En otoño hay ___ y nubes.', 'In autumn there is rain and there are clouds.', 'lluvia', Kit::word('la lluvia', 'lluvia'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.lluvia-invierno', 'Hay lluvia y nubes en invierno.', 'There is rain and there are clouds in winter.', [Kit::word('la lluvia', 'lluvia'), Kit::word('la nube', 'nubes'), Kit::word('el invierno', 'invierno'), Kit::form('hay')], 'dictation', $set, homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sol-frio', 'En verano hace sol, pero tengo frío.', 'In summer it is sunny, but I am cold.', [Kit::word('el verano', 'verano'), Kit::word('el sol', 'sol'), Kit::word('el frío', 'frío'), Kit::form('tengo', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.tiempo-otono', '¿Qué tiempo hace allí en otoño?', 'What is the weather like there in autumn?', [Kit::word('el tiempo', 'tiempo'), Kit::word('el otoño', 'otoño')], 'dictation', $set),
        ];
    }
}
