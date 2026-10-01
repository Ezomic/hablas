<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonStage;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use Illuminate\Support\Str;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    $this->run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $this->post = fn (array $body) => $this->actingAs($this->user)->postJson(route('lesson-runs.answers.store', [$this->run, (string) Str::uuid()]), $body);
});

it('accepts a skip of an exercise that can be skipped', function () {
    $exercise = LessonExercise::query()->whereIn('id', $this->run->planExerciseIds())->where('format', 'listen_choose')->firstOrFail();

    ($this->post)(['exercise_id' => $exercise->id, 'skipped' => true, 'skip_reason' => 'unsupported'])
        ->assertOk()
        ->assertJson(['saved' => true]);

    expect(LessonAnswer::query()->sole()->skipped)->toBeTrue();
});

it('refuses a skip of an exercise that cannot be skipped', function () {
    $exercise = LessonExercise::query()->whereIn('id', $this->run->planExerciseIds())->where('format', 'type_word')->firstOrFail();

    ($this->post)(['exercise_id' => $exercise->id, 'skipped' => true, 'skip_reason' => 'chosen'])->assertUnprocessable()->assertJsonValidationErrors('lesson');
});

it('requires a known skip reason when skipped', function () {
    $exercise = LessonExercise::query()->whereIn('id', $this->run->planExerciseIds())->where('format', 'listen_choose')->firstOrFail();

    ($this->post)(['exercise_id' => $exercise->id, 'skipped' => true])->assertUnprocessable()->assertJsonValidationErrors('skip_reason');
    ($this->post)(['exercise_id' => $exercise->id, 'skipped' => true, 'skip_reason' => 'because'])->assertUnprocessable()->assertJsonValidationErrors('skip_reason');
});
