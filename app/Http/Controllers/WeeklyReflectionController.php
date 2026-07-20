<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Reflections\GetCanDoStatementsForReflection;
use App\Actions\Reflections\HasSubmittedReflectionThisWeek;
use App\Actions\Reflections\SubmitWeeklyReflection;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\StoreWeeklyReflectionRequest;
use App\Models\CefrCanDoStatement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyReflectionController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(
        Request $request,
        GetCanDoStatementsForReflection $getCanDoStatements,
        HasSubmittedReflectionThisWeek $hasSubmittedThisWeek,
        GetCurrentLanguage $getCurrentLanguage,
    ): Response {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('reflections/Index', ['statements' => [], 'submittedThisWeek' => false]);
        }

        if ($hasSubmittedThisWeek->handle($this->currentUser(), $language)) {
            return Inertia::render('reflections/Index', ['statements' => [], 'submittedThisWeek' => true]);
        }

        $statements = $getCanDoStatements->handle($this->currentUser(), $language);

        return Inertia::render('reflections/Index', [
            'statements' => $statements->map(fn (CefrCanDoStatement $statement): array => [
                'id' => $statement->id,
                'skill' => $statement->skill->value,
                'statement_text' => $statement->statement_text,
            ]),
            'submittedThisWeek' => false,
        ]);
    }

    public function store(StoreWeeklyReflectionRequest $request, SubmitWeeklyReflection $submitWeeklyReflection, GetCurrentLanguage $getCurrentLanguage): RedirectResponse
    {
        $language = $getCurrentLanguage->handle($this->currentUser()) ?? abort(404);

        $submitWeeklyReflection->handle(
            $this->currentUser(),
            $language,
            $request->validated('statement_ids'),
            $request->validated('can_do_ids', []),
        );

        return redirect()->route('reflections.index');
    }
}
