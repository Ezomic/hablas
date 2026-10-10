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
        ['term' => 'la maison', 'translation_en' => 'house / home', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => "l'appartement", 'translation_en' => 'flat / apartment', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la cuisine', 'translation_en' => 'kitchen', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le salon', 'translation_en' => 'living room', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la chambre', 'translation_en' => 'bedroom', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le jardin', 'translation_en' => 'garden', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la table', 'translation_en' => 'table', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la chaise', 'translation_en' => 'chair', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le lit', 'translation_en' => 'bed', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la fenêtre', 'translation_en' => 'window', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Il y a versus être, and prepositions of place',
            'explanation' => 'Use il y a (there is, there are) to say that something exists or to introduce it: "Il y a une table dans la cuisine." It never changes, so it is the same for one thing or many: "Il y a deux chaises." Use est or sont (is, are) to say where a thing you both know is: "La table est dans la cuisine." A handy rule: with un, une or a number (something new) use il y a; with le, la, les, mon or ton (something known) use est or sont. In Dutch, "er is" is il y a and "is" plus a place is est. To say where, use dans (in), sur (on), sous (under), à côté de (next to) and près de (near): "La chaise est sous la table." De joins le into du and à joins le into au: "près du jardin", "à côté du salon". Before la it stays de: "près de la maison". À also means in or at with a place: au salon, au jardin (à + le = au); à la cuisine, à la maison. You can also say dans le salon. The final -s of a plural is not pronounced, so write deux chambres, not deux chambre.',
            'error_tag_category' => null,
        ],
    ],
];
