<?php

use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\User;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('redirects to the placement test when the active language has no completed attempt', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('placement.index'));
});

it('allows access to the dashboard once the placement test is completed', function () {
    $user = User::factory()->create();

    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('renders the placement test page with items for the active language', function () {
    PlacementTestItem::factory()->count(3)->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('placement/Index')
            ->has('items', 3),
        );
});

it('scores the submission and redirects to the dashboard', function () {
    $item = PlacementTestItem::factory()->create([
        'language_id' => $this->spanish->id,
        'correct_answer' => 'correct',
    ]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('placement.store'), [
            'responses' => [$item->id => 'correct'],
        ])
        ->assertRedirect(route('dashboard'));

    expect(PlacementTestAttempt::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

it('rejects a submission with no responses and returns a clear error', function () {
    PlacementTestItem::factory()->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('placement.store'), ['responses' => []])
        ->assertSessionHasErrors('responses');

    expect(PlacementTestAttempt::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('lets a user skip the placement test and start at A1', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('placement.skip'))
        ->assertRedirect(route('dashboard'));

    expect(PlacementTestAttempt::query()->where('user_id', $user->id)->whereNotNull('completed_at')->exists())->toBeTrue();
});
