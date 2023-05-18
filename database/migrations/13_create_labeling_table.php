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
        Schema::create('labeling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_id')->constrained('labels')
                ->onDelete('cascade');
            $table->foreignId('sample_id')->constrained('samples')
                ->onDelete('cascade');
            $table->foreignId('performer_id')->nullable()->constrained('users')
                ->onDelete('set null');
            $table->timestamps();
            $table->unique(['label_id', 'sample_id', 'performer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labeling');
    }
};
