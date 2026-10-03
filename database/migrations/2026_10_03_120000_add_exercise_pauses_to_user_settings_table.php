<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_settings', function (Blueprint $table): void {
            $table->timestamp('listening_paused_until')->nullable();
            $table->timestamp('speaking_paused_until')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table): void {
            $table->dropColumn(['listening_paused_until', 'speaking_paused_until']);
        });
    }
};
