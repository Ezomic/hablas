<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\TextNormalizer;
use App\Models\Language;
use RuntimeException;

final class TextNormalizerResolver
{
    public function forLanguage(Language $language): TextNormalizer
    {
        return match ($language->code) {
            'es' => new SpanishTextNormalizer,
            'pt' => new PortugueseTextNormalizer,
            default => throw new RuntimeException("No text normalizer for language '{$language->code}'."),
        };
    }
}
