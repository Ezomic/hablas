<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Lessons\TargetRef;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\VocabularyItem;
use App\Services\UnitStruggles;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    $this->key = VocabularyItem::query()->where('unit_id', $this->unit->id)->where('term', 'la llave')->firstOrFail();
    $this->run = LessonRun::factory()->create(['user_id' => $this->user->id, 'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Recall)->id]);
});

function answerOn(object $test, VocabularyItem $item, bool $correct, bool $skipped = false): void
{
    $answer = LessonAnswer::factory()->create([
        'lesson_run_id' => $test->run->id,
        'lesson_exercise_id' => LessonExercise::factory()->create(['lesson_id' => $test->run->lesson_id])->id,
        'is_correct' => $correct,
        'skipped' => $skipped,
    ]);

    DB::table('lesson_answer_targets')->insert([
        'lesson_answer_id' => $answer->id,
        'targetable_type' => (new VocabularyItem)->getMorphClass(),
        'targetable_id' => $item->id,
        'is_correct' => $correct,
    ]);
}

it('has no struggles before anything was answered', function () {
    expect((new UnitStruggles)->handle($this->user, $this->unit))->toBe([]);
});

it('counts a word whose latest answer was wrong, and clears it with one right answer', function () {
    answerOn($this, $this->key, false);

    expect(array_map(fn (TargetRef $ref): int => $ref->id, (new UnitStruggles)->handle($this->user, $this->unit)))->toBe([$this->key->id]);

    answerOn($this, $this->key, true);

    expect((new UnitStruggles)->handle($this->user, $this->unit))->toBe([]);

    answerOn($this, $this->key, false);

    expect((new UnitStruggles)->handle($this->user, $this->unit))->toHaveCount(1);
});

it('ignores skipped answers and other learners', function () {
    answerOn($this, $this->key, true);
    answerOn($this, $this->key, false, skipped: true);

    expect((new UnitStruggles)->handle($this->user, $this->unit))->toBe([])
        ->and((new UnitStruggles)->handle(LessonWorld::learner(), $this->unit))->toBe([]);
});

it('describes the struggles for the unit page', function () {
    answerOn($this, $this->key, false);

    $described = (new UnitStruggles)->describe($this->user, $this->unit);

    expect($described['count'])->toBe(1)
        ->and($described['items'])->toBe([['label' => 'la llave', 'meaning' => 'key']])
        ->and($described['lessonId'])->toBe(LessonWorld::lesson($this->unit, LessonStage::Check)->id);
});

it('starts a practice run of just the struggling words, with no check taken first', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    answerOn($this, $this->key, false);

    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Practice);

    $exercises = LessonExercise::query()->whereIn('id', array_column($run->plan, 'id'))->with('targets')->get();

    expect($run->kind)->toBe(LessonRunKind::Practice)
        ->and($exercises)->not->toBeEmpty()
        ->and($exercises->every(fn (LessonExercise $exercise): bool => $exercise->targets->pluck('targetable_id')->contains($this->key->id)))->toBeTrue();
});

it('refuses a practice run when nothing is a struggle', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);

    expect(fn () => (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Practice))
        ->toThrow(ValidationException::class);
});
