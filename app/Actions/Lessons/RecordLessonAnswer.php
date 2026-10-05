<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\LessonExerciseFormat;
use App\Enums\LessonRunStatus;
use App\Enums\SkipReason;
use App\Lessons\Grade;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Services\TypingSupport;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class RecordLessonAnswer
{
    public function __construct(
        private readonly GradeLessonAnswer $gradeLessonAnswer = new GradeLessonAnswer,
        private readonly SettleLessonRun $settleLessonRun = new SettleLessonRun,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
        private readonly TypingSupport $typingSupport = new TypingSupport,
    ) {}

    /**
     * Stores one answer, grades it, writes its target verdicts and error tag,
     * records streak activity and settles the run, all in one transaction. If
     * anything throws, nothing is stored and the device keeps the answer
     * queued, so the next try runs everything again.
     *
     * The server trusts the device's hinted, skipped and self-checked flags,
     * since the learner is the only one who gains from honest answers. It does
     * not trust the order of tries: the attempt is derived here.
     *
     * Only a listening or speaking exercise that has a substitute can be
     * skipped, so a skip never removes a probe from a check.
     *
     * A repeat of a step returns the stored verdict, records nothing new and
     * settles the run again, so a run whose completion once failed finishes on
     * the next replay.
     *
     * @param  array{exercise_id: int, hinted?: bool, skipped?: bool, skip_reason?: string|null, response?: array<string, mixed>|null, self_graded_correct?: bool|null, answered_at?: string|null}  $input
     * @return array{answer: LessonAnswer, grade: Grade|null, run: LessonRun, completed: bool, milestone: array{type: string, message: string}|null, repeat: bool}
     */
    public function handle(User $user, LessonRun $run, string $step, array $input): array
    {
        abort_unless($run->user_id === $user->id, 404);

        return DB::transaction(function () use ($user, $run, $step, $input): array {
            $existing = LessonAnswer::query()->where('step', $step)->first();

            if ($existing !== null) {
                if ($existing->lesson_run_id !== $run->id) {
                    throw ValidationException::withMessages(['step' => [__('This step belongs to another run.')]]);
                }

                return $this->result($run, $existing, true, null);
            }

            if ($run->status === LessonRunStatus::Completed) {
                throw ValidationException::withMessages(['run' => [__('This lesson is already completed.')]]);
            }

            $exercise = $this->exercise($run, $input['exercise_id']);
            $skipped = (bool) ($input['skipped'] ?? false);
            $response = $input['response'] ?? null;

            if ($skipped) {
                $this->assertSkippable($exercise);
            }

            $grade = $skipped ? null : $this->gradeLessonAnswer->handle($exercise, $response ?? []);
            $attempt = LessonAnswer::query()->where('lesson_run_id', $run->id)->where('lesson_exercise_id', $exercise->id)->count() + 1;

            $answer = LessonAnswer::query()->create([
                'lesson_run_id' => $run->id,
                'lesson_exercise_id' => $exercise->id,
                'step' => $step,
                'attempt' => $attempt,
                'hinted' => (bool) ($input['hinted'] ?? false),
                'skipped' => $skipped,
                'skip_reason' => $skipped ? SkipReason::from($input['skip_reason'] ?? SkipReason::Chosen->value) : null,
                'response' => $response,
                'is_correct' => $grade?->correct,
                'self_graded_correct' => $input['self_graded_correct'] ?? null,
                'score' => $grade?->score,
                'note' => $grade?->note,
                'error_tag_category' => $attempt === 1 ? $grade?->errorTag : null,
                'answered_at' => $this->answeredAt($run, $input['answered_at'] ?? null),
            ]);

            if ($grade !== null && $grade->targets !== []) {
                DB::table('lesson_answer_targets')->insert(array_map(fn (array $target): array => [
                    'lesson_answer_id' => $answer->id,
                    'targetable_type' => $target['type'],
                    'targetable_id' => $target['id'],
                    'is_correct' => $target['correct'],
                ], $grade->targets));
            }

            if ($grade !== null && ! $run->kind->isCheck() && $exercise->format === LessonExerciseFormat::TypeWord) {
                $this->recordTypingSupport($user, $exercise, $grade->correct, $answer->hinted);
            }

            $this->recordStreakActivity->handle($user);

            return $this->result($run, $answer, false, $grade);
        });
    }

    private function recordTypingSupport(User $user, LessonExercise $exercise, bool $correct, bool $hinted): void
    {
        $item = $exercise->targets->map(fn ($target) => $target->targetable)->first(fn ($targetable): bool => $targetable instanceof VocabularyItem);

        if ($item instanceof VocabularyItem) {
            $this->typingSupport->record($user, $item, $correct, $hinted);
        }
    }

    /**
     * An answer belongs to its run's own plan, or to the substitute of an
     * exercise in it. It is not checked against the current language, so an
     * answer queued offline still syncs after the learner switches language.
     */
    private function exercise(LessonRun $run, int $exerciseId): LessonExercise
    {
        $exercise = LessonExercise::query()->with(['lesson.unit.language', 'targets', 'grammarPoints'])->find($exerciseId);
        $planIds = $run->planExerciseIds();

        if ($exercise === null || (! in_array($exercise->id, $planIds, true) && ! in_array($exercise->substitute_for_id, $planIds, true))) {
            throw ValidationException::withMessages(['exercise_id' => [__('That exercise is not part of this lesson.')]]);
        }

        return $exercise;
    }

    private function assertSkippable(LessonExercise $exercise): void
    {
        $hasSubstitute = LessonExercise::query()->where('substitute_for_id', $exercise->id)->whereNull('retired_at')->exists();

        if (! $exercise->format->isSkippable() || ! $hasSubstitute) {
            throw ValidationException::withMessages(['lesson' => [__('This exercise cannot be skipped.')]]);
        }
    }

    /**
     * A device clock can drift while offline, and rejecting the answer would
     * drop the learner's work, so the time is clamped rather than refused.
     */
    private function answeredAt(LessonRun $run, ?string $given): CarbonImmutable
    {
        $now = CarbonImmutable::now();

        if ($given === null) {
            return $now;
        }

        $parsed = CarbonImmutable::parse($given);

        return $parsed->lessThan($run->started_at) ? $run->started_at : ($parsed->greaterThan($now) ? $now : $parsed);
    }

    /**
     * @return array{answer: LessonAnswer, grade: Grade|null, run: LessonRun, completed: bool, milestone: array{type: string, message: string}|null, repeat: bool}
     */
    private function result(LessonRun $run, LessonAnswer $answer, bool $repeat, ?Grade $graded): array
    {
        $wasOpen = $run->status === LessonRunStatus::InProgress;
        $completed = $this->settleLessonRun->handle($run);
        $run->refresh();

        $milestone = $completed && $wasOpen ? $run->result['milestone'] ?? null : null;

        $grade = $run->kind->isCheck() || $answer->skipped
            ? null
            : $graded ?? $this->gradeLessonAnswer->handle(
                LessonExercise::query()->with(['lesson.unit.language', 'targets', 'grammarPoints'])->findOrFail($answer->lesson_exercise_id),
                $answer->response ?? [],
            );

        return [
            'answer' => $answer,
            'grade' => $grade,
            'run' => $run,
            'completed' => $completed,
            'milestone' => is_array($milestone) ? $this->milestone($milestone) : null,
            'repeat' => $repeat,
        ];
    }

    /**
     * @param  array<mixed>  $milestone
     * @return array{type: string, message: string}
     */
    private function milestone(array $milestone): array
    {
        return [
            'type' => is_string($milestone['type'] ?? null) ? $milestone['type'] : 'milestone',
            'message' => is_string($milestone['message'] ?? null) ? $milestone['message'] : '',
        ];
    }
}
