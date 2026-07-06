<?php

namespace App\Models;

use App\Enums\UnitProgressStatus;
use Carbon\CarbonImmutable;
use Database\Factories\UserUnitProgressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $unit_id
 * @property UnitProgressStatus $status
 * @property CarbonImmutable|null $completed_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'unit_id', 'status', 'completed_at'])]
class UserUnitProgress extends Model
{
    /** @use HasFactory<UserUnitProgressFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => UnitProgressStatus::class,
            'completed_at' => 'datetime',
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
