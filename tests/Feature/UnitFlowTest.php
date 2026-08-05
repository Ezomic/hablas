<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\UnitProgressStatus;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
    $this->unit = Unit::factory()->create(['language_id' => $this->spanish->id, 'title' => 'Ordering coffee']);
});

it('renders a unit with its vocabulary and grammar', function () {
    VocabularyItem::factory()->create([
        'language_id' => $this->spanish->id,
        'unit_id' => $this->unit->id,
        'term' => 'el café',
    ]);
    GrammarPoint::factory()->create([
        'language_id' => $this->spanish->id,
        'unit_id' => $this->unit->id,
        'title' => 'Definite articles',
    ]);

    $this->actingAs($this->user)
        ->get(route('units.show', $this->unit))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('units/Show')
            ->where('unit.title', 'Ordering coffee')
            ->where('vocabularyItems.0.term', 'el café')
            ->where('grammarPoints.0.title', 'Definite articles')
            ->where('isCompleted', false),
        );
});

it('reports a unit the user already completed', function () {
    UserUnitProgress::factory()->create([
        'user_id' => $this->user->id,
        'unit_id' => $this->unit->id,
        'status' => UnitProgressStatus::Completed,
    ]);

    $this->actingAs($this->user)
        ->get(route('units.show', $this->unit))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('isCompleted', true));
});

it('enrolls the unit vocabulary and grammar into srs on completion', function () {
    $vocabulary = VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $this->unit->id]);
    $grammar = GrammarPoint::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $this->unit->id]);

    $this->actingAs($this->user)
        ->post(route('units.completion.store', $this->unit))
        ->assertRedirect(route('dashboard'));

    expect(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(2)
        ->and(SrsCard::query()->where('cardable_type', $vocabulary->getMorphClass())->where('cardable_id', $vocabulary->id)->exists())->toBeTrue()
        ->and(SrsCard::query()->where('cardable_type', $grammar->getMorphClass())->where('cardable_id', $grammar->id)->exists())->toBeTrue()
        ->and(SrsCard::query()->where('user_id', $this->user->id)->first()->language_id)->toBe($this->spanish->id);
});

it('does not duplicate cards when a unit is completed twice', function () {
    VocabularyItem::factory()->count(2)->create(['language_id' => $this->spanish->id, 'unit_id' => $this->unit->id]);

    $this->actingAs($this->user)->post(route('units.completion.store', $this->unit));
    $this->actingAs($this->user)->post(route('units.completion.store', $this->unit));

    expect(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(2)
        ->and(UserUnitProgress::query()->count())->toBe(1);
});

it('does not enroll vocabulary that belongs to no unit', function () {
    VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => null]);

    $this->actingAs($this->user)->post(route('units.completion.store', $this->unit));

    expect(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(0);
});

it('hides a unit from another language deck', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();
    $otherUnit = Unit::factory()->create(['language_id' => $portuguese->id]);

    $this->actingAs($this->user)->get(route('units.show', $otherUnit))->assertNotFound();
    $this->actingAs($this->user)->post(route('units.completion.store', $otherUnit))->assertNotFound();
});

it('requires authentication', function () {
    $this->get(route('units.show', $this->unit))->assertRedirect(route('login'));
});
