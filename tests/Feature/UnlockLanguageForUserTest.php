<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\User;

it('unlocks a language for a user', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    (new UnlockLanguageForUser)->handle($user, $language);

    expect($user->unlockedLanguages()->where('languages.id', $language->id)->exists())->toBeTrue();
});

it('is idempotent when called twice for the same user and language', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    (new UnlockLanguageForUser)->handle($user, $language);
    (new UnlockLanguageForUser)->handle($user, $language);

    expect($user->unlockedLanguages()->where('languages.id', $language->id)->count())->toBe(1);
});

it('does not unlock the language for any other user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $language = Language::factory()->create();

    (new UnlockLanguageForUser)->handle($user, $language);

    expect($otherUser->unlockedLanguages()->where('languages.id', $language->id)->exists())->toBeFalse();
});

it('lets a user unlock multiple languages independently', function () {
    $user = User::factory()->create();
    $first = Language::factory()->create();
    $second = Language::factory()->create();

    (new UnlockLanguageForUser)->handle($user, $first);
    (new UnlockLanguageForUser)->handle($user, $second);

    expect($user->unlockedLanguages()->count())->toBe(2);
});
