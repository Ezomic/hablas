<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'plans-for-next-week',
    'title' => 'Plans for next week',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Make plans for the coming days and weeks.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'la cita', 'translation_en' => 'appointment / date', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el concierto', 'translation_en' => 'concert', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el cumpleaños', 'translation_en' => 'birthday', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el fin de semana', 'translation_en' => 'weekend', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la reunión', 'translation_en' => 'meeting', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el partido', 'translation_en' => 'match / game', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la excursión', 'translation_en' => 'trip / outing', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el teatro', 'translation_en' => 'theatre', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'descansar', 'translation_en' => 'to rest', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'pasear', 'translation_en' => 'to go for a walk', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Plans with ir a + infinitive, and the time words for the future',
            'explanation' => "To say what you are going to do, use the present of ir + a + infinitive: 'Voy a descansar' (I am going to rest), 'Vamos a ver el partido' (we are going to watch the match). It works like Dutch 'ik ga rusten' and English 'I am going to rest', but Spanish needs the little word a between ir and the infinitive: 'Voy descansar' is wrong. Only ir changes (voy a, vas a, va a, vamos a, vais a, van a); the infinitive never changes. Do not mix it up with ir + a + place: 'Voy a casa' (I am going home) has a noun after a, 'Voy a descansar' has a verb. Spanish plans are tied to time words: mañana (tomorrow), pasado mañana (the day after tomorrow, Dutch 'overmorgen'), la semana que viene or la próxima semana (next week, Dutch 'volgende week'), el próximo lunes (next Monday). Próximo normally goes before the day or the week and agrees with it: el próximo domingo, la próxima semana. It can also follow the noun: la semana próxima. Note that el lunes alone means 'on Monday', without next.",
            'error_tag_category' => null,
        ],
    ],
];
