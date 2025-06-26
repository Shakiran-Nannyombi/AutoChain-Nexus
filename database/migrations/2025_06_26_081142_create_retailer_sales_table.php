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
        Schema::create('retailer_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id')->constrained('retailers')->onDelete('cascade');
            $table->string('car_model');
            $table->integer('quantity_sold');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailer_sales');
    }
};
