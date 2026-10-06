<?php

declare(strict_types=1);

use App\Actions\Lessons\SummarizeLessonRun;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Models\LessonRun;
use App\Models\UnitItemMastery;
use App\Models\VocabularyItem;
use App\Models\WordTypingSupport;
use App\Services\RunWordProgress;
use Carbon\CarbonImmutable;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    $this->words = $this->unit->vocabularyItems()->orderBy('id')->get();
    $this->start = CarbonImmutable::now()->subMinutes(10);
    $this->run = LessonRun::factory()->completed()->create([
        'user_id' => $this->user->id,
        'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Recall)->id,
        'started_at' => $this->start,
        'completed_at' => $this->start->addMinutes(8),
    ]);
});

function knowWord(object $test, int $index, ?CarbonImmutable $at = null): void
{
    $row = WordTypingSupport::query()->create(['user_id' => $test->user->id, 'vocabulary_item_id' => $test->words[$index]->id, 'revealed' => 0]);
    $row->forceFill(['updated_at' => $at ?? $test->start->addMinutes(3)])->saveQuietly();
}

it('finds nothing when no word became known in the run', function () {
    expect((new RunWordProgress)->handle($this->run))->toBe(['newlyKnown' => [], 'milestones' => []]);
});

it('does nothing for a run that is still open', function () {
    knowWord($this, 0);
    $this->run->forceFill(['completed_at' => null])->save();

    expect((new RunWordProgress)->handle($this->run->refresh())['newlyKnown'])->toBe([]);
});

it('lists a word typed to zero during the run, and marks the first known word', function () {
    knowWord($this, 0);

    $result = (new RunWordProgress)->handle($this->run);

    expect($result['newlyKnown'])->toBe([['term' => $this->words[0]->term, 'translation' => $this->words[0]->translation_en]])
        ->and(array_column($result['milestones'], 'type'))->toContain('first_word');
});

it('leaves out words that were known before the run, or letters still shown', function () {
    knowWord($this, 0, $this->start->subDay());
    WordTypingSupport::query()->create(['user_id' => $this->user->id, 'vocabulary_item_id' => $this->words[1]->id, 'revealed' => 2])
        ->forceFill(['updated_at' => $this->start->addMinutes(3)])->saveQuietly();

    expect((new RunWordProgress)->handle($this->run)['newlyKnown'])->toBe([]);
});

it('counts a word the run proved in a check', function () {
    UnitItemMastery::factory()->create([
        'user_id' => $this->user->id,
        'unit_id' => $this->unit->id,
        'masterable_type' => $this->words[2]->getMorphClass(),
        'masterable_id' => $this->words[2]->id,
        'lesson_run_id' => $this->run->id,
    ]);

    expect(array_column((new RunWordProgress)->handle($this->run)['newlyKnown'], 'term'))->toBe([$this->words[2]->term]);
});

it('marks every tenth known word', function () {
    $extra = VocabularyItem::factory()->count(12)->create(['unit_id' => $this->unit->id, 'language_id' => $this->unit->language_id]);

    foreach ($extra->take(9) as $item) {
        WordTypingSupport::query()->create(['user_id' => $this->user->id, 'vocabulary_item_id' => $item->id, 'revealed' => 0])
            ->forceFill(['updated_at' => $this->start->subDay()])->saveQuietly();
    }

    WordTypingSupport::query()->create(['user_id' => $this->user->id, 'vocabulary_item_id' => $extra[9]->id, 'revealed' => 0])
        ->forceFill(['updated_at' => $this->start->addMinutes(2)])->saveQuietly();

    $milestones = (new RunWordProgress)->handle($this->run)['milestones'];

    expect($milestones)->toContain(['type' => 'words', 'count' => 10])
        ->and(array_column($milestones, 'type'))->not->toContain('first_word');
});

it('marks a unit whose every word is now known', function () {
    foreach ($this->words as $index => $word) {
        knowWord($this, $index, $index === 0 ? $this->start->addMinutes(3) : $this->start->subDay());
    }

    expect(array_column((new RunWordProgress)->handle($this->run)['milestones'], 'type'))->toContain('unit_words');
});

it('marks the level share passing half', function () {
    $needed = (int) ceil($this->words->count() / 2);

    foreach ($this->words->take($needed) as $index => $word) {
        knowWord($this, $index, $index === 0 ? $this->start->addMinutes(3) : $this->start->subDay());
    }

    $level = collect((new RunWordProgress)->handle($this->run)['milestones'])->firstWhere('type', 'level_half');

    expect($level)->toBe(['type' => 'level_half', 'level' => $this->unit->cefr_level->value]);
});

it('hands the summary the new words and milestones', function () {
    knowWord($this, 0);
    $this->run->forceFill(['kind' => LessonRunKind::Lesson])->save();

    $summary = (new SummarizeLessonRun)->handle($this->run->refresh());

    expect($summary['newlyKnown'])->toHaveCount(1)
        ->and($summary['milestones'])->not->toBeEmpty();
});
