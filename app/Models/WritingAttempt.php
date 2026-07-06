<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\WritingAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $writing_exercise_id
 * @property string $response
 * @property bool $is_correct
 * @property CarbonImmutable $submitted_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'writing_exercise_id', 'response', 'is_correct', 'submitted_at'])]
class WritingAttempt extends Model
{
    /** @use HasFactory<WritingAttemptFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<WritingExercise, $this> */
    public function writingExercise(): BelongsTo
    {
        return $this->belongsTo(WritingExercise::class);
    }
}
