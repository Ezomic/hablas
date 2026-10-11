<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'weather-and-seasons',
    'title' => 'Weather and seasons',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Say what the weather is like, ask about it and talk about the four seasons.',
    'contrast_note' => 'Watch the spelling and the vowels: Spanish el verano and la nube become o verão and a nuvem, with a nasal ão and a nasal -em (plural nuvens), and la lluvia is a chuva, where ch sounds like sh. Otoño becomes outono, where ou is said like a plain o, and hace frío and hay become faz frio and há, with no accent on frio.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'o tempo', 'translation_en' => 'weather', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Same word and same double meaning as Spanish 'el tiempo': 'o tempo' is both the weather and time, and 'Que tempo faz?' asks about the weather."],
        ['term' => 'o frio', 'translation_en' => 'cold', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'el frío' has an accent, Portuguese 'o frio' does not."],
        ['term' => 'o calor', 'translation_en' => 'heat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o sol', 'translation_en' => 'sun', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'a chuva', 'translation_en' => 'rain', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'la lluvia', so a Portuñol slip (writing 'lluvia') is easy to make. The ch sounds like sh."],
        ['term' => 'a nuvem', 'translation_en' => 'cloud', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'la nube' becomes 'a nuvem', with a nasal -em, and the plural is 'nuvens'."],
        ['term' => 'o inverno', 'translation_en' => 'winter', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'el invierno' becomes 'o inverno', without the ie."],
        ['term' => 'o verão', 'translation_en' => 'summer', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'el verano' becomes 'o verão', with a nasal ão, and the plural is 'verões'."],
        ['term' => 'a primavera', 'translation_en' => 'spring', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o outono', 'translation_en' => 'autumn (fall)', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'el otoño' becomes 'o outono', where ou is said like a plain o and there is no ñ."],
    ],
    'grammar' => [
        [
            'title' => 'Faz frio, há nuvens, chove: talking about the weather',
            'explanation' => "Portuguese does not say 'it is cold' with the verb to be. It says 'faz frio', which is closer to 'it makes cold' (Dutch says 'het is koud', Portuguese says 'faz frio'). Use faz with frio, calor, sol and bom tempo: Faz frio. Faz calor. Faz sol. Faz bom tempo. In everyday speech you also hear está with them: Está frio. Está calor. Está sol. Use há for things that are there: Há nuvens. (There are clouds.) Rain has its own verb: Chove (it rains, like Dutch 'het regent'). To ask, say Que tempo faz? and to place the weather in a season, use no, na (em + o, em + a): No inverno faz frio. Na primavera faz bom tempo. Watch the difference between the weather and you: Faz frio describes the weather, while Tenho frio says that you feel cold (literally 'I have cold', like Dutch 'ik heb het koud'). You can also say Estou com frio. Do not use é to say it is cold right now: say faz frio or está frio.",
            'error_tag_category' => null,
        ],
    ],
];
