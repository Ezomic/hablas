<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pronunciation_drill_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pronunciation_drill_exercise_id')->constrained()->cascadeOnDelete();
            $table->text('transcript_guess');
            $table->boolean('is_correct');
            $table->float('score');
            $table->timestamp('attempted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pronunciation_drill_attempts');
    }
};
