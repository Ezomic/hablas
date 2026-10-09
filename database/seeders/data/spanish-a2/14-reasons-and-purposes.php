<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'reasons-and-purposes',
    'title' => 'Reasons and purposes',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Say why you do something and who or what it is for.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el examen', 'translation_en' => 'exam', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la boda', 'translation_en' => 'wedding', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la tarta', 'translation_en' => 'cake', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el paquete', 'translation_en' => 'parcel / package', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el ramo', 'translation_en' => 'bouquet / bunch of flowers', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el dinero', 'translation_en' => 'money', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'ahorrar', 'translation_en' => 'to save (money)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el proyecto', 'translation_en' => 'project', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el mes', 'translation_en' => 'month', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la fecha', 'translation_en' => 'date (day on the calendar)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Por or para: reason, purpose, deadline and recipient',
            'explanation' => "Dutch uses voor for almost all of this; Spanish splits it in two. Use para for a goal or purpose: 'Ahorro dinero para ir a España' (I save money to go to Spain, Dutch 'om te'), for the person something is meant for: 'El ramo es para Marta' (voor Marta), and for a deadline: 'El proyecto es para el lunes' (voor maandag). Use por for the cause or reason: 'No salgo por el examen' (because of the exam, Dutch 'vanwege'), for how long or when in the day: 'por un mes' (voor een maand), 'por la noche' (at night), for thanks: 'Gracias por el paquete' (bedankt voor), and for a price paid: 'Pago veinte euros por la tarta'. A quick test: if you can ask 'for what purpose?', use para; if you can ask 'because of what?', use por. After para with a verb, the verb stays an infinitive: 'para ir', 'para trabajar'. Do not confuse por with ¿por qué? (two words, with an accent), which means why.",
            'error_tag_category' => null,
        ],
    ],
];
