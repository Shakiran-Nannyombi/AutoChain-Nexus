<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analyst_manufacturer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analyst_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('manufacturer_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
            $table->unique(['analyst_id', 'manufacturer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analyst_manufacturer');
    }
}; 