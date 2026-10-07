<?php

declare(strict_types=1);

use App\Actions\Lessons\CompleteLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonExerciseFormat;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\Skill;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\UnitSkillMastery;
use App\Services\UnitCheckRequirements;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    UnitSkillMastery::query()->delete();
    $this->check = LessonWorld::lesson($this->unit, LessonStage::Check);
});

function startSkillTest(object $test, Skill $skill)
{
    return (new StartLessonRun)->handle($test->user, $test->check, LessonRunKind::SkillTest, $skill);
}

it('tests one skill with exercises of that skill only, giving no feedback', function () {
    $run = startSkillTest($this, Skill::Writing);
    $skills = LessonExercise::query()->whereIn('id', array_column($run->plan, 'id'))->get()->map(fn (LessonExercise $exercise): ?Skill => $exercise->format->skill())->unique()->values()->all();

    expect($run->kind)->toBe(LessonRunKind::SkillTest)
        ->and($run->kind->isCheck())->toBeTrue()
        ->and($run->kind->provesUnit())->toBeFalse()
        ->and($skills)->toBe([Skill::Writing]);
});

it('masters the skill when every answer is right', function () {
    $run = LessonWorld::play($this->user, startSkillTest($this, Skill::Writing));

    $result = (new CompleteLessonRun)->handle($run);

    expect($result['skill_mastered'])->toBeTrue()
        ->and($result['missing'])->toBe([])
        ->and(UnitSkillMastery::query()->where('skill', Skill::Writing)->count())->toBe(1);
});

it('allows no mistakes: one wrong answer masters nothing and names the item to practise', function () {
    $first = null;
    $run = LessonWorld::play($this->user, startSkillTest($this, Skill::Writing), function (LessonExercise $exercise) use (&$first): bool {
        $first ??= $exercise->id;

        return $exercise->id === $first;
    });

    $result = (new CompleteLessonRun)->handle($run);

    expect($result['skill_mastered'])->toBeFalse()
        ->and($result['missing'])->not->toBe([])
        ->and(UnitSkillMastery::query()->count())->toBe(0);
});

it('refuses a final test until the skill is trained to 100%, and once it is mastered', function () {
    $writing = LessonExercise::query()->with('targets')->where('format', LessonExerciseFormat::TypeWord)->whereNull('substitute_for_id')->whereNull('probe_set')->firstOrFail();
    $run = LessonRun::factory()->create(['user_id' => $this->user->id, 'lesson_id' => $writing->lesson_id]);
    $answer = LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $writing->id, 'attempt' => 9, 'is_correct' => false]);

    foreach ($writing->targets as $target) {
        DB::table('lesson_answer_targets')->insert(['lesson_answer_id' => $answer->id, 'targetable_type' => $target->targetable_type, 'targetable_id' => $target->targetable_id, 'is_correct' => false]);
    }

    expect(fn () => startSkillTest($this, Skill::Writing))->toThrow(ValidationException::class);

    UnitSkillMastery::query()->create(['user_id' => $this->user->id, 'unit_id' => $this->unit->id, 'skill' => Skill::Reading, 'mastered_at' => now()]);

    expect(fn () => startSkillTest($this, Skill::Reading))->toThrow(ValidationException::class);
});

it('opens the unit check only when every lesson is at 100% and every skill is mastered', function () {
    $requirements = (new UnitCheckRequirements)->handle($this->user, $this->unit);

    expect($requirements['lessonsMastered'])->toBe($requirements['lessonsTotal'])
        ->and($requirements['skillsMastered'])->toBe(0)
        ->and($requirements['met'])->toBeFalse();

    LessonWorld::masterEverySkill($this->user, $this->unit);

    expect((new UnitCheckRequirements)->handle($this->user, $this->unit)['met'])->toBeTrue();
});
