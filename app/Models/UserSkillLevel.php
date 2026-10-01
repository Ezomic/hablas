<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use Carbon\CarbonImmutable;
use Database\Factories\UserSkillLevelFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property Skill $skill
 * @property CefrLevel $cefr_level
 * @property CefrSubLevel|null $sub_level The tier within cefr_level; null where the level has none (C1, C2) or on a row nothing has set yet.
 * @property CarbonImmutable|null $level_set_at When placement or practice last set the level; practice counts only attempts after it. Null counts every attempt.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'language_id', 'skill', 'cefr_level', 'sub_level', 'level_set_at'])]
class UserSkillLevel extends Model
{
    /** @use HasFactory<UserSkillLevelFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'skill' => Skill::class,
            'cefr_level' => CefrLevel::class,
            'sub_level' => CefrSubLevel::class,
            'level_set_at' => 'datetime',
        ];
    }

    public function currentTier(): ?CefrSubLevel
    {
        return $this->sub_level ?? CefrSubLevel::firstOf($this->cefr_level);
    }

    public function displayLevel(): string
    {
        return $this->currentTier()->value ?? $this->cefr_level->value;
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
}
