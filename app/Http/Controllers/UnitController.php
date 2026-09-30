<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CompleteUnit;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Units\DetermineUnitAvailability;
use App\Actions\Units\ListUnitLibrary;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\UnitAvailability;
use App\Models\GrammarPoint;
use App\Models\Unit;
use App\Models\VocabularyItem;
use App\Services\SpeechLocaleResolver;
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
            'language' => $language === null ? null : ['name' => $language->name],
            'units' => $language === null ? [] : $listUnitLibrary->handle($this->currentUser(), $language),
        ]);
    }

    public function show(Request $request, Unit $unit, GetCurrentLanguage $getCurrentLanguage, DetermineUnitAvailability $determineUnitAvailability, SpeechLocaleResolver $speechLocaleResolver): Response
    {
        $availability = $this->authorizeUnit($unit, $getCurrentLanguage, $determineUnitAvailability);

        $unit->load(['vocabularyItems', 'grammarPoints']);

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
            ])->values(),
            'grammarPoints' => $unit->grammarPoints->map(fn (GrammarPoint $point): array => [
                'id' => $point->id,
                'title' => $point->title,
                'explanation' => $point->explanation,
            ])->values(),
            'isCompleted' => $availability === UnitAvailability::Completed,
            'speechLocale' => $unit->language === null ? null : $speechLocaleResolver->forLanguage($unit->language),
        ]);
    }

    public function store(Request $request, Unit $unit, CompleteUnit $completeUnit, GetCurrentLanguage $getCurrentLanguage, DetermineUnitAvailability $determineUnitAvailability): RedirectResponse
    {
        $this->authorizeUnit($unit, $getCurrentLanguage, $determineUnitAvailability);

        $result = $completeUnit->handle($this->currentUser(), $unit);

        Inertia::flash('toast', ['type' => 'success', 'message' => $this->completionMessage($result['enrolled'], $result['deferred'])]);

        return to_route('dashboard');
    }

    /**
     * A unit is only reachable on the deck the user is currently studying:
     * serving one from the other language would put its vocabulary into the
     * wrong deck on completion, which the separate-decks rule forbids. A unit
     * above the learner's level is refused too, because completing it would
     * enroll cards they are not ready for. A held-back unit is not refused:
     * like the dashboard, the library defers it by not offering it.
     */
    private function authorizeUnit(Unit $unit, GetCurrentLanguage $getCurrentLanguage, DetermineUnitAvailability $determineUnitAvailability): UnitAvailability
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        abort_if($language === null || $unit->language_id !== $language->id, 404);

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

        return __('Unit complete. :enrolled cards added, :deferred held back until you have cleared more reviews.', ['enrolled' => $enrolled, 'deferred' => $deferred]);
    }
}
