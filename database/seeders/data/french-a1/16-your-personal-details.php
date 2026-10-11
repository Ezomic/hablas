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
        ['term' => 'un an', 'translation_en' => 'year', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le pays', 'translation_en' => 'country', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'les Pays-Bas', 'translation_en' => 'the Netherlands (Holland)', 'is_cognate' => false, 'part_of_speech' => 'proper noun'],
        ['term' => 'la France', 'translation_en' => 'France', 'is_cognate' => true, 'part_of_speech' => 'proper noun'],
        ['term' => 'néerlandais', 'translation_en' => 'Dutch (nationality)', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'le téléphone', 'translation_en' => 'telephone, phone', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le numéro', 'translation_en' => 'number', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'adresse', 'translation_en' => 'address', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le nom de famille', 'translation_en' => 'surname, last name', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'épeler', 'translation_en' => 'to spell', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Avoir for age, être for nationality and origin',
            'explanation' => "In French you have your age: J'ai vingt ans, literally 'I have twenty years'. Dutch says 'ik ben twintig' and English says 'I am twenty', but French uses avoir, so Je suis vingt ans is wrong. You must always say ans. Ask with Tu as quel âge ? (informal you). For where you are from and for your nationality, use être: Je suis de France, Je suis néerlandais. For the Netherlands we use the nationality at this level: Je suis néerlandais (the country takes des: des Pays-Bas, which comes later). A nationality has no article and, unlike Dutch and English, no capital letter. It matches the person: Je suis néerlandais (a man) or Je suis néerlandaise (a woman; you hear the final z sound in the feminine). For someone else the verb changes: Paul a quinze ans (a, not à). Anne est néerlandaise.",
            'error_tag_category' => null,
        ],
    ],
];
