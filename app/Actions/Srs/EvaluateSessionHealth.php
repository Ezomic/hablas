<?php

namespace App\Actions\Srs;

use App\Enums\SrsRating;
use App\Models\Language;
use App\Models\SrsReview;
use App\Models\User;

class EvaluateSessionHealth
{
    /**
     * How many of the user's most recent reviews to look at — a rolling
     * session window, not the full review history.
     */
    private const SESSION_WINDOW = 10;

    /**
     * "Again" rate at or above this fraction of the window means the user is
     * struggling and should reinforce before moving on to new material.
     */
    private const AGAIN_RATE_THRESHOLD = 0.5;

    public function handle(User $user, Language $language): bool
    {
        $recentRatings = SrsReview::query()
            ->where('user_id', $user->id)
            ->whereHas('srsCard', fn ($query) => $query->where('language_id', $language->id))
            ->latest('reviewed_at')
            ->limit(self::SESSION_WINDOW)
            ->pluck('rating');

        if ($recentRatings->isEmpty()) {
            return false;
        }

        $againCount = $recentRatings->filter(fn (SrsRating $rating): bool => $rating === SrsRating::Again)->count();

        return ($againCount / $recentRatings->count()) >= self::AGAIN_RATE_THRESHOLD;
    }
}
