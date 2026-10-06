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
        ['term' => 'el médico', 'translation_en' => 'doctor', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la medicina', 'translation_en' => 'medicine', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el dolor', 'translation_en' => 'pain, ache', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la cabeza', 'translation_en' => 'head', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la garganta', 'translation_en' => 'throat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la fiebre', 'translation_en' => 'fever', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'enfermo', 'translation_en' => 'sick, ill', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'la receta', 'translation_en' => 'prescription', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'me duele', 'translation_en' => 'it hurts (me)', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Saying what is wrong: tener and doler',
            'explanation' => "Spanish has two ways to say what is wrong. With tener you say what you have, just like English 'I have a fever' and Dutch 'Ik heb koorts': Tengo fiebre. Tengo dolor de cabeza. Tener changes with the person: tengo (I), tienes (you, informal) and tiene (he, she or you, formal). With doler you say what hurts: Me duele la cabeza. This works backwards compared with Dutch ('Mijn hoofd doet pijn') and English ('My head hurts'): the thing that hurts is the subject and me means 'to me', so literally the head hurts me. That is why the verb agrees with the thing that hurts, not with you: duele for one thing (Me duele la garganta) and duelen for more than one (Me duelen los pies). Note too that Spanish says la cabeza, not mi cabeza.",
            'error_tag_category' => null,
        ],
    ],
];
