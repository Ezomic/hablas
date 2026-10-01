<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_exercises', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->unsignedSmallInteger('position');
            $table->string('block');
            $table->string('format');
            $table->string('probe_set')->nullable();
            $table->json('payload');
            $table->foreignId('substitute_for_id')->nullable()->constrained('lesson_exercises')->nullOnDelete();
            $table->char('content_hash', 64);
            $table->timestamp('retired_at')->nullable();
            $table->timestamps();

            $table->unique(['lesson_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_exercises');
    }
};
