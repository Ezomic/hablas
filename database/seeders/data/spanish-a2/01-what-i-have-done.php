<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'what-i-have-done',
    'title' => 'What I have done',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Say what you have already done today or this week, and what you have not done yet.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'terminar', 'translation_en' => 'to finish', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'empezar', 'translation_en' => 'to start / to begin', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'limpiar', 'translation_en' => 'to clean', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'visitar', 'translation_en' => 'to visit', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'preparar', 'translation_en' => 'to prepare', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'lavar', 'translation_en' => 'to wash', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'ya', 'translation_en' => 'already / yet (in a question)', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'todavía', 'translation_en' => 'still / yet (todavía no = not yet)', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'nunca', 'translation_en' => 'never', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'alguna vez', 'translation_en' => 'ever / at some time', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'The perfect tense: he, has, ha, hemos, han + participle',
            'explanation' => 'To say what you have done, use the present of haber (he, has, ha, hemos, han) plus a participle. The participle ends in -ado for -ar verbs (terminar becomes terminado) and in -ido for -er and -ir verbs (comer becomes comido, salir becomes salido). It never changes: Ana ha terminado, Ana y Pablo han terminado. The two parts stay together, so ya, todavía and nunca usually go in front of he: Ya he terminado. Todavía no he terminado. Nunca he visitado el museo. Ya can also come after the participle: He terminado ya. In a question, alguna vez means ever: ¿Has visitado alguna vez el museo? Haber is the helper here, never tener: he comido, not tengo comido. Be careful with Dutch: Dutch uses ik heb gegeten for anything in the past, but in Spain this tense is for what has happened up to now (hoy, ya, nunca, alguna vez). A finished time like ayer (yesterday) needs another past tense, which comes later. The h in he, has, ha, hemos and han is silent.',
            'error_tag_category' => null,
        ],
    ],
];
