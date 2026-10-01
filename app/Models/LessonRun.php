<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use Carbon\CarbonImmutable;
use Database\Factories\LessonRunFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property int|null $open_lesson_id lesson_id while the run is in progress and null once completed, so a unique index allows one open run per lesson.
 * @property LessonRunKind $kind
 * @property string|null $probe_set
 * @property LessonRunStatus $status
 * @property list<array{id: int, origin: string}> $plan
 * @property int $seed
 * @property bool $counts_as_evidence
 * @property float|null $first_try_accuracy
 * @property array<string, mixed>|null $result
 * @property CarbonImmutable $started_at
 * @property CarbonImmutable|null $completed_at
 * @property CarbonImmutable|null $summary_seen_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'lesson_id', 'open_lesson_id', 'kind', 'probe_set', 'status', 'plan', 'seed', 'counts_as_evidence', 'first_try_accuracy', 'result', 'started_at', 'completed_at', 'summary_seen_at'])]
class LessonRun extends Model
{
    /** @use HasFactory<LessonRunFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'kind' => LessonRunKind::class,
            'status' => LessonRunStatus::class,
            'plan' => 'array',
            'counts_as_evidence' => 'boolean',
            'first_try_accuracy' => 'float',
            'result' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'summary_seen_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Lesson, $this> */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /** @return HasMany<LessonAnswer, $this> */
    public function answers(): HasMany
    {
        return $this->hasMany(LessonAnswer::class);
    }

    /** @return list<int> */
    public function planExerciseIds(): array
    {
        return array_map(fn (array $entry): int => $entry['id'], $this->plan);
    }
}
