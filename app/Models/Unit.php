<?php

namespace App\Models;

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\Skill;
use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $language_id
 * @property string $slug
 * @property CefrLevel $cefr_level
 * @property ContextTag $context_tag
 * @property Skill $primary_skill
 * @property Skill|null $secondary_skill
 * @property string $title
 * @property string $task_description
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['language_id', 'slug', 'cefr_level', 'context_tag', 'primary_skill', 'secondary_skill', 'title', 'task_description', 'sort_order'])]
class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cefr_level' => CefrLevel::class,
            'context_tag' => ContextTag::class,
            'primary_skill' => Skill::class,
            'secondary_skill' => Skill::class,
        ];
    }

    /** @return BelongsTo<Language, $this> */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /** @return HasMany<VocabularyItem, $this> */
    public function vocabularyItems(): HasMany
    {
        return $this->hasMany(VocabularyItem::class);
    }

    /** @return HasMany<GrammarPoint, $this> */
    public function grammarPoints(): HasMany
    {
        return $this->hasMany(GrammarPoint::class);
    }

    /** @return HasMany<UserUnitProgress, $this> */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserUnitProgress::class);
    }
}
