<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\PronunciationDrillAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $pronunciation_drill_exercise_id
 * @property string $transcript_guess
 * @property bool $is_correct
 * @property float $score
 * @property CarbonImmutable $attempted_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'pronunciation_drill_exercise_id', 'transcript_guess', 'is_correct', 'score', 'attempted_at'])]
class PronunciationDrillAttempt extends Model
{
    /** @use HasFactory<PronunciationDrillAttemptFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'attempted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<PronunciationDrillExercise, $this> */
    public function pronunciationDrillExercise(): BelongsTo
    {
        return $this->belongsTo(PronunciationDrillExercise::class);
    }
}
