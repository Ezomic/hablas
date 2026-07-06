<?php

use App\Actions\Settings\UpdateUserSettings;
use App\Enums\ContextTag;
use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;

it('updates a user without existing settings', function () {
    $user = User::factory()->create();

    $settings = (new UpdateUserSettings)->handle(
        $user,
        notificationFrequency: NotificationFrequency::Weekly,
        newItemCapOverride: 20,
        contextEmphasis: ContextTag::Travel,
    );

    expect($settings->notification_frequency)->toBe(NotificationFrequency::Weekly)
        ->and($settings->new_item_cap_override)->toBe(20)
        ->and($settings->context_emphasis)->toBe(ContextTag::Travel);
});

it('updates existing settings in place rather than creating a duplicate row', function () {
    $user = User::factory()->create();
    UserSetting::factory()->create(['user_id' => $user->id]);

    (new UpdateUserSettings)->handle(
        $user,
        notificationFrequency: NotificationFrequency::Never,
        newItemCapOverride: null,
        contextEmphasis: null,
    );

    expect(UserSetting::query()->where('user_id', $user->id)->count())->toBe(1)
        ->and(UserSetting::query()->where('user_id', $user->id)->sole()->notification_frequency)->toBe(NotificationFrequency::Never);
});

it('clears an override by passing null', function () {
    $user = User::factory()->create();
    UserSetting::factory()->create(['user_id' => $user->id, 'new_item_cap_override' => 15]);

    $settings = (new UpdateUserSettings)->handle(
        $user,
        notificationFrequency: NotificationFrequency::Daily,
        newItemCapOverride: null,
        contextEmphasis: null,
    );

    expect($settings->new_item_cap_override)->toBeNull();
});
