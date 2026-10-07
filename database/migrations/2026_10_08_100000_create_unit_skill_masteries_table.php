<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_skill_masteries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('skill');
            $table->foreignId('lesson_run_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('mastered_at');
            $table->timestamps();

            $table->unique(['user_id', 'unit_id', 'skill']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_skill_masteries');
    }
};
