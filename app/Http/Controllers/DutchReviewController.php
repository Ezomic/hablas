<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Dutch\ListDutchMeaningReview;
use App\Actions\Dutch\SuggestDutchMeaning;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\IndexDutchReviewRequest;
use App\Http\Requests\StoreDutchMeaningSuggestionRequest;
use App\Models\VocabularyItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class DutchReviewController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(IndexDutchReviewRequest $request, ListDutchMeaningReview $listDutchMeaningReview): Response
    {
        return Inertia::render('dutch-review/Index', $listDutchMeaningReview->handle($this->currentUser(), $request->languageCode(), $request->unitSlug()));
    }

    public function store(StoreDutchMeaningSuggestionRequest $request, VocabularyItem $vocabularyItem, SuggestDutchMeaning $suggestDutchMeaning): RedirectResponse
    {
        $suggestDutchMeaning->handle($this->currentUser(), $vocabularyItem, $request->suggestion(), $request->note());

        return back();
    }
}
