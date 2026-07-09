<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;

it('renders the snapshot and share url for the current language', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);

    $this->actingAs($user)
        ->get(route('progress.share.show'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('progress/Share')
            ->has('snapshot')
            ->where('shareUrl', fn (string $url) => str_contains($url, '/shared/')),
        );
});

it('renders an empty state with no current language', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('progress.share.show'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('progress/Share')
            ->where('snapshot', null)
            ->where('shareUrl', null),
        );
});

it('does not create a share row when there is no current language', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('progress.share.show'));

    expect(ProgressShare::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('regenerates the share link, invalidating the old token', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $language);

    $this->actingAs($user)->get(route('progress.share.show'));
    $oldToken = ProgressShare::query()->where('user_id', $user->id)->sole()->token;

    $this->actingAs($user)
        ->post(route('progress.share.regenerate'))
        ->assertRedirect(route('progress.share.show'));

    $newShare = ProgressShare::query()->where('user_id', $user->id)->whereNull('revoked_at')->sole();

    expect($newShare->token)->not->toBe($oldToken);

    $this->get(route('progress.public', $oldToken))->assertNotFound();
    $this->get(route('progress.public', $newShare->token))->assertOk();
});
