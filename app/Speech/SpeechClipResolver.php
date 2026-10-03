<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;
use App\Models\SpeechClip;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Factory;

final class SpeechClipResolver
{
    private const CHUNK = 500;

    public function __construct(
        private readonly SpeechVoices $voices,
        private readonly SpeechKey $key,
        private readonly Repository $config,
        private readonly Factory $filesystem,
    ) {}

    /**
     * @param  list<string>  $texts
     * @return array<array-key, string|null> the clip url per text, null where there is none; numeric-string texts come back as integer keys, as PHP arrays do
     */
    public function resolve(string $language, array $texts, ?string $voiceId = null, SpeechSpeed $speed = SpeechSpeed::Normal): array
    {
        return array_map(
            fn (array $urls): ?string => $urls[$speed->value],
            $this->lookup($language, $texts, $voiceId, [$speed]),
        );
    }

    /**
     * Both speed variants of every text from one lookup, shaped the way the
     * pages send them: the normal clip as audioUrl, the slow one as audioSlowUrl.
     *
     * @param  list<string>  $texts
     * @return array<array-key, array{audioUrl: string|null, audioSlowUrl: string|null}>
     */
    public function resolveBoth(string $language, array $texts, ?string $voiceId = null): array
    {
        return array_map(
            fn (array $urls): array => ['audioUrl' => $urls[SpeechSpeed::Normal->value], 'audioSlowUrl' => $urls[SpeechSpeed::Slow->value]],
            $this->lookup($language, $texts, $voiceId, SpeechSpeed::cases()),
        );
    }

    /**
     * @param  list<string>  $texts
     * @param  list<SpeechSpeed>  $speeds
     * @return array<array-key, array<string, string|null>>
     */
    private function lookup(string $language, array $texts, ?string $voiceId, array $speeds): array
    {
        $none = array_fill_keys($texts, array_fill_keys(array_map(fn (SpeechSpeed $speed): string => $speed->value, $speeds), null));

        if ($texts === [] || ! $this->config->boolean('speech.enabled')) {
            return $none;
        }

        $voice = $voiceId === null ? $this->voices->primary($language) : $this->voices->find($language, $voiceId);

        if ($voice === null) {
            return $none;
        }

        $hashes = [];

        foreach ($texts as $text) {
            foreach ($speeds as $speed) {
                $hashes[$text][$speed->value] = $this->key->make($language, $voice->id, $speed, $text);
            }
        }

        $existing = [];

        foreach (array_chunk(array_merge(...array_map(array_values(...), array_values($hashes))), self::CHUNK) as $chunk) {
            foreach (SpeechClip::query()->whereIn('hash', $chunk)->get(['hash']) as $clip) {
                $existing[$clip->hash] = true;
            }
        }

        $disk = $this->filesystem->disk($this->config->string('speech.disk'));

        $urls = [];

        foreach ($hashes as $text => $byspeed) {
            foreach ($byspeed as $speed => $hash) {
                $urls[$text][$speed] = isset($existing[$hash]) ? $disk->url(SpeechClip::pathFor($language, $voice->id, $hash)) : null;
            }
        }

        return $urls;
    }
}
