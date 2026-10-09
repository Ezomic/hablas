<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'my-job',
    'title' => 'My job',
    'context_tag' => ContextTag::Professional,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Talk about your work and what you are doing right now.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'ayudar', 'translation_en' => 'to help', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'atender', 'translation_en' => 'to serve / to attend to', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'revisar', 'translation_en' => 'to check / to go over', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'contestar', 'translation_en' => 'to answer', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el cliente', 'translation_en' => 'customer / client', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el informe', 'translation_en' => 'report', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la tarea', 'translation_en' => 'task', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la llamada', 'translation_en' => 'phone call', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la reunión', 'translation_en' => 'meeting', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'ahora mismo', 'translation_en' => 'right now', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'What is happening now: estar + gerundio',
            'explanation' => 'To say what is happening at this moment, use estar plus the gerundio: Estoy trabajando. Estar changes with the person (estoy, estás, está, estamos, están) and the gerundio never changes: Ana está trabajando, Ana y Luis están trabajando. Verbs in -ar take -ando (trabajar becomes trabajando, ayudar becomes ayudando). Verbs in -er and -ir take -iendo (atender becomes atendiendo). Ahora mismo (right now) often goes with it: Ahora mismo estoy revisando el informe. Be careful with Dutch: Dutch often uses the plain present for this (ik werk nu aan het rapport), but the plain Spanish present is mostly for habits (Trabajo en una oficina). It can also describe this moment, but estar + gerundio is the clear way to say you are in the middle of it, like Dutch ik ben aan het werken. Do not use the gerundio like Dutch het werken or English working: after a preposition or as a noun Spanish uses the infinitive.',
            'error_tag_category' => null,
        ],
    ],
];
