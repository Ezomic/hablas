<?php

use App\Enums\SrsRating;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('renders weak-spot cards for the active language', function () {
    $user = User::factory()->create();
    $vocabularyItem = VocabularyItem::factory()->create(['language_id' => $this->spanish->id]);
    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $vocabularyItem->id,
        'is_weak_spot' => true,
    ]);

    $this->actingAs($user)
        ->get(route('review.weak-spots.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('review/WeakSpots')
            ->has('cards', 1),
        );
});

it('does not surface cards that are not weak spots', function () {
    $user = User::factory()->create();
    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'is_weak_spot' => false,
        'due_at' => now()->subMinute(),
    ]);

    $this->actingAs($user)
        ->get(route('review.weak-spots.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('review/WeakSpots')
            ->where('cards', []),
        );
});

it('resolves the weak spot and re-admits the card on a successful review', function () {
    $user = User::factory()->create();
    $card = SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'is_weak_spot' => true,
        'consecutive_lapses' => 3,
        'due_at' => now()->addWeek(),
        'last_reviewed_at' => now()->subDay(),
    ]);

    $this->actingAs($user)
        ->postJson(route('review.weak-spots.reviews.store', $card), ['rating' => SrsRating::Good->value])
        ->assertOk();

    $card->refresh();

    expect($card->is_weak_spot)->toBeFalse()
        ->and($card->consecutive_lapses)->toBe(0)
        ->and($card->due_at->timestamp)->toBeLessThanOrEqual(now()->timestamp);
});

it('keeps the card benched when the review is Again', function () {
    $user = User::factory()->create();
    $card = SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'is_weak_spot' => true,
        'consecutive_lapses' => 3,
        'last_reviewed_at' => now()->subDay(),
    ]);

    $this->actingAs($user)
        ->postJson(route('review.weak-spots.reviews.store', $card), ['rating' => SrsRating::Again->value])
        ->assertOk();

    expect($card->refresh()->is_weak_spot)->toBeTrue();
});

it('hides another user\'s card behind a 404 rather than revealing it exists', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $card = SrsCard::factory()->create([
        'user_id' => $owner->id,
        'language_id' => $this->spanish->id,
        'is_weak_spot' => true,
        'last_reviewed_at' => now()->subDay(),
    ]);

    $this->actingAs($otherUser)
        ->postJson(route('review.weak-spots.reviews.store', $card), ['rating' => SrsRating::Good->value])
        ->assertNotFound();
});

it('rejects an invalid rating', function () {
    $user = User::factory()->create();
    $card = SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'is_weak_spot' => true,
    ]);

    $this->actingAs($user)
        ->postJson(route('review.weak-spots.reviews.store', $card), ['rating' => 'not-a-rating'])
        ->assertUnprocessable();
});
