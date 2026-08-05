<?php

namespace App\Actions\Srs;

use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetDueSrsCards
{
    /**
     * Cards due for the normal review queue, scoped to a single language deck
     * (Spanish and Portuguese must never interleave in one session) and
     * excluding weak-spot cards, which are gated out until their remedial
     * drill is completed.
     *
     * @return Collection<int, SrsCard>
     */
    public function handle(User $user, Language $language): Collection
    {
        return $this->dueQuery($user, $language)
            ->orderBy('due_at')
            ->get();
    }

    /**
     * Cards the user has seen before and owes a repetition on, oldest first.
     *
     * @return Collection<int, SrsCard>
     */
    public function repetitions(User $user, Language $language, int $limit): Collection
    {
        return $this->dueQuery($user, $language)
            ->where('state', '!=', SrsCardState::New)
            ->orderBy('due_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Cards enrolled but never reviewed, oldest enrollment first so a unit's
     * material is introduced in the order it was learned.
     *
     * @return Collection<int, SrsCard>
     */
    public function introductions(User $user, Language $language, int $limit): Collection
    {
        return $this->dueQuery($user, $language)
            ->where('state', SrsCardState::New)
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    /**
     * A lean count of the same due-card definition as handle(), without
     * hydrating full models — for callers (dashboard badges, pacing
     * decisions) that only need the number.
     */
    public function count(User $user, Language $language): int
    {
        return $this->dueQuery($user, $language)->count();
    }

    /** @return Builder<SrsCard> */
    private function dueQuery(User $user, Language $language): Builder
    {
        return SrsCard::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->where('is_weak_spot', false)
            ->where('due_at', '<=', now());
    }
}
