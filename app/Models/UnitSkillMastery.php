<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skill;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $unit_id
 * @property Skill $skill
 * @property int|null $lesson_run_id
 * @property CarbonImmutable $mastered_at
 */
#[Fillable(['user_id', 'unit_id', 'skill', 'lesson_run_id', 'mastered_at'])]
class UnitSkillMastery extends Model
{
    protected function casts(): array
    {
        return [
            'skill' => Skill::class,
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
}
