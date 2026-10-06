<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'food-and-drink',
    'title' => 'Food and drink',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Name everyday foods and drinks, ask a seller for a kilo or a bottle of something at a market or shop, and say what you have.',
    'interest_tags' => [InterestTag::Food, InterestTag::Cooking],
    'vocabulary' => [
        ['term' => 'el pan', 'translation_en' => 'bread', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la leche', 'translation_en' => 'milk', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el agua', 'translation_en' => 'water', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la fruta', 'translation_en' => 'fruit', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la carne', 'translation_en' => 'meat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el pescado', 'translation_en' => 'fish (as food)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el queso', 'translation_en' => 'cheese', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el vino', 'translation_en' => 'wine', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el kilo', 'translation_en' => 'kilo (kilogram)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la botella', 'translation_en' => 'bottle', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Quantity with de: un kilo de queso',
            'explanation' => "When you name an amount of something, Spanish puts de between the amount and the thing: un kilo de queso, una botella de agua, dos kilos de fruta, un poco de pan. English has 'of' in the same place (a kilo of cheese). Dutch has no extra word (een kilo kaas, een fles water), so this is the small word to remember: un kilo de queso, not un kilo queso. After de, the thing has no article: una botella de leche, not una botella de la leche. The amount word agrees with the number: un kilo, dos kilos; una botella, dos botellas. The thing itself stays as it is, so you can use it with any food or drink: un kilo de carne, una botella de vino.",
            'error_tag_category' => null,
        ],
    ],
];
