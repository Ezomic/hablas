<?php

namespace App\Services;

use App\Contracts\TextNormalizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * The folding, whitespace and tokenization mechanics shared by every language.
 * Subclasses supply only the fold map, which is where the languages actually
 * differ: each keeps the marks that are phonemic for it.
 */
abstract class AccentFoldingTextNormalizer implements TextNormalizer
{
    /**
     * @return array<string, string>
     */
    abstract protected function vowelAccentFolds(): array;

    public function foldAccents(string $text): string
    {
        return strtr(Str::lower(trim($text)), $this->vowelAccentFolds());
    }

    public function collapseWhitespace(string $text): string
    {
        return preg_replace('/\s+/', ' ', $this->foldAccents($text)) ?? '';
    }

    /**
     * @return Collection<int, non-empty-string>
     */
    public function uniqueWords(string $text): Collection
    {
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->foldAccents($text)) ?? '';
        $words = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return collect($words)->unique();
    }
}
