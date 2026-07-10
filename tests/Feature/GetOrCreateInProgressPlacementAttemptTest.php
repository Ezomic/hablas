<?php

use App\Actions\Placement\GetOrCreateInProgressPlacementAttempt;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;

it('creates an in-progress attempt on first call', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($attempt->user_id)->toBe($user->id)
        ->and($attempt->language_id)->toBe($language->id)
        ->and($attempt->completed_at)->toBeNull();
});

it('returns the same in-progress attempt on repeated calls', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $first = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);
    $second = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($second->id)->toBe($first->id)
        ->and(PlacementTestAttempt::query()->where('user_id', $user->id)->count())->toBe(1);
});

it('mints a fresh attempt when the existing one is completed', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $completed = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);

    $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($attempt->id)->not->toBe($completed->id)
        ->and($attempt->completed_at)->toBeNull();
});

it('does not resume another users attempt', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $language = Language::factory()->create();
    $otherAttempt = PlacementTestAttempt::factory()->create(['user_id' => $otherUser->id, 'language_id' => $language->id]);

    $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($attempt->id)->not->toBe($otherAttempt->id);
});
