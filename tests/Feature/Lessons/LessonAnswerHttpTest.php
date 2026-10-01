<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\ExerciseFamily;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Models\Language;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel(families: [ExerciseFamily::Choice, ExerciseFamily::Writing]);
    $this->user = LessonWorld::learner();
    $this->run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $this->exercise = fn (string $format): LessonExercise => LessonExercise::query()->whereIn('id', $this->run->planExerciseIds())->where('format', $format)->firstOrFail();
    $this->post = fn (array $body, ?string $step = null, $run = null) => $this->actingAs($this->user)->postJson(route('lesson-runs.answers.store', [$run ?? $this->run, $step ?? (string) Str::uuid()]), $body);
});

it('requires authentication', function () {
    $this->postJson(route('lesson-runs.answers.store', [$this->run, (string) Str::uuid()]), [])->assertUnauthorized();
});

it('grades a typed answer on the server and returns the verdict', function () {
    $exercise = ($this->exercise)('type_word');

    ($this->post)(['exercise_id' => $exercise->id, 'response' => LessonWorld::rightResponse($exercise)])
        ->assertOk()
        ->assertJson(['correct' => true, 'expected' => $exercise->payload['accepted'][0]['text'], 'run' => ['completed' => false, 'unitCompleted' => false]])
        ->assertJsonStructure(['correct', 'expected', 'note', 'score', 'targets', 'run' => ['completed', 'unitCompleted', 'mastery' => ['mastered', 'total']], 'milestone']);
});

it('returns the expected answer for a wrong one', function () {
    $exercise = ($this->exercise)('type_word');

    ($this->post)(['exercise_id' => $exercise->id, 'response' => ['text' => 'zzz']])
        ->assertOk()
        ->assertJson(['correct' => false, 'expected' => $exercise->payload['accepted'][0]['text']]);

    expect(LessonAnswer::query()->sole()->is_correct)->toBeFalse();
});

it('grades a choice and a match', function () {
    $choice = ($this->exercise)('choose_meaning');
    $match = ($this->exercise)('match_pairs');

    ($this->post)(['exercise_id' => $choice->id, 'response' => LessonWorld::wrongResponse($choice)])->assertJson(['correct' => false, 'expected' => $choice->payload['answer']]);
    ($this->post)(['exercise_id' => $match->id, 'response' => ['wrong' => []]])->assertJson(['correct' => true]);
});

it('returns the stored verdict for a repeated step and records nothing new', function () {
    $exercise = ($this->exercise)('type_word');
    $step = (string) Str::uuid();

    ($this->post)(['exercise_id' => $exercise->id, 'response' => ['text' => 'zzz']], $step)->assertJson(['correct' => false]);
    ($this->post)(['exercise_id' => $exercise->id, 'response' => LessonWorld::rightResponse($exercise)], $step)->assertJson(['correct' => false]);

    expect(LessonAnswer::query()->count())->toBe(1);
});

it('derives the attempt number on the server', function () {
    $exercise = ($this->exercise)('type_word');

    ($this->post)(['exercise_id' => $exercise->id, 'response' => ['text' => 'zzz'], 'attempt' => 9]);
    ($this->post)(['exercise_id' => $exercise->id, 'response' => LessonWorld::rightResponse($exercise)]);

    expect(LessonAnswer::query()->orderBy('id')->pluck('attempt')->all())->toBe([1, 2]);
});

it('says the run is completed on the answer that settles it', function () {
    $answers = collect($this->run->planExerciseIds())->map(fn (int $id) => LessonExercise::query()->findOrFail($id));
    $last = $answers->pop();

    foreach ($answers as $exercise) {
        ($this->post)(['exercise_id' => $exercise->id, 'response' => LessonWorld::rightResponse($exercise)])->assertJson(['run' => ['completed' => false]]);
    }

    ($this->post)(['exercise_id' => $last->id, 'response' => LessonWorld::rightResponse($last)])->assertJson(['run' => ['completed' => true]]);

    expect($this->run->fresh()->status)->toBe(LessonRunStatus::Completed);
});

it('gives a check no verdict until it completes', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $check = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);
    $exercise = LessonExercise::query()->findOrFail($check->plan[0]['id']);

    $response = ($this->post)(['exercise_id' => $exercise->id, 'response' => LessonWorld::rightResponse($exercise)], null, $check)->assertOk();

    expect($response->json())->toHaveKey('saved', true)
        ->and($response->json())->not->toHaveKeys(['correct', 'expected']);
});

it('refuses an exercise outside the run\'s plan', function () {
    $outside = LessonExercise::query()->whereNotIn('id', $this->run->planExerciseIds())->whereNull('substitute_for_id')->firstOrFail();

    ($this->post)(['exercise_id' => $outside->id, 'response' => ['text' => 'x']])->assertUnprocessable()->assertJsonValidationErrors('exercise_id');
});

it('answers 404 for another user\'s run', function () {
    $exercise = ($this->exercise)('type_word');

    $this->actingAs(User::factory()->create())
        ->postJson(route('lesson-runs.answers.store', [$this->run, (string) Str::uuid()]), ['exercise_id' => $exercise->id, 'response' => ['text' => 'x']])
        ->assertNotFound();
});

it('does not route a step that is not a uuid', function () {
    $this->actingAs($this->user)->postJson('/lesson-runs/'.$this->run->id.'/answers/not-a-uuid', [])->assertNotFound();
});

describe('validation', function () {
    it('requires an exercise', function () {
        ($this->post)([])->assertUnprocessable()->assertJsonValidationErrors('exercise_id');
    });

    it('requires answered_at to be a date', function () {
        $exercise = ($this->exercise)('type_word');

        ($this->post)(['exercise_id' => $exercise->id, 'response' => ['text' => 'x'], 'answered_at' => 'yesterday-ish'])->assertUnprocessable()->assertJsonValidationErrors('answered_at');
    });

    it('limits the length of typed text', function () {
        $exercise = ($this->exercise)('type_word');

        ($this->post)(['exercise_id' => $exercise->id, 'response' => ['text' => str_repeat('a', 1001)]])->assertUnprocessable()->assertJsonValidationErrors('response.text');
    });
});

it('clamps the answer time between the run\'s start and now instead of refusing it', function () {
    $exercise = ($this->exercise)('type_word');

    ($this->post)(['exercise_id' => $exercise->id, 'response' => ['text' => 'x'], 'answered_at' => now()->addYear()->toIso8601String()])->assertOk();

    expect(LessonAnswer::query()->sole()->answered_at->lessThanOrEqualTo(now()))->toBeTrue();
});

it('still records an answer queued before the learner switched language', function () {
    $portuguese = Language::query()->firstOrCreate(['code' => 'pt'], ['name' => 'Portuguese']);
    $this->user->forceFill(['current_language_id' => $portuguese->id])->save();
    $exercise = ($this->exercise)('type_word');

    ($this->post)(['exercise_id' => $exercise->id, 'response' => LessonWorld::rightResponse($exercise)])->assertOk()->assertJson(['correct' => true]);

    expect(Unit::query()->find($this->unit->id)->language_id)->not->toBe($portuguese->id)
        ->and(LessonAnswer::query()->count())->toBe(1);
});

it('refuses an oversized response instead of storing it', function (array $response) {
    $exercise = ($this->exercise)('type_word');

    ($this->post)(['exercise_id' => $exercise->id, 'response' => $response])->assertUnprocessable();

    expect(LessonAnswer::query()->count())->toBe(0);
})->with([
    'a long choice' => [['choice' => str_repeat('a', 501)]],
    'a long text' => [['text' => str_repeat('a', 1001)]],
    'too many wrong tiles' => [['wrong' => array_fill(0, 41, 'a')]],
    'a long wrong tile' => [['wrong' => [str_repeat('a', 201)]]],
    'too many choices' => [['choices' => array_fill(0, 21, 'a')]],
    'a long choice in a passage' => [['choices' => [str_repeat('a', 501)]]],
    'too many keys' => [array_fill_keys(array_map(fn (int $index): string => "k{$index}", range(1, 11)), 'a')],
]);

it('accepts a response at the size limits', function () {
    $exercise = ($this->exercise)('type_word');

    ($this->post)(['exercise_id' => $exercise->id, 'response' => ['text' => str_repeat('a', 1000), 'choice' => str_repeat('a', 500), 'wrong' => array_fill(0, 40, str_repeat('a', 200)), 'choices' => array_fill(0, 20, str_repeat('a', 500))]])->assertOk();
});
