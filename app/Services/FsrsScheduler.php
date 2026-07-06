<?php

namespace App\Services;

use App\Enums\SrsCardState;
use App\Enums\SrsRating;
use App\Models\SrsCard;
use Carbon\CarbonImmutable;
use DateTime;
use DateTimeZone;
use LogicException;
use Scottlaurent\FSRS\Card as VendorCard;
use Scottlaurent\FSRS\Manager;
use Scottlaurent\FSRS\Rating as VendorRating;
use Scottlaurent\FSRS\State as VendorState;

/**
 * Thin wrapper around scottlaurent/fsrs (an FSRS-5 implementation) that
 * translates to/from our own SrsCard model, so the rest of the app never
 * touches the vendor package's types directly.
 */
class FsrsScheduler
{
    private Manager $manager;

    public function __construct()
    {
        $this->manager = new Manager;
    }

    /**
     * Compute the next scheduling state for a card given a rating. Mutates
     * and returns the given card in memory — the caller is responsible for
     * persisting it (and for any weak-spot-escalation gating).
     */
    public function review(SrsCard $card, SrsRating $rating): SrsCard
    {
        $vendorCard = $this->toVendorCard($card);
        $reviewedAt = new DateTime('now', new DateTimeZone('UTC'));

        $result = $this->manager->reviewCard($vendorCard, $this->toVendorRating($rating), $reviewedAt);
        $updated = $result['card'];

        $card->stability = $updated->stability;
        $card->difficulty = $updated->difficulty;
        $card->state = $this->fromVendorState($updated->state);
        $card->reps = $updated->reps;
        $card->lapses = $updated->lapses;
        $card->due_at = CarbonImmutable::instance($updated->due);
        $card->last_reviewed_at = CarbonImmutable::instance($reviewedAt);

        return $card;
    }

    private function toVendorCard(SrsCard $card): VendorCard
    {
        return new VendorCard(
            due: $card->due_at->toDateTime(),
            stability: $card->stability,
            difficulty: $card->difficulty,
            elapsedDays: 0,
            scheduledDays: 0,
            reps: $card->reps,
            lapses: $card->lapses,
            state: $this->toVendorState($card->state),
            step: 0,
            lastReview: $card->last_reviewed_at?->toDateTime(),
        );
    }

    private function toVendorRating(SrsRating $rating): int
    {
        return match ($rating) {
            SrsRating::Again => VendorRating::AGAIN,
            SrsRating::Hard => VendorRating::HARD,
            SrsRating::Good => VendorRating::GOOD,
            SrsRating::Easy => VendorRating::EASY,
        };
    }

    private function toVendorState(SrsCardState $state): int
    {
        return match ($state) {
            SrsCardState::New => VendorState::NEW,
            SrsCardState::Learning => VendorState::LEARNING,
            SrsCardState::Review => VendorState::REVIEW,
            SrsCardState::Relearning => VendorState::RELEARNING,
        };
    }

    private function fromVendorState(int $state): SrsCardState
    {
        return match ($state) {
            VendorState::NEW => SrsCardState::New,
            VendorState::LEARNING => SrsCardState::Learning,
            VendorState::REVIEW => SrsCardState::Review,
            VendorState::RELEARNING => SrsCardState::Relearning,
            default => throw new LogicException("Unknown FSRS vendor state: {$state}"),
        };
    }
}
