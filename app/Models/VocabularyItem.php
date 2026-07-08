<?php

namespace App\Models;

use Database\Factories\VocabularyItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $language_id
 * @property int|null $unit_id
 * @property string $term
 * @property string $translation_en
 * @property bool $is_cognate
 * @property string $part_of_speech
 * @property string|null $audio_url
 * @property string|null $contrast_note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['language_id', 'unit_id', 'term', 'translation_en', 'is_cognate', 'part_of_speech', 'audio_url', 'contrast_note'])]
class VocabularyItem extends Model
{
    /** @use HasFactory<VocabularyItemFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_cognate' => 'boolean',
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

    /** @return MorphMany<SrsCard, $this> */
    public function srsCards(): MorphMany
    {
        return $this->morphMany(SrsCard::class, 'cardable');
    }
}
