<?php

namespace Tests\Support;

use App\Actions\Auth\SendEmailCode;
use App\Enums\EmailCodePurpose;
use App\Models\User;
use App\Notifications\EmailCodeNotification;
use Illuminate\Support\Facades\Notification;

class EmailCode
{
    /**
     * Issue a real code and hand back the plaintext.
     *
     * Codes are hashed at rest, so a test can't read one out of the database.
     * This drives the production action and captures the value off the
     * notification, which keeps tests honest about how codes are minted.
     */
    public static function issue(User $user, EmailCodePurpose $purpose = EmailCodePurpose::Login): string
    {
        Notification::fake();

        app(SendEmailCode::class)->handle($user, $purpose);

        $code = null;

        Notification::assertSentTo($user, function (EmailCodeNotification $notification) use (&$code, $purpose) {
            if ($notification->purpose === $purpose) {
                $code = $notification->code;
            }

            return true;
        });

        return $code ?? throw new \RuntimeException('No '.$purpose->value.' code was sent.');
    }
}
