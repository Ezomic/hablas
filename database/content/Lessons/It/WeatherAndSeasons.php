<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

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
    private const E_NOTE = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

    private const HO_NOTE = 'Ho (I have) sounds the same as o (or), which is written without the h. Here it is ho, the verb have.';

    private const O_NOTE = 'O (or) sounds the same as ho (I have), which is written with an h. Here it is o, meaning or.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'weather-and-seasons';
    }

    public function words(): array
    {
        return [
            new WordData('il tempo', cue: 'weather', forms: ['tempo'], note: 'Tempo also means time, but here it is the weather: Che tempo fa? Do not mix it up with il tempo libero (free time).'),
            new WordData('il freddo', cue: 'cold (cold weather)'),
            new WordData('il caldo', cue: 'heat (hot weather)'),
            new WordData('il sole', cue: 'sun'),
            new WordData('la pioggia', cue: 'rain (the noun)'),
            new WordData('la nuvola', cue: 'cloud', forms: ['nuvole']),
            new WordData('l\'inverno', cue: 'winter', forms: ['inverno']),
            new WordData('l\'estate', cue: 'summer', forms: ['estate'], note: 'Estate is feminine: l\'estate. In summer is in estate.'),
            new WordData('la primavera', cue: 'spring (the season)'),
            new WordData('l\'autunno', cue: 'autumn (fall)', forms: ['autunno']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'In inverno fa freddo.', 'english' => 'In winter it is cold.'],
            ['text' => 'Piove e ci sono nuvole.', 'english' => 'It rains and there are clouds.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.inverno', 'In inverno ___ freddo.', ['fa', 'sta'], 'fa', Kit::form('fa', true), 'Italian says it makes cold: fa freddo. Sta does not fit, and you do not say è freddo about the weather.', 'choose', 'In winter it is cold.'),
            Kit::gap($stage, 'sentences.choose_gap.io-freddo', 'Io ___ freddo.', ['ho', 'fa'], 'ho', Kit::form('ho', true), 'When you feel cold yourself, you say ho freddo. Fa freddo is only for the weather.', 'choose', 'I am cold.'),
            Kit::gap($stage, 'sentences.choose_gap.nuvole', '___ nuvole.', ['Ci sono', 'Fa'], 'Ci sono', Kit::form('ci sono'), 'Clouds are things that are there, so you say ci sono nuvole. Fa is for cold, heat and good weather.', 'choose', 'There are clouds.'),
            Kit::gap($stage, 'sentences.choose_gap.caldo', 'In estate fa ___.', ['caldo', 'nuvola', 'pioggia'], 'caldo', Kit::word('il caldo', 'caldo'), 'Fa caldo means it is hot. Fa nuvola and fa pioggia do not exist: clouds use ci sono and rain uses piove.', 'choose', 'In summer it is hot.'),
            Kit::gap($stage, 'sentences.choose_gap.autunno-piove', 'In autunno ___ e ci sono nuvole.', ['piove', 'fa'], 'piove', Kit::form('piove', true), 'Rain has its own verb: piove. You do not say fa for rain.', 'choose', 'In autumn it rains and there are clouds.'),
            Kit::gap($stage, 'sentences.choose_gap.sole', 'In primavera c\'è il ___.', ['sole', 'nuvola', 'pioggia'], 'sole', Kit::word('il sole', 'sole'), 'C\'è il sole means it is sunny. Nuvola and pioggia are feminine, so they would need la, not il.', 'choose', 'In spring it is sunny.'),

            Kit::typeGap($stage, 'sentences.type_gap.primavera-tempo', 'In primavera ___ bel tempo.', 'In spring the weather is nice.', 'fa', Kit::form('fa'), 'The weather is nice is fa bel tempo, with fa as in fa freddo.'),
            Kit::typeGap($stage, 'sentences.type_gap.che-tempo', 'Che ___ fa in inverno?', 'What is the weather like in winter?', 'tempo', Kit::word('il tempo', 'tempo')),
            Kit::typeGap($stage, 'sentences.type_gap.nuvole', 'C\'è il sole e non ci sono ___.', 'There is sun and there are no clouds.', 'nuvole', Kit::word('la nuvola', 'nuvole')),
            Kit::typeGap($stage, 'sentences.type_gap.piove', 'Lì ___ in autunno.', 'It rains there in autumn.', 'piove', Kit::form('piove'), 'It rains is one verb in Italian: piove.'),
            Kit::typeGap($stage, 'sentences.type_gap.inverno', 'In ___ fa freddo.', 'In winter it is cold.', 'inverno', Kit::word('l\'inverno', 'inverno')),

            Kit::translate($stage, 'sentences.translate.caldo-estate', 'It is hot in summer.', ['Fa caldo in estate.', 'In estate fa caldo.'], [Kit::word('il caldo', 'caldo'), Kit::word('l\'estate', 'estate'), Kit::form('fa')]),
            Kit::translate($stage, 'sentences.translate.ho-freddo', 'I am cold.', ['Ho freddo.', 'Io ho freddo.'], [Kit::word('il freddo', 'freddo'), Kit::form('ho', true)]),
            Kit::translate($stage, 'sentences.translate.sole-primavera', 'Is it sunny in spring?', ['C\'è il sole in primavera?', 'In primavera c\'è il sole?', 'C\'è sole in primavera?', 'In primavera c\'è sole?'], [Kit::word('il sole', 'sole'), Kit::word('la primavera', 'primavera'), Kit::form('c\'è')]),

            Kit::build($stage, 'sentences.build.che-tempo', 'What is the weather like in winter?', 'Che tempo fa in inverno?', ['è'], [Kit::word('il tempo', 'tempo'), Kit::word('l\'inverno', 'inverno'), Kit::form('fa')]),
            Kit::build($stage, 'sentences.build.una-nuvola', 'There is a cloud.', 'C\'è una nuvola.', ['fa'], [Kit::word('la nuvola', 'nuvola'), Kit::form('c\'è')]),
            Kit::build($stage, 'sentences.build.non-ho-caldo', 'I am not hot.', 'Non ho caldo.', ['è'], [Kit::word('il caldo', 'caldo'), Kit::form('ho', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.caldo-estate', 'Fa caldo in estate.', ['It is hot in summer.', 'It is cold in summer.', 'It is hot in winter.', 'It rains in summer.'], 'It is hot in summer.', [Kit::word('il caldo', 'caldo'), Kit::word('l\'estate', 'estate'), Kit::form('fa')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.autunno', 'In autunno piove.', ['It rains in autumn.', 'It is sunny in autumn.', 'It rains in spring.', 'It is cold in autumn.'], 'It rains in autumn.', [Kit::word('l\'autunno', 'autunno'), Kit::form('piove')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.nuvole-primavera', 'Ci sono nuvole in primavera?', ['Are there clouds in spring?', 'Is it sunny in spring?', 'Are there clouds in autumn?', 'Does it rain in spring?'], 'Are there clouds in spring?', [Kit::word('la primavera', 'primavera'), Kit::word('la nuvola', 'nuvole'), Kit::form('ci sono')]),
            Kit::listenType($stage, 'sentences.listen_type.inverno-freddo', 'In inverno fa freddo.', 'In winter it is cold.', [Kit::word('l\'inverno', 'inverno'), Kit::word('il freddo', 'freddo'), Kit::form('fa')]),
            Kit::listenType($stage, 'sentences.listen_type.estate-sole', 'C\'è il sole e non piove.', 'There is sun and it does not rain.', [Kit::word('il sole', 'sole'), Kit::form('c\'è')], homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.pioggia-freddo', 'Con la pioggia fa freddo.', 'With the rain it is cold.', [Kit::word('la pioggia', 'pioggia'), Kit::word('il freddo', 'freddo'), Kit::form('fa')]),
            Kit::listenType($stage, 'sentences.listen_type.tempo-autunno', 'Che tempo fa in autunno?', 'What is the weather like in autumn?', [Kit::word('il tempo', 'tempo'), Kit::word('l\'autunno', 'autunno'), Kit::form('fa')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.bel-tempo', 'Fa bel tempo qui in primavera.', 'The weather is nice here in spring.', [Kit::word('il tempo', 'tempo'), Kit::word('la primavera', 'primavera'), Kit::form('fa bel tempo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ho-caldo', 'In estate ho caldo.', 'I am hot in summer.', [Kit::word('il caldo', 'caldo'), Kit::word('l\'estate', 'estate'), Kit::form('ho', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pioggia-nuvole', 'In primavera ci sono nuvole e pioggia.', 'In spring there are clouds and rain.', [Kit::word('la primavera', 'primavera'), Kit::word('la pioggia', 'pioggia'), Kit::word('la nuvola', 'nuvole'), Kit::form('ci sono')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.inverno-piove', 'In inverno piove e fa freddo.', 'In winter it rains and it is cold.', [Kit::word('l\'inverno', 'inverno'), Kit::word('il freddo', 'freddo'), Kit::form('piove')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.estate', 'Che tempo fa in estate?', 'What is the weather like in summer?', [['fa', 'c\'è'], ['caldo', 'sole', 'bel']], 'In estate fa caldo.', [Kit::word('il caldo', 'caldo'), Kit::form('fa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.inverno', 'Fa freddo in inverno?', 'Is it cold in winter?', [['sì', 'no'], ['fa', 'c\'è', 'piove']], 'Sì, fa freddo.', [Kit::word('il freddo', 'freddo'), Kit::word('l\'inverno', 'inverno'), Kit::form('fa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.piove', 'Piove in autunno?', 'Does it rain in autumn?', [['sì', 'no'], ['piove', 'pioggia']], 'Sì, piove in autunno.', [Kit::word('l\'autunno', 'autunno'), Kit::form('piove')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tempo-estate', 'Read the conversation about the weather.', [
                Kit::line('Anna', 'Paolo, che tempo fa in estate?'),
                Kit::line('Paolo', 'Fa caldo e c\'è il sole. Vado in spiaggia. E in inverno?'),
                Kit::line('Anna', 'Fa freddo e piove. Sto a casa.'),
                Kit::line('Paolo', 'E in primavera?'),
                Kit::line('Anna', 'Fa bel tempo.'),
            ], [
                Kit::question('What is the weather like in summer?', ['Hot and sunny', 'Cold and rainy', 'Cloudy'], 'Hot and sunny'),
                Kit::question('What does Paolo do in summer?', ['He goes to the beach.', 'He stays at home.', 'He goes to the cinema.'], 'He goes to the beach.'),
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
            ], [Kit::word('il tempo', 'tempo'), Kit::word('l\'estate', 'estate'), Kit::word('il caldo', 'caldo'), Kit::word('il sole', 'sole'), Kit::word('l\'inverno', 'inverno'), Kit::word('il freddo', 'freddo'), Kit::word('la primavera', 'primavera')], 'read', glosses: ['spiaggia' => 'beach', 'casa' => 'home']),
            Kit::gap($stage, 'task.choose_gap.qui-caldo', 'Qui ___ caldo e c\'è il sole.', ['fa', 'sta'], 'fa', Kit::form('fa', true), 'Italian says it makes hot: fa caldo. Sta does not fit, and you do not say è caldo about the weather.', 'read', 'It is hot here and there is sun.'),
            Kit::gap($stage, 'task.choose_gap.bel-tempo', 'Qui fa bel ___.', ['tempo', 'pioggia', 'nuvola'], 'tempo', Kit::word('il tempo', 'tempo'), 'Fa bel tempo means the weather is nice. Bel goes with a masculine word like tempo, and pioggia and nuvola are feminine.', 'read', 'The weather is nice here.'),

            Kit::transform($stage, 'task.transform.domanda-sole', 'Now ask a question.', 'In estate c\'è il sole.', ['In estate c\'è il sole?', 'C\'è il sole in estate?'], [Kit::word('il sole', 'sole'), Kit::word('l\'estate', 'estate'), Kit::form('c\'è')]),
            Kit::transform($stage, 'task.transform.ho-freddo', 'Now say that you are cold.', 'Fa freddo.', ['Ho freddo.', 'Io ho freddo.'], [Kit::word('il freddo', 'freddo'), Kit::form('ho', true)]),
            Kit::transform($stage, 'task.transform.nuvole', 'Change it to several clouds.', 'C\'è una nuvola.', ['Ci sono nuvole.', 'Ci sono le nuvole.'], [Kit::word('la nuvola', 'nuvole'), Kit::form('ci sono')]),
            Kit::writeGuided($stage, 'task.write_guided.estate', 'Say that in summer it is hot and there is sun.', ['estate', 'fa', 'caldo', 'sole'], 'In estate fa caldo e c\'è il sole.', [
                ['forms' => ['estate'], 'term' => 'l\'estate'],
                ['forms' => ['caldo'], 'term' => 'il caldo'],
                ['forms' => ['sole'], 'term' => 'il sole'],
            ], [Kit::word('l\'estate', 'estate'), Kit::word('il caldo', 'caldo'), Kit::word('il sole', 'sole')]),
            Kit::writeGuided($stage, 'task.write_guided.autunno', 'Say that in autumn it rains and there are clouds.', ['autunno', 'piove', 'ci sono', 'nuvole'], 'In autunno piove e ci sono nuvole.', [
                ['forms' => ['autunno'], 'term' => 'l\'autunno'],
                ['forms' => ['piove'], 'term' => null],
                ['forms' => ['nuvole'], 'term' => 'la nuvola'],
            ], [Kit::word('l\'autunno', 'autunno'), Kit::word('la nuvola', 'nuvole'), Kit::form('piove')]),
            Kit::build($stage, 'task.build.che-tempo-primavera', 'What is the weather like in spring?', 'Che tempo fa in primavera?', ['è', 'sta'], [Kit::word('il tempo', 'tempo'), Kit::word('la primavera', 'primavera'), Kit::form('fa')], 'write'),
            Kit::build($stage, 'task.build.inverno-nuvole', 'In winter there are clouds.', 'In inverno ci sono nuvole.', ['fa', 'è'], [Kit::word('l\'inverno', 'inverno'), Kit::word('la nuvola', 'nuvole'), Kit::form('ci sono')], 'write'),
            Kit::build($stage, 'task.build.estate-bel-tempo', 'In summer the weather is nice.', 'In estate fa bel tempo.', ['è', 'sta'], [Kit::word('l\'estate', 'estate'), Kit::word('il tempo', 'tempo'), Kit::form('fa bel tempo')], 'write'),
            Kit::translate($stage, 'task.translate.caldo-freddo', 'It is hot in summer, but it is cold in winter.', ['In estate fa caldo, ma in inverno fa freddo.', 'Fa caldo in estate, ma fa freddo in inverno.'], [Kit::word('l\'estate', 'estate'), Kit::word('il caldo', 'caldo'), Kit::word('l\'inverno', 'inverno'), Kit::word('il freddo', 'freddo'), Kit::form('fa')], 'write'),
            Kit::translate($stage, 'task.translate.autunno-pioggia', 'In autumn there are clouds and rain.', ['In autunno ci sono nuvole e pioggia.', 'Ci sono nuvole e pioggia in autunno.', 'In autunno ci sono le nuvole e la pioggia.'], [Kit::word('l\'autunno', 'autunno'), Kit::word('la nuvola', 'nuvole'), Kit::word('la pioggia', 'pioggia'), Kit::form('ci sono')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.freddo-caldo', [
                Kit::line('Marta', 'Luca, fa freddo lì?'),
                Kit::line('Luca', 'Sì, fa freddo e ci sono nuvole.'),
                Kit::line('Marta', 'Piove?'),
                Kit::line('Luca', 'No, non piove. Ma non c\'è il sole.'),
                Kit::line('Marta', 'Qui fa caldo e c\'è il sole.'),
            ], [
                Kit::question('What is the weather like where Luca is?', ['Cold and cloudy', 'Hot and sunny', 'Rainy'], 'Cold and cloudy'),
                Kit::question('Does it rain where Luca is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('What is the weather like where Marta is?', ['Hot and sunny', 'Cold and cloudy', 'Rainy'], 'Hot and sunny'),
            ], [
                Kit::question('Who asks about the weather first?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('Is there sun where Luca is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('il freddo', 'freddo'), Kit::word('la nuvola', 'nuvole'), Kit::word('il sole', 'sole'), Kit::word('il caldo', 'caldo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.primavera', 'In primavera c\'è il sole, ma piove.', 'In spring it is sunny, but it rains.', [Kit::word('la primavera', 'primavera'), Kit::word('il sole', 'sole'), Kit::form('c\'è')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.caldo-freddo', 'Ho caldo, ma lì fa freddo.', 'I am hot, but it is cold there.', [Kit::word('il caldo', 'caldo'), Kit::word('il freddo', 'freddo'), Kit::form('ho', true)], 'listen', homophoneNote: self::HO_NOTE),
            Kit::listenType($stage, 'task.listen_type.estate-non-piove', 'In estate non fa freddo e non piove.', 'In summer it is not cold and it does not rain.', [Kit::word('l\'estate', 'estate'), Kit::word('il freddo', 'freddo'), Kit::form('piove')], 'listen', homophoneNote: self::E_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.primavera', 'Che tempo fa in primavera?', 'What is the weather like in spring?', [['fa', 'c\'è'], ['bel', 'sole', 'nuvole', 'pioggia', 'freddo', 'caldo']], 'In primavera fa bel tempo.', [Kit::word('il tempo', 'tempo'), Kit::word('la primavera', 'primavera')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sole-inverno', 'C\'è il sole in inverno?', 'Is there sun in winter?', [['sì', 'no'], ['c\'è', 'nuvole', 'piove']], 'Sì, c\'è il sole.', [Kit::word('il sole', 'sole'), Kit::form('c\'è')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.hai-freddo', 'Hai freddo?', 'Are you cold?', [['sì', 'no'], ['ho', 'freddo']], 'Sì, ho freddo.', [Kit::word('il freddo', 'freddo'), Kit::form('ho', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.piove-estate', 'Piove in estate?', 'Does it rain in summer?', [['sì', 'no'], ['piove', 'pioggia']], 'No, in estate non piove.', [Kit::word('l\'estate', 'estate'), Kit::word('la pioggia', 'pioggia'), Kit::form('piove')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.estate-caldo-sole', 'In estate fa caldo e c\'è il sole.', 'In summer it is hot and there is sun.', [Kit::word('l\'estate', 'estate'), Kit::word('il caldo', 'caldo'), Kit::word('il sole', 'sole')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bel-tempo-freddo', 'Fa bel tempo, ma ho freddo.', 'The weather is nice, but I am cold.', [Kit::word('il tempo', 'tempo'), Kit::word('il freddo', 'freddo')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.inverno-nuvole', 'In winter it is cold and there are clouds.', ['In inverno fa freddo e ci sono nuvole.', 'Fa freddo e ci sono nuvole in inverno.'], [Kit::word('l\'inverno', 'inverno'), Kit::word('il freddo', 'freddo'), Kit::word('la nuvola', 'nuvole'), Kit::form('ci sono')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.caldo-autunno', 'It is hot in summer, but in autumn it rains.', ['Fa caldo in estate, ma in autunno piove.', 'In estate fa caldo, ma in autunno piove.'], [Kit::word('il caldo', 'caldo'), Kit::word('l\'estate', 'estate'), Kit::word('l\'autunno', 'autunno'), Kit::form('piove', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.freddo-autunno', 'I am cold in autumn.', ['Ho freddo in autunno.', 'In autunno ho freddo.', 'Io ho freddo in autunno.', 'In autunno io ho freddo.'], [Kit::word('il freddo', 'freddo'), Kit::word('l\'autunno', 'autunno'), Kit::form('ho', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bel-tempo', 'The weather is nice in spring.', ['Fa bel tempo in primavera.', 'In primavera fa bel tempo.'], [Kit::word('il tempo', 'tempo'), Kit::word('la primavera', 'primavera'), Kit::form('fa bel tempo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sole', 'Non ci sono nuvole e c\'è il ___.', 'There are no clouds and there is sun.', 'sole', Kit::word('il sole', 'sole'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pioggia', 'Non c\'è il sole, ci sono nuvole e ___.', 'There is no sun, there are clouds and rain.', 'pioggia', Kit::word('la pioggia', 'pioggia'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.sole-autunno', 'C\'è il sole in autunno?', 'Is there sun in autumn?', [Kit::word('il sole', 'sole'), Kit::word('l\'autunno', 'autunno'), Kit::form('c\'è')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.nuvole-pioggia', 'Con le nuvole e la pioggia ho freddo.', 'With the clouds and the rain I am cold.', [Kit::word('la nuvola', 'nuvole'), Kit::word('la pioggia', 'pioggia')], 'dictation', $set, homophoneNote: self::E_NOTE.' '.self::HO_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.freddo-caldo', 'Fa freddo o caldo in estate?', 'Is it cold or hot in summer?', [Kit::word('il freddo', 'freddo'), Kit::word('il caldo', 'caldo'), Kit::word('l\'estate', 'estate'), Kit::form('fa')], 'dictation', $set, homophoneNote: self::O_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.primavera', [
                Kit::line('Paolo', 'Anna, piove in primavera?'),
                Kit::line('Anna', 'Sì, piove e ci sono nuvole. Ma in estate c\'è il sole.'),
                Kit::line('Paolo', 'Anche qui c\'è il sole. Ho caldo!'),
            ], [
                Kit::question('When does it rain, according to Anna?', ['In spring', 'In summer', 'In winter'], 'In spring'),
                Kit::question('What is the weather like in summer?', ['Sunny', 'Rainy', 'Cold'], 'Sunny'),
                Kit::question('How does Paolo feel?', ['Hot', 'Cold', 'Ill'], 'Hot'),
            ], [
                Kit::question('Who asks the question?', ['Paolo', 'Anna', 'Nobody'], 'Paolo'),
                Kit::question('Does it rain in spring?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la primavera', 'primavera'), Kit::word('la nuvola', 'nuvole'), Kit::word('l\'estate', 'estate'), Kit::word('il sole', 'sole'), Kit::word('il caldo', 'caldo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.inverno', 'Read the conversation.', [
                Kit::line('Luca', 'Marta, fa freddo in inverno?'),
                Kit::line('Marta', 'Sì, fa freddo e piove. Ho freddo!'),
                Kit::line('Luca', 'E in autunno?'),
                Kit::line('Marta', 'In autunno ci sono nuvole, ma fa bel tempo.'),
            ], [
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
                Kit::question('How does Marta feel in winter?', ['Cold', 'Hot', 'Fine'], 'Cold'),
            ], [Kit::word('l\'inverno', 'inverno'), Kit::word('il freddo', 'freddo'), Kit::word('l\'autunno', 'autunno'), Kit::word('la nuvola', 'nuvole'), Kit::word('il tempo', 'tempo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.qui', 'Che tempo fa qui?', 'What is the weather like here?', [['fa', 'c\'è'], ['caldo', 'freddo', 'sole', 'nuvole', 'pioggia', 'bel']], 'Qui fa caldo.', [Kit::word('il tempo', 'tempo'), Kit::word('il caldo', 'caldo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.nuvole-autunno', 'Ci sono nuvole in autunno?', 'Are there clouds in autumn?', [['sì', 'no'], ['sono', 'nuvole']], 'Sì, ci sono nuvole in autunno.', [Kit::word('la nuvola', 'nuvole'), Kit::word('l\'autunno', 'autunno')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.caldo-estate', 'Hai caldo in estate?', 'Are you hot in summer?', [['sì', 'no'], ['ho', 'caldo']], 'Sì, ho caldo.', [Kit::word('il caldo', 'caldo'), Kit::word('l\'estate', 'estate')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.primavera-sole', 'In spring the weather is nice and there is sun.', ['In primavera fa bel tempo e c\'è il sole.', 'Fa bel tempo e c\'è il sole in primavera.'], [Kit::word('la primavera', 'primavera'), Kit::word('il tempo', 'tempo'), Kit::word('il sole', 'sole'), Kit::form('fa bel tempo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.autunno-freddo', 'In autumn it rains and I am cold.', ['In autunno piove e ho freddo.', 'In autunno piove e io ho freddo.', 'Piove e ho freddo in autunno.'], [Kit::word('l\'autunno', 'autunno'), Kit::word('il freddo', 'freddo'), Kit::form('piove', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.estate-nuvole', 'In summer it is hot and there are no clouds.', ['In estate fa caldo e non ci sono nuvole.', 'Fa caldo e non ci sono nuvole in estate.'], [Kit::word('l\'estate', 'estate'), Kit::word('il caldo', 'caldo'), Kit::word('la nuvola', 'nuvole'), Kit::form('fa')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.inverno-caldo', 'It is not hot in winter.', ['Non fa caldo in inverno.', 'In inverno non fa caldo.'], [Kit::word('l\'inverno', 'inverno'), Kit::word('il caldo', 'caldo'), Kit::form('fa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.primavera', 'Qui fa bel tempo in ___.', 'The weather is nice here in spring.', 'primavera', Kit::word('la primavera', 'primavera'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pioggia', 'Ci sono nuvole e ___.', 'There are clouds and rain.', 'pioggia', Kit::word('la pioggia', 'pioggia'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.pioggia-inverno', 'In inverno ci sono nuvole e pioggia.', 'In winter there are clouds and rain.', [Kit::word('la pioggia', 'pioggia'), Kit::word('la nuvola', 'nuvole'), Kit::word('l\'inverno', 'inverno'), Kit::form('ci sono')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sole-freddo', 'In estate c\'è il sole, ma ho freddo.', 'In summer it is sunny, but I am cold.', [Kit::word('l\'estate', 'estate'), Kit::word('il sole', 'sole'), Kit::word('il freddo', 'freddo'), Kit::form('ho', true)], 'dictation', $set, homophoneNote: self::HO_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.tempo-autunno', 'Che tempo fa in estate qui?', 'What is the weather like in summer here?', [Kit::word('il tempo', 'tempo'), Kit::word('l\'estate', 'estate')], 'dictation', $set),
        ];
    }
}
