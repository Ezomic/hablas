<?php

declare(strict_types=1);

use App\Models\PlacementTestAttempt;
use App\Models\Unit;
use App\Models\User;
use Tests\Fixtures\Lessons\LessonWorld;

function placed(User $user): User
{
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $user->current_language_id, 'completed_at' => now()]);

    return $user;
}

it('sends a guest to sign in', function () {
    $this->get(route('continue'))->assertRedirect(route('login'));
});

it('opens the unit the learner should continue', function () {
    [$unit] = LessonWorld::seededHotel();
    $user = placed(LessonWorld::learner());

    $this->actingAs($user)
        ->get(route('continue'))
        ->assertRedirect(route('units.show', Unit::query()->findOrFail($unit->id)));
});

it('falls back to the dashboard when there is no unit to continue', function () {
    $user = placed(LessonWorld::learner());

    $this->actingAs($user)->get(route('continue'))->assertRedirect(route('dashboard'));
});

it('falls back to the dashboard for a learner with no language', function () {
    $user = User::factory()->create(['current_language_id' => null]);

    $this->actingAs($user)->get(route('continue'))->assertRedirect(route('dashboard'));
});

it('gives the practise hub to a signed-in learner', function () {
    $this->actingAs(LessonWorld::learner())->get(route('practice'))->assertOk()->assertInertia(fn ($page) => $page->component('practice/Index'));
});

it('shares the number of reviews due for the tab badge', function () {
    $this->actingAs(LessonWorld::learner())->get(route('practice'))->assertInertia(fn ($page) => $page->where('dueReviewCount', 0));
});

it('sends a learner who has not taken the placement test to it first', function () {
    LessonWorld::seededHotel();

    $this->actingAs(LessonWorld::learner())->get(route('continue'))->assertRedirect(route('placement.index'));
});
