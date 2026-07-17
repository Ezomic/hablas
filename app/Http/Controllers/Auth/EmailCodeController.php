<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendEmailCode;
use App\Enums\EmailCodePurpose;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailCodeController extends Controller
{
    /**
     * Email a sign-in code to a guest.
     *
     * Responds identically whether or not the address belongs to an account —
     * this endpoint is unauthenticated, so branching would turn it into a user
     * enumeration oracle.
     */
    public function store(Request $request, SendEmailCode $sendEmailCode): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if ($user !== null) {
            $sendEmailCode->handle($user, EmailCodePurpose::Login);
        }

        return back()->with('status', 'If that email has an account, we\'ve sent it a sign-in code.');
    }

    /**
     * Email a confirmation code to the already-authenticated user, for
     * re-authenticating before a sensitive action.
     */
    public function confirm(Request $request, SendEmailCode $sendEmailCode): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $sendEmailCode->handle($user, EmailCodePurpose::Confirm);

        return back()->with('status', 'We\'ve sent a confirmation code to your email.');
    }
}
