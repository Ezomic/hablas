<?php

namespace App\Actions\Srs;

use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetWeakSpotCards
{
    /**
     * Weak-spot cards for a single language deck — cards benched from the
     * normal review queue after repeated lapses, surfaced here for a remedial
     * drill that re-admits them via ResolveWeakSpot on a successful review.
     * Unlike the due queue these are not filtered by due_at: a benched card
     * should be clearable at any time. Scoped to one language, since Spanish
     * and Portuguese must never interleave in a session.
     *
     * @return Collection<int, SrsCard>
     */
    public function handle(User $user, Language $language): Collection
    {
        return $this->weakSpotQuery($user, $language)
            ->orderBy('due_at')
            ->get();
    }

    /**
     * A lean count of the same weak-spot definition as handle(), for callers
     * (dashboard badge) that only need the number.
     */
    public function count(User $user, Language $language): int
    {
        return $this->weakSpotQuery($user, $language)->count();
    }

    /** @return Builder<SrsCard> */
    private function weakSpotQuery(User $user, Language $language): Builder
    {
        return SrsCard::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->where('is_weak_spot', true);
    }
}
