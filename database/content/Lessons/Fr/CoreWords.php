<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

/**
 * The A1 function words and forms every unit may use without teaching them:
 * articles, pronouns, the common forms of être, avoir and aller, numbers to
 * twenty, question words, small prepositions, the set phrases of politeness
 * and four first names. Any other word in a unit's text has to be one of the
 * unit's own words, a form its content declares, or glossed where it appears.
 */
final class CoreWords
{
    /** @return list<string> */
    public static function words(): array
    {
        return [
            'le', 'la', 'les', 'une', 'des', 'du', 'au', 'aux',
            'je', 'tu', 'il', 'elle', 'on', 'nous', 'vous', 'ils', 'elles',
            'mon', 'ma', 'mes', 'ton', 'ta', 'tes', 'son', 'sa', 'ses', 'votre', 'vos',
            'suis', 'es', 'est', 'sommes', 'êtes', 'sont',
            'ai', 'as', 'a', 'avons', 'avez', 'ont',
            'vais', 'vas', 'va', 'allons', 'allez', 'vont',
            'il y a', "c'est", 'voici',
            'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix',
            'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf', 'vingt',
            'que', 'quoi', 'qui', 'où', 'quand', 'comment', 'combien', 'quel', 'quelle', 'quels', 'quelles', 'pourquoi',
            'à', 'de', 'en', 'avec', 'sans', 'pour', 'par', 'dans', 'sur', 'et', 'ou', 'mais',
            'non', 'oui', 'ne', 'pas', 'ici', 'là', 'très', 'bien', 'aussi',
            'bonjour', 'bonsoir', 'merci', 'beaucoup', 'au revoir', "s'il vous plaît",
            'anne', 'paul', 'marie', 'luc',
        ];
    }
}
