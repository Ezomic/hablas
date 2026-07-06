<?php

namespace App\Models;

use App\Enums\ErrorTagCategory;
use Database\Factories\GrammarPointFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $language_id
 * @property int|null $unit_id
 * @property string $title
 * @property string $explanation
 * @property ErrorTagCategory|null $error_tag_category
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['language_id', 'unit_id', 'title', 'explanation', 'error_tag_category'])]
class GrammarPoint extends Model
{
    /** @use HasFactory<GrammarPointFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'error_tag_category' => ErrorTagCategory::class,
        ];
    }

    /** @return BelongsTo<Language, $this> */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /** @return BelongsTo<Unit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
