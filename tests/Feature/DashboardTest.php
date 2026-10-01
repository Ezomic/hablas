<?php

declare(strict_types=1);

use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\Srs\ForecastReviewLoad;
use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Enums\SrsCardState;
use App\Enums\SrsRating;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Streak;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\VocabularyItem;
use Carbon\CarbonImmutable;

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
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
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
            ->where('blendedLevel', 'A2.1')
            ->where('skillLevels.reading', 'B1.1')
            ->where('skillLevels.speaking', 'A2.1')
            ->where('skillLevels.writing', 'B2'),
        );
});

it('explains the ceiling and when the skill holding it can be re-taken', function () {
    $this->travelTo(CarbonImmutable::parse('2026-09-30 12:00:00'));
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
    $placement = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => '2026-09-01 10:00:00',
    ]);
    PlacementTestResponse::factory()->create(['attempt_id' => $placement->id]);
    $writingRetake = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'completed_at' => '2026-09-28 10:00:00',
    ]);
    PlacementTestResponse::factory()->create(['attempt_id' => $writingRetake->id, 'skill' => Skill::Writing]);

    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Reading, 'cefr_level' => CefrLevel::A1]);
    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Listening, 'cefr_level' => CefrLevel::B1]);
    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Speaking, 'cefr_level' => CefrLevel::B1]);
    UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => Skill::Writing, 'cefr_level' => CefrLevel::A1]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('blendedLevel', 'A1.1')
            ->where('blendedLevelCeiling', [Skill::Reading->value, Skill::Writing->value])
            ->where('retakeAvailableOn', [
                Skill::Reading->value => null,
                Skill::Listening->value => null,
                Skill::Speaking->value => null,
                Skill::Writing->value => '2026-10-05',
            ]),
        );
});

it('reports no ceiling when every skill is level', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);

    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => $skill, 'cefr_level' => CefrLevel::A2]);
    }

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('blendedLevelCeiling', [])
            ->where('retakeAvailableOn', []),
        );
});

it('shows the count of due review cards for the active language', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
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

it('shows the count of weak-spot cards for the active language', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);
    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'is_weak_spot' => true,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('weakSpotReviewCount', 1),
        );
});

it('forecasts the review load for the active language', function () {
    $this->travelTo(CarbonImmutable::parse('2026-10-01 15:00:00'));
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => now(),
    ]);
    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'state' => SrsCardState::Review,
        'due_at' => CarbonImmutable::parse('2026-10-03 09:00:00'),
    ]);
    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'state' => SrsCardState::New]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('reviewForecast.days', ForecastReviewLoad::DAYS)
            ->where('reviewForecast.days.0', ['date' => '2026-10-01', 'cards' => 0])
            ->where('reviewForecast.days.2', ['date' => '2026-10-03', 'cards' => 1])
            ->where('reviewForecast.newWaiting', 1),
        );
});

it('surfaces the next unit when the session is healthy', function () {
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
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
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);
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

it('tells the learner how many more active days earn the next freeze day', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 9,
        'longest_length' => 9,
        'freeze_days_remaining' => 1,
        'last_activity_date' => CarbonImmutable::yesterday(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('streak.freezeDaysRemaining', 1)
            ->where('streak.daysUntilNextFreezeDay', 5),
        );
});

it('has no next freeze day to earn while the allowance is full', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 9,
        'longest_length' => 9,
        'freeze_days_remaining' => Streak::STARTING_FREEZE_DAYS,
        'last_activity_date' => CarbonImmutable::yesterday(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('streak.daysUntilNextFreezeDay', null),
        );
});

it('shows the stored tier of each skill and the lowest tier as the headline', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);
    (new UnlockLanguageForUser)->handle($user, $language);

    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'completed_at' => now()]);

    foreach ([
        [Skill::Reading, CefrLevel::A2, CefrSubLevel::A2_2],
        [Skill::Listening, CefrLevel::A2, CefrSubLevel::A2_2],
        [Skill::Speaking, CefrLevel::A2, CefrSubLevel::A2_2],
        [Skill::Writing, CefrLevel::A1, CefrSubLevel::A1_3],
    ] as [$skill, $level, $tier]) {
        UserSkillLevel::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'skill' => $skill, 'cefr_level' => $level, 'sub_level' => $tier]);
    }

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page
            ->where('blendedLevel', 'A1.3')
            ->where('skillLevels.reading', 'A2.2')
            ->where('skillLevels.writing', 'A1.3'),
        );
});
