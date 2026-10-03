<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;
use App\Models\SpeechClip;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Factory;

final class SpeechClipResolver
{
    public function __construct(
        private readonly SpeechVoices $voices,
        private readonly SpeechKey $key,
        private readonly Repository $config,
        private readonly Factory $filesystem,
    ) {}

    /**
     * @param  list<string>  $texts
     * @return array<string, string|null> the clip url per text, null where there is none
     */
    public function resolve(string $language, array $texts, ?string $voiceId = null, SpeechSpeed $speed = SpeechSpeed::Normal): array
    {
        $none = array_fill_keys($texts, null);

        if ($texts === [] || ! $this->config->boolean('speech.enabled')) {
            return $none;
        }

        $voice = $voiceId === null ? $this->voices->primary($language) : $this->voices->find($language, $voiceId);

        if ($voice === null) {
            return $none;
        }

        $hashes = [];

        foreach ($texts as $text) {
            $hashes[$text] = $this->key->make($language, $voice->id, $speed, $text);
        }

        $existing = SpeechClip::query()->whereIn('hash', array_values($hashes))->pluck('hash')->flip();
        $disk = $this->filesystem->disk($this->config->string('speech.disk'));

        $urls = [];

        foreach ($hashes as $text => $hash) {
            $urls[$text] = $existing->has($hash) ? $disk->url(SpeechClip::pathFor($language, $voice->id, $hash)) : null;
        }

        return $urls;
    }
}
