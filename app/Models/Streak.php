<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\StreakFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $current_length
 * @property int $longest_length
 * @property int $freeze_days_remaining
 * @property CarbonImmutable|null $last_activity_date
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'current_length', 'longest_length', 'freeze_days_remaining', 'last_activity_date'])]
class Streak extends Model
{
    /** @use HasFactory<StreakFactory> */
    use HasFactory;

    /** Also the cap on earned freeze days, so they cannot be stockpiled. */
    public const int STARTING_FREEZE_DAYS = 2;

    public const int ACTIVE_DAYS_PER_FREEZE_DAY = 7;

    protected function casts(): array
    {
        return [
            'current_length' => 'integer',
            'longest_length' => 'integer',
            'freeze_days_remaining' => 'integer',
            'last_activity_date' => 'date',
        ];
    }

    public function hasFullFreezeAllowance(): bool
    {
        return $this->freeze_days_remaining >= self::STARTING_FREEZE_DAYS;
    }

    public function daysUntilNextFreezeDay(): ?int
    {
        if ($this->hasFullFreezeAllowance()) {
            return null;
        }

        return self::ACTIVE_DAYS_PER_FREEZE_DAY - ($this->current_length % self::ACTIVE_DAYS_PER_FREEZE_DAY);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
