<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'booking-a-trip',
    'title' => 'Booking a trip',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Book a room, a ticket or a table and ask about details.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'reservar', 'translation_en' => 'to book / to reserve', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'cancelar', 'translation_en' => 'to cancel', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'confirmar', 'translation_en' => 'to confirm', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'doble', 'translation_en' => 'double', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'individual', 'translation_en' => 'single / for one person', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'completo', 'translation_en' => 'full / fully booked', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'la agencia de viajes', 'translation_en' => 'travel agency', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la oferta', 'translation_en' => 'offer / special deal', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el huésped', 'translation_en' => 'guest (at a hotel)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el pasajero', 'translation_en' => 'passenger', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Booking: quisiera + infinitive, question words for details and para + number',
            'explanation' => "To ask for something politely, say quisiera or me gustaría and then an infinitive: 'Quisiera reservar una habitación doble' (I would like to book a double room). It is the Spanish version of Dutch 'Ik zou graag een kamer willen reserveren'. To ask about details, choose the question word by what you want to know: cuánto for a price ('¿Cuánto cuesta la habitación?', Dutch 'hoeveel'), cuándo for a day ('¿Cuándo sale el vuelo?', Dutch 'wanneer') and a qué hora for a clock time ('¿A qué hora sale el tren?', Dutch 'hoe laat', literally 'at what hour'). Before a noun the word agrees with it: '¿Cuántas noches?', '¿Cuántas personas?' but '¿Cuántos días?'. Dutch 'voor' is usually para when you say who something is for, for how many people, or for which day: 'una mesa para cuatro personas', 'para el lunes'. Do not use por for how many people (por dos noches is also heard for how long).",
            'error_tag_category' => null,
        ],
    ],
];
