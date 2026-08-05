<?php

use App\Actions\CompleteUnit;
use App\Actions\RecordShadowingAttempt;
use App\Actions\SelectNextUnit;
use App\Actions\Settings\GetUserSettings;
use App\Actions\Srs\BuildReviewSession;
use App\Actions\Srs\ReviewSrsCard;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\AdaptiveNewItemCap;

it('resolves the action layer through the container without a cycle', function (string $action) {
    expect(app($action))->toBeInstanceOf($action);
})->with([
    CompleteUnit::class,
    BuildReviewSession::class,
    ReviewSrsCard::class,
    SelectNextUnit::class,
    RecordShadowingAttempt::class,
    AdaptiveNewItemCap::class,
]);

it('uses a collaborator swapped in through the container', function () {
    $fake = new class extends GetUserSettings
    {
        public function handle(User $user): UserSetting
        {
            return new UserSetting(['new_item_cap_override' => 42]);
        }
    };

    $this->app->instance(GetUserSettings::class, $fake);

    $cap = app(AdaptiveNewItemCap::class);

    expect($cap->forUser(User::factory()->create(), Language::factory()->create()))->toBe(42);
});

it('still works when built directly with no arguments', function () {
    expect((new AdaptiveNewItemCap)->forUser(
        User::factory()->create(),
        Language::factory()->create(),
    ))->toBe(10);
});
