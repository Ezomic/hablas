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
        ['term' => 'la pharmacie', 'translation_en' => 'pharmacy', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le médecin', 'translation_en' => 'doctor', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le médicament', 'translation_en' => 'medicine', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la douleur', 'translation_en' => 'pain, ache', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la tête', 'translation_en' => 'head', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la gorge', 'translation_en' => 'throat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la fièvre', 'translation_en' => 'fever', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'malade', 'translation_en' => 'sick, ill', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'l\'ordonnance', 'translation_en' => 'prescription', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'avoir mal', 'translation_en' => 'to hurt, to be in pain', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
    ],
    'grammar' => [
        [
            'title' => 'Saying what hurts: avoir mal à + au, à la, aux',
            'explanation' => "To say what hurts, French uses avoir mal à followed by the body part: J'ai mal à la tête, Tu as mal à la gorge. Avoir is irregular: j'ai, tu as, il or elle a, and French always keeps the subject pronoun. English says 'my head hurts' and Dutch says 'Ik heb hoofdpijn' or 'Mijn hoofd doet pijn', but French uses the article, not mon or ma: la tête, not ma tête. The little word à joins with the article that follows: à + le becomes au (J'ai mal au ventre, au dos), à + les becomes aux (J'ai mal aux pieds, aux mains), while à la and à l' stay as they are (J'ai mal à la gorge, à l'oreille). A fever works differently: J'ai de la fièvre, literally 'I have some fever', with de la and not une. To ask where it hurts, say Où avez-vous mal ? (formal) or Où as-tu mal ? (informal).",
            'error_tag_category' => null,
        ],
    ],
];
