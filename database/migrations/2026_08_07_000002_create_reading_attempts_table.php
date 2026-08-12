<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reading_passage_id')->constrained()->cascadeOnDelete();
            $table->json('answers');
            $table->float('score');
            $table->timestamp('attempted_at');
            $table->timestamps();

            $table->index(['user_id', 'attempted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_attempts');
    }
};
