<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Settings\UpdateInterfaceLocale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateInterfaceLocaleRequest;
use Illuminate\Http\RedirectResponse;

final class InterfaceLocaleController extends Controller
{
    public function update(UpdateInterfaceLocaleRequest $request, UpdateInterfaceLocale $updateInterfaceLocale): RedirectResponse
    {
        $updateInterfaceLocale->handle($request->user(), $request->string('interface_locale')->toString());

        return back();
    }
}
