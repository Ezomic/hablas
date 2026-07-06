<?php

namespace App\Http\Controllers;

use App\Actions\Languages\SwitchCurrentLanguage;
use App\Http\Requests\UpdateCurrentLanguageRequest;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;

class LanguageSwitchController extends Controller
{
    public function update(UpdateCurrentLanguageRequest $request, SwitchCurrentLanguage $switchCurrentLanguage): RedirectResponse
    {
        $language = Language::query()->where('id', $request->validated('language_id'))->firstOrFail();

        $switchCurrentLanguage->handle($request->user(), $language);

        return back();
    }
}
