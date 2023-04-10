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
        Schema::create('project_label_set', function (Blueprint $table) {
            $table->id();
            $table->boolean('pick_one');
            $table->foreignId('project_id')->constrained('projects')
                ->onDelete('cascade');
            $table->foreignId('label_set_id')->constrained('label_sets')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_label_set');
    }
};
