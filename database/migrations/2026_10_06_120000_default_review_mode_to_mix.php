<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->string('review_mode')->default('mix')->change();
        });

        DB::table('user_settings')->where('review_mode', 'recognition')->update(['review_mode' => 'mix']);
    }

    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->string('review_mode')->default('recognition')->change();
        });
    }
};
