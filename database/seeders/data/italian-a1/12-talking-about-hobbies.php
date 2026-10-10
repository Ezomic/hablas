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
        ['term' => 'lo sport', 'translation_en' => 'sport', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la musica', 'translation_en' => 'music', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il calcio', 'translation_en' => 'football (soccer)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il film', 'translation_en' => 'film / movie', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il tempo libero', 'translation_en' => 'free time', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'ballare', 'translation_en' => 'to dance', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'cantare', 'translation_en' => 'to sing', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'giocare', 'translation_en' => 'to play (a sport or game)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'leggere', 'translation_en' => 'to read', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'il libro', 'translation_en' => 'book', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Mi piace and mi piacciono: saying what you like',
            'explanation' => "Italian does not say 'I like music' with 'I' as the subject. It says 'Mi piace la musica', which is closer to 'music is pleasing to me' (Dutch says 'Ik hou van muziek' or 'Ik vind muziek leuk' with 'ik' as the subject, so this is a real switch; only 'Dat bevalt me' works the same way, and it is not how you name a hobby). The thing you like is the subject, so the verb agrees with it: piace for one thing or for a verb (Mi piace la musica. Mi piace ballare.) and piacciono for several things (Mi piacciono gli sport.). The small word in front shows who likes it: mi for I, ti for you (informal). To ask, use Ti piace...? or Ti piacciono...?. To say you do not like something, put non before mi: Non mi piace ballare. After a verb such as ballare or leggere, always use piace, even when you list two verbs. Note that Italian keeps the article when you talk about something in general: Mi piace il calcio, Mi piace la musica, where English and Dutch leave it out.",
            'error_tag_category' => null,
        ],
    ],
];
