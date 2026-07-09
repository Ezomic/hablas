<?php

use App\Actions\Languages\SwitchCurrentLanguage;
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
        ->post(route('progress.share.regenerate'), ['language_id' => $language->id])
        ->assertRedirect(route('progress.share.show'));

    $newShare = ProgressShare::query()->where('user_id', $user->id)->whereNull('revoked_at')->sole();

    expect($newShare->token)->not->toBe($oldToken);

    $this->get(route('progress.public', $oldToken))->assertNotFound();
    $this->get(route('progress.public', $newShare->token))->assertOk();
});

it('regenerates the share for the language shown on screen, not whatever is current when clicked', function () {
    $user = User::factory()->create();
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();
    (new UnlockLanguageForUser)->handle($user, $spanish);
    (new UnlockLanguageForUser)->handle($user, $portuguese);

    $this->actingAs($user)->get(route('progress.share.show'));
    $spanishToken = ProgressShare::query()->where('user_id', $user->id)->where('language_id', $spanish->id)->sole()->token;

    (new SwitchCurrentLanguage)->handle($user, $portuguese->id);

    $this->actingAs($user)
        ->post(route('progress.share.regenerate'), ['language_id' => $spanish->id])
        ->assertRedirect(route('progress.share.show'));

    $this->get(route('progress.public', $spanishToken))->assertNotFound();
    expect(ProgressShare::query()->where('user_id', $user->id)->where('language_id', $portuguese->id)->exists())->toBeFalse();
});

it('rejects regenerating for a language the user has not unlocked', function () {
    $user = User::factory()->create();
    $otherLanguage = Language::factory()->create();

    $this->actingAs($user)
        ->post(route('progress.share.regenerate'), ['language_id' => $otherLanguage->id])
        ->assertInvalid(['language_id']);
});
