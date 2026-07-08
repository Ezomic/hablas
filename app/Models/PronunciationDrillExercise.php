<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\PronunciationDrillExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $language_id
 * @property int|null $unit_id
 * @property string $word_a
 * @property string $word_a_translation_en
 * @property string $word_b
 * @property string $word_b_translation_en
 * @property string $target_word
 * @property string|null $audio_url
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['language_id', 'unit_id', 'word_a', 'word_a_translation_en', 'word_b', 'word_b_translation_en', 'target_word', 'audio_url'])]
class PronunciationDrillExercise extends Model
{
    /** @use HasFactory<PronunciationDrillExerciseFactory> */
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

    /** @return HasMany<PronunciationDrillAttempt, $this> */
    public function attempts(): HasMany
    {
        return $this->hasMany(PronunciationDrillAttempt::class);
    }
}
