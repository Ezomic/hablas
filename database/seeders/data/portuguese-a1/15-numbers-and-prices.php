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
    'contrast_note' => 'The tens are close to Spanish (trinta, quarenta, cinquenta, cem), but Portuguese writes 21 to 29 as three words (vinte e um), where Spanish writes one word (veintiuno). Costar becomes custar (o to u), and two everyday words differ: Spanish el ordenador is o computador, and Spanish la bolsa is not the shop bag in Portugal, because a bolsa is not the bag you get in a shop (a handbag is usually a mala).',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'trinta', 'translation_en' => 'thirty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'quarenta', 'translation_en' => 'forty', 'is_cognate' => false, 'part_of_speech' => 'number'],
        ['term' => 'cinquenta', 'translation_en' => 'fifty', 'is_cognate' => false, 'part_of_speech' => 'number', 'contrast_note' => 'Written cinquenta, with qu, where Spanish has cincuenta.'],
        ['term' => 'cem', 'translation_en' => 'one hundred', 'is_cognate' => false, 'part_of_speech' => 'number', 'contrast_note' => 'Spanish cien is cem in Portuguese. It sounds exactly like sem (without), so listen for the context.'],
        ['term' => 'o euro', 'translation_en' => 'euro', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'custar', 'translation_en' => 'to cost', 'is_cognate' => true, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish costar has o and cuesta has ue; Portuguese keeps u throughout: custa, custam.'],
        ['term' => 'pagar', 'translation_en' => 'to pay', 'is_cognate' => true, 'part_of_speech' => 'verb', 'contrast_note' => 'Same as Spanish pagar.'],
        ['term' => 'o cartão', 'translation_en' => 'card (bank card)', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish la tarjeta is a different word: Portuguese says o cartão, masculine, with a nasal ão ending (plural cartões).'],
        ['term' => 'o saco', 'translation_en' => 'bag', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'False friend with Spanish la bolsa: in Portugal a bolsa is not the bag you get in a shop; that is o saco (a handbag is usually a mala).'],
        ['term' => 'o computador', 'translation_en' => 'computer', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish el ordenador is o computador in Portugal.'],
    ],
    'grammar' => [
        [
            'title' => 'Numbers to a hundred and Quanto custa? / Quanto custam?',
            'explanation' => "To ask a price, use Quanto custa...? for one thing and Quanto custam...? for several things: Quanto custa o saco? Quanto custam os computadores? The verb agrees with the thing you buy, just like 'kost' and 'kosten' in Dutch (Hoeveel kost de tas? Hoeveel kosten de computers?). The answer uses the same verb: O saco custa vinte euros. Os computadores custam cem euros. For the bigger numbers you need the tens: trinta (30), quarenta (40), cinquenta (50), sessenta (60), setenta (70), oitenta (80), noventa (90) and cem (100). Put e between the tens and the unit: trinta e cinco is 35 and quarenta e dois is 42. This also holds from 21 on: vinte e cinco is 25, in three words. Portuguese says the tens first, like English ('thirty-five'), while Dutch says the unit first ('vijfendertig'). One euro is um euro; from two upwards you say euros. Cem is exactly 100, as in cem euros.",
            'error_tag_category' => null,
        ],
    ],
];
