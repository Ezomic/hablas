<?php

use App\Actions\Srs\ReviewSrsCard;
use App\Enums\ErrorTagCategory;
use App\Enums\SrsCardState;
use App\Enums\SrsRating;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Services\FsrsScheduler;

it('resets consecutive lapses on a non-Again rating', function () {
    $card = SrsCard::factory()->create([
        'state' => SrsCardState::Review,
        'stability' => 10,
        'difficulty' => 5,
        'consecutive_lapses' => 2,
        'last_reviewed_at' => now()->subDays(1),
    ]);

    (new ReviewSrsCard(new FsrsScheduler))->handle($card, SrsRating::Good);

    expect($card->consecutive_lapses)->toBe(0)
        ->and($card->is_weak_spot)->toBeFalse();
});

it('escalates a card to a weak spot after three consecutive Again ratings', function () {
    $card = SrsCard::factory()->create([
        'state' => SrsCardState::Review,
        'stability' => 5,
        'last_reviewed_at' => now()->subDays(1),
    ]);
    $action = new ReviewSrsCard(new FsrsScheduler);

    $action->handle($card, SrsRating::Again);
    expect($card->consecutive_lapses)->toBe(1)->and($card->is_weak_spot)->toBeFalse();

    $action->handle($card, SrsRating::Again);
    expect($card->consecutive_lapses)->toBe(2)->and($card->is_weak_spot)->toBeFalse();

    $action->handle($card, SrsRating::Again);
    expect($card->consecutive_lapses)->toBe(3)->and($card->is_weak_spot)->toBeTrue();
});

it('records a review log with the given rating and error tag', function () {
    $card = SrsCard::factory()->create(['last_reviewed_at' => now()->subDays(1)]);

    (new ReviewSrsCard(new FsrsScheduler))->handle($card, SrsRating::Again, ErrorTagCategory::SerEstarConfusion);

    $review = SrsReview::query()->where('srs_card_id', $card->id)->sole();

    expect($review->rating)->toBe(SrsRating::Again)
        ->and($review->error_tag_category)->toBe(ErrorTagCategory::SerEstarConfusion)
        ->and($review->user_id)->toBe($card->user_id);
});

it('leaves the error tag null for plain vocabulary misses', function () {
    $card = SrsCard::factory()->create(['last_reviewed_at' => now()->subDays(1)]);

    (new ReviewSrsCard(new FsrsScheduler))->handle($card, SrsRating::Again);

    $review = SrsReview::query()->where('srs_card_id', $card->id)->sole();

    expect($review->error_tag_category)->toBeNull();
});
