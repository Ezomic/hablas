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
