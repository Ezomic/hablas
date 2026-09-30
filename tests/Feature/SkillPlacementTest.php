<?php

declare(strict_types=1);

use App\Actions\Placement\GetCurrentPlacementItem;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;
use App\Models\User;
use App\Models\UserSkillLevel;
use Carbon\CarbonImmutable;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PlacementTestSeeder;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(PlacementTestSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->travelTo(CarbonImmutable::parse('2026-09-30 12:00:00'));
});

/**
 * A learner who took the full test at $placedAt, which put every skill at
 * the given level.
 *
 * @param  array<string, CefrLevel>  $levels
 */
function retakeLearner(Language $language, string $placedAt = '2026-09-01 10:00:00', array $levels = []): User
{
    $user = User::factory()->create();
    $resulting = [];

    foreach (Skill::cases() as $skill) {
        $level = $levels[$skill->value] ?? CefrLevel::A2;
        $resulting[$skill->value] = ['cefr_level' => $level->value, 'sub_level' => $level === CefrLevel::A2 ? 'A2.2' : $level->value];

        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'skill' => $skill,
            'cefr_level' => $level,
            'level_set_at' => $placedAt,
        ]);
    }

    $attempt = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'started_at' => $placedAt,
        'completed_at' => $placedAt,
        'resulting_skill_levels' => $resulting,
    ]);

    PlacementTestResponse::factory()->create([
        'attempt_id' => $attempt->id,
        'item_id' => PlacementTestItem::query()->where('language_id', $language->id)->where('skill', Skill::Reading)->firstOrFail()->id,
        'skill' => Skill::Reading,
        'is_correct' => true,
    ]);

    return $user;
}

/**
 * Answers the learner's open attempt to the end, every answer right or every
 * answer "I don't know".
 *
 * @return array{skills: list<string>, last: TestResponse|null}
 */
function retakeAnswerAll(TestCase $test, User $user, bool $correct): array
{
    $skills = [];
    $last = null;

    for ($guard = 0; $guard < 50; $guard++) {
        $attempt = PlacementTestAttempt::query()->where('user_id', $user->id)->whereNull('completed_at')->sole();
        $item = (new GetCurrentPlacementItem)->handle($attempt);

        if ($item === null) {
            break;
        }

        $skills[] = $item->skill->value;
        $last = $test->actingAs($user)->postJson(route('placement.answer', $item), [
            'response' => $correct ? $item->correct_answer : PlacementTestResponse::DONT_KNOW,
        ])->assertOk();

        if ($last->json('done') === true) {
            break;
        }
    }

    return ['skills' => $skills, 'last' => $last];
}

function retakeLevelsOf(User $user): Collection
{
    return UserSkillLevel::query()
        ->where('user_id', $user->id)
        ->get()
        ->keyBy(fn (UserSkillLevel $level): string => $level->skill->value);
}

function openAttemptsOf(User $user): Illuminate\Database\Eloquent\Collection
{
    return PlacementTestAttempt::query()->where('user_id', $user->id)->whereNull('completed_at')->get();
}

it('starts a one-skill attempt that serves only that skill', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)
        ->post(route('placement.skills.store', Skill::Listening))
        ->assertRedirect(route('placement.index'));

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('placement/Index')
            ->where('skill', Skill::Listening->value)
            ->where('canSkip', false)
            ->where('item.skill', Skill::Listening->value)
            ->where('progress', 0),
        );

    ['skills' => $served] = retakeAnswerAll($this, $user, correct: true);

    expect($served)->not->toBeEmpty()
        ->and(array_values(array_unique($served)))->toBe([Skill::Listening->value]);
});

it('moves only the re-placed skill and restarts only its practice window', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Listening));
    retakeAnswerAll($this, $user, correct: true);

    $levels = retakeLevelsOf($user);

    expect($levels[Skill::Listening->value]->cefr_level)->toBe(CefrLevel::B2)
        ->and($levels[Skill::Listening->value]->level_set_at?->toDateTimeString())->toBe('2026-09-30 12:00:00');

    foreach ([Skill::Reading, Skill::Speaking, Skill::Writing] as $untouched) {
        expect($levels[$untouched->value]->cefr_level)->toBe(CefrLevel::A2)
            ->and($levels[$untouched->value]->level_set_at?->toDateTimeString())->toBe('2026-09-01 10:00:00');
    }

    $attempt = PlacementTestAttempt::query()->where('user_id', $user->id)->latest('id')->firstOrFail();

    expect($attempt->skill)->toBe(Skill::Listening)
        ->and($attempt->completed_at)->not->toBeNull()
        ->and(array_keys($attempt->resulting_skill_levels ?? []))->toBe([Skill::Listening->value]);
});

it('can lower the level, since the result replaces it', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Reading));
    retakeAnswerAll($this, $user, correct: false);

    expect(retakeLevelsOf($user)[Skill::Reading->value]->cefr_level)->toBe(CefrLevel::A1);
});

it('starts one attempt however often it is asked', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Reading))->assertRedirect(route('placement.index'));
    $this->actingAs($user)->post(route('placement.skills.store', Skill::Reading))->assertRedirect(route('placement.index'));
    $this->actingAs($user)->post(route('placement.skills.store', Skill::Writing))->assertRedirect(route('placement.index'));

    $open = openAttemptsOf($user);

    expect($open)->toHaveCount(1)
        ->and($open->sole()->skill)->toBe(Skill::Reading);
});

it('resumes a full test in progress instead of starting a re-take', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('placement.skip'));
    $this->actingAs($user)->get(route('placement.index'))->assertOk();

    $this->actingAs($user)
        ->post(route('placement.skills.store', Skill::Reading))
        ->assertRedirect(route('placement.index'));

    $open = openAttemptsOf($user);

    expect($open)->toHaveCount(1)
        ->and($open->sole()->skill)->toBeNull();
});

it('refuses a re-take within a week of the last placement of that skill, and names the date', function () {
    $user = retakeLearner($this->spanish, '2026-09-29 21:15:54');

    $this->actingAs($user)
        ->from(route('placement.results'))
        ->post(route('placement.skills.store', Skill::Reading))
        ->assertRedirect(route('placement.results'))
        ->assertSessionHasErrors(['skill' => 'You can re-take reading on 6 October.']);

    expect(openAttemptsOf($user))->toBeEmpty();

    $this->travelTo(CarbonImmutable::parse('2026-10-06 00:00:00'));

    $this->actingAs($user)
        ->post(route('placement.skills.store', Skill::Reading))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('placement.index'));

    expect(openAttemptsOf($user))->toHaveCount(1);
});

it('starts the cooldown again from a finished re-take', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Reading));
    retakeAnswerAll($this, $user, correct: true);

    $this->actingAs($user)
        ->post(route('placement.skills.store', Skill::Reading))
        ->assertSessionHasErrors(['skill' => 'You can re-take reading on 7 October.']);

    $this->actingAs($user)
        ->post(route('placement.skills.store', Skill::Writing))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('placement.index'));
});

it('sends a learner who never finished a placement to the full test', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('placement.skills.store', Skill::Reading))
        ->assertRedirect(route('placement.index'));

    expect(PlacementTestAttempt::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('answers 404 for a skill that does not exist', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)
        ->post('/placement/skills/grammar')
        ->assertNotFound();
});

it('shows each skill from the newest placement that covered it', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Listening));
    retakeAnswerAll($this, $user, correct: false);

    $this->actingAs($user)
        ->get(route('placement.results'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('placement/Results')
            ->where('result.blendedLevel', 'A1.1')
            ->where('result.skipped', false)
            ->where('result.skills.0.skill', Skill::Reading->value)
            ->where('result.skills.0.level', 'A2.2')
            ->has('result.skills.0.items', 1)
            ->where('result.skills.1.skill', Skill::Listening->value)
            ->where('result.skills.1.level', 'A1.1')
            ->where('result.skills.1.items.0.status', 'dont_know')
            ->where('result.skills.2.level', 'A2.2')
            ->where('result.skills.3.level', 'A2.2')
            ->where('retakeAvailableOn', [
                Skill::Reading->value => null,
                Skill::Listening->value => '2026-10-07',
                Skill::Speaking->value => null,
                Skill::Writing->value => null,
            ])
            ->where('openAttempt', null),
        );
});

it('tells the results page about a re-take still in progress', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Speaking));

    $this->actingAs($user)
        ->get(route('placement.results'))
        ->assertInertia(fn ($page) => $page->where('openAttempt', ['skill' => Skill::Speaking->value]));
});

it('keeps the dashboard open while a re-take is in progress', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Reading));

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('flashes the milestone toast when a re-take raises the blended level', function () {
    $user = retakeLearner($this->spanish, levels: [
        Skill::Reading->value => CefrLevel::A1,
        Skill::Listening->value => CefrLevel::B1,
        Skill::Speaking->value => CefrLevel::B1,
        Skill::Writing->value => CefrLevel::B1,
    ]);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Reading));
    ['last' => $last] = retakeAnswerAll($this, $user, correct: true);

    $last?->assertInertiaFlash('toast', [
        'type' => 'milestone',
        'message' => "You've reached B1 in Spanish!",
    ]);
});

it('no longer starts a fresh full test once a placement was taken', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertRedirect(route('placement.results'));

    expect(openAttemptsOf($user))->toBeEmpty();
});

it('still offers the full test after a skip', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('placement.skip'));

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('placement/Index')
            ->where('skill', null)
            ->where('canSkip', true),
        );

    expect(openAttemptsOf($user)->sole()->skill)->toBeNull();
});

it('still offers the full test after a skip pressed mid-test, without starting a cooldown', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('placement.index'))->assertOk();
    $item = (new GetCurrentPlacementItem)->handle(openAttemptsOf($user)->sole());
    $this->actingAs($user)
        ->postJson(route('placement.answer', $item), ['response' => $item?->correct_answer])
        ->assertJson(['done' => false]);

    $this->actingAs($user)
        ->post(route('placement.skip'))
        ->assertRedirect(route('dashboard'));

    expect(retakeLevelsOf($user)->every(fn (UserSkillLevel $level): bool => $level->cefr_level === CefrLevel::A1))->toBeTrue();

    $this->actingAs($user)
        ->get(route('placement.results'))
        ->assertInertia(fn ($page) => $page
            ->where('result.skipped', true)
            ->where('retakeAvailableOn', array_fill_keys(array_column(Skill::cases(), 'value'), null)),
        );

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('placement/Index')
            ->where('skill', null)
            ->where('canSkip', true)
            ->where('progress', 0),
        );
});

it('resumes a full test left open after a taken placement, without a skip', function () {
    $user = retakeLearner($this->spanish);
    $leftover = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'started_at' => '2026-09-02 10:00:00',
    ]);

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('placement/Index')
            ->where('skill', null)
            ->where('canSkip', false),
        );

    $this->actingAs($user)
        ->post(route('placement.skip'))
        ->assertRedirect(route('placement.index'));

    $this->actingAs($user)
        ->post(route('placement.skills.store', Skill::Reading))
        ->assertRedirect(route('placement.index'));

    expect(openAttemptsOf($user)->sole()->is($leftover))->toBeTrue()
        ->and(retakeLevelsOf($user)->every(fn (UserSkillLevel $level): bool => $level->cefr_level === CefrLevel::A2))->toBeTrue();

    $this->actingAs($user)
        ->get(route('placement.results'))
        ->assertInertia(fn ($page) => $page->where('openAttempt', ['skill' => null]));

    retakeAnswerAll($this, $user, correct: true);

    expect($leftover->fresh()?->completed_at)->not->toBeNull()
        ->and(retakeLevelsOf($user)->every(fn (UserSkillLevel $level): bool => $level->cefr_level === CefrLevel::B2))->toBeTrue();

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertRedirect(route('placement.results'));
});

it('does not skip a one-skill re-take', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)->post(route('placement.skills.store', Skill::Reading));

    $this->actingAs($user)
        ->post(route('placement.skip'))
        ->assertRedirect(route('placement.index'));

    expect(openAttemptsOf($user)->sole()->skill)->toBe(Skill::Reading)
        ->and(retakeLevelsOf($user)[Skill::Reading->value]->cefr_level)->toBe(CefrLevel::A2);
});

it('does not skip to A1 once a placement was taken', function () {
    $user = retakeLearner($this->spanish);

    $this->actingAs($user)
        ->post(route('placement.skip'))
        ->assertRedirect(route('placement.index'));

    expect(PlacementTestAttempt::query()->where('user_id', $user->id)->count())->toBe(1)
        ->and(retakeLevelsOf($user)->every(fn (UserSkillLevel $level): bool => $level->cefr_level === CefrLevel::A2))->toBeTrue();
});

it('finishes a re-take whose last answer was never finalized, for that skill only', function () {
    $user = retakeLearner($this->spanish);
    $retake = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $this->spanish->id,
        'skill' => Skill::Writing,
    ]);
    PlacementTestResponse::factory()->count(8)->create([
        'attempt_id' => $retake->id,
        'skill' => Skill::Writing,
        'is_correct' => false,
    ]);

    $this->actingAs($user)
        ->get(route('placement.index'))
        ->assertRedirect(route('placement.results'));

    $levels = retakeLevelsOf($user);

    expect($retake->fresh()?->completed_at)->not->toBeNull()
        ->and($levels[Skill::Writing->value]->cefr_level)->toBe(CefrLevel::A1)
        ->and($levels[Skill::Reading->value]->level_set_at?->toDateTimeString())->toBe('2026-09-01 10:00:00');
});
