<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'getting-ready',
    'title' => 'Getting ready',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Describe how you get ready in the morning and for a night out.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'vestirse', 'translation_en' => 'to get dressed', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'ponerse', 'translation_en' => 'to put on (clothes)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'quitarse', 'translation_en' => 'to take off (clothes)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'peinarse', 'translation_en' => 'to comb your hair', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'cepillarse', 'translation_en' => 'to brush (your teeth or hair)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'maquillarse', 'translation_en' => 'to put on make-up', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el espejo', 'translation_en' => 'mirror', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'los dientes', 'translation_en' => 'teeth', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'los zapatos', 'translation_en' => 'shoes', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la chaqueta', 'translation_en' => 'jacket', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Reflexive verbs: me, te, se, nos, se + verb',
            'explanation' => 'A reflexive verb has a small pronoun in front of it that points back to the person doing the action: Me visto means I get (myself) dressed. The pronoun matches the subject: yo me, tú te, él and ella se, nosotros nos, vosotros os, ellos and ellas se. It goes before the conjugated verb: Ana se peina. Pablo se pone la chaqueta. The infinitive ends in -se (vestirse, ponerse), and that se changes with the person: me visto, te vistes, se viste, nos vestimos, se visten. Se is the same for one person and for several, so the verb ending tells you which: se viste (one) and se visten (more). Keep the pronoun when you drop the subject: Me peino, not Peino. Dutch has zich too, but Spanish uses these verbs much more for getting ready. Where Dutch says ik doe mijn jas aan or ik poets mijn tanden, Spanish says me pongo la chaqueta and me cepillo los dientes. With clothes and body parts Spanish uses the article (los dientes, la chaqueta) and not mi or mis, because me already tells you whose they are. Not every daily verb is reflexive: Pablo desayuna, with no se. Some verbs change meaning with the pronoun: poner la mesa is to set the table, but ponerse la chaqueta is to put on a jacket. Vestirse also changes its vowel: me visto, te vistes, se visten, but nos vestimos.',
            'error_tag_category' => null,
        ],
    ],
];
