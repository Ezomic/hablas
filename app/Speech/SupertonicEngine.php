<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;
use RuntimeException;

final class SupertonicEngine implements SpeechEngine
{
    public function __construct(private readonly SpeechPython $python) {}

    public function synthesize(VoiceConfig $voice, SpeechSpeed $speed, string $text): string
    {
        return $this->synthesizeBatch($voice, $speed, [$text])[0]
            ?? throw new RuntimeException("Supertonic could not speak [{$text}].");
    }

    public function synthesizeBatch(VoiceConfig $voice, SpeechSpeed $speed, array $texts): array
    {
        $answers = $this->python->run('synthesize.py', array_map(
            fn (string $text): array => [
                'text' => $text,
                'voice' => $voice->voice,
                'language' => $voice->language,
                'speed' => $voice->speedFor($speed),
            ],
            $texts,
        ));

        $wavs = [];

        foreach (array_keys($texts) as $position) {
            $wav = $answers[$position]['wav'] ?? null;
            $decoded = is_string($wav) ? base64_decode($wav, true) : false;
            $wavs[] = is_string($decoded) ? $decoded : null;
        }

        return $wavs;
    }
}
