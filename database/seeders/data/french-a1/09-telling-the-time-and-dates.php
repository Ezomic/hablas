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
        ['term' => 'l\'heure', 'translation_en' => 'time (on the clock) / hour', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la montre', 'translation_en' => 'watch', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le jour', 'translation_en' => 'day', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la semaine', 'translation_en' => 'week', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'aujourd\'hui', 'translation_en' => 'today', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'demain', 'translation_en' => 'tomorrow', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'lundi', 'translation_en' => 'Monday', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'dimanche', 'translation_en' => 'Sunday', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'pile', 'translation_en' => 'on the dot (exactly, for the hour)', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'et demie', 'translation_en' => 'half past', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Telling the time with il est ... heure(s), and à for "at"',
            'explanation' => "In Dutch and English the time is 'het is' or 'it is' followed by the hour. French says il est, and the word heure (hour) agrees with the number. One o'clock is singular: Il est une heure. Every other hour is plural and takes an s: Il est deux heures, Il est trois heures. Add the half hour with et demie: Il est deux heures et demie. Minutes follow the hour directly, with no word for past: Il est une heure vingt. Watch out as a Dutch speaker: Il est deux heures et demie is 2:30, so it names the hour that has already started, while Dutch says half drie for 2:30. To say at what time something happens, use à: à une heure, à trois heures. You ask the time with Quelle heure est-il ? In casual speech people also say Il est quelle heure ? Ask when something happens with À quelle heure ... ? Days of the week have no capital letter in French, and lundi alone means on Monday, while le lundi means every Monday.",
            'error_tag_category' => null,
        ],
    ],
];
