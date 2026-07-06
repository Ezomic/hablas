<?php

use App\Actions\Languages\GetCurrentLanguage;
use App\Models\Language;
use App\Models\User;

it('returns the user\'s explicitly selected language when it is active', function () {
    $spanish = Language::factory()->create(['is_active' => true]);
    $portuguese = Language::factory()->create(['is_active' => true]);
    $user = User::factory()->create(['current_language_id' => $portuguese->id]);

    $language = (new GetCurrentLanguage)->handle($user);

    expect($language?->id)->toBe($portuguese->id);
});

it('falls back to the single active language when the user has not selected one', function () {
    $spanish = Language::factory()->create(['is_active' => true]);
    Language::factory()->create(['is_active' => false]);
    $user = User::factory()->create(['current_language_id' => null]);

    $language = (new GetCurrentLanguage)->handle($user);

    expect($language?->id)->toBe($spanish->id);
});

it('falls back to the active language when the selected language is no longer active', function () {
    $spanish = Language::factory()->create(['is_active' => true]);
    $portuguese = Language::factory()->create(['is_active' => false]);
    $user = User::factory()->create(['current_language_id' => $portuguese->id]);

    $language = (new GetCurrentLanguage)->handle($user);

    expect($language?->id)->toBe($spanish->id);
});

it('returns null when there is no selection and no active language', function () {
    Language::factory()->create(['is_active' => false]);
    $user = User::factory()->create(['current_language_id' => null]);

    expect((new GetCurrentLanguage)->handle($user))->toBeNull();
});
