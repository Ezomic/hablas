<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'giving-and-sharing',
    'title' => 'Giving and sharing',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Say what you give, say and tell to people, and what you like to them.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'regalar', 'translation_en' => 'to give (as a gift)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'gustar', 'translation_en' => 'to like (to be pleasing)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'enseñar', 'translation_en' => 'to show', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'preguntar', 'translation_en' => 'to ask', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'la sorpresa', 'translation_en' => 'surprise', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la tarta', 'translation_en' => 'cake', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la flor', 'translation_en' => 'flower', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la invitación', 'translation_en' => 'invitation', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el ramo', 'translation_en' => 'bouquet', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el abrazo', 'translation_en' => 'hug', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'To whom? The indirect object pronouns me, te, le, nos, les',
            'explanation' => "An indirect object pronoun says to whom or for whom you do something: me (to me), te (to you), le (to him, to her), nos (to us), les (to them). It stands before the conjugated verb: 'Le doy un regalo a Ana' (I give Ana a gift), 'Nos dice hola' (he says hello to us). Dutch has hem, haar and hun, but Spanish uses le for both him and her, and les for more than one person, so you often add 'a Ana' or 'a los abuelos' to make clear who: 'Les regalo flores a los abuelos.' Gustar works the other way round from Dutch 'ik hou van'. The thing you like is the subject: 'Me gusta la tarta' literally means the cake pleases me. The verb agrees with the thing, not with the person: gusta for one thing, gustan for several ('Me gustan las flores'). The person who likes it is the pronoun: 'A Marta le gustan las flores.' Do not say 'Yo gusto la tarta'.",
            'error_tag_category' => null,
        ],
    ],
];
