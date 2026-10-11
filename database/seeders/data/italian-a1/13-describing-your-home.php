<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'describing-your-home',
    'title' => 'Describing your home',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Describe where you live: the rooms, the furniture and where things are.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'la casa', 'translation_en' => 'house / home', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'appartamento', 'translation_en' => 'flat / apartment', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la cucina', 'translation_en' => 'kitchen', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il salotto', 'translation_en' => 'living room', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la camera', 'translation_en' => 'bedroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il giardino', 'translation_en' => 'garden', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il tavolo', 'translation_en' => 'table', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la sedia', 'translation_en' => 'chair', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il letto', 'translation_en' => 'bed', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la finestra', 'translation_en' => 'window', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'C\'è and ci sono versus è and sono, and prepositions of place',
            'explanation' => 'Use c\'è (there is) for one new thing and ci sono (there are) for several: "C\'è un tavolo in cucina." "Ci sono due sedie." Use è or sono (is, are) to say where a thing you both know is: "Il tavolo è in cucina." "Le sedie sono in giardino." A handy rule: with un, una or a number (something new) use c\'è or ci sono; with il, la, i, le, mio or tuo (something known) use è or sono. Dutch works the same way: "er is" is c\'è, "er zijn" is ci sono, and "is" or "zijn" plus a place is è or sono. To say where, use in (in, rooms usually go without an article: in cucina, in giardino, in camera), sopra (on top of), sotto (under), vicino a (near) and accanto a (next to): "La sedia è sotto il tavolo." After a the article merges with it: "accanto al letto", "vicino alla finestra".',
            'error_tag_category' => null,
        ],
    ],
];
