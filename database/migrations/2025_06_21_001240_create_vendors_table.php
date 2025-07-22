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
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->string('vendor_license')->nullable();
            $table->text('product_categories')->nullable();
            $table->text('service_areas')->nullable();
            $table->text('contract_terms')->nullable();
            $table->string('segment')->nullable();
            $table->string('segment_name')->nullable();
            $table->integer('total_orders')->default(0);
            $table->integer('total_quantity')->default(0);
            $table->string('total_value', 255)->nullable();
            $table->string('most_ordered_product')->nullable();
            $table->decimal('order_frequency', 8, 2)->default(0);
            $table->decimal('fulfillment_rate', 5, 2)->default(0);
            $table->decimal('cancellation_rate', 5, 2)->default(0);
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
