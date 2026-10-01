<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('open_lesson_id')->nullable();
            $table->string('kind');
            $table->string('probe_set')->nullable();
            $table->string('status');
            $table->json('plan');
            $table->unsignedInteger('seed');
            $table->boolean('counts_as_evidence')->default(false);
            $table->float('first_try_accuracy')->nullable();
            $table->json('result')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('summary_seen_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'open_lesson_id']);
            $table->index(['user_id', 'lesson_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_runs');
    }
};
