<?php

namespace App\Services;

use App\Models\Language;

/**
 * The BCP 47 tag the browser's speech APIs should use for a language, for both
 * recognition (what the learner said) and synthesis (what we read out).
 *
 * Returns null rather than guessing for a language we have no tag for: leaving
 * the browser on its own default is a degraded experience, whereas asserting
 * the wrong locale actively mistranscribes, which is the bug this exists to
 * prevent.
 */
class SpeechLocaleResolver
{
    /** @var array<string, string> */
    private const LOCALES = [
        'es' => 'es-ES',
        'pt' => 'pt-PT',
    ];

    public function forLanguage(Language $language): ?string
    {
        return self::LOCALES[$language->code] ?? null;
    }
}
