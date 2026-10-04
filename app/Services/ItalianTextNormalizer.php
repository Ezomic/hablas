<?php

declare(strict_types=1);

namespace App\Services;

final class ItalianTextNormalizer extends AccentFoldingTextNormalizer
{
    /**
     * @return array<string, string>
     */
    protected function vowelAccentFolds(): array
    {
        return [
            'à' => 'a', 'è' => 'e', 'é' => 'e', 'ì' => 'i', 'í' => 'i', 'ò' => 'o', 'ó' => 'o', 'ù' => 'u', 'ú' => 'u',
        ];
    }

    /**
     * @return list<string>
     */
    public function articles(): array
    {
        return ['l', 'il', 'lo', 'la', 'i', 'gli', 'le', 'un', 'uno', 'una'];
    }

    /**
     * @return list<string>
     */
    public function accentWords(): array
    {
        return ['è', 'dà', 'là', 'lì', 'sì', 'né', 'tè', 'può', 'più', 'già', 'città', 'caffè', 'perché', 'università', 'papà'];
    }
}
