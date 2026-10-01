<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The tier within the level ("A2.2"), so practice can move one tier at a
     * time. Null where a level has no tiers (C1, C2).
     */
    public function up(): void
    {
        Schema::table('user_skill_levels', function (Blueprint $table) {
            $table->string('sub_level')->nullable()->after('cefr_level');
        });
    }

    public function down(): void
    {
        Schema::table('user_skill_levels', function (Blueprint $table) {
            $table->dropColumn('sub_level');
        });
    }
};
