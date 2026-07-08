<?php

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
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
        ->post(route('language.activate-portuguese'))
        ->assertRedirect(route('dashboard'));

    expect($this->portuguese->fresh()->is_active)->toBeTrue()
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
        ->post(route('language.activate-portuguese'))
        ->assertForbidden();

    expect($this->portuguese->fresh()->is_active)->toBeFalse();
});

it('exposes canActivatePortuguese as true on the dashboard once eligible', function () {
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
        ->assertInertia(fn ($page) => $page->where('canActivatePortuguese', true));
});

it('exposes canActivatePortuguese as false on the dashboard when ineligible', function () {
    $user = User::factory()->create();
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('canActivatePortuguese', false));
});
