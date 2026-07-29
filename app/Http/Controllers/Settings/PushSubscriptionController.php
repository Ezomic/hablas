<?php

namespace App\Http\Controllers\Settings;

use App\Concerns\InteractsWithCurrentUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DestroyPushSubscriptionRequest;
use App\Http\Requests\Settings\StorePushSubscriptionRequest;
use Illuminate\Http\JsonResponse;

class PushSubscriptionController extends Controller
{
    use InteractsWithCurrentUser;

    public function store(StorePushSubscriptionRequest $request): JsonResponse
    {
        $this->currentUser()->updatePushSubscription(
            endpoint: $request->string('endpoint')->toString(),
            key: $request->string('keys.p256dh')->toString() ?: null,
            token: $request->string('keys.auth')->toString() ?: null,
        );

        return response()->json(['subscribed' => true]);
    }

    public function destroy(DestroyPushSubscriptionRequest $request): JsonResponse
    {
        $this->currentUser()->deletePushSubscription($request->string('endpoint')->toString());

        return response()->json(['subscribed' => false]);
    }
}
