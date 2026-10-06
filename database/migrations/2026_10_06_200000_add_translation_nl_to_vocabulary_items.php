<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vocabulary_items', function (Blueprint $table) {
            $table->string('translation_nl')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('vocabulary_items', function (Blueprint $table) {
            $table->dropColumn('translation_nl');
        });
    }
};
