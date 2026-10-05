<?php

declare(strict_types=1);

use App\Actions\Srs\GradeTypedRecall;
use App\Actions\Srs\PresentSrsCardForReview;
use App\Enums\ReviewMode;
use App\Enums\SrsCardState;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\VocabularyItem;
use App\Services\TypingSupport;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
});

function typedRecallCard(User $user, Language $language, string $term, SrsCardState $state = SrsCardState::Review): SrsCard
{
    return SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => VocabularyItem::factory()->create(['language_id' => $language->id, 'term' => $term])->id,
        'state' => $state,
        'due_at' => now()->subMinute(),
        'last_reviewed_at' => $state === SrsCardState::New ? null : now()->subDay(),
    ]);
}

it('accepts an answer that differs only in accents, case and punctuation', function (string $term, string $answer) {
    $item = VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'term' => $term]);

    expect((new GradeTypedRecall)->handle($item, $answer))->toBeTrue();
})->with([
    ['adiós', 'adios'],
    ['¿cómo estás?', 'como estas'],
    ['el vuelo', '  El   Vuelo '],
    ['¿dónde está...?', 'dónde está'],
]);

it('rejects an answer that misses what the language keeps apart', function (string $term, string $answer) {
    $item = VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'term' => $term]);

    expect((new GradeTypedRecall)->handle($item, $answer))->toBeFalse();
})->with([
    'ñ is its own letter' => ['año', 'ano'],
    'the article is part of the word' => ['el vuelo', 'vuelo'],
    'a wrong article is a gender mistake' => ['la maleta', 'el maleta'],
    'a repeated word still counts' => ['poco a poco', 'poco a'],
    'word order counts' => ['buenos días', 'días buenos'],
]);

it('grades the same whether accents arrive composed or decomposed', function (string $term, string $answer, bool $correct) {
    $item = VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'term' => $term]);

    expect((new GradeTypedRecall)->handle($item, $answer))->toBe($correct);
})->with([
    'decomposed ñ typed for a composed term' => ["a\u{F1}o", "an\u{303}o", true],
    'composed ñ typed for a decomposed term' => ["an\u{303}o", "a\u{F1}o", true],
    'decomposed vowel accent is still forgiven' => ['adiós', "adio\u{301}s", true],
    'decomposed ñ is still not an n' => ["a\u{F1}o", 'ano', false],
]);

it('keeps portuguese nasal marks distinct', function () {
    $item = VocabularyItem::factory()->create(['language_id' => $this->portuguese->id, 'term' => 'o pão']);

    expect((new GradeTypedRecall)->handle($item, 'o pao'))->toBeFalse()
        ->and((new GradeTypedRecall)->handle($item, 'O Pão'))->toBeTrue();
});

it('reads a hyphen as a break between words', function () {
    $item = VocabularyItem::factory()->create(['language_id' => $this->portuguese->id, 'term' => 'o pequeno-almoço']);

    expect((new GradeTypedRecall)->handle($item, 'o pequeno almoço'))->toBeTrue()
        ->and((new GradeTypedRecall)->handle($item, 'o pequeno-almoço'))->toBeTrue();
});

it('checks a typed answer without recording a review', function () {
    $user = User::factory()->create();
    $card = typedRecallCard($user, $this->spanish, 'el aeropuerto');

    $this->actingAs($user)
        ->postJson(route('review.answers.check', $card), ['answer' => 'el aeropuerto'])
        ->assertOk()
        ->assertExactJson(['correct' => true]);

    $this->actingAs($user)
        ->postJson(route('review.answers.check', $card), ['answer' => 'aeropuerto'])
        ->assertOk()
        ->assertExactJson(['correct' => false]);

    expect(SrsReview::query()->where('srs_card_id', $card->id)->exists())->toBeFalse()
        ->and($card->fresh()?->reps)->toBe($card->reps);
});

it('hides another user\'s card behind a 404 when checking an answer', function () {
    $card = typedRecallCard(User::factory()->create(), $this->spanish, 'el vuelo');

    $this->actingAs(User::factory()->create())
        ->postJson(route('review.answers.check', $card), ['answer' => 'el vuelo'])
        ->assertNotFound();
});

it('refuses to check an answer for a grammar card', function () {
    $user = User::factory()->create();
    $card = SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'cardable_type' => GrammarPoint::class,
        'cardable_id' => GrammarPoint::factory()->create(['language_id' => $this->spanish->id])->id,
    ]);

    $this->actingAs($user)
        ->postJson(route('review.answers.check', $card), ['answer' => 'ser'])
        ->assertUnprocessable();
});

it('validates the typed answer', function (array $payload) {
    $user = User::factory()->create();
    $card = typedRecallCard($user, $this->spanish, 'el vuelo');

    $this->actingAs($user)
        ->postJson(route('review.answers.check', $card), $payload)
        ->assertInvalid(['answer']);
})->with([
    'missing' => [[]],
    'blank' => [['answer' => '']],
    'not a string' => [['answer' => ['el vuelo']]],
    'too long' => [['answer' => str_repeat('a', 201)]],
]);

it('asks for typed recall on both review pages when the learner chose it', function (string $routeName, array $cardAttributes) {
    $user = User::factory()->create();
    UserSetting::factory()->for($user)->create(['review_mode' => ReviewMode::Production]);
    $card = typedRecallCard($user, $this->spanish, 'el vuelo');
    $card->forceFill($cardAttributes)->save();

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('cards.0.direction', 'production')
            ->where('cards.0.back', 'el vuelo'),
        );
})->with([
    'review' => ['review.index', []],
    'weak spots' => ['review.weak-spots.index', ['is_weak_spot' => true]],
]);

it('keeps recognition as the default review style', function (string $routeName, array $cardAttributes) {
    $user = User::factory()->create();
    $card = typedRecallCard($user, $this->spanish, 'el vuelo');
    $card->forceFill($cardAttributes)->save();

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('cards.0.direction', 'recognition')
            ->where('cards.0.front', 'el vuelo'),
        );
})->with([
    'review' => ['review.index', []],
    'weak spots' => ['review.weak-spots.index', ['is_weak_spot' => true]],
]);

it('asks for typed recall in mix only for words in the review state', function () {
    $user = User::factory()->create();
    UserSetting::factory()->for($user)->create(['review_mode' => ReviewMode::Mix]);
    typedRecallCard($user, $this->spanish, 'el vuelo', SrsCardState::Review);
    typedRecallCard($user, $this->spanish, 'la maleta', SrsCardState::Learning);
    typedRecallCard($user, $this->spanish, 'el billete', SrsCardState::Relearning);
    typedRecallCard($user, $this->spanish, 'la salida', SrsCardState::New);

    $response = $this->actingAs($user)->get(route('review.index'))->assertOk();

    /** @var list<array{back: string, front: string, direction: string}> $cards */
    $cards = $response->viewData('page')['props']['cards'];
    $directions = collect($cards)->mapWithKeys(fn (array $card): array => [
        $card['direction'] === 'production' ? $card['back'] : $card['front'] => $card['direction'],
    ])->all();

    expect($directions)->toEqual([
        'el vuelo' => 'production',
        'la maleta' => 'recognition',
        'el billete' => 'recognition',
        'la salida' => 'recognition',
    ]);
});

it('lowers the letters given for a word after a right typed answer and raises them after a miss', function () {
    $user = User::factory()->create();
    UserSetting::factory()->for($user)->create(['review_mode' => ReviewMode::Production]);
    $card = typedRecallCard($user, $this->spanish, 'aeropuerto', SrsCardState::New);
    $item = $card->cardable;
    $support = new TypingSupport;
    $start = $support->revealed($user->id, $item);

    $this->actingAs($user)
        ->postJson(route('review.answers.check', $card), ['answer' => 'aeropuerto'])
        ->assertOk()
        ->assertJson(['correct' => true]);

    expect($support->revealed($user->id, $item))->toBe($start - 1);

    $this->actingAs($user)
        ->postJson(route('review.answers.check', $card), ['answer' => 'zzz'])
        ->assertJson(['correct' => false]);

    expect($support->revealed($user->id, $item))->toBe($start - 1);

    $this->actingAs($user)
        ->postJson(route('review.reviews.store', $card), ['rating' => 'again'])
        ->assertOk();

    expect($support->revealed($user->id, $item))->toBe($start);
});

it('presents a typed card with its letter mask, which fades as the word is known', function () {
    $user = User::factory()->create();
    $card = typedRecallCard($user, $this->spanish, 'aeropuerto');
    $support = new TypingSupport;

    $mask = (new PresentSrsCardForReview)->handle($card->load('cardable', 'user'), ReviewMode::Production)['mask'];

    expect($mask)->toBeArray()->and(count(array_filter($mask, fn (?string $char): bool => $char !== null)))->toBe($support->initialReveal(10));
});
