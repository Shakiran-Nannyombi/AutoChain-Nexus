<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->unsignedBigInteger('retailer_order_id')->nullable()->index();
            $table->string('status')->nullable();
            $table->string('driver')->nullable();
            $table->string('destination')->nullable();
            $table->integer('progress')->nullable();
            $table->dateTime('eta')->nullable();
            $table->json('tracking_history')->nullable();
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['retailer_order_id', 'status', 'driver', 'destination', 'progress', 'eta', 'tracking_history']);
        });
    }
}; 