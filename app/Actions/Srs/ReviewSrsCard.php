<?php

namespace App\Actions\Srs;

use App\Enums\ErrorTagCategory;
use App\Enums\SrsRating;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Services\FsrsScheduler;

class ReviewSrsCard
{
    /**
     * An item failed 3+ times in a row escalates out of normal rotation into
     * a weak-spot remedial drill before it's allowed back into the queue,
     * rather than endlessly recycling through shortening intervals.
     */
    private const WEAK_SPOT_THRESHOLD = 3;

    public function __construct(private readonly FsrsScheduler $scheduler) {}

    public function handle(SrsCard $card, SrsRating $rating, ?ErrorTagCategory $errorTagCategory = null): SrsCard
    {
        $this->scheduler->review($card, $rating);

        $card->consecutive_lapses = $rating === SrsRating::Again ? $card->consecutive_lapses + 1 : 0;

        if ($card->consecutive_lapses >= self::WEAK_SPOT_THRESHOLD) {
            $card->is_weak_spot = true;
        }

        $card->save();

        SrsReview::create([
            'srs_card_id' => $card->id,
            'user_id' => $card->user_id,
            'rating' => $rating,
            'error_tag_category' => $errorTagCategory,
            'reviewed_at' => now(),
        ]);

        return $card;
    }
}
