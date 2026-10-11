<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'describing-people',
    'title' => 'Describing people',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Describe what a person looks like and what they are like, in a few short sentences.',
    'contrast_note' => 'Watch the spelling and the sounds: Spanish rubio, pelo and guapo become louro, cabelo and bonito, which are different words, not just different spellings. Spanish bajo is baixo in Portuguese, with an x that sounds like sh. Jovem ends in a nasal -em, and its plural is jovens. Pelo exists in Portuguese too, but it means fur or hair on the body, so the hair on your head is o cabelo.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'alto', 'translation_en' => 'tall', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'baixo', 'translation_en' => 'short (not tall)', 'is_cognate' => false, 'part_of_speech' => 'adjective', 'contrast_note' => "Spanish 'bajo' becomes 'baixo', with an x that sounds like sh."],
        ['term' => 'simpático', 'translation_en' => 'nice, friendly', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'jovem', 'translation_en' => 'young', 'is_cognate' => false, 'part_of_speech' => 'adjective', 'contrast_note' => "Spanish 'joven' ends in -n, but Portuguese ends in a nasal -em, and the plural is 'jovens'."],
        ['term' => 'louro', 'translation_en' => 'blond', 'is_cognate' => false, 'part_of_speech' => 'adjective', 'contrast_note' => "Different word from Spanish 'rubio', so a Portuñol slip (writing 'rubio') is easy to make."],
        ['term' => 'moreno', 'translation_en' => 'dark-haired', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'bonito', 'translation_en' => 'good-looking, pretty', 'is_cognate' => false, 'part_of_speech' => 'adjective', 'contrast_note' => "Different word from Spanish 'guapo', so a Portuñol slip (writing 'guapo') is easy to make."],
        ['term' => 'o cabelo', 'translation_en' => 'hair', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el pelo'. In Portuguese 'o pelo' is body hair or fur, so the hair on your head is 'o cabelo'."],
        ['term' => 'o olho', 'translation_en' => 'eye', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'ojo' becomes 'olho', with lh for the sound ly, and the plural 'olhos' is said with an open o."],
        ['term' => 'a pessoa', 'translation_en' => 'person', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'persona' becomes 'pessoa' in Portuguese: the r drops out and the s is doubled. It is always feminine, also for a man."],
    ],
    'grammar' => [
        [
            'title' => 'Describing people with ser, ter and estar',
            'explanation' => 'To say what someone is like, use ser plus an adjective: A Ana é alta. The adjective agrees with the person: alta for a woman, alto for a man, altos for several men or a mixed group (O João e o Rui são altos). Jovem has one form for men and women. To describe hair and eyes, Portuguese uses ter with the article: Tem o cabelo louro. Tem os olhos azuis. Dutch also uses hebben here (ze heeft blond haar, ze heeft blauwe ogen). Unlike Dutch, Portuguese usually adds the article (o cabelo), though tem cabelo louro is also fine. Remember that cabelo is masculine, so the colour is louro, not loura. Ser is for lasting traits such as height, looks and character (É nervoso: he is a nervous person). Estar is for how someone is right now (Está nervoso: he is nervous at the moment, Está bem: he is fine). Dutch uses zijn for both, so this is the new choice for a Dutch speaker: ask yourself whether the trait is lasting (ser) or temporary (estar). In Portugal people often put the article before a first name: A Ana é alta.',
            'error_tag_category' => null,
        ],
    ],
];
