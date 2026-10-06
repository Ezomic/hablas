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
        ['term' => 'prohibido', 'translation_en' => 'prohibited / not allowed', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'abierto', 'translation_en' => 'open', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'cerrado', 'translation_en' => 'closed', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'la entrada', 'translation_en' => 'entrance / entrance ticket', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'fumar', 'translation_en' => 'to smoke', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'esperar', 'translation_en' => 'to wait', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'entrar', 'translation_en' => 'to enter / to go in', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'tener que', 'translation_en' => 'to have to', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'hay que', 'translation_en' => 'one has to / you have to (general rule)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'se puede', 'translation_en' => 'one can / it is allowed', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Tener que, hay que and (no) se puede: what you must and may do',
            'explanation' => "Three small patterns, all followed by an infinitive, say what is necessary and what is allowed. To say what a person has to do, use tener que + infinitive: 'Tengo que entrar' (I have to go in), 'Ana tiene que esperar' (Ana has to wait). Tener changes with the person (tengo, tienes, tiene, tenemos), but que and the infinitive never change. Dutch has one word for this, 'ik moet werken', where Spanish uses two words, tener and que. For a rule that is true for everybody, use hay que + infinitive. It has no person and it never changes: 'Hay que esperar' (you have to wait, one has to wait). To say what is allowed, use se puede + infinitive, and no se puede for what is not allowed: 'No se puede fumar aquí' (you cannot smoke here). Dutch says 'men mag hier niet roken' or 'hier mag je niet roken'. Se puede is also how you ask: '¿Se puede entrar aquí?'. You have already met poder in the making-plans unit; se puede is the form for 'one can' that signs and rules use. Do not forget que: 'tengo esperar' and 'hay esperar' are wrong, it is 'tengo que esperar' and 'hay que esperar'.",
            'error_tag_category' => null,
        ],
    ],
];
