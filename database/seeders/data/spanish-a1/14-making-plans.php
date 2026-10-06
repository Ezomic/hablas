<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'making-plans',
    'title' => 'Making plans',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Invite someone, accept or politely decline, and agree where to meet.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el plan', 'translation_en' => 'plan', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la fiesta', 'translation_en' => 'party', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el cine', 'translation_en' => 'cinema', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la cena', 'translation_en' => 'dinner', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el café', 'translation_en' => 'café', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'invitar', 'translation_en' => 'to invite', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'quedar', 'translation_en' => 'to meet up / to arrange to meet', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => '¿quieres venir?', 'translation_en' => 'do you want to come?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'lo siento', 'translation_en' => 'I am sorry', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'claro', 'translation_en' => 'of course', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
    ],
    'grammar' => [
        [
            'title' => 'Plans with ir a + infinitive, and asking with poder + infinitive',
            'explanation' => "Two verbs do most of the work when you make plans, and both are followed by an infinitive. To say what you are going to do, use ir + a + infinitive: 'Voy a cenar' (I am going to have dinner), 'Vamos a invitar a Ana' (we are going to invite Ana). Before a person, Spanish also puts a: 'invitar a Ana', not 'invitar Ana'. It works like English 'going to' or Dutch 'ik ga eten', but Spanish needs the little word a: 'Vamos cenar' is wrong. To ask whether someone can do something, or to say that you can or cannot, use poder + infinitive, with no a: '¿Puedes venir?' (can you come?), 'No puedo' (I cannot), 'Podemos quedar' (we can meet). Poder changes its stem: puedo, puedes, puede, podemos. In both patterns the infinitive never changes: voy a cenar, vamos a cenar, puedo cenar.",
            'error_tag_category' => null,
        ],
    ],
];
