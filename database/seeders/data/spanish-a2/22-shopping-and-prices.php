<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'shopping-and-prices',
    'title' => 'Shopping and prices',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Shop for clothes and gifts, ask about sizes, prices and discounts.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el probador', 'translation_en' => 'fitting room', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'las rebajas', 'translation_en' => 'sales (reduced prices)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la falda', 'translation_en' => 'skirt', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el vestido', 'translation_en' => 'dress', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el abrigo', 'translation_en' => 'coat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'los vaqueros', 'translation_en' => 'jeans', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el dependiente', 'translation_en' => 'shop assistant', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'largo', 'translation_en' => 'long', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'corto', 'translation_en' => 'short (not long)', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'estrecho', 'translation_en' => 'tight / narrow', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
    ],
    'grammar' => [
        [
            'title' => 'Degree and fit: muy, bastante, demasiado, poco; quedar bien/mal; ser and estar with price and size',
            'explanation' => "Put muy (very, Dutch 'heel'), bastante (quite, Dutch 'best' or 'vrij'), demasiado (too, Dutch 'te') or poco (not very) before an adjective. They never change: 'La falda es demasiado larga' (the skirt is too long, Dutch 'te lang'). 'Un poco' means a bit: 'Es un poco corta'. To say how clothes fit, use quedar with bien, mal or an adjective: 'El vestido me queda bien' (the dress fits me well, Dutch 'staat me goed'). The verb agrees with the clothes, not with you: 'Los vaqueros me quedan estrechos'. Use ser for a size or a normal price: 'Mi talla es la cuarenta', 'El abrigo es caro'. Use estar for the price right now: 'Hoy el abrigo está barato', 'Está en rebajas'. For not very cheap, Spanish usually says 'no es muy barato'; poco before an adjective suits qualities like 'poco simpático'. Ask a price with 'cuánto cuesta' (one thing) or 'cuánto cuestan' (more than one). Largo means long, not large.",
            'error_tag_category' => null,
        ],
    ],
];
