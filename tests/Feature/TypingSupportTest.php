<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\VocabularyItem;
use App\Models\WordTypingSupport;
use App\Services\TypingSupport;

it('starts a word with most of its letters given and none for a very short one', function () {
    $support = new TypingSupport;

    expect($support->initialReveal(8))->toBe(5)
        ->and($support->initialReveal(12))->toBe(8)
        ->and($support->initialReveal(3))->toBe(1)
        ->and($support->initialReveal(2))->toBe(0);
});

it('gives the first letter last and always keeps what is not a letter', function () {
    $mask = (new TypingSupport)->mask('el aeropuerto', 1);

    expect($mask[0])->toBe('e')
        ->and($mask[2])->toBe(' ')
        ->and(count(array_filter($mask, fn (?string $char): bool => $char === null)))->toBe(11);

    expect((new TypingSupport)->mask("l'hôtel", 0)[1])->toBe("'");
});

it('removes letters in a fixed order, so a smaller set is part of a larger one', function () {
    $support = new TypingSupport;
    $given = fn (int $revealed): array => array_keys(array_filter($support->mask('aeropuerto', $revealed), fn (?string $char): bool => $char !== null));

    foreach (range(1, 9) as $revealed) {
        expect(array_diff($given($revealed), $given($revealed + 1)))->toBe([]);
    }
});

it('takes a letter away for each unaided right answer and gives one back for a miss', function () {
    $support = new TypingSupport;
    $user = User::factory()->create();
    $item = VocabularyItem::factory()->create(['term' => 'aeropuerto']);

    expect($support->revealed($user, $item))->toBe(6);

    $support->record($user, $item, true);
    $support->record($user, $item, true);

    expect($support->revealed($user, $item))->toBe(4);

    $support->record($user, $item, false);

    expect($support->revealed($user, $item))->toBe(5);
});

it('never gives more than the word started with, nor fewer than none', function () {
    $support = new TypingSupport;
    $user = User::factory()->create();
    $item = VocabularyItem::factory()->create(['term' => 'maleta']);

    foreach (range(1, 5) as $ignored) {
        $support->record($user, $item, false);
    }

    expect($support->revealed($user, $item))->toBe($support->initialReveal(6));

    foreach (range(1, 9) as $ignored) {
        $support->record($user, $item, true);
    }

    expect($support->revealed($user, $item))->toBe(0)
        ->and($support->maskFor($user, $item))->toBeNull();
});

it('changes nothing when the learner used a hint', function () {
    $support = new TypingSupport;
    $user = User::factory()->create();
    $item = VocabularyItem::factory()->create(['term' => 'aeropuerto']);

    $support->record($user, $item, true, hinted: true);

    expect(WordTypingSupport::query()->count())->toBe(0);
});

it('keeps each learner and each word apart', function () {
    $support = new TypingSupport;
    $one = User::factory()->create();
    $two = User::factory()->create();
    $item = VocabularyItem::factory()->create(['term' => 'aeropuerto']);

    $support->record($one, $item, true);

    expect($support->revealed($one, $item))->toBe(5)
        ->and($support->revealed($two, $item))->toBe(6);
});
