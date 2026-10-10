<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'describing-people',
    'title' => 'Describing people',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Describe what a person looks like and what they are like, in a few short sentences.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'grand', 'translation_en' => 'tall', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'petit', 'translation_en' => 'short (not tall), small', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'sympathique', 'translation_en' => 'nice, friendly', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'jeune', 'translation_en' => 'young', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'blond', 'translation_en' => 'blond', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'brun', 'translation_en' => 'dark-haired, brown', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'beau', 'translation_en' => 'good-looking, handsome, beautiful', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'les cheveux', 'translation_en' => 'hair', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'les yeux', 'translation_en' => 'eyes', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la personne', 'translation_en' => 'person', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Describing people: être, avoir and agreement',
            'explanation' => "To say what someone is like, use être plus an adjective: Anne est grande. In French the adjective agrees with the person, which Dutch does not do (zij is groot): add -e for a woman (grand, grande), add -s for several people (grands, grandes). An adjective that already ends in -e stays the same for men and women: Paul est jeune, Anne est jeune, Marie et Anne sont jeunes. Beau is irregular: beau, belle, beaux, belles. To describe hair and eyes, French uses avoir: Elle a les cheveux blonds, Il a les yeux bleus. Dutch also says 'hebben' (ze heeft blond haar), but les cheveux and les yeux are plural in this use, so the colour is plural too: blonds, not blond. Les yeux is the irregular plural of un œil. The adjective usually comes after the noun: une personne sympathique. Negative: Paul n'est pas grand (ne ... pas around the verb; n' before a vowel).",
            'error_tag_category' => null,
        ],
    ],
];
