<?php

use App\Enums\SrsRating;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\User;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('renders due cards for the active language', function () {
    $user = User::factory()->create();
    $vocabularyItem = VocabularyItem::factory()->create(['language_id' => $this->spanish->id]);
    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $vocabularyItem->id,
        'due_at' => now()->subMinute(),
    ]);

    $this->actingAs($user)
        ->get(route('review.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('review/Index')
            ->has('cards', 1),
        );
});

it('renders an empty queue when nothing is due', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('review.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('review/Index')
            ->where('cards', []),
        );
});

it('records a rating and updates the card', function () {
    $user = User::factory()->create();
    $card = SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'last_reviewed_at' => now()->subDay(),
    ]);

    $this->actingAs($user)
        ->postJson(route('review.reviews.store', $card), ['rating' => SrsRating::Good->value])
        ->assertOk();

    expect(SrsReview::query()->where('srs_card_id', $card->id)->where('rating', SrsRating::Good)->exists())->toBeTrue();
});

it('rejects rating another user\'s card', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $card = SrsCard::factory()->create([
        'user_id' => $owner->id,
        'language_id' => $this->spanish->id,
        'last_reviewed_at' => now()->subDay(),
    ]);

    $this->actingAs($otherUser)
        ->postJson(route('review.reviews.store', $card), ['rating' => SrsRating::Good->value])
        ->assertForbidden();
});

it('rejects an invalid rating', function () {
    $user = User::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $this->spanish->id]);

    $this->actingAs($user)
        ->postJson(route('review.reviews.store', $card), ['rating' => 'not-a-rating'])
        ->assertUnprocessable();
});
