<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\ShadowingExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $language_id
 * @property int|null $unit_id
 * @property string $target_transcript
 * @property string|null $audio_url
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['language_id', 'unit_id', 'target_transcript', 'audio_url'])]
class ShadowingExercise extends Model
{
    /** @use HasFactory<ShadowingExerciseFactory> */
    use HasFactory;

    /** @return BelongsTo<Language, $this> */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /** @return BelongsTo<Unit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /** @return HasMany<ShadowingAttempt, $this> */
    public function attempts(): HasMany
    {
        return $this->hasMany(ShadowingAttempt::class);
    }
}
