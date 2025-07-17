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
        Schema::table('retailer_orders', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retailer_orders', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn([
                'vendor_id', 'status', 'total_amount', 'ordered_at', 
                'confirmed_at', 'shipped_at', 'delivered_at', 'notes'
            ]);
        });
    }
};
