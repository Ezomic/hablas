<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'doing-things',
    'title' => 'Doing things',
    'context_tag' => ContextTag::EverydaySocial,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Say what you do, see, hear and know, say when you come or leave, and talk about bringing, putting and giving, using the most common everyday verbs.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'fare', 'translation_en' => 'to do / to make', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'vedere', 'translation_en' => 'to see', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'venire', 'translation_en' => 'to come', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'sapere', 'translation_en' => 'to know (a fact)', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'uscire', 'translation_en' => 'to leave / to go out', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'mettere', 'translation_en' => 'to put', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'dire', 'translation_en' => 'to say / to tell', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'dare', 'translation_en' => 'to give', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'portare', 'translation_en' => 'to bring', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'sentire', 'translation_en' => 'to hear', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Irregular present: faccio, vengo, so, esco, dico, do',
            'explanation' => 'Italian verbs end in -are, -ere or -ire, and most follow a regular pattern. Four verbs in this unit are regular: vedere (vedo, vedi, vede, vediamo), mettere (metto, metti, mette, mettiamo), portare (porto, porti, porta, portiamo) and sentire (sento, senti, sente, sentiamo). The other six are among the most common verbs in the language and are irregular, so learn their forms as they are: fare (faccio, fai, fa, facciamo), venire (vengo, vieni, viene, veniamo), sapere (so, sai, sa, sappiamo), uscire (esco, esci, esce, usciamo), dire (dico, dici, dice, diciamo) and dare (do, dai, dà, diamo). The irregular io form is the one to watch: faccio, vengo, esco and dico all change the stem, and so and do are very short. In the noi form most of these go back to the infinitive stem: usciamo, diciamo, veniamo. Dutch has irregular verbs too (ik ben, jij bent, wij zijn; ik heb, hij heeft), so the idea is not new. You normally drop the subject pronoun: Vengo con Paolo, not Io vengo con Paolo, and you add io only for emphasis or contrast (some practice sentences here include io only to make the person clear). Watch two small details. Dà (he or she gives) has a grave accent to tell it apart from da (from, by). And with vedere or sentire, Italian puts no little word in front of a person: Vedo Anna, Sento Paolo. The same goes for portare: Porto Marta. Use a only with dare and dire, where it means to: Do un libro a Marta, Dico ciao a Luca. Before a word that starts with a, a becomes ad: ad Anna. Watch the false overlap with Dutch too: fare covers both doen and maken, and sentire covers both horen and voelen.',
            'error_tag_category' => null,
        ],
    ],
];
