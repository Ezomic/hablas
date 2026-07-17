<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Google sign-in is gone; an emailed code and passkeys cover passwordless
     * sign-in without the external dependency.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // The column was added with ->unique(); SQLite refuses to drop a
            // column that an index still references, so the index goes first.
            $table->dropUnique('users_google_id_unique');
            $table->dropColumn('google_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique();
        });
    }
};
