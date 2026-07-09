<?php

use App\Actions\Progress\RevokeProgressShare;
use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;

it('sets revoked_at on the active share for the given user and language', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $share = ProgressShare::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'revoked_at' => null,
    ]);

    (new RevokeProgressShare)->handle($user, $language);

    expect($share->fresh()->revoked_at)->not->toBeNull();
});

it('does not error when there is no active share to revoke', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    (new RevokeProgressShare)->handle($user, $language);

    expect(ProgressShare::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('does not revoke another users share', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $language = Language::factory()->create();
    $otherShare = ProgressShare::factory()->create([
        'user_id' => $otherUser->id,
        'language_id' => $language->id,
        'revoked_at' => null,
    ]);

    (new RevokeProgressShare)->handle($user, $language);

    expect($otherShare->fresh()->revoked_at)->toBeNull();
});
