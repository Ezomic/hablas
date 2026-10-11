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

final class WeatherAndSeasons implements UnitContent
{
    private const HA_NOTE = 'Há (there is) sounds the same as à (to the) and is close to a (the). In a sentence about what is there, it is há.';

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'weather-and-seasons';
    }

    public function words(): array
    {
        return [
            new WordData('o tempo', cue: 'weather', portunolSlips: ['el tiempo'], note: 'Tempo also means time, but here it is the weather: Que tempo faz? Do not mix it up with o tempo livre (free time).'),
            new WordData('o frio', cue: 'cold (cold weather)', portunolSlips: ['el frío']),
            new WordData('o calor', cue: 'heat (hot weather)'),
            new WordData('o sol', cue: 'sun'),
            new WordData('a chuva', cue: 'rain (the noun)', portunolSlips: ['la lluvia']),
            new WordData('a nuvem', cue: 'cloud', forms: ['nuvens'], portunolSlips: ['la nube']),
            new WordData('o inverno', cue: 'winter', portunolSlips: ['el invierno']),
            new WordData('o verão', cue: 'summer', portunolSlips: ['el verano']),
            new WordData('a primavera', cue: 'spring (the season)'),
            new WordData('o outono', cue: 'autumn (fall)', portunolSlips: ['el otoño']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Faz frio no inverno.', 'english' => 'It is cold in winter.'],
            ['text' => 'Chove e há nuvens.', 'english' => 'It rains and there are clouds.'],
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

        return [
            Kit::gap($stage, 'sentences.choose_gap.inverno', 'No inverno ___ frio.', ['faz', 'tem'], 'faz', Kit::form('faz', true), 'Portuguese says it makes cold: faz frio. Tem is \'has\' and does not describe the weather.', 'choose', 'In winter it is cold.'),
            Kit::gap($stage, 'sentences.choose_gap.eu-frio', 'Eu ___ frio.', ['tenho', 'faz'], 'tenho', Kit::form('tenho', true), 'When you feel cold yourself, you say tenho frio. Faz frio is only for the weather.', 'choose', 'I am cold.'),
            Kit::gap($stage, 'sentences.choose_gap.nuvens', '___ nuvens.', ['Há', 'Faz'], 'Há', Kit::form('há'), 'Clouds are things that are there, so you say há nuvens. Faz is for cold, heat and sun.', 'choose', 'There are clouds.'),
            Kit::gap($stage, 'sentences.choose_gap.calor', 'No verão faz ___.', ['calor', 'nuvem', 'chuva'], 'calor', Kit::word('o calor', 'calor'), 'Faz calor means it is hot. Faz nuvem and faz chuva do not exist: clouds use há and rain uses chove.', 'choose', 'In summer it is hot.'),
            Kit::gap($stage, 'sentences.choose_gap.outono-chove', 'No outono ___ e há nuvens.', ['chove', 'faz'], 'chove', Kit::form('chove', true), 'Rain has its own verb: chove. You do not say faz for rain.', 'choose', 'In autumn it rains and there are clouds.'),
            Kit::gap($stage, 'sentences.choose_gap.sol', 'Na primavera faz ___.', ['sol', 'nuvem', 'chove'], 'sol', Kit::word('o sol', 'sol'), 'Faz sol means it is sunny. Nuvem does not go after faz, and chove is a verb on its own.', 'choose', 'In spring it is sunny.'),

            Kit::typeGap($stage, 'sentences.type_gap.primavera-tempo', 'Que tempo ___ na primavera?', 'What is the weather like in spring?', 'faz', Kit::form('faz'), 'What is the weather like is Que tempo faz, with faz as in faz frio.'),
            Kit::typeGap($stage, 'sentences.type_gap.que-tempo', 'Que ___ faz no verão?', 'What is the weather like in summer?', 'tempo', Kit::word('o tempo', 'tempo')),
            Kit::typeGap($stage, 'sentences.type_gap.nuvens', 'Há sol e não há ___.', 'There is sun and there are no clouds.', 'nuvens', Kit::word('a nuvem', 'nuvens')),
            Kit::typeGap($stage, 'sentences.type_gap.chove', 'Ali ___ no outono.', 'It rains there in autumn.', 'chove', Kit::form('chove'), 'It rains is one verb in Portuguese: chove.'),
            Kit::typeGap($stage, 'sentences.type_gap.inverno', 'No ___ faz frio.', 'In winter it is cold.', 'inverno', Kit::word('o inverno', 'inverno')),

            Kit::translate($stage, 'sentences.translate.calor-verao', 'It is hot in summer.', ['Faz calor no verão.', 'No verão faz calor.', 'Está calor no verão.', 'No verão está calor.'], [Kit::word('o calor', 'calor'), Kit::word('o verão', 'verão'), Kit::form('faz', alternates: ['está'])]),
            Kit::translate($stage, 'sentences.translate.tenho-frio', 'I am cold.', ['Tenho frio.', 'Eu tenho frio.', 'Estou com frio.', 'Eu estou com frio.'], [Kit::word('o frio', 'frio'), Kit::form('tenho', true, alternates: ['estou'])]),
            Kit::translate($stage, 'sentences.translate.sol-primavera', 'Is it sunny in spring?', ['Faz sol na primavera?', 'Na primavera faz sol?', 'Está sol na primavera?', 'Na primavera está sol?', 'Há sol na primavera?', 'Na primavera há sol?'], [Kit::word('o sol', 'sol'), Kit::word('a primavera', 'primavera'), Kit::form('faz', alternates: ['está', 'há'])]),

            Kit::build($stage, 'sentences.build.que-tempo', 'What is the weather like in winter?', 'Que tempo faz no inverno?', ['é'], [Kit::word('o tempo', 'tempo'), Kit::word('o inverno', 'inverno'), Kit::form('faz')]),
            Kit::build($stage, 'sentences.build.uma-nuvem', 'There is a cloud.', 'Há uma nuvem.', ['faz'], [Kit::word('a nuvem', 'nuvem'), Kit::form('há')]),
            Kit::build($stage, 'sentences.build.nao-tenho-calor', 'I am not hot.', 'Não tenho calor.', ['é'], [Kit::word('o calor', 'calor'), Kit::form('tenho', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.calor-verao', 'Faz calor no verão.', ['It is hot in summer.', 'It is cold in summer.', 'It is hot in winter.', 'It rains in summer.'], 'It is hot in summer.', [Kit::word('o calor', 'calor'), Kit::word('o verão', 'verão'), Kit::form('faz')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.outono', 'No outono chove.', ['It rains in autumn.', 'It is sunny in autumn.', 'It rains in spring.', 'It is cold in autumn.'], 'It rains in autumn.', [Kit::word('o outono', 'outono'), Kit::form('chove')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.nuvens-primavera', 'Há nuvens na primavera?', ['Are there clouds in spring?', 'Is it sunny in spring?', 'Are there clouds in autumn?', 'Does it rain in spring?'], 'Are there clouds in spring?', [Kit::word('a primavera', 'primavera'), Kit::word('a nuvem', 'nuvens'), Kit::form('há')]),
            Kit::listenType($stage, 'sentences.listen_type.inverno-frio', 'No inverno faz frio.', 'In winter it is cold.', [Kit::word('o inverno', 'inverno'), Kit::word('o frio', 'frio'), Kit::form('faz')]),
            Kit::listenType($stage, 'sentences.listen_type.verao-sol', 'No verão há sol e não chove.', 'In summer there is sun and it does not rain.', [Kit::word('o verão', 'verão'), Kit::word('o sol', 'sol'), Kit::form('há')], homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.chuva-frio', 'Com chuva faz frio.', 'With rain it is cold.', [Kit::word('a chuva', 'chuva'), Kit::word('o frio', 'frio'), Kit::form('faz')]),
            Kit::listenType($stage, 'sentences.listen_type.tempo-outono', 'Que tempo faz no outono?', 'What is the weather like in autumn?', [Kit::word('o tempo', 'tempo'), Kit::word('o outono', 'outono'), Kit::form('faz')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.bom-tempo', 'Faz bom tempo aqui na primavera.', 'The weather is nice here in spring.', [Kit::word('o tempo', 'tempo'), Kit::word('a primavera', 'primavera'), Kit::form('faz bom tempo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tenho-calor', 'Tenho calor no verão.', 'I am hot in summer.', [Kit::word('o calor', 'calor'), Kit::word('o verão', 'verão'), Kit::form('tenho', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.chuva-nuvens', 'Há chuva e nuvens no outono.', 'There is rain and there are clouds in autumn.', [Kit::word('o outono', 'outono'), Kit::word('a chuva', 'chuva'), Kit::word('a nuvem', 'nuvens'), Kit::form('há')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.inverno-chove', 'No inverno chove e faz frio.', 'In winter it rains and it is cold.', [Kit::word('o inverno', 'inverno'), Kit::word('o frio', 'frio'), Kit::form('chove')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.verao', 'Que tempo faz no verão?', 'What is the weather like in summer?', [['faz', 'há', 'está'], ['calor', 'sol', 'bom']], 'No verão faz calor.', [Kit::word('o calor', 'calor'), Kit::form('faz')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.inverno', 'Faz frio no inverno?', 'Is it cold in winter?', [['sim', 'não'], ['faz', 'há', 'está']], 'Sim, faz frio.', [Kit::word('o frio', 'frio'), Kit::word('o inverno', 'inverno'), Kit::form('faz')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.chove', 'Chove no outono?', 'Does it rain in autumn?', [['sim', 'não'], ['chove', 'chuva']], 'Sim, chove no outono.', [Kit::word('o outono', 'outono'), Kit::form('chove')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tempo-verao', 'Read the conversation about the weather.', [
                Kit::line('Ana', 'Rui, que tempo faz no verão?'),
                Kit::line('Rui', 'Faz calor e há sol. Vou à praia. E no inverno?'),
                Kit::line('Ana', 'Faz frio e chove. Estou em casa.'),
                Kit::line('Rui', 'E na primavera?'),
                Kit::line('Ana', 'Faz bom tempo.'),
            ], [
                Kit::question('What is the weather like in summer?', ['Hot and sunny', 'Cold and rainy', 'Cloudy'], 'Hot and sunny'),
                Kit::question('What does Rui do in summer?', ['He goes to the beach.', 'He stays at home.', 'He goes to the cinema.'], 'He goes to the beach.'),
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
            ], [Kit::word('o tempo', 'tempo'), Kit::word('o verão', 'verão'), Kit::word('o calor', 'calor'), Kit::word('o sol', 'sol'), Kit::word('o inverno', 'inverno'), Kit::word('o frio', 'frio'), Kit::word('a primavera', 'primavera')], 'read', glosses: ['praia' => 'beach', 'casa' => 'home']),
            Kit::gap($stage, 'task.choose_gap.aqui-calor', 'Aqui ___ calor e há sol.', ['faz', 'tem'], 'faz', Kit::form('faz', true), 'Portuguese says it makes hot: faz calor. Tem is \'has\' and does not describe the weather.', 'read', 'It is hot here and there is sun.'),
            Kit::gap($stage, 'task.choose_gap.bom-tempo', 'Aqui faz bom ___.', ['tempo', 'chuva', 'nuvem'], 'tempo', Kit::word('o tempo', 'tempo'), 'Faz bom tempo means the weather is nice. Bom goes with masculine words, and chuva and nuvem are feminine.', 'read', 'The weather is nice here.'),

            Kit::transform($stage, 'task.transform.pergunta-sol', 'Now ask a question.', 'Faz sol no verão.', ['Faz sol no verão?', 'No verão faz sol?', 'Está sol no verão?', 'No verão está sol?'], [Kit::word('o sol', 'sol'), Kit::word('o verão', 'verão'), Kit::form('faz', alternates: ['está'])]),
            Kit::transform($stage, 'task.transform.tenho-frio', 'Now say that you are cold.', 'Faz frio.', ['Tenho frio.', 'Eu tenho frio.', 'Estou com frio.', 'Eu estou com frio.'], [Kit::word('o frio', 'frio'), Kit::form('tenho', true, alternates: ['estou'])]),
            Kit::transform($stage, 'task.transform.nuvens', 'Make it plural.', 'Há uma nuvem.', ['Há nuvens.'], [Kit::word('a nuvem', 'nuvens'), Kit::form('há')]),
            Kit::writeGuided($stage, 'task.write_guided.verao', 'Say that in summer it is hot and there is sun.', ['verão', 'faz', 'calor', 'sol'], 'No verão faz calor e há sol.', [
                ['forms' => ['verão'], 'term' => 'o verão'],
                ['forms' => ['calor'], 'term' => 'o calor'],
                ['forms' => ['sol'], 'term' => 'o sol'],
            ], [Kit::word('o verão', 'verão'), Kit::word('o calor', 'calor'), Kit::word('o sol', 'sol')]),
            Kit::writeGuided($stage, 'task.write_guided.outono', 'Say that in autumn it rains and there are clouds.', ['outono', 'chove', 'há', 'nuvens'], 'No outono chove e há nuvens.', [
                ['forms' => ['outono'], 'term' => 'o outono'],
                ['forms' => ['chove'], 'term' => null],
                ['forms' => ['nuvens'], 'term' => 'a nuvem'],
            ], [Kit::word('o outono', 'outono'), Kit::word('a nuvem', 'nuvens'), Kit::form('chove')]),
            Kit::build($stage, 'task.build.que-tempo-primavera', 'What is the weather like in spring?', 'Que tempo faz na primavera?', ['é', 'são'], [Kit::word('o tempo', 'tempo'), Kit::word('a primavera', 'primavera'), Kit::form('faz')], 'write'),
            Kit::build($stage, 'task.build.inverno-nuvens', 'In winter there are clouds.', 'No inverno há nuvens.', ['faz', 'é'], [Kit::word('o inverno', 'inverno'), Kit::word('a nuvem', 'nuvens'), Kit::form('há')], 'write'),
            Kit::build($stage, 'task.build.verao-bom-tempo', 'In summer the weather is nice.', 'No verão faz bom tempo.', ['é', 'são'], [Kit::word('o verão', 'verão'), Kit::word('o tempo', 'tempo'), Kit::form('faz bom tempo')], 'write'),
            Kit::translate($stage, 'task.translate.calor-frio', 'It is hot in summer, but it is cold in winter.', ['No verão faz calor, mas no inverno faz frio.', 'Faz calor no verão, mas faz frio no inverno.', 'No verão está calor, mas no inverno está frio.'], [Kit::word('o verão', 'verão'), Kit::word('o calor', 'calor'), Kit::word('o inverno', 'inverno'), Kit::word('o frio', 'frio'), Kit::form('faz', alternates: ['está'])], 'write'),
            Kit::translate($stage, 'task.translate.outono-chuva', 'In autumn there are clouds and rain.', ['No outono há nuvens e chuva.', 'Há nuvens e chuva no outono.'], [Kit::word('o outono', 'outono'), Kit::word('a nuvem', 'nuvens'), Kit::word('a chuva', 'chuva'), Kit::form('há')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.frio-calor', [
                Kit::line('Marta', 'João, faz frio lá?'),
                Kit::line('João', 'Sim, faz frio e há nuvens.'),
                Kit::line('Marta', 'Chove?'),
                Kit::line('João', 'Não, não chove. Mas não há sol.'),
                Kit::line('Marta', 'Aqui faz calor e há sol.'),
            ], [
                Kit::question('What is the weather like where João is?', ['Cold and cloudy', 'Hot and sunny', 'Rainy'], 'Cold and cloudy'),
                Kit::question('Does it rain where João is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('What is the weather like where Marta is?', ['Hot and sunny', 'Cold and cloudy', 'Rainy'], 'Hot and sunny'),
            ], [
                Kit::question('Who asks about the weather first?', ['Marta', 'João', 'Nobody'], 'Marta'),
                Kit::question('Is there sun where João is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o frio', 'frio'), Kit::word('a nuvem', 'nuvens'), Kit::word('o sol', 'sol'), Kit::word('o calor', 'calor')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.primavera', 'Na primavera há sol e chuva.', 'In spring there is sun and rain.', [Kit::word('a primavera', 'primavera'), Kit::word('o sol', 'sol'), Kit::word('a chuva', 'chuva'), Kit::form('há')], 'listen', homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'task.listen_type.calor-frio', 'Tenho calor, mas lá faz frio.', 'I am hot, but it is cold there.', [Kit::word('o calor', 'calor'), Kit::word('o frio', 'frio'), Kit::form('tenho', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.verao-nao-chove', 'No verão não faz frio e não chove.', 'In summer it is not cold and it does not rain.', [Kit::word('o verão', 'verão'), Kit::word('o frio', 'frio'), Kit::form('chove')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.primavera', 'Que tempo faz na primavera?', 'What is the weather like in spring?', [['faz', 'há', 'está'], ['bom', 'sol', 'nuvens', 'chuva', 'frio', 'calor']], 'Na primavera faz bom tempo.', [Kit::word('o tempo', 'tempo'), Kit::word('a primavera', 'primavera')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sol-inverno', 'Há sol no inverno?', 'Is there sun in winter?', [['sim', 'não'], ['há', 'faz', 'está', 'chove']], 'Sim, há sol no inverno.', [Kit::word('o sol', 'sol'), Kit::form('há')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tens-frio', 'Tens frio?', 'Are you cold?', [['sim', 'não'], ['tenho', 'frio']], 'Sim, tenho frio.', [Kit::word('o frio', 'frio'), Kit::form('tenho', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chove-verao', 'Chove no verão?', 'Does it rain in summer?', [['sim', 'não'], ['chove', 'chuva']], 'Não, no verão não chove.', [Kit::word('o verão', 'verão'), Kit::word('a chuva', 'chuva'), Kit::form('chove')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.verao-calor-sol', 'No verão faz calor e há sol.', 'In summer it is hot and there is sun.', [Kit::word('o verão', 'verão'), Kit::word('o calor', 'calor'), Kit::word('o sol', 'sol')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bom-tempo-frio', 'Faz bom tempo, mas tenho frio.', 'The weather is nice, but I am cold.', [Kit::word('o tempo', 'tempo'), Kit::word('o frio', 'frio')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.inverno-nuvens', 'In winter it is cold and there are clouds.', ['No inverno faz frio e há nuvens.', 'Faz frio e há nuvens no inverno.', 'No inverno está frio e há nuvens.', 'Está frio e há nuvens no inverno.'], [Kit::word('o inverno', 'inverno'), Kit::word('o frio', 'frio'), Kit::word('a nuvem', 'nuvens'), Kit::form('há')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.calor-outono', 'It is hot in summer, but in autumn it rains.', ['Faz calor no verão, mas no outono chove.', 'No verão faz calor, mas no outono chove.', 'Está calor no verão, mas no outono chove.', 'No verão está calor, mas no outono chove.'], [Kit::word('o calor', 'calor'), Kit::word('o verão', 'verão'), Kit::word('o outono', 'outono'), Kit::form('chove', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.frio-outono', 'I am cold in autumn.', ['Tenho frio no outono.', 'No outono tenho frio.', 'Eu tenho frio no outono.', 'No outono eu tenho frio.', 'Estou com frio no outono.', 'No outono estou com frio.', 'Eu estou com frio no outono.', 'No outono eu estou com frio.'], [Kit::word('o frio', 'frio'), Kit::word('o outono', 'outono'), Kit::form('tenho', true, alternates: ['estou'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bom-tempo', 'The weather is nice in spring.', ['Faz bom tempo na primavera.', 'Na primavera faz bom tempo.', 'Está bom tempo na primavera.', 'Na primavera está bom tempo.'], [Kit::word('o tempo', 'tempo'), Kit::word('a primavera', 'primavera'), Kit::form('faz bom tempo', alternates: ['está bom tempo'])], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sol', 'Faz ___ e não há nuvens.', 'It is sunny and there are no clouds.', 'sol', Kit::word('o sol', 'sol'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.chuva', 'Não há sol, há nuvens e ___.', 'There is no sun, there are clouds and rain.', 'chuva', Kit::word('a chuva', 'chuva'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.sol-inverno', 'Faz sol no inverno?', 'Is it sunny in winter?', [Kit::word('o sol', 'sol'), Kit::word('o inverno', 'inverno'), Kit::form('faz')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.nuvens-chuva', 'Com nuvens e chuva tenho frio.', 'With clouds and rain I am cold.', [Kit::word('a nuvem', 'nuvens'), Kit::word('a chuva', 'chuva')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.frio-calor', 'Faz frio ou calor no verão?', 'Is it cold or hot in summer?', [Kit::word('o frio', 'frio'), Kit::word('o calor', 'calor'), Kit::word('o verão', 'verão'), Kit::form('faz')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.primavera', [
                Kit::line('Rui', 'Ana, chove na primavera?'),
                Kit::line('Ana', 'Sim, chove e há nuvens. Mas no verão faz sol.'),
                Kit::line('Rui', 'Aqui também faz sol. Tenho calor!'),
            ], [
                Kit::question('When does it rain, according to Ana?', ['In spring', 'In summer', 'In winter'], 'In spring'),
                Kit::question('What is the weather like in summer?', ['Sunny', 'Rainy', 'Cold'], 'Sunny'),
                Kit::question('How does Rui feel?', ['Hot', 'Cold', 'Ill'], 'Hot'),
            ], [
                Kit::question('Who asks the question?', ['Rui', 'Ana', 'Nobody'], 'Rui'),
                Kit::question('Does it rain in spring?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('a primavera', 'primavera'), Kit::word('a nuvem', 'nuvens'), Kit::word('o verão', 'verão'), Kit::word('o sol', 'sol'), Kit::word('o calor', 'calor')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.inverno', 'Read the conversation.', [
                Kit::line('João', 'Marta, faz frio no inverno?'),
                Kit::line('Marta', 'Sim, faz frio e chove. Tenho frio!'),
                Kit::line('João', 'E no outono?'),
                Kit::line('Marta', 'No outono há nuvens e faz bom tempo.'),
            ], [
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
                Kit::question('How does Marta feel in winter?', ['Cold', 'Hot', 'Fine'], 'Cold'),
            ], [Kit::word('o inverno', 'inverno'), Kit::word('o frio', 'frio'), Kit::word('o outono', 'outono'), Kit::word('a nuvem', 'nuvens'), Kit::word('o tempo', 'tempo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aqui', 'Que tempo faz aqui?', 'What is the weather like here?', [['faz', 'há', 'está'], ['calor', 'frio', 'sol', 'nuvens', 'chuva', 'bom']], 'Aqui faz calor.', [Kit::word('o tempo', 'tempo'), Kit::word('o calor', 'calor')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.nuvens-outono', 'Há nuvens no outono?', 'Are there clouds in autumn?', [['sim', 'não'], ['há', 'nuvens']], 'Sim, há nuvens no outono.', [Kit::word('a nuvem', 'nuvens'), Kit::word('o outono', 'outono')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.calor-verao', 'Tens calor no verão?', 'Are you hot in summer?', [['sim', 'não'], ['tenho', 'calor']], 'Sim, tenho calor.', [Kit::word('o calor', 'calor'), Kit::word('o verão', 'verão')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.primavera-sol', 'In spring the weather is nice and there is sun.', ['Na primavera faz bom tempo e há sol.', 'Faz bom tempo e há sol na primavera.', 'Na primavera está bom tempo e há sol.', 'Está bom tempo e há sol na primavera.'], [Kit::word('a primavera', 'primavera'), Kit::word('o tempo', 'tempo'), Kit::word('o sol', 'sol'), Kit::form('faz bom tempo', alternates: ['está bom tempo'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.outono-frio', 'In autumn it rains and I am cold.', ['No outono chove e tenho frio.', 'No outono chove e eu tenho frio.', 'Chove e tenho frio no outono.', 'No outono chove e estou com frio.', 'No outono chove e eu estou com frio.'], [Kit::word('o outono', 'outono'), Kit::word('o frio', 'frio'), Kit::form('chove', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.verao-nuvens', 'In summer it is hot and there are no clouds.', ['No verão faz calor e não há nuvens.', 'Faz calor e não há nuvens no verão.', 'No verão está calor e não há nuvens.', 'Está calor e não há nuvens no verão.'], [Kit::word('o verão', 'verão'), Kit::word('o calor', 'calor'), Kit::word('a nuvem', 'nuvens'), Kit::form('faz', alternates: ['está'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.inverno-calor', 'It is not hot in winter.', ['Não faz calor no inverno.', 'No inverno não faz calor.', 'Não está calor no inverno.', 'No inverno não está calor.'], [Kit::word('o inverno', 'inverno'), Kit::word('o calor', 'calor'), Kit::form('faz', alternates: ['está'])], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.primavera', 'Aqui faz bom tempo na ___.', 'The weather is nice here in spring.', 'primavera', Kit::word('a primavera', 'primavera'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.chuva', 'No inverno há ___ e faz frio.', 'In winter there is rain and it is cold.', 'chuva', Kit::word('a chuva', 'chuva'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.chuva-inverno', 'Há chuva e nuvens no inverno.', 'There is rain and there are clouds in winter.', [Kit::word('a chuva', 'chuva'), Kit::word('a nuvem', 'nuvens'), Kit::word('o inverno', 'inverno'), Kit::form('há')], 'dictation', $set, homophoneNote: self::HA_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sol-frio', 'No verão faz sol, mas tenho frio.', 'In summer it is sunny, but I am cold.', [Kit::word('o verão', 'verão'), Kit::word('o sol', 'sol'), Kit::word('o frio', 'frio'), Kit::form('tenho', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.tempo-outono', 'Que tempo faz aqui no verão?', 'What is the weather like here in summer?', [Kit::word('o tempo', 'tempo'), Kit::word('o verão', 'verão')], 'dictation', $set),
        ];
    }
}
