<?php

declare(strict_types=1);

use App\Models\GrammarPoint;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonExerciseTarget;
use App\Models\LessonRun;
use App\Models\LessonSkillScore;
use App\Models\UnitItemMastery;
use App\Models\VocabularyItem;
use Illuminate\Support\Facades\DB;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

it('relates a unit to its lessons in order, and a lesson to its exercises and runs', function () {
    $lesson = Lesson::query()->where('unit_id', $this->unit->id)->orderBy('position')->firstOrFail();
    $run = LessonRun::factory()->create(['user_id' => $this->user->id, 'lesson_id' => $lesson->id]);

    expect($this->unit->lessons->pluck('position')->all())->toBe([1, 2, 3, 4, 5])
        ->and($lesson->unit->is($this->unit))->toBeTrue()
        ->and($lesson->exercises->first()->position)->toBe(1)
        ->and($lesson->runs->first()->is($run))->toBeTrue()
        ->and($this->user->lessonRuns->first()->is($run))->toBeTrue();
});

it('relates an exercise to its targets, its substitute and its original', function () {
    $original = LessonExercise::query()->where('key', 'sentences.listen_type.reserva')->firstOrFail();
    $substitute = LessonExercise::query()->where('key', 'sentences.listen_type.reserva.sub')->firstOrFail();
    $grammar = GrammarPoint::query()->where('unit_id', $this->unit->id)->firstOrFail();

    expect($original->lesson->unit->is($this->unit))->toBeTrue()
        ->and($original->vocabularyItems->pluck('term')->sort()->values()->all())->toBe(['la noche', 'la reserva'])
        ->and((bool) $original->vocabularyItems->first()->pivot->is_probe)->toBeFalse()
        ->and($original->grammarPoints->first()->is($grammar))->toBeTrue()
        ->and((bool) $original->grammarPoints->first()->pivot->is_contrast)->toBeTrue()
        ->and($original->targets)->toHaveCount(3)
        ->and($original->targets->first()->lessonExercise->is($original))->toBeTrue()
        ->and($original->targets->first()->targetable)->not->toBeNull()
        ->and($original->substitute->is($substitute))->toBeTrue()
        ->and($original->isSubstitute())->toBeFalse()
        ->and($substitute->original->is($original))->toBeTrue()
        ->and($substitute->isSubstitute())->toBeTrue();
});

it('relates an answer to its run, its exercise and its target verdicts', function () {
    $exercise = LessonExercise::query()->where('key', 'sentences.translate.desayuno')->firstOrFail();
    $run = LessonRun::factory()->create(['user_id' => $this->user->id, 'lesson_id' => $exercise->lesson_id]);
    $answer = LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $exercise->id]);
    $item = VocabularyItem::query()->where('term', 'incluido')->firstOrFail();
    $point = GrammarPoint::query()->firstOrFail();

    DB::table('lesson_answer_targets')->insert([
        ['lesson_answer_id' => $answer->id, 'targetable_type' => $item->getMorphClass(), 'targetable_id' => $item->id, 'is_correct' => false],
        ['lesson_answer_id' => $answer->id, 'targetable_type' => $point->getMorphClass(), 'targetable_id' => $point->id, 'is_correct' => true],
    ]);

    expect($answer->lessonRun->is($run))->toBeTrue()
        ->and($answer->lessonExercise->is($exercise))->toBeTrue()
        ->and($answer->vocabularyItems->first()->pivot->is_correct)->toBe(0)
        ->and($answer->grammarPoints->first()->is($point))->toBeTrue()
        ->and($run->answers->first()->is($answer))->toBeTrue();
});

it('relates a mastery to its learner, unit, item and run', function () {
    $item = VocabularyItem::query()->firstOrFail();
    $run = LessonRun::factory()->create(['user_id' => $this->user->id, 'lesson_id' => Lesson::query()->firstOrFail()->id]);
    $mastery = UnitItemMastery::factory()->create([
        'user_id' => $this->user->id,
        'unit_id' => $this->unit->id,
        'masterable_type' => $item->getMorphClass(),
        'masterable_id' => $item->id,
        'lesson_run_id' => $run->id,
    ]);

    expect($mastery->user->is($this->user))->toBeTrue()
        ->and($mastery->unit->is($this->unit))->toBeTrue()
        ->and($mastery->masterable->is($item))->toBeTrue()
        ->and($mastery->lessonRun->is($run))->toBeTrue()
        ->and($this->user->itemMasteries->first()->is($mastery))->toBeTrue();
});

it('relates a skill score to its learner, language and run', function () {
    $run = LessonRun::factory()->create(['user_id' => $this->user->id, 'lesson_id' => Lesson::query()->firstOrFail()->id]);
    $score = LessonSkillScore::factory()->create(['user_id' => $this->user->id, 'language_id' => $this->unit->language_id, 'lesson_run_id' => $run->id]);

    expect($score->user->is($this->user))->toBeTrue()
        ->and($score->language->is($this->unit->language))->toBeTrue()
        ->and($score->lessonRun->is($run))->toBeTrue();
});

it('relates a run to its learner and lists the exercise ids of its plan', function () {
    $run = LessonRun::factory()->create([
        'user_id' => $this->user->id,
        'lesson_id' => Lesson::query()->firstOrFail()->id,
        'plan' => [['id' => 4, 'origin' => 'lesson'], ['id' => 2, 'origin' => 'warmup']],
    ]);

    expect($run->user->is($this->user))->toBeTrue()
        ->and($run->planExerciseIds())->toBe([4, 2]);
});

it('exposes an exercise target as a model with its probe and contrast flags', function () {
    $target = LessonExerciseTarget::query()->where('is_contrast', true)->firstOrFail();

    expect($target->is_probe)->toBeBool()
        ->and($target->is_contrast)->toBeTrue();
});
