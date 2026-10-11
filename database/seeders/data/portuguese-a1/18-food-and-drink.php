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
    'contrast_note' => 'Several of these look different from Spanish: pan is o pão (nasal ão), queso is o queijo, pescado as food is o peixe, vino is o vinho (nh), and kilo is written o quilo. The biggest trap is the bottle: Spanish botella is a garrafa in Portuguese, and Spanish garrafa means a large carafe. Water is a água with the normal feminine article, where Spanish says el agua.',
    'interest_tags' => [InterestTag::Food, InterestTag::Cooking],
    'vocabulary' => [
        ['term' => 'o pão', 'translation_en' => 'bread', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish pan is o pão in Portuguese: ão is a nasal diphthong, and the plural is pães.'],
        ['term' => 'o leite', 'translation_en' => 'milk', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish la leche is o leite in Portuguese, masculine, with an ei sound.'],
        ['term' => 'a água', 'translation_en' => 'water', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish needs el agua, but Portuguese keeps the normal feminine article: a água, uma garrafa de água.'],
        ['term' => 'a fruta', 'translation_en' => 'fruit', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a carne', 'translation_en' => 'meat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o peixe', 'translation_en' => 'fish (as food)', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish splits pez (the live animal) and pescado (the food); Portuguese uses o peixe for both.'],
        ['term' => 'o queijo', 'translation_en' => 'cheese', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish queso is o queijo in Portuguese: the j sounds like the s in English measure.'],
        ['term' => 'o vinho', 'translation_en' => 'wine', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish vino is o vinho: nh is the same sound as Spanish ñ.'],
        ['term' => 'o quilo', 'translation_en' => 'kilo (kilogram)', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish writes kilo; Portuguese spells it quilo, with qu.'],
        ['term' => 'a garrafa', 'translation_en' => 'bottle', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'False friend: Spanish botella is a garrafa in Portuguese, while Spanish garrafa means a large carafe.'],
    ],
    'grammar' => [
        [
            'title' => 'Quantity with de: um quilo de queijo',
            'explanation' => "When you name an amount of something, Portuguese puts de between the amount and the thing: um quilo de queijo, uma garrafa de água, dois quilos de fruta. English has 'of' in the same place (a kilo of cheese). Dutch has no extra word (een kilo kaas, een fles water), so this is the small word to remember: um quilo de queijo, not um quilo queijo. In this pattern (an amount of a food or drink), there is no article after de: uma garrafa de leite, not uma garrafa do leite. The amount word agrees with the number: um quilo, dois quilos; uma garrafa, duas garrafas. The thing itself stays as it is, so you can use it with any food or drink: um quilo de carne, uma garrafa de vinho.",
            'error_tag_category' => null,
        ],
    ],
];
