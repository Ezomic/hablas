<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonStage;
use App\Enums\SrsRating;
use App\Models\Lesson;
use App\Models\PlacementTestAttempt;
use App\Models\SrsCard;
use App\Models\SrsReview;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel(withAuthored: false);
    $this->user = LessonWorld::learner();
    PlacementTestAttempt::factory()->create(['user_id' => $this->user->id, 'language_id' => $this->unit->language_id, 'completed_at' => now()]);
});

function struggling(object $test): void
{
    $card = SrsCard::factory()->create(['user_id' => $test->user->id, 'language_id' => $test->unit->language_id]);

    collect(range(1, 8))->each(fn () => SrsReview::factory()->create(['user_id' => $test->user->id, 'srs_card_id' => $card->id, 'rating' => SrsRating::Again]));
}

it('offers lesson 1 of a new unit to start', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page
            ->where('nextUnit.id', $this->unit->id)
            ->where('nextUnit.lesson', ['lessonId' => LessonWorld::lesson($this->unit, LessonStage::Meet)->id, 'title' => 'Meet the words', 'number' => 1, 'count' => 3, 'resumes' => false]),
        );
});

it('offers the lesson in progress to continue', function () {
    (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('nextUnit.lesson.number', 1)->where('nextUnit.lesson.resumes', true));
});

it('offers the next lesson once the first is completed', function () {
    LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('nextUnit.lesson.title', 'Recall the words')->where('nextUnit.lesson.number', 2));
});

it('has no lesson for a unit with none, which keeps the old start link', function () {
    $this->unit->lessons()->delete();

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('nextUnit.id', $this->unit->id)->where('nextUnit.lesson', null));

    expect(Lesson::query()->count())->toBe(0);
});

it('holds back a new unit while the reviews need clearing', function () {
    struggling($this);

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('sessionNeedsRemediation', true)->where('nextUnit', null));
});

it('keeps a unit in progress on the dashboard while the reviews need clearing', function () {
    (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    struggling($this);

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('sessionNeedsRemediation', true)->where('nextUnit.id', $this->unit->id)->where('nextUnit.lesson.resumes', true));
});

it('points at a completed run whose summary was never seen', function () {
    $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('unseenLessonResults', [['runId' => $run->id, 'unitTitle' => 'Checking into a hotel', 'lessonTitle' => 'Meet the words', 'isCheck' => false]]));

    $this->actingAs($this->user)->get(route('lesson-runs.show', $run));

    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page->where('unseenLessonResults', []));
});
