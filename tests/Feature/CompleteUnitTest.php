<?php

use App\Actions\CompleteUnit;
use App\Enums\UnitProgressStatus;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;

it('marks a unit completed for the user', function () {
    $user = User::factory()->create();
    $unit = Unit::factory()->create();

    $progress = (new CompleteUnit)->handle($user, $unit);

    expect($progress->status)->toBe(UnitProgressStatus::Completed)
        ->and($progress->completed_at)->not->toBeNull()
        ->and($progress->user_id)->toBe($user->id)
        ->and($progress->unit_id)->toBe($unit->id);
});

it('is idempotent for the same user and unit', function () {
    $user = User::factory()->create();
    $unit = Unit::factory()->create();
    $action = new CompleteUnit;

    $first = $action->handle($user, $unit);
    $second = $action->handle($user, $unit);

    expect($second->id)->toBe($first->id)
        ->and(UserUnitProgress::query()->count())->toBe(1);
});
