<?php

declare(strict_types=1);

use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;

it('keeps the cards in one learner\'s deck that are scheduled by due date', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $inDeck = ['user_id' => $user->id, 'language_id' => $language->id];

    $scheduled = collect([SrsCardState::Learning, SrsCardState::Review, SrsCardState::Relearning])
        ->map(fn (SrsCardState $state): int => SrsCard::factory()->create([...$inDeck, 'state' => $state])->id);

    SrsCard::factory()->create([...$inDeck, 'state' => SrsCardState::New]);
    SrsCard::factory()->create([...$inDeck, 'state' => SrsCardState::Review, 'is_weak_spot' => true]);
    SrsCard::factory()->create([...$inDeck, 'state' => SrsCardState::Review, 'language_id' => Language::factory()->create()->id]);
    SrsCard::factory()->create([...$inDeck, 'state' => SrsCardState::Review, 'user_id' => User::factory()->create()->id]);

    expect(SrsCard::query()->repetitionsInDeck($user, $language)->orderBy('id')->pluck('id')->all())
        ->toBe($scheduled->all());
});
