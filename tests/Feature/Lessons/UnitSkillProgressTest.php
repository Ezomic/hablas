<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\Skill;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Services\UnitSkillProgress;
use Illuminate\Support\Facades\DB;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    $this->run = LessonRun::factory()->create(['user_id' => $this->user->id, 'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Recall)->id]);
});

function rows(object $test): array
{
    return collect((new UnitSkillProgress)->handle($test->user, $test->unit))->keyBy('skill')->all();
}

function answerExercise(object $test, LessonExercise $exercise, bool $correct, bool $skipped = false): void
{
    $attempt = LessonAnswer::query()->where('lesson_run_id', $test->run->id)->where('lesson_exercise_id', $exercise->id)->count() + 1;
    $answer = LessonAnswer::factory()->create(['lesson_run_id' => $test->run->id, 'lesson_exercise_id' => $exercise->id, 'attempt' => $attempt, 'is_correct' => $correct, 'skipped' => $skipped]);

    foreach ($exercise->targets as $target) {
        DB::table('lesson_answer_targets')->insert(['lesson_answer_id' => $answer->id, 'targetable_type' => $target->targetable_type, 'targetable_id' => $target->targetable_id, 'is_correct' => $correct]);
    }
}

function exerciseOfSkill(object $test, Skill $skill): LessonExercise
{
    return LessonExercise::query()
        ->whereNull('retired_at')->whereNull('substitute_for_id')->whereNull('probe_set')
        ->whereHas('lesson', fn ($query) => $query->where('unit_id', $test->unit->id))
        ->with('targets')->get()
        ->first(fn (LessonExercise $exercise): bool => $exercise->format->skill() === $skill && $exercise->targets->isNotEmpty());
}

it('starts every skill at nothing done, with every skill the unit trains having items', function () {
    foreach (rows($this) as $row) {
        expect($row['done'])->toBe(0)
            ->and($row['total'])->toBeGreaterThanOrEqual(0);
    }

    expect(rows($this)['writing']['total'])->toBeGreaterThan(0);
});

it('counts an item in the skill it was answered right in, and only that skill', function () {
    $writing = exerciseOfSkill($this, Skill::Writing);
    answerExercise($this, $writing, true);

    $rows = rows($this);

    expect($rows['writing']['done'])->toBeGreaterThan(0)
        ->and($rows['reading']['done'])->toBe(0);
});

it('takes the latest answer, and ignores skipped ones', function () {
    $writing = exerciseOfSkill($this, Skill::Writing);
    answerExercise($this, $writing, true);
    $done = rows($this)['writing']['done'];

    answerExercise($this, $writing, false);

    expect(rows($this)['writing']['done'])->toBeLessThan($done);

    answerExercise($this, $writing, true, skipped: true);

    expect(rows($this)['writing']['done'])->toBeLessThan($done);
});

it('starts a practice run of just the exercises of one skill, on the items not done yet', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    answerExercise($this, exerciseOfSkill($this, Skill::Writing), false);

    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Practice, Skill::Writing);
    $formats = LessonExercise::query()->whereIn('id', array_column($run->plan, 'id'))->get()->map(fn (LessonExercise $exercise): ?Skill => $exercise->format->skill())->unique()->values()->all();

    expect($run->kind)->toBe(LessonRunKind::Practice)
        ->and($formats)->toBe([Skill::Writing]);
});
