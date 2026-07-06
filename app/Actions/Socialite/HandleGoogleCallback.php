<?php

namespace App\Actions\Socialite;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class HandleGoogleCallback
{
    public function handle(SocialiteUser $googleUser): User
    {
        $user = User::query()->firstWhere('google_id', $googleUser->getId());

        if ($user !== null) {
            return $user;
        }

        $user = User::query()->firstWhere('email', $googleUser->getEmail());

        if ($user !== null) {
            // Google just proved ownership of this email, so it supersedes whatever
            // verification state the existing (password-based) account was in.
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
            ])->save();

            return $user;
        }

        $user = User::create([
            'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? $googleUser->getEmail(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => Hash::make(Str::random(40)),
            'email_verified_at' => now(),
        ]);

        event(new Registered($user));

        return $user;
    }
}
