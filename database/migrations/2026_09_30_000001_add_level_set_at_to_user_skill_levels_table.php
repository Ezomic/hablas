<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Practice may only raise a level on attempts made since the level was
     * last set, so the row records when that was. updated_at cannot stand in:
     * a placement that measures the same level again does not write the row.
     */
    public function up(): void
    {
        Schema::table('user_skill_levels', function (Blueprint $table) {
            $table->timestamp('level_set_at')->nullable()->after('cefr_level');
        });
    }

    public function down(): void
    {
        Schema::table('user_skill_levels', function (Blueprint $table) {
            $table->dropColumn('level_set_at');
        });
    }
};
