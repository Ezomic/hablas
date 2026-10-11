<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'talking-about-hobbies',
    'title' => 'Talking about hobbies',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what you like to do in your free time, ask someone about their hobbies and talk about sport and music.',
    'interest_tags' => [InterestTag::Music, InterestTag::Football],
    'vocabulary' => [
        ['term' => 'le sport', 'translation_en' => 'sport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la musique', 'translation_en' => 'music', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le football', 'translation_en' => 'football (soccer)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le film', 'translation_en' => 'film / movie', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le temps libre', 'translation_en' => 'free time', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'danser', 'translation_en' => 'to dance', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'chanter', 'translation_en' => 'to sing', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'jouer', 'translation_en' => 'to play (a sport or game)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'lire', 'translation_en' => 'to read', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'le livre', 'translation_en' => 'book', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Aimer with le, la, les, and jouer au',
            'explanation' => "Dutch says 'ik hou van muziek', with the preposition van and no article. French says what you like with aimer, which takes no preposition, and it almost always keeps the article: 'J'aime la musique', never 'J'aime musique'. Use le or la for one thing (J'aime le sport. J'aime la musique.) and les for several (J'aime les films.). The same article is used when you mean the thing in general. Before a vowel je becomes j': j'aime. With tu the verb gets an -s: tu aimes. With Paul or Anne (il or elle) the verb has no -s: Paul aime, Paul joue. After aimer you can put a verb in the infinitive: J'aime danser. To say you do not like something, wrap the verb in ne and pas: je n'aime pas danser. The article stays after the negation: je n'aime pas les films. For a sport use jouer with au: 'Je joue au football', because à plus le becomes au (jouer à la for a feminine noun, jouer aux for a plural).",
            'error_tag_category' => null,
        ],
    ],
];
