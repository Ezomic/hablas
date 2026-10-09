<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'opinions-and-agreeing',
    'title' => 'Opinions and agreeing',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Give your opinion, agree and disagree politely.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'la opinión', 'translation_en' => 'opinion', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la razón', 'translation_en' => 'reason / being right', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la verdad', 'translation_en' => 'truth', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'creer', 'translation_en' => 'to believe / to think', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'pensar', 'translation_en' => 'to think', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'parecer', 'translation_en' => 'to seem', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'interesante', 'translation_en' => 'interesting', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'aburrido', 'translation_en' => 'boring', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'divertido', 'translation_en' => 'fun', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'importante', 'translation_en' => 'important', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
    ],
    'grammar' => [
        [
            'title' => 'Giving your opinion: creo que, pienso que, me parece que, estoy de acuerdo and porque',
            'explanation' => "To give an opinion, start with 'creo que' (I think), 'pienso que' (I think) or 'me parece que' (it seems to me that), then say the opinion as a normal sentence: 'Creo que la película es interesante.' Dutch 'ik denk dat' is the same idea, and English can drop 'that' ('I think it is good'), but Spanish always keeps que. In a positive opinion the verb after que is the ordinary present, the same one you already know. To agree, say 'estoy de acuerdo' (I agree), with estar and de. Dutch 'ik ben het eens' uses zijn, but Spanish never says 'soy de acuerdo'. To disagree, put no in front: 'No estoy de acuerdo.' To give a reason, use 'porque' in one word without an accent: 'Es aburrido porque no es interesante.' The question 'why?' is two words with an accent: '¿Por qué?'. Two small phrases help as well: 'tienes razón' (you are right, with tener, like Dutch 'je hebt gelijk') and 'es verdad' (it is true).",
            'error_tag_category' => null,
        ],
    ],
];
