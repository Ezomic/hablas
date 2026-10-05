<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

/**
 * The A1 function words and forms every unit may use without teaching them:
 * articles, pronouns, the common forms of essere, avere, stare and andare,
 * numbers to twenty, question words, small prepositions, the set phrases of
 * politeness and four first names. Any other word in a unit's text has to be
 * one of the unit's own words, a form its content declares, or glossed where
 * it appears.
 */
final class CoreWords
{
    /** @return list<string> */
    public static function words(): array
    {
        return [
            'il', 'lo', 'la', 'i', 'gli', 'le', 'un', 'uno', 'una', 'del', 'dei', 'al', 'alla', 'alle',
            'io', 'tu', 'lui', 'lei', 'noi', 'voi', 'loro',
            'mio', 'mia', 'miei', 'mie', 'tuo', 'tua', 'tuoi', 'tue', 'suo', 'sua', 'suoi', 'sue',
            'sono', 'sei', 'è', 'siamo', 'siete',
            'ho', 'hai', 'ha', 'abbiamo', 'avete', 'hanno',
            'sto', 'stai', 'sta', 'stiamo', 'state', 'stanno',
            'vado', 'vai', 'va', 'andiamo', 'andate', 'vanno',
            "c'è", 'ci sono',
            'due', 'tre', 'quattro', 'cinque', 'sette', 'otto', 'nove', 'dieci',
            'undici', 'dodici', 'tredici', 'quattordici', 'quindici', 'sedici', 'diciassette', 'diciotto', 'diciannove', 'venti',
            'che', 'chi', 'dove', 'quando', 'come', 'quanto', 'quanta', 'quanti', 'quante', 'quale', 'perché',
            'a', 'di', 'in', 'con', 'senza', 'per', 'da', 'su', 'e', 'o', 'ma',
            'no', 'sì', 'non', 'qui', 'lì', 'molto', 'bene', 'anche',
            'ciao', 'buongiorno', 'buonasera', 'grazie', 'prego', 'per favore', 'arrivederci',
            'anna', 'paolo', 'marta', 'luca',
        ];
    }
}
