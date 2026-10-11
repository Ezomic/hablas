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
    'contrast_note' => 'Spanish names Monday to Friday (lunes, martes), but Portuguese numbers them and adds -feira (segunda-feira, terça-feira). Spanish mañana means both tomorrow and morning, while Portuguese splits them: amanhã is tomorrow and a manhã is the morning. Listen for the nasal vowel in amanhã and manhã.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'a hora', 'translation_en' => 'time (on the clock) / hour', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o relógio', 'translation_en' => 'clock / watch', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o dia', 'translation_en' => 'day', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'a semana', 'translation_en' => 'week', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'hoje', 'translation_en' => 'today', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'amanhã', 'translation_en' => 'tomorrow', 'is_cognate' => false, 'part_of_speech' => 'adverb', 'contrast_note' => 'Amanhã is tomorrow. A manhã, written as two words, is the morning. Spanish uses mañana for both.'],
        ['term' => 'a segunda-feira', 'translation_en' => 'Monday', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish lunes is a named day, but Portuguese segunda-feira literally means second weekday (segunda = second). People often say just segunda.'],
        ['term' => 'o domingo', 'translation_en' => 'Sunday', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'em ponto', 'translation_en' => 'on the dot (exactly, for the hour)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'e meia', 'translation_en' => 'half past', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Telling the time with ser, and à(s) for "at"',
            'explanation' => "In Dutch and English the time is 'het is' or 'it is' followed by the hour. Portuguese also uses ser, but the verb agrees with the hour. One o'clock is singular, so we say É uma hora. Every other numbered hour is plural, so we say São duas horas, São três horas, and the number changes too (uma, duas). Add the minutes with e: São duas e meia. Watch out as a Dutch speaker: São duas e meia is 2:30, so it names the hour that has already started, while Dutch says half drie for 2:30. To say at what time something happens, use à or às: à uma, às três. You ask the time with Que horas são? (always the plural horas) and ask when something happens with A que horas...?",
            'error_tag_category' => null,
        ],
    ],
];
