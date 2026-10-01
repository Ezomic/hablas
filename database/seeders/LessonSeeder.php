<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\ExerciseFamily;
use App\Models\Unit;
use App\Services\UnitContentRegistry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Builds the lessons of every unit that has reviewed content. Idempotent and
 * cheap on a deploy with no content change: only changed exercises are
 * written, so the usual pass writes nothing.
 */
class LessonSeeder extends Seeder
{
    use WithoutModelEvents;

    private const array GRADED_FAMILIES = [ExerciseFamily::Choice, ExerciseFamily::Writing, ExerciseFamily::Listening];

    public function run(UnitContentRegistry $registry, BuildUnitLessons $buildUnitLessons, SyncUnitLessons $syncUnitLessons): void
    {
        foreach ($registry->all() as $content) {
            $unit = Unit::query()
                ->where('slug', $content->unitSlug())
                ->whereHas('language', fn ($query) => $query->where('code', $content->languageCode()))
                ->first();

            if ($unit === null) {
                continue;
            }

            $definitions = $buildUnitLessons->handle($unit, $content, self::GRADED_FAMILIES);

            if ($definitions === []) {
                continue;
            }

            $syncUnitLessons->handle($unit, $definitions);
        }
    }
}
