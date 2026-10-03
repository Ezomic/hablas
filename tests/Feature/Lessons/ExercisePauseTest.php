<?php

declare(strict_types=1);

use App\Actions\Lessons\PauseExerciseFamily;
use App\Enums\ExerciseFamily;
use App\Models\User;
use App\Models\UserSetting;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->travelTo(now()->startOfMinute());
});

describe('PauseExerciseFamily', function () {
    it('pauses listening for the given minutes, creating the settings row when there is none', function () {
        $until = (new PauseExerciseFamily)->handle($this->user, ExerciseFamily::Listening, 60);
        $settings = UserSetting::query()->where('user_id', $this->user->id)->sole();

        expect($until?->equalTo(now()->addHour()))->toBeTrue()
            ->and($settings->listening_paused_until?->equalTo(now()->addHour()))->toBeTrue()
            ->and($settings->speaking_paused_until)->toBeNull();
    });

    it('pauses speaking without touching listening, and clears a pause with zero minutes', function () {
        $action = new PauseExerciseFamily;
        $action->handle($this->user, ExerciseFamily::Listening, 60);
        $action->handle($this->user, ExerciseFamily::Speaking, 30);

        expect($action->handle($this->user, ExerciseFamily::Speaking, 0))->toBeNull();

        $settings = UserSetting::query()->where('user_id', $this->user->id)->sole();

        expect($settings->speaking_paused_until)->toBeNull()
            ->and($settings->listening_paused_until)->not->toBeNull();
    });

    it('keeps the other settings as they were', function () {
        UserSetting::factory()->for($this->user)->create(['new_item_cap_override' => 7]);

        (new PauseExerciseFamily)->handle($this->user, ExerciseFamily::Listening, 15);

        expect(UserSetting::query()->where('user_id', $this->user->id)->sole()->new_item_cap_override)->toBe(7);
    });

    it('cannot pause a family that is not skippable', function () {
        (new PauseExerciseFamily)->handle($this->user, ExerciseFamily::Writing, 60);
    })->throws(InvalidArgumentException::class);
});

describe('the pause endpoint', function () {
    it('pauses a family for the minutes asked and reports when it ends', function () {
        $this->actingAs($this->user)->postJson(route('exercise-pauses.store', 'listening'), ['minutes' => 60])
            ->assertOk()
            ->assertJson(['family' => 'listening', 'until' => now()->addHour()->toIso8601String()]);

        expect(UserSetting::query()->sole()->activePause(ExerciseFamily::Listening))->not->toBeNull();
    });

    it('turns a pause back on with zero minutes', function () {
        $this->actingAs($this->user)->postJson(route('exercise-pauses.store', 'speaking'), ['minutes' => 60]);

        $this->actingAs($this->user)->postJson(route('exercise-pauses.store', 'speaking'), ['minutes' => 0])
            ->assertOk()
            ->assertJson(['family' => 'speaking', 'until' => null]);

        expect(UserSetting::query()->sole()->activePause(ExerciseFamily::Speaking))->toBeNull();
    });

    it('does not count an ended pause as active', function () {
        UserSetting::factory()->for($this->user)->create(['listening_paused_until' => now()->subMinute()]);

        expect(UserSetting::query()->sole()->activePause(ExerciseFamily::Listening))->toBeNull()
            ->and(UserSetting::query()->sole()->activePause(ExerciseFamily::Writing))->toBeNull();
    });

    it('refuses minutes outside 0 to 240, and a family that cannot be paused', function () {
        $this->actingAs($this->user)->postJson(route('exercise-pauses.store', 'listening'), ['minutes' => 241])->assertUnprocessable()->assertJsonValidationErrors('minutes');
        $this->actingAs($this->user)->postJson(route('exercise-pauses.store', 'listening'), ['minutes' => -1])->assertUnprocessable();
        $this->actingAs($this->user)->postJson(route('exercise-pauses.store', 'listening'), [])->assertUnprocessable();
        $this->actingAs($this->user)->postJson('/exercise-pauses/writing', ['minutes' => 5])->assertNotFound();
    });

    it('requires authentication', function () {
        $this->postJson(route('exercise-pauses.store', 'listening'), ['minutes' => 5])->assertUnauthorized();
    });
});
