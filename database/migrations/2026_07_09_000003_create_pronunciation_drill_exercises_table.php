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
        Schema::create('pronunciation_drill_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('word_a');
            $table->string('word_a_translation_en');
            $table->string('word_b');
            $table->string('word_b_translation_en');
            $table->string('target_word');
            $table->string('audio_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pronunciation_drill_exercises');
    }
};
