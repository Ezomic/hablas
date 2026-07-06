<?php

namespace App\Models;

use App\Enums\ErrorTagCategory;
use App\Enums\SrsRating;
use Carbon\CarbonImmutable;
use Database\Factories\SrsReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $srs_card_id
 * @property int $user_id
 * @property SrsRating $rating
 * @property ErrorTagCategory|null $error_tag_category
 * @property CarbonImmutable $reviewed_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['srs_card_id', 'user_id', 'rating', 'error_tag_category', 'reviewed_at'])]
class SrsReview extends Model
{
    /** @use HasFactory<SrsReviewFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'rating' => SrsRating::class,
            'error_tag_category' => ErrorTagCategory::class,
            'reviewed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<SrsCard, $this> */
    public function srsCard(): BelongsTo
    {
        return $this->belongsTo(SrsCard::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
