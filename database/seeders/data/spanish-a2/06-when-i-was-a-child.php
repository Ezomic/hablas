<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'when-i-was-a-child',
    'title' => 'When I was a child',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Describe your childhood: where you lived, what you were like and what you used to do.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el colegio', 'translation_en' => 'school', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el juguete', 'translation_en' => 'toy', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el vecino', 'translation_en' => 'neighbour', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la bicicleta', 'translation_en' => 'bicycle', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el niño', 'translation_en' => 'boy / child', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'vivir', 'translation_en' => 'to live', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'pasear', 'translation_en' => 'to go for a walk', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'de pequeño', 'translation_en' => 'as a child', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'siempre', 'translation_en' => 'always', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'a menudo', 'translation_en' => 'often', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'The imperfect: how things used to be',
            'explanation' => "The imperfect tense describes how things used to be: where you lived, what you were like and what you did again and again. Regular -ar verbs end in -aba, -abas, -aba, -ábamos, -abais, -aban (jugaba, paseaba). Regular -er and -ir verbs end in -ía, -ías, -ía, -íamos, -íais, -ían (vivía, tenía). Only three verbs are irregular: ser (era, eras, era, éramos, erais, eran), ir (iba, ibas, iba, íbamos, ibais, iban) and ver (veía, veías, veía, veíamos, veíais, veían), which simply adds -ía to ve. The forms for yo and for él or ella are identical, so the subject or the context shows who it is: Yo vivía en un pueblo. Mi padre vivía en la ciudad. Dutch has one simple past (ik woonde, ik speelde), but Spanish needs the imperfect for habits and descriptions in the past: De pequeño siempre jugaba al fútbol is 'als kind speelde ik altijd voetbal'. Words that often go with it are de pequeño, siempre and a menudo. Compare the present: Hoy vivo en la ciudad, but de pequeño vivía en un pueblo.",
            'error_tag_category' => null,
        ],
    ],
];
