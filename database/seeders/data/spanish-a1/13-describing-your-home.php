<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'describing-your-home',
    'title' => 'Describing your home',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Describe where you live: the rooms, the furniture and where things are.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'la casa', 'translation_en' => 'house / home', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el piso', 'translation_en' => 'flat / apartment', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la cocina', 'translation_en' => 'kitchen', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el salón', 'translation_en' => 'living room', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el dormitorio', 'translation_en' => 'bedroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el jardín', 'translation_en' => 'garden', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la mesa', 'translation_en' => 'table', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la silla', 'translation_en' => 'chair', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la cama', 'translation_en' => 'bed', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la ventana', 'translation_en' => 'window', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Hay versus estar, and prepositions of place',
            'explanation' => 'Use hay (there is, there are) to say that something exists or to introduce it: "Hay una mesa en la cocina." It never changes, so it is the same for one thing or many: "Hay dos sillas." Use está or están (is, are) to say where a thing you both know is: "La mesa está en la cocina." A handy rule: with un, una or a number (something new) use hay; with el, la, mi or tu (something known) use está. In Dutch, "er is" is hay and "is" plus a place is está. To say where, use en (in, on), encima de (on top of), debajo de (under), al lado de (next to) and cerca de (near): "La silla está debajo de la mesa." De joins el into del: "cerca del jardín".',
            'error_tag_category' => null,
        ],
    ],
];
