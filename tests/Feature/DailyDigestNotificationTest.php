<?php

use App\Models\User;
use App\Notifications\DailyDigestNotification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

it('mentions the due review count when there are cards due', function () {
    $user = User::factory()->create();
    $notification = new DailyDigestNotification('Spanish', 3, 5, false);

    $mail = $notification->toMail($user);

    expect($mail)->toBeInstanceOf(MailMessage::class)
        ->and(collect($mail->introLines)->join(' '))->toContain('3 review cards due');
});

it('omits the due review line when nothing is due', function () {
    $user = User::factory()->create();
    $notification = new DailyDigestNotification('Spanish', 0, 5, false);

    $mail = $notification->toMail($user);

    expect(collect($mail->introLines)->join(' '))->not->toContain('review');
});

it('mentions an unsubmitted weekly reflection', function () {
    $user = User::factory()->create();
    $notification = new DailyDigestNotification('Spanish', 0, 5, true);

    $mail = $notification->toMail($user);

    expect(collect($mail->introLines)->join(' '))->toContain("haven't submitted this week's reflection");
});

it('only sends via mail when the user has no push subscription', function () {
    $user = User::factory()->create();
    $notification = new DailyDigestNotification('Spanish', 0, 0, false);

    expect($notification->via($user))->toBe(['mail']);
});

it('also sends via web push once the user has a push subscription', function () {
    $user = User::factory()->create();
    $user->updatePushSubscription('https://fcm.googleapis.com/fcm/send/abc123', 'p256dh-key', 'auth-token');
    $notification = new DailyDigestNotification('Spanish', 0, 0, false);

    expect($notification->via($user))->toBe(['mail', WebPushChannel::class]);
});

it('builds a web push message with the review count and streak', function () {
    $user = User::factory()->create();
    $notification = new DailyDigestNotification('Spanish', 3, 5, false);

    $message = $notification->toWebPush($user);

    expect($message)->toBeInstanceOf(WebPushMessage::class);

    $payload = $message->toArray();

    expect($payload['title'])->toBe('Your Spanish learning digest')
        ->and($payload['body'])->toContain('3 review cards due')
        ->and($payload['body'])->toContain('5 days streak')
        ->and($payload['data'])->toBe(['url' => '/dashboard']);
});
