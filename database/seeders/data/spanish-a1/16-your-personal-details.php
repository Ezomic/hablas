<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'your-personal-details',
    'title' => 'Your personal details',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Say and ask your age, where you are from, your phone number and your address, and spell your surname.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el año', 'translation_en' => 'year', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el país', 'translation_en' => 'country', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'Holanda', 'translation_en' => 'Holland (the Netherlands)', 'is_cognate' => true, 'part_of_speech' => 'proper noun'],
        ['term' => 'España', 'translation_en' => 'Spain', 'is_cognate' => false, 'part_of_speech' => 'proper noun'],
        ['term' => 'holandés', 'translation_en' => 'Dutch (nationality)', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'el teléfono', 'translation_en' => 'telephone, phone', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el número', 'translation_en' => 'number', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la dirección', 'translation_en' => 'address', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el apellido', 'translation_en' => 'surname, last name', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'deletrear', 'translation_en' => 'to spell', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Tener for age and ser de for where you are from',
            'explanation' => "In Spanish you do not 'are' your age, you 'have' it: Tengo veinte años, literally 'I have twenty years'. Dutch says 'ik ben twintig' and English says 'I am twenty', but Spanish uses tener, so Soy veinte años is wrong. Ask with ¿Cuántos años tienes? (informal you). For where you are from, use ser followed by de and the place: Soy de Holanda (English has the same shape, 'I am from Holland', while Dutch says 'ik kom uit Nederland'). Ask with ¿De dónde eres?. For someone else, change the verb: Pablo tiene quince años. Ana es de Holanda. A nationality also uses ser, and its ending matches the person: Soy holandés (a man) or Soy holandesa (a woman).",
            'error_tag_category' => null,
        ],
    ],
];
