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
        ['term' => 'il pane', 'translation_en' => 'bread', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il latte', 'translation_en' => 'milk', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => "l'acqua", 'translation_en' => 'water', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la frutta', 'translation_en' => 'fruit', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la carne', 'translation_en' => 'meat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il pesce', 'translation_en' => 'fish (as food)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il formaggio', 'translation_en' => 'cheese', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il vino', 'translation_en' => 'wine', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il chilo', 'translation_en' => 'kilo (kilogram)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la bottiglia', 'translation_en' => 'bottle', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Quantity with di: un chilo di formaggio',
            'explanation' => "When you name an amount of something, Italian puts di between the amount and the thing: un chilo di formaggio, una bottiglia di vino, due chili di frutta. English has 'of' in the same place (a kilo of cheese). Dutch has no extra word (een kilo kaas, een fles wijn), so this is the small word to remember: un chilo di formaggio, not un chilo formaggio. When you name what the amount is of, the thing has no article: una bottiglia di latte (a bottle of milk, not a specific milk). Before a vowel, di shortens to d' and joins the next word: una bottiglia d'acqua. The amount word agrees with the number: un chilo, due chili; una bottiglia, due bottiglie. The thing itself stays as it is, so you can use it with any food or drink: un chilo di carne, una bottiglia di vino.",
            'error_tag_category' => null,
        ],
    ],
];
