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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained('manufacturers')->onDelete('cascade');
            $table->string('car_name');
            $table->string('model');
            $table->string('current_stage'); // e.g., Frame, Engine, Interior, Paint, Quality Check, Completed
            $table->integer('progress')->default(0); // numeric progress e.g., 0-100
            $table->integer('efficiency')->default(0); // numeric efficiency e.g., 0-100
            $table->string('status')->default('active'); // e.g., active, maintenance, completed
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
