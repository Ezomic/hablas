<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'comparing-things',
    'title' => 'Comparing things',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Compare two people or things.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'barato', 'translation_en' => 'cheap', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'caro', 'translation_en' => 'expensive', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'rápido', 'translation_en' => 'fast / quick', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'lento', 'translation_en' => 'slow', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'cómodo', 'translation_en' => 'comfortable', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'fácil', 'translation_en' => 'easy', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'difícil', 'translation_en' => 'difficult / hard', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'grande', 'translation_en' => 'big / large', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'pequeño', 'translation_en' => 'small / little', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'nuevo', 'translation_en' => 'new', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
    ],
    'grammar' => [
        [
            'title' => 'Comparing: más / menos + adjective + que, tan + adjective + como, and mejor, peor, mayor, menor',
            'explanation' => "To say that one thing is more or less than another, use más (more) or menos (less) + adjective + que: 'El tren es más rápido que el autobús' (the train is faster than the bus). Where English says 'than' and Dutch says 'dan', Spanish says que after más and menos. To say that two things are equal, use tan + adjective + como: 'Ana es tan alta como Marta' (Ana is as tall as Marta, Dutch 'even groot als'). Here it is como, not que: que goes with más and menos, como goes with tan. With a verb, use tanto como after the verb: 'Pablo come tanto como Luis' (Pablo eats as much as Luis, Dutch 'evenveel als'). The adjective agrees with the thing you describe, not with the thing you compare it to: 'La bicicleta es más lenta que el coche'. Four comparatives are irregular and never take más: mejor (better), peor (worse), mayor (older) and menor (younger): 'El tren es mejor que el autobús', not 'más bueno'. Use mayor and menor for age only, and más grande and más pequeño for size. Avoid 'más mayor', which is colloquial.",
            'error_tag_category' => null,
        ],
    ],
];
