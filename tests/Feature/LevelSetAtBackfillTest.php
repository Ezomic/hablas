<?php

declare(strict_types=1);

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;
use Illuminate\Support\Facades\Date;

function runLevelSetAtBackfill(): void
{
    (require database_path('migrations/2026_09_30_000002_backfill_level_set_at_on_user_skill_levels.php'))->up();
}

function levelLastWrittenAt(User $user, Language $language, Skill $skill, string $at): UserSkillLevel
{
    return UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => $skill,
        'created_at' => $at,
        'updated_at' => $at,
    ]);
}

function placementCompletedAt(User $user, Language $language, string $at): void
{
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'started_at' => Date::parse($at)->subHour(),
        'completed_at' => $at,
    ]);
}

function levelSetAt(UserSkillLevel $level): ?string
{
    return $level->fresh()?->level_set_at?->toDateTimeString();
}

it('backfills the newest completed placement, or a practice level-up after it', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    // A placement that measures the same level again does not write the row,
    // so updated_at still shows the first placement.
    $unchangedByPlacement = levelLastWrittenAt($user, $language, Skill::Writing, '2026-07-17 14:11:08');
    $changedByPlacement = levelLastWrittenAt($user, $language, Skill::Reading, '2026-07-17 21:15:54');
    $raisedByPractice = levelLastWrittenAt($user, $language, Skill::Speaking, '2026-08-01 09:00:00');

    placementCompletedAt($user, $language, '2026-07-17 14:11:08');
    placementCompletedAt($user, $language, '2026-07-17 21:15:54');
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'started_at' => '2026-09-01 10:00:00',
    ]);
    placementCompletedAt($user, Language::factory()->create(), '2026-09-02 10:00:00');

    runLevelSetAtBackfill();

    expect(levelSetAt($unchangedByPlacement))->toBe('2026-07-17 21:15:54')
        ->and(levelSetAt($changedByPlacement))->toBe('2026-07-17 21:15:54')
        ->and(levelSetAt($raisedByPractice))->toBe('2026-08-01 09:00:00');
});

it('backfills updated_at when the user never completed a placement', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $level = levelLastWrittenAt($user, $language, Skill::Listening, '2026-07-20 12:00:00');

    runLevelSetAtBackfill();

    expect(levelSetAt($level))->toBe('2026-07-20 12:00:00');
});

it('leaves a recorded level_set_at alone', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $level = levelLastWrittenAt($user, $language, Skill::Writing, '2026-07-17 14:11:08');
    $level->forceFill(['level_set_at' => '2026-09-30 08:00:00'])->save();

    placementCompletedAt($user, $language, '2026-07-17 21:15:54');

    runLevelSetAtBackfill();

    expect(levelSetAt($level))->toBe('2026-09-30 08:00:00');
});
