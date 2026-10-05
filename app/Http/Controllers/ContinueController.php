<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Units\SelectContinueUnit;
use App\Concerns\InteractsWithCurrentUser;
use Illuminate\Http\RedirectResponse;

final class ContinueController extends Controller
{
    use InteractsWithCurrentUser;

    public function __invoke(GetCurrentLanguage $getCurrentLanguage, SelectContinueUnit $selectContinueUnit): RedirectResponse
    {
        $language = $getCurrentLanguage->handle($this->currentUser());
        $unit = $language === null ? null : $selectContinueUnit->handle($this->currentUser(), $language);

        return $unit === null ? redirect()->route('dashboard') : redirect()->route('units.show', $unit);
    }
}
