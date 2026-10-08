<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'what-i-did-yesterday',
    'title' => 'What I did yesterday',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Say what you did yesterday and last weekend.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'ayer', 'translation_en' => 'yesterday', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'anoche', 'translation_en' => 'last night', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'el fin de semana', 'translation_en' => 'weekend', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'pasado', 'translation_en' => 'last (as in last week)', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'comprar', 'translation_en' => 'to buy', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'hablar', 'translation_en' => 'to talk / to speak', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'estudiar', 'translation_en' => 'to study', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'cocinar', 'translation_en' => 'to cook', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'llamar', 'translation_en' => 'to call', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'escuchar', 'translation_en' => 'to listen (to)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Past tense of -ar verbs: hablé, hablaste, habló, hablamos',
            'explanation' => "Use the pretérito indefinido for something that happened and is finished, at a moment you name: ayer, anoche, el año pasado, la semana pasada, el fin de semana pasado. Take the infinitive, drop -ar and add the ending: hablar becomes hablé (yo), hablaste (tú), habló (él, ella), hablamos (nosotros) and hablaron (ellos, ellas). Compré pan ayer. ¿Con quién hablaste anoche? Ana llamó a Luis. Two things to watch. First, the accent on the yo and él forms changes the meaning: hablo is I talk (now), hablé is I talked, and habló is he or she talked. Second, nosotros is the same in the present and the past (hablamos), so the time word, such as ayer, tells you which one it is. Dutch speakers often reach for the perfect tense here, because Dutch says gisteren heb ik gebeld. For a finished moment with a time word, Spanish normally uses the indefinido: Ayer llamé a Ana, not 'ayer he llamado'. Estudiar, cocinar, llamar, comprar and escuchar all follow the same pattern, and so do all the other regular -ar verbs.",
            'error_tag_category' => null,
        ],
    ],
];
