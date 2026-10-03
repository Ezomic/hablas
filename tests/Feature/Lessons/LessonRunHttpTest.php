<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\CefrLevel;
use App\Enums\ExerciseFamily;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel(families: [ExerciseFamily::Choice, ExerciseFamily::Writing]);
    $this->user = LessonWorld::learner();
    $this->meet = LessonWorld::lesson($this->unit, LessonStage::Meet);
    $this->startUrl = fn ($lesson = null): string => route('lessons.runs.store', [$this->unit, $lesson ?? $this->meet]);
});

it('requires authentication to start and to play a run', function () {
    $run = (new StartLessonRun)->handle($this->user, $this->meet);

    $this->post(($this->startUrl)())->assertRedirect(route('login'));
    $this->get(route('lesson-runs.show', $run))->assertRedirect(route('login'));
});

describe('starting a run', function () {
    it('starts a run, puts the unit in progress and redirects to the player', function () {
        $response = $this->actingAs($this->user)->post(($this->startUrl)());
        $run = LessonRun::query()->sole();

        $response->assertRedirect(route('lesson-runs.show', $run));

        expect($run->kind)->toBe(LessonRunKind::Lesson)
            ->and($run->lesson_id)->toBe($this->meet->id)
            ->and(UserUnitProgress::query()->where('user_id', $this->user->id)->value('status'))->toBe(UnitProgressStatus::InProgress);
    });

    it('resumes the run in progress when started twice', function () {
        $this->actingAs($this->user)->post(($this->startUrl)());
        $this->actingAs($this->user)->post(($this->startUrl)())->assertRedirect(route('lesson-runs.show', LessonRun::query()->sole()));

        expect(LessonRun::query()->count())->toBe(1);
    });

    it('refuses a lesson that is not open yet with a message on the lesson key', function () {
        $recall = LessonWorld::lesson($this->unit, LessonStage::Recall);

        $this->actingAs($this->user)->post(($this->startUrl)($recall))->assertSessionHasErrors('lesson');

        expect(LessonRun::query()->count())->toBe(0);
    });

    it('starts the check lesson as a check run', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $check = LessonWorld::lesson($this->unit, LessonStage::Check);

        $this->actingAs($this->user)->post(($this->startUrl)($check))->assertRedirect();

        expect(LessonRun::query()->where('lesson_id', $check->id)->sole()->kind)->toBe(LessonRunKind::Check);
    });

    it('starts the check of a unit not yet started as a test-out when asked', function () {
        $check = LessonWorld::lesson($this->unit, LessonStage::Check);

        $this->actingAs($this->user)->post(($this->startUrl)($check), ['kind' => 'test_out'])->assertRedirect();

        expect(LessonRun::query()->sole()->kind)->toBe(LessonRunKind::TestOut);
    });

    it('starts practice and a retake from the check lesson after a check, and resumes either as what it is', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $check = LessonWorld::lesson($this->unit, LessonStage::Check);
        $taken = (new StartLessonRun)->handle($this->user, $check, LessonRunKind::Check);
        LessonWorld::play($this->user, $taken, fn (LessonExercise $exercise): bool => $exercise->key === 'check.a.type_word.la-llave');

        $this->actingAs($this->user)->post(($this->startUrl)($check))->assertSessionHasErrors('lesson');
        $this->actingAs($this->user)->post(($this->startUrl)($check), ['kind' => 'retake'])->assertSessionHasErrors('lesson');

        $this->actingAs($this->user)->post(($this->startUrl)($check), ['kind' => 'practice'])->assertRedirect();
        $practice = LessonRun::query()->where('lesson_id', $check->id)->where('kind', LessonRunKind::Practice)->sole();

        $this->actingAs($this->user)->post(($this->startUrl)($check))->assertRedirect(route('lesson-runs.show', $practice));

        expect(LessonRun::query()->where('lesson_id', $check->id)->where('status', LessonRunStatus::InProgress)->count())->toBe(1);
    });

    it('opens a retake over HTTP only on a later day', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $check = LessonWorld::lesson($this->unit, LessonStage::Check);
        $taken = (new StartLessonRun)->handle($this->user, $check, LessonRunKind::Check);
        LessonWorld::play($this->user, $taken, fn (LessonExercise $exercise): bool => $exercise->key === 'check.a.type_word.la-llave');

        $this->travel(1)->days();
        $this->actingAs($this->user)->post(($this->startUrl)($check), ['kind' => 'retake'])->assertRedirect();

        expect(LessonRun::query()->where('lesson_id', $check->id)->where('kind', LessonRunKind::Retake)->count())->toBe(1);
    });

    it('refuses practice and a retake on a lesson that is not the check', function () {
        $this->actingAs($this->user)->post(($this->startUrl)(), ['kind' => 'practice'])->assertSessionHasErrors('lesson');
    });

    it('refuses a kind other than lesson, test_out, practice or retake', function () {
        $this->actingAs($this->user)->post(($this->startUrl)(), ['kind' => 'check'])->assertSessionHasErrors('kind');

        expect(LessonRun::query()->count())->toBe(0);
    });

    it('answers 403 for a unit above the learner\'s level', function () {
        Unit::query()->whereKey($this->unit->id)->update(['cefr_level' => CefrLevel::B2]);

        $this->actingAs($this->user)->post(($this->startUrl)())->assertForbidden();
    });

    it('answers 404 for a unit in the other language', function () {
        $portuguese = Language::query()->firstOrCreate(['code' => 'pt'], ['name' => 'Portuguese']);
        Unit::query()->whereKey($this->unit->id)->update(['language_id' => $portuguese->id]);

        $this->actingAs($this->user)->post(($this->startUrl)())->assertNotFound();
    });

    it('answers 404 for a lesson that belongs to another unit', function () {
        $other = LessonWorld::hotelUnit(slug: 'another-unit');

        $this->actingAs($this->user)->post(route('lessons.runs.store', [$other, $this->meet]))->assertNotFound();
    });
});

describe('the player', function () {
    it('renders the run, its plan and its settings', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->meet);

        $this->actingAs($this->user)
            ->get(route('lesson-runs.show', $run))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('lessons/Play')
                ->where('run.id', $run->id)
                ->where('run.status', 'in_progress')
                ->where('run.summary', null)
                ->where('unit.title', 'Checking into a hotel')
                ->where('lesson.stage', 'meet')
                ->where('settings.feedback', true)
                ->where('settings.speechLocale', 'es-ES')
                ->has('plan', count($run->plan)),
            );
    });

    it('answers 404 for another user\'s run', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->meet);

        $this->actingAs(User::factory()->create())->get(route('lesson-runs.show', $run))->assertNotFound();
    });

    it('answers 404 for a run of a unit in the other language', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->meet);
        $portuguese = Language::query()->firstOrCreate(['code' => 'pt'], ['name' => 'Portuguese']);
        Unit::query()->whereKey($this->unit->id)->update(['language_id' => $portuguese->id]);

        $this->actingAs($this->user)->get(route('lesson-runs.show', $run))->assertNotFound();
    });

    it('shows a completed run with its summary and marks the summary as seen', function () {
        $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, $this->meet));

        expect($run->summary_seen_at)->toBeNull();

        $this->actingAs($this->user)
            ->get(route('lesson-runs.show', $run))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('run.status', 'completed')
                ->where('run.summary.unitCompleted', false)
                ->where('run.next.stage', 'recall')
                ->has('run.summary.accuracy'),
            );

        expect($run->fresh()->summary_seen_at)->not->toBeNull();
    });

    it('settles a run whose completion once failed when its page is loaded', function () {
        $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, $this->meet));
        $run->forceFill(['status' => LessonRunStatus::InProgress, 'open_lesson_id' => $this->meet->id, 'completed_at' => null, 'result' => null, 'first_try_accuracy' => null])->save();

        $this->actingAs($this->user)->get(route('lesson-runs.show', $run))->assertOk()->assertInertia(fn ($page) => $page->where('run.status', 'completed'));

        expect($run->fresh()->status)->toBe(LessonRunStatus::Completed);
    });
});
