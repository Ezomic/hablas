<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Enums\SpeechSpeed;
use App\Speech\SpeechEngine;
use App\Speech\VoiceConfig;
use RuntimeException;

final class FakeSpeechEngine implements SpeechEngine
{
    public const FAILING_TEXT = 'FAIL';

    /** @var list<array{voice: string, speed: string, texts: list<string>}> */
    public static array $batches = [];

    public static function spoken(): int
    {
        return array_sum(array_map(fn (array $batch): int => count($batch['texts']), self::$batches));
    }

    public function synthesize(VoiceConfig $voice, SpeechSpeed $speed, string $text): string
    {
        return $this->synthesizeBatch($voice, $speed, [$text])[0] ?? throw new RuntimeException('failed');
    }

    public function synthesizeBatch(VoiceConfig $voice, SpeechSpeed $speed, array $texts): array
    {
        self::$batches[] = ['voice' => $voice->id, 'speed' => $speed->value, 'texts' => $texts];

        return array_map(
            fn (string $text): ?string => $text === self::FAILING_TEXT ? null : "wav|{$voice->id}|{$speed->value}|{$text}",
            $texts,
        );
    }
}
