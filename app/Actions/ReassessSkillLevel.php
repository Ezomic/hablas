<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\ScriptedPromptAttempt;
use App\Models\ShadowingAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\WritingAttempt;
use Illuminate\Support\Collection;

class ReassessSkillLevel
{
    /**
     * How many of the user's most recent graded attempts for a skill to look
     * at — a rolling window, not the full attempt history. Milestone 1 only
     * ever sets a skill level once, via the placement test; this is what lets
     * it move again from ongoing practice.
     */
    private const ATTEMPT_WINDOW = 20;

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

    public function handle(User $user, Language $language, Skill $skill): void
    {
        $outcomes = match ($skill) {
            Skill::Writing => $this->recentWritingOutcomes($user, $language),
            Skill::Speaking => $this->recentSpeakingOutcomes($user, $language),
            // Reading/Listening have no live practice mechanism yet — only
            // the one-time placement test ever sets those two skill levels.
            Skill::Reading, Skill::Listening => collect(),
        };

        if ($outcomes->count() < self::ATTEMPT_WINDOW) {
            return;
        }

        $successRate = $outcomes->filter(fn (bool $correct): bool => $correct)->count() / $outcomes->count();

        if ($successRate < self::SUCCESS_RATE_THRESHOLD) {
            return;
        }

        $skillLevel = UserSkillLevel::query()->firstWhere([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'skill' => $skill,
        ]);

        if ($skillLevel === null) {
            return;
        }

        $nextLevel = CefrLevel::cases()[$skillLevel->cefr_level->sortOrder() + 1] ?? null;

        if ($nextLevel === null) {
            return;
        }

        $skillLevel->forceFill(['cefr_level' => $nextLevel])->save();
    }

    /** @return Collection<int, bool> */
    private function recentWritingOutcomes(User $user, Language $language): Collection
    {
        return WritingAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('writingExercise', fn ($query) => $query->where('language_id', $language->id))
            ->latest('submitted_at')
            ->limit(self::ATTEMPT_WINDOW)
            ->pluck('is_correct');
    }

    /**
     * Combines shadowing (tier 1) and scripted-prompt (tier 2) attempts into
     * one rolling window, since both are speaking practice for the same
     * skill — just interleaved by recency rather than treated separately.
     *
     * @return Collection<int, bool>
     */
    private function recentSpeakingOutcomes(User $user, Language $language): Collection
    {
        $shadowing = ShadowingAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('shadowingExercise', fn ($query) => $query->where('language_id', $language->id))
            ->latest('attempted_at')
            ->limit(self::ATTEMPT_WINDOW)
            ->get(['score', 'attempted_at']);

        $scriptedPrompts = ScriptedPromptAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('scriptedPromptExercise', fn ($query) => $query->where('language_id', $language->id))
            ->latest('attempted_at')
            ->limit(self::ATTEMPT_WINDOW)
            ->get(['score', 'attempted_at']);

        return $shadowing->concat($scriptedPrompts)
            ->sortByDesc('attempted_at')
            ->take(self::ATTEMPT_WINDOW)
            ->map(fn ($attempt): bool => $attempt->score >= self::SPEAKING_SUCCESS_SCORE)
            ->values();
    }
}
