<?php

declare(strict_types=1);

use App\Models\User;
use CBOR\ByteStringObject;
use CBOR\MapObject;
use CBOR\NegativeIntegerObject;
use CBOR\UnsignedIntegerObject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Laravel\Passkeys\Passkeys;
use Laravel\Passkeys\Support\WebAuthn;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Tests\Support\EmailCode;
use Webauthn\CredentialRecord;
use Webauthn\TrustPath\EmptyTrustPath;

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

    $cookie = rememberCookieFrom(test()->get(route('sso.callback'))->assertRedirect(route('continue', absolute: false)));

    expect($cookie)->not->toBeNull();

    return (string) $cookie;
}

const SOFTWARE_PASSKEY_ID = 'software-authenticator';

/**
 * A user with a passkey held by a software authenticator: a real P-256 key
 * pair, stored the way a registration ceremony would store its public half.
 *
 * @return array{0: User, 1: OpenSSLAsymmetricKey}
 */
function passkeyHolder(): array
{
    $user = User::factory()->create();

    $key = openssl_pkey_new(['private_key_type' => OPENSSL_KEYTYPE_EC, 'curve_name' => 'prime256v1']);
    $point = openssl_pkey_get_details($key)['ec'];

    // COSE_Key: EC2, ES256, P-256, then the x and y coordinates.
    $publicKey = (string) MapObject::create()
        ->add(UnsignedIntegerObject::create(1), UnsignedIntegerObject::create(2))
        ->add(UnsignedIntegerObject::create(3), NegativeIntegerObject::create(-7))
        ->add(NegativeIntegerObject::create(-1), UnsignedIntegerObject::create(1))
        ->add(NegativeIntegerObject::create(-2), ByteStringObject::create(str_pad($point['x'], 32, "\0", STR_PAD_LEFT)))
        ->add(NegativeIntegerObject::create(-3), ByteStringObject::create(str_pad($point['y'], 32, "\0", STR_PAD_LEFT)));

    $record = CredentialRecord::create(
        publicKeyCredentialId: SOFTWARE_PASSKEY_ID,
        type: 'public-key',
        transports: [],
        attestationType: 'none',
        trustPath: EmptyTrustPath::create(),
        aaguid: Uuid::fromString('00000000-0000-0000-0000-000000000000'),
        credentialPublicKey: $publicKey,
        userHandle: $user->getPasskeyUserHandle(),
        counter: 0,
    );

    $user->passkeys()->create([
        'name' => 'Laptop',
        'credential_id' => Base64UrlSafe::encodeUnpadded(SOFTWARE_PASSKEY_ID),
        'credential' => json_decode(WebAuthn::toJson($record), true, flags: JSON_THROW_ON_ERROR),
    ]);

    return [$user, $key];
}

/**
 * What the browser does: fetch a challenge, have the authenticator sign it,
 * and post the assertion the way @laravel/passkeys does. Its body carries
 * only the credential, so "Remember me" travels on the URL.
 *
 * @param  array<string, mixed>  $query
 * @return TestResponse<Response>
 */
function signInWithPasskey(User $user, OpenSSLAsymmetricKey $key, array $query = []): TestResponse
{
    $challenge = test()->getJson(route('passkey.login-options'))->assertOk()->json('options.challenge');

    $clientData = json_encode([
        'type' => 'webauthn.get',
        'challenge' => $challenge,
        'origin' => config('app.url'),
        'crossOrigin' => false,
    ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

    // RP ID hash, flags (user present and verified), signature counter.
    $authenticatorData = hash('sha256', Passkeys::relyingPartyId(), true)."\x05".pack('N', 1);

    openssl_sign($authenticatorData.hash('sha256', $clientData, true), $signature, $key, OPENSSL_ALGO_SHA256);

    return test()->postJson(route('passkey.login', $query), [
        'credential' => [
            'id' => Base64UrlSafe::encodeUnpadded(SOFTWARE_PASSKEY_ID),
            'rawId' => Base64UrlSafe::encodeUnpadded(SOFTWARE_PASSKEY_ID),
            'type' => 'public-key',
            'response' => [
                'clientDataJSON' => Base64UrlSafe::encodeUnpadded($clientData),
                'authenticatorData' => Base64UrlSafe::encodeUnpadded($authenticatorData),
                'signature' => Base64UrlSafe::encodeUnpadded($signature),
                'userHandle' => Base64UrlSafe::encodeUnpadded($user->getPasskeyUserHandle()),
            ],
        ],
    ]);
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
    $cookie = rememberCookieFrom($response->assertRedirect(route('continue', absolute: false)));

    expect($cookie)->not->toBeNull();

    returnWithOnlyTheRememberCookie((string) $cookie)->assertOk();
    $this->assertAuthenticatedAs($user);
});

it('does not remember a code sign-in with "Remember me" left unticked', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), ['email' => $user->email, 'code' => EmailCode::issue($user)]);

    expect(rememberCookieFrom($response->assertRedirect(route('continue', absolute: false))))->toBeNull();
});

it('keeps a browser signed in after a passkey sign-in with "Remember me" ticked', function () {
    [$user, $key] = passkeyHolder();

    $cookie = rememberCookieFrom(signInWithPasskey($user, $key, ['remember' => 1])->assertOk());

    expect($cookie)->not->toBeNull();

    returnWithOnlyTheRememberCookie((string) $cookie)->assertOk();
    $this->assertAuthenticatedAs($user);
});

it('does not remember a passkey sign-in without "Remember me"', function (array $query) {
    [$user, $key] = passkeyHolder();

    $response = signInWithPasskey($user, $key, $query)->assertOk();

    $this->assertAuthenticatedAs($user);
    expect(rememberCookieFrom($response))->toBeNull();
})->with([
    'unticked' => [['remember' => 0]],
    'left out' => [[]],
]);

it('refuses a passkey sign-in whose "Remember me" is not a yes or no', function () {
    [$user, $key] = passkeyHolder();

    signInWithPasskey($user, $key, ['remember' => 'forever'])->assertJsonValidationErrors('remember');

    $this->assertGuest();
});
