<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'rules-and-obligations',
    'title' => 'Rules and obligations',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what you have to do and what you may or may not do, and read simple signs and rules.',
    'contrast_note' => 'Spanish tener que, hay que and se puede become ter de, é preciso and pode-se (há que exists in Portuguese, but it is formal). Portugal prefers ter de (tenho de). Spanish no se puede is não se pode: after não the se goes before the verb, while a plain question is Pode-se entrar?. Spanish prohibido loses its h (proibido), and abierto is not Portuguese, and cerrado is not what Portuguese says for a closed door: closed is fechado and open is aberto.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'proibido', 'translation_en' => 'prohibited / not allowed', 'is_cognate' => true, 'part_of_speech' => 'adjective', 'contrast_note' => "Spanish 'prohibido' keeps an h in the middle. Portuguese writes 'proibido' with no h, and the sign says 'é proibido fumar'."],
        ['term' => 'aberto', 'translation_en' => 'open', 'is_cognate' => false, 'part_of_speech' => 'adjective', 'contrast_note' => "Spanish 'abierto' has ie. Portuguese is 'aberto', feminine 'aberta': 'a entrada está aberta'."],
        ['term' => 'fechado', 'translation_en' => 'closed', 'is_cognate' => false, 'part_of_speech' => 'adjective', 'contrast_note' => "Spanish 'cerrado' is not what Portuguese says for a closed door or entrance. Closed is 'fechado', feminine 'fechada': 'a entrada está fechada'."],
        ['term' => 'a entrada', 'translation_en' => 'entrance / entrance ticket', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'fumar', 'translation_en' => 'to smoke', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'esperar', 'translation_en' => 'to wait', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'entrar', 'translation_en' => 'to enter / to go in', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'ter de', 'translation_en' => 'to have to', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Spanish says 'tener que'. Portuguese in Portugal prefers 'ter de': 'tenho de entrar'. 'Tenho que entrar' is also heard, but 'ter de' is the safe choice."],
        ['term' => 'é preciso', 'translation_en' => 'one has to / you have to (general rule)', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Spanish 'hay que' has a formal twin, 'há que', but everyday Portuguese uses 'é preciso' + infinitive: 'é preciso esperar'. It never changes."],
        ['term' => 'pode-se', 'translation_en' => 'one can / it is allowed', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Spanish 'se puede' is 'pode-se' in Portuguese: 'Pode-se entrar?'. After 'não' the se moves before the verb: 'não se pode fumar'."],
    ],
    'grammar' => [
        [
            'title' => 'Ter de, é preciso and (não se) pode-se: what you must and may do',
            'explanation' => "Three small patterns, all followed by an infinitive, say what is necessary and what is allowed. To say what a person has to do, use ter de + infinitive: 'Tenho de entrar' (I have to go in), 'A Ana tem de esperar' (Ana has to wait). Ter changes with the person (tenho, tens, tem, temos), but de and the infinitive never change. Dutch has one word for this, 'ik moet werken', where Portuguese uses two words, ter and de. The Dutch moeten is followed by a bare infinitive, so de is the extra piece: 'hij moet werken' is 'ele tem de trabalhar'. In Portugal you also hear ter que (tenho que entrar), but ter de is the form to learn. For a rule that is true for everybody, use é preciso + infinitive. It has no person and it never changes: 'É preciso esperar' (you have to wait, one has to wait). To say what is allowed, use pode-se + infinitive in a plain sentence or question: 'Pode-se entrar?'. For what is not allowed, put não in front and move se before the verb: 'Não se pode fumar aqui' (you cannot smoke here). Dutch says 'men mag hier niet roken' or 'hier mag je niet roken'. A sign can also say it with ser: 'É proibido fumar'. Do not forget de: 'tenho esperar' is wrong, it is 'tenho de esperar'.",
            'error_tag_category' => null,
        ],
    ],
];
