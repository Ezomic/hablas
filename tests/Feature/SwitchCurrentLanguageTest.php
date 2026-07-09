<?php

use App\Actions\Languages\SwitchCurrentLanguage;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('sets the user\'s current language', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => null]);
    (new UnlockLanguageForUser)->handle($user, $language);

    (new SwitchCurrentLanguage)->handle($user, $language->id);

    expect($user->fresh()->current_language_id)->toBe($language->id);
});

it('can switch away from a previously selected language', function () {
    $first = Language::factory()->create();
    $second = Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => $first->id]);
    (new UnlockLanguageForUser)->handle($user, $first);
    (new UnlockLanguageForUser)->handle($user, $second);

    (new SwitchCurrentLanguage)->handle($user, $second->id);

    expect($user->fresh()->current_language_id)->toBe($second->id);
});

it('returns the resolved language', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);

    $resolved = (new SwitchCurrentLanguage)->handle($user, $language->id);

    expect($resolved->id)->toBe($language->id);
});

it('throws when the language is not unlocked for this user', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create();

    (new SwitchCurrentLanguage)->handle($user, $language->id);
})->throws(ModelNotFoundException::class);

it('throws when the language is unlocked only for a different user', function () {
    $language = Language::factory()->create();
    $otherUser = User::factory()->create();
    (new UnlockLanguageForUser)->handle($otherUser, $language);
    $user = User::factory()->create();

    (new SwitchCurrentLanguage)->handle($user, $language->id);
})->throws(ModelNotFoundException::class);
