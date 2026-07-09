<?php

use App\Models\User;
use NotificationChannels\WebPush\PushSubscription;

it('exposes pushEnabled as false and the vapid public key on the learning settings page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('learning.edit'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('pushEnabled', false)
            ->where('vapidPublicKey', config('webpush.vapid.public_key')),
        );
});

it('stores a push subscription for the authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('push-subscriptions.store'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/abc123',
            'keys' => ['p256dh' => 'p256dh-key', 'auth' => 'auth-token'],
        ])
        ->assertOk()
        ->assertJson(['subscribed' => true]);

    $subscription = PushSubscription::query()->where('endpoint', 'https://fcm.googleapis.com/fcm/send/abc123')->sole();

    expect($subscription->subscribable_id)->toBe($user->id)
        ->and($subscription->subscribable_type)->toBe($user->getMorphClass())
        ->and($subscription->public_key)->toBe('p256dh-key')
        ->and($subscription->auth_token)->toBe('auth-token');
});

it('exposes pushEnabled as true once a subscription exists', function () {
    $user = User::factory()->create();
    $user->updatePushSubscription('https://fcm.googleapis.com/fcm/send/abc123', 'p256dh-key', 'auth-token');

    $this->actingAs($user)
        ->get(route('learning.edit'))
        ->assertInertia(fn ($page) => $page->where('pushEnabled', true));
});

it('rejects a subscription request missing keys', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('push-subscriptions.store'), ['endpoint' => 'https://fcm.googleapis.com/fcm/send/abc123'])
        ->assertInvalid(['keys.p256dh', 'keys.auth']);
});

it('deletes a push subscription for the authenticated user', function () {
    $user = User::factory()->create();
    $user->updatePushSubscription('https://fcm.googleapis.com/fcm/send/abc123', 'p256dh-key', 'auth-token');

    $this->actingAs($user)
        ->deleteJson(route('push-subscriptions.destroy'), ['endpoint' => 'https://fcm.googleapis.com/fcm/send/abc123'])
        ->assertOk()
        ->assertJson(['subscribed' => false]);

    expect(PushSubscription::query()->where('endpoint', 'https://fcm.googleapis.com/fcm/send/abc123')->exists())->toBeFalse();
});

it('does not delete another users push subscription', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherUser->updatePushSubscription('https://fcm.googleapis.com/fcm/send/other', 'p256dh-key', 'auth-token');

    $this->actingAs($user)
        ->deleteJson(route('push-subscriptions.destroy'), ['endpoint' => 'https://fcm.googleapis.com/fcm/send/other'])
        ->assertOk();

    expect(PushSubscription::query()->where('endpoint', 'https://fcm.googleapis.com/fcm/send/other')->exists())->toBeTrue();
});
