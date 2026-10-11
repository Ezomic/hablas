<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;

return [
    'slug' => 'taking-the-train-or-bus',
    'title' => 'Taking the train or bus',
    'context_tag' => ContextTag::Travel,
    'primary_skill' => Skill::Reading,
    'secondary_skill' => Skill::Listening,
    'task_description' => 'Buy a ticket and get around by public transport: ask which train or bus to take, what time it leaves and where it leaves from.',
    'interest_tags' => [InterestTag::Travel],
    'contrast_note' => 'Most of the transport words are different from Spanish (comboio and tren, autocarro and autobús, paragem and parada, cais and andén), and lugar means seat where Spanish lugar only means place, so a Portuñol slip is easy. Querer also loses the vowel change: quero, not quiero. Listen for the open e (as in the word é) in quer, queres and querem, and for the nasal ending of querem.',
    'vocabulary' => [
        ['term' => 'o comboio', 'translation_en' => 'train', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el tren'. 'O comboio' is the word in Portugal; 'o trem' is the Brazilian word."],
        ['term' => 'o autocarro', 'translation_en' => 'bus', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el autobús'. 'O autocarro' is the word in Portugal; 'o ônibus' is the Brazilian word."],
        ['term' => 'a estação', 'translation_en' => 'station', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'a paragem', 'translation_en' => 'stop (bus or tram)', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'la parada'. 'A paragem' is the Portuguese bus stop; 'o ponto' is the Brazilian word."],
        ['term' => 'o cais', 'translation_en' => 'platform', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Different word from Spanish 'el andén'. 'O cais' is masculine and does not change in the plural; it also means quay. 'A plataforma' is also heard in Portugal."],
        ['term' => 'o horário', 'translation_en' => 'timetable', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Same word family as Spanish 'el horario', with an accent on the á."],
        ['term' => 'o lugar', 'translation_en' => 'seat', 'is_cognate' => false, 'part_of_speech' => 'noun', 'contrast_note' => "Spanish 'lugar' only means 'place'; for a seat Spanish says 'el asiento'. Portuguese 'o lugar' means both seat and place."],
        ['term' => 'de ida e volta', 'translation_en' => 'return (as in a return ticket)', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Spanish says 'de ida y vuelta'; Portuguese has e, not y."],
        ['term' => 'a que horas sai?', 'translation_en' => 'what time does it leave?', 'is_cognate' => false, 'part_of_speech' => 'phrase', 'contrast_note' => "Portuguese asks 'a que horas' (plural horas, no accent on que), where Spanish asks '¿a qué hora?'."],
        ['term' => 'a linha', 'translation_en' => 'line (of a bus or metro)', 'is_cognate' => true, 'part_of_speech' => 'noun', 'contrast_note' => "Looks like Spanish 'la línea' but is spelled with nh and has no accent. In Lisbon buses are often called 'carreira' (o autocarro da carreira 28); 'linha' is standard for metro, train and the Porto buses."],
    ],
    'grammar' => [
        [
            'title' => 'Wanting things: querer + infinitive',
            'explanation' => "To say what you want, use querer. The present is: quero, queres, quer, queremos, querem. Note that the he or she form is quer, not 'quere'. Put a noun after it to ask for something ('Quero um bilhete') or an infinitive to say what you want to do ('Quero ir à estação'). Like Dutch 'ik wil gaan' (and unlike English 'I want to go'), Portuguese has no extra word between the two verbs: quero ir, never 'quero a ir'. Dutch puts the second verb last ('ik wil naar het station gaan'), but Portuguese keeps ir right after quero. The subject pronoun is usually dropped because the ending already shows who wants. There is no form for vós in everyday speech: for more than one person you use vocês querem. At a ticket desk the politer queria ('Queria um bilhete') is common and sounds more natural than a plain quero (the lessons use quero to keep things simple).",
            'error_tag_category' => null,
        ],
    ],
];
