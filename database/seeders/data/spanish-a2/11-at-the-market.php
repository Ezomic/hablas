<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'at-the-market',
    'title' => 'At the market',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Buy food and say what you want to take, using pronouns for things already mentioned.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el mercado', 'translation_en' => 'market', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la manzana', 'translation_en' => 'apple', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el tomate', 'translation_en' => 'tomato', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el huevo', 'translation_en' => 'egg', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la naranja', 'translation_en' => 'orange', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la docena', 'translation_en' => 'dozen', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'llevar', 'translation_en' => 'to take / to carry', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'comprar', 'translation_en' => 'to buy', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'necesitar', 'translation_en' => 'to need', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'fresco', 'translation_en' => 'fresh', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
    ],
    'grammar' => [
        [
            'title' => 'Direct object pronouns: lo, la, los, las',
            'explanation' => "When the thing is already known, replace it with a pronoun that matches its gender and number: lo (masculine, one), la (feminine, one), los (masculine, more than one), las (feminine, more than one). '¿El pan? Lo compro' (The bread? I buy it), '¿La leche? La necesito', '¿Los tomates? Los llevo', '¿Las naranjas? Las llevo'. Dutch picks hem (de-words), het (het-words) or ze (plural), but Spanish only looks at the Spanish gender and number of the noun: el pan takes lo, la leche takes la, even where the Dutch word has a different gender. The pronoun goes directly before the conjugated verb: 'Lo compro', never 'Compro lo'. With ir a, necesitar or another verb followed by an infinitive you can put it in front of the whole phrase or on the end of the infinitive: 'Lo voy a comprar' or 'Voy a comprarlo'. Do not mix up the pronouns la, los, las with the articles: 'La compro' (I buy it) has no noun after it, 'Compro la leche' (I buy the milk) has one.",
            'error_tag_category' => null,
        ],
    ],
];
