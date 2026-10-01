<?php

declare(strict_types=1);

namespace App\Services;

final class SpanishTextNormalizer extends AccentFoldingTextNormalizer
{
    /**
     * Only vowel accents are folded — 'ñ' is deliberately left alone, since
     * it is a distinct Spanish letter/phoneme rather than an accent mark
     * (año/ano is a canonical minimal pair), and every caller of this
     * normalizer is checking a distinction where that difference matters.
     *
     * @return array<string, string>
     */
    protected function vowelAccentFolds(): array
    {
        return [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
        ];
    }

    /**
     * @return list<string>
     */
    public function articles(): array
    {
        return ['el', 'la', 'los', 'las', 'un', 'una', 'unos', 'unas'];
    }

    /**
     * @return list<string>
     */
    public function accentWords(): array
    {
        return ['él', 'tú', 'mí', 'sí', 'té', 'más', 'sé', 'dé', 'qué', 'cómo', 'dónde', 'cuándo', 'quién', 'cuál', 'cuánto', 'está', 'estás', 'papá', 'mamá', 'habló', 'aún'];
    }
}
