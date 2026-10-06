<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'weather-and-seasons',
    'title' => 'Weather and seasons',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Say what the weather is like, ask about it and talk about the four seasons.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'el tiempo', 'translation_en' => 'weather', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el frío', 'translation_en' => 'cold', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el calor', 'translation_en' => 'heat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el sol', 'translation_en' => 'sun', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la lluvia', 'translation_en' => 'rain', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la nube', 'translation_en' => 'cloud', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el invierno', 'translation_en' => 'winter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el verano', 'translation_en' => 'summer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la primavera', 'translation_en' => 'spring', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el otoño', 'translation_en' => 'autumn (fall)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Hace frío, hay nubes, llueve: talking about the weather',
            'explanation' => "Spanish does not say 'it is cold' with the verb to be. It says 'hace frío', which is closer to 'it makes cold' (Dutch says 'het is koud', Spanish says 'hace frío'). Use hace with frío, calor, sol and buen tiempo: Hace frío. Hace calor. Hace sol. Hace buen tiempo. Use hay for things that are there: Hay nubes. (There are clouds.) Rain has its own verb: Llueve (it rains, like Dutch 'het regent'). To ask, say ¿Qué tiempo hace? and to place the weather in a season, use en: En invierno hace frío. Watch the difference between the weather and you: Hace frío describes the weather, while Tengo frío says that you feel cold (literally 'I have cold', like Dutch 'ik heb het koud'). Do not say es frío about the weather. Hay sol is also heard, but hace sol is the usual way to say it is sunny.",
            'error_tag_category' => null,
        ],
    ],
];
