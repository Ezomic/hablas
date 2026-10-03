<?php

declare(strict_types=1);

use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\CefrLevel;
use App\Enums\LessonStage;
use App\Enums\SpeechSpeed;
use App\Listeners\IndexSpeechClipsAfterMigrating;
use App\Models\Language;
use App\Models\ListeningExercise;
use App\Models\PronunciationDrillExercise;
use App\Models\ShadowingExercise;
use App\Models\SpeechClip;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Services\UnitContentRegistry;
use App\Speech\SpeechKey;
use App\Speech\SpeechVoices;
use Database\Seeders\LanguageSeeder;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function (): void {
    Storage::fake('public');
    config(['speech.disk' => 'public']);
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

function clipUrl(string $text, SpeechSpeed $speed, string $language = 'es'): string
{
    $voice = app(SpeechVoices::class)->primary($language);
    $hash = app(SpeechKey::class)->make($language, $voice->id, $speed, $text);

    SpeechClip::query()->create(['language' => $language, 'voice_id' => $voice->id, 'speed' => $speed, 'hash' => $hash, 'bytes' => 10]);

    return Storage::disk('public')->url(SpeechClip::pathFor($language, $voice->id, $hash));
}

function dueCard(User $user, Language $language, string $term, bool $weak = false): SrsCard
{
    $item = VocabularyItem::factory()->create(['language_id' => $language->id, 'term' => $term]);

    return SrsCard::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'cardable_type' => VocabularyItem::class,
        'cardable_id' => $item->id,
        'due_at' => now()->subMinute(),
        'is_weak_spot' => $weak,
    ]);
}

describe('exercise pages', function (): void {
    it('serves the listening transcript clips at both speeds', function (): void {
        $exercise = ListeningExercise::factory()->create(['language_id' => $this->spanish->id, 'cefr_level' => CefrLevel::A1, 'transcript' => 'Hola, soy Carmen.']);
        $normal = clipUrl('Hola, soy Carmen.', SpeechSpeed::Normal);
        $slow = clipUrl('Hola, soy Carmen.', SpeechSpeed::Slow);

        $this->actingAs($this->user)->get(route('listening.index'))
            ->assertInertia(fn ($page) => $page
                ->where('exercise.id', $exercise->id)
                ->where('exercise.audioUrl', $normal)
                ->where('exercise.audioSlowUrl', $slow));
    });

    it('serves the shadowing clips', function (): void {
        ShadowingExercise::factory()->create(['language_id' => $this->spanish->id, 'target_transcript' => 'Buenos días']);
        $normal = clipUrl('Buenos días', SpeechSpeed::Normal);

        $this->actingAs($this->user)->get(route('shadowing.index'))
            ->assertInertia(fn ($page) => $page
                ->where('exercise.audioUrl', $normal)
                ->where('exercise.audioSlowUrl', null));
    });

    it('serves the pronunciation drill target clips', function (): void {
        PronunciationDrillExercise::factory()->create(['language_id' => $this->spanish->id, 'word_a' => 'pero', 'word_b' => 'perro', 'target_word' => 'perro']);
        $slow = clipUrl('perro', SpeechSpeed::Slow);

        $this->actingAs($this->user)->get(route('pronunciation-drills.index'))
            ->assertInertia(fn ($page) => $page
                ->where('exercise.audioUrl', null)
                ->where('exercise.audioSlowUrl', $slow));
    });

    it('sends null urls for every exercise page when no clip exists', function (): void {
        ListeningExercise::factory()->create(['language_id' => $this->spanish->id, 'cefr_level' => CefrLevel::A1]);
        ShadowingExercise::factory()->create(['language_id' => $this->spanish->id]);
        PronunciationDrillExercise::factory()->create(['language_id' => $this->spanish->id]);

        foreach (['listening.index', 'shadowing.index', 'pronunciation-drills.index'] as $route) {
            $this->actingAs($this->user)->get(route($route))
                ->assertInertia(fn ($page) => $page->where('exercise.audioUrl', null)->where('exercise.audioSlowUrl', null));
        }
    });
});

describe('word lists', function (): void {
    it('serves the vocabulary rows their clips, with one resolver query', function (): void {
        dueCard($this->user, $this->spanish, 'la casa');
        dueCard($this->user, $this->spanish, 'el perro');
        $normal = clipUrl('la casa', SpeechSpeed::Normal);

        DB::enableQueryLog();
        $response = $this->actingAs($this->user)->get(route('vocabulary.index', ['sort' => 'alphabetical']));
        $clipQueries = collect(DB::getQueryLog())->filter(fn (array $query): bool => str_contains($query['query'], 'speech_clips'));

        $response->assertInertia(fn ($page) => $page
            ->where('items.0.term', 'la casa')
            ->where('items.0.audioUrl', $normal)
            ->where('items.0.audioSlowUrl', null)
            ->where('items.1.term', 'el perro')
            ->where('items.1.audioUrl', null));
        expect($clipQueries)->toHaveCount(1);
    });

    it('serves the unit reference its vocabulary clips', function (): void {
        $unit = Unit::factory()->create(['language_id' => $this->spanish->id, 'cefr_level' => CefrLevel::A1]);
        VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id, 'term' => 'el café']);
        $slow = clipUrl('el café', SpeechSpeed::Slow);

        $this->actingAs($this->user)->get(route('units.show', $unit))
            ->assertInertia(fn ($page) => $page
                ->where('vocabularyItems.0.audioUrl', null)
                ->where('vocabularyItems.0.audioSlowUrl', $slow));
    });

    it('serves review cards the clips of the word they speak', function (): void {
        dueCard($this->user, $this->spanish, 'el gato');
        $normal = clipUrl('el gato', SpeechSpeed::Normal);
        $slow = clipUrl('el gato', SpeechSpeed::Slow);

        $this->actingAs($this->user)->get(route('review.index'))
            ->assertInertia(fn ($page) => $page
                ->where('cards.0.audioUrl', $normal)
                ->where('cards.0.audioSlowUrl', $slow));
    });

    it('serves weak spot cards the clips of the word they speak', function (): void {
        dueCard($this->user, $this->spanish, 'el gato', weak: true);
        $normal = clipUrl('el gato', SpeechSpeed::Normal);

        $this->actingAs($this->user)->get(route('review.weak-spots.index'))
            ->assertInertia(fn ($page) => $page
                ->where('cards.0.audioUrl', $normal)
                ->where('cards.0.audioSlowUrl', null));
    });

    it('sends null urls everywhere when no clip exists or speech is off', function (): void {
        dueCard($this->user, $this->spanish, 'el gato');

        foreach ([true, false] as $enabled) {
            config(['speech.enabled' => $enabled]);
            clipUrl('el gato', SpeechSpeed::Normal);

            $this->actingAs($this->user)->get(route('review.index'))
                ->assertInertia(fn ($page) => $enabled
                    ? $page->whereNot('cards.0.audioUrl', null)
                    : $page->where('cards.0.audioUrl', null)->where('cards.0.audioSlowUrl', null));

            SpeechClip::query()->delete();
        }

        $this->actingAs($this->user)->get(route('vocabulary.index'))
            ->assertInertia(fn ($page) => $page->where('items.0.audioUrl', null)->where('items.0.audioSlowUrl', null));
    });
});

describe('lessons', function (): void {
    beforeEach(function (): void {
        [$this->unit] = LessonWorld::seededHotel();
        $this->learner = LessonWorld::learner();
        $this->run = (new StartLessonRun)->handle($this->learner, LessonWorld::lesson($this->unit, LessonStage::Meet));
    });

    function teachWords(array $props): array
    {
        return collect($props['plan'])->filter(fn (array $entry): bool => $entry['format'] === 'teach_word')->values()->all();
    }

    it('gives every taught word null urls when no clip exists', function (): void {
        $words = teachWords(app(PresentLessonRun::class)->handle($this->run));

        expect($words)->not->toBe([])
            ->and(collect($words)->every(fn (array $entry): bool => $entry['payload']['audioUrl'] === null && $entry['payload']['audioSlowUrl'] === null))->toBeTrue();
    });

    it('gives a taught word its clips at both speeds and leaves the others null', function (): void {
        $words = teachWords(app(PresentLessonRun::class)->handle($this->run));
        $term = $words[0]['payload']['term'];
        $normal = clipUrl($term, SpeechSpeed::Normal);
        $slow = clipUrl($term, SpeechSpeed::Slow);

        $words = teachWords(app(PresentLessonRun::class)->handle($this->run));
        $served = collect($words)->firstWhere('payload.term', $term);
        $others = collect($words)->reject(fn (array $entry): bool => $entry['payload']['term'] === $term);

        expect($served['payload']['audioUrl'])->toBe($normal)
            ->and($served['payload']['audioSlowUrl'])->toBe($slow)
            ->and($others->every(fn (array $entry): bool => $entry['payload']['audioUrl'] === null))->toBeTrue();
    });

    it('looks every spoken string of the run up with one query', function (): void {
        DB::enableQueryLog();
        app(PresentLessonRun::class)->handle($this->run);

        expect(collect(DB::getQueryLog())->filter(fn (array $query): bool => str_contains($query['query'], 'speech_clips')))->toHaveCount(1);
    });
});

describe('speech index after migrate', function (): void {
    function migrateFinished(array $options = [], int $exitCode = 0, string $command = 'migrate'): CommandFinished
    {
        $input = new ArrayInput($options, Artisan::all()[$command]->getDefinition());

        return new CommandFinished($command, $input, new BufferedOutput, $exitCode);
    }

    beforeEach(function (): void {
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry([]));
        VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'term' => 'el gato']);
        $voice = app(SpeechVoices::class)->primary('es');
        $this->hash = app(SpeechKey::class)->make('es', $voice->id, SpeechSpeed::Normal, 'el gato');
        $this->path = SpeechClip::pathFor('es', $voice->id, $this->hash);
    });

    it('records the clips found on disk and writes no files', function (): void {
        Storage::disk('public')->put($this->path, 'mp3');

        $event = migrateFinished(['--force' => true]);
        app(IndexSpeechClipsAfterMigrating::class)->handle($event);

        expect(SpeechClip::query()->where('hash', $this->hash)->exists())->toBeTrue()
            ->and(SpeechClip::query()->count())->toBe(1)
            ->and($event->output->fetch())->toContain('Indexed 1 speech clips.')
            ->and(Storage::disk('public')->allFiles('speech'))->toBe([$this->path]);
    });

    it('is a no-op for a failed migrate, a dry run and other commands', function (): void {
        Storage::disk('public')->put($this->path, 'mp3');

        $listener = app(IndexSpeechClipsAfterMigrating::class);
        $listener->handle(migrateFinished(['--force' => true], exitCode: 1));
        $listener->handle(migrateFinished(['--pretend' => true]));
        $listener->handle(migrateFinished(command: 'migrate:fresh'));

        expect(SpeechClip::query()->count())->toBe(0);
    });

    it('reports a failure without throwing', function (): void {
        config(['speech.disk' => 'missing-disk']);

        $event = migrateFinished(['--force' => true]);
        app(IndexSpeechClipsAfterMigrating::class)->handle($event);

        expect($event->output->fetch())->toContain('Speech clips were not indexed');
    });
});
