<?php

namespace App\Models;

use App\Enums\CefrLevel;
use Carbon\CarbonImmutable;
use Database\Factories\ReadingPassageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $language_id
 * @property int|null $unit_id
 * @property CefrLevel $cefr_level
 * @property string $title
 * @property string $body
 * @property array<int, array{prompt: string, options: array<int, string>, correct_answer: string}> $questions
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['language_id', 'unit_id', 'cefr_level', 'title', 'body', 'questions'])]
class ReadingPassage extends Model
{
    /** @use HasFactory<ReadingPassageFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cefr_level' => CefrLevel::class,
            'questions' => 'array',
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

    /** @return HasMany<ReadingAttempt, $this> */
    public function attempts(): HasMany
    {
        return $this->hasMany(ReadingAttempt::class);
    }
}
