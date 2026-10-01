<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_skill_scores', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_run_id')->constrained()->cascadeOnDelete();
            $table->string('skill');
            $table->float('score');
            $table->unsignedTinyInteger('graded_count');
            $table->boolean('counts_toward_level');
            $table->timestamp('scored_at');
            $table->timestamps();

            $table->unique(['lesson_run_id', 'skill']);
            $table->index(['user_id', 'language_id', 'skill', 'scored_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_skill_scores');
    }
};
