<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'describing-your-home',
    'title' => 'Describing your home',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Writing,
    'secondary_skill' => Skill::Reading,
    'task_description' => 'Describe where you live: the rooms, the furniture and where things are.',
    'contrast_note' => 'Watch the gender flip and the spelling: Spanish el salón (masculine) is a sala (feminine) in Portuguese, and cocina becomes cozinha (same word, new spelling), while ventana and silla become janela and cadeira, which are different words. Jardim ends in a nasal -im, a soft hum instead of a clear n like Spanish jardín.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'a casa', 'translation_en' => 'house / home', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'o apartamento', 'translation_en' => 'flat / apartment', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'el piso' is the usual word for a flat in Spain; Portuguese uses 'o apartamento'. Careful: Portuguese 'o piso' means a floor or storey, and 'o andar' is a floor of a building."],
        ['term' => 'a cozinha', 'translation_en' => 'kitchen', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'la cocina' becomes 'a cozinha', with a z and an nh that sounds like ny."],
        ['term' => 'a sala', 'translation_en' => 'living room', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Gender flip from Spanish: 'a sala' is feminine, but Spanish 'el salón' is masculine."],
        ['term' => 'o quarto', 'translation_en' => 'bedroom', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "'O quarto' is masculine and is the everyday word for a bedroom in Portugal; Spanish 'el cuarto' is the cognate; 'el dormitorio' and 'la habitación' are the other usual Spanish words. 'O quarto' also means a room in a hotel."],
        ['term' => 'o jardim', 'translation_en' => 'garden', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Same word family as Spanish 'el jardín', but Portuguese ends in a nasal -im and the plural is 'jardins'."],
        ['term' => 'a mesa', 'translation_en' => 'table', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'a cadeira', 'translation_en' => 'chair', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'la silla', so a Portuñol slip (writing 'silla') is easy to make."],
        ['term' => 'a cama', 'translation_en' => 'bed', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'a janela', 'translation_en' => 'window', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'la ventana', so a Portuñol slip (writing 'ventana') is easy to make."],
    ],
    'grammar' => [
        [
            'title' => 'Há versus estar, and prepositions of place',
            'explanation' => 'Use há (there is, there are) to say that something exists or to introduce it: "Há uma mesa na cozinha." It never changes, so it is the same for one thing or many: "Há duas cadeiras." Use está or estão (is, are) to say where a thing you both know is: "A mesa está na cozinha." A handy rule: with um, uma or a number (something new) use há; with o, a, o meu or a minha (something known) use está. In Dutch, "er is" and "er staat/er ligt" are há, and "is" plus a place is está. To say where, use em (in, on), em cima de (on top of), debaixo de (under), ao lado de (next to; a + o is ao) and perto de (near): "A cadeira está debaixo da mesa." Em joins the article: em + o is no, em + a is na ("no quarto", "na cozinha"), and de joins it too: de + o is do, de + a is da ("perto do jardim").',
            'error_tag_category' => null,
        ],
    ],
];
