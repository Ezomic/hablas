<?php

declare(strict_types=1);

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    config(['languages.activatable' => ['pt']]);
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
});

it('activates Portuguese for a user with a Spanish blended level of A2 or above', function () {
    $user = User::factory()->create();
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }

    $this->actingAs($user)
        ->post(route('language.activate', 'pt'))
        ->assertRedirect(route('dashboard'));

    expect($user->unlockedLanguages()->where('languages.id', $this->portuguese->id)->exists())->toBeTrue()
        ->and($user->fresh()->current_language_id)->toBe($this->portuguese->id);
});

it('forbids activation for a user below A2 in Spanish', function () {
    $user = User::factory()->create();
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A1,
        ]);
    }

    $this->actingAs($user)
        ->post(route('language.activate', 'pt'))
        ->assertForbidden();

    expect($user->unlockedLanguages()->where('languages.id', $this->portuguese->id)->exists())->toBeFalse();
});

it('lists Portuguese as activatable on the dashboard once eligible', function () {
    $user = User::factory()->create();
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('activatableLanguages', [['code' => 'pt', 'name' => 'Portuguese']]));
});

it('lists nothing as activatable on the dashboard when ineligible', function () {
    $user = User::factory()->create();
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('activatableLanguages', []));
});

it('forbids activating a language whose content is not released', function () {
    $user = User::factory()->create();
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }
    $french = Language::query()->where('code', 'fr')->sole();

    $this->actingAs($user)
        ->post(route('language.activate', 'fr'))
        ->assertForbidden();

    expect($user->unlockedLanguages()->where('languages.id', $french->id)->exists())->toBeFalse();
});

it('returns not found for an unknown language code', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('language.activate', 'xx'))
        ->assertNotFound();
});
