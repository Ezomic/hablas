<?php

declare(strict_types=1);

use App\Actions\Lessons\RecordLessonAnswer;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\ErrorTagCategory;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Models\Language;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\User;
use App\Models\VocabularyItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

/** @param  list<string>  $keys */
function runOver(User $user, LessonStage $stage, array $keys, LessonRunKind $kind = LessonRunKind::Lesson): LessonRun
{
    $exercises = LessonExercise::query()->whereIn('key', $keys)->get()->sortBy(fn (LessonExercise $exercise): int => array_search($exercise->key, $keys, true));

    return LessonRun::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $exercises->first()->lesson_id,
        'open_lesson_id' => $exercises->first()->lesson_id,
        'kind' => $kind,
        'plan' => $exercises->map(fn (LessonExercise $exercise): array => ['id' => $exercise->id, 'origin' => 'lesson'])->values()->all(),
    ]);
}

function send(User $user, LessonRun $run, string $key, array $input = [], ?string $step = null): array
{
    $exercise = LessonExercise::query()->where('key', $key)->firstOrFail();

    return (new RecordLessonAnswer)->handle($user, $run, $step ?? (string) Str::uuid(), ['exercise_id' => $exercise->id, ...$input]);
}

it('stores an answer with the server\'s grade and per-target verdicts', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);

    $result = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'El desayuno está inclduido']]);
    $answer = $result['answer'];
    $verdicts = DB::table('lesson_answer_targets')->where('lesson_answer_id', $answer->id)->orderBy('targetable_id')->get();
    $wrong = $verdicts->where('is_correct', 0);

    expect($answer->is_correct)->toBeFalse()
        ->and($answer->attempt)->toBe(1)
        ->and($verdicts)->toHaveCount(3)
        ->and($wrong)->toHaveCount(1)
        ->and($wrong->first()->targetable_id)->toBe(VocabularyItem::query()->where('term', 'incluido')->firstOrFail()->id)
        ->and($result['grade']->expected)->toBe('El desayuno está incluido.')
        ->and($result['completed'])->toBeFalse();
});

it('derives the attempt on the server from the answers already stored', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);

    $first = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no'], 'attempt' => 9]);
    $second = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no']]);
    $third = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'El desayuno está incluido']]);

    expect([$first['answer']->attempt, $second['answer']->attempt, $third['answer']->attempt])->toBe([1, 2, 3])
        ->and($third['completed'])->toBeTrue();
});

it('treats a repeat of a step as the same answer and records nothing new', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno', 'sentences.type_gap.llave']);
    $step = (string) Str::uuid();

    $first = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'El desayuno está incluido']], $step);
    $again = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'something else']], $step);

    expect($again['repeat'])->toBeTrue()
        ->and($again['answer']->id)->toBe($first['answer']->id)
        ->and($again['grade']->correct)->toBeTrue()
        ->and(LessonAnswer::query()->count())->toBe(1)
        ->and(DB::table('lesson_answer_targets')->count())->toBe(3);
});

it('settles the run again on a repeated step, so a run whose completion failed finishes', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);
    $exercise = LessonExercise::query()->where('key', 'sentences.translate.desayuno')->firstOrFail();
    $answer = LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $exercise->id, 'response' => ['text' => 'El desayuno está incluido'], 'is_correct' => true]);

    expect($run->fresh()->status)->toBe(LessonRunStatus::InProgress);

    $result = (new RecordLessonAnswer)->handle($this->user, $run, $answer->step, ['exercise_id' => $exercise->id]);

    expect($result['completed'])->toBeTrue()
        ->and($run->fresh()->status)->toBe(LessonRunStatus::Completed);
});

it('rolls the answer back when anything after the insert fails, and the retry completes the run', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);
    $failing = true;

    LessonAnswer::created(function () use (&$failing): void {
        if ($failing) {
            throw new RuntimeException('boom');
        }
    });

    $step = (string) Str::uuid();

    expect(fn () => send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'El desayuno está incluido']], $step))->toThrow(RuntimeException::class)
        ->and(LessonAnswer::query()->count())->toBe(0)
        ->and(DB::table('lesson_answer_targets')->count())->toBe(0)
        ->and($run->fresh()->status)->toBe(LessonRunStatus::InProgress);

    $failing = false;
    $result = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'El desayuno está incluido']], $step);

    expect($result['completed'])->toBeTrue()
        ->and($run->fresh()->status)->toBe(LessonRunStatus::Completed);
});

it('records an answer to the substitute of a plan exercise but not to an exercise outside the plan', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.listen_type.reserva']);

    send($this->user, $run, 'sentences.listen_type.reserva', ['skipped' => true, 'skip_reason' => 'chosen', 'response' => null]);
    $substitute = send($this->user, $run, 'sentences.listen_type.reserva.sub', ['response' => ['text' => 'La reserva es para dos noches']]);

    expect($substitute['answer']->is_correct)->toBeTrue()
        ->and($substitute['completed'])->toBeTrue();

    $other = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);

    expect(fn () => send($this->user, $other, 'sentences.type_gap.llave', ['response' => ['text' => 'está']]))->toThrow(ValidationException::class);
});

it('stores a skipped answer without a grade', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.listen_type.reserva']);

    $answer = send($this->user, $run, 'sentences.listen_type.reserva', ['skipped' => true, 'skip_reason' => 'paused', 'response' => null])['answer'];

    expect($answer->skipped)->toBeTrue()
        ->and($answer->is_correct)->toBeNull()
        ->and($answer->skip_reason->value)->toBe('paused')
        ->and(DB::table('lesson_answer_targets')->count())->toBe(0)
        ->and($run->fresh()->status)->toBe(LessonRunStatus::InProgress);
});

describe('skipping', function () {
    it('refuses to skip an exercise that is not listening or speaking, and stores nothing', function (string $stage, string $key) {
        $run = runOver($this->user, LessonStage::from($stage), [$key]);

        try {
            send($this->user, $run, $key, ['skipped' => true, 'skip_reason' => 'chosen', 'response' => null]);
        } catch (ValidationException $exception) {
            expect($exception->errors())->toHaveKey('lesson');
        }

        expect(isset($exception))->toBeTrue()
            ->and(LessonAnswer::query()->count())->toBe(0)
            ->and($run->fresh()->status)->toBe(LessonRunStatus::InProgress);
    })->with([
        'a typed word in a lesson' => ['recall', 'recall.type_word.el-desayuno'],
        'a translation' => ['sentences', 'sentences.translate.desayuno'],
    ]);

    it('refuses to skip a generated typed-recall probe of a check, so it cannot settle the run', function () {
        $run = runOver($this->user, LessonStage::Check, ['check.a.type_word.la-llave'], LessonRunKind::Check);

        expect(fn () => send($this->user, $run, 'check.a.type_word.la-llave', ['skipped' => true, 'skip_reason' => 'chosen', 'response' => null]))->toThrow(ValidationException::class)
            ->and($run->fresh()->status)->toBe(LessonRunStatus::InProgress);
    });

    it('refuses to skip a listening exercise that has no substitute', function () {
        $run = runOver($this->user, LessonStage::Sentences, ['sentences.listen_type.reserva']);
        LessonExercise::query()->where('key', 'sentences.listen_type.reserva.sub')->delete();

        expect(fn () => send($this->user, $run, 'sentences.listen_type.reserva', ['skipped' => true, 'skip_reason' => 'chosen', 'response' => null]))->toThrow(ValidationException::class)
            ->and(LessonAnswer::query()->count())->toBe(0);
    });

    it('refuses to skip a listening exercise whose substitute is retired', function () {
        $run = runOver($this->user, LessonStage::Sentences, ['sentences.listen_type.reserva']);
        LessonExercise::query()->where('key', 'sentences.listen_type.reserva.sub')->update(['retired_at' => now()]);

        expect(fn () => send($this->user, $run, 'sentences.listen_type.reserva', ['skipped' => true, 'skip_reason' => 'chosen', 'response' => null]))->toThrow(ValidationException::class);
    });
});

it('answers 404 for another learner\'s run', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);

    expect(fn () => send(LessonWorld::learner(), $run, 'sentences.translate.desayuno', ['response' => ['text' => 'x']]))->toThrow(function (HttpException $exception) {
        expect($exception->getStatusCode())->toBe(404);
    });
});

it('refuses a step that belongs to another run, and a new step on a completed run', function () {
    $first = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);
    $second = runOver($this->user, LessonStage::Task, ['task.transform.plural']);
    $step = (string) Str::uuid();

    send($this->user, $first, 'sentences.translate.desayuno', ['response' => ['text' => 'no']], $step);

    expect(fn () => send($this->user, $second, 'task.transform.plural', ['response' => ['text' => 'no']], $step))->toThrow(ValidationException::class);

    $first->forceFill(['status' => LessonRunStatus::Completed, 'open_lesson_id' => null])->save();

    expect(fn () => send($this->user, $first, 'sentences.translate.desayuno', ['response' => ['text' => 'no']]))->toThrow(ValidationException::class);
});

it('still records an answer queued before a language switch', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);
    $portuguese = Language::factory()->create(['code' => 'pt']);
    $this->user->unlockedLanguages()->attach($portuguese->id);
    $this->user->forceFill(['current_language_id' => $portuguese->id])->save();

    $result = send($this->user->fresh(), $run, 'sentences.translate.desayuno', ['response' => ['text' => 'El desayuno está incluido']]);

    expect($result['answer']->is_correct)->toBeTrue();
});

it('clamps answered_at between the start of the run and now instead of refusing the answer', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);

    $early = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no'], 'answered_at' => now()->subYear()->toIso8601String()])['answer'];
    $late = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no'], 'answered_at' => now()->addYear()->toIso8601String()])['answer'];
    $fine = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no'], 'answered_at' => now()->subSecond()->toIso8601String()])['answer'];

    expect($early->answered_at->timestamp)->toBe($run->fresh()->started_at->timestamp)
        ->and($late->answered_at->timestamp)->toBeLessThanOrEqual(now()->timestamp)
        ->and($late->answered_at->timestamp)->toBeGreaterThan(now()->subMinute()->timestamp)
        ->and($fine->answered_at->timestamp)->toBeLessThanOrEqual($late->answered_at->timestamp);
});

it('records streak activity once a day', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);

    send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no']]);
    send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no']]);

    expect($this->user->streak()->first()->current_length)->toBe(1);
});

describe('error tags', function () {
    it('tags the wrong gender, the grammar point\'s category, and only on the first try', function () {
        $run = runOver($this->user, LessonStage::Recall, ['recall.type_word.el-desayuno', 'recall.choose_gap.bano-aqui']);

        $article = send($this->user, $run, 'recall.type_word.el-desayuno', ['response' => ['text' => 'la desayuno']])['answer'];
        $retry = send($this->user, $run, 'recall.type_word.el-desayuno', ['response' => ['text' => 'la desayuno']])['answer'];
        $grammar = send($this->user, $run, 'recall.choose_gap.bano-aqui', ['response' => ['choice' => 'es']])['answer'];

        expect($article->error_tag_category)->toBe(ErrorTagCategory::WrongGender)
            ->and($article->note)->toBe('article')
            ->and($retry->error_tag_category)->toBeNull()
            ->and($grammar->error_tag_category)->toBe(ErrorTagCategory::SerEstarConfusion);
    });

    it('tags nothing on a right answer', function () {
        $run = runOver($this->user, LessonStage::Recall, ['recall.type_word.el-desayuno']);

        expect(send($this->user, $run, 'recall.type_word.el-desayuno', ['response' => ['text' => 'el desayuno']])['answer']->error_tag_category)->toBeNull();
    });
});

describe('accents in a run', function () {
    it('is right first time in lesson 2, accepted with a note in lesson 3, wrong in the check', function () {
        $recall = runOver($this->user, LessonStage::Recall, ['recall.type_word.la-habitacion']);
        $task = runOver($this->user, LessonStage::Task, ['task.transform.plural']);
        $otherLearner = LessonWorld::learner();
        $otherTask = runOver($otherLearner, LessonStage::Task, ['task.transform.plural']);
        $check = runOver($this->user, LessonStage::Check, ['check.a.translate.0'], LessonRunKind::Check);

        $lesson2 = send($this->user, $recall, 'recall.type_word.la-habitacion', ['response' => ['text' => 'la habitacion']])['answer'];
        $lesson4 = send($this->user, $task, 'task.transform.plural', ['response' => ['text' => 'Las habitaciones estan disponibles']])['answer'];
        $wrongOther = send($otherLearner, $otherTask, 'task.transform.plural', ['response' => ['text' => 'Las habitaciones esta disponibles']])['answer'];
        $inCheck = send($this->user, $check, 'check.a.translate.0', ['response' => ['text' => 'El baño está en la habitacion']])['answer'];

        expect([$lesson2->is_correct, $lesson2->note])->toBe([true, 'accent'])
            ->and([$lesson4->is_correct, $lesson4->note])->toBe([true, 'accent'])
            ->and($wrongOther->is_correct)->toBeFalse()
            ->and($inCheck->is_correct)->toBeFalse();
    });
});

it('returns no verdict in a check-kind run, though the answer is stored and graded', function () {
    $run = runOver($this->user, LessonStage::Check, ['check.a.translate.0'], LessonRunKind::Check);

    $result = send($this->user, $run, 'check.a.translate.0', ['response' => ['text' => 'El baño está en la habitación']]);

    expect($result['grade'])->toBeNull()
        ->and($result['answer']->is_correct)->toBeTrue()
        ->and($result['answer']->answered_at)->not->toBeNull();
});

it('keeps hinted and self-checked flags as the device sent them', function () {
    $run = runOver($this->user, LessonStage::Sentences, ['sentences.translate.desayuno']);

    $answer = send($this->user, $run, 'sentences.translate.desayuno', ['response' => ['text' => 'no'], 'hinted' => true, 'self_graded_correct' => true])['answer'];

    expect($answer->hinted)->toBeTrue()
        ->and($answer->self_graded_correct)->toBeTrue()
        ->and($answer->is_correct)->toBeFalse();
});

it('records a run end to end through StartLessonRun', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $exercise = LessonExercise::query()->findOrFail($run->plan[0]['id']);

    $result = LessonWorld::answer($this->user, $run, $exercise);

    expect($result['answer']->is_correct)->toBeTrue()
        ->and($result['run']->id)->toBe($run->id);
});
