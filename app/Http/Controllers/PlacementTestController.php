<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\NotifyOnBlendedLevelIncrease;
use App\Actions\Placement\FinalizePlacementAttempt;
use App\Actions\Placement\GetCurrentPlacementItem;
use App\Actions\Placement\GetOrCreateInProgressPlacementAttempt;
use App\Actions\Placement\RecordPlacementResponse;
use App\Actions\Placement\SkipPlacementTest;
use App\Http\Requests\AnswerPlacementItemRequest;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlacementTestController extends Controller
{
    public function index(
        Request $request,
        GetCurrentLanguage $getCurrentLanguage,
        GetOrCreateInProgressPlacementAttempt $getOrCreateInProgressPlacementAttempt,
        GetCurrentPlacementItem $getCurrentPlacementItem,
        FinalizePlacementAttempt $finalizePlacementAttempt,
        NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease,
    ): Response|RedirectResponse {
        $language = $getCurrentLanguage->handle($request->user()) ?? abort(404);

        $attempt = $getOrCreateInProgressPlacementAttempt->handle($request->user(), $language);
        $item = $getCurrentPlacementItem->handle($attempt);

        if ($item === null) {
            // Every skill's staircase is already done but the attempt was
            // never finalized (e.g. the process died between the last
            // answer and finalization) — finish it now, via the same
            // notifier-wrapped path answer() uses, so a level-up here still
            // shows the milestone toast rather than finalizing silently.
            $notifyOnBlendedLevelIncrease->handle(
                $request->user(),
                $language,
                fn () => $finalizePlacementAttempt->handle($attempt),
            );

            return redirect()->route('dashboard');
        }

        return Inertia::render('placement/Index', [
            'item' => $this->serializeItem($item),
            'language' => ['code' => $language->code, 'name' => $language->name],
        ]);
    }

    public function answer(
        AnswerPlacementItemRequest $request,
        PlacementTestItem $item,
        GetCurrentLanguage $getCurrentLanguage,
        GetCurrentPlacementItem $getCurrentPlacementItem,
        RecordPlacementResponse $recordPlacementResponse,
        FinalizePlacementAttempt $finalizePlacementAttempt,
        NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease,
    ): JsonResponse {
        $language = $getCurrentLanguage->handle($request->user()) ?? abort(404);

        $attempt = PlacementTestAttempt::query()
            ->where('user_id', $request->user()->id)
            ->where('language_id', $language->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        // The item being answered must be the server-computed "current" item
        // for this attempt — rejects a spoofed item id and, since an already
        // -recorded item is never "current" again, also naturally rejects a
        // duplicate submission of the same item.
        $expected = $getCurrentPlacementItem->handle($attempt);
        abort_unless($expected?->id === $item->id, 409);

        try {
            $recordPlacementResponse->handle($attempt, $item, $request->validated('response'));
        } catch (UniqueConstraintViolationException) {
            // A second, near-simultaneous submission for the same item lost
            // the race to the unique(attempt_id, item_id) constraint — treat
            // it the same as the spoofing/staleness guard above rather than
            // surfacing a 500.
            abort(409);
        }

        $next = $getCurrentPlacementItem->handle($attempt);

        if ($next === null) {
            $notifyOnBlendedLevelIncrease->handle(
                $request->user(),
                $language,
                fn () => $finalizePlacementAttempt->handle($attempt),
            );

            return response()->json(['done' => true]);
        }

        return response()->json([
            'done' => false,
            'item' => $this->serializeItem($next),
        ]);
    }

    public function skip(Request $request, SkipPlacementTest $skipPlacementTest, GetCurrentLanguage $getCurrentLanguage): RedirectResponse
    {
        $language = $getCurrentLanguage->handle($request->user()) ?? abort(404);

        $skipPlacementTest->handle($request->user(), $language);

        return redirect()->route('dashboard');
    }

    /** @return array{id: int, skill: string, prompt: string, options: array<int, string>} */
    private function serializeItem(PlacementTestItem $item): array
    {
        return [
            'id' => $item->id,
            'skill' => $item->skill->value,
            'prompt' => $item->prompt,
            'options' => $item->options,
        ];
    }
}
