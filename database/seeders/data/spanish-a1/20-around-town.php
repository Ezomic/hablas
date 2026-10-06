<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'around-town',
    'title' => 'Around town',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Name the places in a town and say where you are going, where you are and where you come from.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'el banco', 'translation_en' => 'bank', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'correos', 'translation_en' => 'post office', 'is_cognate' => false, 'part_of_speech' => 'proper noun'],
        ['term' => 'el supermercado', 'translation_en' => 'supermarket', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el parque', 'translation_en' => 'park', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la tienda', 'translation_en' => 'shop / store', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la iglesia', 'translation_en' => 'church', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el museo', 'translation_en' => 'museum', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la biblioteca', 'translation_en' => 'library', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la ciudad', 'translation_en' => 'city', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el pueblo', 'translation_en' => 'village / small town', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'al and del: a + el and de + el',
            'explanation' => "When a or de is followed by el, Spanish always joins the two words: a + el becomes al, and de + el becomes del. So you say Voy al banco (not 'a el banco') and Vengo del parque (not 'de el parque'). Dutch keeps the words apart (naar het park, van het museum), but in Spanish the merge is not optional. It happens only with el. With la the two words stay separate: Voy a la tienda. Vengo de la iglesia. Use a with ir for where you are going (Voy al museo), en with estar for where you are (Estoy en el museo, Estoy en la tienda) and de with venir for where you come from (Vengo del pueblo, Vengo de la ciudad). Notice that en never merges: en el stays en el. The place called correos takes no article at all: Voy a correos.",
            'error_tag_category' => null,
        ],
    ],
];
