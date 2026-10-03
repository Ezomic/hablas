<?php

declare(strict_types=1);

namespace App\Speech;

final readonly class EncodedAudio
{
    public function __construct(
        public string $bytes,
        public int $durationMs,
    ) {}
}
