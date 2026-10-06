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
        ['term' => 'el deporte', 'translation_en' => 'sport', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la música', 'translation_en' => 'music', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el fútbol', 'translation_en' => 'football (soccer)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la película', 'translation_en' => 'film / movie', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el tiempo libre', 'translation_en' => 'free time', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'bailar', 'translation_en' => 'to dance', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'cantar', 'translation_en' => 'to sing', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'jugar', 'translation_en' => 'to play (a sport or game)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'leer', 'translation_en' => 'to read', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el libro', 'translation_en' => 'book', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Me gusta and me gustan: saying what you like',
            'explanation' => "Spanish does not say 'I like music' with 'I' as the subject. It says 'Me gusta la música', which is closer to 'music is pleasing to me' (Dutch has the same idea in 'dat bevalt me', where 'dat' is the subject). The thing you like is the subject, so the verb agrees with it: gusta for one thing or for a verb (Me gusta la música. Me gusta bailar.) and gustan for several things (Me gustan los deportes.). The small word in front shows who likes it: me for I, te for you (informal). To ask, use ¿Te gusta...? or ¿Te gustan...?. To say you do not like something, put no before me: No me gusta bailar. After a verb such as bailar or leer, always use gusta, even when you list two verbs.",
            'error_tag_category' => null,
        ],
    ],
];
