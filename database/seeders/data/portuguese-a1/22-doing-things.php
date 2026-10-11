<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'doing-things',
    'title' => 'Doing things',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Say what you do, see, hear and know, say when you come or leave, and talk about bringing, putting and giving, using the most common everyday verbs.',
    'contrast_note' => 'The infinitives look like Spanish, but the spelling shifts: hacer, decir and traer become fazer, dizer and trazer with a z, poner becomes pôr with a circumflex, and salir loses its l: sair. Oír is a different word, ouvir. The eu forms are the real trap: faço and ouço take a ç, vejo takes a j, venho and ponho take nh, and sei has no accent where Spanish writes sé.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'fazer', 'translation_en' => 'to do / to make', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish hacer becomes fazer, with a z, and the eu form is faço, with a ç, where Spanish has hago.'],
        ['term' => 'ver', 'translation_en' => 'to see', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'The eu form is vejo, with a j, where Spanish has veo. He or she sees is vê, with a circumflex.'],
        ['term' => 'vir', 'translation_en' => 'to come', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish venir is much shorter in Portuguese: vir. The eu form is venho, with nh, where Spanish has vengo.'],
        ['term' => 'saber', 'translation_en' => 'to know (a fact)', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'The eu form is sei, with no accent, where Spanish writes sé.'],
        ['term' => 'sair', 'translation_en' => 'to leave / to go out', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish salir loses its l: sair. The eu form is saio, where Spanish has salgo.'],
        ['term' => 'pôr', 'translation_en' => 'to put', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish poner becomes pôr, with a circumflex that tells it apart from por (for, by). The eu form is ponho, with nh.'],
        ['term' => 'dizer', 'translation_en' => 'to say / to tell', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish decir becomes dizer, with a z. The eu form is digo, as in Spanish.'],
        ['term' => 'dar', 'translation_en' => 'to give', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'trazer', 'translation_en' => 'to bring', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish traer becomes trazer, with a z. The eu form is trago, as in Spanish traigo without the i.'],
        ['term' => 'ouvir', 'translation_en' => 'to hear', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => 'Spanish oír is a different word: ouvir. The eu form is ouço, with a ç, where Spanish has oigo.'],
    ],
    'grammar' => [
        [
            'title' => 'Irregular eu forms: faço, vejo, venho, sei, saio, ponho',
            'explanation' => 'Many of the most common Portuguese verbs are irregular mainly in the eu form of the present, and a few also have accents or short forms elsewhere. The irregular eu forms in this unit are faço (fazer), vejo (ver), venho (vir), sei (saber), saio (sair), ponho (pôr), digo (dizer), dou (dar), trago (trazer) and ouço (ouvir). You already know this from sou, estou, vou and tenho. The rest is close to regular: fazes, faz, fazemos; sabes, sabe, sabemos; sais, sai; pões, põe, pomos; dizes, diz, dizemos; trazes, traz, trazemos; ouves, ouve, ouvimos. Watch the accents, which separate forms: vês, vê, vemos; vimos, vens, vem (he comes) but vêm (they come); dás, dá, damos. Dutch has irregular verbs too (ik ben, jij bent, wij zijn; ik heb, jij hebt, hij heeft), so the idea is not new. Vejo a Ana is the normal way to say it, and you add eu (Eu vejo a Ana) only for emphasis or contrast. Unlike Spanish, Portuguese has no little a before a person: in vejo a Ana the a is just the article, as in a Ana, and you also say vejo o João and vejo a mesa in the same way.',
            'error_tag_category' => null,
        ],
    ],
];
