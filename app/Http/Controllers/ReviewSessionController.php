<?php

namespace App\Http\Controllers;

use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Srs\PresentSrsCardForReview;
use App\Actions\Srs\ReviewSrsCard;
use App\Enums\SrsRating;
use App\Http\Requests\StoreSrsReviewRequest;
use App\Models\Language;
use App\Models\SrsCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReviewSessionController extends Controller
{
    public function index(Request $request, GetDueSrsCards $getDueSrsCards, PresentSrsCardForReview $presentCard): Response
    {
        $language = Language::active();

        if ($language === null) {
            return Inertia::render('review/Index', ['cards' => []]);
        }

        $cards = $getDueSrsCards->handle($request->user(), $language)->load('cardable');

        return Inertia::render('review/Index', [
            'cards' => $cards->map(fn (SrsCard $card): array => $presentCard->handle($card))->values(),
        ]);
    }

    public function store(StoreSrsReviewRequest $request, SrsCard $srsCard, ReviewSrsCard $reviewSrsCard): JsonResponse
    {
        abort_if($srsCard->user_id !== $request->user()->id, 403);

        $reviewSrsCard->handle($srsCard, SrsRating::from($request->validated('rating')));

        return response()->json(['status' => 'ok']);
    }
}
