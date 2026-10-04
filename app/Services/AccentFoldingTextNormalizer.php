<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\TextNormalizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Normalizer;

/**
 * The folding, whitespace and tokenization mechanics shared by every language.
 * Subclasses supply only the fold map, which is where the languages actually
 * differ: each keeps the marks that are phonemic for it.
 */
abstract class AccentFoldingTextNormalizer implements TextNormalizer
{
    /** @var array<string, string> */
    private const SEARCH_ONLY_FOLDS = ['ñ' => 'n', 'ç' => 'c', 'ã' => 'a', 'õ' => 'o', 'œ' => 'oe'];

    /**
     * @return array<string, string>
     */
    abstract protected function vowelAccentFolds(): array;

    public function foldAccents(string $text): string
    {
        return strtr(Str::lower(trim($this->composed($text))), $this->vowelAccentFolds());
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
        return collect($this->words($this->foldAccents($text)))->unique();
    }

    public function answerKey(string $text): string
    {
        return implode(' ', $this->words($this->foldAccents($text)));
    }

    public function exactKey(string $text): string
    {
        return implode(' ', $this->words(Str::lower(trim($this->composed($text)))));
    }

    public function searchKey(string $text): string
    {
        return implode(' ', $this->words(strtr($this->foldAccents($text), self::SEARCH_ONLY_FOLDS), splitApostrophes: true));
    }

    /**
     * The article is matched before folding, so "él" (he) and Portuguese "à"
     * are not mistaken for "el" and "a".
     */
    public function sortKey(string $text): string
    {
        $words = $this->words(Str::lower($text), splitApostrophes: true);

        if (count($words) > 1 && in_array($words[0], $this->articles(), true)) {
            array_shift($words);
        }

        return $this->searchKey(implode(' ', $words));
    }

    /**
     * The fold maps and ñ are single composed characters, so text typed or
     * pasted with a combining accent (NFD) would otherwise never match.
     */
    private function composed(string $text): string
    {
        $composed = Normalizer::normalize($text, Normalizer::FORM_C);

        return is_string($composed) ? $composed : $text;
    }

    /**
     * An apostrophe inside a word is part of it (l'italiano, j'ai), so a typed
     * answer without it is not the same answer; search and sort keys split on it.
     *
     * @return list<non-empty-string>
     */
    private function words(string $text, bool $splitApostrophes = false): array
    {
        $text = str_replace(["\u{2019}", "\u{2018}", "\u{02BC}", '`', "\u{00B4}"], "'", $text);

        if ($splitApostrophes) {
            return preg_split('/[^\p{L}\p{N}]+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        }

        $words = preg_split("/[^\p{L}\p{N}']+/u", $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $trimmed = [];

        foreach ($words as $word) {
            $word = trim($word, "'");

            if ($word !== '') {
                $trimmed[] = $word;
            }
        }

        return $trimmed;
    }
}
