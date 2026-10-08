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
            // [{speaker, text}], the body split by who says it, read in a voice per speaker.
            $table->json('segments')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reading_passages', function (Blueprint $table) {
            $table->dropColumn('segments');
        });
    }
};
