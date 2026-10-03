<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;
use App\Models\SpeechClip;

final readonly class SpeechClipSpec
{
    public function __construct(
        public string $text,
        public VoiceConfig $voice,
        public SpeechSpeed $speed,
        public string $hash,
    ) {}

    public function path(): string
    {
        return SpeechClip::pathFor($this->voice->language, $this->voice->id, $this->hash);
    }
}
