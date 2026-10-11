<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'your-personal-details',
    'title' => 'Your personal details',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Say and ask your age, where you are from, your phone number and your address, and spell your surname.',
    'contrast_note' => 'Portuguese keeps ter for age and ser de for origin, but countries often take the article, so de joins with a: sou da Holanda, yet sou de Espanha. The words also shift: Spanish año loses its ñ (ano), and dirección has the false friend direção (direction), so an address is a morada. Apelido is the Spanish apellido twin and means surname in Portugal.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'o ano', 'translation_en' => 'year', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish año has an ñ; Portuguese writes a plain n: ano. (Spanish ano means anus, a memorable reason to keep the two spellings apart.)'],
        ['term' => 'o país', 'translation_en' => 'country', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a Holanda', 'translation_en' => 'Holland (the Netherlands)', 'is_cognate' => true, 'part_of_speech' => 'proper noun', 'contrast_note' => 'Portuguese uses the article with Holanda (a Holanda), where Spanish says plain Holanda, so you say sou da Holanda.'],
        ['term' => 'Espanha', 'translation_en' => 'Spain', 'is_cognate' => false, 'part_of_speech' => 'proper noun', 'contrast_note' => 'Spanish España becomes Espanha, with nh for the sound ñ. It takes no article: sou de Espanha.'],
        ['term' => 'holandês', 'translation_en' => 'Dutch (nationality)', 'is_cognate' => true, 'part_of_speech' => 'adjective'],
        ['term' => 'o telefone', 'translation_en' => 'telephone, phone', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o número', 'translation_en' => 'number', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a morada', 'translation_en' => 'address', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Spanish dirección looks like Portuguese direção, but direção means direction or management. The address where you live is a morada.'],
        ['term' => 'o apelido', 'translation_en' => 'surname, last name', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => 'Close to Spanish apellido, and in Portugal it also means surname. In Brazil apelido means nickname.'],
        ['term' => 'soletrar', 'translation_en' => 'to spell', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish deletrear exists in Portuguese too but is rare; in Portugal you say soletrar.'],
    ],
    'grammar' => [
        [
            'title' => 'Ter for age and ser de or da for where you are from',
            'explanation' => "In Portuguese you do not 'are' your age, you 'have' it: Tenho vinte anos, literally 'I have twenty years'. Dutch says 'ik ben twintig' and English says 'I am twenty', but Portuguese uses ter, so Sou vinte anos is wrong. Ask with Quantos anos tens? (informal you). For where you are from, use ser followed by de and the place: Sou de Espanha (Dutch says 'ik kom uit Spanje'). Many countries take the article in Portuguese, and then de and a join into da: Sou da Holanda. Ask with De onde és?. For someone else, change the verb: O João tem quinze anos. A Ana é da Holanda. A nationality also uses ser, and its ending matches the person: Sou holandês (a man) or Sou holandesa (a woman).",
            'error_tag_category' => null,
        ],
    ],
];
