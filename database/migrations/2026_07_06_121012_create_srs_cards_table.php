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
        Schema::create('srs_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->morphs('cardable');
            $table->string('state');
            $table->float('stability')->default(0);
            $table->float('difficulty')->default(0);
            $table->unsignedInteger('reps')->default(0);
            $table->unsignedInteger('lapses')->default(0);
            $table->unsignedInteger('consecutive_lapses')->default(0);
            $table->boolean('is_weak_spot')->default(false);
            $table->timestamp('due_at');
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'cardable_type', 'cardable_id']);
            $table->index(['user_id', 'language_id', 'is_weak_spot', 'due_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srs_cards');
    }
};
