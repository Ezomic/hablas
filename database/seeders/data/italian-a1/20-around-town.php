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
        ['term' => 'la banca', 'translation_en' => 'bank', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la posta', 'translation_en' => 'post office', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il supermercato', 'translation_en' => 'supermarket', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il parco', 'translation_en' => 'park', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il negozio', 'translation_en' => 'shop / store', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la chiesa', 'translation_en' => 'church', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il museo', 'translation_en' => 'museum', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la biblioteca', 'translation_en' => 'library', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la città', 'translation_en' => 'city', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il paese', 'translation_en' => 'village / small town', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'al and dal: a and da joined with the article',
            'explanation' => "When a or da is followed by the article il, Italian joins the two into one word: a + il becomes al, and da + il becomes dal. With la it works the same way: a + la becomes alla, and da + la becomes dalla. So you say Vado al parco and Vado alla posta, and Vengo dal museo and Vengo dalla chiesa. Dutch keeps the words apart (naar het park, van het museum), but in Italian the merge is never optional, so 'a il parco' is wrong. Use a for where you are going (Vado al museo) and also for where you are (Sono al supermercato, Sono alla posta), and da for where you come from (Vengo dal paese, Vengo dalla città). Watch the pair al and dal: only one letter differs (Vado al parco, Vengo dal parco), but the meaning is the opposite. To say 'of the', di joins the article in the same way: di + il becomes del and di + la becomes della (il parco del paese, la chiesa del paese). Several places skip the article and take in instead: Sono in banca, in chiesa, in biblioteca, in città, in paese. Then nothing is merged: Vado in banca, Sono in città. Vengo da has no in form, so the article stays: Vengo dalla banca, Vengo dalla biblioteca.",
            'error_tag_category' => null,
        ],
    ],
];
