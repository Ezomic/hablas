<?php

namespace App\Models;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use Carbon\CarbonImmutable;
use Database\Factories\PlacementTestResponseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $attempt_id
 * @property int $item_id
 * @property Skill $skill
 * @property string $response
 * @property bool $is_correct
 * @property CefrSubLevel $tier_at_time
 * @property CarbonImmutable $answered_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['attempt_id', 'item_id', 'skill', 'response', 'is_correct', 'tier_at_time', 'answered_at'])]
class PlacementTestResponse extends Model
{
    /** @use HasFactory<PlacementTestResponseFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'skill' => Skill::class,
            'is_correct' => 'boolean',
            'tier_at_time' => CefrSubLevel::class,
            'answered_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PlacementTestAttempt, $this> */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(PlacementTestAttempt::class, 'attempt_id');
    }

    /** @return BelongsTo<PlacementTestItem, $this> */
    public function item(): BelongsTo
    {
        return $this->belongsTo(PlacementTestItem::class, 'item_id');
    }
}
