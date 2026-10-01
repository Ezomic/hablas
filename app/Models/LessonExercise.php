<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LessonExerciseFormat;
use Carbon\CarbonImmutable;
use Database\Factories\LessonExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $lesson_id
 * @property string $key
 * @property int $position
 * @property string $block
 * @property LessonExerciseFormat $format
 * @property string|null $probe_set
 * @property array<string, mixed> $payload
 * @property int|null $substitute_for_id
 * @property string $content_hash
 * @property CarbonImmutable|null $retired_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['lesson_id', 'key', 'position', 'block', 'format', 'probe_set', 'payload', 'substitute_for_id', 'content_hash', 'retired_at'])]
class LessonExercise extends Model
{
    /** @use HasFactory<LessonExerciseFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'format' => LessonExerciseFormat::class,
            'payload' => 'array',
            'retired_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Lesson, $this> */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /** @return MorphToMany<VocabularyItem, $this> */
    public function vocabularyItems(): MorphToMany
    {
        return $this->morphedByMany(VocabularyItem::class, 'targetable', 'lesson_exercise_targets')
            ->withPivot(['is_probe', 'is_contrast', 'form']);
    }

    /** @return MorphToMany<GrammarPoint, $this> */
    public function grammarPoints(): MorphToMany
    {
        return $this->morphedByMany(GrammarPoint::class, 'targetable', 'lesson_exercise_targets')
            ->withPivot(['is_probe', 'is_contrast', 'form']);
    }

    /** @return HasMany<LessonExerciseTarget, $this> */
    public function targets(): HasMany
    {
        return $this->hasMany(LessonExerciseTarget::class);
    }

    /** @return HasOne<LessonExercise, $this> */
    public function substitute(): HasOne
    {
        return $this->hasOne(self::class, 'substitute_for_id');
    }

    /** @return BelongsTo<LessonExercise, $this> */
    public function original(): BelongsTo
    {
        return $this->belongsTo(self::class, 'substitute_for_id');
    }

    public function isSubstitute(): bool
    {
        return $this->substitute_for_id !== null;
    }
}
