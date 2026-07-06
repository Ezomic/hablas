<?php

use App\Actions\Srs\EvaluateSessionHealth;
use App\Enums\SrsRating;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\User;

it('returns false when the user has no review history', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    expect((new EvaluateSessionHealth)->handle($user, $language))->toBeFalse();
});

it('returns true when at least half of the recent reviews are Again', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    collect([
        SrsRating::Again, SrsRating::Again, SrsRating::Again,
        SrsRating::Again, SrsRating::Again, SrsRating::Good,
        SrsRating::Good, SrsRating::Good, SrsRating::Good, SrsRating::Good,
    ])->each(fn (SrsRating $rating) => SrsReview::factory()->create([
        'user_id' => $user->id,
        'srs_card_id' => $card->id,
        'rating' => $rating,
    ]));

    expect((new EvaluateSessionHealth)->handle($user, $language))->toBeTrue();
});

it('returns false when most recent reviews are Good', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    collect([
        SrsRating::Good, SrsRating::Good, SrsRating::Good, SrsRating::Good,
        SrsRating::Good, SrsRating::Good, SrsRating::Good, SrsRating::Good,
        SrsRating::Good, SrsRating::Again,
    ])->each(fn (SrsRating $rating) => SrsReview::factory()->create([
        'user_id' => $user->id,
        'srs_card_id' => $card->id,
        'rating' => $rating,
    ]));

    expect((new EvaluateSessionHealth)->handle($user, $language))->toBeFalse();
});

it('only weighs the most recent session window, not the full history', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    collect(range(1, 20))->each(fn (int $i) => SrsReview::factory()->create([
        'user_id' => $user->id,
        'srs_card_id' => $card->id,
        'rating' => SrsRating::Again,
        'reviewed_at' => now()->subDays(100)->addMinutes($i),
    ]));

    collect(range(1, 10))->each(fn (int $i) => SrsReview::factory()->create([
        'user_id' => $user->id,
        'srs_card_id' => $card->id,
        'rating' => SrsRating::Good,
        'reviewed_at' => now()->addMinutes($i),
    ]));

    expect((new EvaluateSessionHealth)->handle($user, $language))->toBeFalse();
});

it('never mixes review history from a different language deck', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $otherLanguage = Language::factory()->create();
    $otherCard = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $otherLanguage->id]);

    collect(range(1, 10))->each(fn () => SrsReview::factory()->create([
        'user_id' => $user->id,
        'srs_card_id' => $otherCard->id,
        'rating' => SrsRating::Again,
    ]));

    expect((new EvaluateSessionHealth)->handle($user, $language))->toBeFalse();
});
