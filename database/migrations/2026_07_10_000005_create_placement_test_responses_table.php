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
        Schema::create('placement_test_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('placement_test_attempts')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('placement_test_items')->cascadeOnDelete();
            $table->string('skill');
            $table->string('response');
            $table->boolean('is_correct');
            $table->string('tier_at_time');
            $table->timestamp('answered_at');
            $table->timestamps();

            $table->unique(['attempt_id', 'item_id']);
            $table->index(['attempt_id', 'skill']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_test_responses');
    }
};
