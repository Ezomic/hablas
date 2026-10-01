<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_answers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('lesson_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_exercise_id')->constrained()->cascadeOnDelete();
            $table->uuid('step')->unique();
            $table->unsignedTinyInteger('attempt');
            $table->boolean('hinted')->default(false);
            $table->boolean('skipped')->default(false);
            $table->string('skip_reason')->nullable();
            $table->json('response')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->boolean('self_graded_correct')->nullable();
            $table->float('score')->nullable();
            $table->string('note')->nullable();
            $table->string('error_tag_category')->nullable();
            $table->timestamp('flagged_at')->nullable();
            $table->timestamp('answered_at');
            $table->timestamps();

            $table->unique(['lesson_run_id', 'lesson_exercise_id', 'attempt']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_answers');
    }
};
