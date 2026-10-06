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
        ['term' => 'trabajar', 'translation_en' => 'to work', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el profesor', 'translation_en' => 'teacher', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el ingeniero', 'translation_en' => 'engineer', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el abogado', 'translation_en' => 'lawyer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el cocinero', 'translation_en' => 'cook', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el enfermero', 'translation_en' => 'nurse', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la oficina', 'translation_en' => 'office', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la empresa', 'translation_en' => 'company', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el jefe', 'translation_en' => 'boss', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el compañero', 'translation_en' => 'colleague', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Saying your job: ser without an article, and trabajar en or de',
            'explanation' => "To say what your job is, Spanish uses ser and leaves out the article: Soy profesor, not Soy un profesor. Dutch does the same ('ik ben leraar'), while English adds 'a' (I am a teacher). The job word changes with the person: profesor for a man, profesora for a woman. You only add un or una when an adjective follows, as in Soy un profesor muy bueno. Dutch does that too ('ik ben een goede leraar'). With trabajar, use en for the place where you work (Trabajo en una oficina) and de for the job you do (Trabajo de cocinero, 'I work as a cook'). To ask, use ¿En qué trabajas? (what is your job?) or ¿Dónde trabajas? (where do you work?).",
            'error_tag_category' => null,
        ],
    ],
];
