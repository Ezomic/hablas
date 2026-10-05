<?php

declare(strict_types=1);

use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Laravel\Passkeys\Passkey;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Symfony\Component\HttpFoundation\Response;
use Tests\Support\EmailCode;

/**
 * id-client links a first ID sign-in to the row holding the same email. Hablas
 * lets a row hold an address nobody proved, so without HAB-92's guard an
 * attacker who put the victim's address on their own account kept a passkey,
 * two-factor or session into it after the victim's first ID sign-in.
 *
 * @return TestResponse<Response>
 */
function idCallback(string $email, string $idpId = '42'): TestResponse
{
    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn((new SocialiteUser)->map([
        'id' => $idpId,
        'name' => 'Victim',
        'email' => $email,
    ]));

    Socialite::shouldReceive('driver')->with('thijssensoftware')->andReturn($provider);

    return test()->get(route('sso.callback'));
}

function addPasskey(User $user): void
{
    Passkey::query()->forceCreate([
        'user_id' => $user->id,
        'name' => 'Security key',
        'credential_id' => Str::random(32),
        'credential' => [],
    ]);
}

/**
 * @param  array<string, mixed>  $data
 */
function storeSession(User $user, array $data = []): string
{
    $id = Str::random(40);

    DB::table('sessions')->insert([
        'id' => $id,
        'user_id' => $user->id,
        'ip_address' => '203.0.113.9',
        'user_agent' => 'Another browser',
        'payload' => base64_encode(json_encode(['_token' => Str::random(40), ...$data], JSON_THROW_ON_ERROR)),
        'last_activity' => time(),
    ]);

    return $id;
}

/**
 * The attacker's requests and the victim's callback share one test process;
 * this puts the callback in a browser of its own.
 */
function switchBrowser(): void
{
    test()->flushSession();
    Auth::forgetGuards();
}

function expectLocalSignInsRevoked(User $user, string $previousRememberToken): void
{
    $user->refresh();

    expect($user->passkeys()->count())->toBe(0)
        ->and($user->two_factor_secret)->toBeNull()
        ->and($user->two_factor_recovery_codes)->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull()
        ->and($user->remember_token)->not->toBe($previousRememberToken)
        ->and(DB::table('sessions')->where('user_id', $user->id)->count())->toBe(0)
        ->and(LoginCode::query()->where('user_id', $user->id)->whereNull('consumed_at')->count())->toBe(0)
        ->and($user->email_verified_at)->not->toBeNull();
}

it('takes every local way in from an attacker who changed their email to the victim\'s', function () {
    $attacker = User::factory()->withTwoFactor()->create(['email' => 'attacker@example.com']);
    addPasskey($attacker);
    storeSession($attacker);
    $codeToOwnInbox = EmailCode::issue($attacker);

    $this->actingAs($attacker)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->patch(route('profile.update'), ['name' => $attacker->name, 'email' => 'victim@example.com'])
        ->assertSessionHasNoErrors();

    $rememberToken = (string) $attacker->fresh()?->remember_token;
    switchBrowser();

    idCallback('victim@example.com')->assertRedirect(route('continue', absolute: false));

    $this->assertAuthenticatedAs($attacker);
    expect(User::query()->count())->toBe(1)
        ->and($attacker->fresh()?->idp_id)->toBe('42');
    expectLocalSignInsRevoked($attacker, $rememberToken);

    switchBrowser();
    $this->post(route('login.store'), ['email' => 'victim@example.com', 'code' => $codeToOwnInbox]);
    $this->assertGuest();
});

it('takes every local way in from an attacker who registered with the victim\'s email', function () {
    $this->post(route('register.store'), ['name' => 'Attacker', 'email' => 'victim@example.com'])
        ->assertRedirect(route('continue', absolute: false));

    $attacker = User::query()->where('email', 'victim@example.com')->sole();
    $attacker->forceFill([
        'two_factor_secret' => encrypt('secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
        'two_factor_confirmed_at' => now(),
    ])->save();
    addPasskey($attacker);
    storeSession($attacker);

    $rememberToken = (string) $attacker->fresh()?->remember_token;
    switchBrowser();

    idCallback('victim@example.com')->assertRedirect(route('continue', absolute: false));

    $this->assertAuthenticatedAs($attacker);
    expectLocalSignInsRevoked($attacker, $rememberToken);
});

it('removes a passkey or two-factor added while the address was unproven', function () {
    $user = User::factory()->unverified()->create(['email' => 'victim@example.com']);
    addPasskey($user);

    // A passkey confirms it is you without an emailed code, so nothing on the
    // way to two-factor proves the address.
    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post(route('two-factor.enable'))
        ->assertSessionHasNoErrors();

    expect($user->fresh()?->two_factor_secret)->not->toBeNull();

    $rememberToken = (string) $user->fresh()?->remember_token;
    switchBrowser();

    idCallback('victim@example.com');

    $this->assertAuthenticatedAs($user);
    expectLocalSignInsRevoked($user, $rememberToken);
});

it('keeps the passkeys, two-factor and sessions of an account whose address was proven', function () {
    $user = User::factory()->withTwoFactor()->create(['email' => 'learner@example.com']);
    addPasskey($user);
    $session = storeSession($user);
    $rememberToken = $user->remember_token;

    idCallback('learner@example.com');

    $this->assertAuthenticatedAs($user);
    $user->refresh();
    expect($user->passkeys()->count())->toBe(1)
        ->and($user->two_factor_secret)->not->toBeNull()
        ->and($user->two_factor_confirmed_at)->not->toBeNull()
        ->and($user->remember_token)->toBe($rememberToken)
        ->and(DB::table('sessions')->where('id', $session)->exists())->toBeTrue();
});

it('revokes the local ways in when the account belonged to another ID user', function () {
    $user = User::factory()->withTwoFactor()->create(['email' => 'learner@example.com', 'idp_id' => '7']);
    addPasskey($user);
    storeSession($user);
    $rememberToken = (string) $user->remember_token;

    idCallback('learner@example.com', '42');

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()?->idp_id)->toBe('42');
    expectLocalSignInsRevoked($user, $rememberToken);
});

it('marks the address of a user ID provisions as proven', function () {
    idCallback('new@example.com');

    expect(User::query()->where('email', 'new@example.com')->sole()->email_verified_at)->not->toBeNull();
});

it('keeps the ID user signed in when the callback runs in a session of the account it revokes', function () {
    config(['session.driver' => 'database']);

    $user = User::factory()->unverified()->create(['email' => 'learner@example.com']);
    $loginKey = 'login_web_'.sha1(SessionGuard::class);
    $otherDevice = storeSession($user, [$loginKey => $user->id]);
    $thisBrowser = storeSession($user, [$loginKey => $user->id]);

    $this->withCookie((string) config('session.cookie'), $thisBrowser)
        ->get(route('profile.edit'))
        ->assertOk();
    Auth::forgetGuards();

    idCallback('learner@example.com')->assertRedirect(route('continue', absolute: false));

    $session = DB::table('sessions')->where('user_id', $user->id)->sole();
    $payload = json_decode(base64_decode((string) $session->payload), true, flags: JSON_THROW_ON_ERROR);

    expect($session->id)->not->toBeIn([$otherDevice, $thisBrowser])
        ->and($payload[$loginKey] ?? null)->toBe($user->id);
});
