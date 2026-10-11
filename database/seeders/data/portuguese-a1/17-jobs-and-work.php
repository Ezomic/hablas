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
    'contrast_note' => 'Several job words look like Spanish but are spelled and sounded differently: abogado becomes advogado (a d and a v), cocinero becomes cozinheiro (z and nh) and ingeniero becomes engenheiro (nh). Three words are different, not just respelled: oficina is o escritório, jefe is o chefe (ch sounds like sh) and compañero is o colega, because o companheiro is a partner or companion. Where Spanish says trabajar de, Portuguese says trabalhar como, and chefe and colega keep one form for men and women (o colega, a colega).',
    'interest_tags' => [InterestTag::Tech],
    'vocabulary' => [
        ['term' => 'trabalhar', 'translation_en' => 'to work', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish trabajar has a j, Portuguese spells it with lh (trabalho, trabalhas), which sounds like ly.'],
        ['term' => 'o professor', 'translation_en' => 'teacher', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o engenheiro', 'translation_en' => 'engineer', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish ingeniero is engenheiro in Portuguese: the first vowel is e, and nh sounds like ny. The feminine is a engenheira.'],
        ['term' => 'o advogado', 'translation_en' => 'lawyer', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish abogado is advogado in Portuguese, with a d and a v. The feminine is a advogada.'],
        ['term' => 'o cozinheiro', 'translation_en' => 'cook', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish cocinero is cozinheiro in Portuguese, with a z and nh. The feminine is a cozinheira.'],
        ['term' => 'o enfermeiro', 'translation_en' => 'nurse', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish enfermero ends in -ero, Portuguese in -eiro. The feminine is a enfermeira.'],
        ['term' => 'o escritório', 'translation_en' => 'office', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Different word from Spanish la oficina, so a Portuñol slip (oficina) is easy to make. In Portugal o escritório is the usual word for an office.'],
        ['term' => 'a empresa', 'translation_en' => 'company', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o chefe', 'translation_en' => 'boss', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish el jefe is o chefe in Portuguese, with ch sounding like sh. The word has one form for both genders: o chefe, a chefe.'],
        ['term' => 'o colega', 'translation_en' => 'colleague', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish el compañero is o colega for a workmate; o companheiro is a partner or companion. The word has one form for both genders: o colega, a colega.'],
    ],
    'grammar' => [
        [
            'title' => 'Saying your job: ser without an article, and trabalhar em or como',
            'explanation' => "To say what your job is, Portuguese uses ser and leaves out the article: Sou professor, usually without um or uma. Dutch does the same ('ik ben leraar'), while English adds 'a' (I am a teacher). The job word changes with the person: professor for a man, professora for a woman. Chefe and colega stay the same for both: o colega, a colega. Um or uma appears mainly when you add an adjective or description, as in Sou um bom professor. Dutch does that too ('ik ben een goede leraar'). With trabalhar, use em for the place where you work (Trabalho num escritório, where em and um join into num; em and o join into no) and como for the job you do (Trabalho como cozinheiro, 'ik werk als kok'). To ask, use Em que trabalhas? (what is your job?) or Onde trabalhas? (where do you work?).",
            'error_tag_category' => null,
        ],
    ],
];
