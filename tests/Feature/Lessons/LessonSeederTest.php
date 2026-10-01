<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\ExerciseFamily;
use App\Enums\LessonStage;
use App\Lessons\ExerciseDefinition;
use App\Lessons\LessonDefinition;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Services\UnitContentRegistry;
use Database\Seeders\ContentSeeder;
use Database\Seeders\LessonSeeder;
use Illuminate\Support\Facades\DB;
use Tests\Fixtures\Lessons\HotelContent;

function seedWith(HotelContent ...$contents): void
{
    app()->instance(UnitContentRegistry::class, new UnitContentRegistry(array_values($contents)));

    test()->seed(ContentSeeder::class);
}

/** @return list<string> */
function writesDuring(Closure $callback): array
{
    $writes = [];

    DB::listen(function ($query) use (&$writes): void {
        if (preg_match('/^\s*(insert|update|delete)\b/i', $query->sql) === 1) {
            $writes[] = $query->sql;
        }
    });

    $callback();

    return $writes;
}

it('seeds the reviewed unit lessons through ContentSeeder', function () {
    seedWith(new HotelContent);

    $unit = Unit::query()->where('slug', 'checking-into-a-hotel')->firstOrFail();
    $lessons = Lesson::query()->where('unit_id', $unit->id)->orderBy('position')->get();

    expect($lessons->pluck('stage')->map(fn (LessonStage $stage): string => $stage->value)->all())->toBe(['meet', 'recall', 'sentences', 'task', 'check'])
        ->and(LessonExercise::query()->count())->toBeGreaterThan(80)
        ->and(DB::table('lesson_exercise_targets')->count())->toBeGreaterThan(100)
        ->and(LessonExercise::query()->whereNotNull('substitute_for_id')->count())->toBe(0);
});

it('points every substitute at its original', function () {
    seedWith();
    $unit = Unit::query()->where('slug', 'checking-into-a-hotel')->firstOrFail();
    (new SyncUnitLessons)->handle($unit, (new BuildUnitLessons)->handle($unit, new HotelContent));

    $substitute = LessonExercise::query()->where('key', 'sentences.listen_type.reserva.sub')->firstOrFail();
    $original = LessonExercise::query()->where('key', 'sentences.listen_type.reserva')->firstOrFail();

    expect($substitute->substitute_for_id)->toBe($original->id)
        ->and($original->substitute?->id)->toBe($substitute->id);
});

it('seeds no listening or speaking exercise, since they are played from a later release', function () {
    seedWith(new HotelContent);

    $formats = LessonExercise::query()->get()->map(fn (LessonExercise $exercise): ?ExerciseFamily => $exercise->format->family());

    expect($formats->contains(ExerciseFamily::Speaking))->toBeFalse()
        ->and($formats->contains(ExerciseFamily::Listening))->toBeFalse()
        ->and(LessonExercise::query()->where('key', 'like', '%speak%')->orWhere('key', 'like', '%listen%')->count())->toBe(0);
});

it('creates the same rows on a second pass and writes nothing', function () {
    seedWith(new HotelContent);

    $counts = fn (): array => [
        'lessons' => Lesson::query()->count(),
        'exercises' => LessonExercise::query()->count(),
        'targets' => DB::table('lesson_exercise_targets')->count(),
    ];
    $before = $counts();
    $updatedAt = LessonExercise::query()->orderBy('id')->pluck('updated_at', 'id')->map(fn ($time): string => $time->toDateTimeString())->all();

    $this->travel(5)->minutes();
    $writes = writesDuring(fn () => $this->seed(LessonSeeder::class));

    expect($counts())->toBe($before)
        ->and($writes)->toBe([])
        ->and(LessonExercise::query()->orderBy('id')->pluck('updated_at', 'id')->map(fn ($time): string => $time->toDateTimeString())->all())->toBe($updatedAt);
});

it('updates a changed exercise in place and leaves the rest untouched', function () {
    seedWith(new HotelContent);

    $ids = LessonExercise::query()->pluck('id', 'key')->all();
    $untouched = LessonExercise::query()->where('key', 'meet.teach_word.el-hotel')->firstOrFail()->updated_at;

    VocabularyItem::query()->where('term', 'la llave')->update(['translation_en' => 'door key']);

    $this->travel(5)->minutes();
    $this->seed(LessonSeeder::class);

    $teach = LessonExercise::query()->where('key', 'meet.teach_word.la-llave')->firstOrFail();

    expect($teach->payload['translation'])->toBe('door key')
        ->and(LessonExercise::query()->pluck('id', 'key')->all())->toBe($ids)
        ->and(LessonExercise::query()->where('key', 'meet.teach_word.el-hotel')->firstOrFail()->updated_at->equalTo($untouched))->toBeTrue()
        ->and($teach->updated_at->greaterThan($untouched))->toBeTrue();
});

it('retires an exercise that left the content instead of deleting it, and keeps its answers', function () {
    seedWith(new HotelContent);

    $user = User::factory()->create();
    $exercise = LessonExercise::query()->where('key', 'sentences.translate.desayuno')->firstOrFail();
    $run = LessonRun::factory()->create(['user_id' => $user->id, 'lesson_id' => $exercise->lesson_id]);
    $answer = LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $exercise->id]);

    seedWith(new HotelContent(withAuthored: false));

    $exercise->refresh();

    expect($exercise->retired_at)->not->toBeNull()
        ->and(LessonAnswer::query()->whereKey($answer->id)->exists())->toBeTrue()
        ->and(LessonExercise::query()->whereNull('retired_at')->where('key', 'like', 'sentences.%')->count())->toBe(0)
        ->and(LessonExercise::query()->where('key', 'meet.teach_word.el-hotel')->firstOrFail()->retired_at)->toBeNull();

    seedWith(new HotelContent);

    expect($exercise->fresh()->retired_at)->toBeNull();
});

it('does not seed content that has not been reviewed', function () {
    seedWith(new HotelContent(wordsReviewed: false, lessonsReviewed: false));

    expect(Lesson::query()->count())->toBe(0)
        ->and(LessonExercise::query()->count())->toBe(0);
});

it('seeds lessons 1 and 2 and a words-only check when only the words are reviewed', function () {
    seedWith(new HotelContent(lessonsReviewed: false));

    $stages = Lesson::query()->orderBy('position')->get()->map(fn (Lesson $lesson): string => $lesson->stage->value)->all();

    expect($stages)->toBe(['meet', 'recall', 'check']);
});

it('skips content for a unit that is not in the database', function () {
    app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new HotelContent]));
    Unit::query()->where('slug', 'checking-into-a-hotel')->delete();

    $this->seed(LessonSeeder::class);

    expect(Lesson::query()->count())->toBe(0);
});

it('seeds nothing and breaks nothing when no unit has content yet', function () {
    $this->seed(ContentSeeder::class);

    expect(Lesson::query()->count())->toBe(0)
        ->and(User::query()->count())->toBe(0);
});

it('finds no content classes in an empty content folder', function () {
    expect((new UnitContentRegistry(path: sys_get_temp_dir().'/no-such-content-folder'))->all())->toBe([]);
});

it('finds the eight Spanish content classes in the content folder', function () {
    expect(array_map(fn ($content): string => $content->unitSlug(), (new UnitContentRegistry)->all()))->toHaveCount(8);
});

it('syncs a unit whose content lost a whole lesson by retiring that lesson\'s exercises', function () {
    seedWith(new HotelContent);
    $unit = Unit::query()->where('slug', 'checking-into-a-hotel')->firstOrFail();
    $definitions = array_values(array_filter(
        (new BuildUnitLessons)->handle($unit, new HotelContent),
        fn ($lesson): bool => $lesson->stage !== LessonStage::Task,
    ));

    $result = (new SyncUnitLessons)->handle($unit, $definitions);

    expect($result['retired'])->toBeGreaterThan(0)
        ->and(LessonExercise::query()->whereHas('lesson', fn ($query) => $query->where('stage', LessonStage::Task))->whereNull('retired_at')->count())->toBe(0);
});

it('puts a lesson back in place when its position or title was changed', function () {
    seedWith(new HotelContent);

    Lesson::query()->where('stage', LessonStage::Recall)->update(['position' => 9, 'title' => 'Old title']);
    $this->seed(LessonSeeder::class);

    $lesson = Lesson::query()->where('stage', LessonStage::Recall)->firstOrFail();

    expect([$lesson->position, $lesson->title])->toBe([2, 'Recall the words']);
});

it('clears the substitute link of an exercise that stopped being a substitute', function () {
    seedWith(new HotelContent);

    $unit = Unit::query()->where('slug', 'checking-into-a-hotel')->firstOrFail();
    $definitions = (new BuildUnitLessons)->handle($unit, new HotelContent);
    $changed = [];

    foreach ($definitions as $lesson) {
        $exercises = array_map(fn ($exercise) => $exercise->key === 'sentences.listen_type.reserva.sub'
            ? new ExerciseDefinition($exercise->key, $exercise->position, $exercise->block, $exercise->format, $exercise->probeSet, $exercise->payload, $exercise->targets)
            : $exercise, $lesson->exercises);
        $changed[] = new LessonDefinition($lesson->stage, $lesson->title, $lesson->position, $exercises);
    }

    (new SyncUnitLessons)->handle($unit, $changed);

    expect(LessonExercise::query()->where('key', 'sentences.listen_type.reserva.sub')->firstOrFail()->substitute_for_id)->toBeNull();
});
