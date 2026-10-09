<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'wanting-and-being-able',
    'title' => 'Wanting and being able',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what you want, prefer and can do.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'querer', 'translation_en' => 'to want', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'poder', 'translation_en' => 'can / to be able to', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'preferir', 'translation_en' => 'to prefer', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'volver', 'translation_en' => 'to come back', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'dormir', 'translation_en' => 'to sleep', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'empezar', 'translation_en' => 'to start', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'la siesta', 'translation_en' => 'nap', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el paseo', 'translation_en' => 'walk / stroll', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el gimnasio', 'translation_en' => 'gym', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la serie', 'translation_en' => 'series (TV show)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el videojuego', 'translation_en' => 'video game', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Stem-changing verbs: e to ie and o to ue',
            'explanation' => "Some very common verbs change the vowel in the middle of the stem when that vowel is stressed. Querer, preferir and empezar change e to ie: quiero, prefieres, empieza. Poder, volver and dormir change o to ue: puedo, vuelves, duerme. The change happens in yo, tú, él/ella/usted and ellos/ellas, but not in nosotros: queremos, podemos, volvemos, dormimos, empezamos. Dutch has a similar pattern in ik kan but wij kunnen and ik mag but wij mogen. Learn it as a boot: the forms inside the boot change, nosotros stays outside. After querer, preferir and poder the second verb stays in the infinitive: 'Quiero ver una serie', '¿Puedes venir?'. Empezar needs a before an infinitive: 'Empiezo a trabajar temprano'. Volver a casa means to come back home.",
            'error_tag_category' => null,
        ],
    ],
];
