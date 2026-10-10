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
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'faire', 'translation_en' => 'to do / to make', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'voir', 'translation_en' => 'to see', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'venir', 'translation_en' => 'to come', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'savoir', 'translation_en' => 'to know (a fact)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'sortir', 'translation_en' => 'to leave / to go out', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'mettre', 'translation_en' => 'to put', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'dire', 'translation_en' => 'to say / to tell', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'donner', 'translation_en' => 'to give', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'apporter', 'translation_en' => 'to bring (a thing)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'entendre', 'translation_en' => 'to hear', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Irregular present: je fais, je vois, je viens, je sais, je sors, je mets, je dis',
            'explanation' => "Most of the verbs in this unit are irregular in the present, but they share one pattern. Je and tu have the same form, ending in s: je fais, tu fais; je vois, tu vois; je viens, tu viens; je sais, tu sais; je sors, tu sors; je mets, tu mets; je dis, tu dis. Il and elle end in t instead of s: il fait, il voit, il vient, il sait, il sort, il met, il dit. Nous ends in ons: nous faisons, nous voyons, nous venons, nous savons, nous sortons, nous mettons, nous disons. Vous usually ends in ez (vous voyez, vous venez), except vous faites and vous dites. Careful: Dutch jij ziet ends in t, but French tu vois ends in s (not tu voit). Dutch ik weet and jij weet are also the same form, just like je sais and tu sais. Je vois and il voit sound exactly the same, and so do je dis and il dit: only the spelling tells them apart. Donner, apporter and entendre are regular: je donne, tu donnes, il donne; j'apporte, tu apportes; j'entends, tu entends, il entend. Before a vowel je shrinks to j', and in a negative sentence ne becomes n' there: je n'entends pas. French always keeps the subject pronoun, so say je vois Anne, never just vois Anne. A negative sentence wraps the verb in ne and pas: je ne sais pas. Savoir is to know a fact: je ne sais pas où est Anne.",
            'error_tag_category' => null,
        ],
    ],
];
