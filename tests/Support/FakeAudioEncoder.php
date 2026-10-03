<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Speech\AudioEncoder;
use App\Speech\EncodedAudio;

final class FakeAudioEncoder implements AudioEncoder
{
    public function encode(array $wavs): array
    {
        return array_map(fn (string $wav): EncodedAudio => new EncodedAudio("mp3:{$wav}", 1500), $wavs);
    }
}
