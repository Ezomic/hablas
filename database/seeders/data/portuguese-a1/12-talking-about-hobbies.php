<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'talking-about-hobbies',
    'title' => 'Talking about hobbies',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what you like to do in your free time, ask someone about their hobbies and talk about sport and music.',
    'interest_tags' => [InterestTag::Music, InterestTag::Football],
    'contrast_note' => "Portuguese says 'gosto de' (I like) with the person as the subject, where Spanish turns it round with 'me gusta' and the thing you like as the subject. Watch the false friends and sound shifts: desporto and deporte, filme and película, jogar and jugar, dançar and bailar, and the nasal vowel in dançar and cantar, plus the cedilla (ç) in dançar.",
    'vocabulary' => [
        ['term' => 'o desporto', 'translation_en' => 'sport', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Portuguese adds an s at the start (des-) and ends in -o: 'desporto', not 'deporte'. Same gender (masculine). Brazilian Portuguese says 'esporte'."],
        ['term' => 'a música', 'translation_en' => 'music', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'o futebol', 'translation_en' => 'football (soccer)', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish writes an accent on 'fútbol'; Portuguese 'futebol' has no written accent and an extra e."],
        ['term' => 'o filme', 'translation_en' => 'film / movie', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'la película' (feminine): Portuguese 'o filme' is masculine, so link the gender to the word filme."],
        ['term' => 'o tempo livre', 'translation_en' => 'free time', 'is_cognate' => false, 'part_of_speech' => 'phrase'],
        ['term' => 'dançar', 'translation_en' => 'to dance', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => "Different root from Spanish 'bailar': dançar is related to English 'dance', and the ç is pronounced like an s."],
        ['term' => 'cantar', 'translation_en' => 'to sing', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'jogar', 'translation_en' => 'to play (a sport or game)', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => "Like Spanish 'jugar' (u to o) but but the j is not the Dutch j: it sounds like the s in 'measure'. You can also play a sport without a small word: 'jogar futebol'."],
        ['term' => 'ler', 'translation_en' => 'to read', 'is_cognate' => false, 'part_of_speech' => 'verb', 'contrast_note' => "Spanish 'leer' loses one e: Portuguese 'ler' is a single short syllable."],
        ['term' => 'o livro', 'translation_en' => 'book', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'el libro' becomes 'o livro': the b turns into a v."],
    ],
    'grammar' => [
        [
            'title' => 'Gostar de: saying what you like',
            'explanation' => "Portuguese says what you like with gostar de, and the person is the subject: Gosto de música ('I like music'). Dutch has a similar shape in 'ik hou van' (stronger than gostar) and 'ik vind muziek leuk'. Spanish does it the other way round (me gusta la música), so do not copy that order. Gostar is always followed by de: gosto de (I like), gostas de (you like, informal). Before a verb or a bare noun it stays de: Gosto de ler. Gosto de música. When the noun has the article o, a, os or as, de merges with it: do, da, dos, das. So you say Gosto do futebol. Gosto da música. Gosto dos filmes. Portugal keeps the article before meu and minha too: Gosto do meu livro. Portuguese often uses the article for things in general: Gosto dos desportos can mean 'I like sports'. To ask a friend, use gostas: Gostas de dançar? To say you do not like something, put não before the verb: Não gosto de cantar.",
            'error_tag_category' => null,
        ],
    ],
];
