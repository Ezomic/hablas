<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StorePushSubscriptionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(StorePushSubscriptionRequest $request): JsonResponse
    {
        $request->user()->updatePushSubscription(
            endpoint: $request->validated('endpoint'),
            key: $request->validated('keys.p256dh'),
            token: $request->validated('keys.auth'),
        );

        return response()->json(['subscribed' => true]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['endpoint' => ['required', 'string', 'max:500']]);

        $request->user()->deletePushSubscription($request->string('endpoint')->toString());

        return response()->json(['subscribed' => false]);
    }
}
