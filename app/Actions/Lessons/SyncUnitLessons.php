<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Lessons\ExerciseDefinition;
use App\Lessons\LessonDefinition;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

final class SyncUnitLessons
{
    /**
     * Writes only what changed since the last pass, compared by content hash,
     * in one transaction per unit. A deploy with no content change writes
     * nothing. An exercise that disappeared is retired, never deleted, so no
     * answer loses its exercise and no cascade removes history.
     *
     * @param  list<LessonDefinition>  $definitions
     * @return array{written: int, retired: int}
     */
    public function handle(Unit $unit, array $definitions): array
    {
        return DB::transaction(function () use ($unit, $definitions): array {
            $written = 0;
            $retired = 0;
            $stages = [];

            foreach ($definitions as $definition) {
                $stages[] = $definition->stage->value;
                $lesson = $this->lesson($unit, $definition);
                $counts = $this->syncExercises($lesson, $definition->exercises);
                $written += $counts['written'];
                $retired += $counts['retired'];
            }

            $dropped = Lesson::query()->where('unit_id', $unit->id)->whereNotIn('stage', $stages)->get();

            foreach ($dropped as $lesson) {
                $retired += $this->syncExercises($lesson, [])['retired'];
            }

            return ['written' => $written, 'retired' => $retired];
        });
    }

    private function lesson(Unit $unit, LessonDefinition $definition): Lesson
    {
        $lesson = Lesson::query()->firstOrCreate(
            ['unit_id' => $unit->id, 'stage' => $definition->stage],
            ['position' => $definition->position, 'title' => $definition->title],
        );

        if ($lesson->position !== $definition->position || $lesson->title !== $definition->title) {
            $lesson->forceFill(['position' => $definition->position, 'title' => $definition->title])->save();
        }

        return $lesson;
    }

    /**
     * @param  list<ExerciseDefinition>  $definitions
     * @return array{written: int, retired: int}
     */
    private function syncExercises(Lesson $lesson, array $definitions): array
    {
        $existing = LessonExercise::query()
            ->where('lesson_id', $lesson->id)
            ->get(['id', 'key', 'content_hash', 'retired_at', 'substitute_for_id'])
            ->keyBy('key');

        $now = now();
        $inserts = [];
        $inserted = [];
        $changed = [];
        $seen = [];

        foreach ($definitions as $definition) {
            $seen[$definition->key] = true;
            $row = $existing->get($definition->key);

            if ($row === null) {
                $inserts[] = $this->row($lesson, $definition, $now);
                $inserted[] = $definition->key;
            } elseif ($row->content_hash !== $definition->hash || $row->retired_at !== null) {
                $changed[$definition->key] = $definition;
            }
        }

        foreach (array_chunk($inserts, 100) as $chunk) {
            LessonExercise::query()->insert($chunk);
        }

        $ids = [];

        foreach (LessonExercise::query()->where('lesson_id', $lesson->id)->get(['id', 'key']) as $row) {
            $ids[$row->key] = $row->id;
        }

        foreach ($changed as $key => $definition) {
            LessonExercise::query()->whereKey($ids[$key] ?? 0)->update([
                'position' => $definition->position,
                'block' => $definition->block,
                'format' => $definition->format->value,
                'probe_set' => $definition->probeSet,
                'payload' => json_encode($definition->payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'content_hash' => $definition->hash,
                'retired_at' => null,
                'updated_at' => $now,
            ]);
        }

        $touched = [];

        foreach ($inserted as $key) {
            $touched[] = $key;
        }

        foreach (array_keys($changed) as $key) {
            $touched[] = (string) $key;
        }

        $this->syncSubstitutes($definitions, $touched, $ids);
        $this->syncTargets($definitions, $touched, $ids);

        $gone = $existing->filter(fn (LessonExercise $row): bool => ! isset($seen[$row->key]) && $row->retired_at === null);

        if ($gone->isNotEmpty()) {
            LessonExercise::query()->whereKey($gone->pluck('id')->all())->update(['retired_at' => $now, 'updated_at' => $now]);
        }

        return ['written' => count($touched), 'retired' => $gone->count()];
    }

    /** @return array<string, mixed> */
    private function row(Lesson $lesson, ExerciseDefinition $definition, mixed $now): array
    {
        return [
            'lesson_id' => $lesson->id,
            'key' => $definition->key,
            'position' => $definition->position,
            'block' => $definition->block,
            'format' => $definition->format->value,
            'probe_set' => $definition->probeSet,
            'payload' => json_encode($definition->payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'substitute_for_id' => null,
            'content_hash' => $definition->hash,
            'retired_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    /**
     * @param  list<ExerciseDefinition>  $definitions
     * @param  list<string>  $touched
     * @param  array<string, int>  $ids
     */
    private function syncSubstitutes(array $definitions, array $touched, array $ids): void
    {
        foreach ($definitions as $definition) {
            if (! in_array($definition->key, $touched, true)) {
                continue;
            }

            LessonExercise::query()->whereKey($ids[$definition->key] ?? 0)->update([
                'substitute_for_id' => $definition->substituteForKey === null ? null : $ids[$definition->substituteForKey] ?? null,
            ]);
        }
    }

    /**
     * @param  list<ExerciseDefinition>  $definitions
     * @param  list<string>  $touched
     * @param  array<string, int>  $ids
     */
    private function syncTargets(array $definitions, array $touched, array $ids): void
    {
        if ($touched === []) {
            return;
        }

        $touchedIds = array_map(fn (string $key): int => $ids[$key] ?? 0, $touched);

        DB::table('lesson_exercise_targets')->whereIn('lesson_exercise_id', $touchedIds)->delete();

        $rows = [];

        foreach ($definitions as $definition) {
            if (! in_array($definition->key, $touched, true)) {
                continue;
            }

            foreach ($definition->targets as $target) {
                $rows[] = [
                    'lesson_exercise_id' => $ids[$definition->key] ?? 0,
                    'targetable_type' => $target->ref->type,
                    'targetable_id' => $target->ref->id,
                    'is_probe' => $target->isProbe,
                    'is_contrast' => $target->isContrast,
                    'form' => $target->form,
                ];
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('lesson_exercise_targets')->insert($chunk);
        }
    }
}
