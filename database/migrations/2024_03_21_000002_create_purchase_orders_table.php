<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'processing', 'in_transit', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total', 10, 2);
            $table->timestamp('expected_delivery');
            $table->timestamp('delivery_date')->nullable();
            $table->boolean('quality_check_passed')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}; 