<?php

namespace App\Models;

use App\Enums\WritingExerciseType;
use Carbon\CarbonImmutable;
use Database\Factories\WritingExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $language_id
 * @property int|null $unit_id
 * @property WritingExerciseType $type
 * @property string $prompt
 * @property array<string, mixed>|null $template
 * @property array<int, string> $correct_answers
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['language_id', 'unit_id', 'type', 'prompt', 'template', 'correct_answers'])]
class WritingExercise extends Model
{
    /** @use HasFactory<WritingExerciseFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => WritingExerciseType::class,
            'template' => 'array',
            'correct_answers' => 'array',
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

    /** @return HasMany<WritingAttempt, $this> */
    public function attempts(): HasMany
    {
        return $this->hasMany(WritingAttempt::class);
    }
}
