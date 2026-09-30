<?php

declare(strict_types=1);

use App\Models\User;
use App\Notifications\DueReviewsReminder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\WebPush;
use NotificationChannels\WebPush\PushSubscription;
use NotificationChannels\WebPush\WebPushChannel;

function pushServiceAnswering(int $status): WebPush
{
    $report = new MessageSentReport(
        new Request('POST', 'https://fcm.googleapis.com/fcm/send/abc123'),
        new Response($status),
        success: $status < 300,
    );

    $webPush = Mockery::mock(WebPush::class);
    $webPush->shouldReceive('queueNotification')->once();
    $webPush->shouldReceive('flush')->once()->andReturnUsing(function () use ($report): Generator {
        yield $report;
    });

    return $webPush;
}

function subscribedLearner(): User
{
    $user = User::factory()->create();
    $user->updatePushSubscription('https://fcm.googleapis.com/fcm/send/abc123', 'p256dh-key', 'auth-token');

    return $user;
}

it('goes out by push only, so no mail throttle applies', function () {
    $reminder = new DueReviewsReminder('Spanish', 23);

    expect($reminder->via(User::factory()->create()))->toBe([WebPushChannel::class]);
});

it('names the count and the deck and opens the review session', function () {
    $payload = (new DueReviewsReminder('Portuguese', 23))->toWebPush(User::factory()->create())->toArray();

    expect($payload['title'])->toBe('Reviews are due')
        ->and($payload['body'])->toBe('23 Portuguese cards are ready to review')
        ->and($payload['data'])->toBe(['url' => '/review']);
});

it('drops a subscription the push service reports as gone', function (int $status) {
    $user = subscribedLearner();
    $channel = app()->makeWith(WebPushChannel::class, ['webPush' => pushServiceAnswering($status)]);

    $channel->send($user, new DueReviewsReminder('Spanish', 23));

    expect(PushSubscription::query()->count())->toBe(0);
})->with([404, 410]);

it('keeps a subscription through a passing push service failure', function () {
    $user = subscribedLearner();
    $channel = app()->makeWith(WebPushChannel::class, ['webPush' => pushServiceAnswering(500)]);

    $channel->send($user, new DueReviewsReminder('Spanish', 23));

    expect(PushSubscription::query()->count())->toBe(1);
});
