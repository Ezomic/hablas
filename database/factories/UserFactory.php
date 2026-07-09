<?php

namespace Database\Factories;

use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ];
    }

    /**
     * Mirrors the unlock half of UnlockSpanishForNewUser's production
     * behavior, so tests that create a plain factory user keep resolving
     * Spanish the way real registered users do — a no-op if Spanish hasn't
     * been seeded in that test's DB state. Deliberately doesn't also set
     * current_language_id like the listener does, since many tests pass an
     * explicit current_language_id override that this would otherwise
     * clobber.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $spanish = Language::query()->where('code', 'es')->first();

            if ($spanish !== null) {
                (new UnlockLanguageForUser)->handle($user, $spanish);
            }
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
