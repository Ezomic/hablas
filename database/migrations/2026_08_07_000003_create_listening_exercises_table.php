<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listening_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cefr_level');
            $table->string('title');
            // Spoken by the browser rather than shown, so the learner is
            // listening rather than reading. A recorded audio_url takes over
            // when one exists.
            $table->text('transcript');
            $table->string('audio_url')->nullable();
            $table->json('questions');
            $table->timestamps();

            $table->index(['language_id', 'cefr_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listening_exercises');
    }
};
