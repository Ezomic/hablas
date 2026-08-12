<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Srs\GetWeakSpotCards;
use App\Actions\Srs\PresentSrsCardForReview;
use App\Actions\Srs\ResolveWeakSpot;
use App\Actions\Srs\ReviewSrsCard;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\SrsRating;
use App\Http\Requests\StoreSrsReviewRequest;
use App\Models\SrsCard;
use App\Services\SpeechLocaleResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeakSpotReviewController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(Request $request, GetWeakSpotCards $getWeakSpotCards, PresentSrsCardForReview $presentCard, GetCurrentLanguage $getCurrentLanguage, SpeechLocaleResolver $speechLocaleResolver): Response
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('review/WeakSpots', ['cards' => [], 'speechLocale' => null]);
        }

        $cards = $getWeakSpotCards->handle($this->currentUser(), $language)->load('cardable');

        return Inertia::render('review/WeakSpots', [
            'cards' => $cards->map(fn (SrsCard $card): array => $presentCard->handle($card))->values(),
            'speechLocale' => $speechLocaleResolver->forLanguage($language),
        ]);
    }

    public function store(StoreSrsReviewRequest $request, SrsCard $srsCard, ReviewSrsCard $reviewSrsCard, ResolveWeakSpot $resolveWeakSpot): JsonResponse
    {
        abort_if($srsCard->user_id !== $this->currentUser()->id, 404);

        $rating = $request->rating();

        $reviewSrsCard->handle($srsCard, $rating, $request->errorTagCategory());

        // A non-Again review clears the remedial drill and re-admits the card to
        // the normal FSRS rotation; an Again leaves it benched for another pass.
        if ($rating !== SrsRating::Again) {
            $resolveWeakSpot->handle($srsCard);
        }

        return response()->json(['status' => 'ok']);
    }
}
