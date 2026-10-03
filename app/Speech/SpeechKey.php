<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;
use Illuminate\Contracts\Config\Repository;

final class SpeechKey
{
    public function __construct(
        private readonly SpeechText $text,
        private readonly Repository $config,
    ) {}

    public function make(string $language, string $voiceId, SpeechSpeed $speed, string $text): string
    {
        return hash('sha256', implode('|', [
            (string) $this->config->integer('speech.normaliser_version'),
            $language,
            $voiceId,
            $speed->value,
            $this->text->normalise($text),
        ]));
    }
}
