<?php

declare(strict_types=1);

namespace App\Services;

final class FrenchTextNormalizer extends AccentFoldingTextNormalizer
{
    /**
     * Vowel accents are folded. 'ç' is left alone: it is a distinct sound
     * from 'c', the same reasoning the Spanish normalizer uses for 'ñ'.
     *
     * @return array<string, string>
     */
    protected function vowelAccentFolds(): array
    {
        return [
            'à' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ö' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ÿ' => 'y',
        ];
    }

    /**
     * @return list<string>
     */
    public function articles(): array
    {
        return ['l', 'le', 'la', 'les', 'un', 'une', 'des', 'du'];
    }

    /**
     * @return list<string>
     */
    public function accentWords(): array
    {
        return ['à', 'où', 'là', 'sûr', 'dû', 'mûr', 'été', 'après', 'très', 'déjà', 'même', 'père', 'mère', 'frère', 'sœur'];
    }
}
