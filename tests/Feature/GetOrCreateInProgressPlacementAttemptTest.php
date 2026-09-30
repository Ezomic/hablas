<?php

declare(strict_types=1);

use App\Actions\Placement\GetOrCreateInProgressPlacementAttempt;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use App\Models\User;

it('creates an in-progress attempt on first call', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($attempt?->user_id)->toBe($user->id)
        ->and($attempt?->language_id)->toBe($language->id)
        ->and($attempt?->completed_at)->toBeNull();
});

it('returns the same in-progress attempt on repeated calls', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $first = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);
    $second = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($second?->id)->toBe($first?->id)
        ->and(PlacementTestAttempt::query()->where('user_id', $user->id)->count())->toBe(1);
});

it('mints a fresh full test when the completed one was skipped', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skipped = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);

    $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($attempt?->id)->not->toBe($skipped->id)
        ->and($attempt?->completed_at)->toBeNull()
        ->and($attempt?->skill)->toBeNull();
});

it('starts no new full test once a placement was taken', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $taken = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);
    PlacementTestResponse::factory()->create(['attempt_id' => $taken->id]);

    expect((new GetOrCreateInProgressPlacementAttempt)->handle($user, $language))->toBeNull()
        ->and(PlacementTestAttempt::query()->where('user_id', $user->id)->count())->toBe(1);
});

it('resumes an open one-skill attempt', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $retake = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
    ]);

    expect((new GetOrCreateInProgressPlacementAttempt)->handle($user, $language)?->id)->toBe($retake->id);
});

it('does not resume another users attempt', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $language = Language::factory()->create();
    $otherAttempt = PlacementTestAttempt::factory()->create(['user_id' => $otherUser->id, 'language_id' => $language->id]);

    $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

    expect($attempt?->id)->not->toBe($otherAttempt->id);
});
