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
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('has_label_sets');
            $table->integer('fixed_number_of_label_sets');
            $table->integer('maximum_of_label_sets');
            $table->boolean('has_generated_texts');
            $table->integer('fixed_number_of_generated_texts');
            $table->integer('maximum_of_generated_texts');
            $table->boolean('has_entity_recognition');
            $table->string('text_titles');
            $table->string('generated_text_titles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_types');
    }
};
