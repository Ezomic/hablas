<?php

declare(strict_types=1);

use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;

function runSubLevelBackfill(): void
{
    (require database_path('migrations/2026_10_01_000002_backfill_sub_level_on_user_skill_levels.php'))->up();
}

function levelRow(User $user, Language $language, Skill $skill, CefrLevel $level): UserSkillLevel
{
    return UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => $skill,
        'cefr_level' => $level,
        'sub_level' => null,
    ]);
}

function completedAttempt(User $user, Language $language, string $at, array $resulting, ?Skill $skill = null): void
{
    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => $skill,
        'started_at' => $at,
        'completed_at' => $at,
        'resulting_skill_levels' => $resulting,
    ]);
}

function tierOf(UserSkillLevel $level): ?string
{
    return $level->fresh()?->sub_level?->value;
}

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->language = Language::factory()->create();
});

it('takes the tier of the newest attempt when its parent matches the stored level', function () {
    $level = levelRow($this->user, $this->language, Skill::Reading, CefrLevel::A2);
    completedAttempt($this->user, $this->language, '2026-07-17 10:00:00', ['reading' => ['cefr_level' => 'A1', 'sub_level' => 'A1.3']]);
    completedAttempt($this->user, $this->language, '2026-07-18 10:00:00', ['reading' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2']]);

    runSubLevelBackfill();

    expect(tierOf($level))->toBe('A2.2');
});

it('falls back to the first tier of the stored level when practice moved it past the attempt', function () {
    $level = levelRow($this->user, $this->language, Skill::Reading, CefrLevel::B1);
    completedAttempt($this->user, $this->language, '2026-07-17 10:00:00', ['reading' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2']]);

    runSubLevelBackfill();

    expect(tierOf($level))->toBe('B1.1');
});

it('reads the old string shape, which carries no tier, as the first tier of the stored level', function () {
    $level = levelRow($this->user, $this->language, Skill::Reading, CefrLevel::A2);
    completedAttempt($this->user, $this->language, '2026-07-17 10:00:00', ['reading' => 'A2']);

    runSubLevelBackfill();

    expect(tierOf($level))->toBe('A2.1');
});

it('uses the first tier when no completed attempt covers the skill', function () {
    $level = levelRow($this->user, $this->language, Skill::Writing, CefrLevel::A1);
    PlacementTestAttempt::factory()->create(['user_id' => $this->user->id, 'language_id' => $this->language->id, 'completed_at' => null]);

    runSubLevelBackfill();

    expect(tierOf($level))->toBe('A1.1');
});

it('leaves a level without tiers null', function () {
    $c1 = levelRow($this->user, $this->language, Skill::Reading, CefrLevel::C1);
    $c2 = levelRow($this->user, $this->language, Skill::Writing, CefrLevel::C2);
    completedAttempt($this->user, $this->language, '2026-07-17 10:00:00', ['reading' => ['cefr_level' => 'B2', 'sub_level' => 'B2']]);

    runSubLevelBackfill();

    expect(tierOf($c1))->toBeNull()
        ->and(tierOf($c2))->toBeNull();
});

it('gives B2 its single tier', function () {
    $level = levelRow($this->user, $this->language, Skill::Reading, CefrLevel::B2);

    runSubLevelBackfill();

    expect(tierOf($level))->toBe('B2');
});

it('reads each skill from the newest attempt that covers it, so a one-skill re-take wins only for its skill', function () {
    $listening = levelRow($this->user, $this->language, Skill::Listening, CefrLevel::A2);
    $reading = levelRow($this->user, $this->language, Skill::Reading, CefrLevel::A2);
    completedAttempt($this->user, $this->language, '2026-07-17 10:00:00', [
        'listening' => ['cefr_level' => 'A2', 'sub_level' => 'A2.1'],
        'reading' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2'],
    ]);
    completedAttempt($this->user, $this->language, '2026-07-20 10:00:00', ['listening' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2']], Skill::Listening);

    runSubLevelBackfill();

    expect(tierOf($listening))->toBe('A2.2')
        ->and(tierOf($reading))->toBe('A2.2');
});

it('ignores attempts of another user or language, and leaves a recorded tier alone', function () {
    $level = levelRow($this->user, $this->language, Skill::Reading, CefrLevel::A2);
    $recorded = UserSkillLevel::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => Language::factory()->create()->id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::A2,
        'sub_level' => CefrSubLevel::A2_2,
    ]);
    completedAttempt(User::factory()->create(), $this->language, '2026-07-17 10:00:00', ['reading' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2']]);
    completedAttempt($this->user, Language::factory()->create(), '2026-07-17 10:00:00', ['reading' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2']]);

    runSubLevelBackfill();

    expect(tierOf($level))->toBe('A2.1')
        ->and(tierOf($recorded))->toBe('A2.2');
});
