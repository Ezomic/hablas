<?php

declare(strict_types=1);

use App\Actions\Lessons\ReopenUncheckedUnits;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\UnitProgressStatus;
use App\Models\LessonRun;
use App\Models\SrsCard;
use App\Models\UserUnitProgress;
use App\Services\UnitContentRegistry;
use Database\Seeders\ContentSeeder;
use Tests\Fixtures\Lessons\HotelContent;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    $this->user = LessonWorld::learner();
    $this->completed = fn () => UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $this->unit->id, 'status' => UnitProgressStatus::Completed, 'completed_at' => now()->subDay()]);
});

it('leaves a completed unit alone while it has no playable lessons', function () {
    $this->unit = LessonWorld::hotelUnit();
    ($this->completed)();

    expect((new ReopenUncheckedUnits)->handle())->toBe(0)
        ->and(UserUnitProgress::query()->sole()->status)->toBe(UnitProgressStatus::Completed);
});

it('puts a unit completed through the button back in progress once it has lessons, and keeps its cards', function () {
    $this->unit = LessonWorld::hotelUnit();
    ($this->completed)();
    $card = SrsCard::factory()->create(['user_id' => $this->user->id, 'language_id' => $this->unit->language_id]);
    app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new HotelContent(lessonsReviewed: false)]));

    $this->seed(ContentSeeder::class);

    $progress = UserUnitProgress::query()->sole();

    expect($progress->status)->toBe(UnitProgressStatus::InProgress)
        ->and($progress->completed_at)->toBeNull()
        ->and(SrsCard::query()->whereKey($card->id)->exists())->toBeTrue();
});

it('leaves a unit completed through its check alone', function () {
    [$unit] = LessonWorld::seededHotel();
    $this->unit = $unit;
    ($this->completed)();
    LessonWorld::finishTeachingLessons($this->user, $unit);
    LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($unit, LessonStage::Check), LessonRunKind::Check));

    expect(UserUnitProgress::query()->sole()->status)->toBe(UnitProgressStatus::Completed)
        ->and((new ReopenUncheckedUnits)->handle())->toBe(0);
});

it('leaves a unit completed through a test-out or a retake alone', function (LessonRunKind $kind) {
    [$unit] = LessonWorld::seededHotel();
    $this->unit = $unit;
    ($this->completed)();
    LessonRun::factory()->create([
        'user_id' => $this->user->id,
        'lesson_id' => LessonWorld::lesson($unit, LessonStage::Check)->id,
        'kind' => $kind,
        'status' => LessonRunStatus::Completed,
    ]);

    expect((new ReopenUncheckedUnits)->handle())->toBe(0)
        ->and(UserUnitProgress::query()->sole()->status)->toBe(UnitProgressStatus::Completed);
})->with([LessonRunKind::TestOut, LessonRunKind::Retake]);

it('still reopens a unit whose only completed run is a plain lesson or an unfinished check', function (LessonRunKind $kind, LessonRunStatus $status) {
    [$unit] = LessonWorld::seededHotel();
    $this->unit = $unit;
    ($this->completed)();
    LessonRun::factory()->create([
        'user_id' => $this->user->id,
        'lesson_id' => LessonWorld::lesson($unit, LessonStage::Meet)->id,
        'kind' => $kind,
        'status' => $status,
    ]);

    expect((new ReopenUncheckedUnits)->handle())->toBe(1);
})->with([
    'a completed lesson' => [LessonRunKind::Lesson, LessonRunStatus::Completed],
    'an unfinished test-out' => [LessonRunKind::TestOut, LessonRunStatus::InProgress],
]);

it('does nothing on a second pass', function () {
    [$this->unit] = LessonWorld::seededHotel();
    ($this->completed)();

    expect((new ReopenUncheckedUnits)->handle())->toBe(1)
        ->and((new ReopenUncheckedUnits)->handle())->toBe(0);
});
