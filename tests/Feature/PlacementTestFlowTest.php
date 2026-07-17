<?php

use App\Actions\Placement\GetCurrentPlacementItem;
use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;
use App\Models\User;
use App\Models\UserSkillLevel;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PlacementTestSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('redirects to the placement test when the active language has no completed attempt', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('placement.index'));
});

it('allows access to the dashboard once the placement test is completed', function () {
    $user = User::factory()->create();

    PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('renders the placement test page with the first item for the active language', function () {
    $item = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('placement/Index')
            ->where('item.id', $item->id)
            ->where('language.code', 'es')
            ->where('language.name', 'Spanish'),
        );
});

it('resumes the same in-progress attempt instead of starting a new one', function () {
    PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading]);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));
    $this->actingAs($user)->get(route('placement.index'));

    expect(PlacementTestAttempt::query()->where('user_id', $user->id)->count())->toBe(1);
});

it('answers an item, receives the next item, and does not repeat items', function () {
    $reading = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading, 'correct_answer' => 'right']);
    $listening = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Listening, 'correct_answer' => 'right']);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $response = $this->actingAs($user)
        ->postJson(route('placement.answer', $reading), ['response' => 'right'])
        ->assertOk()
        ->json();

    expect($response['done'])->toBeFalse()
        ->and($response['item']['id'])->toBe($listening->id);
});

it('rejects answering an item that is not the currently-expected one', function () {
    PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading]);
    $otherItem = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Listening]);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $this->actingAs($user)
        ->postJson(route('placement.answer', $otherItem), ['response' => 'anything'])
        ->assertStatus(409);

    expect(PlacementTestResponse::query()->count())->toBe(0);
});

it('completes all four skills, finalizes the attempt, and unlocks the dashboard', function () {
    foreach (Skill::cases() as $skill) {
        PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => $skill, 'correct_answer' => 'right']);
    }
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $done = false;
    $guard = 0;

    while (! $done && $guard < 50) {
        $attempt = PlacementTestAttempt::query()->where('user_id', $user->id)->sole();
        $currentItem = (new GetCurrentPlacementItem)->handle($attempt);

        if ($currentItem === null) {
            break;
        }

        $response = $this->actingAs($user)
            ->postJson(route('placement.answer', $currentItem), ['response' => $currentItem->correct_answer])
            ->assertOk()
            ->json();

        $done = $response['done'];
        $guard++;
    }

    expect($done)->toBeTrue();

    $attempt = PlacementTestAttempt::query()->where('user_id', $user->id)->sole();
    expect($attempt->completed_at)->not->toBeNull();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('flashes a celebratory toast when finishing the placement test raises the blended level', function () {
    foreach (Skill::cases() as $skill) {
        PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => $skill, 'correct_answer' => 'right']);
    }
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $lastResponse = null;
    $done = false;
    $guard = 0;

    while (! $done && $guard < 50) {
        $attempt = PlacementTestAttempt::query()->where('user_id', $user->id)->sole();
        $currentItem = (new GetCurrentPlacementItem)->handle($attempt);

        if ($currentItem === null) {
            break;
        }

        $lastResponse = $this->actingAs($user)
            ->postJson(route('placement.answer', $currentItem), ['response' => $currentItem->correct_answer]);

        $done = $lastResponse->json('done');
        $guard++;
    }

    $lastResponse->assertInertiaFlash('toast', [
        'type' => 'milestone',
        'message' => "You've reached A2 in Spanish!",
    ]);
});

it('rejects an answer request with no response', function () {
    $item = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading]);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $this->actingAs($user)
        ->postJson(route('placement.answer', $item), [])
        ->assertInvalid(['response']);
});

it('lets a user skip the placement test and start at A1', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('placement.skip'))
        ->assertRedirect(route('dashboard'));

    expect(PlacementTestAttempt::query()->where('user_id', $user->id)->whereNotNull('completed_at')->exists())->toBeTrue();

    foreach (Skill::cases() as $skill) {
        expect(UserSkillLevel::query()->where('user_id', $user->id)->where('skill', $skill)->sole()->cefr_level)->toBe(CefrLevel::A1);
    }
});

it('seeds a realistic item bank that a full staircase can walk through end to end', function () {
    $this->seed(PlacementTestSeeder::class);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $done = false;
    $guard = 0;

    while (! $done && $guard < 50) {
        $attempt = PlacementTestAttempt::query()->where('user_id', $user->id)->sole();
        $currentItem = (new GetCurrentPlacementItem)->handle($attempt);

        if ($currentItem === null) {
            break;
        }

        $response = $this->actingAs($user)
            ->postJson(route('placement.answer', $currentItem), ['response' => $currentItem->correct_answer])
            ->assertOk()
            ->json();

        $done = $response['done'];
        $guard++;
    }

    expect($done)->toBeTrue();
});

it('exposes the "I don\'t know" response value to the page', function () {
    PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertInertia(fn ($page) => $page
            ->where('dontKnowResponse', PlacementTestResponse::DONT_KNOW),
        );
});

it('records "I don\'t know" as incorrect so it never over-places', function () {
    $reading = PlacementTestItem::factory()->create([
        'language_id' => $this->spanish->id,
        'skill' => Skill::Reading,
        'correct_answer' => 'right',
    ]);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $this->actingAs($user)
        ->postJson(route('placement.answer', $reading), ['response' => PlacementTestResponse::DONT_KNOW])
        ->assertOk();

    $recorded = PlacementTestResponse::query()->sole();

    // Scored incorrect, and stored verbatim so an abstention stays
    // distinguishable from a wrong guess.
    expect($recorded->is_correct)->toBeFalse()
        ->and($recorded->response)->toBe(PlacementTestResponse::DONT_KNOW);
});

it('steps the staircase down when the user does not know', function () {
    $a1 = PlacementTestItem::factory()->create([
        'language_id' => $this->spanish->id,
        'skill' => Skill::Reading,
        'cefr_sublevel_tag' => CefrSubLevel::A1_3,
        'correct_answer' => 'right',
    ]);
    // A lower-tier item must exist for the staircase to step down into.
    $lower = PlacementTestItem::factory()->create([
        'language_id' => $this->spanish->id,
        'skill' => Skill::Reading,
        'cefr_sublevel_tag' => CefrSubLevel::A1_2,
        'correct_answer' => 'right',
    ]);
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'));

    $next = $this->actingAs($user)
        ->postJson(route('placement.answer', $a1), ['response' => PlacementTestResponse::DONT_KNOW])
        ->assertOk()
        ->json();

    expect($next['done'])->toBeFalse()
        ->and($next['item']['id'])->toBe($lower->id);
});
