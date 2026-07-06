<?php

namespace App\Http\Controllers;

use App\Actions\Reflections\GetCanDoStatementsForReflection;
use App\Actions\Reflections\SubmitWeeklyReflection;
use App\Http\Requests\StoreWeeklyReflectionRequest;
use App\Models\CefrCanDoStatement;
use App\Models\Language;
use App\Models\WeeklyReflection;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyReflectionController extends Controller
{
    public function index(Request $request, GetCanDoStatementsForReflection $getCanDoStatements): Response
    {
        $language = Language::active();

        if ($language === null) {
            return Inertia::render('reflections/Index', ['statements' => [], 'submittedThisWeek' => false]);
        }

        $alreadySubmitted = WeeklyReflection::query()
            ->where('user_id', $request->user()->id)
            ->where('language_id', $language->id)
            ->where('week_start_date', CarbonImmutable::now()->startOfWeek())
            ->whereNotNull('submitted_at')
            ->exists();

        if ($alreadySubmitted) {
            return Inertia::render('reflections/Index', ['statements' => [], 'submittedThisWeek' => true]);
        }

        $statements = $getCanDoStatements->handle($request->user(), $language);

        return Inertia::render('reflections/Index', [
            'statements' => $statements->map(fn (CefrCanDoStatement $statement): array => [
                'id' => $statement->id,
                'skill' => $statement->skill->value,
                'statement_text' => $statement->statement_text,
            ]),
            'submittedThisWeek' => false,
        ]);
    }

    public function store(StoreWeeklyReflectionRequest $request, SubmitWeeklyReflection $submitWeeklyReflection): RedirectResponse
    {
        $language = Language::active() ?? abort(404);

        $submitWeeklyReflection->handle(
            $request->user(),
            $language,
            $request->validated('statement_ids'),
            $request->validated('can_do_ids', []),
        );

        return redirect()->route('reflections.index');
    }
}
