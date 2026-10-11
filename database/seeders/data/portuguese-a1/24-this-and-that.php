<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'this-and-that',
    'title' => 'This and that',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Point at things and people near and far, ask what something is and choose between two things.',
    'contrast_note' => 'Watch the genders and the spelling: Spanish el bolso and el bolígrafo are a mala and a caneta in Portuguese, both feminine, so you say esta mala and esta caneta. Spanish coche, vaso and regalo are different words too (carro, copo, presente). Spanish estos, esos are estes, esses in Portuguese, and esse and essa are written with a double s. Chapéu starts with a sh sound.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'o copo', 'translation_en' => 'glass (for drinking)', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el vaso'. In Portuguese 'o vaso' is a flower vase, so a glass to drink from is 'o copo'."],
        ['term' => 'a mala', 'translation_en' => 'bag, handbag', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el bolso', and the gender flips: 'a mala' is feminine, so esta mala, not este mala. Spanish 'maleta' is a suitcase; Portuguese 'mala' covers bags and suitcases."],
        ['term' => 'o carro', 'translation_en' => 'car', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el coche'. In Portugal 'o coche' is a horse carriage, so use 'o carro' for a car."],
        ['term' => 'a mochila', 'translation_en' => 'backpack', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a foto', 'translation_en' => 'photo', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o presente', 'translation_en' => 'gift, present', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el regalo'. 'O presente' also means the present tense, but with a gift in your hand the meaning is clear. In Portugal you will also hear 'a prenda' for a gift."],
        ['term' => 'o guarda-chuva', 'translation_en' => 'umbrella', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el paraguas'. It is written with a hyphen, and the plural is 'os guarda-chuvas'. 'O chapéu de chuva' is also common in Portugal."],
        ['term' => 'a caneta', 'translation_en' => 'pen', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el bolígrafo', and the gender flips: 'a caneta' is feminine, so esta caneta."],
        ['term' => 'o chapéu', 'translation_en' => 'hat', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el sombrero'. The plural is 'chapéus', and the ch is said like sh."],
        ['term' => 'preferir', 'translation_en' => 'to prefer', 'is_cognate' => true, 'part_of_speech' => 'verb', 'contrast_note' => "Spanish 'prefiero' is 'prefiro' in Portuguese: only eu changes the stem (e becomes i), tu and ele keep the e: 'prefiro, preferes, prefere' where Spanish has 'prefiero, prefieres, prefiere'."],
    ],
    'grammar' => [
        [
            'title' => 'This and that: este, esse and isto',
            'explanation' => "To point at something, Portuguese puts a small word in front of the noun, and it matches the noun in gender and number. For 'this' (near me) use este for a masculine word, esta for a feminine word, estes for several masculine words and estas for several feminine words: este copo, esta mochila, estes carros, estas fotos. For 'that' (near you, or the thing you are talking about) use esse, essa, esses, essas: esse chapéu, essa foto. Aquele (aquele carro) is 'that over there', far from both of you; you only need to recognise it for now. Dutch does something similar: deze tafel and dit boek, die tafel and dat boek, where the choice depends on de or het. Portuguese looks at masculine or feminine, and also at one or several. Dutch de and het do not match Portuguese masculine and feminine, so learn the gender with each noun. Dutch and English have two steps (this and that); Portuguese has three: este, esse and aquele. Do not put the article as well: say esta mala, not a esta mala. Esta (this) has no accent; está (is) does. 'É do Rui' is 'de' + 'o'; 'da' is 'de' + 'a'. When you do not name the thing, use the neutral isto or isso. They never change: O que é isto? É um presente. Watch the gender of the new words: mala and caneta are feminine, so esta mala and esta caneta. A common mistake is to use este with a feminine word: say esta mochila, not este mochila.",
            'error_tag_category' => null,
        ],
    ],
];
