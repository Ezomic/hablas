<?php

declare(strict_types=1);

namespace App\Speech;

final class SpeechCredits
{
    public function __construct(private readonly SpeechVoices $voices) {}

    /**
     * @return list<array{
     *     engine: string,
     *     license: string,
     *     licenseUrl: string,
     *     attribution: string,
     *     sourceUrl: string,
     *     restrictions: array<string, string>,
     *     voices: array<string, list<string>>
     * }>
     */
    public function handle(): array
    {
        $credits = [];

        foreach ($this->voices->languages() as $language) {
            foreach ($this->voices->forLanguage($language) as $voice) {
                $key = implode('|', [$voice->engine, $voice->license, $voice->attribution, $voice->sourceUrl]);

                $credits[$key] ??= [
                    'engine' => $voice->engine,
                    'license' => $voice->license,
                    'licenseUrl' => $voice->licenseUrl,
                    'attribution' => $voice->attribution,
                    'sourceUrl' => $voice->sourceUrl,
                    'restrictions' => $voice->restrictions,
                    'voices' => [],
                ];

                $credits[$key]['voices'][$language][] = $voice->voice;
            }
        }

        return array_values($credits);
    }
}
