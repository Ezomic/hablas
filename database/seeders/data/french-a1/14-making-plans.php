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
        ['term' => 'la fête', 'translation_en' => 'party', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le cinéma', 'translation_en' => 'cinema', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le dîner', 'translation_en' => 'dinner', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le café', 'translation_en' => 'café', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le rendez-vous', 'translation_en' => 'meeting / appointment (an arranged time and place to meet)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'inviter', 'translation_en' => 'to invite', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'se retrouver', 'translation_en' => 'to meet up / to arrange to meet', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'tu veux venir ?', 'translation_en' => 'do you want to come?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'désolé', 'translation_en' => 'sorry', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'bien sûr', 'translation_en' => 'of course', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Plans with aller + infinitive, and asking with pouvoir + infinitive',
            'explanation' => "Two verbs do most of the work when you make plans, and both are followed by an infinitive. To say what you are going to do, use aller + infinitive: 'Je vais dîner' (I am going to have dinner), 'Nous allons inviter Anne' (we are going to invite Anne). It works like English 'going to' or Dutch 'ik ga eten', and there is no little word in between: 'Je vais dîner', never 'Je vais à dîner'. A person straight after inviter needs no preposition either: 'inviter Anne'. Aller changes with the subject: je vais, tu vas, il va, nous allons, vous allez, ils vont (in everyday speech 'on va' often replaces 'nous allons'). To ask whether someone can do something, or to say that you can or cannot, use pouvoir + infinitive: 'Tu peux venir ?' (can you come?), 'Je ne peux pas' (I cannot), 'Nous pouvons nous retrouver' (we can meet up). Pouvoir changes its stem: je peux, tu peux, il peut, nous pouvons. In a negative, ne and pas wrap the first verb: 'Je ne peux pas venir', 'Je ne vais pas venir'. Aller can also take a place: 'Je vais au cinéma' (I am going to the cinema). In both patterns the infinitive never changes: je vais dîner, nous allons dîner, je peux dîner.",
            'error_tag_category' => null,
        ],
    ],
];
