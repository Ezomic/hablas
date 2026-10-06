<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'numbers-and-prices',
    'title' => 'Numbers and prices',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Understand and say numbers up to a hundred, ask what something costs and say how you want to pay.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'treinta', 'translation_en' => 'thirty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cuarenta', 'translation_en' => 'forty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cincuenta', 'translation_en' => 'fifty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cien', 'translation_en' => 'one hundred', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'el euro', 'translation_en' => 'euro', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'costar', 'translation_en' => 'to cost', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'pagar', 'translation_en' => 'to pay', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'la tarjeta', 'translation_en' => 'card (bank card)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la bolsa', 'translation_en' => 'bag', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el ordenador', 'translation_en' => 'computer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Numbers to a hundred and ¿Cuánto cuesta? / ¿Cuánto cuestan?',
            'explanation' => "To ask a price, use ¿Cuánto cuesta...? for one thing and ¿Cuánto cuestan...? for several things: ¿Cuánto cuesta la bolsa? ¿Cuánto cuestan los ordenadores? The verb agrees with the thing you buy, just like 'kost' and 'kosten' in Dutch (Hoeveel kost de tas? Hoeveel kosten de computers?). The answer uses the same verb: La bolsa cuesta veinte euros. Los ordenadores cuestan cien euros. For the bigger numbers you need the tens: treinta (30), cuarenta (40), cincuenta (50), sesenta (60), setenta (70), ochenta (80), noventa (90) and cien (100). From 21 to 29 Spanish writes one word: veintiuno, veintidós, veinticinco. From 31 on, put y in between: treinta y cinco is 35 and cuarenta y dos is 42. Spanish says the tens first, like English ('thirty-five'), while Dutch says the unit first ('vijfendertig'). One euro is un euro; from two upwards you say euros. Before euros, 21 is veintiún euros and 31 is treinta y un euros.",
            'error_tag_category' => null,
        ],
    ],
];
