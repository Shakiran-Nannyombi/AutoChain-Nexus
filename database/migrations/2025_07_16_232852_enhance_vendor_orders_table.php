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
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('product');
            $table->string('product_category')->nullable()->after('product_name');
            $table->decimal('unit_price', 10, 2)->nullable()->after('quantity');
            $table->decimal('total_amount', 10, 2)->nullable()->after('unit_price');
            $table->timestamp('accepted_at')->nullable()->after('ordered_at');
            $table->timestamp('rejected_at')->nullable()->after('accepted_at');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            $table->text('notes')->nullable()->after('rejection_reason');
            $table->string('delivery_address')->nullable()->after('notes');
            $table->date('expected_delivery_date')->nullable()->after('delivery_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->dropColumn([
                'product_name', 'product_category', 'unit_price', 'total_amount',
                'accepted_at', 'rejected_at', 'rejection_reason', 'notes',
                'delivery_address', 'expected_delivery_date'
            ]);
        });
    }
};
