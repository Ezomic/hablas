<?php

declare(strict_types=1);

use App\Actions\Fortify\CreateNewUser;
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
    $response->assertRedirect(route('continue', absolute: false));
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

it('stores the email lowercased and trimmed', function () {
    // Fortify's lowercase_usernames and the TrimStrings middleware already do
    // this for a POST to /register; the action must not rely on either.
    $user = app(CreateNewUser::class)->create([
        'name' => 'Test User',
        'email' => '  New.Learner@Example.COM ',
    ]);

    expect($user->email)->toBe('new.learner@example.com');
});

it('rejects an email that differs from an existing account only in case or padding', function (string $existing, string $typed) {
    User::factory()->create(['email' => $existing]);

    $this->post(route('register.store'), ['name' => 'Test User', 'email' => $typed])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
    expect(User::query()->count())->toBe(1);
})->with([
    'typed in another case' => ['learner@example.com', 'Learner@Example.COM'],
    'typed with padding' => ['learner@example.com', ' learner@example.com '],
    'stored in another case' => ['Learner@Example.com', 'learner@example.com'],
]);
