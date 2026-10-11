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
        ['term' => 'interdit', 'translation_en' => 'prohibited / not allowed', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'ouvert', 'translation_en' => 'open', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'fermé', 'translation_en' => 'closed', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => "l'entrée", 'translation_en' => 'entrance', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'fumer', 'translation_en' => 'to smoke', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'attendre', 'translation_en' => 'to wait', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'entrer', 'translation_en' => 'to enter / to go in', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'devoir', 'translation_en' => 'to have to / must', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'il faut', 'translation_en' => 'one has to / you have to (general rule)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'on peut', 'translation_en' => 'one can / it is allowed', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Devoir, il faut and on peut: what you must and may do',
            'explanation' => "Three small patterns, all followed by an infinitive, say what is necessary and what is allowed. To say what a person has to do, use devoir + infinitive: 'Je dois entrer' (I have to go in), 'Anne doit attendre' (Anne has to wait). Devoir changes with the person (je dois, tu dois, il ou elle doit, nous devons), but the infinitive that follows never changes. Dutch does the same with 'ik moet werken', one verb plus an infinitive. For a rule that is true for everybody, use il faut + infinitive. It has no other person and it never changes: 'Il faut attendre' (you have to wait, one has to wait), like Dutch 'men moet wachten'. Do not say 'il doit attendre' for a general rule: that is about one particular man. To say what is allowed, use on peut + infinitive, and on ne peut pas for what is not allowed: 'On ne peut pas fumer ici' (you cannot smoke here). Dutch says 'men mag hier niet roken' or 'hier mag je niet roken'. Devoir is negated around the verb: 'Tu ne dois pas attendre' (you must not wait), which is also a prohibition, not 'you do not need to'. Beware of the negative: 'il ne faut pas' means you must NOT, not 'you do not have to'. 'Il ne faut pas fumer' is a prohibition, just like 'on ne peut pas fumer'. On a sign you will also read 'Il est interdit de fumer' (with de and an infinitive) or 'Fumer est interdit' (the infinitive is the subject, no de).",
            'error_tag_category' => null,
        ],
    ],
];
