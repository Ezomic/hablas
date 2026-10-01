<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LessonStage;
use Carbon\CarbonImmutable;
use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $unit_id
 * @property LessonStage $stage
 * @property int $position
 * @property string $title
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['unit_id', 'stage', 'position', 'title'])]
class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'stage' => LessonStage::class,
        ];
    }

    /** @return BelongsTo<Unit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Lessons that still have an exercise to play: one whose exercises were all
     * retired, or are only substitutes, is as good as not there.
     *
     * @param  Builder<Lesson>  $query
     */
    #[Scope]
    protected function playable(Builder $query): void
    {
        $query->whereHas('exercises', fn (Builder $exercises) => $exercises->whereNull('retired_at')->whereNull('substitute_for_id'));
    }

    /** @return HasMany<LessonExercise, $this> */
    public function exercises(): HasMany
    {
        return $this->hasMany(LessonExercise::class)->orderBy('position');
    }

    /** @return HasMany<LessonRun, $this> */
    public function runs(): HasMany
    {
        return $this->hasMany(LessonRun::class);
    }
}
