<?php

namespace App\Http\Controllers;

use App\Actions\CompleteUnit;
use App\Actions\Languages\GetCurrentLanguage;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\UnitProgressStatus;
use App\Models\GrammarPoint;
use App\Models\Unit;
use App\Models\VocabularyItem;
use App\Services\SpeechLocaleResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UnitController extends Controller
{
    use InteractsWithCurrentUser;

    public function show(Request $request, Unit $unit, GetCurrentLanguage $getCurrentLanguage, SpeechLocaleResolver $speechLocaleResolver): Response
    {
        $this->authorizeUnit($unit, $getCurrentLanguage);

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
            'isCompleted' => $this->isCompleted($unit),
            'speechLocale' => $unit->language === null ? null : $speechLocaleResolver->forLanguage($unit->language),
        ]);
    }

    public function store(Request $request, Unit $unit, CompleteUnit $completeUnit, GetCurrentLanguage $getCurrentLanguage): RedirectResponse
    {
        $this->authorizeUnit($unit, $getCurrentLanguage);

        $result = $completeUnit->handle($this->currentUser(), $unit);

        Inertia::flash('toast', ['type' => 'success', 'message' => $this->completionMessage($result['enrolled'], $result['deferred'])]);

        return to_route('dashboard');
    }

    /**
     * A unit is only reachable on the deck the user is currently studying:
     * serving one from the other language would put its vocabulary into the
     * wrong deck on completion, which the separate-decks rule forbids.
     */
    private function authorizeUnit(Unit $unit, GetCurrentLanguage $getCurrentLanguage): void
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        abort_if($language === null || $unit->language_id !== $language->id, 404);
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

    private function isCompleted(Unit $unit): bool
    {
        return $unit->userProgress()
            ->where('user_id', $this->currentUser()->id)
            ->where('status', UnitProgressStatus::Completed)
            ->exists();
    }
}
