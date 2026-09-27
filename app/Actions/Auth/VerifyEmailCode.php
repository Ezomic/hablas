<?php

namespace App\Actions\Auth;

use App\Enums\EmailCodePurpose;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class VerifyEmailCode
{
    /**
     * Check a plaintext code against the user's outstanding code for $purpose
     * and consume it on success.
     *
     * Only the newest unconsumed code is considered: SendEmailCode already
     * consumes older ones, but checking a single row keeps this constant-work
     * regardless of how many codes were requested.
     */
    public function handle(User $user, string $code, EmailCodePurpose $purpose): bool
    {
        $loginCode = $this->outstandingCode($user, $purpose);

        if ($loginCode === null || ! $loginCode->isUsable()) {
            return false;
        }

        if (! Hash::check($code, $loginCode->code_hash)) {
            return false;
        }

        $loginCode->forceFill(['consumed_at' => Date::now()])->save();

        $this->proveAddress($user, $loginCode);

        return true;
    }

    /**
     * Using the code proves the inbox it was sent to. That is only the current
     * address if the email has not changed since the code went out; otherwise a
     * code sent to one's own inbox would prove an address typed in afterwards.
     */
    private function proveAddress(User $user, LoginCode $loginCode): void
    {
        if ($user->email_verified_at === null && $loginCode->email === $user->email) {
            $user->forceFill(['email_verified_at' => Date::now()])->save();
        }
    }

    private function outstandingCode(User $user, EmailCodePurpose $purpose): ?LoginCode
    {
        return LoginCode::query()
            ->where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();
    }
}
