<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_passages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cefr_level');
            $table->string('title');
            $table->text('body');
            // [{prompt, options: [...], correct_answer}], the same shape
            // placement items use for their multiple choice.
            $table->json('questions');
            $table->timestamps();

            $table->index(['language_id', 'cefr_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_passages');
    }
};
