<?php

declare(strict_types=1);

namespace App\Speech;

final class Mp3Encoder implements AudioEncoder
{
    public function __construct(private readonly SpeechPython $python) {}

    public function encode(array $wavs): array
    {
        $answers = $this->python->run('encode.py', array_map(
            fn (string $wav): array => ['wav' => base64_encode($wav)],
            $wavs,
        ));

        $clips = [];

        foreach (array_keys($wavs) as $position) {
            $mp3 = $answers[$position]['mp3'] ?? null;
            $duration = $answers[$position]['duration_ms'] ?? null;
            $bytes = is_string($mp3) ? base64_decode($mp3, true) : false;

            $clips[] = is_string($bytes) && is_int($duration) ? new EncodedAudio($bytes, $duration) : null;
        }

        return $clips;
    }
}
