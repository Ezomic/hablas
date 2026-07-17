<?php

namespace App\Actions\Auth;

use App\Enums\EmailCodePurpose;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Fortify's authenticateUsing callback: proves control of the inbox with a
 * one-time code instead of a password.
 *
 * IMPORTANT — the result is memoized on the request, and it has to be.
 * Fortify's login pipeline invokes this callback TWICE for a user who has no
 * 2FA secret: once in RedirectIfTwoFactorAuthenticatable::validateCredentials()
 * (which runs because the 2FA feature is enabled, finds no secret, and falls
 * through) and again in AttemptToAuthenticate::handleUsingCustomCallback().
 * Because VerifyEmailCode consumes the code, an un-memoized callback would
 * succeed on the first call and fail on the second, breaking every login.
 *
 * The memo lives on the Request — the same instance is piped through both
 * steps — rather than on this object. Memoizing on the instance would require a
 * singleton, and a singleton's memo would outlive the request and authenticate
 * a later request with an already-consumed code under a persistent runtime
 * (Octane), and across requests within a single test.
 */
class AuthenticateWithEmailCode
{
    private const MEMO_KEY = 'email_code_authenticated_user';

    public function __construct(private readonly VerifyEmailCode $verifyEmailCode) {}

    public function __invoke(Request $request): ?User
    {
        if ($request->attributes->has(self::MEMO_KEY)) {
            return $request->attributes->get(self::MEMO_KEY);
        }

        $user = $this->attempt(
            (string) $request->input('email'),
            (string) $request->input('code'),
        );

        $request->attributes->set(self::MEMO_KEY, $user);

        return $user;
    }

    private function attempt(string $email, string $code): ?User
    {
        if ($email === '' || $code === '') {
            return null;
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            return null;
        }

        if (! $this->verifyEmailCode->handle($user, $code, EmailCodePurpose::Login)) {
            return null;
        }

        return $user;
    }
}
