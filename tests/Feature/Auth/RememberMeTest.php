<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Symfony\Component\HttpFoundation\Response;
use Tests\Support\EmailCode;

/**
 * The remember-me cookie a sign-in hands the browser, exactly as it arrives.
 *
 * @param  TestResponse<Response>  $response
 */
function rememberCookieFrom(TestResponse $response): ?string
{
    return $response->getCookie(Auth::guard('web')->getRecallerName(), decrypt: false)?->getValue();
}

function rememberCookieAfterIdSignIn(): string
{
    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn((new SocialiteUser)->map([
        'id' => '42',
        'name' => 'Robbin Thijssen',
        'email' => 'learner@example.com',
    ]));
    Socialite::shouldReceive('driver')->with('thijssensoftware')->andReturn($provider);

    $cookie = rememberCookieFrom(test()->get(route('sso.callback'))->assertRedirect(route('dashboard', absolute: false)));

    expect($cookie)->not->toBeNull();

    return (string) $cookie;
}

/**
 * What the browser sends once its session has expired: no session, only the
 * remember-me cookie.
 *
 * @return TestResponse<Response>
 */
function returnWithOnlyTheRememberCookie(string $cookie): TestResponse
{
    test()->flushSession();
    Auth::forgetGuards();

    return test()->withUnencryptedCookie(Auth::guard('web')->getRecallerName(), $cookie)->get(route('dashboard'));
}

/**
 * @return TestResponse<Response>
 */
function signOutAtId(User $user): TestResponse
{
    config(['id-client.logout_secret' => 'test-logout-secret']);

    $body = json_encode(['event' => 'logout', 'sub' => $user->idp_id, 'issued_at' => now()->getTimestamp()], JSON_THROW_ON_ERROR);

    return test()->call('POST', route('sso.logout'), server: [
        'HTTP_X_ID_SIGNATURE' => hash_hmac('sha256', $body, 'test-logout-secret'),
        'CONTENT_TYPE' => 'application/json',
    ], content: $body);
}

it('keeps a browser signed in through ID after its session expires', function () {
    $cookie = rememberCookieAfterIdSignIn();

    returnWithOnlyTheRememberCookie($cookie)->assertOk();

    $this->assertAuthenticatedAs(User::query()->where('email', 'learner@example.com')->sole());
});

it('refuses the remember-me cookie once ID signs the user out', function () {
    $cookie = rememberCookieAfterIdSignIn();
    returnWithOnlyTheRememberCookie($cookie)->assertOk();

    signOutAtId(User::query()->where('email', 'learner@example.com')->sole())->assertOk();

    returnWithOnlyTheRememberCookie($cookie)->assertRedirect(route('login'));
    $this->assertGuest();
});

it('keeps a browser signed in after a code sign-in with "Remember me" ticked', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), ['email' => $user->email, 'code' => EmailCode::issue($user), 'remember' => true]);
    $cookie = rememberCookieFrom($response->assertRedirect(route('dashboard', absolute: false)));

    expect($cookie)->not->toBeNull();

    returnWithOnlyTheRememberCookie((string) $cookie)->assertOk();
    $this->assertAuthenticatedAs($user);
});

it('does not remember a code sign-in with "Remember me" left unticked', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), ['email' => $user->email, 'code' => EmailCode::issue($user)]);

    expect(rememberCookieFrom($response->assertRedirect(route('dashboard', absolute: false))))->toBeNull();
});
