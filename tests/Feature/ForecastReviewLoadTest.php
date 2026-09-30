<?php

declare(strict_types=1);

use App\Actions\Srs\ForecastReviewLoad;
use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->travelTo(CarbonImmutable::parse('2026-10-01 15:00:00'));

    $this->user = User::factory()->create();
    $this->language = Language::factory()->create();

    $this->scheduleCard = fn (string $dueAt, array $attributes = []): SrsCard => SrsCard::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->language->id,
        'state' => SrsCardState::Review,
        'due_at' => CarbonImmutable::parse($dueAt),
        ...$attributes,
    ]);
});

function forecastCardsOn(array $forecast, string $date): int
{
    return collect($forecast['days'])->firstWhere('date', $date)['cards'];
}

it('always forecasts fourteen consecutive days, today first, with empty days as zero', function () {
    $forecast = (new ForecastReviewLoad)->handle($this->user, $this->language);

    expect($forecast['days'])->toHaveCount(ForecastReviewLoad::DAYS)
        ->and($forecast['days'][0])->toBe(['date' => '2026-10-01', 'cards' => 0])
        ->and($forecast['days'][13])->toBe(['date' => '2026-10-14', 'cards' => 0])
        ->and($forecast['newWaiting'])->toBe(0);
});

it('buckets repetitions by the day they fall due', function () {
    ($this->scheduleCard)('2026-10-01 20:00:00');
    ($this->scheduleCard)('2026-10-03 00:00:00');
    ($this->scheduleCard)('2026-10-03 23:59:59');
    ($this->scheduleCard)('2026-10-08 09:00:00', ['state' => SrsCardState::Learning]);
    ($this->scheduleCard)('2026-10-08 10:00:00', ['state' => SrsCardState::Relearning]);

    $forecast = (new ForecastReviewLoad)->handle($this->user, $this->language);

    expect(forecastCardsOn($forecast, '2026-10-01'))->toBe(1)
        ->and(forecastCardsOn($forecast, '2026-10-02'))->toBe(0)
        ->and(forecastCardsOn($forecast, '2026-10-03'))->toBe(2)
        ->and(forecastCardsOn($forecast, '2026-10-08'))->toBe(2);
});

it('counts overdue cards towards today instead of dropping them', function () {
    ($this->scheduleCard)('2026-10-01 08:00:00');
    ($this->scheduleCard)('2026-09-30 23:59:59');
    ($this->scheduleCard)('2026-09-12 10:00:00');
    ($this->scheduleCard)('2025-01-01 10:00:00');

    $forecast = (new ForecastReviewLoad)->handle($this->user, $this->language);

    expect(forecastCardsOn($forecast, '2026-10-01'))->toBe(4)
        ->and(collect($forecast['days'])->sum('cards'))->toBe(4);
});

it('includes the last day of the window and leaves out the day after it', function () {
    ($this->scheduleCard)('2026-10-14 23:59:59');
    ($this->scheduleCard)('2026-10-15 00:00:00');

    $forecast = (new ForecastReviewLoad)->handle($this->user, $this->language);

    expect(forecastCardsOn($forecast, '2026-10-14'))->toBe(1)
        ->and(collect($forecast['days'])->sum('cards'))->toBe(1);
});

it('counts new cards as waiting rather than due, since the daily cap releases them', function () {
    ($this->scheduleCard)('2026-10-01 10:00:00', ['state' => SrsCardState::New]);
    ($this->scheduleCard)('2026-09-20 10:00:00', ['state' => SrsCardState::New]);

    $forecast = (new ForecastReviewLoad)->handle($this->user, $this->language);

    expect(collect($forecast['days'])->sum('cards'))->toBe(0)
        ->and($forecast['newWaiting'])->toBe(2);
});

it('leaves weak spots, other decks and other learners out', function () {
    ($this->scheduleCard)('2026-10-02 10:00:00', ['is_weak_spot' => true]);
    ($this->scheduleCard)('2026-10-02 10:00:00', ['language_id' => Language::factory()->create()->id]);
    ($this->scheduleCard)('2026-10-02 10:00:00', ['user_id' => User::factory()->create()->id]);
    ($this->scheduleCard)('2026-10-02 10:00:00', ['state' => SrsCardState::New, 'language_id' => Language::factory()->create()->id]);
    ($this->scheduleCard)('2026-10-02 10:00:00', ['state' => SrsCardState::New, 'user_id' => User::factory()->create()->id]);

    $forecast = (new ForecastReviewLoad)->handle($this->user, $this->language);

    expect(collect($forecast['days'])->sum('cards'))->toBe(0)
        ->and($forecast['newWaiting'])->toBe(0);
});

it('buckets every day in one grouped query', function () {
    foreach (['2026-09-29', '2026-10-01', '2026-10-02', '2026-10-05', '2026-10-09', '2026-10-13'] as $day) {
        ($this->scheduleCard)("{$day} 12:00:00");
    }

    DB::enableQueryLog();
    $forecast = (new ForecastReviewLoad)->handle($this->user, $this->language);
    $queries = collect(DB::getQueryLog())->pluck('query');
    DB::disableQueryLog();

    expect(collect($forecast['days'])->sum('cards'))->toBe(6)
        ->and($queries)->toHaveCount(2)
        ->and($queries->filter(fn (string $query): bool => str_contains($query, 'group by')))->toHaveCount(1);
});
