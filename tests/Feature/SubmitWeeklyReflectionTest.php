<?php

use App\Actions\Reflections\SubmitWeeklyReflection;
use App\Models\CefrCanDoStatement;
use App\Models\Language;
use App\Models\User;
use App\Models\WeeklyReflection;
use Carbon\CarbonImmutable;

it('creates a submitted weekly reflection with the given responses', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $statements = CefrCanDoStatement::factory()->count(3)->create();

    $reflection = (new SubmitWeeklyReflection)->handle(
        $user,
        $language,
        $statements->pluck('id')->all(),
        [$statements->first()->id],
    );

    expect($reflection)->toBeInstanceOf(WeeklyReflection::class)
        ->and($reflection->submitted_at)->not->toBeNull()
        ->and($reflection->responses['can_do_ids'])->toBe([$statements->first()->id])
        ->and($reflection->week_start_date->isSameDay(CarbonImmutable::now()->startOfWeek()))->toBeTrue();
});

it('overwrites an existing reflection for the same week instead of duplicating it', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $action = new SubmitWeeklyReflection;

    $action->handle($user, $language, [1, 2], [1]);
    $action->handle($user, $language, [1, 2], [1, 2]);

    expect(WeeklyReflection::query()->where('user_id', $user->id)->count())->toBe(1);

    $reflection = WeeklyReflection::query()->where('user_id', $user->id)->sole();
    expect($reflection->responses['can_do_ids'])->toBe([1, 2]);
});
