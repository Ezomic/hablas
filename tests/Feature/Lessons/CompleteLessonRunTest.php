<?php

declare(strict_types=1);

use App\Actions\Lessons\CompleteLessonRun;
use App\Actions\Lessons\RecordLessonAnswer;
use App\Actions\Lessons\RecordLessonSkillScores;
use App\Actions\Lessons\SettleLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\LessonExerciseFormat;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\Skill;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\LessonSkillScore;
use App\Models\User;
use App\Models\UserSkillLevel;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

/**
 * @return array{LessonRun, array<string, int>}
 */
function settleCase(User $user, array $case): array
{
    $lesson = Lesson::factory()->stage($case['kind'] === 'check' ? LessonStage::Check : LessonStage::Sentences)->create([
        'unit_id' => LessonWorld::hotelUnit(slug: 'case-'.uniqid())->id,
    ]);
    $ids = [];

    foreach ($case['exercises'] as $exercise) {
        $ids[$exercise['id']] = LessonExercise::factory()->create([
            'lesson_id' => $lesson->id,
            'format' => $exercise['format'] ?? LessonExerciseFormat::ChooseMeaning->value,
            'substitute_for_id' => isset($exercise['substituteFor']) ? $ids[$exercise['substituteFor']] : null,
        ])->id;
    }

    $run = LessonRun::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
        'open_lesson_id' => $lesson->id,
        'kind' => $case['kind'] === 'check' ? LessonRunKind::Check : LessonRunKind::Lesson,
        'plan' => array_map(fn (string $id): array => ['id' => $ids[$id], 'origin' => 'lesson'], $case['plan']),
    ]);

    $attempts = [];

    foreach ($case['answers'] as $answer) {
        $attempts[$answer['exercise']] = ($attempts[$answer['exercise']] ?? 0) + 1;

        LessonAnswer::factory()->create([
            'attempt' => $attempts[$answer['exercise']],
            'lesson_run_id' => $run->id,
            'lesson_exercise_id' => $ids[$answer['exercise']],
            'skipped' => $answer['skipped'] ?? false,
            'is_correct' => ($answer['skipped'] ?? false) ? null : ($answer['correct'] ?? false),
            'self_graded_correct' => $answer['self'] ?? null,
            'flagged_at' => ($answer['flagged'] ?? false) ? now() : null,
        ]);
    }

    return [$run, $ids];
}

it('settles a run by the shared cases', function (array $case) {
    [$run] = settleCase($this->user, $case);

    expect((new SettleLessonRun)->handle($run))->toBe($case['settled'])
        ->and($run->fresh()->status === LessonRunStatus::Completed)->toBe($case['settled']);
})->with(array_map(
    fn (array $case): array => [$case],
    json_decode((string) file_get_contents(__DIR__.'/../../Fixtures/Lessons/settle-cases.json'), true, 512, JSON_THROW_ON_ERROR)['cases'],
));

it('is idempotent once completed', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    LessonWorld::play($this->user, $run);
    $completedAt = $run->fresh()->completed_at;

    $this->travel(1)->hour();

    expect((new SettleLessonRun)->handle($run->fresh()))->toBeTrue()
        ->and($run->fresh()->completed_at->equalTo($completedAt))->toBeTrue();
});

it('completes a run, clears its open lesson and stores its result', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $run = LessonWorld::play($this->user, $run);

    expect($run->status)->toBe(LessonRunStatus::Completed)
        ->and($run->open_lesson_id)->toBeNull()
        ->and($run->completed_at)->not->toBeNull()
        ->and($run->first_try_accuracy)->toBe(1.0)
        ->and($run->result)->toMatchArray(['mastered' => [], 'unit_completed' => false, 'milestone' => null]);

    expect((new StartLessonRun)->handle($this->user, $run->lesson)->id)->not->toBe($run->id);
});

describe('first-try accuracy', function () {
    it('excludes retried answers, and counts the mistake as missed', function () {
        $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
        $mistakes = 0;
        $run = LessonWorld::play($this->user, $run, function (LessonExercise $exercise) use (&$mistakes): bool {
            if ($exercise->format->isTeach() || $mistakes >= 2) {
                return false;
            }

            $mistakes++;

            return true;
        });

        $graded = collect($run->plan)->filter(fn (array $entry): bool => ! LessonExercise::query()->findOrFail($entry['id'])->format->isTeach())->count();

        expect($run->first_try_accuracy)->toBe(round(($graded - 2) / $graded, 4));
    });

    it('does not count a hinted answer as right first time after lesson 1, but does in lesson 1', function () {
        $meet = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
        $meet = LessonWorld::play($this->user, $meet);

        $recall = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Recall));

        foreach ($recall->planExerciseIds() as $id) {
            $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

            if ($exercise->format->isSpeaking()) {
                LessonWorld::answer($this->user, $recall, $exercise, ['skipped' => true, 'skip_reason' => 'chosen', 'response' => null]);
                $exercise = $exercise->substitute;
            }

            LessonWorld::answer($this->user, $recall, $exercise, ['hinted' => true]);
        }

        $recall = $recall->fresh();

        expect($recall->status)->toBe(LessonRunStatus::Completed)
            ->and($recall->first_try_accuracy)->toBe(0.0)
            ->and($meet->first_try_accuracy)->toBe(1.0);
    });

    it('settles an exercise on a self-check while the server\'s grade drives accuracy', function () {
        $run = LessonRun::factory()->create([
            'user_id' => $this->user->id,
            'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Sentences)->id,
            'open_lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Sentences)->id,
            'plan' => [['id' => LessonExercise::query()->where('key', 'sentences.translate.desayuno')->value('id'), 'origin' => 'lesson']],
        ]);

        $result = (new RecordLessonAnswer)->handle($this->user, $run, (string) Str::uuid(), [
            'exercise_id' => $run->plan[0]['id'],
            'response' => ['text' => 'zzz'],
            'self_graded_correct' => true,
        ]);

        expect($result['completed'])->toBeTrue()
            ->and($result['answer']->is_correct)->toBeFalse()
            ->and($run->fresh()->first_try_accuracy)->toBe(0.0);
    });

    it('leaves the warm-up and review out of the accuracy', function () {
        $run = LessonRun::factory()->create([
            'user_id' => $this->user->id,
            'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Sentences)->id,
            'open_lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Sentences)->id,
            'plan' => [
                ['id' => LessonExercise::query()->where('key', 'sentences.translate.desayuno')->value('id'), 'origin' => 'lesson'],
                ['id' => LessonExercise::query()->where('key', 'recall.type_word.el-hotel')->value('id'), 'origin' => 'warmup'],
            ],
        ]);

        LessonWorld::answer($this->user, $run, LessonExercise::query()->where('key', 'recall.type_word.el-hotel')->firstOrFail(), ['response' => ['text' => 'zzz']]);
        LessonWorld::answer($this->user, $run, LessonExercise::query()->where('key', 'recall.type_word.el-hotel')->firstOrFail());
        LessonWorld::answer($this->user, $run, LessonExercise::query()->where('key', 'sentences.translate.desayuno')->firstOrFail());

        expect($run->fresh()->first_try_accuracy)->toBe(1.0);
    });
});

describe('evidence for skill levels', function () {
    function completedLesson3(object $test): LessonRun
    {
        foreach ([LessonStage::Meet, LessonStage::Recall] as $stage) {
            LessonWorld::play($test->user, (new StartLessonRun)->handle($test->user, LessonWorld::lesson($test->unit, $stage)));
        }

        return LessonWorld::play($test->user, (new StartLessonRun)->handle($test->user, LessonWorld::lesson($test->unit, LessonStage::Sentences)));
    }

    it('gives evidence only to the first completed run of lessons 3 to 5, never to lessons 1 and 2', function () {
        $meet = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));
        $recall = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Recall)));
        $sentences = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences)));
        $replay = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences)));

        expect([$meet->counts_as_evidence, $recall->counts_as_evidence, $sentences->counts_as_evidence, $replay->counts_as_evidence])->toBe([false, false, true, false])
            ->and(LessonSkillScore::query()->where('lesson_run_id', $replay->id)->count())->toBe(0)
            ->and(LessonSkillScore::query()->where('lesson_run_id', $meet->id)->count())->toBe(0);
    });

    it('scores a skill only with at least three first-try, unhinted, unscaffolded answers', function () {
        $run = completedLesson3($this);
        $scores = LessonSkillScore::query()->where('lesson_run_id', $run->id)->get()->keyBy(fn (LessonSkillScore $score): string => $score->skill->value);

        expect($scores->keys()->all())->toBe(['writing'])
            ->and($scores['writing']->graded_count)->toBe(3)
            ->and($scores['writing']->score)->toBe(100.0);
    });

    it('leaves a skill with fewer than three graded answers unscored', function () {
        $run = completedLesson3($this);
        $scored = LessonSkillScore::query()->where('lesson_run_id', $run->id)->pluck('skill')->map(fn (Skill $skill): string => $skill->value);

        expect($scored->contains('listening'))->toBeFalse()
            ->and($scored->contains('speaking'))->toBeFalse();
    });

    it('counts toward the level only when the unit is at or above the learner\'s level in that skill', function () {
        $run = completedLesson3($this);

        expect(LessonSkillScore::query()->where('lesson_run_id', $run->id)->value('counts_toward_level'))->toBeTrue();

        LessonSkillScore::query()->delete();
        UserSkillLevel::query()->where('user_id', $this->user->id)->where('skill', Skill::Writing)->update(['cefr_level' => CefrLevel::A2]);
        $this->unit->forceFill(['cefr_level' => CefrLevel::A1])->save();

        $again = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task)));

        expect(LessonSkillScore::query()->where('lesson_run_id', $again->id)->where('skill', Skill::Writing)->value('counts_toward_level'))->toBeFalse();
    });

    it('does not count a score for a skill the learner has no level in', function () {
        UserSkillLevel::query()->where('user_id', $this->user->id)->delete();

        $run = completedLesson3($this);

        expect(LessonSkillScore::query()->where('lesson_run_id', $run->id)->where('counts_toward_level', true)->count())->toBe(0);
    });

    it('gives no evidence to a practice run, a retake, or a replay', function () {
        $retake = LessonRun::factory()->create([
            'user_id' => $this->user->id,
            'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Check)->id,
            'open_lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Check)->id,
            'kind' => LessonRunKind::Retake,
            'plan' => [['id' => LessonExercise::query()->where('key', 'check.b.type_word.el-hotel')->value('id'), 'origin' => 'lesson']],
        ]);
        LessonWorld::answer($this->user, $retake, LessonExercise::query()->where('key', 'check.b.type_word.el-hotel')->firstOrFail());

        $practice = LessonRun::factory()->create([
            'user_id' => $this->user->id,
            'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Check)->id,
            'open_lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Check)->id,
            'kind' => LessonRunKind::Practice,
            'plan' => [['id' => LessonExercise::query()->where('key', 'sentences.translate.desayuno')->value('id'), 'origin' => 'practice']],
        ]);

        expect($retake->fresh()->status)->toBe(LessonRunStatus::Completed)
            ->and($retake->fresh()->counts_as_evidence)->toBeFalse()
            ->and(LessonSkillScore::query()->count())->toBe(0)
            ->and($practice->fresh()->counts_as_evidence)->toBeFalse();
    });

    it('moves a skill level once, with the milestone in the answer and in the result', function () {
        foreach (Skill::cases() as $skill) {
            UserSkillLevel::query()->where('user_id', $this->user->id)->where('skill', $skill)->update([
                'cefr_level' => $skill === Skill::Writing ? CefrLevel::A1 : CefrLevel::A2,
                'sub_level' => $skill === Skill::Writing ? CefrSubLevel::A1_3 : CefrSubLevel::A2_1,
            ]);
        }

        LessonSkillScore::factory()->count(9)->create([
            'user_id' => $this->user->id,
            'language_id' => $this->unit->language_id,
            'skill' => Skill::Writing,
            'score' => 100.0,
            'counts_toward_level' => true,
            'scored_at' => now()->subDay(),
        ]);

        foreach ([LessonStage::Meet, LessonStage::Recall] as $stage) {
            LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, $stage)));
        }

        $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences));
        $last = null;

        foreach ($run->planExerciseIds() as $id) {
            $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

            if ($exercise->format->isSpeaking()) {
                LessonWorld::answer($this->user, $run, $exercise, ['skipped' => true, 'skip_reason' => 'chosen', 'response' => null]);
                $exercise = $exercise->substitute;
            }

            $last = LessonWorld::answer($this->user, $run, $exercise);
        }

        $writing = UserSkillLevel::query()->where('user_id', $this->user->id)->where('skill', Skill::Writing)->firstOrFail();

        expect($writing->cefr_level)->toBe(CefrLevel::A2)
            ->and($last['completed'])->toBeTrue()
            ->and($last['milestone'])->toBe(['type' => 'milestone', 'message' => "You've reached A2 in Spanish!"])
            ->and($run->fresh()->result['milestone']['message'])->toBe("You've reached A2 in Spanish!");
    });
});

describe('the minimum of graded answers for a skill score', function () {
    it('scores a skill on three graded answers and not on two', function (int $answers, int $scores) {
        $keys = array_slice(['sentences.translate.desayuno', 'sentences.type_gap.llave', 'task.transform.plural'], 0, $answers);
        $lesson = LessonWorld::lesson($this->unit, LessonStage::Sentences);
        $run = LessonRun::factory()->create([
            'user_id' => $this->user->id,
            'lesson_id' => $lesson->id,
            'open_lesson_id' => $lesson->id,
            'plan' => array_map(fn (string $key): array => ['id' => LessonExercise::query()->where('key', $key)->value('id'), 'origin' => 'lesson'], $keys),
        ]);

        foreach ($keys as $key) {
            LessonWorld::answer($this->user, $run, LessonExercise::query()->where('key', $key)->firstOrFail());
        }

        expect($run->fresh()->status)->toBe(LessonRunStatus::Completed)
            ->and(LessonSkillScore::query()->where('lesson_run_id', $run->id)->count())->toBe($scores);
    })->with([
        'two answers' => [2, 0],
        'three answers' => [3, 1],
    ]);
});

describe('a run that is completed twice', function () {
    it('returns the stored result and writes no second set of skill scores', function () {
        foreach ([LessonStage::Meet, LessonStage::Recall] as $stage) {
            LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, $stage)));
        }

        $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences)));
        $scores = LessonSkillScore::query()->count();
        $completedAt = $run->completed_at;

        $this->travel(1)->hour();
        $result = (new CompleteLessonRun)->handle($run->fresh());

        expect($result)->toBe($run->result)
            ->and(LessonSkillScore::query()->count())->toBe($scores)
            ->and($run->fresh()->completed_at->equalTo($completedAt))->toBeTrue();
    });

    it('keeps one score per skill when scoring is retried for the same run', function () {
        foreach ([LessonStage::Meet, LessonStage::Recall] as $stage) {
            LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, $stage)));
        }

        $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences)));

        (new RecordLessonSkillScores)->handle($run);

        expect(LessonSkillScore::query()->where('lesson_run_id', $run->id)->count())->toBe(1);
    });
});

it('refuses a second answer with the same attempt number for an exercise in a run', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $exercise = LessonExercise::query()->findOrFail($run->plan[0]['id']);
    LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $exercise->id, 'attempt' => 1]);

    LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $exercise->id, 'attempt' => 1]);
})->throws(UniqueConstraintViolationException::class);

it('rolls back everything its own transaction wrote when completing fails', function () {
    foreach ([LessonStage::Meet, LessonStage::Recall] as $stage) {
        LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, $stage)));
    }

    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences));

    foreach ($run->planExerciseIds() as $id) {
        $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

        if ($exercise->format->isSpeaking()) {
            LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $id, 'skipped' => true, 'is_correct' => null]);
            $exercise = $exercise->substitute;
        }

        LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $exercise->id, 'is_correct' => true, 'response' => LessonWorld::rightResponse($exercise)]);
    }

    LessonRun::saving(function (LessonRun $saving): void {
        if ($saving->status === LessonRunStatus::Completed) {
            throw new RuntimeException('boom');
        }
    });

    expect(fn () => (new CompleteLessonRun)->handle($run->fresh()))->toThrow(RuntimeException::class)
        ->and(LessonSkillScore::query()->count())->toBe(0)
        ->and($run->fresh()->status)->toBe(LessonRunStatus::InProgress);
});
