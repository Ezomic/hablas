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
    'contrast_note' => "Two gender and form traps against Spanish: 'a dor' is feminine where Spanish 'el dolor' is masculine, and 'doente' is one form for men and women where Spanish has 'enfermo' and 'enferma'. The medicine you take is 'o medicamento' (or 'o remédio'): Portuguese 'a medicina' is the science of medicine, so Spanish 'la medicina' is a false friend here.",
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'a farmácia', 'translation_en' => 'pharmacy', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o médico', 'translation_en' => 'doctor', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o medicamento', 'translation_en' => 'medicine', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'la medicina' is the medicine you take, but Portuguese 'a medicina' means the science of medicine. For the medicine itself say 'o medicamento' or 'o remédio'."],
        ['term' => 'a dor', 'translation_en' => 'pain, ache', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "'A dor' is feminine, while Spanish 'el dolor' is masculine: the gender does not carry over."],
        ['term' => 'a cabeça', 'translation_en' => 'head', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'a garganta', 'translation_en' => 'throat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'a febre', 'translation_en' => 'fever', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'doente', 'translation_en' => 'sick, ill', 'is_cognate' => false, 'part_of_speech' => 'adjective', 'contrast_note' => "'Doente' has one form for men and women (o Rui está doente, a Ana está doente), where Spanish has 'enfermo' and 'enferma'."],
        ['term' => 'a receita', 'translation_en' => 'prescription', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'dói-me', 'translation_en' => 'it hurts (me)', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "In Portugal the pronoun comes after the verb (dói-me), where Spanish puts it first (me duele). After 'não' it goes before the verb: não me dói."],
    ],
    'grammar' => [
        [
            'title' => 'Saying what is wrong: ter and doer',
            'explanation' => "Portuguese has two ways to say what is wrong, just like Spanish. With ter you say what you have, as in English 'I have a fever' and Dutch 'Ik heb koorts': Tenho febre. Tenho dor de cabeça. Ter changes with the person: tenho (I), tens (tu, informal) and tem (he, she or o senhor, formal). With doer you say what hurts: Dói-me a cabeça. This works backwards compared with Dutch ('Mijn hoofd doet pijn') and English ('My head hurts'): the thing that hurts is the subject and me means 'to me', so literally the head hurts me. That is why the verb agrees with the thing that hurts, not with you: dói for one thing (Dói-me a garganta) and doem for more than one (Doem-me os pés). In Portugal the pronoun goes after the verb with a hyphen (dói-me), and before it after não (não me dói a cabeça). With dói-me, Portuguese says a cabeça (the head), not a minha cabeça, because me already shows whose head it is. Tenho dor de cabeça is the same as Dutch 'ik heb hoofdpijn'.",
            'error_tag_category' => null,
        ],
    ],
];
