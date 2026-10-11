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
        ['term' => 'lavorare', 'translation_en' => 'to work', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'l\'insegnante', 'translation_en' => 'teacher', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'ingegnere', 'translation_en' => 'engineer', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'avvocato', 'translation_en' => 'lawyer', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il cuoco', 'translation_en' => 'cook', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'infermiere', 'translation_en' => 'nurse', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'l\'ufficio', 'translation_en' => 'office', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'l\'azienda', 'translation_en' => 'company', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il capo', 'translation_en' => 'boss', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il collega', 'translation_en' => 'colleague', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Saying your job: essere without an article, and lavorare come or in',
            'explanation' => "To say what your job is, Italian uses essere and usually leaves out the article: Sono cuoco (Sono un cuoco is also possible, but the short form is more usual). Dutch does the same ('ik ben kok'), while English adds 'a' (I am a cook). Many job words change with the person: cuoco for a man, cuoca for a woman, and the same for avvocato and infermiere (infermiera). Words ending in -ante or -ista (insegnante, dentista) stay the same for both, and ingegnere is the same for both too (ingegnera is also used). You only add un or una when an adjective follows, as in Sono un buon cuoco. With lavorare, use come for the job you do (Lavoro come cuoco, 'I work as a cook') and in for the place where you work (Lavoro in un ufficio, in un'azienda). Before a feminine word that starts with a vowel, una becomes un': un'azienda. To ask, say Che lavoro fai? (what do you do for work?) or Dove lavori? (where do you work?). You can also answer with Faccio il cuoco.",
            'error_tag_category' => null,
        ],
    ],
];
