<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'jobs-and-work',
    'title' => 'Jobs and work',
    'context_tag' => ContextTag::Professional,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what you do for a living, where you work, and ask someone about their job.',
    'interest_tags' => [InterestTag::Tech],
    'vocabulary' => [
        ['term' => 'travailler', 'translation_en' => 'to work', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'le professeur', 'translation_en' => 'teacher', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'ingénieur', 'translation_en' => 'engineer', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'avocat', 'translation_en' => 'lawyer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le cuisinier', 'translation_en' => 'cook', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'infirmier', 'translation_en' => 'nurse', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le bureau', 'translation_en' => 'office', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'entreprise', 'translation_en' => 'company', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le patron', 'translation_en' => 'boss', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le collègue', 'translation_en' => 'colleague', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Saying your job: être without an article, and travailler comme or dans',
            'explanation' => "To say what your job is, French uses être and leaves out the article: Je suis professeur, not Je suis un professeur. Dutch does the same ('ik ben leraar'), while English adds 'a' (I am a teacher). Many job words change with the person: avocat for a man, avocate for a woman, and likewise cuisinier and cuisinière, infirmier and infirmière. Professeur and ingénieur are often used for both men and women, and women can also use professeure and ingénieure. Collègue never changes (le collègue, la collègue). After je suis or il est the article is dropped. After c'est, or when an adjective follows, you add un or une: C'est un professeur. Je suis un bon professeur. Dutch does that too ('het is een goede leraar'). With travailler, use comme for the job you do (Je travaille comme cuisinier, 'I work as a cook', where Dutch says 'als') and dans for the company or building you work in (Je travaille dans une entreprise). Au bureau (at the office) is the usual way to say you work at the office (au = à + le); dans un bureau is also fine. To ask, use Tu es professeur ou cuisinier ? (are you a teacher or a cook?), Tu travailles comme cuisinier ? (do you work as a cook?) or Tu travailles où ? (where do you work?).",
            'error_tag_category' => null,
        ],
    ],
];
