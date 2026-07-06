<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\ShadowingAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $shadowing_exercise_id
 * @property string $transcript_guess
 * @property float $score
 * @property CarbonImmutable $attempted_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'shadowing_exercise_id', 'transcript_guess', 'score', 'attempted_at'])]
class ShadowingAttempt extends Model
{
    /** @use HasFactory<ShadowingAttemptFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'attempted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<ShadowingExercise, $this> */
    public function shadowingExercise(): BelongsTo
    {
        return $this->belongsTo(ShadowingExercise::class);
    }
}
