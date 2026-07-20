<?php

namespace App\Http\Controllers;

use App\Actions\Languages\SwitchCurrentLanguage;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\UpdateCurrentLanguageRequest;
use Illuminate\Http\RedirectResponse;

class LanguageSwitchController extends Controller
{
    use InteractsWithCurrentUser;

    public function update(UpdateCurrentLanguageRequest $request, SwitchCurrentLanguage $switchCurrentLanguage): RedirectResponse
    {
        $switchCurrentLanguage->handle($this->currentUser(), $request->validated('language_id'));

        return back();
    }
}
