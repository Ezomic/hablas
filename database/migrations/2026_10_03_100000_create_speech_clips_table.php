<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speech_clips', function (Blueprint $table): void {
            $table->id();
            $table->string('language', 5);
            $table->string('voice_id', 64);
            $table->string('speed', 16);
            $table->char('hash', 64)->unique();
            $table->unsignedInteger('bytes')->default(0);
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();

            $table->index(['language', 'voice_id', 'speed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speech_clips');
    }
};
