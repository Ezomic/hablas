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
        Schema::create('cefr_can_do_statements', function (Blueprint $table) {
            $table->id();
            $table->string('cefr_level');
            $table->string('skill');
            $table->text('statement_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cefr_can_do_statements');
    }
};
