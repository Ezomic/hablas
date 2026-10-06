<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\ErrorTagCategory;
use App\Enums\Skill;

return [
    'slug' => 'this-and-that',
    'title' => 'This and that',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Point at things and people near and far, ask what something is and choose between two things.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el vaso', 'translation_en' => 'glass (for drinking)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el bolso', 'translation_en' => 'bag, handbag', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el coche', 'translation_en' => 'car', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la mochila', 'translation_en' => 'backpack', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la foto', 'translation_en' => 'photo', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el regalo', 'translation_en' => 'gift, present', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el paraguas', 'translation_en' => 'umbrella', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el bolígrafo', 'translation_en' => 'pen', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el sombrero', 'translation_en' => 'hat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'preferir', 'translation_en' => 'to prefer', 'is_cognate' => true, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'This and that: este, ese and esto',
            'explanation' => "To point at something, Spanish puts a small word in front of the noun, and it matches the noun in gender and number. For 'this' (near me) use este for a masculine word, esta for a feminine word, estos for several masculine words and estas for several feminine words: este vaso, esta mochila, estos coches, estas fotos. For 'that' (near you, or the thing you are talking about) use ese, esa, esos, esas: ese bolso, esa foto. Aquel (aquel coche) is 'that over there', far from both of you; you only need to recognise it for now. Dutch does something similar: deze tafel and dit boek, die tafel and dat boek, where the choice depends on de or het. Spanish looks at masculine or feminine, and also at one or several. Dutch and English have two steps (this and that); Spanish has three: este, ese and aquel. When you do not name the thing, use the neutral esto or eso. They never change: ¿Qué es esto? Es un regalo. A common mistake is to use este with a feminine word: say esta mochila, not este mochila.",
            'error_tag_category' => ErrorTagCategory::WrongGender,
        ],
    ],
];
