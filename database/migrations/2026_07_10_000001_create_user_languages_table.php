<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'language_id']);
        });

        $this->backfillExistingUsers();
    }

    /**
     * Every existing user was implicitly relying on the global is_active
     * flag (via Language::active()) rather than an explicit per-user
     * unlock. Preserve their current access: attach their current_language
     * if set, plus every currently-active language, so no one loses access
     * to a language they were already using once is_active is dropped.
     */
    private function backfillExistingUsers(): void
    {
        $now = now();

        DB::table('users')
            ->whereNotNull('current_language_id')
            ->select('id', 'current_language_id')
            ->get()
            ->each(function (object $user) use ($now) {
                DB::table('user_languages')->insertOrIgnore([
                    'user_id' => $user->id,
                    'language_id' => $user->current_language_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });

        $activeLanguageIds = DB::table('languages')->where('is_active', true)->pluck('id');

        DB::table('users')->select('id')->get()->each(function (object $user) use ($now, $activeLanguageIds) {
            foreach ($activeLanguageIds as $languageId) {
                DB::table('user_languages')->insertOrIgnore([
                    'user_id' => $user->id,
                    'language_id' => $languageId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_languages');
    }
};
