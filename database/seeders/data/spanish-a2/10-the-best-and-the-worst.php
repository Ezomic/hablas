<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'the-best-and-the-worst',
    'title' => 'The best and the worst',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Say which is the best, the worst or the most ... of a group.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'famoso', 'translation_en' => 'famous', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'antiguo', 'translation_en' => 'old, ancient', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'bonito', 'translation_en' => 'pretty, nice', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'tranquilo', 'translation_en' => 'quiet, calm', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'ruidoso', 'translation_en' => 'noisy', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'grande', 'translation_en' => 'big, large', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'el barrio', 'translation_en' => 'neighbourhood', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la plaza', 'translation_en' => 'square', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la catedral', 'translation_en' => 'cathedral', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el turista', 'translation_en' => 'tourist', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'The most and the best: el más, el mejor, -ísimo',
            'explanation' => "To say that something is the most ... of a group, use the article + más + adjective: 'La catedral es la más antigua del pueblo' (the cathedral is the oldest in the village). Dutch adds -st to the adjective (de oudste, de grootste), but Spanish never does: it always puts más in front. The article and the adjective agree with the noun: el barrio más tranquilo, la plaza más bonita, los barrios más ruidosos. The noun can come before más (el museo más famoso) or be left out (el más famoso). When the noun comes first, the article stays before the noun and there is no second article: la catedral más famosa, not la catedral la más famosa. After a superlative Spanish says de where English and Dutch say 'in' or 'of' (van, in): el más grande de España, la mejor del barrio. Do not use que here: que is for comparing two things (más grande que), de is for the group. Good and bad have their own forms: el mejor (the best) and el peor (the worst), plural los mejores and las peores. Say el mejor, never más bueno. For 'extremely', drop the final vowel of the adjective and add -ísimo: famoso becomes famosísimo, grande becomes grandísimo, and it agrees like an ordinary adjective (la plaza es grandísima, extremely big).",
            'error_tag_category' => null,
        ],
    ],
];
