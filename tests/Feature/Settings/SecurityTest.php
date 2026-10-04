<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

it('displays the security page', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);
    Features::passkeys([
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/Security')
            ->where('canManagePasskeys', true)
            ->where('passkeys', [])
            ->where('canManageTwoFactor', true)
            ->where('twoFactorEnabled', false),
        );
});

it('requires password confirmation when two-factor is enabled', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    $user = User::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $response = $this->actingAs($user)
        ->get(route('security.edit'));

    $response->assertRedirect(route('password.confirm'));
});

it('renders without two-factor when the feature is disabled', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    config(['fortify.features' => []]);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/Security')
            ->where('canManagePasskeys', false)
            ->where('passkeys', [])
            ->where('canManageTwoFactor', false)
            ->missing('twoFactorEnabled')
            ->missing('requiresConfirmation'),
        );
});

it('words passkey ages in the interface language', function (string $locale, string $created, string $used) {
    config(['app.supported_locales' => ['en', 'nl']]);
    Features::passkeys(['confirmPassword' => true]);

    $user = User::factory()->create();

    DB::table('passkeys')->insert([
        'user_id' => $user->id,
        'name' => 'Laptop',
        'credential_id' => 'credential-1',
        'credential' => '{}',
        'created_at' => now()->subDays(3),
        'last_used_at' => now()->subHours(2),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->withUnencryptedCookie('interface_locale', $locale)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('passkeys.0.created_at_diff', $created)
            ->where('passkeys.0.last_used_at_diff', $used),
        );
})->with([
    ['en', '3 days ago', '2 hours ago'],
    ['nl', '3 dagen geleden', '2 uur geleden'],
]);
