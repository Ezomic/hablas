<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\HandleGoogleCallback;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleAuthController extends Controller
{
    public function redirect(): SymfonyRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(HandleGoogleCallback $handleGoogleCallback): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = $handleGoogleCallback->handle($googleUser);

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
