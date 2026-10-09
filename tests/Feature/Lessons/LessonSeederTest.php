<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat;
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
use App\Speech\SpeechCorpus;
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
        ->and(LessonExercise::query()->whereNotNull('substitute_for_id')->count())->toBeGreaterThan(0);
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

it('seeds listening and speaking exercises, each with a substitute from another family', function () {
    seedWith(new HotelContent);

    $exercises = LessonExercise::query()->with('substitute')->get();
    $spoken = $exercises->filter(fn (LessonExercise $exercise): bool => $exercise->format->isSkippable());

    expect($spoken->map(fn (LessonExercise $exercise): ?ExerciseFamily => $exercise->format->family())->unique()->values()->all())->toContain(ExerciseFamily::Listening, ExerciseFamily::Speaking)
        ->and($spoken->every(fn (LessonExercise $exercise): bool => $exercise->substitute !== null && $exercise->substitute->format->family() !== $exercise->format->family()))->toBeTrue();
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

it('seeds nothing and breaks nothing when no unit has content', function () {
    app()->instance(UnitContentRegistry::class, new UnitContentRegistry([]));

    $this->seed(ContentSeeder::class);

    expect(Lesson::query()->count())->toBe(0)
        ->and(User::query()->count())->toBe(0);
});

it('seeds the same lessons on a second pass over the real content, and writes nothing', function () {
    $this->seed(ContentSeeder::class);
    $before = [Lesson::query()->count(), LessonExercise::query()->count()];
    $writes = writesDuring(fn () => $this->seed(LessonSeeder::class));

    expect($before[0])->toBe(320)
        ->and($before[1])->toBeGreaterThan(0)
        ->and([Lesson::query()->count(), LessonExercise::query()->count()])->toBe($before)
        ->and($writes)->toBe([]);
});

it('finds no content classes in an empty content folder', function () {
    expect((new UnitContentRegistry(path: sys_get_temp_dir().'/no-such-content-folder'))->all())->toBe([]);
});

it('finds the twenty-four Spanish and the eight Portuguese content classes in the content folder', function () {
    $contents = (new UnitContentRegistry)->all();

    expect(array_filter($contents, fn ($content): bool => $content->languageCode() === 'es'))->toHaveCount(48)
        ->and(array_filter($contents, fn ($content): bool => $content->languageCode() === 'pt'))->toHaveCount(8);
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

it('seeds speaking and listening into lessons 1 and 2 of every Spanish unit', function () {
    $this->seed(ContentSeeder::class);

    $units = Unit::query()->whereHas('language', fn ($query) => $query->where('code', 'es'))->get();

    expect($units)->toHaveCount(48);

    foreach ($units as $unit) {
        foreach (['meet' => ['speak_repeat', 'listen_choose'], 'recall' => ['speak_repeat', 'listen_choose']] as $stage => $formats) {
            $seeded = LessonExercise::query()
                ->whereHas('lesson', fn ($query) => $query->where('unit_id', $unit->id)->where('stage', $stage))
                ->whereNull('substitute_for_id')
                ->pluck('format')
                ->map(fn (LessonExerciseFormat $format): string => $format->value)
                ->all();

            expect($seeded)->toContain(...$formats);
        }
    }
});

it('only speaks texts the speech corpus holds, so every clip exists once the corpus has been generated', function () {
    $this->seed(ContentSeeder::class);

    $corpus = array_flip(app(SpeechCorpus::class)->texts('es'));
    $speakers = [LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ListenPair, LessonExerciseFormat::ListenType, LessonExerciseFormat::SpeakRepeat];
    $spoken = LessonExercise::query()->whereIn('format', $speakers)->whereHas('lesson.unit.language', fn ($query) => $query->where('code', 'es'))->get()->map(fn (LessonExercise $exercise): string => (string) $exercise->payload['text']);

    expect($spoken)->not->toBeEmpty();

    foreach ($spoken as $text) {
        expect($corpus)->toHaveKey($text);
    }
});
