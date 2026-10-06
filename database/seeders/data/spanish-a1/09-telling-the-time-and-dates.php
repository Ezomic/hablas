<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'telling-the-time-and-dates',
    'title' => 'Telling the time and dates',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Say and understand the time, the days of the week and simple dates, and ask when something happens.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'la hora', 'translation_en' => 'time (on the clock) / hour', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el reloj', 'translation_en' => 'clock / watch', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el día', 'translation_en' => 'day', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la semana', 'translation_en' => 'week', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'hoy', 'translation_en' => 'today', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'mañana', 'translation_en' => 'tomorrow', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'el lunes', 'translation_en' => 'Monday', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el domingo', 'translation_en' => 'Sunday', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'en punto', 'translation_en' => 'on the dot (exactly, for the hour)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'y media', 'translation_en' => 'half past', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Telling the time with ser, and a la(s) for "at"',
            'explanation' => "In Dutch and English the time is 'het is' or 'it is' followed by the hour. Spanish also uses ser, but the verb agrees with the hour. One o'clock is singular, so we say Es la una. Every other hour is plural, so we say Son las dos, Son las tres, Son las cuatro, and the article changes too (la una, las dos). Add the minutes with y: Son las dos y media. Watch out as a Dutch speaker: Son las dos y media is 2:30, so it names the hour that has already started, while Dutch says half drie for 2:30. To say at what time something happens, use a la or a las: a la una, a las tres. You ask the time with ¿Qué hora es? and ask when something happens with ¿A qué hora...?",
            'error_tag_category' => null,
        ],
    ],
];
