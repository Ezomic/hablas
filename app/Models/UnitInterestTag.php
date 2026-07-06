<?php

namespace App\Models;

use App\Enums\InterestTag;
use Database\Factories\UnitInterestTagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $unit_id
 * @property InterestTag $interest_tag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['unit_id', 'interest_tag'])]
class UnitInterestTag extends Model
{
    /** @use HasFactory<UnitInterestTagFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'interest_tag' => InterestTag::class,
        ];
    }

    /** @return BelongsTo<Unit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
