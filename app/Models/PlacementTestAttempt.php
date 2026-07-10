<?php

namespace App\Models;

use Database\Factories\PlacementTestAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property Carbon $started_at
 * @property Carbon|null $completed_at
 * @property array<string, array{cefr_level: string, sub_level: string}>|null $resulting_skill_levels
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'language_id', 'started_at', 'completed_at', 'resulting_skill_levels'])]
class PlacementTestAttempt extends Model
{
    /** @use HasFactory<PlacementTestAttemptFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'resulting_skill_levels' => 'array',
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

    /** @return HasMany<PlacementTestResponse, $this> */
    public function responses(): HasMany
    {
        return $this->hasMany(PlacementTestResponse::class, 'attempt_id');
    }
}
