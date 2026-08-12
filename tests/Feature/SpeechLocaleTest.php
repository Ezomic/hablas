<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\PronunciationDrillExercise;
use App\Models\ScriptedPromptExercise;
use App\Models\ShadowingExercise;
use App\Models\User;
use App\Services\SpeechLocaleResolver;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
});

function userOn(Language $language): User
{
    $user = User::factory()->create(['current_language_id' => $language->id]);
    (new UnlockLanguageForUser)->handle($user, $language);

    return $user;
}

it('resolves a tag per language', function () {
    $resolver = new SpeechLocaleResolver;

    expect($resolver->forLanguage($this->spanish))->toBe('es-ES')
        ->and($resolver->forLanguage($this->portuguese))->toBe('pt-PT');
});

it('returns nothing rather than guessing for an unknown language', function () {
    $unknown = Language::factory()->create(['code' => 'xx']);

    expect((new SpeechLocaleResolver)->forLanguage($unknown))->toBeNull();
});

it('serves the spanish tag to shadowing on the spanish deck', function () {
    ShadowingExercise::factory()->create(['language_id' => $this->spanish->id]);

    $this->actingAs(userOn($this->spanish))
        ->get(route('shadowing.index'))
        ->assertInertia(fn ($page) => $page->where('speechLocale', 'es-ES'));
});

it('serves the portuguese tag to shadowing on the portuguese deck', function () {
    ShadowingExercise::factory()->create(['language_id' => $this->portuguese->id]);

    $this->actingAs(userOn($this->portuguese))
        ->get(route('shadowing.index'))
        ->assertInertia(fn ($page) => $page->where('speechLocale', 'pt-PT'));
});

it('serves the portuguese tag to scripted prompts on the portuguese deck', function () {
    ScriptedPromptExercise::factory()->create(['language_id' => $this->portuguese->id]);

    $this->actingAs(userOn($this->portuguese))
        ->get(route('scripted-prompts.index'))
        ->assertInertia(fn ($page) => $page->where('speechLocale', 'pt-PT'));
});

it('serves the spanish tag to pronunciation drills on the spanish deck', function () {
    PronunciationDrillExercise::factory()->create(['language_id' => $this->spanish->id]);

    $this->actingAs(userOn($this->spanish))
        ->get(route('pronunciation-drills.index'))
        ->assertInertia(fn ($page) => $page->where('speechLocale', 'es-ES'));
});

it('serves no tag when the user has no active language', function () {
    // New users get Spanish unlocked by a listener, so this has to be undone
    // to reach the genuinely language-less branch.
    $user = User::factory()->create(['current_language_id' => null]);
    $user->unlockedLanguages()->detach();

    $this->actingAs($user)
        ->get(route('shadowing.index'))
        ->assertInertia(fn ($page) => $page->where('speechLocale', null));
});
