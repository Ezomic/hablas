<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\MasteryScope;
use App\Enums\UnitProgressStatus;
use App\Lessons\AuthoredExercise;
use App\Lessons\AuthoredTexts;
use App\Lessons\PreviewContent;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\UserUnitProgress;
use App\Services\SpanishTextNormalizer;
use App\Services\UnitContentRegistry;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\SpanishA1Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Lessons\LessonWorld;

foreach (['greetings-and-introductions', 'at-the-airport', 'ordering-food-at-a-restaurant', 'asking-for-directions', 'shopping-for-clothes', 'talking-about-your-family', 'describing-your-daily-routine'] as $slug) {
    describe("the full path of {$slug}", function () use ($slug) {
        beforeEach(function () use ($slug) {
            $this->seed(LanguageSeeder::class);
            $this->seed(SpanishA1Seeder::class);
            $this->unit = Unit::query()->where('slug', $slug)->firstOrFail();
            $registered = collect(app(UnitContentRegistry::class)->all())->first(fn ($content): bool => $content->unitSlug() === $slug);
            $this->content = $registered;
            $this->released = new PreviewContent($registered, withLessons: true);
            $this->user = LessonWorld::learner();
            $this->check = fn (): Lesson => LessonWorld::lesson($this->unit, LessonStage::Check);
            (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->released));
        });

        it('seeds all five lessons, and a second sync writes nothing', function () {
            $updated = LessonExercise::query()->max('updated_at');
            $count = LessonExercise::query()->count();
            $this->travel(1)->minute();
            (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->released));

            expect(Lesson::query()->where('unit_id', $this->unit->id)->orderBy('position')->pluck('stage')->map(fn (LessonStage $stage): string => $stage->value)->all())->toBe(['meet', 'recall', 'sentences', 'task', 'check'])
                ->and(LessonExercise::query()->count())->toBe($count)
                ->and(LessonExercise::query()->max('updated_at'))->toEqual($updated);
        });

        it('lets a learner who knows everything play the four lessons and the check, and completes the unit', function () {
            foreach ([LessonStage::Meet, LessonStage::Recall, LessonStage::Sentences, LessonStage::Task] as $stage) {
                $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, $stage)));
                expect($run->first_try_accuracy)->toEqual(1.0);
                $run->forceFill(['completed_at' => now()->subDays(2)])->save();
            }

            $check = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check));

            expect($check->result['missing'])->toBe([])
                ->and($check->result['unit_completed'])->toBeTrue()
                ->and(UnitItemMastery::query()->where('user_id', $this->user->id)->where('scope', MasteryScope::Full)->count())->toBe(11)
                ->and(UserUnitProgress::query()->where('user_id', $this->user->id)->where('unit_id', $this->unit->id)->value('status'))->toBe(UnitProgressStatus::Completed);
        });

        it('sends a failed check to practice and a retake on a later day, from the other probe set, and then completes', function () {
            LessonWorld::finishTeachingLessons($this->user, $this->unit);
            $missed = collect($this->content->exercises())->first(fn (AuthoredExercise $exercise): bool => $exercise->probeSet === 'a' && $exercise->format->value === 'translate_sentence')->key;
            $run = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check);

            $check = LessonWorld::play($this->user, $run, fn (LessonExercise $exercise): bool => $exercise->key === $missed);

            expect($check->result['unit_completed'])->toBeFalse()
                ->and($check->result['missing'])->not->toBeEmpty();

            $practice = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Practice);
            LessonWorld::play($this->user, $practice);

            expect($practice->fresh()?->plan)->not->toBeEmpty()
                ->and(fn () => (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Retake))->toThrow(ValidationException::class);

            $this->travel(1)->days();
            $retake = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Retake);
            $done = LessonWorld::play($this->user, $retake);

            expect($retake->probe_set)->toBe('b')
                ->and($done->result['missing'])->toBe([])
                ->and($done->result['unit_completed'])->toBeTrue();
        });

        it('shows a lesson passage with its text and answers, and a check with neither', function () {
            LessonWorld::finishTeachingLessons($this->user, $this->unit);
            $task = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task));
            $entry = collect(app(PresentLessonRun::class)->handle($task)['plan'])->firstWhere('format', 'listen_passage');

            expect($entry['payload']['lines'][0])->toHaveKeys(['speaker', 'text', 'audioUrl', 'audioSlowUrl'])
                ->and($entry['payload']['questions'][0])->toHaveKey('answer')
                ->and($entry['substitute']['format'])->toBe('read_passage');

            $check = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check);
            $props = app(PresentLessonRun::class)->handle($check);
            $passage = collect($props['plan'])->firstWhere('format', 'listen_passage');

            expect($passage['payload']['lines'][0])->not->toHaveKey('text')
                ->and($passage['payload']['questions'][0])->not->toHaveKey('answer')
                ->and(json_encode($props, JSON_UNESCAPED_UNICODE))->not->toContain('homophone');
        });

        it('keeps the homophone notes out of what a learner receives in a lesson', function () {
            LessonWorld::finishTeachingLessons($this->user, $this->unit);
            $task = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task));

            expect(json_encode(app(PresentLessonRun::class)->handle($task), JSON_UNESCAPED_UNICODE))->not->toContain('homophone');
        });

        it('renders the review sheet with the checklist, the authored lessons and both check sets, and an owner sheet without the check', function () {
            Artisan::call('lessons:review-sheet', ['language' => 'es', 'unit' => $this->unit->slug]);
            $reviewer = Artisan::output();
            Artisan::call('lessons:review-sheet', ['language' => 'es', 'unit' => $this->unit->slug, '--audience' => 'owner']);
            $owner = Artisan::output();

            expect($reviewer)->toContain('## Checklist for the reviewer', '### Lesson 3: Build sentences', '### Lesson 4: Do the task', 'check set a', 'check set b', 'question if skipped:')
                ->and($owner)->toContain('### Lesson 4: Do the task')
                ->not->toContain('Checklist for the reviewer')
                ->not->toContain('check set')
                ->not->toContain('check.a');
        });

        it('lists every word of the authored content for the spelling pass', function () {
            Artisan::call('lessons:words', ['language' => 'es', 'unit' => $this->unit->slug]);
            $words = explode("\n", trim(Artisan::output()));

            foreach ($this->content->exercises() as $exercise) {
                foreach (AuthoredTexts::of($exercise) as $text) {
                    foreach (explode(' ', (new SpanishTextNormalizer)->exactKey($text)) as $word) {
                        expect($words)->toContain($word);
                    }
                }
            }
        });
    });
}
