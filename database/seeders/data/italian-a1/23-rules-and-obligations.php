<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'rules-and-obligations',
    'title' => 'Rules and obligations',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what you have to do and what you may or may not do, and read simple signs and rules.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'vietato', 'translation_en' => 'prohibited / not allowed', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'aperto', 'translation_en' => 'open', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'chiuso', 'translation_en' => 'closed', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => "l'entrata", 'translation_en' => 'entrance', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'fumare', 'translation_en' => 'to smoke', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'aspettare', 'translation_en' => 'to wait', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'entrare', 'translation_en' => 'to enter / to go in', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'dovere', 'translation_en' => 'to have to / must', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'bisogna', 'translation_en' => 'one has to / you have to (general rule)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'si può', 'translation_en' => 'one can / it is allowed', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Dovere, bisogna and (non) si può: what you must and may do',
            'explanation' => "Three small patterns, all followed by an infinitive, say what is necessary and what is allowed. To say what a person has to do, use dovere + infinitive: 'Devo entrare' (I have to go in), 'Anna deve aspettare' (Anna has to wait). Dovere changes with the person (devo, devi, deve, dobbiamo) and the second verb stays in the infinitive, just like Dutch 'ik moet werken'. For a rule that is true for everybody, use bisogna + infinitive. It has no person and it never changes: 'Bisogna aspettare' (you have to wait, one has to wait). Do not put a person in front of it: 'tu bisogna' is wrong. To say what is allowed, use si può + infinitive, and non si può for what is not allowed: 'Non si può fumare qui' (you cannot smoke here). Dutch says 'hier mag je niet roken'; 'je hoeft niet te wachten' is 'non devi aspettare'. Si può is also how you ask: 'Si può entrare qui?'. Signs often use è vietato + infinitive: 'È vietato fumare'. Be careful with the negative: 'Non devi aspettare' means you do not have to wait, while 'Non si può aspettare' means waiting is not allowed. Careful: non bisogna aspettare means one must not wait (a prohibition), not 'you do not have to wait'. For that, use non devi aspettare.",
            'error_tag_category' => null,
        ],
    ],
];
