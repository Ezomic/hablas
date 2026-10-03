<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;

final class SpeechInventory
{
    public function __construct(
        private readonly SpeechCorpus $corpus,
        private readonly SpeechVoices $voices,
        private readonly SpeechKey $key,
    ) {}

    /**
     * @param  list<VoiceConfig>|null  $voices  every configured voice of the language by default
     * @param  list<SpeechSpeed>|null  $speeds  every speed by default
     * @return list<SpeechClipSpec>
     */
    public function clips(string $language, ?array $voices = null, ?array $speeds = null): array
    {
        $specs = [];

        foreach ($this->corpus->texts($language) as $text) {
            foreach ($voices ?? $this->voices->forLanguage($language) as $voice) {
                foreach ($speeds ?? SpeechSpeed::cases() as $speed) {
                    $specs[] = new SpeechClipSpec($text, $voice, $speed, $this->key->make($language, $voice->id, $speed, $text));
                }
            }
        }

        return $specs;
    }
}
