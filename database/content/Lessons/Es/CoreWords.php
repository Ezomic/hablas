<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

/**
 * The A1 function words and forms every unit may use without teaching them:
 * articles, pronouns, the common forms of ser, estar, tener, haber and ir,
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
            'el', 'la', 'los', 'las', 'un', 'una', 'unos', 'unas', 'al', 'del',
            'yo', 'tú', 'él', 'ella', 'usted', 'nosotros', 'nosotras', 'vosotros', 'ellos', 'ellas', 'ustedes',
            'mi', 'mis', 'tu', 'tus', 'su', 'sus',
            'soy', 'eres', 'es', 'somos', 'sois', 'son',
            'estoy', 'estás', 'está', 'estamos', 'estáis', 'están',
            'tengo', 'tienes', 'tiene', 'tenemos', 'tenéis', 'tienen',
            'hay',
            'voy', 'vas', 'va', 'vamos', 'vais', 'van',
            'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez',
            'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve', 'veinte',
            'qué', 'quién', 'dónde', 'cuándo', 'cómo', 'cuánto', 'cuánta', 'cuántos', 'cuántas', 'cuál', 'por qué',
            'a', 'de', 'en', 'con', 'sin', 'para', 'por', 'y', 'o', 'pero',
            'no', 'sí', 'aquí', 'allí', 'muy', 'bien', 'también',
            'hola', 'buenos', 'buenas', 'días', 'tardes', 'gracias', 'muchas', 'favor', 'adiós',
            'ana', 'pablo', 'marta', 'luis',
        ];
    }
}
