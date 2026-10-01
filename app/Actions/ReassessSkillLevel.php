<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\LessonSkillScore;
use App\Models\ListeningAttempt;
use App\Models\ReadingAttempt;
use App\Models\ScriptedPromptAttempt;
use App\Models\ShadowingAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\WritingAttempt;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final class ReassessSkillLevel
{
    /**
     * How many of the user's most recent graded attempts for a skill to look
     * at, counting only attempts made since the level was last set. Without
     * that bound, the attempts that earned one step would earn the next one
     * too, and every further good attempt would raise the level again.
     * A step to the next tier takes a short window; a whole-level step where
     * there are no tiers (B2 to C1, C1 to C2) keeps the long one, so it is no
     * easier than it was before tiers.
     */
    private const TIER_WINDOW = 10;

    private const LEVEL_WINDOW = 20;

    /**
     * Success rate at or above this fraction of the window earns a level
     * bump.
     */
    private const SUCCESS_RATE_THRESHOLD = 0.8;

    /**
     * Shadowing/scripted-prompt scores (0-100) at or above this count as a
     * successful attempt for the speaking skill.
     */
    private const SPEAKING_SUCCESS_SCORE = 80.0;

    /**
     * Comprehension scores (0-100) at or above this count as a successful
     * attempt for the reading and listening skills.
     */
    private const COMPREHENSION_SUCCESS_SCORE = 80.0;

    /**
     * Lesson skill scores (0-100) at or above this count as a successful
     * outcome, like the other sources.
     */
    private const LESSON_SUCCESS_SCORE = 80.0;

    public function handle(User $user, Language $language, Skill $skill): void
    {
        $skillLevel = UserSkillLevel::query()->firstWhere([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'skill' => $skill,
        ]);

        if ($skillLevel === null) {
            return;
        }

        $step = $this->nextStep($skillLevel);

        if ($step === null) {
            return;
        }

        $since = $skillLevel->level_set_at;
        $window = $step['window'];

        $outcomes = match ($skill) {
            Skill::Writing => $this->recentWritingOutcomes($user, $language, $since, $window),
            Skill::Speaking => $this->recentSpeakingOutcomes($user, $language, $since, $window),
            Skill::Reading => $this->recentReadingOutcomes($user, $language, $since, $window),
            Skill::Listening => $this->recentListeningOutcomes($user, $language, $since, $window),
        };

        $outcomes = $outcomes
            ->concat($this->recentLessonOutcomes($user, $language, $skill, $since, $window))
            ->sortByDesc('at')
            ->take($window)
            ->map(fn (array $outcome): bool => $outcome['ok'])
            ->values();

        if ($outcomes->count() < $window) {
            return;
        }

        $successRate = $outcomes->filter(fn (bool $correct): bool => $correct)->count() / $outcomes->count();

        if ($successRate < self::SUCCESS_RATE_THRESHOLD) {
            return;
        }

        $skillLevel->forceFill([
            'cefr_level' => $step['level'],
            'sub_level' => $step['tier'],
            'level_set_at' => now(),
        ])->save();
    }

    /** @return array{level: CefrLevel, tier: CefrSubLevel|null, window: int}|null */
    private function nextStep(UserSkillLevel $skillLevel): ?array
    {
        $current = $skillLevel->currentTier();
        $next = $current?->stepUp();

        if ($next !== null && $next !== $current) {
            return ['level' => $next->parentLevel(), 'tier' => $next, 'window' => self::TIER_WINDOW];
        }

        $level = CefrLevel::cases()[$skillLevel->cefr_level->sortOrder() + 1] ?? null;

        if ($level === null) {
            return null;
        }

        return [
            'level' => $level,
            'tier' => CefrSubLevel::firstOf($level),
            'window' => self::LEVEL_WINDOW,
        ];
    }

    /** @return Collection<int, array{at: CarbonImmutable, ok: bool}> */
    private function recentReadingOutcomes(User $user, Language $language, ?CarbonImmutable $since, int $window): Collection
    {
        return ReadingAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('readingPassage', fn ($query) => $query->where('language_id', $language->id))
            ->when($since, fn ($query) => $query->where('attempted_at', '>', $since))
            ->latest('attempted_at')
            ->limit($window)
            ->get()
            ->map(fn (ReadingAttempt $attempt): array => ['at' => $attempt->attempted_at, 'ok' => $attempt->score >= self::COMPREHENSION_SUCCESS_SCORE]);
    }

    /** @return Collection<int, array{at: CarbonImmutable, ok: bool}> */
    private function recentListeningOutcomes(User $user, Language $language, ?CarbonImmutable $since, int $window): Collection
    {
        return ListeningAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('listeningExercise', fn ($query) => $query->where('language_id', $language->id))
            ->when($since, fn ($query) => $query->where('attempted_at', '>', $since))
            ->latest('attempted_at')
            ->limit($window)
            ->get()
            ->map(fn (ListeningAttempt $attempt): array => ['at' => $attempt->attempted_at, 'ok' => $attempt->score >= self::COMPREHENSION_SUCCESS_SCORE]);
    }

    /** @return Collection<int, array{at: CarbonImmutable, ok: bool}> */
    private function recentWritingOutcomes(User $user, Language $language, ?CarbonImmutable $since, int $window): Collection
    {
        return WritingAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('writingExercise', fn ($query) => $query->where('language_id', $language->id))
            ->when($since, fn ($query) => $query->where('submitted_at', '>', $since))
            ->latest('submitted_at')
            ->limit($window)
            ->get()
            ->map(fn (WritingAttempt $attempt): array => ['at' => $attempt->submitted_at, 'ok' => $attempt->is_correct]);
    }

    /**
     * Combines shadowing (tier 1) and scripted-prompt (tier 2) attempts into
     * one rolling window, since both are speaking practice for the same
     * skill — just interleaved by recency rather than treated separately.
     *
     * @return Collection<int, array{at: CarbonImmutable, ok: bool}>
     */
    private function recentSpeakingOutcomes(User $user, Language $language, ?CarbonImmutable $since, int $window): Collection
    {
        $shadowing = ShadowingAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('shadowingExercise', fn ($query) => $query->where('language_id', $language->id))
            ->when($since, fn ($query) => $query->where('attempted_at', '>', $since))
            ->latest('attempted_at')
            ->limit($window)
            ->get(['score', 'attempted_at']);

        $scriptedPrompts = ScriptedPromptAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('scriptedPromptExercise', fn ($query) => $query->where('language_id', $language->id))
            ->when($since, fn ($query) => $query->where('attempted_at', '>', $since))
            ->latest('attempted_at')
            ->limit($window)
            ->get(['score', 'attempted_at']);

        return $shadowing->concat($scriptedPrompts)
            ->sortByDesc('attempted_at')
            ->take($window)
            ->map(fn (ShadowingAttempt|ScriptedPromptAttempt $attempt): array => ['at' => $attempt->attempted_at, 'ok' => $attempt->score >= self::SPEAKING_SUCCESS_SCORE])
            ->values();
    }

    /**
     * Lesson scores that count toward the level, one outcome per score, so a
     * lesson with many answers moves a skill no faster than one with few.
     *
     * @return Collection<int, array{at: CarbonImmutable, ok: bool}>
     */
    private function recentLessonOutcomes(User $user, Language $language, Skill $skill, ?CarbonImmutable $since, int $window): Collection
    {
        return LessonSkillScore::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->where('skill', $skill)
            ->where('counts_toward_level', true)
            ->when($since, fn ($query) => $query->where('scored_at', '>', $since))
            ->latest('scored_at')
            ->limit($window)
            ->get()
            ->map(fn (LessonSkillScore $score): array => ['at' => $score->scored_at, 'ok' => $score->score >= self::LESSON_SUCCESS_SCORE]);
    }
}
