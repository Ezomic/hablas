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
        ['term' => 'le pain', 'translation_en' => 'bread', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le lait', 'translation_en' => 'milk', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'eau', 'translation_en' => 'water', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le fruit', 'translation_en' => 'fruit', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la viande', 'translation_en' => 'meat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le poisson', 'translation_en' => 'fish (as food)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le fromage', 'translation_en' => 'cheese', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le vin', 'translation_en' => 'wine', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le kilo', 'translation_en' => 'kilo (kilogram)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la bouteille', 'translation_en' => 'bottle', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Quantity with de: un kilo de fromage',
            'explanation' => "When you name an amount of something, French puts de between the amount and the thing: un kilo de fromage, une bouteille de lait, deux kilos de fruits. English has 'of' in the same place (a kilo of cheese). Dutch has no extra word (een kilo kaas, een fles water), so this is the small word to remember: un kilo de fromage, not un kilo fromage. After de, the thing has no article: une bouteille de lait, not une bouteille du lait. Before a vowel, de becomes d': une bouteille d'eau, not une bouteille de eau. The amount word agrees with the number: un kilo, deux kilos; une bouteille, deux bouteilles. The same de follows pas: Il n'y a pas de vin. Without an amount, French needs du, de la, de l' or des for 'some', where English and Dutch use nothing: J'ai du pain, de la viande, de l'eau, des fruits.",
            'error_tag_category' => null,
        ],
    ],
];
