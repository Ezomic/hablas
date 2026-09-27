<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Existing codes do not record the address they were sent to, so the proof
     * is reconstructed from two facts. The newest code of a purpose can only
     * have been consumed by being used, because requesting a code consumes the
     * older ones instead. And a code created after the user row was last
     * written went to the address the row still holds, because changing the
     * email writes the row. Anything less certain stays unproven.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->whereExists(function (Builder $query): void {
                $query->from('login_codes', 'used')
                    ->whereColumn('used.user_id', 'users.id')
                    ->whereNotNull('used.consumed_at')
                    ->whereColumn('used.created_at', '>', 'users.updated_at')
                    ->whereNotExists(function (Builder $newer): void {
                        $newer->from('login_codes', 'newer')
                            ->whereColumn('newer.user_id', 'used.user_id')
                            ->whereColumn('newer.purpose', 'used.purpose')
                            ->whereColumn('newer.id', '>', 'used.id');
                    });
            })
            ->update(['email_verified_at' => Date::now()]);
    }
};
