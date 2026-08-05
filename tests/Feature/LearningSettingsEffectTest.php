<?php

use App\Actions\CompleteUnit;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\SelectNextUnit;
use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\Skill;
use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;
use Inertia\Support\SessionKey;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

function makeUnit(Language $language, ContextTag $tag, int $sortOrder): Unit
{
    return Unit::factory()->create([
        'language_id' => $language->id,
        'context_tag' => $tag,
        'cefr_level' => CefrLevel::A1,
        'primary_skill' => Skill::Reading,
        'sort_order' => $sortOrder,
    ]);
}

it('follows the declared context priority when no focus is chosen', function () {
    makeUnit($this->spanish, ContextTag::Professional, 1);
    $travel = makeUnit($this->spanish, ContextTag::Travel, 2);

    expect((new SelectNextUnit)->handle($this->user, $this->spanish)->id)->toBe($travel->id);
});

it('prefers the user chosen content focus over the declared priority', function () {
    $professional = makeUnit($this->spanish, ContextTag::Professional, 1);
    makeUnit($this->spanish, ContextTag::Travel, 2);

    UserSetting::factory()->create([
        'user_id' => $this->user->id,
        'context_emphasis' => ContextTag::Professional,
    ]);

    expect((new SelectNextUnit)->handle($this->user, $this->spanish)->id)->toBe($professional->id);
});

it('falls back to the declared priority once the chosen focus is exhausted', function () {
    $travel = makeUnit($this->spanish, ContextTag::Travel, 2);

    UserSetting::factory()->create([
        'user_id' => $this->user->id,
        'context_emphasis' => ContextTag::Professional,
    ]);

    expect((new SelectNextUnit)->handle($this->user, $this->spanish)->id)->toBe($travel->id);
});

it('caps how many new cards a completed unit adds', function () {
    $unit = makeUnit($this->spanish, ContextTag::Travel, 1);
    VocabularyItem::factory()->count(14)->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id]);

    $result = (new CompleteUnit)->handle($this->user, $unit);

    expect($result['enrolled'])->toBe(10)
        ->and($result['deferred'])->toBe(4)
        ->and(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(10);
});

it('honours an explicit new item cap override', function () {
    UserSetting::factory()->create([
        'user_id' => $this->user->id,
        'new_item_cap_override' => 3,
    ]);

    $unit = makeUnit($this->spanish, ContextTag::Travel, 1);
    VocabularyItem::factory()->count(8)->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id]);

    $result = (new CompleteUnit)->handle($this->user, $unit);

    expect($result['enrolled'])->toBe(3)
        ->and($result['deferred'])->toBe(5);
});

it('counts cards already added today against the cap', function () {
    UserSetting::factory()->create([
        'user_id' => $this->user->id,
        'new_item_cap_override' => 5,
    ]);

    $first = makeUnit($this->spanish, ContextTag::Travel, 1);
    VocabularyItem::factory()->count(4)->create(['language_id' => $this->spanish->id, 'unit_id' => $first->id]);
    (new CompleteUnit)->handle($this->user, $first);

    $second = makeUnit($this->spanish, ContextTag::Travel, 2);
    VocabularyItem::factory()->count(4)->create(['language_id' => $this->spanish->id, 'unit_id' => $second->id]);

    $result = (new CompleteUnit)->handle($this->user, $second);

    expect($result['enrolled'])->toBe(1)
        ->and($result['deferred'])->toBe(3)
        ->and(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(5);
});

it('adds the held-back cards when the unit is revisited under a raised cap', function () {
    UserSetting::factory()->create([
        'user_id' => $this->user->id,
        'new_item_cap_override' => 2,
    ]);

    $unit = makeUnit($this->spanish, ContextTag::Travel, 1);
    VocabularyItem::factory()->count(5)->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id]);

    (new CompleteUnit)->handle($this->user, $unit);

    UserSetting::query()->where('user_id', $this->user->id)->update(['new_item_cap_override' => 20]);

    $result = (new CompleteUnit)->handle($this->user, $unit);

    expect($result['enrolled'])->toBe(3)
        ->and($result['deferred'])->toBe(0)
        ->and(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(5);
});

it('adds nothing while the backlog is heavy but still completes the unit', function () {
    // Inserted directly rather than through the factory: a 100-card backlog is
    // past the point where the vocabulary factory's unique() word pool holds up,
    // and this test only cares about the due count the cap reads. The cardable
    // ids sit well clear of the real ones this test creates below.
    SrsCard::query()->insert(collect(range(10_000, 10_099))->map(fn (int $i): array => [
        'user_id' => $this->user->id,
        'language_id' => $this->spanish->id,
        'cardable_type' => (new VocabularyItem)->getMorphClass(),
        'cardable_id' => $i,
        'state' => SrsCardState::New->value,
        'stability' => 0,
        'difficulty' => 0,
        'reps' => 0,
        'lapses' => 0,
        'consecutive_lapses' => 0,
        'is_weak_spot' => false,
        'due_at' => now()->subDay(),
        'created_at' => now()->subMonth(),
        'updated_at' => now()->subMonth(),
    ])->all());

    $unit = makeUnit($this->spanish, ContextTag::Travel, 1);
    VocabularyItem::factory()->count(3)->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id]);

    $result = (new CompleteUnit)->handle($this->user, $unit);

    expect($result['enrolled'])->toBe(0)
        ->and($result['deferred'])->toBe(3)
        ->and($result['progress']->status->value)->toBe('completed');
});

it('tells the user what actually landed in their deck', function () {
    UserSetting::factory()->create([
        'user_id' => $this->user->id,
        'new_item_cap_override' => 2,
    ]);

    $unit = makeUnit($this->spanish, ContextTag::Travel, 1);
    VocabularyItem::factory()->count(5)->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id]);

    $this->actingAs($this->user)
        ->post(route('units.completion.store', $unit))
        ->assertRedirect(route('dashboard'));

    expect(session(SessionKey::FLASH_DATA, [])['toast']['message'] ?? null)
        ->toBe('Unit complete. 2 cards added, 3 held back until you have cleared more reviews.');
});
