<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\ErrorTagCategory;
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
        ['term' => 'alto', 'translation_en' => 'tall', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'bajo', 'translation_en' => 'short (not tall)', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'simpático', 'translation_en' => 'nice, friendly', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'joven', 'translation_en' => 'young', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'rubio', 'translation_en' => 'blond', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'moreno', 'translation_en' => 'dark-haired', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'guapo', 'translation_en' => 'good-looking', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'el pelo', 'translation_en' => 'hair', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el ojo', 'translation_en' => 'eye', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la persona', 'translation_en' => 'person', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Describing people with ser and tener',
            'explanation' => 'To say what someone is like, use ser plus an adjective: Ana es alta. The adjective agrees with the person: alta for a woman, alto for a man, altos for several men or a mixed group (Pablo y Luis son altos). Joven has one form for men and women. To describe hair and eyes, Spanish uses tener with the article: Tiene el pelo rubio. Tiene los ojos azules. Dutch works the same way (ze heeft blond haar, ze heeft blauwe ogen), but remember that pelo is masculine, so the colour is rubio, not rubia. Ser is for lasting traits such as height, looks and character (Es nervioso: he is a nervous person). Estar is for how someone is right now (Está nervioso: he is nervous at the moment, Está bien: he is fine). Dutch uses zijn for both, so this is the new choice for a Dutch speaker: ask yourself whether the trait is lasting (ser) or temporary (estar).',
            'error_tag_category' => ErrorTagCategory::SerEstarConfusion,
        ],
    ],
];
