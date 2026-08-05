<?php

namespace App\Actions;

use App\Actions\Srs\EnrollInSrs;
use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\UnitProgressStatus;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use App\Models\VocabularyItem;
use App\Services\AdaptiveNewItemCap;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class CompleteUnit
{
    public function __construct(
        private readonly AdaptiveNewItemCap $adaptiveNewItemCap = new AdaptiveNewItemCap,
        private readonly EnrollInSrs $enrollInSrs = new EnrollInSrs,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    /**
     * @return array{progress: UserUnitProgress, enrolled: int, deferred: int}
     */
    public function handle(User $user, Unit $unit): array
    {
        $progress = UserUnitProgress::query()->updateOrCreate(
            ['user_id' => $user->id, 'unit_id' => $unit->id],
            ['status' => UnitProgressStatus::Completed, 'completed_at' => now()],
        );

        $enrollment = $this->enrollUnitContent($user, $unit);

        $this->recordStreakActivity->handle($user);

        return ['progress' => $progress, ...$enrollment];
    }

    /**
     * Completing a unit is what puts its material into the review deck, up to
     * the user's daily new-item cap: a big backlog throttles how much new
     * material lands at once, and anything over the cap is left for a later
     * visit rather than dumped on top of reviews they haven't cleared.
     *
     * Cards are enrolled against the unit's own language rather than the
     * user's current one, and EnrollInSrs is a firstOrCreate, so completing a
     * unit again never duplicates a card or resets its scheduling — it just
     * picks up whatever the cap held back.
     *
     * @return array{enrolled: int, deferred: int}
     */
    private function enrollUnitContent(User $user, Unit $unit): array
    {
        $language = $unit->language;

        if ($language === null) {
            throw new RuntimeException("Unit {$unit->id} has no language.");
        }

        $pending = $this->unenrolledContent($user, $unit);
        $allowance = $this->remainingAllowance($user, $language);

        foreach ($pending->take($allowance) as $cardable) {
            $this->enrollInSrs->handle($user, $language, $cardable);
        }

        $enrolled = min($allowance, $pending->count());

        return ['enrolled' => $enrolled, 'deferred' => $pending->count() - $enrolled];
    }

    /**
     * @return EloquentCollection<int, GrammarPoint|VocabularyItem>
     */
    private function unenrolledContent(User $user, Unit $unit): EloquentCollection
    {
        $notYetEnrolled = fn (Model $cardable): bool => ! SrsCard::query()
            ->where('user_id', $user->id)
            ->where('cardable_type', $cardable->getMorphClass())
            ->where('cardable_id', $cardable->getKey())
            ->exists();

        return $unit->vocabularyItems()->get()
            ->concat($unit->grammarPoints()->get())
            ->filter($notYetEnrolled)
            ->values();
    }

    /**
     * The cap counts everything already enrolled today, so completing three
     * units in a row can't sidestep it one unit at a time.
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
