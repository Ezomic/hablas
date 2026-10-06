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
        ['term' => 'el tren', 'translation_en' => 'train', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el autobús', 'translation_en' => 'bus', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la estación', 'translation_en' => 'station', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la parada', 'translation_en' => 'stop (bus or tram)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el andén', 'translation_en' => 'platform', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el horario', 'translation_en' => 'timetable', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el asiento', 'translation_en' => 'seat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'de ida y vuelta', 'translation_en' => 'return (as in a return ticket)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => '¿a qué hora sale?', 'translation_en' => 'what time does it leave?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'la línea', 'translation_en' => 'line (of a bus or metro)', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Wanting things: querer + infinitive',
            'explanation' => "To say what you want, use querer. Its present is irregular in the same way for most persons: quiero, quieres, quiere, queremos, queréis, quieren (the e becomes ie, except in queremos and queréis). Put a noun after it to ask for something ('Quiero un billete') or an infinitive to say what you want to do ('Quiero ir a la estación'). Like Dutch 'ik wil gaan' (and unlike English 'I want to go'), Spanish has no extra word between the two verbs: quiero ir, never 'quiero a ir'. The subject pronoun is usually dropped because the ending already shows who wants. In shops and at ticket desks you also meet the politer quisiera ('Quisiera un billete'), which you already know from the restaurant.",
            'error_tag_category' => null,
        ],
    ],
];
