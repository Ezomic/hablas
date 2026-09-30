<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A re-take places one skill; null is the full test over all four, which
     * is what every attempt before this column was.
     */
    public function up(): void
    {
        Schema::table('placement_test_attempts', function (Blueprint $table) {
            $table->string('skill')->nullable()->after('language_id');
        });
    }

    public function down(): void
    {
        Schema::table('placement_test_attempts', function (Blueprint $table) {
            $table->dropColumn('skill');
        });
    }
};
