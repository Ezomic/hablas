<?php

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;
use Database\Seeders\LanguageSeeder;

/**
 * The test that actually proves THI-297 is fixed: activating Portuguese for
 * one user must not leak access, eligibility, or switcher options to any
 * other user in the system — the exact bug the old global Language.is_active
 * flag caused.
 */
it('does not leak Portuguese access from one user to another', function () {
    $this->seed(LanguageSeeder::class);
    $spanish = Language::query()->where('code', 'es')->sole();
    $portuguese = Language::query()->where('code', 'pt')->sole();

    $userA = User::factory()->create();
    $userB = User::factory()->create();

    foreach ([$userA, $userB] as $user) {
        foreach (Skill::cases() as $skill) {
            UserSkillLevel::factory()->create([
                'user_id' => $user->id,
                'language_id' => $spanish->id,
                'skill' => $skill,
                'cefr_level' => CefrLevel::A2,
            ]);
        }
        PlacementTestAttempt::factory()->create([
            'user_id' => $user->id,
            'language_id' => $spanish->id,
            'completed_at' => now(),
        ]);
    }

    // User A activates Portuguese.
    $this->actingAs($userA)
        ->post(route('language.activate-portuguese'))
        ->assertRedirect(route('dashboard'));

    // User B's eligibility is unaffected by A's activation — still reflects
    // B's own (also-qualifying) Spanish progress, not a global "already
    // active" short-circuit.
    $this->actingAs($userB)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('canActivatePortuguese', true));

    // B's availableLanguages excludes Portuguese.
    $this->actingAs($userB)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->has('availableLanguages', 1));

    // B cannot switch to Portuguese via the language switcher.
    $this->actingAs($userB)
        ->patch(route('language.update'), ['language_id' => $portuguese->id])
        ->assertInvalid(['language_id']);

    // B's current language still resolves to Spanish, not Portuguese.
    $this->actingAs($userB)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('currentLanguage.code', 'es'));

    // A's own activation is unaffected: still active, still switched.
    expect($userA->unlockedLanguages()->where('languages.id', $portuguese->id)->exists())->toBeTrue()
        ->and($userA->fresh()->current_language_id)->toBe($portuguese->id)
        ->and($userB->unlockedLanguages()->where('languages.id', $portuguese->id)->exists())->toBeFalse();
});
