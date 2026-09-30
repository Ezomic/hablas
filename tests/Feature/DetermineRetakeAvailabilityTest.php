<?php

declare(strict_types=1);

use App\Actions\Placement\DetermineRetakeAvailability;
use App\Actions\Placement\HasTakenPlacementTest;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use App\Models\User;
use Carbon\CarbonImmutable;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->language = Language::factory()->create();
    $this->travelTo(CarbonImmutable::parse('2026-09-30 12:00:00'));
});

function placementFinishedAt(User $user, Language $language, string $at, ?Skill $skill = null, bool $answered = true, bool $skipped = false): PlacementTestAttempt
{
    $attempt = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => $skill,
        'started_at' => $at,
        'completed_at' => $at,
        'skipped' => $skipped,
    ]);

    if ($answered) {
        PlacementTestResponse::factory()->create(['attempt_id' => $attempt->id, 'skill' => $skill ?? Skill::Reading]);
    }

    return $attempt;
}

it('lets every skill be re-taken when nothing was placed yet', function () {
    expect((new DetermineRetakeAvailability)->handle($this->user, $this->language))->toBe([
        'reading' => null,
        'listening' => null,
        'speaking' => null,
        'writing' => null,
    ]);
});

it('does not start a cooldown for a skipped test', function () {
    placementFinishedAt($this->user, $this->language, '2026-09-30 11:00:00', answered: false);

    expect(array_filter((new DetermineRetakeAvailability)->handle($this->user, $this->language)))->toBe([]);
});

it('does not start a cooldown for a skip pressed after some answers', function () {
    placementFinishedAt($this->user, $this->language, '2026-09-30 11:00:00', skipped: true);

    expect(array_filter((new DetermineRetakeAvailability)->handle($this->user, $this->language)))->toBe([]);
});

it('holds every skill for a week after the full test, from the start of that day', function () {
    placementFinishedAt($this->user, $this->language, '2026-09-29 21:15:54');

    expect((new DetermineRetakeAvailability)->handle($this->user, $this->language))->toBe([
        'reading' => '2026-10-06',
        'listening' => '2026-10-06',
        'speaking' => '2026-10-06',
        'writing' => '2026-10-06',
    ]);

    $this->travelTo(CarbonImmutable::parse('2026-10-05 23:59:59'));
    expect((new DetermineRetakeAvailability)->handle($this->user, $this->language)['reading'])->toBe('2026-10-06');

    $this->travelTo(CarbonImmutable::parse('2026-10-06 00:00:00'));
    expect((new DetermineRetakeAvailability)->handle($this->user, $this->language)['reading'])->toBeNull();
});

it('holds only the re-placed skill after a one-skill re-take, counting its newest placement', function () {
    placementFinishedAt($this->user, $this->language, '2026-09-01 10:00:00');
    placementFinishedAt($this->user, $this->language, '2026-09-28 10:00:00', Skill::Listening);

    expect((new DetermineRetakeAvailability)->handle($this->user, $this->language))->toBe([
        'reading' => null,
        'listening' => '2026-10-05',
        'speaking' => null,
        'writing' => null,
    ]);
});

it('ignores unfinished attempts, other learners and other languages', function () {
    PlacementTestAttempt::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->language->id,
        'skill' => Skill::Reading,
        'completed_at' => null,
    ]);
    placementFinishedAt(User::factory()->create(), $this->language, '2026-09-30 10:00:00');
    placementFinishedAt($this->user, Language::factory()->create(), '2026-09-30 10:00:00');

    expect(array_filter((new DetermineRetakeAvailability)->handle($this->user, $this->language)))->toBe([]);
});

it('knows whether the learner has taken a placement test rather than skipped it', function () {
    $hasTaken = fn (): bool => (new HasTakenPlacementTest)->handle($this->user, $this->language);

    expect($hasTaken())->toBeFalse();

    placementFinishedAt($this->user, $this->language, '2026-09-01 10:00:00', answered: false);
    placementFinishedAt($this->user, $this->language, '2026-09-01 10:30:00', skipped: true);
    expect($hasTaken())->toBeFalse();

    placementFinishedAt(User::factory()->create(), $this->language, '2026-09-01 10:00:00');
    placementFinishedAt($this->user, Language::factory()->create(), '2026-09-01 10:00:00');
    placementFinishedAt($this->user, $this->language, '2026-09-01 11:00:00')->forceFill(['completed_at' => null])->save();
    expect($hasTaken())->toBeFalse();

    placementFinishedAt($this->user, $this->language, '2026-09-02 10:00:00', Skill::Writing);
    expect($hasTaken())->toBeTrue();
});
