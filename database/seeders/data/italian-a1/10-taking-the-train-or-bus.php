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
        ['term' => 'il treno', 'translation_en' => 'train', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'autobus', 'translation_en' => 'bus', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la stazione', 'translation_en' => 'station', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la fermata', 'translation_en' => 'stop (bus or tram)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il binario', 'translation_en' => 'platform', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'orario', 'translation_en' => 'timetable', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il posto', 'translation_en' => 'seat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'di andata e ritorno', 'translation_en' => 'return (as in a return ticket)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'a che ora parte?', 'translation_en' => 'what time does it leave?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'la linea', 'translation_en' => 'line (of a bus or metro)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Wanting things: volere + infinitive',
            'explanation' => "To say what you want, use volere. Its present is irregular: voglio, vuoi, vuole, vogliamo, volete, vogliono (note the gl in voglio, vogliamo and vogliono, and that vogliono ends in -ono, like sono for 'they are'). Put a noun after it to ask for something ('Voglio un biglietto') or an infinitive to say what you want to do ('Voglio andare alla stazione'). Like Dutch 'ik wil gaan' (and unlike English 'I want to go'), Italian has no extra word between the two verbs: voglio andare, never 'voglio di andare'. The subject pronoun is usually dropped because the ending already shows who wants. Voglio sounds quite direct, so at ticket desks and in shops you also hear the politer vorrei ('Vorrei un biglietto'), which you already know from the restaurant.",
            'error_tag_category' => null,
        ],
    ],
];
