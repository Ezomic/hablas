<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;

final readonly class VoiceConfig
{
    /**
     * @param  array<string, float>  $speeds  the engine speed parameter per speed variant
     */
    public function __construct(
        public string $id,
        public string $language,
        public string $engine,
        public string $voice,
        public array $speeds,
        public bool $primary,
        public string $license,
        public string $attribution,
        public string $sourceUrl,
    ) {}

    public function speedFor(SpeechSpeed $speed): float
    {
        return $this->speeds[$speed->value];
    }
}
