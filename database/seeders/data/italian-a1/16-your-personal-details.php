<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'your-personal-details',
    'title' => 'Your personal details',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Say and ask your age, where you are from, your phone number and your address, and spell your surname.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'l\'anno', 'translation_en' => 'year', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il paese', 'translation_en' => 'country (also village or hometown)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'Olanda', 'translation_en' => 'Holland (the Netherlands)', 'is_cognate' => true, 'part_of_speech' => 'proper noun'],
        ['term' => 'l\'Italia', 'translation_en' => 'Italy', 'is_cognate' => true, 'part_of_speech' => 'proper noun'],
        ['term' => 'olandese', 'translation_en' => 'Dutch (nationality)', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'il telefono', 'translation_en' => 'telephone, phone', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il numero', 'translation_en' => 'number', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'indirizzo', 'translation_en' => 'address', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il cognome', 'translation_en' => 'surname, last name', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'sillabare', 'translation_en' => 'to spell out (say the letters one by one; literally: split into syllables)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Avere for age and essere for nationality',
            'explanation' => "In Italian you do not 'are' your age, you 'have' it: Ho venti anni, literally 'I have twenty years'. Dutch says 'ik ben twintig' and English says 'I am twenty', but Italian uses avere, so Sono venti anni is wrong. Ask with Quanti anni hai? (informal you). For someone else, change the verb: Paolo ha quindici anni. Nationality uses essere, with no article and no capital letter: Sono olandese, Anna è olandese. Olandese ends in -e, so it is the same for a man and a woman. Dutch also uses 'to be' here (ik ben Nederlands), but Italian writes olandese with a small letter. To ask where someone is from, say Di dove sei? or Di che paese sei? Watch the spelling: ho, hai and ha start with a silent h, which separates them from o (or), ai (to the) and a (to, at).",
            'error_tag_category' => null,
        ],
    ],
];
