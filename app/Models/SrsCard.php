<?php

namespace App\Models;

use App\Enums\SrsCardState;
use Carbon\CarbonImmutable;
use Database\Factories\SrsCardFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property string $cardable_type
 * @property int $cardable_id
 * @property SrsCardState $state
 * @property float $stability
 * @property float $difficulty
 * @property int $reps
 * @property int $lapses
 * @property int $consecutive_lapses
 * @property bool $is_weak_spot
 * @property CarbonImmutable $due_at
 * @property CarbonImmutable|null $last_reviewed_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'language_id', 'cardable_type', 'cardable_id', 'state', 'stability', 'difficulty', 'reps', 'lapses', 'consecutive_lapses', 'is_weak_spot', 'due_at', 'last_reviewed_at'])]
class SrsCard extends Model
{
    /** @use HasFactory<SrsCardFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'state' => SrsCardState::class,
            'is_weak_spot' => 'boolean',
            'due_at' => 'datetime',
            'last_reviewed_at' => 'datetime',
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

    /** @return MorphTo<Model, $this> */
    public function cardable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return HasMany<SrsReview, $this> */
    public function reviews(): HasMany
    {
        return $this->hasMany(SrsReview::class);
    }
}
