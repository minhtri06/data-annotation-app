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
        Schema::create('entity_recognition', function (Blueprint $table) {
            $table->id();
            $table->integer('start');
            $table->integer('end');
            $table->foreignId('entity_id')->constrained('entities')
                ->onDelete('cascade');
            $table->foreignId('sample_text_id')->constrained('sample_texts')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_recognition');
    }
};
