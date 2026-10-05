<?php

declare(strict_types=1);

use App\Actions\Srs\PresentSrsCardForReview;
use App\Enums\ErrorTagCategory;
use App\Enums\ReviewMode;
use App\Enums\SrsCardState;
use App\Models\GrammarPoint;
use App\Models\SrsCard;
use App\Models\VocabularyItem;
use App\Services\TypingSupport;

it('presents a vocabulary card using its term and translation', function () {
    $vocabularyItem = VocabularyItem::factory()->create([
        'term' => 'gato',
        'translation_en' => 'cat',
    ]);
    $card = SrsCard::factory()->create([
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $vocabularyItem->id,
    ]);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($presented)->toBe([
        'id' => $card->id,
        'front' => 'gato',
        'back' => 'cat',
        'kind' => 'vocabulary',
        'exercise' => 'flip',
        'options' => null,
        'direction' => 'recognition',
        'needsArticle' => false,
        'mask' => null,
        'suggestedErrorTag' => null,
    ]);
});

it('presents a grammar card using its title and explanation', function () {
    $grammarPoint = GrammarPoint::factory()->create([
        'title' => 'Ser vs estar',
        'explanation' => 'Ser is for permanent traits, estar for states and locations.',
        'error_tag_category' => ErrorTagCategory::SerEstarConfusion,
    ]);
    $card = SrsCard::factory()->create([
        'cardable_type' => GrammarPoint::class,
        'cardable_id' => $grammarPoint->id,
    ]);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($presented)->toBe([
        'id' => $card->id,
        'front' => 'Ser vs estar',
        'back' => 'Ser is for permanent traits, estar for states and locations.',
        'kind' => 'grammar',
        'exercise' => 'flip',
        'options' => null,
        'direction' => 'recognition',
        'needsArticle' => false,
        'mask' => null,
        'suggestedErrorTag' => 'ser_estar_confusion',
    ]);
});

it('suggests no error tag for a grammar point authored without one', function () {
    $grammarPoint = GrammarPoint::factory()->create(['error_tag_category' => null]);
    $card = SrsCard::factory()->create([
        'cardable_type' => GrammarPoint::class,
        'cardable_id' => $grammarPoint->id,
    ]);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($presented['kind'])->toBe('grammar')
        ->and($presented['suggestedErrorTag'])->toBeNull();
});

it('asks for the word from its translation in production mode', function () {
    $vocabularyItem = VocabularyItem::factory()->create([
        'term' => 'el gato',
        'translation_en' => 'cat',
        'part_of_speech' => 'noun',
    ]);
    $card = SrsCard::factory()->create([
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $vocabularyItem->id,
    ]);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'), ReviewMode::Production);

    expect($presented)->toBe([
        'id' => $card->id,
        'front' => 'cat',
        'back' => 'el gato',
        'kind' => 'vocabulary',
        'exercise' => 'type',
        'options' => null,
        'direction' => 'production',
        'needsArticle' => true,
        'mask' => (new TypingSupport)->mask('el gato', (new TypingSupport)->initialReveal(6)),
        'suggestedErrorTag' => null,
    ]);
});

it('only asks for the article when the word is a noun', function (string $partOfSpeech, bool $needsArticle) {
    $vocabularyItem = VocabularyItem::factory()->create(['part_of_speech' => $partOfSpeech]);
    $card = SrsCard::factory()->create([
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $vocabularyItem->id,
    ]);

    expect((new PresentSrsCardForReview)->handle($card->load('cardable'), ReviewMode::Production)['needsArticle'])->toBe($needsArticle);
})->with([
    ['noun', true],
    ['verb', false],
    ['phrase', false],
]);

it('keeps a grammar card as recognition whatever the mode', function (ReviewMode $mode) {
    $grammarPoint = GrammarPoint::factory()->create(['title' => 'Ser vs estar']);
    $card = SrsCard::factory()->create([
        'cardable_type' => GrammarPoint::class,
        'cardable_id' => $grammarPoint->id,
        'state' => SrsCardState::Review,
    ]);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'), $mode);

    expect($presented['direction'])->toBe('recognition')
        ->and($presented['front'])->toBe('Ser vs estar');
})->with([ReviewMode::Production, ReviewMode::Mix]);

it('asks for the word in mix mode only once it has graduated to review', function (SrsCardState $state, string $direction) {
    $card = SrsCard::factory()->create(['state' => $state]);

    expect((new PresentSrsCardForReview)->handle($card->load('cardable'), ReviewMode::Mix)['direction'])->toBe($direction);
})->with([
    [SrsCardState::New, 'recognition'],
    [SrsCardState::Learning, 'recognition'],
    [SrsCardState::Relearning, 'recognition'],
    [SrsCardState::Review, 'production'],
]);

function choiceCard(SrsCardState $state, string $term = 'el gato', string $translation = 'cat'): SrsCard
{
    $item = VocabularyItem::factory()->create(['term' => $term, 'translation_en' => $translation, 'part_of_speech' => 'noun']);

    foreach ([['el perro', 'dog'], ['el pez', 'fish'], ['el pato', 'duck'], ['la casa', 'house']] as [$otherTerm, $otherTranslation]) {
        VocabularyItem::factory()->create(['language_id' => $item->language_id, 'term' => $otherTerm, 'translation_en' => $otherTranslation, 'part_of_speech' => 'noun']);
    }

    return SrsCard::factory()->create([
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $item->id,
        'state' => $state,
    ]);
}

it('asks a new word as a choice of its meaning', function (SrsCardState $state) {
    $card = choiceCard($state);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($presented['exercise'])->toBe('choose_meaning')
        ->and($presented['front'])->toBe('el gato')
        ->and($presented['back'])->toBe('cat')
        ->and($presented['direction'])->toBe('recognition')
        ->and($presented['options'])->toHaveCount(4)
        ->toContain('cat')
        ->not->toContain('house');
})->with([SrsCardState::New, SrsCardState::Learning]);

it('asks a graduated word as a choice of the word itself', function () {
    $card = choiceCard(SrsCardState::Review);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($presented['exercise'])->toBe('choose_word')
        ->and($presented['front'])->toBe('cat')
        ->and($presented['back'])->toBe('el gato')
        ->and($presented['direction'])->toBe('production')
        ->and($presented['needsArticle'])->toBeFalse()
        ->and($presented['mask'])->toBeNull()
        ->and($presented['options'])->toContain('el gato');
});

it('keeps the same options every time the card is shown', function () {
    $card = choiceCard(SrsCardState::New);

    $first = (new PresentSrsCardForReview)->handle($card->load('cardable'));
    $second = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($second['options'])->toBe($first['options']);
});

it('falls back to a flashcard when the language has too few similar words', function () {
    $item = VocabularyItem::factory()->create(['part_of_speech' => 'noun']);
    $card = SrsCard::factory()->create([
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $item->id,
        'state' => SrsCardState::New,
    ]);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($presented['exercise'])->toBe('flip')
        ->and($presented['options'])->toBeNull();
});

it('never mixes in the translation or the term of the answer', function () {
    $card = choiceCard(SrsCardState::New);
    VocabularyItem::factory()->create(['language_id' => $card->cardable->language_id, 'term' => 'el felino', 'translation_en' => 'Cat', 'part_of_speech' => 'noun']);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect(array_count_values(array_map('mb_strtolower', $presented['options']))['cat'])->toBe(1);
});

it('prefers words with the same article as distractors for a noun', function () {
    $card = choiceCard(SrsCardState::Review);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'));

    expect($presented['options'])->not->toContain('la casa');
});

it('never offers a choice in production mode', function () {
    $card = choiceCard(SrsCardState::New);

    $presented = (new PresentSrsCardForReview)->handle($card->load('cardable'), ReviewMode::Production);

    expect($presented['exercise'])->toBe('type')->and($presented['options'])->toBeNull();
});

it('keeps a grammar card a flashcard', function () {
    $card = SrsCard::factory()->create([
        'cardable_type' => GrammarPoint::class,
        'cardable_id' => GrammarPoint::factory()->create()->id,
        'state' => SrsCardState::New,
    ]);

    expect((new PresentSrsCardForReview)->handle($card->load('cardable'))['exercise'])->toBe('flip');
});
