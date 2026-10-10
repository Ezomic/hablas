<?php

declare(strict_types=1);

use App\Enums\ContextTag;
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
        ['term' => 'le verre', 'translation_en' => 'glass (for drinking)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le sac', 'translation_en' => 'bag, handbag', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la voiture', 'translation_en' => 'car', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le sac à dos', 'translation_en' => 'backpack', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la photo', 'translation_en' => 'photo', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le cadeau', 'translation_en' => 'gift, present', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le parapluie', 'translation_en' => 'umbrella', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le stylo', 'translation_en' => 'pen', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le chapeau', 'translation_en' => 'hat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'préférer', 'translation_en' => 'to prefer', 'is_cognate' => true, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'This and that: ce, cette, ces and ça',
            'explanation' => "To point at something, French puts a small word in front of the noun, and it matches the noun. Use ce for a masculine word (ce verre, ce sac), cette for a feminine word (cette voiture, cette photo) and ces for several things, masculine or feminine (ces stylos, ces photos). Before a masculine word that starts with a vowel sound, ce becomes cet: cet arbre. French has no separate small words for 'this' and 'that': ce sac can mean both. To make the difference clear, add -ci (here, near) or -là (there, further away) after the noun: ce sac-ci, ce sac-là. Dutch says deze and dit, die and dat, and chooses with de or het. French only looks at masculine, feminine and plural, so check the noun first: say cette voiture, not ce voiture. When you do not name the thing, use the neutral ça, which never changes: C'est quoi, ça ? (the longer question is Qu'est-ce que c'est ?). To say who something belongs to, French uses être à: Ce sac est à Anne (Dutch: Deze tas is van Anne). To ask, say À qui est ce sac ?",
            'error_tag_category' => null,
        ],
    ],
];
