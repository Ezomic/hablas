<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'eating-out',
    'title' => 'Eating out',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Order a full meal, say what you like and ask for the bill.',
    'interest_tags' => [InterestTag::Food],
    'vocabulary' => [
        ['term' => 'el plato', 'translation_en' => 'dish / course', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la sopa', 'translation_en' => 'soup', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la ensalada', 'translation_en' => 'salad', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el postre', 'translation_en' => 'dessert', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el helado', 'translation_en' => 'ice cream', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el zumo', 'translation_en' => 'juice', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la cerveza', 'translation_en' => 'beer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'pedir', 'translation_en' => 'to order / to ask for', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'picante', 'translation_en' => 'spicy', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'dulce', 'translation_en' => 'sweet', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
    ],
    'grammar' => [
        [
            'title' => 'Quantities and wishes: un poco de, algo de, nada de, mucho, poco and me apetece',
            'explanation' => "To say how much of a food or drink, put de between the amount and the thing: 'un poco de sopa' (a little soup), 'algo de postre' (some dessert), 'nada de helado' (no ice cream at all). Dutch says 'een beetje soep' with nothing in between, but Spanish always needs the de. To say none at all, use nada de: 'No pido nada de postre' (plainly 'No pido postre' is fine too). Mucho and poco are different: they stand straight in front of the thing, with no de, and they agree with it: 'mucha sopa', 'mucho zumo', 'poca ensalada', 'poco postre'. Dutch 'veel' never changes, so watch the ending. To say what you feel like eating or drinking, use 'me apetece' plus the thing: 'Me apetece un helado' (Dutch: ik heb zin in een ijsje). To state a preference, use 'prefiero': 'Prefiero la sopa'. In a restaurant you order with the verb pedir: 'pido' (I order), 'pedimos' (we order), and you ask for the bill with 'La cuenta, por favor.'",
            'error_tag_category' => null,
        ],
    ],
];
