<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\Srs\BuildReviewSession;
use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

/**
 * Cards are inserted directly rather than through the factory: these counts run
 * past the point where the vocabulary factory's unique() word pool holds up,
 * and the session builder only reads state, due_at and ordering.
 */
function seedCards(User $user, Language $language, SrsCardState $state, int $count, int $idOffset): void
{
    SrsCard::query()->insert(collect(range(1, $count))->map(fn (int $i): array => [
        'user_id' => $user->id,
        'language_id' => $language->id,
        'cardable_type' => (new VocabularyItem)->getMorphClass(),
        'cardable_id' => $idOffset + $i,
        'state' => $state->value,
        'stability' => 0,
        'difficulty' => 0,
        'reps' => 0,
        'lapses' => 0,
        'consecutive_lapses' => 0,
        'is_weak_spot' => false,
        'due_at' => now()->subDay(),
        'created_at' => now()->subMonth(),
        'updated_at' => now()->subMonth(),
    ])->all());
}

it('caps a session at the session size', function () {
    seedCards($this->user, $this->spanish, SrsCardState::Review, 80, 10_000);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);

    expect($session['cards'])->toHaveCount(BuildReviewSession::SESSION_SIZE)
        ->and($session['dueRemaining'])->toBe(50);
});

it('reports nothing remaining when the whole backlog fits', function () {
    seedCards($this->user, $this->spanish, SrsCardState::Review, 5, 10_000);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);

    expect($session['cards'])->toHaveCount(5)
        ->and($session['dueRemaining'])->toBe(0);
});

it('mixes new cards in alongside repetitions', function () {
    seedCards($this->user, $this->spanish, SrsCardState::Review, 10, 10_000);
    seedCards($this->user, $this->spanish, SrsCardState::New, 10, 20_000);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);
    $states = $session['cards']->map(fn (SrsCard $card): string => $card->state->value);

    expect($states->filter(fn (string $state): bool => $state === 'review'))->toHaveCount(10)
        ->and($states->filter(fn (string $state): bool => $state === 'new'))->toHaveCount(10);
});

it('limits new cards to the adaptive cap', function () {
    seedCards($this->user, $this->spanish, SrsCardState::New, 25, 20_000);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);

    expect($session['cards'])->toHaveCount(10);
});

it('honours an explicit new item cap override', function () {
    UserSetting::factory()->create(['user_id' => $this->user->id, 'new_item_cap_override' => 2]);
    seedCards($this->user, $this->spanish, SrsCardState::Review, 5, 10_000);
    seedCards($this->user, $this->spanish, SrsCardState::New, 20, 20_000);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);
    $newCards = $session['cards']->filter(fn (SrsCard $card): bool => $card->state === SrsCardState::New);

    expect($session['cards'])->toHaveCount(7)
        ->and($newCards)->toHaveCount(2);
});

it('leaves no room for new cards when repetitions fill the session', function () {
    seedCards($this->user, $this->spanish, SrsCardState::Review, 40, 10_000);
    seedCards($this->user, $this->spanish, SrsCardState::New, 10, 20_000);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);
    $newCards = $session['cards']->filter(fn (SrsCard $card): bool => $card->state === SrsCardState::New);

    expect($session['cards'])->toHaveCount(BuildReviewSession::SESSION_SIZE)
        ->and($newCards)->toHaveCount(0);
});

it('does not open the session with a wall of new cards', function () {
    seedCards($this->user, $this->spanish, SrsCardState::Review, 12, 10_000);
    seedCards($this->user, $this->spanish, SrsCardState::New, 4, 20_000);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);
    $first = $session['cards']->take(4)->filter(fn (SrsCard $card): bool => $card->state === SrsCardState::New);

    expect($session['cards'])->toHaveCount(16)
        ->and($first)->toHaveCount(1);
});

it('excludes weak spots and cards from the other deck', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();
    seedCards($this->user, $this->spanish, SrsCardState::Review, 3, 10_000);
    seedCards($this->user, $portuguese, SrsCardState::Review, 3, 30_000);
    SrsCard::query()->where('language_id', $this->spanish->id)->limit(1)->update(['is_weak_spot' => true]);

    $session = (new BuildReviewSession)->handle($this->user, $this->spanish);

    expect($session['cards'])->toHaveCount(2);
});

it('serves the capped session and remaining count to the review page', function () {
    seedCards($this->user, $this->spanish, SrsCardState::Review, 45, 10_000);
    VocabularyItem::factory()->count(45)->sequence(fn ($sequence): array => ['id' => 10_001 + $sequence->index])
        ->create(['language_id' => $this->spanish->id]);

    $this->actingAs($this->user)
        ->get(route('review.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('review/Index')
            ->has('cards', BuildReviewSession::SESSION_SIZE)
            ->where('dueRemaining', 15),
        );
});
