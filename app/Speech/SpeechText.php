<?php

declare(strict_types=1);

namespace App\Speech;

use Normalizer;

final class SpeechText
{
    private const APOSTROPHES = ["\u{2019}", "\u{2018}", "\u{02BC}", "\u{0060}", "\u{00B4}"];

    public function normalise(string $text): string
    {
        $composed = Normalizer::normalize($text, Normalizer::FORM_C);

        $unified = str_replace(self::APOSTROPHES, "'", is_string($composed) ? $composed : $text);

        return trim(preg_replace('/\s+/u', ' ', $unified) ?? '');
    }
}
