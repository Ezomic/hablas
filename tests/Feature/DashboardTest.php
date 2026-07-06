<?php

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Enums\SrsRating;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\VocabularyItem;

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

it('shows the count of due review cards for the active language', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish', 'is_active' => true]);
    $user = User::factory()->create();
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);
    $vocabularyItem = VocabularyItem::factory()->create(['language_id' => $language->id]);
    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $vocabularyItem->id,
        'due_at' => now()->subMinute(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('dueReviewCount', 1),
        );
});

it('surfaces the next unit when the session is healthy', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish', 'is_active' => true]);
    $user = User::factory()->create();
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);
    $unit = Unit::factory()->create(['language_id' => $language->id, 'cefr_level' => CefrLevel::A1]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('sessionNeedsRemediation', false)
            ->where('nextUnit.id', $unit->id)
            ->where('nextUnit.title', $unit->title),
        );
});

it('shows a remediation prompt instead of the next unit when the session is unhealthy', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish', 'is_active' => true]);
    $user = User::factory()->create();
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);
    Unit::factory()->create(['language_id' => $language->id, 'cefr_level' => CefrLevel::A1]);
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    collect(range(1, 8))->each(fn () => SrsReview::factory()->create([
        'user_id' => $user->id,
        'srs_card_id' => $card->id,
        'rating' => SrsRating::Again,
    ]));

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('sessionNeedsRemediation', true)
            ->where('nextUnit', null),
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
