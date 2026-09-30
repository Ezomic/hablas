<?php

declare(strict_types=1);

use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\SrsCardState;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
    $this->user = User::factory()->create();
});

/**
 * @param  array<string, mixed>  $item
 * @param  array<string, mixed>  $card
 */
function enrolWordForLookup(User $user, Language $language, array $item = [], array $card = []): SrsCard
{
    $vocabularyItem = VocabularyItem::factory()->create(['language_id' => $language->id, ...$item]);

    return SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $vocabularyItem->id,
        ...$card,
    ]);
}

/**
 * @param  array<string, mixed>  $parameters
 * @return list<string>
 */
function lookupTerms(array $parameters = []): array
{
    $terms = [];

    test()->get(route('vocabulary.index', $parameters))
        ->assertOk()
        ->assertInertia(function (Assert $page) use (&$terms) {
            $page->component('vocabulary/Index');
            $terms = array_column($page->toArray()['props']['items'], 'term');
        });

    return $terms;
}

it('finds a word from an unaccented or upper-case query, and through its translation', function (string $query) {
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'adiós', 'translation_en' => 'goodbye']);
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'hola', 'translation_en' => 'hello']);

    $this->actingAs($this->user);

    expect(lookupTerms(['q' => $query]))->toBe(['adiós']);
})->with(['adios', 'ADIÓS', 'goodbye', 'Good']);

it('folds ñ for search, so ano finds año', function () {
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'el año', 'translation_en' => 'year']);

    $this->actingAs($this->user);

    expect(lookupTerms(['q' => 'ano']))->toBe(['el año']);
});

it('finds a phrase by a run of its words, repeated words included', function () {
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'poco a poco', 'translation_en' => 'little by little']);
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'poco', 'translation_en' => 'a little']);

    $this->actingAs($this->user);

    expect(lookupTerms(['q' => 'a poco']))->toBe(['poco a poco'])
        ->and(lookupTerms(['q' => '¿Poco, a poco?']))->toBe(['poco a poco']);
});

it('matches a grammar point by its title, not its explanation', function () {
    $grammarPoint = GrammarPoint::factory()->create([
        'language_id' => $this->spanish->id,
        'title' => 'Ser vs estar',
        'explanation' => 'Ser is for permanent traits.',
    ]);
    SrsCard::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->spanish->id,
        'cardable_type' => GrammarPoint::class,
        'cardable_id' => $grammarPoint->id,
    ]);

    $this->actingAs($this->user);

    expect(lookupTerms(['q' => 'estar']))->toBe(['Ser vs estar'])
        ->and(lookupTerms(['q' => 'permanent']))->toBe([]);
});

it('lists only the current language deck of the signed-in user', function () {
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'la casa', 'translation_en' => 'house']);
    enrolWordForLookup($this->user, $this->portuguese, ['term' => 'a casa', 'translation_en' => 'house']);
    enrolWordForLookup(User::factory()->create(), $this->spanish, ['term' => 'la casita', 'translation_en' => 'little house']);
    VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'term' => 'el casero', 'translation_en' => 'landlord']);

    $this->actingAs($this->user);

    expect(lookupTerms(['q' => 'cas']))->toBe(['la casa']);
});

it('lists the Portuguese deck once Portuguese is the current language', function () {
    (new UnlockLanguageForUser)->handle($this->user, $this->portuguese);
    $this->user->forceFill(['current_language_id' => $this->portuguese->id])->save();
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'la acción', 'translation_en' => 'action']);
    enrolWordForLookup($this->user, $this->portuguese, ['term' => 'a ação', 'translation_en' => 'action']);

    $this->actingAs($this->user);

    expect(lookupTerms(['q' => 'action']))->toBe(['a ação'])
        ->and(lookupTerms(['q' => 'acao']))->toBe(['a ação']);

    $this->get(route('vocabulary.index'))
        ->assertInertia(fn (Assert $page) => $page->where('speechLocale', 'pt-PT'));
});

it('serves the deck 25 items a page and echoes the filters for the page links', function () {
    foreach (range(1, 26) as $index) {
        enrolWordForLookup($this->user, $this->spanish, ['term' => sprintf('palabra %02d', $index), 'translation_en' => "word {$index}"]);
    }

    $this->actingAs($this->user)
        ->get(route('vocabulary.index', ['q' => 'palabra', 'sort' => 'alphabetical']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('vocabulary/Index')
            ->has('items', 25)
            ->where('items.0.term', 'palabra 01')
            ->where('pagination', ['currentPage' => 1, 'lastPage' => 2, 'total' => 26])
            ->where('filters', ['q' => 'palabra', 'sort' => 'alphabetical']),
        );

    $this->get(route('vocabulary.index', ['q' => 'palabra', 'sort' => 'alphabetical', 'page' => 2]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.term', 'palabra 26')
            ->where('pagination', ['currentPage' => 2, 'lastPage' => 2, 'total' => 26]),
        );
});

it('serves the last page for a page number past the end', function () {
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'hola']);

    $this->actingAs($this->user)
        ->get(route('vocabulary.index', ['page' => 9]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('pagination', ['currentPage' => 1, 'lastPage' => 1, 'total' => 1]),
        );
});

it('sorts alphabetically without the article or the opening question mark', function () {
    foreach (['la maleta', 'bien', '¿cómo estás?', 'el aeropuerto', 'los abuelos'] as $term) {
        enrolWordForLookup($this->user, $this->spanish, ['term' => $term]);
    }

    $this->actingAs($this->user);

    expect(lookupTerms(['sort' => 'alphabetical']))
        ->toBe(['los abuelos', 'el aeropuerto', 'bien', '¿cómo estás?', 'la maleta']);
});

it('sorts by most recently added by default', function () {
    $twoDaysAgo = now()->subDays(2)->startOfSecond();
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'primero'], ['created_at' => $twoDaysAgo]);
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'tercero'], ['created_at' => now()->subDay()]);
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'segundo'], ['created_at' => $twoDaysAgo]);

    $this->actingAs($this->user);

    expect(lookupTerms())->toBe(['tercero', 'segundo', 'primero'])
        ->and(lookupTerms(['sort' => 'recent']))->toBe(['tercero', 'segundo', 'primero']);
});

it('sorts by due date, with words not yet studied after the scheduled ones', function () {
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'nuevo'], ['state' => SrsCardState::New, 'due_at' => now()->subDays(5)]);
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'mañana'], ['state' => SrsCardState::Review, 'due_at' => now()->addDay()]);
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'ayer'], ['state' => SrsCardState::Relearning, 'due_at' => now()->subDay()]);
    enrolWordForLookup($this->user, $this->spanish, ['term' => 'hoy'], ['state' => SrsCardState::Learning, 'due_at' => now()]);

    $this->actingAs($this->user);

    expect(lookupTerms(['sort' => 'due']))->toBe(['ayer', 'hoy', 'mañana', 'nuevo']);
});

it('carries the state, due date and weak-spot flag of each card', function () {
    $dueAt = now()->addDays(3)->startOfSecond();
    $card = enrolWordForLookup(
        $this->user,
        $this->spanish,
        ['term' => 'la llave', 'translation_en' => 'key'],
        ['state' => SrsCardState::Relearning, 'due_at' => $dueAt, 'is_weak_spot' => true],
    );

    $this->actingAs($this->user)
        ->get(route('vocabulary.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('items.0', [
                'id' => $card->id,
                'kind' => 'vocabulary',
                'term' => 'la llave',
                'translation' => 'key',
                'state' => 'relearning',
                'dueAt' => $dueAt->toIso8601String(),
                'isWeakSpot' => true,
            ])
            ->where('speechLocale', 'es-ES'),
        );
});

it('rejects a malformed filter', function (array $parameters, string $field) {
    $this->actingAs($this->user)
        ->get(route('vocabulary.index', $parameters))
        ->assertSessionHasErrors($field);
})->with([
    'unknown sort' => [['sort' => 'random'], 'sort'],
    'page zero' => [['page' => 0], 'page'],
    'query too long' => [['q' => str_repeat('a', 101)], 'q'],
]);

it('requires a signed-in user', function () {
    $this->get(route('vocabulary.index'))->assertRedirect(route('login'));
});

it('renders an empty deck for a user without a language', function () {
    $user = User::factory()->create();
    $user->unlockedLanguages()->detach();

    $this->actingAs($user)
        ->get(route('vocabulary.index', ['q' => 'hola']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('vocabulary/Index')
            ->where('items', [])
            ->where('pagination', ['currentPage' => 1, 'lastPage' => 1, 'total' => 0])
            ->where('filters', ['q' => 'hola', 'sort' => 'recent'])
            ->where('speechLocale', null),
        );
});
