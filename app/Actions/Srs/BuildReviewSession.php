<?php

namespace App\Actions\Srs;

use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Services\AdaptiveNewItemCap;
use Illuminate\Support\Collection;

class BuildReviewSession
{
    public function __construct(
        private readonly AdaptiveNewItemCap $adaptiveNewItemCap = new AdaptiveNewItemCap,
        private readonly GetDueSrsCards $getDueSrsCards = new GetDueSrsCards,
    ) {}

    /**
     * How many cards one sitting serves. A backlog of several hundred is a
     * realistic state for this app, and handing the lot over in one payload
     * gives the user a queue with no end in sight — the rest simply waits for
     * the next session.
     */
    public const SESSION_SIZE = 30;

    /**
     * @return array{cards: Collection<int, SrsCard>, dueRemaining: int}
     */
    public function handle(User $user, Language $language): array
    {
        $repetitions = $this->getDueSrsCards->repetitions($user, $language, self::SESSION_SIZE)->load('cardable');

        $newAllowance = max(0, min(
            $this->adaptiveNewItemCap->forUser($user, $language),
            self::SESSION_SIZE - $repetitions->count(),
        ));

        $introductions = $this->getDueSrsCards->introductions($user, $language, $newAllowance)->load('cardable');

        return [
            'cards' => $this->interleave($repetitions->values(), $introductions->values()),
            'dueRemaining' => max(0, $this->getDueSrsCards->count($user, $language) - $repetitions->count() - $introductions->count()),
        ];
    }

    /**
     * Spreads the new cards evenly through the repetitions rather than
     * stacking them at either end, so a session never opens with a wall of
     * unfamiliar material or saves it all for when the user is tired.
     *
     * @param  Collection<int, SrsCard>  $repetitions
     * @param  Collection<int, SrsCard>  $introductions
     * @return Collection<int, SrsCard>
     */
    private function interleave(Collection $repetitions, Collection $introductions): Collection
    {
        if ($introductions->isEmpty()) {
            return $repetitions;
        }

        if ($repetitions->isEmpty()) {
            return $introductions;
        }

        $pending = $introductions->all();
        $spacing = $repetitions->count() / count($pending);
        $session = [];
        $placed = 0;

        foreach ($repetitions as $index => $repetition) {
            $session[] = $repetition;

            while ($placed < count($pending) && ($placed + 1) * $spacing <= $index + 1) {
                $session[] = $pending[$placed];
                $placed++;
            }
        }

        for (; $placed < count($pending); $placed++) {
            $session[] = $pending[$placed];
        }

        return collect($session);
    }
}
