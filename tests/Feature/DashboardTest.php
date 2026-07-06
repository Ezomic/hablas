<?php

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;

it('redirects guests to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

it('allows authenticated users to visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

it('shows the blended headline level and per-skill breakdown for the active language', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish', 'is_active' => true]);
    $user = User::factory()->create();
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);

    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Reading, 'cefr_level' => CefrLevel::B1]);
    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Listening, 'cefr_level' => CefrLevel::B1]);
    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Speaking, 'cefr_level' => CefrLevel::A2]);
    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Writing, 'cefr_level' => CefrLevel::B2]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('language.code', 'es')
            ->where('blendedLevel', CefrLevel::A2->value)
            ->where('skillLevels.reading', CefrLevel::B1->value)
            ->where('skillLevels.speaking', CefrLevel::A2->value),
        );
});

it('renders a graceful empty state when there is no active language', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('language', null)
            ->where('streak.currentLength', 0),
        );
});
