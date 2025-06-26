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
        Schema::create('retailer_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id')->constrained('retailers')->onDelete('cascade');
            $table->string('car_model');
            $table->string('vendor_name');
            $table->integer('quantity_received');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailer_stocks');
    }
};
