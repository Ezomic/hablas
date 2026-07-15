<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Srs\GetWeakSpotCards;
use App\Actions\Srs\PresentSrsCardForReview;
use App\Actions\Srs\ResolveWeakSpot;
use App\Actions\Srs\ReviewSrsCard;
use App\Enums\SrsRating;
use App\Http\Requests\StoreSrsReviewRequest;
use App\Models\SrsCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeakSpotReviewController extends Controller
{
    public function index(Request $request, GetWeakSpotCards $getWeakSpotCards, PresentSrsCardForReview $presentCard, GetCurrentLanguage $getCurrentLanguage): Response
    {
        $language = $getCurrentLanguage->handle($request->user());

        if ($language === null) {
            return Inertia::render('review/WeakSpots', ['cards' => []]);
        }

        $cards = $getWeakSpotCards->handle($request->user(), $language)->load('cardable');

        return Inertia::render('review/WeakSpots', [
            'cards' => $cards->map(fn (SrsCard $card): array => $presentCard->handle($card))->values(),
        ]);
    }

    public function store(StoreSrsReviewRequest $request, SrsCard $srsCard, ReviewSrsCard $reviewSrsCard, ResolveWeakSpot $resolveWeakSpot): JsonResponse
    {
        abort_if($srsCard->user_id !== $request->user()->id, 404);

        $rating = SrsRating::from($request->validated('rating'));

        $reviewSrsCard->handle($srsCard, $rating);

        // A non-Again review clears the remedial drill and re-admits the card to
        // the normal FSRS rotation; an Again leaves it benched for another pass.
        if ($rating !== SrsRating::Again) {
            $resolveWeakSpot->handle($srsCard);
        }

        return response()->json(['status' => 'ok']);
    }
}
