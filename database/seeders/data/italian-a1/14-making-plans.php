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
        ['term' => 'il programma', 'translation_en' => 'plan', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la festa', 'translation_en' => 'party', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il cinema', 'translation_en' => 'cinema', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la cena', 'translation_en' => 'dinner', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il bar', 'translation_en' => 'café / bar', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'invitare', 'translation_en' => 'to invite', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'vedersi', 'translation_en' => 'to meet up / to see each other', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'vuoi venire?', 'translation_en' => 'do you want to come?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'mi dispiace', 'translation_en' => 'I am sorry', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'certo', 'translation_en' => 'of course', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
    ],
    'grammar' => [
        [
            'title' => 'Plans with andare a + infinitive, and asking with potere + infinitive',
            'explanation' => "Two verbs do most of the work when you make plans, and both are followed by an infinitive. To say you are going off to do something, use andare + a + infinitive: 'Vado a cenare con Luca' (I am going to have dinner with Luca), 'Andiamo a cenare al bar' (we are going to have dinner at the bar). Dutch says 'ik ga eten' without a little word, but Italian needs a between andare and the verb: 'Vado cenare' is wrong. To ask whether someone can do something, or to say that you can or cannot, use potere + infinitive, with no a: 'Puoi venire?' (can you come?), 'Non posso' (I cannot), 'Possiamo invitare Anna?' (can we invite Anna?). Potere is irregular: posso, puoi, può, possiamo. In both patterns the infinitive never changes: vado a cenare, andiamo a cenare, posso cenare.",
            'error_tag_category' => null,
        ],
    ],
];
