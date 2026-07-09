<?php

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\User;

it('returns the user\'s explicitly selected language when it is unlocked for them', function () {
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => $portuguese->id]);
    (new UnlockLanguageForUser)->handle($user, $spanish);
    (new UnlockLanguageForUser)->handle($user, $portuguese);

    $language = (new GetCurrentLanguage)->handle($user);

    expect($language?->id)->toBe($portuguese->id);
});

it('falls back to the earliest-unlocked language when the user has not selected one', function () {
    $spanish = Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => null]);
    (new UnlockLanguageForUser)->handle($user, $spanish);

    $language = (new GetCurrentLanguage)->handle($user);

    expect($language?->id)->toBe($spanish->id);
});

it('falls back to an unlocked language when the selected language is not unlocked for this user', function () {
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => $portuguese->id]);
    (new UnlockLanguageForUser)->handle($user, $spanish);

    $language = (new GetCurrentLanguage)->handle($user);

    expect($language?->id)->toBe($spanish->id);
});

it('returns null when the user has no unlocked languages at all', function () {
    Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => null]);

    expect((new GetCurrentLanguage)->handle($user))->toBeNull();
});

it('does not resolve a language unlocked only by a different user', function () {
    $spanish = Language::factory()->create();
    $otherUser = User::factory()->create();
    (new UnlockLanguageForUser)->handle($otherUser, $spanish);
    $user = User::factory()->create(['current_language_id' => null]);

    expect((new GetCurrentLanguage)->handle($user))->toBeNull();
});
