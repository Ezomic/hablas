<?php

use App\Actions\Progress\RevokeProgressShare;
use App\Models\ProgressShare;

it('sets revoked_at on the share', function () {
    $share = ProgressShare::factory()->create(['revoked_at' => null]);

    (new RevokeProgressShare)->handle($share);

    expect($share->fresh()->revoked_at)->not->toBeNull();
});
