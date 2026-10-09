<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'how-do-you-feel',
    'title' => 'How do you feel?',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Listening,
    'secondary_skill' => Skill::Speaking,
    'task_description' => 'Say how you feel and give simple advice.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'sentirse', 'translation_en' => 'to feel (tired, well, ill)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'deber', 'translation_en' => 'should, ought to', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'tomar', 'translation_en' => 'to take (medicine, a drink)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'descansar', 'translation_en' => 'to rest', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'la tos', 'translation_en' => 'cough', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el estómago', 'translation_en' => 'stomach', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la espalda', 'translation_en' => 'back (of the body)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el resfriado', 'translation_en' => 'cold (illness)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'cansado', 'translation_en' => 'tired', 'is_cognate' => false, 'part_of_speech' => 'adjective'],
        ['term' => 'la pastilla', 'translation_en' => 'pill, tablet', 'is_cognate' => false, 'part_of_speech' => 'noun'],
    ],
    'grammar' => [
        [
            'title' => 'Feeling, aches and advice: sentirse, doler, tener + noun, deber',
            'explanation' => 'Four patterns cover how you feel. To say how you feel, use sentirse with a pronoun that matches the person, like the reflexive verbs: Me siento cansado (I feel tired), Ana se siente bien, Nos sentimos cansados. The e becomes ie in most forms (me siento, te sientes, se siente, se sienten) but not in nos sentimos. For an ache, use doler as you met it before: the part of the body is the subject and me means to me, so the verb agrees with the body part: Me duele la espalda, but Me duelen los pies. For another person, change me: Te duele el estómago (your stomach hurts), A Ana le duele la espalda. Spanish says la espalda, not mi espalda. For temperature and most aches Spanish says what you have, with tener + noun: Tengo tos, Tengo un resfriado, Tengo frío, Tengo calor. Dutch says ik ben verkouden or ik heb het koud, and English says I am cold, but Spanish does not use estar for temperature: Estoy frío is wrong, it is Tengo frío. To give advice, use deber or tener que + infinitive: Debes descansar (you should rest) is a gentle piece of advice, Tienes que descansar (you have to rest) is stronger, like Dutch je moet rusten against je zou moeten rusten. Deber changes with the person (debo, debes, debe, debemos, deben) and the second verb stays in the infinitive: Pablo debe tomar una pastilla, Debes descansar.',
            'error_tag_category' => null,
        ],
    ],
];
