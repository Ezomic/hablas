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
        ['term' => 'il tempo', 'translation_en' => 'weather', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il freddo', 'translation_en' => 'cold', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il caldo', 'translation_en' => 'heat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il sole', 'translation_en' => 'sun', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la pioggia', 'translation_en' => 'rain', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la nuvola', 'translation_en' => 'cloud', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'inverno', 'translation_en' => 'winter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'estate', 'translation_en' => 'summer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la primavera', 'translation_en' => 'spring', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'autunno', 'translation_en' => 'autumn (fall)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Fa freddo, c\'è il sole, piove: talking about the weather',
            'explanation' => "For temperature and good or bad weather Italian does not use 'to be'. It says 'fa freddo', which is closer to 'it makes cold' (Dutch says 'het is koud', Italian says 'fa freddo'). Use fa with freddo, caldo and bel tempo: Fa freddo. Fa caldo. Fa bel tempo. Use c'è for one thing that is there and ci sono for several: C'è il sole. (There is sun.) Ci sono nuvole. (There are clouds.) Rain has its own verb: Piove (it rains, like Dutch 'het regent'). To ask, say Che tempo fa? and to place the weather in a season, use in: In inverno fa freddo. Watch the difference between the weather and you: Fa freddo describes the weather, while Ho freddo says that you feel cold (literally 'I have cold', like Dutch 'ik heb het koud'). Do not say è freddo about the weather.",
            'error_tag_category' => null,
        ],
    ],
];
