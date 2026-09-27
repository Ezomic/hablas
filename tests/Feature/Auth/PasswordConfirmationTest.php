<?php

use App\Enums\EmailCodePurpose;
use App\Models\User;
use App\Notifications\EmailCodeNotification;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Support\EmailCode;

/**
 * There are no passwords; Fortify's password.confirm route survives as the
 * re-authentication gate, but it confirms with an emailed code. Fortify's
 * ConfirmablePasswordController reads $request->input('password'), so the code
 * travels under that field name.
 */
it('renders the confirm identity screen', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('password.confirm'));

    $response->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('auth/ConfirmPassword'),
    );
});

it('requires authentication to confirm', function () {
    $response = $this->get(route('password.confirm'));

    $response->assertRedirect(route('login'));
});

it('emails a confirmation code to the authenticated user', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('user.confirm-code.store'))
        ->assertSessionHasNoErrors();

    Notification::assertSentTo(
        $user,
        fn (EmailCodeNotification $notification) => $notification->purpose === EmailCodePurpose::Confirm,
    );
});

it('confirms with a valid code', function () {
    $user = User::factory()->create();
    $code = EmailCode::issue($user, EmailCodePurpose::Confirm);

    $response = $this->actingAs($user)
        ->post(route('password.confirm.store'), ['password' => $code]);

    $response->assertSessionHasNoErrors();
    expect(session()->has('auth.password_confirmed_at'))->toBeTrue();
});

it('does not confirm with an invalid code', function () {
    $user = User::factory()->create();
    EmailCode::issue($user, EmailCodePurpose::Confirm);

    $this->actingAs($user)
        ->post(route('password.confirm.store'), ['password' => '000000']);

    expect(session()->has('auth.password_confirmed_at'))->toBeFalse();
});

it('does not accept a login code for confirmation', function () {
    $user = User::factory()->create();
    $loginCode = EmailCode::issue($user, EmailCodePurpose::Login);

    $this->actingAs($user)
        ->post(route('password.confirm.store'), ['password' => $loginCode]);

    expect(session()->has('auth.password_confirmed_at'))->toBeFalse();
});

it('proves the address when a confirmation code is used', function () {
    $user = User::factory()->unverified()->create();
    $code = EmailCode::issue($user, EmailCodePurpose::Confirm);

    $this->actingAs($user)
        ->post(route('password.confirm.store'), ['password' => $code])
        ->assertSessionHasNoErrors();

    expect($user->fresh()?->email_verified_at)->not->toBeNull();
});

it('does not prove an address typed in after the confirmation code went out', function () {
    $user = User::factory()->create(['email' => 'attacker@example.com']);
    $code = EmailCode::issue($user, EmailCodePurpose::Confirm);

    $this->actingAs($user)
        ->patch(route('profile.update'), ['name' => $user->name, 'email' => 'victim@example.com'])
        ->assertSessionHasNoErrors();

    $this->actingAs($user)
        ->post(route('password.confirm.store'), ['password' => $code])
        ->assertSessionHasNoErrors();

    expect(session()->has('auth.password_confirmed_at'))->toBeTrue()
        ->and($user->fresh()?->email_verified_at)->toBeNull();
});
