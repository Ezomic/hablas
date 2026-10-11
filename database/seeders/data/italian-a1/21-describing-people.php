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
        ['term' => 'alto', 'translation_en' => 'tall', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'basso', 'translation_en' => 'short (not tall)', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'simpatico', 'translation_en' => 'nice, friendly', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'giovane', 'translation_en' => 'young', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'biondo', 'translation_en' => 'blond', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'moro', 'translation_en' => 'dark-haired', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'bello', 'translation_en' => 'good-looking', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'i capelli', 'translation_en' => 'hair', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'occhio', 'translation_en' => 'eye', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la persona', 'translation_en' => 'person', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Describing people with essere and avere, and adjective agreement',
            'explanation' => 'To say what someone is like, use essere plus an adjective: "Anna è alta." The adjective agrees with the person: -o for a man (alto), -a for a woman (alta), -i for several men or a mixed group (alti), -e for several women (alte). "Giovane" ends in -e for both a man and a woman and becomes giovani in the plural. To describe hair and eyes, use avere with the article: "Ha i capelli biondi." "Ha gli occhi azzurri." Careful: hair as a whole is plural in Italian (i capelli), so the article and the colour are plural too (i capelli biondi), while Dutch says "blond haar" in the singular. The colour agrees with capelli, not with the person: "Anna ha i capelli biondi" but "Anna è bionda". Essere is for lasting traits such as height, looks and character ("È simpatico"). To say how someone is right now, use stare: "Sta bene" (he is fine). Dutch uses zijn for both, so ask yourself whether you mean what someone is like (essere) or how they feel at the moment (stare).',
            'error_tag_category' => null,
        ],
    ],
];
