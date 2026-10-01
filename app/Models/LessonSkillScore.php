<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skill;
use Carbon\CarbonImmutable;
use Database\Factories\LessonSkillScoreFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property int $lesson_run_id
 * @property Skill $skill
 * @property float $score First-try accuracy for the skill in the run, 0 to 100.
 * @property int $graded_count
 * @property bool $counts_toward_level
 * @property CarbonImmutable $scored_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'language_id', 'lesson_run_id', 'skill', 'score', 'graded_count', 'counts_toward_level', 'scored_at'])]
class LessonSkillScore extends Model
{
    /** @use HasFactory<LessonSkillScoreFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'skill' => Skill::class,
            'score' => 'float',
            'counts_toward_level' => 'boolean',
            'scored_at' => 'datetime',
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

    /** @return BelongsTo<LessonRun, $this> */
    public function lessonRun(): BelongsTo
    {
        return $this->belongsTo(LessonRun::class);
    }
}
