<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'a-trip-i-took',
    'title' => 'A trip I took',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Describe a trip you took and what happened.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'viajar', 'translation_en' => 'to travel', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el viaje', 'translation_en' => 'trip / journey', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la playa', 'translation_en' => 'beach', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la montaña', 'translation_en' => 'mountain', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el avión', 'translation_en' => 'plane', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el equipaje', 'translation_en' => 'luggage', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'perder', 'translation_en' => 'to lose / to miss (a plane, train or bus)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el recuerdo', 'translation_en' => 'souvenir', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la excursión', 'translation_en' => 'excursion / day trip', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el barco', 'translation_en' => 'boat / ship', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Irregular past forms: fui, estuve, tuve, hice, vi',
            'explanation' => 'Six very common verbs have their own forms for something that happened and is finished: ir and ser (fui, fuiste, fue, fuimos), estar (estuve, estuviste, estuvo, estuvimos), tener (tuve, tuviste, tuvo, tuvimos), hacer (hice, hiciste, hizo, hicimos) and ver (vi, viste, vio, vimos). Learn them as whole words, because the stem changes: tener becomes tuv-, estar becomes estuv-, hacer becomes hic- (and hiz- in hizo). None of them has an accent, and the yo form and the él or ella form differ in the ending (-e or -o): tuve and tuvo, estuve and estuvo, hice and hizo (hice also changes c to z). Ir and ser share the same past forms, and the rest of the sentence tells you which one it is: Fui a la playa (I went to the beach), El viaje fue caro (the trip was expensive). Dutch often says ik ben naar het strand gegaan or ik heb een reis gemaakt, with a helper verb. Spanish uses one word for a finished event: Fui a la playa. Hice un viaje. The he or ha forms (he ido) are a different tense, used for recent time like hoy or esta semana, so do not mix them in here.',
            'error_tag_category' => null,
        ],
    ],
];
