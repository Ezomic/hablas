<?php

declare(strict_types=1);

namespace App\Speech;

use Illuminate\Contracts\Config\Repository;

final class SpeechVoices
{
    public function __construct(private readonly Repository $config) {}

    /** @return list<string> */
    public function languages(): array
    {
        $languages = $this->config->get('speech.languages', []);

        return is_array($languages) ? array_map(strval(...), array_keys($languages)) : [];
    }

    /** @return list<VoiceConfig> */
    public function forLanguage(string $language): array
    {
        $entries = $this->config->get("speech.languages.{$language}.voices", []);

        $voices = [];

        foreach (is_array($entries) ? $entries : [] as $entry) {
            /** @var array{id: string, engine: string, voice: string, speeds: array<string, float>, primary?: bool, license: string, attribution: string, source_url: string} $entry */
            $voices[] = new VoiceConfig(
                id: $entry['id'],
                language: $language,
                engine: $entry['engine'],
                voice: $entry['voice'],
                speeds: $entry['speeds'],
                primary: $entry['primary'] ?? false,
                license: $entry['license'],
                attribution: $entry['attribution'],
                sourceUrl: $entry['source_url'],
            );
        }

        return $voices;
    }

    public function find(string $language, string $voiceId): ?VoiceConfig
    {
        foreach ($this->forLanguage($language) as $voice) {
            if ($voice->id === $voiceId) {
                return $voice;
            }
        }

        return null;
    }

    public function primary(string $language): ?VoiceConfig
    {
        foreach ($this->forLanguage($language) as $voice) {
            if ($voice->primary) {
                return $voice;
            }
        }

        return $this->forLanguage($language)[0] ?? null;
    }

    public function requiresAudio(string $language): bool
    {
        return (bool) $this->config->get("speech.languages.{$language}.require_audio", false);
    }
}
