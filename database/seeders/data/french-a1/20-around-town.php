<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'around-town',
    'title' => 'Around town',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Name the places in a town and say where you are going, where you are and where you come from.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'la banque', 'translation_en' => 'bank', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la poste', 'translation_en' => 'post office', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le supermarché', 'translation_en' => 'supermarket', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le parc', 'translation_en' => 'park', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'le magasin', 'translation_en' => 'shop / store', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => "l'église", 'translation_en' => 'church', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le musée', 'translation_en' => 'museum', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la bibliothèque', 'translation_en' => 'library', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la ville', 'translation_en' => 'city / town', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'le village', 'translation_en' => 'village', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'au and du: à + le and de + le',
            'explanation' => "When à or de is followed by le, French always joins the two words: à + le becomes au, and de + le becomes du. So you say 'Je vais au parc' (not 'à le parc') and 'Je viens du musée' (not 'de le musée'). Dutch keeps the words apart (naar het park, van het museum), but in French the merge is not optional. It happens only with le. With la the two words stay separate: 'Je vais à la banque', 'Je viens de la ville'. Before a vowel the article shrinks to l' and nothing merges, whether the noun is masculine or feminine: 'Je vais à l'église', 'Je viens de l'église'. French often uses à for where you are, with a place: 'Je suis au parc', 'Je suis à la bibliothèque' (Dutch says ik ben in het park). Dans is for inside something, as in dans la cuisine. So à goes with aller for where you are going (Je vais au musée) and with être for where you are (Je suis au musée), and de goes with venir for where you come from (Je viens du village). The same du also means 'of the': 'le parc du village' (the park of the village), and de la in 'la bibliothèque de la ville'.",
            'error_tag_category' => null,
        ],
    ],
];
