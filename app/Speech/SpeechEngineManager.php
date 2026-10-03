<?php

declare(strict_types=1);

namespace App\Speech;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

final class SpeechEngineManager
{
    public function __construct(
        private readonly Container $container,
        private readonly Repository $config,
    ) {}

    public function engine(string $name): SpeechEngine
    {
        $class = $this->config->get("speech.engines.{$name}");

        if (! is_string($class)) {
            throw new InvalidArgumentException("Speech engine [{$name}] is not configured.");
        }

        $engine = $this->container->make($class);

        if (! $engine instanceof SpeechEngine) {
            throw new InvalidArgumentException("Speech engine [{$name}] must implement ".SpeechEngine::class.'.');
        }

        return $engine;
    }

    public function forVoice(VoiceConfig $voice): SpeechEngine
    {
        return $this->engine($voice->engine);
    }
}
