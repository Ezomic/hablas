<?php

use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Services\AdaptiveNewItemCap;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * SrsCard has a unique constraint on (user_id, cardable_type, cardable_id), so
 * each card needs its own cardable — explicit terms avoid exhausting Faker's
 * unique-word pool when creating cards in bulk.
 *
 * @return Collection<int, SrsCard>
 */
function createDueSrsCardsForAdaptiveCapTest(User $user, Language $language, int $count, CarbonImmutable $dueAt, bool $isWeakSpot = false): Collection
{
    return collect(range(1, $count))->map(function (int $i) use ($user, $language, $dueAt, $isWeakSpot) {
        $vocabularyItem = VocabularyItem::query()->create([
            'language_id' => $language->id,
            'term' => "word-{$i}-{$language->id}",
            'translation_en' => "translation-{$i}-{$language->id}",
            'part_of_speech' => 'noun',
        ]);

        return SrsCard::factory()->create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'cardable_type' => VocabularyItem::class,
            'cardable_id' => $vocabularyItem->id,
            'due_at' => $dueAt,
            'is_weak_spot' => $isWeakSpot,
        ]);
    });
}

it('returns the base cap when there is no review backlog', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    expect((new AdaptiveNewItemCap)->forUser($user, $language))->toBe(10);
});

it('lowers the cap once the due backlog crosses the moderate threshold', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    createDueSrsCardsForAdaptiveCapTest($user, $language, 50, CarbonImmutable::now());

    expect((new AdaptiveNewItemCap)->forUser($user, $language))->toBe(5);
});

it('drops the cap to zero once the due backlog crosses the heavy threshold', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    createDueSrsCardsForAdaptiveCapTest($user, $language, 100, CarbonImmutable::now());

    expect((new AdaptiveNewItemCap)->forUser($user, $language))->toBe(0);
});

it('raises the cap back up once the backlog is cleared', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $cards = createDueSrsCardsForAdaptiveCapTest($user, $language, 60, CarbonImmutable::now());

    expect((new AdaptiveNewItemCap)->forUser($user, $language))->toBe(5);

    $cards->each(fn (SrsCard $card) => $card->update(['due_at' => now()->addDays(3)]));

    expect((new AdaptiveNewItemCap)->forUser($user, $language))->toBe(10);
});

it('does not count cards that are not yet due', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    createDueSrsCardsForAdaptiveCapTest($user, $language, 60, CarbonImmutable::now()->addDay());

    expect((new AdaptiveNewItemCap)->forUser($user, $language))->toBe(10);
});

it('scopes the backlog to the given language', function () {
    $user = User::factory()->create();
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();
    createDueSrsCardsForAdaptiveCapTest($user, $spanish, 60, CarbonImmutable::now());

    expect((new AdaptiveNewItemCap)->forUser($user, $spanish))->toBe(5)
        ->and((new AdaptiveNewItemCap)->forUser($user, $portuguese))->toBe(10);
});

it('does not count weak-spot cards toward the backlog', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    createDueSrsCardsForAdaptiveCapTest($user, $language, 60, CarbonImmutable::now(), isWeakSpot: true);

    expect((new AdaptiveNewItemCap)->forUser($user, $language))->toBe(10);
});

it('scopes the backlog to the given user', function () {
    $language = Language::factory()->create();
    $userWithBacklog = User::factory()->create();
    $otherUser = User::factory()->create();
    createDueSrsCardsForAdaptiveCapTest($userWithBacklog, $language, 60, CarbonImmutable::now());

    expect((new AdaptiveNewItemCap)->forUser($userWithBacklog, $language))->toBe(5)
        ->and((new AdaptiveNewItemCap)->forUser($otherUser, $language))->toBe(10);
});
