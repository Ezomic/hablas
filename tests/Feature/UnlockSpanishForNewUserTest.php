<?php

use App\Listeners\UnlockSpanishForNewUser;
use App\Models\Language;
use App\Models\User;
use Database\Seeders\LanguageSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Uses User::create() directly rather than the factory — the factory's own
 * afterCreating hook would unlock Spanish independently of the listener,
 * masking a broken listener. Mirrors how the real registration flow
 * (Fortify's CreateNewUser) actually creates users.
 */
it('unlocks Spanish for the newly registered user', function () {
    $this->seed(LanguageSeeder::class);
    $spanish = Language::query()->where('code', 'es')->sole();
    $user = User::create(['name' => 'New User', 'email' => 'new@example.com', 'password' => Hash::make('password')]);

    (new UnlockSpanishForNewUser)->handle(new Registered($user));

    expect($user->unlockedLanguages()->where('languages.id', $spanish->id)->exists())->toBeTrue();
});

it('also sets Spanish as the user\'s current language', function () {
    $this->seed(LanguageSeeder::class);
    $spanish = Language::query()->where('code', 'es')->sole();
    $user = User::create(['name' => 'New User', 'email' => 'new@example.com', 'password' => Hash::make('password')]);

    (new UnlockSpanishForNewUser)->handle(new Registered($user));

    expect($user->fresh()->current_language_id)->toBe($spanish->id);
});

it('does not unlock Spanish for any other user', function () {
    $this->seed(LanguageSeeder::class);
    $spanish = Language::query()->where('code', 'es')->sole();
    $user = User::create(['name' => 'New User', 'email' => 'new@example.com', 'password' => Hash::make('password')]);
    $otherUser = User::create(['name' => 'Other User', 'email' => 'other@example.com', 'password' => Hash::make('password')]);

    (new UnlockSpanishForNewUser)->handle(new Registered($user));

    expect($otherUser->unlockedLanguages()->where('languages.id', $spanish->id)->exists())->toBeFalse();
});

it('does not error when Spanish has not been seeded yet', function () {
    $user = User::create(['name' => 'New User', 'email' => 'new@example.com', 'password' => Hash::make('password')]);

    (new UnlockSpanishForNewUser)->handle(new Registered($user));

    expect($user->unlockedLanguages()->count())->toBe(0);
});

it('stays silent about a missing Spanish row in the testing environment', function () {
    Log::spy();
    $user = User::create(['name' => 'New User', 'email' => 'new@example.com', 'password' => Hash::make('password')]);

    (new UnlockSpanishForNewUser)->handle(new Registered($user));

    Log::shouldNotHaveReceived('warning');
    Log::shouldNotHaveReceived('error');
});

it('logs an error when Spanish is missing in production so the misconfig is visible', function () {
    app()->detectEnvironment(fn () => 'production');
    Log::spy();
    $user = User::create(['name' => 'New User', 'email' => 'new@example.com', 'password' => Hash::make('password')]);

    (new UnlockSpanishForNewUser)->handle(new Registered($user));

    Log::shouldHaveReceived('error')->once();
    // Still a no-op for the account itself — loud in logs, not a hard failure.
    expect($user->unlockedLanguages()->count())->toBe(0);
});
