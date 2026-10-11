<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'at-the-pharmacy-and-the-doctor',
    'title' => 'At the pharmacy and the doctor',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what hurts or what is wrong, ask for medicine at the pharmacy and understand simple advice from a doctor.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'la farmacia', 'translation_en' => 'pharmacy', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il medico', 'translation_en' => 'doctor', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la medicina', 'translation_en' => 'medicine', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il dolore', 'translation_en' => 'pain, ache', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la testa', 'translation_en' => 'head', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la gola', 'translation_en' => 'throat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la febbre', 'translation_en' => 'fever', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'malato', 'translation_en' => 'sick, ill', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'la ricetta', 'translation_en' => 'prescription', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'mi fa male', 'translation_en' => 'it hurts (me)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Saying what is wrong: avere and fare male',
            'explanation' => "Italian has two ways to say what is wrong. With avere you say what you have, just like English 'I have a fever' and Dutch 'Ik heb koorts': Ho la febbre. Ho mal di gola. Avere changes with the person: ho (I), hai (you, informal) and ha (he, she or you, formal). Ho mal di testa and ho mal di gola are the everyday ways to say headache and sore throat. Note that Italian keeps the article (la febbre), where Dutch says just 'koorts'. With fare male you say what hurts: Mi fa male la testa. This works backwards compared with Dutch ('Mijn hoofd doet pijn') and English ('My head hurts'): the thing that hurts is the subject and mi means 'to me', so literally the head does harm to me. That is why the verb agrees with the thing that hurts, not with you: fa for one thing (Mi fa male la gola) and fanno for more than one (Mi fanno male i piedi). Note too that Italian says la testa, not la mia testa.",
            'error_tag_category' => null,
        ],
    ],
];
