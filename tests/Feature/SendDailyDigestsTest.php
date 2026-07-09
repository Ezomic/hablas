<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Console\Commands\SendDailyDigests;
use App\Enums\NotificationFrequency;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSetting;
use App\Notifications\DailyDigestNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->language = Language::factory()->create();
});

it('sends a digest to a user with Daily frequency', function () {
    Notification::fake();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $this->language);
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $this->language->id, 'completed_at' => now()]);
    UserSetting::factory()->create(['user_id' => $user->id, 'notification_frequency' => NotificationFrequency::Daily]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    Notification::assertSentTo($user, DailyDigestNotification::class);
});

it('never sends a digest to a user with Never frequency', function () {
    Notification::fake();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $this->language);
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $this->language->id, 'completed_at' => now()]);
    UserSetting::factory()->create(['user_id' => $user->id, 'notification_frequency' => NotificationFrequency::Never]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    Notification::assertNotSentTo($user, DailyDigestNotification::class);
});

it('sends a digest to a Weekly user who has never received one', function () {
    Notification::fake();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $this->language);
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $this->language->id, 'completed_at' => now()]);
    UserSetting::factory()->create([
        'user_id' => $user->id,
        'notification_frequency' => NotificationFrequency::Weekly,
        'last_digest_sent_at' => null,
    ]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    Notification::assertSentTo($user, DailyDigestNotification::class);
});

it('does not resend to a Weekly user before 7 days have passed', function () {
    Notification::fake();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $this->language);
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $this->language->id, 'completed_at' => now()]);
    UserSetting::factory()->create([
        'user_id' => $user->id,
        'notification_frequency' => NotificationFrequency::Weekly,
        'last_digest_sent_at' => now()->subDays(3),
    ]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    Notification::assertNotSentTo($user, DailyDigestNotification::class);
});

it('resends to a Weekly user once 7 days have passed', function () {
    Notification::fake();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $this->language);
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $this->language->id, 'completed_at' => now()]);
    UserSetting::factory()->create([
        'user_id' => $user->id,
        'notification_frequency' => NotificationFrequency::Weekly,
        'last_digest_sent_at' => now()->subDays(8),
    ]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    Notification::assertSentTo($user, DailyDigestNotification::class);
});

it('records when the digest was sent', function () {
    Notification::fake();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $this->language);
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $this->language->id, 'completed_at' => now()]);
    UserSetting::factory()->create(['user_id' => $user->id, 'notification_frequency' => NotificationFrequency::Daily]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    expect(UserSetting::query()->where('user_id', $user->id)->sole()->last_digest_sent_at)->not->toBeNull();
});

it('defaults a user with no settings row to Daily and creates one', function () {
    Notification::fake();
    $user = User::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $this->language);
    PlacementTestAttempt::factory()->create(['user_id' => $user->id, 'language_id' => $this->language->id, 'completed_at' => now()]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    Notification::assertSentTo($user, DailyDigestNotification::class);
    expect(UserSetting::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

it('skips a user with no unlocked language', function () {
    Notification::fake();
    $user = User::factory()->create();
    UserSetting::factory()->create(['user_id' => $user->id, 'notification_frequency' => NotificationFrequency::Daily]);

    $this->artisan(SendDailyDigests::class)->assertExitCode(0);

    Notification::assertNotSentTo($user, DailyDigestNotification::class);
});
