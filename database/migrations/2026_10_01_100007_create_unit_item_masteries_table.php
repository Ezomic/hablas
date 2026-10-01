<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_item_masteries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('masterable_type');
            $table->unsignedBigInteger('masterable_id');
            $table->string('scope');
            $table->foreignId('lesson_run_id')->constrained()->cascadeOnDelete();
            $table->timestamp('mastered_at');
            $table->timestamps();

            $table->unique(['user_id', 'masterable_type', 'masterable_id', 'scope'], 'unit_item_masteries_item_scope_unique');
            $table->index(['user_id', 'unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_item_masteries');
    }
};
