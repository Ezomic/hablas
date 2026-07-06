<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\WeeklyReflectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property CarbonImmutable $week_start_date
 * @property array{statement_ids: array<int, int>, can_do_ids: array<int, int>} $responses
 * @property CarbonImmutable|null $submitted_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'language_id', 'week_start_date', 'responses', 'submitted_at'])]
class WeeklyReflection extends Model
{
    /** @use HasFactory<WeeklyReflectionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'week_start_date' => 'date',
            'responses' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Language, $this> */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
