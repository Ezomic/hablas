<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Srs\PresentSrsCardForReview;
use App\Actions\Srs\ReviewSrsCard;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\SrsRating;
use App\Http\Requests\StoreSrsReviewRequest;
use App\Models\SrsCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReviewSessionController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(Request $request, GetDueSrsCards $getDueSrsCards, PresentSrsCardForReview $presentCard, GetCurrentLanguage $getCurrentLanguage): Response
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('review/Index', ['cards' => []]);
        }

        $cards = $getDueSrsCards->handle($this->currentUser(), $language)->load('cardable');

        return Inertia::render('review/Index', [
            'cards' => $cards->map(fn (SrsCard $card): array => $presentCard->handle($card))->values(),
        ]);
    }

    public function store(StoreSrsReviewRequest $request, SrsCard $srsCard, ReviewSrsCard $reviewSrsCard): JsonResponse
    {
        abort_if($srsCard->user_id !== $this->currentUser()->id, 404);

        $reviewSrsCard->handle($srsCard, SrsRating::from($request->validated('rating')));

        return response()->json(['status' => 'ok']);
    }
}
