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
     * @param  bool  $charactersOnly  only the lines of the stories and dialogues spoken by each voice
     * @return list<SpeechClipSpec>
     */
    public function clips(string $language, ?array $voices = null, ?array $speeds = null, bool $charactersOnly = false): array
    {
        $specs = [];

        $shared = $this->corpus->texts($language);

        foreach ($voices ?? $this->voices->forLanguage($language) as $voice) {
            $story = $this->corpus->characterTexts($language, $voice->voice);
            $texts = $charactersOnly ? $story : [...$shared, ...$story];

            foreach (array_values(array_unique($texts)) as $text) {
                foreach ($speeds ?? SpeechSpeed::cases() as $speed) {
                    $specs[] = new SpeechClipSpec($text, $voice, $speed, $this->key->make($language, $voice->id, $speed, $text));
                }
            }
        }

        return $specs;
    }
}
