<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\ErrorTagCategory;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\User;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);

    $grammarPoint = GrammarPoint::factory()->create([
        'language_id' => $this->spanish->id,
        'error_tag_category' => ErrorTagCategory::SerEstarConfusion,
    ]);

    $this->card = SrsCard::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->spanish->id,
        'cardable_type' => GrammarPoint::class,
        'cardable_id' => $grammarPoint->id,
        'due_at' => now()->subDay(),
        'is_weak_spot' => false,
    ]);
});

it('records the error tag category submitted with a miss', function () {
    $this->actingAs($this->user)
        ->postJson(route('review.reviews.store', $this->card), [
            'rating' => 'again',
            'error_tag_category' => 'ser_estar_confusion',
        ])
        ->assertOk();

    expect(SrsReview::query()->sole()->error_tag_category)->toBe(ErrorTagCategory::SerEstarConfusion);
});

it('records a miss with no category when none is offered', function () {
    $this->actingAs($this->user)
        ->postJson(route('review.reviews.store', $this->card), ['rating' => 'again'])
        ->assertOk();

    expect(SrsReview::query()->sole()->error_tag_category)->toBeNull();
});

it('ignores a category sent alongside a successful rating', function () {
    $this->actingAs($this->user)
        ->postJson(route('review.reviews.store', $this->card), [
            'rating' => 'good',
            'error_tag_category' => 'wrong_gender',
        ])
        ->assertOk();

    expect(SrsReview::query()->sole()->error_tag_category)->toBeNull();
});

it('rejects a category outside the enum', function () {
    $this->actingAs($this->user)
        ->postJson(route('review.reviews.store', $this->card), [
            'rating' => 'again',
            'error_tag_category' => 'made_up',
        ])
        ->assertJsonValidationErrorFor('error_tag_category');
});

it('records the error tag category from a weak-spot drill', function () {
    $this->card->forceFill(['is_weak_spot' => true])->save();

    $this->actingAs($this->user)
        ->postJson(route('review.weak-spots.reviews.store', $this->card), [
            'rating' => 'again',
            'error_tag_category' => 'wrong_tense',
        ])
        ->assertOk();

    expect(SrsReview::query()->sole()->error_tag_category)->toBe(ErrorTagCategory::WrongTense);
});

it('serves the card kind and suggested tag to the review page', function () {
    $this->actingAs($this->user)
        ->get(route('review.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('cards.0.kind', 'grammar')
            ->where('cards.0.suggestedErrorTag', 'ser_estar_confusion'),
        );
});
