<?php

declare(strict_types=1);

use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\Notifications\SendDueReviewReminder;
use App\Enums\NotificationFrequency;
use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Models\UserSetting;
use App\Notifications\DueReviewsReminder;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    $this->travelTo(CarbonImmutable::parse('2026-09-30 15:00:00'));

    $this->language = Language::factory()->create(['name' => 'Spanish']);
    $this->user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($this->user, $this->language);
    $this->user->updatePushSubscription('https://fcm.googleapis.com/fcm/send/abc123', 'p256dh-key', 'auth-token');

    $this->settings = UserSetting::factory()->create(['user_id' => $this->user->id]);
});

/**
 * A card last reviewed at the given moment, next due well after today, so it
 * only marks when the learner last studied this deck.
 */
function reviewedDeckAt(User $user, Language $language, string $at): void
{
    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'state' => SrsCardState::Review,
        'due_at' => CarbonImmutable::parse($at)->addDays(10),
        'last_reviewed_at' => $at,
    ]);
}

/** @param array<string, mixed> $attributes */
function repetitionsComingDueAt(User $user, Language $language, string $dueAt, int $count, array $attributes = []): void
{
    SrsCard::factory()->count($count)->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'state' => SrsCardState::Review,
        'due_at' => $dueAt,
        'last_reviewed_at' => CarbonImmutable::parse($dueAt)->subDays(3),
        ...$attributes,
    ]);
}

function remindOfDueReviews(User $user): bool
{
    return (new SendDueReviewReminder)->handle($user);
}

it('pushes once twenty repetitions have come due since the learner last reviewed', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 20);

    expect(remindOfDueReviews($this->user))->toBeTrue();

    Notification::assertSentTo(
        $this->user,
        DueReviewsReminder::class,
        fn (DueReviewsReminder $reminder): bool => $reminder->toWebPush($this->user)->toArray()['body'] === '20 Spanish cards are ready to review',
    );
});

it('stays quiet at nineteen due repetitions', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 19);

    expect(remindOfDueReviews($this->user))->toBeFalse();

    Notification::assertNothingSent();
});

it('counts only scheduled repetitions in the current deck', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 19);
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 1, ['state' => SrsCardState::New, 'last_reviewed_at' => null]);
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 1, ['is_weak_spot' => true]);
    repetitionsComingDueAt($this->user, Language::factory()->create(), '2026-09-30 13:00:00', 1);
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 16:00:00', 1);

    expect(remindOfDueReviews($this->user))->toBeFalse();
});

it('stays quiet when the learner last reviewed with twenty already waiting, as nothing crossed since', function () {
    repetitionsComingDueAt($this->user, $this->language, '2026-09-29 18:00:00', 25);
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 20);

    expect(remindOfDueReviews($this->user))->toBeFalse();
});

it('pushes when the count was below twenty at the last review and crossed afterwards', function () {
    repetitionsComingDueAt($this->user, $this->language, '2026-09-29 18:00:00', 12);
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 8);

    expect(remindOfDueReviews($this->user))->toBeTrue();
});

it('does not repeat what the morning digest already announced', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-29 09:00:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 06:00:00', 20);
    $this->settings->forceFill(['last_digest_sent_at' => '2026-09-30 08:00:00'])->save();
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 5);

    expect(remindOfDueReviews($this->user))->toBeFalse();
});

it('pushes when the count crossed twenty after a digest that announced fewer', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-29 09:00:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 06:00:00', 10);
    $this->settings->forceFill(['last_digest_sent_at' => '2026-09-30 08:00:00'])->save();
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 10);

    expect(remindOfDueReviews($this->user))->toBeTrue();
});

it('sends at most one reminder a day, and again the next day', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 20);

    expect(remindOfDueReviews($this->user))->toBeTrue();

    $this->travelTo(CarbonImmutable::parse('2026-09-30 20:00:00'));
    reviewedDeckAt($this->user, $this->language, '2026-09-30 15:30:00');
    SrsCard::query()->where('due_at', '2026-09-30 13:00:00')->update(['due_at' => '2026-10-05 00:00:00']);
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 19:30:00', 20);

    expect(remindOfDueReviews($this->user))->toBeFalse();

    $this->travelTo(CarbonImmutable::parse('2026-10-01 12:00:00'));

    expect(remindOfDueReviews($this->user))->toBeTrue();
    Notification::assertSentToTimes($this->user, DueReviewsReminder::class, 2);
});

it('does not remind again of what the last reminder already said', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-29 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-29 13:00:00', 20);
    $this->settings->forceFill(['last_due_reminder_sent_at' => '2026-09-29 14:00:00'])->save();

    expect(remindOfDueReviews($this->user))->toBeFalse();
});

it('records when the reminder was sent', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 20);

    remindOfDueReviews($this->user);

    expect($this->settings->refresh()->last_due_reminder_sent_at?->toDateTimeString())->toBe('2026-09-30 15:00:00');
});

it('only reminds learners on Daily reminders', function (NotificationFrequency $frequency) {
    $this->settings->forceFill(['notification_frequency' => $frequency])->save();
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 20);

    expect(remindOfDueReviews($this->user))->toBeFalse();

    Notification::assertNothingSent();
    expect($this->settings->refresh()->last_due_reminder_sent_at)->toBeNull();
})->with([NotificationFrequency::Weekly, NotificationFrequency::Never]);

it('treats a learner without a settings row as Daily and keeps the stamp on a new row', function () {
    $this->settings->delete();
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 20);

    expect(remindOfDueReviews($this->user))->toBeTrue()
        ->and(UserSetting::query()->where('user_id', $this->user->id)->sole()->last_due_reminder_sent_at)->not->toBeNull();
});

it('sends nothing and stamps nothing without a push subscription', function () {
    $this->user->pushSubscriptions()->delete();
    reviewedDeckAt($this->user, $this->language, '2026-09-30 08:30:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 13:00:00', 20);

    expect(remindOfDueReviews($this->user))->toBeFalse();

    Notification::assertNothingSent();
    expect($this->settings->refresh()->last_due_reminder_sent_at)->toBeNull();
});

it('waits four hours after the last review so it never lands in or right after a session', function () {
    reviewedDeckAt($this->user, $this->language, '2026-09-30 12:00:00');
    repetitionsComingDueAt($this->user, $this->language, '2026-09-30 12:10:00', 20);

    expect(remindOfDueReviews($this->user))->toBeFalse();

    $this->travelTo(CarbonImmutable::parse('2026-09-30 16:00:00'));

    expect(remindOfDueReviews($this->user))->toBeTrue();
});

it('sends nothing to a learner with no unlocked language', function () {
    $this->user->unlockedLanguages()->detach();

    expect(remindOfDueReviews($this->user))->toBeFalse();

    Notification::assertNothingSent();
});
