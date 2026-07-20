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
            endpoint: $request->validated('endpoint'),
            key: $request->validated('keys.p256dh'),
            token: $request->validated('keys.auth'),
        );

        return response()->json(['subscribed' => true]);
    }

    public function destroy(DestroyPushSubscriptionRequest $request): JsonResponse
    {
        $this->currentUser()->deletePushSubscription($request->validated('endpoint'));

        return response()->json(['subscribed' => false]);
    }
}
