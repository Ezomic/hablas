<?php

declare(strict_types=1);

use App\Actions\Auth\SendEmailCode;
use App\Enums\EmailCodePurpose;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Laravel\Fortify\Features;
use Tests\Support\EmailCode;

/**
 * Regression guard for the subtlety that shapes AuthenticateWithEmailCode.
 *
 * With the two-factor feature enabled, Fortify's login pipeline calls the
 * authenticateUsing callback TWICE for a user who has no 2FA secret: once in
 * RedirectIfTwoFactorAuthenticatable::validateCredentials() (which falls
 * through) and again in AttemptToAuthenticate. Since verifying consumes the
 * code, a non-memoized callback verifies successfully, consumes, then fails the
 * second call — so every ordinary login would break. This is the plain
 * happy-path login, and it only passes because the callback memoizes.
 */
it('logs in a user who does not have two-factor enabled', function () {
    expect(Features::enabled(Features::twoFactorAuthentication()))->toBeTrue();

    $user = User::factory()->create();
    expect($user->two_factor_secret)->toBeNull();

    $code = EmailCode::issue($user);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code]);

    $this->assertAuthenticated();
});

it('consumes the code exactly once', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code]);
    $this->assertAuthenticated();

    expect(LoginCode::query()->whereNotNull('consumed_at')->count())->toBe(1);
});

it('rejects a code that has already been used', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code]);
    $this->post(route('logout'));
    $this->assertGuest();

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code]);

    $this->assertGuest();
});

it('rejects an expired code', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user);

    $this->travelTo(Date::now()->addMinutes(SendEmailCode::EXPIRES_IN_MINUTES + 1));

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code]);

    $this->assertGuest();
});

it('rejects another user\'s code', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersCode = EmailCode::issue($other);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $othersCode]);

    $this->assertGuest();
});

it('rejects a confirmation code at the login endpoint', function () {
    $user = User::factory()->create();
    $confirmCode = EmailCode::issue($user, EmailCodePurpose::Confirm);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $confirmCode]);

    $this->assertGuest();
});

it('invalidates the previous code when a new one is requested', function () {
    $user = User::factory()->create();
    $first = EmailCode::issue($user);
    $second = EmailCode::issue($user);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $first]);
    $this->assertGuest();

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $second]);
    $this->assertAuthenticated();
});

it('stores codes hashed, never in plaintext', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user);

    $stored = LoginCode::query()->sole();

    expect($stored->code_hash)->not->toBe($code)
        ->and(str_contains($stored->code_hash, $code))->toBeFalse();
});

it('throttles how often a code can be requested', function () {
    $user = User::factory()->create();

    // The login-code limiter allows 3 per minute per email+IP.
    for ($i = 0; $i < 3; $i++) {
        $this->post(route('login.code.store'), ['email' => $user->email])
            ->assertSessionHasNoErrors();
    }

    $this->post(route('login.code.store'), ['email' => $user->email])
        ->assertTooManyRequests();
});

it('voids a sign-in code after five wrong guesses until a new one is requested', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user);

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('login.store'), ['email' => $user->email, 'code' => EmailCode::wrongGuess($code)]);
    }

    // Past Fortify's login throttle, so only the cap on the code can refuse it.
    $this->travel(1)->minutes();

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code])
        ->assertSessionHasErrors('email');
    $this->assertGuest();

    $this->post(route('login.store'), ['email' => $user->email, 'code' => EmailCode::issue($user)]);
    $this->assertAuthenticatedAs($user);
});

it('still signs in with the right code after four wrong guesses', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user);

    for ($i = 0; $i < 4; $i++) {
        $this->post(route('login.store'), ['email' => $user->email, 'code' => EmailCode::wrongGuess($code)]);
    }

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code]);

    $this->assertAuthenticatedAs($user);
});

it('proves the address when a sign-in code is used', function () {
    $user = User::factory()->unverified()->create();
    $code = EmailCode::issue($user);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code]);

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()?->email_verified_at)->not->toBeNull();
});

it('does not prove an address the code was never sent to', function () {
    $user = User::factory()->create(['email' => 'attacker@example.com']);
    $code = EmailCode::issue($user);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->patch(route('profile.update'), ['name' => $user->name, 'email' => 'victim@example.com'])
        ->assertSessionHasNoErrors();
    $this->post(route('logout'));

    $this->post(route('login.store'), ['email' => 'victim@example.com', 'code' => $code]);

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()?->email_verified_at)->toBeNull();
});
