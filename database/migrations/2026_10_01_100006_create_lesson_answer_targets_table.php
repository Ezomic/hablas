<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_answer_targets', function (Blueprint $table): void {
            $table->foreignId('lesson_answer_id')->constrained()->cascadeOnDelete();
            $table->string('targetable_type');
            $table->unsignedBigInteger('targetable_id');
            $table->boolean('is_correct');

            $table->primary(['lesson_answer_id', 'targetable_type', 'targetable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_answer_targets');
    }
};
