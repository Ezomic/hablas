<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\ExerciseFamily;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel(families: [ExerciseFamily::Choice, ExerciseFamily::Writing]);
    $this->user = LessonWorld::learner();
    $this->run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $this->typed = LessonExercise::query()->whereIn('id', $this->run->planExerciseIds())->where('format', 'type_word')->firstOrFail();
    $this->answer = fn (array $response): LessonAnswer => LessonWorld::answer($this->user, $this->run, $this->typed, ['response' => $response])['answer'];
    $this->flag = fn (string $step, $run = null) => $this->actingAs($this->user)->postJson(route('lesson-runs.answers.flag.store', [$run ?? $this->run, $step]));
});

it('requires authentication', function () {
    $answer = ($this->answer)(['text' => 'zzz']);

    $this->postJson(route('lesson-runs.answers.flag.store', [$this->run, $answer->step]))->assertUnauthorized();
});

it('marks a wrong answer as disputed', function () {
    $answer = ($this->answer)(['text' => 'zzz']);

    ($this->flag)($answer->step)->assertOk()->assertJson(['flagged' => true, 'run' => ['completed' => false]]);

    expect($answer->fresh()->flagged_at)->not->toBeNull()
        ->and($answer->fresh()->settlesExercise())->toBeTrue();
});

it('keeps the first flag time when flagged again', function () {
    $answer = ($this->answer)(['text' => 'zzz']);

    ($this->flag)($answer->step)->assertOk();
    $first = $answer->fresh()->flagged_at;
    $this->travel(5)->minutes();
    ($this->flag)($answer->step)->assertOk();

    expect($answer->fresh()->flagged_at->equalTo($first))->toBeTrue();
});

it('lets the flag finish a run whose last exercise was answered wrong', function () {
    $others = collect($this->run->planExerciseIds())->reject(fn (int $id): bool => $id === $this->typed->id);

    foreach ($others as $id) {
        LessonWorld::answer($this->user, $this->run, LessonExercise::query()->findOrFail($id));
    }

    $answer = ($this->answer)(['text' => 'zzz']);

    ($this->flag)($answer->step)->assertOk()->assertJson(['run' => ['completed' => true]]);

    expect($this->run->fresh()->status)->toBe(LessonRunStatus::Completed);
});

it('refuses to flag a right answer', function () {
    $answer = ($this->answer)(LessonWorld::rightResponse($this->typed));

    ($this->flag)($answer->step)->assertUnprocessable()->assertJsonValidationErrors('lesson');

    expect($answer->fresh()->flagged_at)->toBeNull();
});

it('refuses to flag an answer in a check, which gives no feedback', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $check = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);
    $exercise = LessonExercise::query()->findOrFail($check->plan[0]['id']);
    $answer = LessonWorld::answer($this->user, $check, $exercise, ['response' => ['text' => 'zzz']])['answer'];

    ($this->flag)($answer->step, $check)->assertUnprocessable()->assertJsonValidationErrors('lesson');
});

it('words the dispute refusal in the learner locale', function () {
    $answer = ($this->answer)(LessonWorld::rightResponse($this->typed));
    $this->user->forceFill(['interface_locale' => 'nl'])->save();
    config(['app.supported_locales' => ['en', 'nl']]);

    ($this->flag)($answer->step)->assertUnprocessable()->assertJsonValidationErrors(['lesson' => 'Alleen een fout antwoord kan worden betwist.']);
});

it('answers 404 for a step that is not in the run', function () {
    ($this->flag)((string) Str::uuid())->assertNotFound();
});

it('answers 404 for another user\'s run', function () {
    $answer = ($this->answer)(['text' => 'zzz']);

    $this->actingAs(User::factory()->create())->postJson(route('lesson-runs.answers.flag.store', [$this->run, $answer->step]))->assertNotFound();
});
