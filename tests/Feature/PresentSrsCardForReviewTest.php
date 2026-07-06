<?php

use App\Actions\Srs\PresentSrsCardForReview;
use App\Models\GrammarPoint;
use App\Models\SrsCard;
use App\Models\VocabularyItem;

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

    expect($presented)->toBe(['id' => $card->id, 'front' => 'gato', 'back' => 'cat']);
});

it('presents a grammar card using its title and explanation', function () {
    $grammarPoint = GrammarPoint::factory()->create([
        'title' => 'Ser vs estar',
        'explanation' => 'Ser is for permanent traits, estar for states and locations.',
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
    ]);
});
