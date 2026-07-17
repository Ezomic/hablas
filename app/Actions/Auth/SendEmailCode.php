<?php

namespace App\Actions\Auth;

use App\Enums\EmailCodePurpose;
use App\Models\LoginCode;
use App\Models\User;
use App\Notifications\EmailCodeNotification;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Random\RandomException;

class SendEmailCode
{
    public const int LENGTH = 6;

    public const int EXPIRES_IN_MINUTES = 10;

    /**
     * Issue a single-use numeric code and email it.
     *
     * Any previous unconsumed code for the same user+purpose is consumed first,
     * so requesting a new code invalidates the old one and only ever one code
     * is live at a time.
     *
     * @throws RandomException
     */
    public function handle(User $user, EmailCodePurpose $purpose): void
    {
        $this->invalidateOutstandingCodes($user, $purpose);

        $code = $this->generateCode();

        LoginCode::create([
            'user_id' => $user->id,
            // Hashed at rest: a leaked DB must not hand over live sign-in codes.
            'code_hash' => Hash::make($code),
            'purpose' => $purpose,
            'expires_at' => Date::now()->addMinutes(self::EXPIRES_IN_MINUTES),
        ]);

        $user->notify(new EmailCodeNotification($code, $purpose, self::EXPIRES_IN_MINUTES));
    }

    private function invalidateOutstandingCodes(User $user, EmailCodePurpose $purpose): void
    {
        LoginCode::query()
            ->where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => Date::now()]);
    }

    /**
     * @throws RandomException
     */
    private function generateCode(): string
    {
        return str_pad((string) random_int(0, 10 ** self::LENGTH - 1), self::LENGTH, '0', STR_PAD_LEFT);
    }
}
