<?php

use App\Actions\Settings\GetUserSettings;
use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;

it('returns in-memory default settings for a user with no saved settings, without persisting them', function () {
    $user = User::factory()->create();

    $settings = (new GetUserSettings)->handle($user);

    expect($settings->notification_frequency)->toBe(NotificationFrequency::Daily)
        ->and($settings->new_item_cap_override)->toBeNull()
        ->and($settings->context_emphasis)->toBeNull()
        ->and($settings->exists)->toBeFalse()
        ->and(UserSetting::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('returns the existing settings on subsequent access instead of creating another row', function () {
    $user = User::factory()->create();
    $existing = UserSetting::factory()->create([
        'user_id' => $user->id,
        'notification_frequency' => NotificationFrequency::Weekly,
    ]);

    $settings = (new GetUserSettings)->handle($user);

    expect($settings->id)->toBe($existing->id)
        ->and($settings->notification_frequency)->toBe(NotificationFrequency::Weekly)
        ->and(UserSetting::query()->where('user_id', $user->id)->count())->toBe(1);
});
