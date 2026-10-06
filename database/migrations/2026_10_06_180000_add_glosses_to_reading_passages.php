<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reading_passages', function (Blueprint $table) {
            // {word as written in the body: English meaning}, shown on tap.
            $table->json('glosses')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reading_passages', function (Blueprint $table) {
            $table->dropColumn('glosses');
        });
    }
};
