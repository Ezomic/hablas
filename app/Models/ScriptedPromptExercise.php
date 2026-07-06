<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\ScriptedPromptExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $language_id
 * @property int|null $unit_id
 * @property string $prompt_text
 * @property array<int, string> $expected_keywords
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['language_id', 'unit_id', 'prompt_text', 'expected_keywords'])]
class ScriptedPromptExercise extends Model
{
    /** @use HasFactory<ScriptedPromptExerciseFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'expected_keywords' => 'array',
        ];
    }

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

    /** @return HasMany<ScriptedPromptAttempt, $this> */
    public function attempts(): HasMany
    {
        return $this->hasMany(ScriptedPromptAttempt::class);
    }
}
