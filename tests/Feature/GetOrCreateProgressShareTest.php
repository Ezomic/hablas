<?php

use App\Actions\Progress\GetOrCreateProgressShare;
use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;

it('creates a share on first call', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $share = (new GetOrCreateProgressShare)->handle($user, $language);

    expect($share->user_id)->toBe($user->id)
        ->and($share->language_id)->toBe($language->id)
        ->and($share->token)->not->toBeEmpty()
        ->and($share->revoked_at)->toBeNull();
});

it('returns the same active share on repeated calls', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $first = (new GetOrCreateProgressShare)->handle($user, $language);
    $second = (new GetOrCreateProgressShare)->handle($user, $language);

    expect($second->id)->toBe($first->id)
        ->and(ProgressShare::query()->where('user_id', $user->id)->count())->toBe(1);
});

it('mints a fresh share when the existing one is revoked', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $revoked = ProgressShare::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'revoked_at' => now(),
    ]);

    $share = (new GetOrCreateProgressShare)->handle($user, $language);

    expect($share->id)->not->toBe($revoked->id)
        ->and($share->revoked_at)->toBeNull();
});
