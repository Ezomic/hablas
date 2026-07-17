<?php

use App\Enums\EmailCodePurpose;
use App\Models\User;
use App\Notifications\EmailCodeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Features;
use Tests\Support\EmailCode;

it('renders the login screen', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

it('authenticates users with an emailed code', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'code' => $code,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

it('redirects users with two-factor enabled to the two-factor challenge', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();
    $code = EmailCode::issue($user);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'code' => $code,
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $response->assertSessionHas('login.id', $user->id);
    $this->assertGuest();
});

it('does not authenticate users with an invalid code', function () {
    $user = User::factory()->create();
    EmailCode::issue($user);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'code' => '000000',
    ]);

    $this->assertGuest();
});

it('requires a code', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), ['email' => $user->email])
        ->assertSessionHasErrors('code');

    $this->assertGuest();
});

it('sends a sign-in code for a known email', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post(route('login.code.store'), ['email' => $user->email])
        ->assertSessionHasNoErrors();

    Notification::assertSentTo(
        $user,
        fn (EmailCodeNotification $notification) => $notification->purpose === EmailCodePurpose::Login,
    );
});

it('does not reveal whether an email has an account', function () {
    Notification::fake();
    $user = User::factory()->create();

    $known = $this->post(route('login.code.store'), ['email' => $user->email]);
    $unknown = $this->post(route('login.code.store'), ['email' => 'nobody@example.com']);

    // Identical status and flash message, so this endpoint can't be used to
    // enumerate which addresses have accounts.
    expect($unknown->status())->toBe($known->status())
        ->and(session('status'))->toBe('If that email has an account, we\'ve sent it a sign-in code.');

    Notification::assertCount(1);
});

it('logs users out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});

it('rate limits login attempts', function () {
    $user = User::factory()->create();

    RateLimiter::increment(md5('login'.implode('|', [$user->email, '127.0.0.1'])), amount: 5);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'code' => '000000',
    ]);

    $response->assertTooManyRequests();
});
