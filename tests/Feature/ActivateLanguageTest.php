<?php

declare(strict_types=1);

use App\Actions\Languages\ActivateLanguage;
use App\Models\Language;
use App\Models\User;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
    $this->user = User::factory()->create();
});

it('unlocks Portuguese for the user', function () {
    expect($this->user->unlockedLanguages()->where('languages.id', $this->portuguese->id)->exists())->toBeFalse();

    (new ActivateLanguage)->handle($this->user, $this->portuguese);

    expect($this->user->unlockedLanguages()->where('languages.id', $this->portuguese->id)->exists())->toBeTrue();
});

it('switches the user\'s current language to Portuguese', function () {
    (new ActivateLanguage)->handle($this->user, $this->portuguese);

    expect($this->user->fresh()->current_language_id)->toBe($this->portuguese->id);
});

it('returns the Portuguese language', function () {
    $language = (new ActivateLanguage)->handle($this->user, $this->portuguese);

    expect($language->id)->toBe($this->portuguese->id);
});

it('does not error when called twice', function () {
    (new ActivateLanguage)->handle($this->user, $this->portuguese);
    (new ActivateLanguage)->handle($this->user, $this->portuguese);

    expect($this->user->unlockedLanguages()->where('languages.id', $this->portuguese->id)->count())->toBe(1)
        ->and($this->user->fresh()->current_language_id)->toBe($this->portuguese->id);
});

it('does not unlock Portuguese for a different user', function () {
    $otherUser = User::factory()->create();

    (new ActivateLanguage)->handle($this->user, $this->portuguese);

    expect($otherUser->unlockedLanguages()->where('languages.id', $this->portuguese->id)->exists())->toBeFalse();
});
