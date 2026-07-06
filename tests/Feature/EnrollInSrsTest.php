<?php

use App\Actions\Srs\EnrollInSrs;
use App\Enums\SrsCardState;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Models\VocabularyItem;

it('creates a new card in the New state, due immediately', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $vocabularyItem = VocabularyItem::factory()->create(['language_id' => $language->id]);

    $card = (new EnrollInSrs)->handle($user, $language, $vocabularyItem);

    expect($card->state)->toBe(SrsCardState::New)
        ->and($card->cardable_id)->toBe($vocabularyItem->id)
        ->and($card->cardable_type)->toBe($vocabularyItem->getMorphClass())
        ->and($card->due_at->isPast() || $card->due_at->equalTo(now()))->toBeTrue();
});

it('does not enroll the same cardable twice for the same user', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $vocabularyItem = VocabularyItem::factory()->create(['language_id' => $language->id]);
    $action = new EnrollInSrs;

    $first = $action->handle($user, $language, $vocabularyItem);
    $second = $action->handle($user, $language, $vocabularyItem);

    expect($second->id)->toBe($first->id)
        ->and(SrsCard::query()->count())->toBe(1);
});

it('supports grammar points as cardables via the same polymorphic relation', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $grammarPoint = GrammarPoint::factory()->create(['language_id' => $language->id]);

    $card = (new EnrollInSrs)->handle($user, $language, $grammarPoint);

    expect($card->cardable_type)->toBe($grammarPoint->getMorphClass())
        ->and($card->cardable->is($grammarPoint))->toBeTrue();
});
