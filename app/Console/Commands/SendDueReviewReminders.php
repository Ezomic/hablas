<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Notifications\SendDueReviewReminder;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('reviews:remind')]
#[Description('Push a reminder to learners whose due reviews have crossed the threshold since they last reviewed or were told')]
class SendDueReviewReminders extends Command
{
    public function handle(SendDueReviewReminder $sendDueReviewReminder): int
    {
        $sent = 0;

        User::query()->whereHas('pushSubscriptions')->chunkById(50, function ($users) use ($sendDueReviewReminder, &$sent) {
            $users->each(function (User $user) use ($sendDueReviewReminder, &$sent) {
                $sent += $sendDueReviewReminder->handle($user) ? 1 : 0;
            });
        });

        $this->info("Sent {$sent} due-review ".Str::plural('reminder', $sent).'.');

        return self::SUCCESS;
    }
}
