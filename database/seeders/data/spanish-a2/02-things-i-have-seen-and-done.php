<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'things-i-have-seen-and-done',
    'title' => 'Experiences',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Talk about experiences you have had, using the irregular participles.',
    'interest_tags' => [InterestTag::Travel, InterestTag::Music],
    'vocabulary' => [
        ['term' => 'escribir', 'translation_en' => 'to write', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'abrir', 'translation_en' => 'to open', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'romper', 'translation_en' => 'to break', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'volver', 'translation_en' => 'to come back / to return', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'el viaje', 'translation_en' => 'trip / journey', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el concierto', 'translation_en' => 'concert', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el teatro', 'translation_en' => 'theatre', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la playa', 'translation_en' => 'beach', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la carta', 'translation_en' => 'letter', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el mensaje', 'translation_en' => 'message', 'is_cognate' => true, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Irregular participles: hecho, dicho, visto, escrito, puesto, vuelto, abierto, roto',
            'explanation' => 'The perfect tense is he, has, ha, hemos, habéis or han plus a participle: he escrito una carta, ha visto un concierto. Most participles are regular (hablado, comido), but eight very common verbs have an irregular one that you have to learn by heart: hacer becomes hecho, decir becomes dicho, ver becomes visto, escribir becomes escrito, poner becomes puesto, volver becomes vuelto, abrir becomes abierto and romper becomes roto. Dutch has irregular participles too (gedaan, gezegd, gezien, geschreven, gezet, gebroken), so the idea is familiar, but never add -ado or -ido to these verbs: hacido, escribido and abrido do not exist. After he, has, ha, hemos, habéis or han the participle never changes: Ana ha escrito una carta, Ana y Luis han escrito una carta. Dutch uses zijn with some verbs (ik ben teruggekomen), but Spanish always uses haber: he vuelto, hemos vuelto. Dutch geopend is regular, so abierto is a new irregular form for you. Dutch also uses the perfect for finished past (ik heb gisteren gezien), but Spanish keeps this tense for experiences and recent or still relevant events, so do not combine it with ayer. Use this tense for experiences and for things that still matter now: He visto un concierto. Hemos hecho un viaje.',
            'error_tag_category' => null,
        ],
    ],
];
