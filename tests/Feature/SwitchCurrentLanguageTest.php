<?php

use App\Actions\Languages\SwitchCurrentLanguage;
use App\Models\Language;
use App\Models\User;

it('sets the user\'s current language', function () {
    $language = Language::factory()->create(['is_active' => true]);
    $user = User::factory()->create(['current_language_id' => null]);

    (new SwitchCurrentLanguage)->handle($user, $language);

    expect($user->fresh()->current_language_id)->toBe($language->id);
});

it('can switch away from a previously selected language', function () {
    $first = Language::factory()->create(['is_active' => true]);
    $second = Language::factory()->create(['is_active' => true]);
    $user = User::factory()->create(['current_language_id' => $first->id]);

    (new SwitchCurrentLanguage)->handle($user, $second);

    expect($user->fresh()->current_language_id)->toBe($second->id);
});
