<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lessons;

use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\RecordLessonAnswer;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\ErrorTagCategory;
use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;
use App\Enums\Skill;
use App\Lessons\TargetRef;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\UnitSkillMastery;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\VocabularyItem;
use Closure;
use Illuminate\Support\Str;

/**
 * Builds the database rows the fixture unit stands on, and its lessons.
 */
final class LessonWorld
{
    private const WORDS = [
        ['el hotel', 'hotel', 'noun'],
        ['la habitación', 'room', 'noun'],
        ['la reserva', 'reservation', 'noun'],
        ['la llave', 'key', 'noun'],
        ['el recepcionista', 'receptionist', 'noun'],
        ['disponible', 'available', 'adjective'],
        ['la noche', 'night', 'noun'],
        ['el baño', 'bathroom', 'noun'],
        ['incluido', 'included', 'adjective'],
        ['el desayuno', 'breakfast', 'noun'],
    ];

    public static function spanish(): Language
    {
        return Language::query()->firstOrCreate(['code' => 'es'], ['name' => 'Spanish']);
    }

    public static function portuguese(): Language
    {
        return Language::query()->firstOrCreate(['code' => 'pt'], ['name' => 'Portuguese']);
    }

    public static function hotelUnit(?Language $language = null, string $slug = 'checking-into-a-hotel'): Unit
    {
        $language ??= self::spanish();
        $unit = Unit::factory()->create([
            'language_id' => $language->id,
            'slug' => $slug,
            'cefr_level' => CefrLevel::A1,
            'context_tag' => ContextTag::Travel,
            'primary_skill' => Skill::Speaking,
            'title' => 'Checking into a hotel',
        ]);

        foreach (self::WORDS as [$term, $translation, $pos]) {
            VocabularyItem::factory()->create([
                'language_id' => $language->id,
                'unit_id' => $unit->id,
                'term' => $term,
                'translation_en' => $translation,
                'part_of_speech' => $pos,
            ]);
        }

        GrammarPoint::factory()->create([
            'language_id' => $language->id,
            'unit_id' => $unit->id,
            'title' => 'Estar for location',
            'explanation' => 'Estar is used for location and temporary states.',
            'error_tag_category' => ErrorTagCategory::SerEstarConfusion,
        ]);

        return $unit->fresh() ?? $unit;
    }

    /**
     * @param  list<ExerciseFamily>|null  $families  every family when null
     * @return array{Unit, HotelContent}
     */
    public static function seededHotel(bool $withAuthored = true, ?array $families = null): array
    {
        $unit = self::hotelUnit();
        $content = new HotelContent(withAuthored: $withAuthored);

        (new SyncUnitLessons)->handle($unit, $families === null ? (new BuildUnitLessons)->handle($unit, $content) : (new BuildUnitLessons)->handle($unit, $content, $families));

        return [$unit, $content];
    }

    /**
     * A learner studying Spanish, or the given language, with every skill at the given level.
     */
    public static function learner(CefrLevel $level = CefrLevel::A1, ?Language $language = null): User
    {
        $spanish = $language ?? self::spanish();
        $user = User::factory()->create(['current_language_id' => $spanish->id]);
        (new UnlockLanguageForUser)->handle($user, $spanish);

        foreach (Skill::cases() as $skill) {
            UserSkillLevel::factory()->create([
                'user_id' => $user->id,
                'language_id' => $spanish->id,
                'skill' => $skill,
                'cefr_level' => $level,
                'level_set_at' => now()->subYear(),
            ]);
        }

        return $user;
    }

    public static function lesson(Unit $unit, LessonStage $stage): Lesson
    {
        return Lesson::query()->where('unit_id', $unit->id)->where('stage', $stage)->firstOrFail();
    }

    /**
     * What a learner who knows the answer sends for an exercise.
     *
     * @return array<string, mixed>
     */
    public static function rightResponse(LessonExercise $exercise): array
    {
        $payload = $exercise->payload;
        $format = $exercise->format;

        return match (true) {
            $format->isChoice() => ['choice' => $payload['answer']],
            $format === LessonExerciseFormat::MatchPairs => ['wrong' => []],
            $format->isPassage() => ['choices' => array_column($payload['questions'], 'answer')],
            $format === LessonExerciseFormat::WriteGuided => ['text' => implode(' ', array_map(fn (array $entry): string => $entry['forms'][0], $payload['required']))],
            $format->isTeach() => [],
            default => ['text' => $payload['accepted'][0]['text'] ?? throw new \RuntimeException($exercise->key.' '.$format->value)],
        };
    }

    /**
     * @return array<string, mixed>
     */
    public static function wrongResponse(LessonExercise $exercise): array
    {
        $payload = $exercise->payload;
        $format = $exercise->format;

        return match (true) {
            $format->isChoice() => ['choice' => collect($payload['options'])->first(fn (string $option): bool => $option !== $payload['answer'])],
            $format === LessonExerciseFormat::MatchPairs => ['wrong' => [TargetRef::keyFor($exercise->targets()->firstOrFail()->targetable_type, $exercise->targets()->firstOrFail()->targetable_id)]],
            $format->isPassage() => ['choices' => array_map(fn (): string => 'nope', $payload['questions'])],
            default => ['text' => 'zzz'],
        };
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public static function answer(User $user, LessonRun $run, LessonExercise $exercise, array $input = []): array
    {
        return (new RecordLessonAnswer)->handle($user, $run, (string) Str::uuid(), [
            'exercise_id' => $exercise->id,
            ...$input,
            'response' => array_key_exists('response', $input) ? $input['response'] : self::rightResponse($exercise),
        ]);
    }

    /**
     * Plays a run to the end. Speaking is skipped for its substitute, since a
     * test has no recogniser to say it. An exercise for which the
     * callback returns true is answered wrong first and right second.
     */
    public static function play(User $user, LessonRun $run, ?Closure $mistake = null): LessonRun
    {
        foreach ($run->planExerciseIds() as $id) {
            $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

            if ($exercise->format->isSpeaking()) {
                self::answer($user, $run, $exercise, ['skipped' => true, 'skip_reason' => 'unsupported', 'response' => null]);
                $exercise = $exercise->substitute ?? $exercise;
            }

            if ($mistake !== null && $mistake($exercise)) {
                self::answer($user, $run, $exercise, ['response' => self::wrongResponse($exercise)]);
            }

            self::answer($user, $run, $exercise);
        }

        return $run->fresh() ?? $run;
    }

    /**
     * Plays lessons 1 to 4 to completion on separate earlier days, so the
     * check is open today.
     */
    public static function finishTeachingLessons(User $user, Unit $unit): void
    {
        foreach ([LessonStage::Meet, LessonStage::Recall, LessonStage::Sentences, LessonStage::Task] as $stage) {
            $lesson = Lesson::query()->where('unit_id', $unit->id)->where('stage', $stage)->whereHas('exercises', fn ($query) => $query->whereNull('retired_at'))->first();

            if ($lesson === null) {
                continue;
            }

            $run = (new StartLessonRun)->handle($user, $lesson);
            self::play($user, $run);
            $run->fresh()?->forceFill(['completed_at' => now()->subDays(2)])->save();
        }

        self::masterEverySkill($user, $unit);
    }

    /** What the final tests of the skills leave behind, so the unit check is unlocked. */
    public static function masterEverySkill(User $user, Unit $unit): void
    {
        foreach ([Skill::Reading, Skill::Listening, Skill::Speaking, Skill::Writing] as $skill) {
            UnitSkillMastery::query()->updateOrCreate(['user_id' => $user->id, 'unit_id' => $unit->id, 'skill' => $skill], ['mastered_at' => now()]);
        }
    }
}
