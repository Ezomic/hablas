<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Settings\GetUserSettings;
use App\Actions\Srs\BuildReviewSession;
use App\Actions\Srs\GradeTypedRecall;
use App\Actions\Srs\PresentSrsCardsWithSpeech;
use App\Actions\Srs\ReviewSrsCard;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\CheckTypedAnswerRequest;
use App\Http\Requests\StoreSrsReviewRequest;
use App\Models\SrsCard;
use App\Models\VocabularyItem;
use App\Services\SpeechLocaleResolver;
use App\Services\TypingSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ReviewSessionController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(Request $request, BuildReviewSession $buildReviewSession, PresentSrsCardsWithSpeech $presentCards, GetCurrentLanguage $getCurrentLanguage, SpeechLocaleResolver $speechLocaleResolver, GetUserSettings $getUserSettings): Response
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('review/Index', ['cards' => [], 'dueRemaining' => 0, 'speechLocale' => null]);
        }

        $session = $buildReviewSession->handle($this->currentUser(), $language);
        $mode = $getUserSettings->handle($this->currentUser())->review_mode;

        return Inertia::render('review/Index', [
            'cards' => $presentCards->handle($language, $session['cards'], $mode),
            'dueRemaining' => $session['dueRemaining'],
            'speechLocale' => $speechLocaleResolver->forLanguage($language),
        ]);
    }

    public function store(StoreSrsReviewRequest $request, SrsCard $srsCard, ReviewSrsCard $reviewSrsCard): JsonResponse
    {
        abort_if($srsCard->user_id !== $this->currentUser()->id, 404);

        $reviewSrsCard->handle($srsCard, $request->rating(), $request->errorTagCategory());

        return response()->json(['status' => 'ok']);
    }

    /**
     * Grades a typed answer without recording anything: the learner still
     * picks the rating, which goes through store like any other review.
     */
    public function check(CheckTypedAnswerRequest $request, SrsCard $srsCard, GradeTypedRecall $gradeTypedRecall, TypingSupport $typingSupport): JsonResponse
    {
        abort_if($srsCard->user_id !== $this->currentUser()->id, 404);

        $item = $srsCard->cardable;

        abort_unless($item instanceof VocabularyItem, 422);

        $correct = $gradeTypedRecall->handle($item, $request->answer());

        $typingSupport->record($this->currentUser(), $item, $correct);

        return response()->json(['correct' => $correct]);
    }
}
