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
        ['term' => 'le temps', 'translation_en' => 'weather', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'froid', 'translation_en' => 'cold', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'chaud', 'translation_en' => 'hot / warm', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'le soleil', 'translation_en' => 'sun', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la pluie', 'translation_en' => 'rain', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le nuage', 'translation_en' => 'cloud', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => "l'hiver", 'translation_en' => 'winter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => "l'été", 'translation_en' => 'summer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le printemps', 'translation_en' => 'spring', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => "l'automne", 'translation_en' => 'autumn (fall)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Il fait froid, il y a des nuages, il pleut: talking about the weather',
            'explanation' => "French does not say 'it is cold' with the verb to be. It says 'il fait froid', which is closer to 'it makes cold' (Dutch says 'het is koud', French says il fait froid). Use il fait with froid, chaud and beau: Il fait froid. Il fait chaud. Il fait beau. Use il y a for things that are there: Il y a des nuages. (There are clouds.) Il y a du soleil. (It is sunny.) Rain has its own verb: Il pleut (it rains, like Dutch 'het regent'). To ask, say Quel temps fait-il ? and to place the weather in a season, use en: En hiver, il fait froid. Only spring is different: au printemps. Watch the difference between the weather and you: Il fait froid describes the weather, while J'ai froid says that you feel cold (literally 'I have cold', like Dutch 'ik heb het koud'). With il y a, use du for le soleil, de la for la pluie and des for several clouds: Il y a du soleil, de la pluie, des nuages. To say no, put ne ... pas around the verb: Il ne pleut pas, Je n'ai pas chaud. Do not say il est froid about the weather or je suis froid about yourself.",
            'error_tag_category' => null,
        ],
    ],
];
