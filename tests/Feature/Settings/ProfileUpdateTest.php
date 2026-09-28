<?php

declare(strict_types=1);

use App\Models\User;

it('displays the profile page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
});

it('updates profile information', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

it('leaves email verification status unchanged when the email is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

it('makes a user confirm it is them before changing their email', function () {
    $user = User::factory()->create(['email' => 'me@example.com']);

    $this->actingAs($user)
        ->patch(route('profile.update'), ['name' => $user->name, 'email' => 'new@example.com'])
        ->assertRedirect(route('password.confirm'));

    expect($user->refresh()->email)->toBe('me@example.com')
        ->and($user->email_verified_at)->not->toBeNull();
});

it('allows a user to delete their account once they have confirmed it is them', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->delete(route('profile.destroy'));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

it('makes a user confirm it is them before deleting the account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'));

    // The password.confirm middleware now re-authenticates with an emailed
    // code, so an unconfirmed session is bounced rather than deleting anything.
    $response->assertRedirect(route('password.confirm'));

    expect($user->fresh())->not->toBeNull();
});

it('stores a changed email lowercased and trimmed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->patch(route('profile.update'), ['name' => $user->name, 'email' => ' New.Address@Example.COM '])
        ->assertSessionHasNoErrors();

    expect($user->refresh()->email)->toBe('new.address@example.com');
});

it('rejects an email that differs from another account only in case or padding', function (string $existing, string $typed) {
    User::factory()->create(['email' => $existing]);
    $user = User::factory()->create(['email' => 'me@example.com']);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->patch(route('profile.update'), ['name' => $user->name, 'email' => $typed])
        ->assertSessionHasErrors('email');

    expect($user->refresh()->email)->toBe('me@example.com');
})->with([
    'typed in another case' => ['learner@example.com', 'Learner@Example.COM'],
    'typed with padding' => ['learner@example.com', ' learner@example.com '],
    'stored in another case' => ['Learner@Example.com', 'learner@example.com'],
]);

it('does not count the user\'s own address as taken', function () {
    $user = User::factory()->create(['email' => 'me@example.com']);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->patch(route('profile.update'), ['name' => 'New Name', 'email' => 'Me@Example.com'])
        ->assertSessionHasNoErrors();

    expect($user->refresh()->name)->toBe('New Name');
});
