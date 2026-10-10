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
        ['term' => 'l\'ora', 'translation_en' => 'time (on the clock) / hour', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'orologio', 'translation_en' => 'clock / watch', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il giorno', 'translation_en' => 'day', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la settimana', 'translation_en' => 'week', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'oggi', 'translation_en' => 'today', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'domani', 'translation_en' => 'tomorrow', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'lunedì', 'translation_en' => 'Monday', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'domenica', 'translation_en' => 'Sunday', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'in punto', 'translation_en' => 'on the dot (exactly, for the hour)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'e mezza', 'translation_en' => 'half past', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Telling the time with essere, and alle for "at"',
            'explanation' => "In Dutch and English the time is 'het is' or 'it is' followed by the hour. Italian also uses essere, but the verb agrees with the hour. One o'clock is singular, so we say È l'una. Every other hour is plural, so we say Sono le due, Sono le tre, Sono le quattro, and the article changes too (l'una, le due). Add the minutes with e: Sono le due e mezza. Watch out as a Dutch speaker: Sono le due e mezza is 2:30, so it names the hour that has already started, while Dutch says half drie for 2:30. To say at what time something happens, use alle with a plural hour: alle tre, alle cinque. For one o'clock it is all'una. You ask the time with Che ora è? or Che ore sono?, and ask when something happens with A che ora...? At noon and midnight Italians usually say È mezzogiorno and È mezzanotte, but Sono le dodici is also fine.",
            'error_tag_category' => null,
        ],
    ],
];
