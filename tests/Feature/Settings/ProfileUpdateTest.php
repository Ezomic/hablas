<?php

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
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($user->refresh()->email_verified_at)->not->toBeNull();
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
