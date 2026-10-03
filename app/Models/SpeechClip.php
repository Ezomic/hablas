<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SpeechSpeed;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $language
 * @property string $voice_id
 * @property SpeechSpeed $speed
 * @property string $hash
 * @property int $bytes
 * @property int|null $duration_ms
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['language', 'voice_id', 'speed', 'hash', 'bytes', 'duration_ms'])]
class SpeechClip extends Model
{
    protected function casts(): array
    {
        return [
            'speed' => SpeechSpeed::class,
        ];
    }

    public static function pathFor(string $language, string $voiceId, string $hash): string
    {
        return "speech/{$language}/{$voiceId}/".substr($hash, 0, 2)."/{$hash}.mp3";
    }

    public function path(): string
    {
        return self::pathFor($this->language, $this->voice_id, $this->hash);
    }
}
