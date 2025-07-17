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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->string('company');
            $table->text('address');
            $table->string('profile_picture')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->string('vendor_license')->nullable();
            $table->text('product_categories')->nullable();
            $table->text('service_areas')->nullable();
            $table->string('contract_terms')->nullable();
            $table->string('segment')->default('Unsegmented'); // Vendor segmentation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
