<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Services\PortugueseTextNormalizer;

final class PortugalGuard
{
    private const SUBJECTS = ['eu', 'tu', 'ele', 'ela', 'nós', 'eles', 'elas', 'vocês'];

    private const CLITICS = ['me', 'te', 'se', 'nos', 'vos', 'lhe', 'lhes'];

    private const TRIGGERS = ['não', 'nunca', 'já', 'também', 'ainda', 'só', 'que', 'quem', 'onde', 'quando', 'como', 'porque', 'tudo', 'todos', 'alguém', 'ninguém'];

    private const NOT_GERUNDS = ['quando'];

    /**
     * @param  array{words: list<string>, phrases: list<string>}  $forms
     * @return list<string> every sign that a text is Brazilian and not European Portuguese
     */
    public static function violations(string $text, array $forms): array
    {
        $normalizer = new PortugueseTextNormalizer;
        $key = $normalizer->exactKey($text);
        $words = $key === '' ? [] : explode(' ', $key);
        $found = [];

        foreach (array_intersect($words, $forms['words']) as $word) {
            $found[] = "Brazilian word: {$word}";
        }

        foreach ($forms['phrases'] as $phrase) {
            if (str_contains(" {$key} ", " {$normalizer->exactKey($phrase)} ")) {
                $found[] = "Brazilian phrase: {$phrase}";
            }
        }

        foreach (preg_split('/(?<=[.?!])\s+/u', $text) ?: [] as $sentence) {
            array_push($found, ...self::gerunds($normalizer->exactKey($sentence)), ...self::clitics($sentence));
        }

        return $found;
    }

    /** @return list<string> */
    private static function gerunds(string $key): array
    {
        if (preg_match_all('/\b(?:estou|estás|está|estamos|estão|estava|estavam)\s+(\p{L}+(?:ando|endo|indo))\b/u', $key, $matches) < 1) {
            return [];
        }

        return array_map(fn (string $gerund): string => "estar plus gerund, which is Brazilian (European: estar a plus infinitive): {$gerund}", array_values(array_diff($matches[1], self::NOT_GERUNDS)));
    }

    /** @return list<string> */
    private static function clitics(string $sentence): array
    {
        $tokens = array_values(array_filter(array_map(
            fn (string $token): string => trim(mb_strtolower($token), " \t\n\r\0\x0B.,;:!?¿¡\"'()"),
            preg_split('/\s+/u', $sentence) ?: [],
        ), fn (string $token): bool => $token !== ''));

        $found = [];

        if (count($tokens) > 1 && in_array($tokens[0], self::CLITICS, true)) {
            $found[] = "Sentence starts with a clitic: {$tokens[0]}";
        }

        $triggered = false;

        foreach ($tokens as $index => $token) {
            $next = $tokens[$index + 1] ?? '';

            if (in_array($token, self::TRIGGERS, true) && ! ($token === 'todos' && in_array($next, ['os', 'as'], true))) {
                $triggered = true;
            }

            if (! $triggered && in_array($token, self::SUBJECTS, true) && in_array($next, self::CLITICS, true)) {
                $found[] = "Subject pronoun before a clitic: {$token} {$next}";
            }

            if ($triggered && preg_match('/^(\p{L}+)-(?:'.implode('|', self::CLITICS).')$/u', $token, $parts) === 1 && ! str_ends_with($parts[1], 'r')) {
                $found[] = "Clitic after the verb where a trigger asks for proclisis: {$token}";
            }
        }

        return $found;
    }
}
