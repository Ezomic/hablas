<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Settings\GetUserSettings;
use App\Actions\Srs\BuildReviewSession;
use App\Actions\Srs\GradeTypedRecall;
use App\Actions\Srs\PresentSrsCardForReview;
use App\Actions\Srs\ReviewSrsCard;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\CheckTypedAnswerRequest;
use App\Http\Requests\StoreSrsReviewRequest;
use App\Models\SrsCard;
use App\Models\VocabularyItem;
use App\Services\SpeechLocaleResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ReviewSessionController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(Request $request, BuildReviewSession $buildReviewSession, PresentSrsCardForReview $presentCard, GetCurrentLanguage $getCurrentLanguage, SpeechLocaleResolver $speechLocaleResolver, GetUserSettings $getUserSettings): Response
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('review/Index', ['cards' => [], 'dueRemaining' => 0, 'speechLocale' => null]);
        }

        $session = $buildReviewSession->handle($this->currentUser(), $language);
        $mode = $getUserSettings->handle($this->currentUser())->review_mode;

        return Inertia::render('review/Index', [
            'cards' => $session['cards']->map(fn (SrsCard $card): array => $presentCard->handle($card, $mode))->values(),
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
    public function check(CheckTypedAnswerRequest $request, SrsCard $srsCard, GradeTypedRecall $gradeTypedRecall): JsonResponse
    {
        abort_if($srsCard->user_id !== $this->currentUser()->id, 404);

        $item = $srsCard->cardable;

        abort_unless($item instanceof VocabularyItem, 422);

        return response()->json(['correct' => $gradeTypedRecall->handle($item, $request->answer())]);
    }
}
