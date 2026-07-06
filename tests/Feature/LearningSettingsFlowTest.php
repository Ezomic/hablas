<?php

use App\Enums\ContextTag;
use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;

it('renders the learning settings page with the current settings', function () {
    $user = User::factory()->create();
    UserSetting::factory()->create([
        'user_id' => $user->id,
        'notification_frequency' => NotificationFrequency::Weekly,
        'new_item_cap_override' => 7,
        'context_emphasis' => ContextTag::Professional,
    ]);

    $this->actingAs($user)
        ->get(route('learning.edit'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('settings/Learning')
            ->where('settings.notificationFrequency', 'weekly')
            ->where('settings.newItemCapOverride', 7)
            ->where('settings.contextEmphasis', 'professional'),
        );
});

it('shows in-memory default settings on first visit without persisting a row', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('learning.edit'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('settings.notificationFrequency', 'daily')
            ->where('settings.newItemCapOverride', null),
        );

    expect(UserSetting::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('updates settings and redirects back to the settings page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('learning.update'), [
            'notification_frequency' => 'never',
            'new_item_cap_override' => 3,
            'context_emphasis' => 'travel',
        ])
        ->assertRedirect(route('learning.edit'));

    $settings = UserSetting::query()->where('user_id', $user->id)->sole();

    expect($settings->notification_frequency)->toBe(NotificationFrequency::Never)
        ->and($settings->new_item_cap_override)->toBe(3)
        ->and($settings->context_emphasis)->toBe(ContextTag::Travel);
});

it('rejects an out-of-range new item cap override', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('learning.update'), [
            'notification_frequency' => 'daily',
            'new_item_cap_override' => 500,
        ])
        ->assertInvalid(['new_item_cap_override']);
});
