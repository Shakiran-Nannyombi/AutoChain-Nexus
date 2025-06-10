<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'delivery_date')) {
                $table->timestamp('delivery_date')->nullable()->after('expected_delivery');
            }
            if (!Schema::hasColumn('purchase_orders', 'quality_check_passed')) {
                $table->boolean('quality_check_passed')->nullable()->after('delivery_date');
            }
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'delivery_date')) {
                $table->dropColumn('delivery_date');
            }
            if (Schema::hasColumn('purchase_orders', 'quality_check_passed')) {
                $table->dropColumn('quality_check_passed');
            }
        });
    }
}; 