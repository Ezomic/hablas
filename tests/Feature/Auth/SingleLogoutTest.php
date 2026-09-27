<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Signing out at Thijssensoftware ID reaches hablas only through this endpoint:
 * the call is server to server, so it stamps the user row and id-client's
 * middleware ends the local session on the user's next request.
 */
const HABLAS_LOGOUT_SECRET = 'test-logout-secret';

/**
 * @param  array<string, mixed>  $payload
 * @return TestResponse<Response>
 */
function signedLogout(array $payload): TestResponse
{
    $body = json_encode($payload, JSON_THROW_ON_ERROR);

    return test()->call('POST', route('sso.logout'), server: [
        'HTTP_X_ID_SIGNATURE' => hash_hmac('sha256', $body, HABLAS_LOGOUT_SECRET),
        'CONTENT_TYPE' => 'application/json',
    ], content: $body);
}

beforeEach(function () {
    config(['id-client.logout_secret' => HABLAS_LOGOUT_SECRET]);
});

it('refuses an unsigned back-channel call', function () {
    $user = User::factory()->create(['idp_id' => '42']);

    $this->postJson(route('sso.logout'), ['sub' => '42', 'issued_at' => Carbon::now()->getTimestamp()])
        ->assertUnauthorized();

    expect($user->fresh()?->sso_logged_out_at)->toBeNull();
});

it('ends a session that came from ID once ID signs the user out', function () {
    $user = User::factory()->create(['email' => 'learner@example.com', 'idp_id' => '42']);

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn((new SocialiteUser)->map([
        'id' => '42',
        'name' => $user->name,
        'email' => $user->email,
    ]));
    Socialite::shouldReceive('driver')->with('thijssensoftware')->andReturn($provider);

    $this->get(route('sso.callback'));
    $this->assertAuthenticatedAs($user);

    signedLogout(['sub' => '42', 'issued_at' => Carbon::now()->getTimestamp()])
        ->assertOk()
        ->assertJson(['status' => 'ok']);

    // A real next request resolves the user from the database, not from the
    // model the guard cached during the callback.
    Auth::forgetGuards();

    $this->get(route('dashboard'))->assertRedirect('/');
    $this->assertGuest();
});
