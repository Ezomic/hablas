<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'giving-instructions',
    'title' => 'Giving instructions',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Give simple instructions and follow a recipe.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'cortar', 'translation_en' => 'to cut', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'mezclar', 'translation_en' => 'to mix', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'añadir', 'translation_en' => 'to add', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'cocinar', 'translation_en' => 'to cook', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'pelar', 'translation_en' => 'to peel', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'la olla', 'translation_en' => 'pot / saucepan', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el cuchillo', 'translation_en' => 'knife', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el horno', 'translation_en' => 'oven', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el huevo', 'translation_en' => 'egg', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la sartén', 'translation_en' => 'frying pan', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Telling one person what to do: the tú command',
            'explanation' => "To tell a friend or a family member what to do, use the tú command. For regular verbs it is the same as the él/ella form of the present: 'cortar' becomes 'Corta el pan' (cut the bread), 'mezclar' becomes 'Mezcla los huevos', 'añadir' becomes 'Añade agua'. Dutch uses the verb stem for this ('snijd het brood', 'kom hier'); Spanish uses a form with a vowel ending, so 'Luis corta el pan' (Luis cuts the bread) and 'Luis, corta el pan' (Luis, cut the bread) look the same, and only the comma and the tone of voice tell them apart. Eight common verbs have a short, irregular command that you have to learn by heart: decir > di, hacer > haz, ir > ve, venir > ven, poner > pon, salir > sal, tener > ten, ser > sé. 'Sé' is also the 'I know' of saber, but the context makes it clear: 'Sé simpático' means 'be nice'. Note that Spanish does not put tú in front of the command, and the object comes after the verb: 'Haz la cena' (make dinner), 'Pon la mesa' (set the table). The commands for 'you must not' are different and come in a later unit.",
            'error_tag_category' => null,
        ],
    ],
];
