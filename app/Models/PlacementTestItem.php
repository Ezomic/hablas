<?php

namespace App\Models;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use Database\Factories\PlacementTestItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $language_id
 * @property Skill $skill
 * @property string $prompt
 * @property array<int, string> $options
 * @property string $correct_answer
 * @property CefrSubLevel $cefr_sublevel_tag
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['language_id', 'skill', 'prompt', 'options', 'correct_answer', 'cefr_sublevel_tag', 'sort_order'])]
class PlacementTestItem extends Model
{
    /** @use HasFactory<PlacementTestItemFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'skill' => Skill::class,
            'options' => 'array',
            'cefr_sublevel_tag' => CefrSubLevel::class,
        ];
    }

    /** @return BelongsTo<Language, $this> */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
