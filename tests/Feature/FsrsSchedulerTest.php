<?php

use App\Enums\SrsCardState;
use App\Enums\SrsRating;
use App\Models\SrsCard;
use App\Services\FsrsScheduler;

it('moves a new card into learning and schedules it in the future on a Good rating', function () {
    $card = SrsCard::factory()->create([
        'state' => SrsCardState::New,
        'due_at' => now(),
    ]);

    $updated = (new FsrsScheduler)->review($card, SrsRating::Good);

    expect($updated->state)->not->toBe(SrsCardState::New)
        ->and($updated->due_at->isFuture())->toBeTrue()
        ->and($updated->reps)->toBe(1);
});

it('graduates a card to review and schedules a longer interval on an Easy rating', function () {
    $card = SrsCard::factory()->create([
        'state' => SrsCardState::New,
        'due_at' => now(),
    ]);

    $updated = (new FsrsScheduler)->review($card, SrsRating::Easy);

    expect($updated->state)->toBe(SrsCardState::Review)
        ->and($updated->due_at->diffInMinutes(now(), true))->toBeGreaterThan(60);
});

it('schedules a Good rating further out than a Hard rating from the same starting state', function () {
    $state = [
        'state' => SrsCardState::Review,
        'stability' => 10,
        'difficulty' => 5,
        'reps' => 3,
        'lapses' => 0,
        'last_reviewed_at' => now()->subDays(5),
        'due_at' => now(),
    ];
    $goodCard = SrsCard::factory()->create($state);
    $hardCard = SrsCard::factory()->create($state);
    $scheduler = new FsrsScheduler;

    $scheduler->review($goodCard, SrsRating::Good);
    $scheduler->review($hardCard, SrsRating::Hard);

    expect($goodCard->due_at->greaterThan($hardCard->due_at))->toBeTrue();
});

it('sends a card in review back to relearning on an Again rating', function () {
    $card = SrsCard::factory()->create([
        'state' => SrsCardState::Review,
        'stability' => 10,
        'difficulty' => 5,
        'reps' => 3,
        'lapses' => 0,
        'last_reviewed_at' => now()->subDays(5),
        'due_at' => now(),
    ]);

    $updated = (new FsrsScheduler)->review($card, SrsRating::Again);

    expect($updated->state)->toBe(SrsCardState::Relearning)
        ->and($updated->lapses)->toBe(1);
});
