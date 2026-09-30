<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A skip can finish an attempt that already has answers, so the answers
     * alone cannot tell a skip from a placement the learner took.
     */
    public function up(): void
    {
        Schema::table('placement_test_attempts', function (Blueprint $table) {
            $table->boolean('skipped')->default(false)->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('placement_test_attempts', function (Blueprint $table) {
            $table->dropColumn('skipped');
        });
    }
};
