<?php

use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;

it('switches the current language for an active language', function () {
    $spanish = Language::factory()->create(['is_active' => true]);
    $portuguese = Language::factory()->create(['is_active' => true]);
    $user = User::factory()->create(['current_language_id' => $spanish->id]);

    $this->actingAs($user)
        ->patch(route('language.update'), ['language_id' => $portuguese->id])
        ->assertRedirect();

    expect($user->fresh()->current_language_id)->toBe($portuguese->id);
});

it('rejects switching to an inactive language', function () {
    $spanish = Language::factory()->create(['is_active' => true]);
    $inactive = Language::factory()->create(['is_active' => false]);
    $user = User::factory()->create(['current_language_id' => $spanish->id]);

    $this->actingAs($user)
        ->patch(route('language.update'), ['language_id' => $inactive->id])
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
    $spanish = Language::factory()->create(['code' => 'es', 'is_active' => true]);
    $portuguese = Language::factory()->create(['code' => 'pt', 'is_active' => true]);
    $user = User::factory()->create(['current_language_id' => $spanish->id]);
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $spanish->id,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page
            ->where('currentLanguage.id', $spanish->id)
            ->has('availableLanguages', 2),
        );
});
