<?php

namespace App\Actions\Progress;

use App\Models\ProgressShare;

class RevokeProgressShare
{
    public function handle(ProgressShare $share): void
    {
        $share->forceFill(['revoked_at' => now()])->save();
    }
}
