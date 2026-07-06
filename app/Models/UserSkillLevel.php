<?php

namespace App\Models;

use App\Enums\CefrLevel;
use App\Enums\Skill;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'language_id', 'skill', 'cefr_level'])]
class UserSkillLevel extends Model
{
    /** @use HasFactory<UserSkillLevelFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'skill' => Skill::class,
            'cefr_level' => CefrLevel::class,
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
}
