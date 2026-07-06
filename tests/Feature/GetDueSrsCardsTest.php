<?php

use App\Actions\Srs\GetDueSrsCards;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;

it('returns only cards due now or earlier', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $due = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'due_at' => now()->subMinute()]);
    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'due_at' => now()->addDay()]);

    $result = (new GetDueSrsCards)->handle($user, $language);

    expect($result)->toHaveCount(1)
        ->and($result->first()->id)->toBe($due->id);
});

it('excludes weak-spot cards from the normal queue', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'due_at' => now()->subMinute(),
        'is_weak_spot' => true,
    ]);

    $result = (new GetDueSrsCards)->handle($user, $language);

    expect($result)->toHaveCount(0);
});

it('never mixes cards from different language decks', function () {
    $user = User::factory()->create();
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();

    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $spanish->id, 'due_at' => now()->subMinute()]);
    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $portuguese->id, 'due_at' => now()->subMinute()]);

    $result = (new GetDueSrsCards)->handle($user, $spanish);

    expect($result)->toHaveCount(1)
        ->and($result->first()->language_id)->toBe($spanish->id);
});

it('counts due cards using the same definition as handle()', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'due_at' => now()->subMinute()]);
    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'due_at' => now()->addDay()]);
    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'due_at' => now()->subMinute(), 'is_weak_spot' => true]);

    expect((new GetDueSrsCards)->count($user, $language))->toBe(1);
});
