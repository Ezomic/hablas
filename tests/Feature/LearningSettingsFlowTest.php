<?php

use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserInterestPreference;
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

it('includes the users current interest preferences and available options on the learning page', function () {
    $user = User::factory()->create();
    UserInterestPreference::factory()->create(['user_id' => $user->id, 'interest_tag' => InterestTag::Music]);

    $this->actingAs($user)
        ->get(route('learning.edit'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('interestTags', ['music'])
            ->where('availableInterestTags', collect(InterestTag::cases())->map(fn (InterestTag $tag): string => $tag->value)->all()),
        );
});

it('replaces the users interest preferences with the submitted set', function () {
    $user = User::factory()->create();
    UserInterestPreference::factory()->create(['user_id' => $user->id, 'interest_tag' => InterestTag::Music]);

    $this->actingAs($user)
        ->patch(route('learning.interests.update'), [
            'interest_tags' => ['cooking', 'tech'],
        ])
        ->assertRedirect(route('learning.edit'));

    $tags = UserInterestPreference::query()->where('user_id', $user->id)->pluck('interest_tag')->all();

    expect($tags)->toEqualCanonicalizing([InterestTag::Cooking, InterestTag::Tech]);
});

it('clears interest preferences when submitted empty', function () {
    $user = User::factory()->create();
    UserInterestPreference::factory()->create(['user_id' => $user->id, 'interest_tag' => InterestTag::Music]);

    $this->actingAs($user)
        ->patch(route('learning.interests.update'), ['interest_tags' => []])
        ->assertRedirect(route('learning.edit'));

    expect(UserInterestPreference::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('rejects an invalid interest tag', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('learning.interests.update'), [
            'interest_tags' => ['not-a-real-tag'],
        ])
        ->assertInvalid(['interest_tags.0']);
});
