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
        ['term' => 'trente', 'translation_en' => 'thirty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'quarante', 'translation_en' => 'forty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cinquante', 'translation_en' => 'fifty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cent', 'translation_en' => 'one hundred', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'l\'euro', 'translation_en' => 'euro', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'coûter', 'translation_en' => 'to cost', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'payer', 'translation_en' => 'to pay', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'la carte', 'translation_en' => 'card (bank card)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le sac', 'translation_en' => 'bag', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'ordinateur', 'translation_en' => 'computer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Numbers to a hundred and Combien coûte / coûtent ?',
            'explanation' => "To ask a price, use Combien coûte...? for one thing and Combien coûtent...? for several things: Combien coûte le sac ? Combien coûtent les ordinateurs ? The verb agrees with the thing you buy, just like 'kost' and 'kosten' in Dutch (Hoeveel kost de tas? Hoeveel kosten de computers?). Careful: coûte and coûtent sound exactly the same, because the ending -ent is silent. You hear the difference in le/les and in il/ils, and you must write the right one. The answer uses the same verb: Le sac coûte vingt euros. Les ordinateurs coûtent cent euros. For the bigger numbers you need the tens: trente (30), quarante (40), cinquante (50), soixante (60) and cent (100). From 22 on, French joins the unit to the tens with a hyphen: vingt-cinq is 25, trente-cinq is 35 and quarante-deux is 42. Only 21, 31, 41 and so on use et, without hyphens: vingt et un, trente et un. French says the tens first, like English ('thirty-five'), while Dutch says the unit first ('vijfendertig'). One euro is un euro; from two upwards you say euros, and the s is silent. Cent is exactly 100: cent euros. Careful: Dutch 'cent' is a coin, but French cent is the number 100.",
            'error_tag_category' => null,
        ],
    ],
];
