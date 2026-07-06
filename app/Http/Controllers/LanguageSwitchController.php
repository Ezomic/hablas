<?php

namespace App\Http\Controllers;

use App\Actions\Languages\SwitchCurrentLanguage;
use App\Http\Requests\UpdateCurrentLanguageRequest;
use Illuminate\Http\RedirectResponse;

class LanguageSwitchController extends Controller
{
    public function update(UpdateCurrentLanguageRequest $request, SwitchCurrentLanguage $switchCurrentLanguage): RedirectResponse
    {
        $switchCurrentLanguage->handle($request->user(), $request->validated('language_id'));

        return back();
    }
}
