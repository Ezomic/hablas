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
    'contrast_note' => 'Some places are different words from Spanish: la tienda is a loja and el pueblo is an aldeia. La iglesia is the same word spelled igreja, and correos becomes the plural os correios. Museu ends in -eu, not -eo. The small words differ too: Spanish keeps a la apart, but Portuguese fuses a + a into à, with a grave accent. In cidade the d is a clear d and the final e is almost silent, so it sounds like "see-DAD-ê", not like ciudad.',
    'interest_tags' => [InterestTag::Travel],
    'vocabulary' => [
        ['term' => 'o banco', 'translation_en' => 'bank', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'os correios', 'translation_en' => 'post office', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'correos' has no article, but Portuguese 'os correios' is plural and takes one: 'vou aos correios'."],
        ['term' => 'o supermercado', 'translation_en' => 'supermarket', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o parque', 'translation_en' => 'park', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a loja', 'translation_en' => 'shop / store', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'la tienda', so a Portuñol slip (writing 'tienda') is easy to make."],
        ['term' => 'a igreja', 'translation_en' => 'church', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'la iglesia' is the same word; Portuguese spells it 'igreja', with a j."],
        ['term' => 'o museu', 'translation_en' => 'museum', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'el museo' ends in -o, but Portuguese 'o museu' ends in -u."],
        ['term' => 'a biblioteca', 'translation_en' => 'library', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a cidade', 'translation_en' => 'city', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'la ciudad' becomes 'a cidade': the ending is -dade, with a clear d and an almost silent final e."],
        ['term' => 'a aldeia', 'translation_en' => 'village', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish also has 'aldea', but everyday Spanish says 'pueblo'. In Portuguese use 'a aldeia' (feminine); writing 'pueblo' is a Portuñol slip."],
    ],
    'grammar' => [
        [
            'title' => 'ao, à, do, da, no, na: a, de and em with the article',
            'explanation' => 'In Portuguese the small words a (to), de (from, of) and em (in) join the article that follows. A + o becomes ao and a + a becomes à (with a grave accent): Vou ao banco, Vou à loja. De + o becomes do and de + a becomes da: Venho do museu, Venho da igreja. Em + o becomes no and em + a becomes na: Estou no parque, Estou na cidade. So the article tells you which form to use: masculine places take ao, do and no, feminine places take à, da and na. Dutch keeps the words apart (naar het park, van de kerk), and Spanish only joins with el (al, del), so à, da, no and na are all new. Use ir a for where you are going (Vou ao museu), estar em for where you are (Estou no museu) and vir de for where you come from (Venho da aldeia). A + os becomes aos, so os correios gives Vou aos correios.',
            'error_tag_category' => null,
        ],
    ],
];
