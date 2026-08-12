<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\ListeningAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $listening_exercise_id
 * @property array<int, string> $answers
 * @property float $score
 * @property int $replays_used
 * @property CarbonImmutable $attempted_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'listening_exercise_id', 'answers', 'score', 'replays_used', 'attempted_at'])]
class ListeningAttempt extends Model
{
    /** @use HasFactory<ListeningAttemptFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'score' => 'float',
            'attempted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<ListeningExercise, $this> */
    public function listeningExercise(): BelongsTo
    {
        return $this->belongsTo(ListeningExercise::class);
    }
}
