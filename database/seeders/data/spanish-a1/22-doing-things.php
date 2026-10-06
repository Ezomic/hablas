<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'doing-things',
    'title' => 'Doing things',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Say what you do, see, hear and know, say when you come or leave, and talk about bringing, putting and giving, using the most common everyday verbs.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'hacer', 'translation_en' => 'to do / to make', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'ver', 'translation_en' => 'to see', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'venir', 'translation_en' => 'to come', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'saber', 'translation_en' => 'to know (a fact)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'salir', 'translation_en' => 'to leave / to go out', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'poner', 'translation_en' => 'to put', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'decir', 'translation_en' => 'to say / to tell', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'dar', 'translation_en' => 'to give', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'traer', 'translation_en' => 'to bring', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'oír', 'translation_en' => 'to hear', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Irregular yo forms: hago, veo, vengo, sé, salgo, pongo',
            'explanation' => 'Many of the most common Spanish verbs are regular everywhere except in one place: the yo form of the present. The other persons follow the normal pattern or come close to it. The irregular yo forms in this unit are hago (hacer), veo (ver), vengo (venir), sé (saber), salgo (salir), pongo (poner), digo (decir), doy (dar), traigo (traer) and oigo (oír). You already know this from soy, estoy, voy and tengo. Look at how regular the rest is: haces, hace, hacemos; ves, ve, vemos; sabes, sabe, sabemos; sales, sale, salimos; pones, pone, ponemos; traes, trae, traemos; das, da. Three verbs change a little more: vienes, viene (but venimos), dices, dice (but decimos) and oyes, oye (but oímos). Dutch has irregular verbs too (ik ben, jij bent, wij zijn; ik zie, wij zien), so the idea is not new. Veo a Ana is the normal way to say it, and you add yo (Yo veo a Ana) only for emphasis or contrast. Note that sé with an accent is I know, while se without an accent is a different little word. When the thing you see, hear or bring is a person, Spanish puts a in front of the name: veo a Ana, traigo a Marta. This a is not used for things: veo la mesa.',
            'error_tag_category' => null,
        ],
    ],
];
