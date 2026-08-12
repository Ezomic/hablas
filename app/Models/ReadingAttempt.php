<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\ReadingAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $reading_passage_id
 * @property array<int, string> $answers
 * @property float $score
 * @property CarbonImmutable $attempted_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'reading_passage_id', 'answers', 'score', 'attempted_at'])]
class ReadingAttempt extends Model
{
    /** @use HasFactory<ReadingAttemptFactory> */
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

    /** @return BelongsTo<ReadingPassage, $this> */
    public function readingPassage(): BelongsTo
    {
        return $this->belongsTo(ReadingPassage::class);
    }
}
