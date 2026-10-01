<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MasteryScope;
use Carbon\CarbonImmutable;
use Database\Factories\UnitItemMasteryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $unit_id
 * @property string $masterable_type
 * @property int $masterable_id
 * @property MasteryScope $scope
 * @property int $lesson_run_id
 * @property CarbonImmutable $mastered_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'unit_id', 'masterable_type', 'masterable_id', 'scope', 'lesson_run_id', 'mastered_at'])]
class UnitItemMastery extends Model
{
    /** @use HasFactory<UnitItemMasteryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'scope' => MasteryScope::class,
            'mastered_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Unit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /** @return MorphTo<Model, $this> */
    public function masterable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<LessonRun, $this> */
    public function lessonRun(): BelongsTo
    {
        return $this->belongsTo(LessonRun::class);
    }
}
