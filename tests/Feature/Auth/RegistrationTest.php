<?php

use App\Models\Language;
use App\Models\User;
use Database\Seeders\LanguageSeeder;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

it('renders the registration screen', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

it('registers new users', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

it('unlocks Spanish for a newly registered user', function () {
    $this->seed(LanguageSeeder::class);
    $spanish = Language::query()->where('code', 'es')->sole();

    $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $user = User::query()->where('email', 'test@example.com')->sole();

    expect($user->unlockedLanguages()->where('languages.id', $spanish->id)->exists())->toBeTrue()
        ->and($user->current_language_id)->toBe($spanish->id);
});
