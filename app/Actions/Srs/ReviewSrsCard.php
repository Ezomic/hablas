<?php

namespace App\Actions\Srs;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\ErrorTagCategory;
use App\Enums\SrsRating;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Services\FsrsScheduler;
use RuntimeException;

class ReviewSrsCard
{
    /**
     * An item failed 3+ times in a row escalates out of normal rotation into
     * a weak-spot remedial drill before it's allowed back into the queue,
     * rather than endlessly recycling through shortening intervals.
     */
    private const WEAK_SPOT_THRESHOLD = 3;

    public function __construct(
        private readonly FsrsScheduler $fsrsScheduler = new FsrsScheduler,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    public function handle(SrsCard $card, SrsRating $rating, ?ErrorTagCategory $errorTagCategory = null): SrsCard
    {
        $this->fsrsScheduler->review($card, $rating);

        $consecutiveLapses = $rating === SrsRating::Again ? $card->consecutive_lapses + 1 : 0;

        $card->forceFill([
            'consecutive_lapses' => $consecutiveLapses,
            'is_weak_spot' => $card->is_weak_spot || $consecutiveLapses >= self::WEAK_SPOT_THRESHOLD,
        ])->save();

        SrsReview::create([
            'srs_card_id' => $card->id,
            'user_id' => $card->user_id,
            'rating' => $rating,
            'error_tag_category' => $errorTagCategory,
            'reviewed_at' => now(),
        ]);

        $user = $card->user;

        if ($user === null) {
            throw new RuntimeException("Srs card {$card->id} has no user.");
        }

        $this->recordStreakActivity->handle($user);

        return $card;
    }
}
