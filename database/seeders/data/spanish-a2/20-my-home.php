<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'my-home',
    'title' => 'My home',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Describe your home and where things are.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el baño', 'translation_en' => 'bathroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el pasillo', 'translation_en' => 'hallway', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la terraza', 'translation_en' => 'terrace', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el sofá', 'translation_en' => 'sofa', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el armario', 'translation_en' => 'wardrobe / cupboard', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la estantería', 'translation_en' => 'bookcase / shelves', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la lámpara', 'translation_en' => 'lamp', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la alfombra', 'translation_en' => 'rug / carpet', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la nevera', 'translation_en' => 'fridge', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la puerta', 'translation_en' => 'door', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Where things are: estar with prepositions of place, and hay versus está',
            'explanation' => "To say where something is, use está (one thing) or están (several) plus a place word: en (in, on), encima de (on top of), debajo de (under), al lado de (next to), entre (between), delante de (in front of) and detrás de (behind). Dutch uses one short word where Spanish often needs two: encima de = bovenop, debajo de = onder, al lado de = naast, delante de = voor, detrás de = achter, entre = tussen, en = in / op. Dutch picks staan, liggen or hangen; Spanish simply says está. All of them except en and entre need de after them, and de joins el into del: 'La lámpara está encima de la mesa', 'El armario está al lado del baño'. Entre takes two things joined by y: 'La alfombra está entre el sofá y la mesa'. Do not mix up hay and está. Hay is Dutch 'er is / er zijn': it introduces something new (un, una, a number, or no article at all, like 'Hay leche') and never changes: 'Hay una lámpara en el salón', 'Hay dos puertas'. Está and están point to something you both already know (el, la, mi, tu): 'La lámpara está en el salón'. Spanish never says hay with el, la or mi.",
            'error_tag_category' => null,
        ],
    ],
];
