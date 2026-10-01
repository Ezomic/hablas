<?php

declare(strict_types=1);

use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage;
use App\Lessons\AuthoredExercise;
use App\Lessons\WordData;
use App\Models\LessonExercise;
use App\Services\UnitContentRegistry;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\SpanishA1Seeder;
use Illuminate\Support\Facades\Artisan;
use Tests\Fixtures\Lessons\ArrayContent;
use Tests\Fixtures\Lessons\LessonWorld;

describe('lessons:review-sheet', function () {
    beforeEach(function () {
        $this->seed(LanguageSeeder::class);
        $this->seed(SpanishA1Seeder::class);
    });

    it('renders the words, the grammar card and the generated exercises, with no questions section once every question is answered', function () {
        $this->artisan('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel'])->assertSuccessful();
        Artisan::call('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel']);

        expect(Artisan::output())->toContain(
            '# Review sheet: Checking into a hotel',
            '| el recepcionista | noun | receptionist | receptionist (at the hotel desk) |  |  | yes |',
            '- El hotel está cerca. (The hotel is near.)',
            '- choose_meaning: el hotel | options:',
            '- type_word (check set a): hotel | accepted: el hotel',
        )->not->toContain('## Questions for the reviewer');
    });

    it('prints the open questions of a word under their own heading', function () {
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new ArrayContent(words: [new WordData('el recepcionista', questions: ['Common gender: is la recepcionista also right?'])])]));

        Artisan::call('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel']);

        expect(Artisan::output())->toContain('## Questions for the reviewer', '- el recepcionista: Common gender: is la recepcionista also right?');
    });

    it('leaves the check out of the owner sheet, so no answer key is read before the check is taken', function () {
        Artisan::call('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel', '--audience' => 'owner']);

        expect(Artisan::output())->toContain('### Lesson 2: Recall the words')
            ->not->toContain('check set')
            ->not->toContain('Unit check');
    });

    it('refuses an unknown audience and an unknown unit', function () {
        $this->artisan('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel', '--audience' => 'native'])->expectsOutput('The audience is reviewer or owner.')->assertFailed();
        $this->artisan('lessons:review-sheet', ['language' => 'es', 'unit' => 'nowhere'])->expectsOutput('No content for that language and unit.')->assertFailed();
    });

    it('says so when the words do not build', function () {
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new ArrayContent(words: [new WordData('not a word of the unit')])]));

        $this->artisan('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel'])->expectsOutputToContain('The words do not build')->assertSuccessful();
    });

    it('fails when the unit is not in the database', function () {
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new ArrayContent(slug: 'a-unit-that-is-not-seeded')]));

        $this->artisan('lessons:review-sheet', ['language' => 'es', 'unit' => 'a-unit-that-is-not-seeded'])->assertFailed();
    });
});

describe('lessons:words', function () {
    beforeEach(function () {
        $this->seed(LanguageSeeder::class);
    });

    it('lists every distinct word of a unit once, sorted, accents kept', function () {
        $this->artisan('lessons:words', ['language' => 'es', 'unit' => 'checking-into-a-hotel'])
            ->expectsOutputToContain('habitación')
            ->expectsOutputToContain('cuarto')
            ->assertSuccessful();

        $words = [];
        $this->artisan('lessons:words', ['language' => 'es', 'unit' => 'checking-into-a-hotel']);
        Artisan::call('lessons:words', ['language' => 'es', 'unit' => 'checking-into-a-hotel']);
        $words = array_values(array_filter(explode("\n", Artisan::output())));

        expect($words)->toBe(array_values(array_unique($words)))
            ->and($words)->toContain('está', 'recepcionista')
            ->and($words)->toBe((function () use ($words): array {
                $sorted = $words;
                sort($sorted, SORT_STRING);

                return $sorted;
            })());
    });

    it('lists the words of every unit of the language when no unit is given', function () {
        Artisan::call('lessons:words', ['language' => 'es']);
        $words = array_filter(explode("\n", Artisan::output()));

        expect($words)->toContain('aeropuerto', 'levantarse', 'hola');
    });

    it('lists the words of authored answers too', function () {
        $unit = LessonWorld::hotelUnit();
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new ArrayContent(exercises: [new AuthoredExercise(LessonStage::Sentences, Format::TranslateSentence, 'k', ['prompt' => 'x'], ['Zumbido raro.'])])]));

        Artisan::call('lessons:words', ['language' => 'es', 'unit' => $unit->slug]);

        expect(explode("\n", Artisan::output()))->toContain('zumbido', 'raro');
    });

    it('refuses an unknown language', function () {
        $this->artisan('lessons:words', ['language' => 'xx'])->expectsOutput('Unknown language.')->assertFailed();
    });
});

describe('lessons:flags', function () {
    it('lists the answers a learner disputed', function () {
        [$unit] = LessonWorld::seededHotel();
        $user = LessonWorld::learner();
        $run = (new StartLessonRun)->handle($user, LessonWorld::lesson($unit, LessonStage::Meet));
        $exercise = LessonExercise::query()->whereIn('id', $run->planExerciseIds())->where('format', 'type_word')->firstOrFail();
        $answer = LessonWorld::answer($user, $run, $exercise, ['response' => ['text' => 'zzz']])['answer'];
        $answer->forceFill(['flagged_at' => now()])->save();
        LessonWorld::answer($user, $run, $exercise, ['response' => ['text' => 'also wrong']]);

        Artisan::call('lessons:flags');

        expect(Artisan::output())->toContain('checking-into-a-hotel', $exercise->key, 'zzz')
            ->not->toContain('also wrong');
    });

    it('prints an empty table when nothing is flagged', function () {
        $this->artisan('lessons:flags')->assertSuccessful();
    });
});
