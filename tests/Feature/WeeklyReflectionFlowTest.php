<?php

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\CefrCanDoStatement;
use App\Models\Language;
use App\Models\User;
use App\Models\WeeklyReflection;
use Carbon\CarbonImmutable;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('renders the reflection page with statements for the active language', function () {
    CefrCanDoStatement::factory()->create(['skill' => Skill::Reading, 'cefr_level' => CefrLevel::A1]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('reflections.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('reflections/Index')
            ->where('submittedThisWeek', false),
        );
});

it('shows a submitted state once this week reflection is already completed', function () {
    $user = User::factory()->create();
    WeeklyReflection::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'week_start_date' => CarbonImmutable::now()->startOfWeek(),
        'submitted_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('reflections.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('reflections/Index')
            ->where('submittedThisWeek', true),
        );
});

it('submits a weekly reflection and redirects to the index', function () {
    $statement = CefrCanDoStatement::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('reflections.store'), [
            'statement_ids' => [$statement->id],
            'can_do_ids' => [$statement->id],
        ])
        ->assertRedirect(route('reflections.index'));

    expect(WeeklyReflection::query()->where('user_id', $user->id)->exists())->toBeTrue();
});
