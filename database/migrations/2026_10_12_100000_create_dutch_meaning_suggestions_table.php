<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dutch_meaning_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vocabulary_item_id')->constrained()->cascadeOnDelete();
            $table->string('suggestion')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'vocabulary_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dutch_meaning_suggestions');
    }
};
