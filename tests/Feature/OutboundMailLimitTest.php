<?php

use App\Models\User;
use App\Notifications\DailyDigestNotification;
use App\Services\OutboundMailLimit;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\RateLimiter;

/**
 * A stand-in for the queued job the middleware wraps. RateLimited only ever
 * calls release() on it, so that is all this needs to record.
 */
function fakeJob(): object
{
    return new class
    {
        public int $releases = 0;

        public ?int $releasedFor = null;

        public function release(int $delay = 0): void
        {
            $this->releases++;
            $this->releasedFor = $delay;
        }
    };
}

/**
 * Pushes jobs through the middleware and reports how many actually ran versus
 * how many were released back to the queue.
 *
 * @return array{ran: int, released: int}
 */
function pushThroughLimiter(int $count): array
{
    $middleware = new RateLimited(OutboundMailLimit::RATE_LIMITER);
    $ran = 0;
    $released = 0;

    for ($i = 0; $i < $count; $i++) {
        $job = fakeJob();
        $middleware->handle($job, function () use (&$ran) {
            $ran++;
        });

        $released += $job->releases;
    }

    return ['ran' => $ran, 'released' => $released];
}

it('reserves headroom under the provider cap for interactive mail', function () {
    expect(OutboundMailLimit::bulkAllowance())
        ->toBe(OutboundMailLimit::PROVIDER_CAP - OutboundMailLimit::INTERACTIVE_RESERVE)
        ->toBeLessThan(OutboundMailLimit::PROVIDER_CAP);
});

it('registers the bulk mail limiter', function () {
    expect(RateLimiter::limiter(OutboundMailLimit::RATE_LIMITER))->not->toBeNull();
});

it('lets the bulk allowance through', function () {
    $result = pushThroughLimiter(OutboundMailLimit::bulkAllowance());

    expect($result['ran'])->toBe(OutboundMailLimit::bulkAllowance())
        ->and($result['released'])->toBe(0);
});

it('releases bulk mail once the allowance is spent instead of sending it', function () {
    $result = pushThroughLimiter(OutboundMailLimit::bulkAllowance() + 5);

    expect($result['ran'])->toBe(OutboundMailLimit::bulkAllowance())
        ->and($result['released'])->toBe(5);
});

it('never lets bulk mail reach the provider cap, so sign-in codes keep room', function () {
    $result = pushThroughLimiter(OutboundMailLimit::PROVIDER_CAP * 3);

    expect($result['ran'])->toBeLessThanOrEqual(
        OutboundMailLimit::PROVIDER_CAP - OutboundMailLimit::INTERACTIVE_RESERVE,
    );
});

it('releases with a delay rather than dropping the mail', function () {
    pushThroughLimiter(OutboundMailLimit::bulkAllowance());

    $middleware = new RateLimited(OutboundMailLimit::RATE_LIMITER);
    $job = fakeJob();
    $ran = false;

    $middleware->handle($job, function () use (&$ran) {
        $ran = true;
    });

    expect($ran)->toBeFalse()
        ->and($job->releases)->toBe(1)
        ->and($job->releasedFor)->toBeGreaterThan(0);
});

it('throttles the daily digest through the bulk limiter', function () {
    $notification = new DailyDigestNotification('Spanish', 3, 5, false);
    $middleware = $notification->middleware(User::factory()->make());

    expect($middleware)->toHaveCount(1)
        ->and($middleware[0])->toBeInstanceOf(RateLimited::class);
});

it('gives the digest enough tries to outlast a spent allowance', function () {
    $notification = new DailyDigestNotification('Spanish', 3, 5, false);

    // Each release costs an attempt, so the retry budget has to exceed the
    // number of releases a full window can produce.
    expect($notification->tries)->toBeGreaterThan(OutboundMailLimit::PROVIDER_CAP)
        ->and($notification->backoff)->toBeGreaterThanOrEqual(60);
});
