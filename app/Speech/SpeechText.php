<?php

declare(strict_types=1);

namespace App\Speech;

use InvalidArgumentException;
use Normalizer;

final class SpeechText
{
    private const APOSTROPHES = ["\u{2019}", "\u{2018}", "\u{02BC}", "\u{0060}", "\u{00B4}"];

    public function normalise(string $text): string
    {
        if (! mb_check_encoding($text, 'UTF-8')) {
            throw new InvalidArgumentException('Speech text must be valid UTF-8.');
        }

        $composed = Normalizer::normalize($text, Normalizer::FORM_C);

        $unified = str_replace(self::APOSTROPHES, "'", is_string($composed) ? $composed : $text);

        return trim(preg_replace('/\s+/u', ' ', $unified) ?? '');
    }
}
