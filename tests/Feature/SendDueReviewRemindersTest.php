<?php

declare(strict_types=1);

use App\Actions\Languages\UnlockLanguageForUser;
use App\Console\Commands\SendDueReviewReminders;
use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Notifications\DueReviewsReminder;
use Carbon\CarbonImmutable;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schedule as ScheduleFacade;

function learnerWithTwentyDue(Language $language, bool $subscribed): User
{
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);

    if ($subscribed) {
        $user->updatePushSubscription("https://fcm.googleapis.com/fcm/send/{$user->id}", 'p256dh-key', 'auth-token');
    }

    SrsCard::factory()->count(20)->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'state' => SrsCardState::Review,
        'due_at' => now()->subHours(2),
        'last_reviewed_at' => now()->subDays(3),
    ]);

    return $user;
}

/**
 * The time-of-day filter reads the clock when the schedule is defined, so the
 * schedule is defined afresh at the moment under test, as schedule:run does.
 */
function dueReviewReminderEventAt(string $utc): Event
{
    test()->travelTo(CarbonImmutable::parse($utc));
    ScheduleFacade::swap($schedule = new Schedule);

    require base_path('routes/console.php');

    return collect($schedule->events())
        ->sole(fn (Event $event): bool => str_contains((string) $event->command, 'reviews:remind'));
}

it('reminds subscribed learners and passes over the rest', function () {
    Notification::fake();
    $this->travelTo(CarbonImmutable::parse('2026-09-30 14:00:00'));
    $language = Language::factory()->create();
    $subscribed = learnerWithTwentyDue($language, subscribed: true);
    $unsubscribed = learnerWithTwentyDue($language, subscribed: false);

    $this->artisan(SendDueReviewReminders::class)
        ->expectsOutput('Sent 1 due-review reminder.')
        ->assertExitCode(0);

    Notification::assertSentTo($subscribed, DueReviewsReminder::class);
    Notification::assertNotSentTo($unsubscribed, DueReviewsReminder::class);
});

it('runs every hour on the hour and leaves the time-of-day window to the action', function (string $utc, bool $runs) {
    $event = dueReviewReminderEventAt($utc);

    expect($event->isDue(app()) && $event->filtersPass(app()))->toBe($runs);
})->with([
    'on the hour' => ['2026-09-30 19:00:00', true],
    'the nine o\'clock slot, which a between() window would drop' => ['2026-09-30 19:00:00.350', true],
    'not on the half hour' => ['2026-09-30 12:30:00', false],
]);

it('sends nothing from a manual run at night', function () {
    Notification::fake();
    $this->travelTo(CarbonImmutable::parse('2026-09-30 22:30:00'));
    learnerWithTwentyDue(Language::factory()->create(), subscribed: true);

    $this->artisan(SendDueReviewReminders::class)
        ->expectsOutput('Sent 0 due-review reminders.')
        ->assertExitCode(0);

    Notification::assertNothingSent();
});

it('counts every learner it reminded', function () {
    Notification::fake();
    $this->travelTo(CarbonImmutable::parse('2026-09-30 14:00:00'));
    $language = Language::factory()->create();
    learnerWithTwentyDue($language, subscribed: true);
    learnerWithTwentyDue($language, subscribed: true);
    learnerWithTwentyDue($language, subscribed: false);

    $this->artisan(SendDueReviewReminders::class)
        ->expectsOutput('Sent 2 due-review reminders.')
        ->assertExitCode(0);
});

it('never runs twice at once', function () {
    expect(dueReviewReminderEventAt('2026-09-30 10:00:00')->withoutOverlapping)->toBeTrue();
});
