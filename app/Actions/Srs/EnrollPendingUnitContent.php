<?php

namespace App\Actions\Srs;

use App\Enums\UnitProgressStatus;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use App\Models\VocabularyItem;
use App\Services\AdaptiveNewItemCap;
use Illuminate\Support\Collection;

class EnrollPendingUnitContent
{
    public function __construct(
        private readonly AdaptiveNewItemCap $adaptiveNewItemCap = new AdaptiveNewItemCap,
        private readonly EnrollInSrs $enrollInSrs = new EnrollInSrs,
    ) {}

    /**
     * Enrols whatever the daily new-item cap has not let through yet from the
     * units this user has already completed, oldest completion first.
     *
     * The cap paces new material, it does not discard it: a unit finished on a
     * heavy-backlog day would otherwise leave most of its vocabulary stranded
     * until the user happened to walk back into it and complete it again.
     *
     * @return array{enrolled: int, deferred: int}
     */
    public function handle(User $user, Language $language): array
    {
        $pending = $this->pendingContent($user, $language);
        $allowance = $this->remainingAllowance($user, $language);

        foreach ($pending->take($allowance) as $cardable) {
            $this->enrollInSrs->handle($user, $language, $cardable);
        }

        $enrolled = min($allowance, $pending->count());

        return ['enrolled' => $enrolled, 'deferred' => $pending->count() - $enrolled];
    }

    /**
     * @return Collection<int, GrammarPoint|VocabularyItem>
     */
    private function pendingContent(User $user, Language $language): Collection
    {
        $completedUnitIds = UserUnitProgress::query()
            ->where('user_id', $user->id)
            ->where('status', UnitProgressStatus::Completed)
            ->orderBy('completed_at')
            ->orderBy('id')
            ->get(['id', 'unit_id'])
            ->map(fn (UserUnitProgress $progress): int => $progress->unit_id)
            ->all();

        $units = $completedUnitIds === []
            ? collect()
            : Unit::query()
                ->where('language_id', $language->id)
                ->whereIn('id', $completedUnitIds)
                ->with(['vocabularyItems', 'grammarPoints'])
                ->get()
                ->keyBy('id');

        $alreadyEnrolled = $this->enrolledCardableIds($user);
        $pending = [];

        foreach ($completedUnitIds as $unitId) {
            $unit = $units->get($unitId);

            if (! $unit instanceof Unit) {
                continue;
            }

            foreach ([...$unit->vocabularyItems->all(), ...$unit->grammarPoints->all()] as $cardable) {
                $enrolled = $alreadyEnrolled[$cardable->getMorphClass()] ?? [];

                if (! in_array($cardable->getKey(), $enrolled, true)) {
                    $pending[] = $cardable;
                }
            }
        }

        return collect($pending);
    }

    /**
     * Every cardable the user already has a card for, keyed by morph type, so
     * a sweep across many units costs one query rather than one per item.
     *
     * @return array<string, array<int, int>>
     */
    private function enrolledCardableIds(User $user): array
    {
        $enrolled = [];

        $cards = SrsCard::query()
            ->where('user_id', $user->id)
            ->whereIn('cardable_type', [
                (new VocabularyItem)->getMorphClass(),
                (new GrammarPoint)->getMorphClass(),
            ])
            ->get(['cardable_type', 'cardable_id']);

        foreach ($cards as $card) {
            $enrolled[$card->cardable_type][] = $card->cardable_id;
        }

        return $enrolled;
    }

    /**
     * The cap counts everything already enrolled today, so several units in a
     * row, or a top-up on top of a completion, can't sidestep it.
     */
    private function remainingAllowance(User $user, Language $language): int
    {
        $enrolledToday = SrsCard::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->whereDate('created_at', today())
            ->count();

        return max(0, $this->adaptiveNewItemCap->forUser($user, $language) - $enrolledToday);
    }
}
