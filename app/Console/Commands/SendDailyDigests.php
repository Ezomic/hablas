<?php

namespace App\Console\Commands;

use App\Actions\Digests\SendDigestToUser;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('digests:send')]
#[Description('Send the daily/weekly email digest to users based on their notification frequency preference')]
class SendDailyDigests extends Command
{
    public function handle(SendDigestToUser $sendDigestToUser): int
    {
        User::query()->chunkById(50, function ($users) use ($sendDigestToUser) {
            $users->each(fn (User $user) => $sendDigestToUser->handle($user));
        });

        return self::SUCCESS;
    }
}
