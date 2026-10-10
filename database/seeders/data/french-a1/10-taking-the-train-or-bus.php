<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'taking-the-train-or-bus',
    'title' => 'Taking the train or bus',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Buy a ticket and get around by public transport: ask which train or bus to take, what time it leaves and where it leaves from.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'le train', 'translation_en' => 'train', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le bus', 'translation_en' => 'bus', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la gare', 'translation_en' => 'station (train station)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'arrêt', 'translation_en' => 'stop (bus or tram)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le quai', 'translation_en' => 'platform', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'horaire', 'translation_en' => 'timetable', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la place', 'translation_en' => 'seat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'un aller-retour', 'translation_en' => 'return (as in a return ticket)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'à quelle heure part-il ?', 'translation_en' => 'what time does it leave?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'la ligne', 'translation_en' => 'line (of a bus or metro)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Wanting things: vouloir + infinitive',
            'explanation' => "To say what you want, use vouloir. Its present is irregular: je veux, tu veux, il/elle veut, nous voulons, vous voulez, ils/elles veulent (the stem veu- becomes voul- for nous and vous). Notice that je veux and tu veux are the same, and that il veut sounds the same too, while ils veulent ends in an l sound (veul). French always keeps the subject pronoun, so the pronoun tells you who wants. Put a noun after it to ask for something ('Je veux un aller-retour') or an infinitive to say what you want to do ('Je veux aller à la gare'). Like Dutch 'ik wil gaan' (and unlike English 'I want to go'), French has no extra word between the two verbs: je veux aller, never 'je veux à aller'. At a ticket desk 'Je veux' can sound blunt, so you also meet the politer je voudrais ('Je voudrais un aller-retour'), the same 'would like' you know from the restaurant.",
            'error_tag_category' => null,
        ],
    ],
];
