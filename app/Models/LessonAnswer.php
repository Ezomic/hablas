<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ErrorTagCategory;
use App\Enums\SkipReason;
use Carbon\CarbonImmutable;
use Database\Factories\LessonAnswerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $lesson_run_id
 * @property int $lesson_exercise_id
 * @property string $step
 * @property int $attempt
 * @property bool $hinted
 * @property bool $skipped
 * @property SkipReason|null $skip_reason
 * @property array<string, mixed>|null $response
 * @property bool|null $is_correct
 * @property bool|null $self_graded_correct
 * @property float|null $score
 * @property string|null $note
 * @property ErrorTagCategory|null $error_tag_category
 * @property CarbonImmutable|null $flagged_at
 * @property CarbonImmutable $answered_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['lesson_run_id', 'lesson_exercise_id', 'step', 'attempt', 'hinted', 'skipped', 'skip_reason', 'response', 'is_correct', 'self_graded_correct', 'score', 'note', 'error_tag_category', 'flagged_at', 'answered_at'])]
class LessonAnswer extends Model
{
    /** @use HasFactory<LessonAnswerFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'hinted' => 'boolean',
            'skipped' => 'boolean',
            'skip_reason' => SkipReason::class,
            'response' => 'array',
            'is_correct' => 'boolean',
            'self_graded_correct' => 'boolean',
            'score' => 'float',
            'error_tag_category' => ErrorTagCategory::class,
            'flagged_at' => 'datetime',
            'answered_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<LessonRun, $this> */
    public function lessonRun(): BelongsTo
    {
        return $this->belongsTo(LessonRun::class);
    }

    /** @return BelongsTo<LessonExercise, $this> */
    public function lessonExercise(): BelongsTo
    {
        return $this->belongsTo(LessonExercise::class);
    }

    /** @return MorphToMany<VocabularyItem, $this> */
    public function vocabularyItems(): MorphToMany
    {
        return $this->morphedByMany(VocabularyItem::class, 'targetable', 'lesson_answer_targets')->withPivot('is_correct');
    }

    /** @return MorphToMany<GrammarPoint, $this> */
    public function grammarPoints(): MorphToMany
    {
        return $this->morphedByMany(GrammarPoint::class, 'targetable', 'lesson_answer_targets')->withPivot('is_correct');
    }

    /**
     * Whether this answer settles its exercise: the server's grade, a
     * self-check made offline, or the learner disputing the grade.
     */
    public function settlesExercise(): bool
    {
        return $this->is_correct === true || $this->self_graded_correct === true || $this->flagged_at !== null;
    }
}
