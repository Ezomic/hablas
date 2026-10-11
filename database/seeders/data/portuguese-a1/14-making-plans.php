<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'making-plans',
    'title' => 'Making plans',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Invite someone, accept or politely decline, and agree where to meet.',
    'contrast_note' => 'Portuguese goes straight from ir to the infinitive (vou jantar), where Spanish needs a (voy a cenar). Watch the false friend cena: in Portuguese it means a scene, and dinner is jantar. For arranging to meet, Portuguese says combinar, not quedar.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'o plano', 'translation_en' => 'plan', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a festa', 'translation_en' => 'party', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o cinema', 'translation_en' => 'cinema', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o jantar', 'translation_en' => 'dinner', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'la cena' looks like Portuguese 'a cena', but that means a scene. Dinner is 'o jantar', masculine, and the same word is the verb: vou jantar."],
        ['term' => 'o café', 'translation_en' => 'café', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'convidar', 'translation_en' => 'to invite', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'combinar', 'translation_en' => 'to arrange / to agree on (to meet)', 'is_cognate' => true, 'part_of_speech' => 'verb', 'contrast_note' => "Spanish 'quedar' is not used for arranging to meet in Portuguese. Say 'combinar': 'Onde combinamos?' (Where shall we meet?). Combinar is agreeing on the plan; to say you will physically be somewhere, Portuguese says encontrar-se, which comes later."],
        ['term' => 'queres vir?', 'translation_en' => 'do you want to come?', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'desculpa', 'translation_en' => 'I am sorry', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
        ['term' => 'claro', 'translation_en' => 'of course', 'is_cognate' => false, 'part_of_speech' => 'interjection'],
    ],
    'grammar' => [
        [
            'title' => 'Plans with ir + infinitive (no a), and asking with poder + infinitive',
            'explanation' => "Two verbs do most of the work when you make plans, and both are followed directly by an infinitive. To say what you are going to do, use ir + infinitive: 'Vou jantar' (I am going to have dinner), 'Vamos convidar a Ana' (we are going to invite Ana). It works like English 'going to' or Dutch 'ik ga eten'. Unlike Spanish, Portuguese has no little word a between the two verbs: 'Vamos a jantar' is wrong. The forms are vou, vais, vai, vamos, vão. Before a person's name Portuguese uses the article: 'convidar a Ana', 'convidar o João'. In 'convidar a Ana' the a is the article (the Ana), not the Spanish personal a: Portuguese puts no preposition before the person. To ask whether someone can do something, or to say that you can or cannot, use poder + infinitive: 'Podes vir?' (can you come?), 'Não posso' (I cannot), 'Podemos combinar' (we can meet). Poder is regular except for eu: posso. The other forms are podes, pode, podemos. In both patterns the infinitive never changes: vou jantar, vamos jantar, posso jantar.",
            'error_tag_category' => null,
        ],
    ],
];
