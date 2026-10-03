<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\SpeechSpeed;
use Illuminate\Contracts\Filesystem\Factory;

final class SpeechSamples
{
    private const SENTENCES = [
        'es' => 'Me gustaría aprender español.',
        'pt' => 'Eu gostaria de aprender português.',
        'fr' => 'Je voudrais apprendre le français.',
        'it' => 'Vorrei imparare l\'italiano.',
    ];

    public function __construct(
        private readonly SpeechVoices $voices,
        private readonly SpeechEngineManager $engines,
        private readonly AudioEncoder $encoder,
        private readonly Factory $filesystem,
    ) {}

    /** @return list<string> the paths written on the local disk */
    public function write(): array
    {
        $disk = $this->filesystem->disk('local');
        $written = [];

        foreach (self::SENTENCES as $language => $sentence) {
            $voice = $this->voices->primary($language);

            if ($voice === null) {
                continue;
            }

            foreach (SpeechSpeed::cases() as $speed) {
                $wav = $this->engines->forVoice($voice)->synthesize($voice, $speed, $sentence);
                $audio = $this->encoder->encode([$wav])[0];

                if ($audio !== null) {
                    $path = "speech-samples/{$language}-{$voice->id}-{$speed->value}.mp3";
                    $disk->put($path, $audio->bytes);
                    $written[] = $path;
                }
            }
        }

        return $written;
    }
}
