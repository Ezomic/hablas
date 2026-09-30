<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skill;
use Database\Factories\PlacementTestAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property Skill|null $skill The one skill a re-take places; null for the full test over all four.
 * @property Carbon $started_at
 * @property Carbon|null $completed_at
 * @property bool $skipped Finished by a skip at A1, answers or not; such an attempt is not a placement the learner took.
 * @property array<string, string>|array<string, array{cefr_level: string, sub_level: string}>|null $resulting_skill_levels Old attempts (ScorePlacementTest) wrote skill => CefrLevel string; new ones (FinalizePlacementAttempt) write skill => {cefr_level, sub_level}.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'language_id', 'skill', 'started_at', 'completed_at', 'skipped', 'resulting_skill_levels'])]
class PlacementTestAttempt extends Model
{
    /** @use HasFactory<PlacementTestAttemptFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'skill' => Skill::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'skipped' => 'boolean',
            'resulting_skill_levels' => 'array',
        ];
    }

    /** @return list<Skill> */
    public function skills(): array
    {
        return $this->skill === null ? Skill::cases() : [$this->skill];
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

    /** @return HasMany<PlacementTestResponse, $this> */
    public function responses(): HasMany
    {
        return $this->hasMany(PlacementTestResponse::class, 'attempt_id');
    }
}
