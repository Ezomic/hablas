<?php

use App\Models\User;
use App\Notifications\DailyDigestNotification;
use Illuminate\Notifications\Messages\MailMessage;

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

it('only sends via mail', function () {
    $user = User::factory()->create();
    $notification = new DailyDigestNotification('Spanish', 0, 0, false);

    expect($notification->via($user))->toBe(['mail']);
});
