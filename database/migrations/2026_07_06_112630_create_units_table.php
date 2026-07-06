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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('cefr_level');
            $table->string('context_tag');
            $table->string('primary_skill');
            $table->string('secondary_skill')->nullable();
            $table->string('title');
            $table->text('task_description');
            $table->unsignedInteger('sort_order');
            $table->timestamps();

            $table->unique(['language_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
