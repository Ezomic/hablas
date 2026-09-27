<?php

declare(strict_types=1);

use App\Models\Streak;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Symfony\Component\HttpFoundation\Response;
use Tests\Support\EmailCode;

/**
 * Thijssensoftware ID is a second way in beside the emailed codes (HAB-92).
 * Accounts predate it, so the first ID sign-in has to land in the account that
 * already owns the email, with its progress, rather than next to it.
 *
 * @return TestResponse<Response>
 */
function signInThroughId(string $email): TestResponse
{
    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn((new SocialiteUser)->map([
        'id' => '42',
        'name' => 'Robbin Thijssen',
        'email' => $email,
    ]));

    Socialite::shouldReceive('driver')->with('thijssensoftware')->andReturn($provider);

    return test()->get(route('sso.callback'));
}

it('links the first ID sign-in to the existing account with the same email', function () {
    $user = User::factory()->create(['email' => 'learner@example.com']);
    Streak::factory()->for($user)->create(['current_length' => 12, 'longest_length' => 30]);

    signInThroughId('learner@example.com')->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
    expect(User::query()->count())->toBe(1)
        ->and($user->fresh()?->idp_id)->toBe('42')
        ->and(Streak::query()->where('user_id', $user->id)->sole()->longest_length)->toBe(30);
});

it('provisions an ID user who has no account yet', function () {
    signInThroughId('new@example.com')->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->where('email', 'new@example.com')->sole();

    $this->assertAuthenticatedAs($user);
    expect($user->idp_id)->toBe('42')
        ->and($user->name)->toBe('Robbin Thijssen');
});

it('still signs an ID-linked account in with an emailed code', function () {
    $user = User::factory()->create(['idp_id' => '42', 'sso_logged_out_at' => now()->subHour()]);
    $code = EmailCode::issue($user);

    $this->post(route('login.store'), ['email' => $user->email, 'code' => $code])
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);

    // An earlier sign-out at ID ends ID sessions only, never an emailed-code one.
    $this->get(route('dashboard'))->assertOk();
    $this->assertAuthenticatedAs($user);
});
