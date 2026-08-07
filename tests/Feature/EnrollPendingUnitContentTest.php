<?php

use App\Actions\CompleteUnit;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\Srs\EnrollPendingUnitContent;
use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\Skill;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

function unitWithVocabulary(Language $language, int $items, int $sortOrder = 1): Unit
{
    $unit = Unit::factory()->create([
        'language_id' => $language->id,
        'context_tag' => ContextTag::Travel,
        'cefr_level' => CefrLevel::A1,
        'primary_skill' => Skill::Reading,
        'sort_order' => $sortOrder,
    ]);

    VocabularyItem::factory()->count($items)->create([
        'language_id' => $language->id,
        'unit_id' => $unit->id,
    ]);

    return $unit;
}

it('enrols nothing for a user with no completed units', function () {
    unitWithVocabulary($this->spanish, 5);

    $result = (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);

    expect($result)->toBe(['enrolled' => 0, 'deferred' => 0])
        ->and(SrsCard::query()->count())->toBe(0);
});

it('brings in what an earlier cap held back once there is room', function () {
    UserSetting::factory()->create(['user_id' => $this->user->id, 'new_item_cap_override' => 2]);
    $unit = unitWithVocabulary($this->spanish, 6);

    (new CompleteUnit)->handle($this->user, $unit);
    expect(SrsCard::query()->count())->toBe(2);

    // A later day: nothing has been enrolled yet today, so the cap is free again.
    SrsCard::query()->update(['created_at' => now()->subDay()]);

    $result = (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);

    expect($result['enrolled'])->toBe(2)
        ->and($result['deferred'])->toBe(2)
        ->and(SrsCard::query()->count())->toBe(4);
});

it('keeps topping up across days until the unit is fully enrolled', function () {
    UserSetting::factory()->create(['user_id' => $this->user->id, 'new_item_cap_override' => 2]);
    $unit = unitWithVocabulary($this->spanish, 5);

    (new CompleteUnit)->handle($this->user, $unit);

    for ($day = 0; $day < 3; $day++) {
        SrsCard::query()->update(['created_at' => now()->subDay()]);
        (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);
    }

    expect(SrsCard::query()->count())->toBe(5)
        ->and((new EnrollPendingUnitContent)->handle($this->user, $this->spanish))
        ->toBe(['enrolled' => 0, 'deferred' => 0]);
});

it('still respects the cap while topping up', function () {
    UserSetting::factory()->create(['user_id' => $this->user->id, 'new_item_cap_override' => 3]);
    $unit = unitWithVocabulary($this->spanish, 10);

    (new CompleteUnit)->handle($this->user, $unit);

    $result = (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);

    expect($result['enrolled'])->toBe(0)
        ->and(SrsCard::query()->count())->toBe(3);
});

it('never enrols the same item twice', function () {
    $unit = unitWithVocabulary($this->spanish, 4);

    (new CompleteUnit)->handle($this->user, $unit);
    (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);
    (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);

    expect(SrsCard::query()->count())->toBe(4);
});

it('takes the oldest completed unit first', function () {
    UserSetting::factory()->create(['user_id' => $this->user->id, 'new_item_cap_override' => 1]);

    $first = unitWithVocabulary($this->spanish, 2, 1);
    $second = unitWithVocabulary($this->spanish, 2, 2);

    (new CompleteUnit)->handle($this->user, $first);
    (new CompleteUnit)->handle($this->user, $second);

    SrsCard::query()->update(['created_at' => now()->subDay()]);
    (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);

    $enrolledUnitIds = SrsCard::query()->pluck('cardable_id')
        ->map(fn (int $id): ?int => VocabularyItem::query()->find($id)?->unit_id)
        ->unique()
        ->values();

    expect($enrolledUnitIds->all())->toBe([$first->id]);
});

it('leaves the other language deck alone', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();
    (new UnlockLanguageForUser)->handle($this->user, $portuguese);

    $spanishUnit = unitWithVocabulary($this->spanish, 3);
    unitWithVocabulary($portuguese, 3, 2);

    (new CompleteUnit)->handle($this->user, $spanishUnit);
    SrsCard::query()->update(['created_at' => now()->subDay()]);

    (new EnrollPendingUnitContent)->handle($this->user, $portuguese);

    expect(SrsCard::query()->where('language_id', $portuguese->id)->count())->toBe(0);
});

it('includes grammar points alongside vocabulary', function () {
    UserSetting::factory()->create(['user_id' => $this->user->id, 'new_item_cap_override' => 1]);
    $unit = unitWithVocabulary($this->spanish, 1);
    GrammarPoint::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id]);

    (new CompleteUnit)->handle($this->user, $unit);
    SrsCard::query()->update(['created_at' => now()->subDay()]);
    (new EnrollPendingUnitContent)->handle($this->user, $this->spanish);

    expect(SrsCard::query()->where('cardable_type', (new GrammarPoint)->getMorphClass())->count())->toBe(1);
});

it('tops up automatically when the next review session is built', function () {
    UserSetting::factory()->create(['user_id' => $this->user->id, 'new_item_cap_override' => 2]);
    $unit = unitWithVocabulary($this->spanish, 6);

    (new CompleteUnit)->handle($this->user, $unit);
    SrsCard::query()->update(['created_at' => now()->subDay()]);

    $this->actingAs($this->user)->get(route('review.index'))->assertOk();

    expect(SrsCard::query()->count())->toBe(4);
});
