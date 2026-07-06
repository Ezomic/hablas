<?php

use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Srs\ResolveWeakSpot;
use App\Models\SrsCard;

it('clears the weak-spot flag and resets consecutive lapses', function () {
    $card = SrsCard::factory()->create([
        'is_weak_spot' => true,
        'consecutive_lapses' => 3,
        'due_at' => now()->addWeek(),
    ]);

    (new ResolveWeakSpot)->handle($card);

    expect($card->is_weak_spot)->toBeFalse()
        ->and($card->consecutive_lapses)->toBe(0)
        ->and($card->due_at->isPast())->toBeTrue();
});

it('allows a resolved card back into the normal due queue', function () {
    $card = SrsCard::factory()->create([
        'is_weak_spot' => true,
        'due_at' => now()->subMinute(),
    ]);

    expect((new GetDueSrsCards)->handle($card->user, $card->language))->toHaveCount(0);

    (new ResolveWeakSpot)->handle($card);

    expect((new GetDueSrsCards)->handle($card->user, $card->language))->toHaveCount(1);
});
