<?php

declare(strict_types=1);

namespace App\Speech;

final readonly class SpeechRunReport
{
    public function __construct(
        public int $planned,
        public int $skipped,
        public int $generated,
        public int $failed,
        public int $bytes,
    ) {}
}
