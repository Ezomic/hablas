<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'telling-a-story',
    'title' => 'Telling a story',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Tell a short story with what was happening and what happened.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'mientras', 'translation_en' => 'while', 'is_cognate' => false, 'part_of_speech' => 'conjunction'],
        ['term' => 'de repente', 'translation_en' => 'suddenly', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'cuando', 'translation_en' => 'when', 'is_cognate' => false, 'part_of_speech' => 'conjunction'],
        ['term' => 'entonces', 'translation_en' => 'then / so', 'is_cognate' => false, 'part_of_speech' => 'adverb'],
        ['term' => 'caminar', 'translation_en' => 'to walk', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'llover', 'translation_en' => 'to rain', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'sonar', 'translation_en' => 'to ring / to sound', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'perder', 'translation_en' => 'to lose', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'abrir', 'translation_en' => 'to open', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'llegar', 'translation_en' => 'to arrive', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Imperfecto vs indefinido: the background and the event',
            'explanation' => 'A story needs two past tenses. The imperfecto sets the scene: what was going on, what the weather was like, an action in progress (caminaba, llovía, estaba). The indefinido names an event that happened and was over, one step of the story (sonó, perdió, llegó, abrió). A story mixes both: Ana caminaba por la calle cuando sonó el teléfono (she was walking: scene; the phone rang: event). Mientras introduces what was going on in the background, so its verb is in the imperfecto. The main clause can be a second imperfecto (Mientras llovía, Ana caminaba) or an event in the indefinido (Mientras llovía, Marta abrió el paraguas). De repente brings in one sudden event, so the verb after it is in the indefinido: De repente sonó el teléfono. Cuando and entonces often introduce the event that happened, so the verb right after them is usually the indefinido (cuando llegó Luis, entonces abrí el paraguas). Endings: -ar verbs make caminaba and caminó; -er and -ir verbs make perdía and perdió, abría and abrió. With yo the indefinido changes: perdí, abrí, llegué. Dutch usually tells a story in one tense (de verleden tijd): het regende, toen ging de telefoon. Spanish splits it by meaning: het regende is llovía (scene), toen ging de telefoon is sonó (event).',
            'error_tag_category' => null,
        ],
    ],
];
