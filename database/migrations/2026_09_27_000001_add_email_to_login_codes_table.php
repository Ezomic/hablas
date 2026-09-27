<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A used code proves the inbox it was sent to, which is only the account's
     * current address if the address has not changed since. Codes issued before
     * this column existed stay null and prove nothing on their own.
     */
    public function up(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            $table->string('email')->nullable()->after('code_hash');
        });
    }

    public function down(): void
    {
        Schema::table('login_codes', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
