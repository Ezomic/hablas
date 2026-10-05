<?php

declare(strict_types=1);

use App\Actions\Units\GetUnitProgress;
use App\Enums\CefrLevel;
use App\Enums\SrsCardState;
use App\Enums\WordState;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Models\WordTypingSupport;
use App\Services\UnitStars;
use App\Services\WordProgress;
use Tests\Fixtures\Lessons\LessonWorld;

function unitWithWords(int $count = 4): array
{
    $unit = Unit::factory()->create(['cefr_level' => CefrLevel::A1]);
    $items = VocabularyItem::factory()->count($count)->create(['unit_id' => $unit->id, 'language_id' => $unit->language_id]);

    return [$unit, $items, User::factory()->create()];
}

it('starts every word as new', function () {
    [, $items, $user] = unitWithWords();

    $states = (new WordProgress)->states($user, $items->pluck('id')->all());

    expect(collect($states)->every(fn (WordState $state): bool => $state === WordState::New))->toBeTrue();
});

it('moves a word through typing, known and solid', function () {
    [, $items, $user] = unitWithWords(3);
    [$typing, $known, $solid] = $items->pluck('id')->all();

    WordTypingSupport::query()->create(['user_id' => $user->id, 'vocabulary_item_id' => $typing, 'revealed' => 3]);
    WordTypingSupport::query()->create(['user_id' => $user->id, 'vocabulary_item_id' => $known, 'revealed' => 0]);
    SrsCard::factory()->create(['user_id' => $user->id, 'cardable_type' => VocabularyItem::class, 'cardable_id' => $solid, 'state' => SrsCardState::Review]);

    $states = (new WordProgress)->states($user, [$typing, $known, $solid]);

    expect($states[$typing])->toBe(WordState::Typing)
        ->and($states[$known])->toBe(WordState::Known)
        ->and($states[$solid])->toBe(WordState::Solid);
});

it('counts a word a check proved as known', function () {
    [$unit, $items, $user] = unitWithWords(2);
    UnitItemMastery::factory()->create(['user_id' => $user->id, 'unit_id' => $unit->id, 'masterable_type' => (new VocabularyItem)->getMorphClass(), 'masterable_id' => $items[0]->id]);

    expect((new WordProgress)->states($user, [$items[0]->id])[$items[0]->id])->toBe(WordState::Known);
});

it('shows a word the lessons have shown as seen', function () {
    [$unit] = LessonWorld::seededHotel();
    $user = LessonWorld::learner();
    LessonWorld::finishTeachingLessons($user, $unit);

    $states = (new WordProgress)->states($user, $unit->vocabularyItems()->pluck('id')->all());

    expect(collect($states)->contains(fn (WordState $state): bool => $state !== WordState::New))->toBeTrue();
});

it('gives the share of a unit known and a state for every word', function () {
    [$unit, $items, $user] = unitWithWords(4);

    foreach ($items->take(3) as $item) {
        WordTypingSupport::query()->create(['user_id' => $user->id, 'vocabulary_item_id' => $item->id, 'revealed' => 0]);
    }

    $summary = (new WordProgress)->forUnits($user, collect([$unit]))[$unit->id];

    expect($summary)->toMatchArray(['percent' => 75, 'known' => 3, 'total' => 4])
        ->and($summary['words'])->toHaveCount(4)
        ->and(array_column($summary['words'], 'state'))->toBe(['known', 'known', 'known', 'new']);
});

it('has no share for a unit without words', function () {
    $unit = Unit::factory()->create();

    expect((new WordProgress)->forUnits(User::factory()->create(), collect([$unit]))[$unit->id])->toMatchArray(['percent' => 0, 'known' => 0, 'total' => 0]);
});

it('adds the words known across a level', function () {
    [$unit, $items, $user] = unitWithWords(4);
    $other = Unit::factory()->create(['language_id' => $unit->language_id, 'cefr_level' => CefrLevel::A1]);
    $more = VocabularyItem::factory()->count(4)->create(['unit_id' => $other->id, 'language_id' => $unit->language_id]);

    foreach ([$items[0], $more[0], $more[1]] as $item) {
        WordTypingSupport::query()->create(['user_id' => $user->id, 'vocabulary_item_id' => $item->id, 'revealed' => 0]);
    }

    expect((new WordProgress)->forLevel($user, $unit->language, CefrLevel::A1))->toBe(['known' => 3, 'total' => 8, 'percent' => 37]);
});

it('gives a unit no stars until it is started, one for starting, two for the lessons and three for the words', function () {
    [$unit] = LessonWorld::seededHotel();
    $user = LessonWorld::learner();
    $stars = new UnitStars;

    expect($stars->handle($user, $unit, 0))->toBe(0);

    LessonWorld::finishTeachingLessons($user, $unit);

    expect($stars->handle($user, $unit, 40))->toBe(2)
        ->and($stars->handle($user, $unit, 90))->toBe(3);
});

it('shows the progress of the unit and of its level together', function () {
    [$unit, $items, $user] = unitWithWords(4);
    WordTypingSupport::query()->create(['user_id' => $user->id, 'vocabulary_item_id' => $items[0]->id, 'revealed' => 0]);

    $progress = (new GetUnitProgress)->handle($user, $unit->language, $unit);

    expect($progress)->toMatchArray(['percent' => 25, 'known' => 1, 'total' => 4, 'stars' => 0])
        ->and($progress['level'])->toBe(['code' => 'A1', 'known' => 1, 'total' => 4, 'percent' => 25]);
});

it('hands the progress to the unit page and the unit list', function () {
    [$unit] = LessonWorld::seededHotel();
    $user = LessonWorld::learner();
    $user->forceFill(['current_language_id' => $unit->language_id])->save();
    LessonWorld::finishTeachingLessons($user, $unit);

    $this->actingAs($user)
        ->get(route('units.show', $unit))
        ->assertInertia(fn ($page) => $page->has('progress.percent')->has('progress.stars')->has('progress.words')->has('progress.level.code'));

    $this->actingAs($user)
        ->get(route('units.index'))
        ->assertInertia(fn ($page) => $page->has('units.0.percent')->has('units.0.stars'));
});
