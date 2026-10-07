<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\UnitProgressStatus;
use App\Lessons\TargetRef;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use App\Services\FirstTryRule;
use App\Services\LessonProgress;
use App\Services\UnitMasteryReader;
use App\Services\UnitStruggles;
use LogicException;
use Random\Engine\Mt19937;
use Random\Randomizer;

final class BuildLessonPlan
{
    private const WARM_UP_LIMIT = 5;

    private const PRACTICE_PER_ITEM = 3;

    public function __construct(
        private readonly LessonProgress $lessonProgress = new LessonProgress,
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
        private readonly UnitStruggles $unitStruggles = new UnitStruggles,
        private readonly FirstTryRule $firstTryRule = new FirstTryRule,
    ) {}

    /**
     * The plan a run is played from, stored with its seed so a replay is not
     * a memorised sequence but a resumed run is exactly where it was left.
     *
     * @return array{plan: list<array{id: int, origin: string}>, seed: int, probe_set: string|null}
     */
    public function handle(User $user, Lesson $lesson, LessonRunKind $kind, ?int $seed = null): array
    {
        $seed ??= random_int(1, 2147483647);
        $randomizer = new Randomizer(new Mt19937($seed));
        $unit = Unit::query()->findOrFail($lesson->unit_id);

        [$plan, $probeSet] = match ($kind) {
            LessonRunKind::Lesson => [$this->lessonPlan($user, $lesson, $unit, $randomizer), null],
            LessonRunKind::Check, LessonRunKind::TestOut => $this->checkPlan($user, $lesson, $this->nextProbeSet($user, $lesson)),
            LessonRunKind::Retake => $this->retakePlan($user, $lesson, $unit),
            LessonRunKind::Practice => [$this->practicePlan($user, $unit, $randomizer), null],
        };

        return ['plan' => $plan, 'seed' => $seed, 'probe_set' => $probeSet];
    }

    /** @return list<array{id: int, origin: string}> */
    private function lessonPlan(User $user, Lesson $lesson, Unit $unit, Randomizer $randomizer): array
    {
        $groups = [];

        foreach ($this->ordinaryExercises($lesson->id) as $exercise) {
            $groups[$exercise->block.'|'.$this->tier($exercise)][] = $exercise->id;
        }

        $main = [];

        foreach ($groups as $ids) {
            array_push($main, ...$this->shuffle($randomizer, $ids));
        }

        $plan = [];

        if ($lesson->stage->position() >= 2) {
            array_push($plan, ...$this->entries($this->warmUp($user, $lesson), 'warmup'));
            array_push($plan, ...$this->entries($this->review($user, $unit, $lesson->stage, $randomizer), 'review'));
        }

        array_push($plan, ...$this->entries($main, 'lesson'));

        return $plan;
    }

    /**
     * The shuffle stays inside a block and inside a tier of it, so a teach
     * card and the first recognition exercise of an item always come before
     * anything else on that item.
     */
    private function tier(LessonExercise $exercise): int
    {
        return match (true) {
            $exercise->format->isTeach() => 0,
            in_array($exercise->format, [LessonExerciseFormat::ChooseMeaning, LessonExerciseFormat::ListenChoose], true) => 1,
            default => 2,
        };
    }

    /**
     * @return list<LessonExercise>
     */
    private function ordinaryExercises(int $lessonId): array
    {
        $exercises = [];

        foreach (LessonExercise::query()
            ->where('lesson_id', $lessonId)
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->whereNull('probe_set')
            ->orderBy('position')
            ->get() as $exercise) {
            $exercises[] = $exercise;
        }

        return $exercises;
    }

    /**
     * Up to five exercises missed at first try in the learner's last completed
     * run of the lesson before this one.
     *
     * @return list<int>
     */
    private function warmUp(User $user, Lesson $lesson): array
    {
        $previous = Lesson::query()
            ->where('unit_id', $lesson->unit_id)
            ->where('position', '<', $lesson->position)
            ->where('stage', '!=', LessonStage::Check)
            ->orderByDesc('position')
            ->first();

        if ($previous === null) {
            return [];
        }

        $run = LessonRun::query()
            ->where('user_id', $user->id)
            ->where('lesson_id', $previous->id)
            ->where('kind', LessonRunKind::Lesson)
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->orderByDesc('id')
            ->first();

        if ($run === null) {
            return [];
        }

        $misses = [];

        $answers = $run->answers()->with('lessonExercise.lesson')->where('attempt', 1)->where('skipped', false)->orderBy('answered_at')->orderBy('id')->get();

        foreach ($answers as $answer) {
            $exercise = $answer->lessonExercise;

            if ($exercise === null || $exercise->format->isTeach() || $this->firstTryRule->rightFirstTime($answer)) {
                continue;
            }

            $misses[$exercise->substitute_for_id ?? $exercise->id] = true;
        }

        $alive = $this->ids(LessonExercise::query()->whereKey(array_keys($misses))->whereNull('retired_at')->get(['id']));

        return array_slice(array_values(array_filter(array_keys($misses), fn (int $id): bool => in_array($id, $alive, true))), 0, self::WARM_UP_LIMIT);
    }

    /**
     * Recall exercises of units the learner has completed in this language.
     * Never from a unit not done, and never from the other language.
     *
     * @return list<int>
     */
    private function review(User $user, Unit $unit, LessonStage $stage, Randomizer $randomizer): array
    {
        $completed = UserUnitProgress::query()
            ->where('user_id', $user->id)
            ->where('status', UnitProgressStatus::Completed)
            ->where('unit_id', '!=', $unit->id)
            ->whereHas('unit', fn ($query) => $query->where('language_id', $unit->language_id))
            ->pluck('unit_id')
            ->all();

        if ($completed === []) {
            return [];
        }

        $candidates = $this->ids(LessonExercise::query()
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->whereNull('probe_set')
            ->where('format', 'type_word')
            ->whereHas('lesson', fn ($query) => $query->whereIn('unit_id', $completed)->where('stage', LessonStage::Recall))
            ->orderBy('id')
            ->get(['id']));

        return array_slice($this->shuffle($randomizer, $candidates), 0, $stage->position());
    }

    /**
     * @return array{list<array{id: int, origin: string}>, string}
     */
    private function checkPlan(User $user, Lesson $lesson, string $set): array
    {
        $ids = $this->ids(LessonExercise::query()
            ->where('lesson_id', $lesson->id)
            ->where('probe_set', $set)
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->orderBy('position')
            ->get(['id']));

        return [$this->entries($ids, 'lesson'), $set];
    }

    /**
     * The probe set the learner has seen least recently, so a check is never
     * remembered from last time.
     */
    private function nextProbeSet(User $user, Lesson $lesson): string
    {
        $latest = ['a' => null, 'b' => null];

        foreach (LessonRun::query()->where('user_id', $user->id)->where('lesson_id', $lesson->id)->whereNotNull('probe_set')->get(['probe_set', 'started_at']) as $run) {
            $set = (string) $run->probe_set;

            if (array_key_exists($set, $latest) && ($latest[$set] === null || $run->started_at->greaterThan($latest[$set]))) {
                $latest[$set] = $run->started_at;
            }
        }

        return match (true) {
            $latest['a'] === null => 'a',
            $latest['b'] === null => 'b',
            default => $latest['a']->lessThanOrEqualTo($latest['b']) ? 'a' : 'b',
        };
    }

    /**
     * A retake holds only the probes of the items still missing, from the
     * other probe set, so the sentences are new.
     *
     * @return array{list<array{id: int, origin: string}>, string}
     */
    private function retakePlan(User $user, Lesson $lesson, Unit $unit): array
    {
        $last = $this->lessonProgress->lastCheck($user, $unit);
        $set = $last?->probe_set === 'a' ? 'b' : 'a';
        $missing = array_map(fn (TargetRef $ref): string => $ref->key(), $this->unitMasteryReader->missing($user, $unit));

        $exercises = LessonExercise::query()
            ->where('lesson_id', $lesson->id)
            ->where('probe_set', $set)
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->with('targets')
            ->orderBy('position')
            ->get();

        $plan = [];

        foreach ($exercises as $exercise) {
            foreach ($exercise->targets as $target) {
                if ($target->is_probe && in_array(TargetRef::keyFor($target->targetable_type, $target->targetable_id), $missing, true)) {
                    $plan[] = ['id' => $exercise->id, 'origin' => 'lesson'];

                    break;
                }
            }
        }

        return [$plan, $set];
    }

    /**
     * Three exercises per missed item from lessons 2 to 4, with feedback and
     * returning mistakes.
     *
     * @return list<array{id: int, origin: string}>
     */
    private function practicePlan(User $user, Unit $unit, Randomizer $randomizer): array
    {
        $exercises = LessonExercise::query()
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->whereNull('probe_set')
            ->whereHas('lesson', fn ($query) => $query->where('unit_id', $unit->id)->whereIn('stage', [LessonStage::Recall, LessonStage::Sentences, LessonStage::Task]))
            ->with('targets')
            ->orderBy('id')
            ->get()
            ->filter(fn (LessonExercise $exercise): bool => ! $exercise->format->isTeach());

        $plan = [];
        $chosen = [];

        $struggles = $this->unitStruggles->handle($user, $unit);

        foreach ($struggles !== [] ? $struggles : $this->unitMasteryReader->missing($user, $unit) as $ref) {
            $candidates = [];

            foreach ($exercises as $exercise) {
                if ($exercise->targets->contains(fn ($target): bool => TargetRef::keyFor($target->targetable_type, $target->targetable_id) === $ref->key())) {
                    $candidates[] = $exercise->id;
                }
            }

            foreach (array_slice($this->shuffle($randomizer, $candidates), 0, self::PRACTICE_PER_ITEM) as $id) {
                if (! isset($chosen[$id])) {
                    $chosen[$id] = true;
                    $plan[] = ['id' => $id, 'origin' => 'practice'];
                }
            }
        }

        if ($plan === []) {
            throw new LogicException('There is nothing to practise.');
        }

        return $plan;
    }

    /**
     * @param  iterable<LessonExercise>  $exercises
     * @return list<int>
     */
    private function ids(iterable $exercises): array
    {
        $ids = [];

        foreach ($exercises as $exercise) {
            $ids[] = $exercise->id;
        }

        return $ids;
    }

    /**
     * @param  list<int>  $ids
     * @return list<array{id: int, origin: string}>
     */
    private function entries(array $ids, string $origin): array
    {
        return array_map(fn (int $id): array => ['id' => $id, 'origin' => $origin], $ids);
    }

    /**
     * @param  list<int>  $ids
     * @return list<int>
     */
    private function shuffle(Randomizer $randomizer, array $ids): array
    {
        $shuffled = [];

        foreach ($randomizer->shuffleArray($ids) as $id) {
            $shuffled[] = is_int($id) ? $id : 0;
        }

        return $shuffled;
    }
}
