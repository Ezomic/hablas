<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $lesson_exercise_id
 * @property string $targetable_type
 * @property int $targetable_id
 * @property bool $is_probe
 * @property bool $is_contrast
 * @property string|null $form
 */
#[Fillable(['lesson_exercise_id', 'targetable_type', 'targetable_id', 'is_probe', 'is_contrast', 'form'])]
class LessonExerciseTarget extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'lesson_exercise_targets';

    protected function casts(): array
    {
        return [
            'is_probe' => 'boolean',
            'is_contrast' => 'boolean',
        ];
    }

    /** @return BelongsTo<LessonExercise, $this> */
    public function lessonExercise(): BelongsTo
    {
        return $this->belongsTo(LessonExercise::class);
    }

    /** @return MorphTo<Model, $this> */
    public function targetable(): MorphTo
    {
        return $this->morphTo();
    }
}
