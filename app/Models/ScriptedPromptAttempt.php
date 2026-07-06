<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\ScriptedPromptAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $scripted_prompt_exercise_id
 * @property string $transcript_guess
 * @property float $score
 * @property CarbonImmutable $attempted_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'scripted_prompt_exercise_id', 'transcript_guess', 'score', 'attempted_at'])]
class ScriptedPromptAttempt extends Model
{
    /** @use HasFactory<ScriptedPromptAttemptFactory> */
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

    /** @return BelongsTo<ScriptedPromptExercise, $this> */
    public function scriptedPromptExercise(): BelongsTo
    {
        return $this->belongsTo(ScriptedPromptExercise::class);
    }
}
