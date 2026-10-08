<?php

declare(strict_types=1);

namespace App\Speech;

/**
 * The voice of each character, the same in every lesson dialogue and every
 * story of the language, so Pablo always sounds like Pablo. The narrator Ana
 * uses the primary voice; a name not listed gets a fixed voice from its
 * letters, so it never changes either.
 */
final class CharacterVoices
{
    private const BY_SPEAKER = [
        'Ana' => 'F1',
        'Marta' => 'F2',
        'Carmen' => 'F2',
        'Pablo' => 'M1',
        'Luis' => 'M2',
        'Empleado' => 'F2',
        'Dependiente' => 'M2',
        'Camarero' => 'M1',
        'Médico' => 'F2',
        'Agente' => 'M2',
        'Otro' => 'F2',
    ];

    private const VOICES = ['F1', 'F2', 'M1', 'M2'];

    public static function voiceFor(string $speaker): string
    {
        return self::BY_SPEAKER[$speaker] ?? self::VOICES[crc32($speaker) % count(self::VOICES)];
    }
}
