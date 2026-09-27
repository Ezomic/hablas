<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * A user provisioned by id-client arrives with an address ID already proved.
     */
    public function creating(User $user): void
    {
        if ($user->idp_id !== null) {
            $user->forceFill(['email_verified_at' => Date::now()]);
        }
    }

    /**
     * id-client links a first ID sign-in to the row that holds the same email.
     * If nobody ever proved that address here, or the row belonged to another
     * ID user, whoever set the row up may not be the person ID just vouched
     * for, so every local way in goes before the ID user takes it over.
     */
    public function updating(User $user): void
    {
        if (! $user->isDirty('idp_id') || $user->idp_id === null) {
            return;
        }

        if ($user->email_verified_at !== null && $user->getOriginal('idp_id') === null) {
            return;
        }

        $this->revokeLocalSignIns($user);

        $user->forceFill(['email_verified_at' => Date::now()]);
    }

    /**
     * An outstanding code may have gone to an inbox the row held before, so it
     * goes too. Deleting the sessions cannot end the callback's own sign-in:
     * this runs while id-client saves the user, before Auth::login, and login
     * moves the session to a fresh id that is only written when the request ends.
     */
    private function revokeLocalSignIns(User $user): void
    {
        $user->passkeys()->delete();

        LoginCode::query()
            ->where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => Date::now()]);

        $connection = Config::get('session.connection');

        DB::connection(is_string($connection) ? $connection : null)
            ->table(Config::string('session.table'))
            ->where('user_id', $user->id)
            ->delete();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'remember_token' => Str::random(60),
        ]);
    }
}
