<?php

namespace App\Providers;

use App\Actions\Auth\AuthenticateWithEmailCode;
use App\Actions\Auth\VerifyEmailCode;
use App\Actions\Fortify\CreateNewUser;
use App\Enums\EmailCodePurpose;
use App\Http\Requests\Auth\LoginCodeRequest;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Fortify's own LoginRequest requires a `password` field; ours requires
        // a `code`. AuthenticatedSessionController type-hints the vendor class,
        // so rebind it to the subclass.
        $this->app->bind(LoginRequest::class, LoginCodeRequest::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        // There are no passwords: POST /login proves control of the inbox with a
        // one-time code instead. Reusing Fortify's callback (rather than our own
        // login route) keeps its throttling, session regeneration and 2FA
        // challenge redirect working unchanged.
        Fortify::authenticateUsing(fn (Request $request) => app(AuthenticateWithEmailCode::class)($request));

        // Likewise, the password.confirm middleware still guards sensitive
        // actions; it just confirms with an emailed code. Fortify's
        // ConfirmablePasswordController hands the callback
        // $request->input('password'), so the confirm form posts the code under
        // that field name — hence the odd-looking parameter here.
        Fortify::confirmPasswordsUsing(function (User $user, ?string $code) {
            return $code !== null
                && app(VerifyEmailCode::class)->handle($user, $code, EmailCodePurpose::Confirm);
        });
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (Request $request) => Inertia::render('auth/Login', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::verifyEmailView(fn (Request $request) => Inertia::render('auth/VerifyEmail', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::registerView(fn () => Inertia::render('auth/Register'));

        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/TwoFactorChallenge'));

        Fortify::confirmPasswordView(fn (Request $request) => Inertia::render('auth/ConfirmPassword', [
            'status' => $request->session()->get('status'),
        ]));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Sending mail is expensive and the code is a secret being pushed to an
        // inbox, so requesting one is throttled harder than verifying it. Keyed
        // by user id when re-confirming (that route has no email field) and by
        // the submitted address when signing in.
        RateLimiter::for('login-code', function (Request $request) {
            $identifier = $request->user()?->getAuthIdentifier() ?? Str::lower((string) $request->input('email'));
            $throttleKey = Str::transliterate($identifier.'|'.$request->ip());

            return [
                Limit::perMinute(3)->by($throttleKey),
                Limit::perHour(10)->by($throttleKey),
            ];
        });

        RateLimiter::for('passkeys', function (Request $request) {
            return Limit::perMinute(10)->by(
                ($request->input('credential.id') ?: $request->session()->getId()).'|'.$request->ip(),
            );
        });
    }
}
