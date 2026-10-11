<?php

declare(strict_types=1);

use App\Models\DutchMeaningSuggestion;
use App\Models\Language;
use App\Models\Unit;
use App\Models\User;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->french = Language::query()->where('code', 'fr')->sole();
    $this->user = User::factory()->create();
    $this->unitOne = Unit::factory()->create(['language_id' => $this->spanish->id, 'slug' => 'one', 'title' => 'One', 'sort_order' => 1]);
    $this->unitTwo = Unit::factory()->create(['language_id' => $this->spanish->id, 'slug' => 'two', 'title' => 'Two', 'sort_order' => 2]);
    $this->hola = VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $this->unitOne->id, 'term' => 'hola', 'translation_en' => 'hello', 'translation_nl' => 'hallo']);
    $this->adios = VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $this->unitTwo->id, 'term' => 'adiós', 'translation_en' => 'goodbye', 'translation_nl' => 'tot ziens']);
    $this->bonjour = VocabularyItem::factory()->create(['language_id' => $this->french->id, 'unit_id' => Unit::factory()->create(['language_id' => $this->french->id, 'slug' => 'fr-one', 'sort_order' => 1])->id, 'term' => 'bonjour', 'translation_en' => 'hello', 'translation_nl' => 'hallo']);
});

it('requires a signed-in user', function () {
    $this->get(route('dutch-review.index'))->assertRedirect(route('login'));
    $this->post(route('dutch-review.store', $this->hola), ['suggestion' => 'hoi'])->assertRedirect(route('login'));
});

it('lists the words of the first Spanish unit with their English and Dutch meanings by default', function () {
    $this->actingAs($this->user)->get(route('dutch-review.index'))->assertInertia(fn (Assert $page) => $page
        ->component('dutch-review/Index')
        ->where('language', 'es')
        ->where('unit', 'one')
        ->where('previous', null)
        ->where('next', 'two')
        ->has('languages', 2)
        ->has('units', 2)
        ->has('items', 1)
        ->where('items.0.term', 'hola')
        ->where('items.0.english', 'hello')
        ->where('items.0.dutch', 'hallo')
        ->where('items.0.suggestion', null));
});

it('moves between languages and units', function () {
    $this->actingAs($this->user)->get(route('dutch-review.index', ['language' => 'es', 'unit' => 'two']))->assertInertia(fn (Assert $page) => $page
        ->where('unit', 'two')
        ->where('previous', 'one')
        ->where('next', null)
        ->where('items.0.term', 'adiós'));

    $this->actingAs($this->user)->get(route('dutch-review.index', ['language' => 'fr']))->assertInertia(fn (Assert $page) => $page
        ->where('language', 'fr')
        ->where('items.0.term', 'bonjour'));
});

it('falls back to the first language and unit for an unknown one', function () {
    $this->actingAs($this->user)->get(route('dutch-review.index', ['language' => 'xx', 'unit' => 'nope']))->assertInertia(fn (Assert $page) => $page
        ->where('language', 'es')
        ->where('unit', 'one'));
});

it('leaves out a language that has no Dutch meanings yet', function () {
    VocabularyItem::query()->where('language_id', $this->french->id)->update(['translation_nl' => null]);

    $this->actingAs($this->user)->get(route('dutch-review.index'))->assertInertia(fn (Assert $page) => $page
        ->has('languages', 1)
        ->where('languages.0.code', 'es'));
});

it('stores a suggestion and shows it back, counted per unit', function () {
    $this->actingAs($this->user)->post(route('dutch-review.store', $this->hola), ['suggestion' => 'hoi', 'note' => 'informeler'])->assertRedirect();

    expect(DutchMeaningSuggestion::query()->where('user_id', $this->user->id)->sole())
        ->suggestion->toBe('hoi')
        ->note->toBe('informeler');

    $this->actingAs($this->user)->get(route('dutch-review.index'))->assertInertia(fn (Assert $page) => $page
        ->where('items.0.suggestion', 'hoi')
        ->where('items.0.note', 'informeler')
        ->where('units.0.suggestions', 1)
        ->where('units.1.suggestions', 0)
        ->where('suggestionCount', 1));
});

it('updates an existing suggestion instead of adding another', function () {
    $this->actingAs($this->user)->post(route('dutch-review.store', $this->hola), ['suggestion' => 'hoi']);
    $this->actingAs($this->user)->post(route('dutch-review.store', $this->hola), ['suggestion' => 'dag']);

    expect(DutchMeaningSuggestion::query()->count())->toBe(1)
        ->and(DutchMeaningSuggestion::query()->sole()->suggestion)->toBe('dag');
});

it('clears the suggestion when both fields are emptied', function () {
    $this->actingAs($this->user)->post(route('dutch-review.store', $this->hola), ['suggestion' => 'hoi']);
    $this->actingAs($this->user)->post(route('dutch-review.store', $this->hola), ['suggestion' => '  ', 'note' => '']);

    expect(DutchMeaningSuggestion::query()->count())->toBe(0);
});

it('keeps suggestions per user and never changes the stored meaning', function () {
    $other = User::factory()->create();
    $this->actingAs($other)->post(route('dutch-review.store', $this->hola), ['suggestion' => 'hoi']);

    $this->actingAs($this->user)->get(route('dutch-review.index'))->assertInertia(fn (Assert $page) => $page
        ->where('items.0.suggestion', null)
        ->where('suggestionCount', 0));

    expect($this->hola->fresh()?->translation_nl)->toBe('hallo');
});

it('rejects a suggestion that is too long', function () {
    $this->actingAs($this->user)->post(route('dutch-review.store', $this->hola), ['suggestion' => str_repeat('a', 256)])->assertSessionHasErrors('suggestion');
});
