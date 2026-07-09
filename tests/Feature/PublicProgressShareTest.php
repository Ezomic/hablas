<?php

use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;

it('renders the shared snapshot without requiring authentication', function () {
    $user = User::factory()->create(['name' => 'Ada']);
    $language = Language::factory()->create();
    $share = ProgressShare::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    $this->get(route('progress.public', $share->token))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('progress/Public')
            ->has('snapshot')
            ->where('ownerName', 'Ada'),
        );
});

it('404s for an unknown token', function () {
    $this->get(route('progress.public', 'not-a-real-token'))->assertNotFound();
});

it('404s for a revoked token', function () {
    $share = ProgressShare::factory()->create(['revoked_at' => now()]);

    $this->get(route('progress.public', $share->token))->assertNotFound();
});

it('renders the share owners language, not any visitor session state', function () {
    $owner = User::factory()->create();
    $ownerLanguage = Language::factory()->create(['code' => 'pt', 'name' => 'Portuguese']);
    $share = ProgressShare::factory()->create(['user_id' => $owner->id, 'language_id' => $ownerLanguage->id]);

    $this->get(route('progress.public', $share->token))
        ->assertInertia(fn ($page) => $page->where('snapshot.language.code', 'pt'));
});
