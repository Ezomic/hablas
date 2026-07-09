<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;

it('switches the current language for a language unlocked by this user', function () {
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => $spanish->id]);
    (new UnlockLanguageForUser)->handle($user, $spanish);
    (new UnlockLanguageForUser)->handle($user, $portuguese);

    $this->actingAs($user)
        ->patch(route('language.update'), ['language_id' => $portuguese->id])
        ->assertRedirect();

    expect($user->fresh()->current_language_id)->toBe($portuguese->id);
});

it('rejects switching to a language not unlocked for this user', function () {
    $spanish = Language::factory()->create();
    $notUnlocked = Language::factory()->create();
    $user = User::factory()->create(['current_language_id' => $spanish->id]);
    (new UnlockLanguageForUser)->handle($user, $spanish);

    $this->actingAs($user)
        ->patch(route('language.update'), ['language_id' => $notUnlocked->id])
        ->assertInvalid(['language_id']);

    expect($user->fresh()->current_language_id)->toBe($spanish->id);
});

it('rejects switching to a language unlocked only by a different user', function () {
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();
    $otherUser = User::factory()->create();
    (new UnlockLanguageForUser)->handle($otherUser, $portuguese);
    $user = User::factory()->create(['current_language_id' => $spanish->id]);
    (new UnlockLanguageForUser)->handle($user, $spanish);

    $this->actingAs($user)
        ->patch(route('language.update'), ['language_id' => $portuguese->id])
        ->assertInvalid(['language_id']);

    expect($user->fresh()->current_language_id)->toBe($spanish->id);
});

it('rejects switching to a nonexistent language', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('language.update'), ['language_id' => 999999])
        ->assertInvalid(['language_id']);
});

it('shares the current and available languages with every Inertia page', function () {
    $spanish = Language::factory()->create(['code' => 'es']);
    $portuguese = Language::factory()->create(['code' => 'pt']);
    $user = User::factory()->create(['current_language_id' => $spanish->id]);
    (new UnlockLanguageForUser)->handle($user, $spanish);
    (new UnlockLanguageForUser)->handle($user, $portuguese);
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $spanish->id,
        'completed_at' => now(),
    ]);

    // Each entry must be a plain {id, code, name} shape, not a raw
    // pivot-model dump — Eloquent's BelongsToMany attaches a `pivot`
    // sub-object to every hydrated model even when specific columns are
    // selected. Asserting only id/code/name here (without ->etc()) makes
    // Laravel's fluent JSON assertion fail if a `pivot` key is present.
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page
            ->where('currentLanguage.id', $spanish->id)
            ->has('availableLanguages', 2)
            ->has('availableLanguages.0', fn ($language) => $language
                ->whereType('id', 'integer')
                ->whereType('code', 'string')
                ->whereType('name', 'string'),
            )
            ->has('availableLanguages.1', fn ($language) => $language
                ->whereType('id', 'integer')
                ->whereType('code', 'string')
                ->whereType('name', 'string'),
            ),
        );
});

it('does not share a language unlocked only by a different user', function () {
    $spanish = Language::factory()->create(['code' => 'es']);
    $portuguese = Language::factory()->create(['code' => 'pt']);
    $otherUser = User::factory()->create();
    (new UnlockLanguageForUser)->handle($otherUser, $portuguese);
    $user = User::factory()->create(['current_language_id' => $spanish->id]);
    (new UnlockLanguageForUser)->handle($user, $spanish);
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $spanish->id,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->has('availableLanguages', 1));
});
