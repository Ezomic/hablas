<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'a-day-out',
    'title' => 'A day out',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Tell the story of a day out: where you went and what you ate and saw.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'comer', 'translation_en' => 'to eat', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'beber', 'translation_en' => 'to drink', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'volver', 'translation_en' => 'to come back / to return', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'subir', 'translation_en' => 'to go up / to climb', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'decidir', 'translation_en' => 'to decide', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'la playa', 'translation_en' => 'beach', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el castillo', 'translation_en' => 'castle', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el mercado', 'translation_en' => 'market', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la montaña', 'translation_en' => 'mountain', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el lago', 'translation_en' => 'lake', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Past tense of regular -er and -ir verbs: comí, comiste, comió',
            'explanation' => "Use the pretérito indefinido for something that happened and was finished at a given moment: El domingo comí en el mercado. For regular -er and -ir verbs the endings are the same: -í, -iste, -ió, -imos, -ieron. Take the stem and add them: comer gives comí, comiste, comió, comimos, comieron, and subir gives subí, subiste, subió, subimos, subieron. The accent is part of the spelling: comí (I ate) and comió (he or she ate) are stressed on the last syllable. Dutch says ik at or ik heb gegeten, but with a finished moment such as el domingo or el lunes Spanish uses this tense, not he comido. For -ar verbs the endings differ (hablé, hablaste, habló), so do not mix them up. Verbs that are irregular in the present are often regular here: volver gives volví, volviste, volvió (not 'vuelví'), and salir gives salí, saliste, salió. Watch nosotros: subimos can be present or past, and the sentence tells you which. For -er verbs the two differ: comemos is now, comimos is past.",
            'error_tag_category' => null,
        ],
    ],
];
