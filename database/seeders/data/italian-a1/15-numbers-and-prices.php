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
        ['term' => 'trenta', 'translation_en' => 'thirty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'quaranta', 'translation_en' => 'forty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cinquanta', 'translation_en' => 'fifty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cento', 'translation_en' => 'one hundred', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => "l'euro", 'translation_en' => 'euro', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'costare', 'translation_en' => 'to cost', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'pagare', 'translation_en' => 'to pay', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'la carta', 'translation_en' => 'card (bank card)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la borsa', 'translation_en' => 'bag', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il computer', 'translation_en' => 'computer', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Numbers to a hundred and Quanto costa? / Quanto costano?',
            'explanation' => "To ask a price, use Quanto costa...? for one thing and Quanto costano...? for several things: Quanto costa la borsa? Quanto costano le borse? The verb agrees with the thing you buy, just like 'kost' and 'kosten' in Dutch (Hoeveel kost de tas? Hoeveel kosten de tassen?). The answer uses the same verb: La borsa costa venti euro. I computer costano cento euro. The words euro and computer do not change in the plural: un euro, dieci euro, il computer, i computer. For the bigger numbers you need the tens: trenta (30), quaranta (40), cinquanta (50), sessanta (60), settanta (70), ottanta (80), novanta (90) and cento (100). Italian writes the tens and the unit together as one word, tens first like English: trentacinque is 35 and quarantadue is 42, while Dutch says the unit first (vijfendertig). Before uno and otto the tens lose their last vowel (ventuno, trentuno, quarantotto), and with tre the last e gets an accent: trentatré. At the till you also hear Sono trenta euro (that is 30 euros). In Dutch and English you add an s ('euro's', 'computers'), Italian does not.",
            'error_tag_category' => null,
        ],
    ],
];
