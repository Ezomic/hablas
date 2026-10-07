<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\CefrLevel;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\UserUnitProgress;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel(withAuthored: false);
    $this->user = LessonWorld::learner();
});

it('gives a unit with playable lessons its lesson overview', function () {
    $this->actingAs($this->user)
        ->get(route('units.show', $this->unit))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('units/Show')
            ->where('lessons.contentPending', true)
            ->where('lessons.lessons.0', ['stage' => 'meet', 'title' => 'Meet the words', 'position' => 1, 'lessonId' => LessonWorld::lesson($this->unit, LessonStage::Meet)->id, 'state' => 'available', 'bestAccuracy' => null, 'mastered' => false])
            ->where('lessons.lessons.1.state', 'locked')
            ->where('lessons.lessons.2.state', 'coming')
            ->where('lessons.lessons.3.state', 'coming')
            ->where('lessons.lessons.4.state', 'locked')
            ->where('lessons.mastery', ['mastered' => 0, 'total' => 10])
            ->where('lessons.canTestOut', true)
            ->where('availability', 'available'),
        );
});

it('shows a lesson in progress and a lesson completed with its best accuracy', function () {
    LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));
    (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Recall));

    $this->actingAs($this->user)
        ->get(route('units.show', $this->unit))
        ->assertInertia(fn ($page) => $page
            ->where('lessons.lessons.0.state', 'completed')
            ->where('lessons.lessons.0.bestAccuracy', 1)
            ->where('lessons.lessons.1.state', 'in_progress')
            ->where('lessons.canTestOut', false)
            ->where('availability', 'in_progress'),
        );
});

it('has no lesson overview for a unit without playable lessons, which keeps the old page', function () {
    $plain = Unit::factory()->create(['language_id' => $this->unit->language_id, 'cefr_level' => CefrLevel::A1]);

    $this->actingAs($this->user)
        ->get(route('units.show', $plain))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('lessons', null));
});

it('no longer completes a unit with playable lessons through the old button', function () {
    $this->actingAs($this->user)->post(route('units.completion.store', $this->unit))->assertNotFound();

    expect(UserUnitProgress::query()->count())->toBe(0);
});

it('still completes a unit without playable lessons through the old button', function () {
    $plain = Unit::factory()->create(['language_id' => $this->unit->language_id, 'cefr_level' => CefrLevel::A1]);

    $this->actingAs($this->user)->post(route('units.completion.store', $plain))->assertRedirect(route('dashboard'));

    expect(UserUnitProgress::query()->where('unit_id', $plain->id)->exists())->toBeTrue();
});

it('lists a unit in progress in the library with its lesson counts', function () {
    (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertInertia(fn ($page) => $page
            ->where('units.0.availability', 'in_progress')
            ->where('units.0.lessonCount', 3)
            ->where('units.0.lessonsCompleted', 0),
        );
});

it('shows the remediation after a check with something missing, and the check row as awaiting it', function () {
    $this->actingAs($this->user)->get(route('units.show', $this->unit))->assertInertia(fn ($page) => $page->where('lessons.remediation', null));

    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $check = LessonWorld::lesson($this->unit, LessonStage::Check);
    $run = (new StartLessonRun)->handle($this->user, $check, LessonRunKind::Check);
    LessonWorld::play($this->user, $run, fn (LessonExercise $exercise): bool => $exercise->key === 'check.a.type_word.la-llave');

    $this->actingAs($this->user)
        ->get(route('units.show', $this->unit))
        ->assertInertia(fn ($page) => $page
            ->where('lessons.remediation', ['lessonId' => $check->id, 'missing' => 1, 'retake' => 'opens_tomorrow'])
            ->where('lessons.lessons.4.state', 'remediation'),
        );
});
