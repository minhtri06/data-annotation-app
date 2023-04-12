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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->boolean('has_label_sets');
            $table->boolean('has_entity_recognition');
            $table->boolean('has_generated_text');
            $table->integer('number_of_generated_texts')->nullable();
            $table->integer('maximum_of_generated_texts')->nullable();
            $table->string('text_titles');
            $table->integer('number_of_texts')->min(1);
            $table->string('generated_text_titles'); // text 1, text 2, text 3
            $table->integer('maximum_performer')->min(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
