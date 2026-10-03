<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;

interface SpeechEngine
{
    /** @return string WAV bytes for the text spoken in the voice at the speed variant */
    public function synthesize(VoiceConfig $voice, SpeechSpeed $speed, string $text): string;
}
