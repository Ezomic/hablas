<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\HandleGoogleCallback;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): SymfonyRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(HandleGoogleCallback $handleGoogleCallback): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('login')->with('status', 'The Google sign-in request expired or was invalid. Please try again.');
        }

        $user = $handleGoogleCallback->handle($googleUser);

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
