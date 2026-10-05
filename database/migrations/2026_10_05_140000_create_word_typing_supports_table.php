<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('word_typing_supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vocabulary_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('revealed');
            $table->timestamps();

            $table->unique(['user_id', 'vocabulary_item_id']);
        });
    }
};
