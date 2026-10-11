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
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'il bicchiere', 'translation_en' => 'glass (for drinking)', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la borsa', 'translation_en' => 'bag, handbag', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la macchina', 'translation_en' => 'car', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'lo zaino', 'translation_en' => 'backpack', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la foto', 'translation_en' => 'photo', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'il regalo', 'translation_en' => 'gift, present', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => "l'ombrello", 'translation_en' => 'umbrella', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la penna', 'translation_en' => 'pen', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'il cappello', 'translation_en' => 'hat', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'preferire', 'translation_en' => 'to prefer', 'is_cognate' => true, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'This and that: questo and quello',
            'explanation' => "To point at something, Italian puts a small word in front of the noun, and it matches the noun in gender and number. For 'this' use questo for a masculine word, questa for a feminine word, questi for several masculine words and queste for several feminine words: questo zaino, questa borsa, questi bicchieri, queste foto. Note that foto is feminine, even though it ends in -o. For 'that' use quello, but in front of a noun it copies the article: il bicchiere becomes quel bicchiere, la borsa becomes quella borsa, i bicchieri becomes quei bicchieri, le borse becomes quelle borse, lo zaino becomes quello zaino and l'ombrello becomes quell'ombrello. The plural of quello zaino is quegli zaini. Learn these as a set. On its own, without a noun, say quello or quella: Preferisco quella. Dutch does something similar: deze tafel and dit boek, die tafel and dat boek, where the choice depends on de or het. Italian looks at masculine or feminine, and also at one or several. When you ask about something you have not named, use the masculine singular questo or quello: Che cos'è questo? È un regalo. When the thing is named, they agree: Preferisco quella. A common mistake is to use quel with every word: say quella borsa, not quel borsa.",
            'error_tag_category' => null,
        ],
    ],
];
