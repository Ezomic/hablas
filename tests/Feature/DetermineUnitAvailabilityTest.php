<?php

declare(strict_types=1);

use App\Actions\Units\DetermineUnitAvailability;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Enums\SrsRating;
use App\Enums\UnitAvailability;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\UserUnitProgress;

beforeEach(function () {
    $this->language = Language::factory()->create();
    $this->user = User::factory()->create();
});

function availabilityUnit(Language $language, CefrLevel $level): Unit
{
    return Unit::factory()->create(['language_id' => $language->id, 'cefr_level' => $level]);
}

function availabilityLevels(User $user, Language $language, CefrLevel $level): void
{
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'skill' => $skill,
            'cefr_level' => $level,
        ]);
    }
}

function availabilityCompleted(User $user, Unit $unit, UnitProgressStatus $status = UnitProgressStatus::Completed): void
{
    UserUnitProgress::factory()->create(['user_id' => $user->id, 'unit_id' => $unit->id, 'status' => $status]);
}

function availabilityStruggle(User $user, Language $language): void
{
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    SrsReview::factory()->count(8)->create([
        'user_id' => $user->id,
        'srs_card_id' => $card->id,
        'rating' => SrsRating::Again,
    ]);
}

/** @return array<int, UnitAvailability> */
function availabilityOf(User $user, Language $language, Unit ...$units): array
{
    return (new DetermineUnitAvailability)->handle($user, $language, collect($units));
}

it('makes units at or below the blended level available and locks the ones above', function () {
    availabilityLevels($this->user, $this->language, CefrLevel::A2);
    $a1 = availabilityUnit($this->language, CefrLevel::A1);
    $a2 = availabilityUnit($this->language, CefrLevel::A2);
    $b1 = availabilityUnit($this->language, CefrLevel::B1);

    expect(availabilityOf($this->user, $this->language, $a1, $a2, $b1))->toBe([
        $a1->id => UnitAvailability::Available,
        $a2->id => UnitAvailability::Available,
        $b1->id => UnitAvailability::Locked,
    ]);
});

it('uses the lowest skill as the ceiling', function () {
    availabilityLevels($this->user, $this->language, CefrLevel::B1);
    UserSkillLevel::query()->where('skill', Skill::Writing)->update(['cefr_level' => CefrLevel::A1]);
    $a2 = availabilityUnit($this->language, CefrLevel::A2);

    expect(availabilityOf($this->user, $this->language, $a2))->toBe([$a2->id => UnitAvailability::Locked]);
});

it('treats a learner without skill levels as A1', function () {
    $a1 = availabilityUnit($this->language, CefrLevel::A1);
    $a2 = availabilityUnit($this->language, CefrLevel::A2);

    expect(availabilityOf($this->user, $this->language, $a1, $a2))->toBe([
        $a1->id => UnitAvailability::Available,
        $a2->id => UnitAvailability::Locked,
    ]);
});

it('keeps a completed unit open even above the blended level', function () {
    $b1 = availabilityUnit($this->language, CefrLevel::B1);
    availabilityCompleted($this->user, $b1);

    expect(availabilityOf($this->user, $this->language, $b1))->toBe([$b1->id => UnitAvailability::Completed]);
});

it('counts only this learner finishing a unit as completed, and shows a unit they started as in progress', function () {
    $unit = availabilityUnit($this->language, CefrLevel::A1);
    availabilityCompleted(User::factory()->create(), $unit);
    $started = availabilityUnit($this->language, CefrLevel::A1);
    availabilityCompleted($this->user, $started, UnitProgressStatus::InProgress);

    expect(availabilityOf($this->user, $this->language, $unit, $started))->toBe([
        $unit->id => UnitAvailability::Available,
        $started->id => UnitAvailability::InProgress,
    ]);
});

it('holds back new units while recent reviews need remediation', function () {
    availabilityLevels($this->user, $this->language, CefrLevel::A2);
    availabilityStruggle($this->user, $this->language);
    $done = availabilityUnit($this->language, CefrLevel::A1);
    availabilityCompleted($this->user, $done);
    $next = availabilityUnit($this->language, CefrLevel::A2);
    $above = availabilityUnit($this->language, CefrLevel::B1);

    expect(availabilityOf($this->user, $this->language, $done, $next, $above))->toBe([
        $done->id => UnitAvailability::Completed,
        $next->id => UnitAvailability::HeldBack,
        $above->id => UnitAvailability::Locked,
    ]);
});

it('ignores struggles in the other language deck', function () {
    availabilityStruggle($this->user, Language::factory()->create());
    $unit = availabilityUnit($this->language, CefrLevel::A1);

    expect(availabilityOf($this->user, $this->language, $unit))->toBe([$unit->id => UnitAvailability::Available]);
});

it('returns nothing for no units', function () {
    expect(availabilityOf($this->user, $this->language))->toBe([]);
});

it('keeps a unit with a lesson started open, whatever the review deck or a re-placement says', function () {
    availabilityLevels($this->user, $this->language, CefrLevel::A1);
    $started = availabilityUnit($this->language, CefrLevel::B1);
    $other = availabilityUnit($this->language, CefrLevel::A1);
    availabilityCompleted($this->user, $started, UnitProgressStatus::InProgress);
    availabilityStruggle($this->user, $this->language);

    expect(availabilityOf($this->user, $this->language, $started, $other))->toBe([
        $started->id => UnitAvailability::InProgress,
        $other->id => UnitAvailability::HeldBack,
    ]);
});

it('still shows a completed unit as completed even when it was once in progress', function () {
    $unit = availabilityUnit($this->language, CefrLevel::A1);
    availabilityCompleted($this->user, $unit);

    expect(availabilityOf($this->user, $this->language, $unit))->toBe([$unit->id => UnitAvailability::Completed]);
});
