<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CompleteUnit;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Lessons\GetUnitLessonOverview;
use App\Actions\Units\DetermineUnitAvailability;
use App\Actions\Units\ListUnitLibrary;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\UnitAvailability;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\VocabularyItem;
use App\Services\SpeechLocaleResolver;
use App\Speech\SpeechClipResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class UnitController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(GetCurrentLanguage $getCurrentLanguage, ListUnitLibrary $listUnitLibrary): Response
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        return Inertia::render('units/Index', [
            'language' => $language === null ? null : ['name' => $language->localizedName()],
            'units' => $language === null ? [] : $listUnitLibrary->handle($this->currentUser(), $language),
        ]);
    }

    public function show(Request $request, Unit $unit, GetCurrentLanguage $getCurrentLanguage, DetermineUnitAvailability $determineUnitAvailability, SpeechLocaleResolver $speechLocaleResolver, GetUnitLessonOverview $getUnitLessonOverview, SpeechClipResolver $speechClipResolver): Response
    {
        $language = $this->currentLanguage($getCurrentLanguage);
        $availability = $this->authorizeUnit($unit, $language, $determineUnitAvailability);
        $overview = $getUnitLessonOverview->handle($this->currentUser(), $unit);

        $unit->load(['vocabularyItems', 'grammarPoints']);

        $clips = $speechClipResolver->resolveBoth($language->code, array_values($unit->vocabularyItems->map(fn (VocabularyItem $item): string => $item->term)->all()));

        return Inertia::render('units/Show', [
            'unit' => [
                'id' => $unit->id,
                'title' => $unit->title,
                'taskDescription' => $unit->task_description,
                'cefrLevel' => $unit->cefr_level->value,
                'primarySkill' => $unit->primary_skill->value,
                'contrastNote' => $unit->contrast_note,
            ],
            'vocabularyItems' => $unit->vocabularyItems->map(fn (VocabularyItem $item): array => [
                'id' => $item->id,
                'term' => $item->term,
                'translation' => $item->translation_en,
                'partOfSpeech' => $item->part_of_speech,
                'isCognate' => $item->is_cognate,
                'contrastNote' => $item->contrast_note,
                'audioUrl' => $clips[$item->term]['audioUrl'] ?? null,
                'audioSlowUrl' => $clips[$item->term]['audioSlowUrl'] ?? null,
            ])->values(),
            'grammarPoints' => $unit->grammarPoints->map(fn (GrammarPoint $point): array => [
                'id' => $point->id,
                'title' => $point->title,
                'explanation' => $point->explanation,
            ])->values(),
            'isCompleted' => $availability === UnitAvailability::Completed,
            'availability' => $availability->value,
            'lessons' => $this->hasLessons($overview) ? $overview : null,
            'speechLocale' => $speechLocaleResolver->forLanguage($language),
        ]);
    }

    public function store(Request $request, Unit $unit, CompleteUnit $completeUnit, GetCurrentLanguage $getCurrentLanguage, DetermineUnitAvailability $determineUnitAvailability): RedirectResponse
    {
        $this->authorizeUnit($unit, $this->currentLanguage($getCurrentLanguage), $determineUnitAvailability);

        abort_if(Lesson::query()->where('unit_id', $unit->id)->playable()->exists(), 404);

        $result = $completeUnit->handle($this->currentUser(), $unit);

        Inertia::flash('toast', ['type' => 'success', 'message' => $this->completionMessage($result['enrolled'], $result['deferred'])]);

        return to_route('dashboard');
    }

    /**
     * A unit with playable lessons is completed through them, never through
     * the old button, which stays only for units that have none.
     *
     * @param  array{lessons: list<array{lessonId: int|null}>}  $overview
     */
    private function hasLessons(array $overview): bool
    {
        foreach ($overview['lessons'] as $lesson) {
            if ($lesson['lessonId'] !== null) {
                return true;
            }
        }

        return false;
    }

    private function currentLanguage(GetCurrentLanguage $getCurrentLanguage): Language
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        abort_if($language === null, 404);

        return $language;
    }

    /**
     * A unit is only reachable on the deck the user is currently studying:
     * serving one from the other language would put its vocabulary into the
     * wrong deck on completion, which the separate-decks rule forbids. A unit
     * above the learner's level is refused too, because completing it would
     * enroll cards they are not ready for. A held-back unit is not refused:
     * like the dashboard, the library defers it by not offering it.
     */
    private function authorizeUnit(Unit $unit, Language $language, DetermineUnitAvailability $determineUnitAvailability): UnitAvailability
    {
        abort_if($unit->language_id !== $language->id, 404);

        $availability = $determineUnitAvailability->handle($this->currentUser(), $language, collect([$unit]))[$unit->id];

        abort_if($availability === UnitAvailability::Locked, 403);

        return $availability;
    }

    /**
     * The daily new-item cap can hold part of a unit back, so the message says
     * what actually landed rather than claiming the whole unit is in the deck.
     */
    private function completionMessage(int $enrolled, int $deferred): string
    {
        if ($deferred === 0) {
            return trans_choice('Unit complete. :count card is now in your review deck.|Unit complete. :count cards are now in your review deck.', $enrolled, ['count' => $enrolled]);
        }

        if ($enrolled === 0) {
            return __('Unit complete. Your review backlog is full, so its cards will be added once you have caught up.');
        }

        return trans_choice('Unit complete. :enrolled cards added, :deferred held back until you have cleared more reviews.', $enrolled, ['enrolled' => $enrolled, 'deferred' => $deferred]);
    }
}
